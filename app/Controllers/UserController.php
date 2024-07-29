<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json';

    public function fetch()
    {
        $page = $this->request->getGet('page');
        if (!$page) {
            return $this->fail('Missing query parameter: page', ResponseInterface::HTTP_BAD_REQUEST);
        }

        $url = "https://reqres.in/api/users?page=$page";
        $response = file_get_contents($url);
        $data = json_decode($response, true)['data'];

        $model = new \App\Models\UserModel();
        $fetchedData = [];
        foreach ($data as $user) {
            $user['created_at'] = date('Y-m-d H:i:s');
            $user['updated_at'] = date('Y-m-d H:i:s');
            $user['deleted_at'] = null;

            if (!$model->where('id', $user['id'])->first()) {
                $model->insert($user);
            }

            $fetchedData[] = $user;
        }

        return $this->respond($fetchedData);
    }

    public function getUser($id = null)
    {
        $model = new \App\Models\UserModel();
        $user = $model->where('deleted_at', null)->find($id);

        if (!$user) {
            return $this->failNotFound('User not found');
        }

        return $this->respond($user);
    }

    public function index()
    {
        $model = new \App\Models\UserModel();
        $data = $model->where('deleted_at', null)->findAll();
        return $this->respond($data);
    }

    public function create()
    {
        $model = new \App\Models\UserModel();
        $data = $this->request->getJSON(true);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['deleted_at'] = null;
        $model->insert($data);

        return $this->respondCreated($data);
    }

    public function update($id = null)
    {
        $model = new \App\Models\UserModel();

        $existingData = $model->find($id);
        if ($existingData && $existingData['deleted_at'] === null) {
            $data = $this->request->getJSON(true);
            $data['updated_at'] = date('Y-m-d H:i:s');

            $updatedData = array_merge($existingData, $data);
            $model->update($id, $updatedData);

            return $this->respond($updatedData);
        } else {
            return $this->failNotFound('Data not found or already deleted');
        }

    }

    public function delete($id = null)
    {
        $authHeader = $this->request->getHeaderLine('Authorization');

        if ($authHeader !== '3cdcnTiBsl') {
            return $this->failUnauthorized('Invalid Authorization header');
        }

        $model = new \App\Models\UserModel();
        $data = $model->find($id);
        $data['deleted_at'] = date('Y-m-d H:i:s');
        $model->update($id, $data);

        return $this->respondDeleted(['id' => $id]);
    }
}

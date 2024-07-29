<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id', 'email', 'first_name', 'last_name', 'avatar', 'created_at', 'updated_at', 'deleted_at'
    ];
    protected $useTimestamps = false;
}

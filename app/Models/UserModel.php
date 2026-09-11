<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['email', 'password_hash', 'name', 'role'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function findAdminByEmail(string $email): ?array
    {
        $user = $this->where('email', strtolower(trim($email)))
            ->where('role', 'admin')
            ->first();

        return $user ?: null;
    }
}

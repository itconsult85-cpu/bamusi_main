<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthAuditLogModel extends Model
{
    protected $table = 'auth_audit_logs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['email', 'user_id', 'event', 'ip_hash', 'user_agent', 'created_at'];
    protected $useTimestamps = false;
}

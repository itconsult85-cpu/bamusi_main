<?php

namespace App\Models;

use CodeIgniter\Model;

class BoardMemberModel extends Model
{
    protected $table = 'board_members';
    protected $allowedFields = [
        'role',
        'role_en',
        'name',
        'group_name',
        'group_name_en',
        'group_order',
        'member_order',
        'note',
        'note_en',
        'photo_url',
        'sort_order',
        'published',
    ];
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
    {
        protected $table = 'users';
        protected $primaryKey = 'email';
        protected $allowedFields = ['name','surname', 'password', 'role', 'number'];


    protected $validationRules = [
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]',
    ];

    protected $validationMessages = [
        'email' => [
            'required' => 'Email is required.',
            'valid_email' => 'Please enter a valid email address.',
            'is_unique' => 'This email is already registered.',
        ],
        'password' => [
            'required' => 'Password is required.',
            'min_length' => 'Password must be at least 6 characters long.',
        ],
    ];
}

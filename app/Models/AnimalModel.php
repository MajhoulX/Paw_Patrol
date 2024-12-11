<?php

namespace App\Models;

use CodeIgniter\Model;

class AnimalModel extends Model
{
    protected $table = 'animals';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name','species', 'age', 'sex', 'location', 'description', 'picture'];

    protected $validationRules = [
        'name' => 'required',
        'species' => 'required|min_length[3]',
        'age' => 'required|integer',
        'sex' => 'required',
        'location' => 'required|min_length[3]',
        'description' => 'required',
        'picture' => 'required', 
    ];

    protected $validationMessages = [
        'species' => [
            'required' => 'Species is required.',
            'min_length' => 'Species name must be at least 3 characters long.',
        ],
        'age' => [
            'required' => 'Age is required.',
            'integer' => 'Age must be a valid number.',
        ],
        'sex' => [
            'required' => 'Sex is required.',
        ],
        'location' => [
            'required' => 'Location is required.',
            'min_length' => 'Location must be at least 3 characters long.',
        ],
        'description' => [
            'required' => 'Description is required.'
        ],
        'picture' => [
            'required' => 'Image is required.',
            'valid_url' => 'Please provide a valid image URL.',
        ],
    ];
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonModel extends Model
{
    protected $table            = 'persons';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'first_name',
        'last_name',
        'maiden_name',
        'gender',
        'birth_date',
        'birth_place',
        'death_date',
        'death_place',
        'biography',
        'occupation',
        'primary_photo_id',
        'created_by',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'first_name' => 'required|min_length[2]|max_length[100]',
        'last_name'  => 'required|min_length[2]|max_length[100]',
    ];

    protected $validationMessages = [
        'first_name' => [
            'required'   => 'Vorname ist erforderlich.',
            'min_length' => 'Vorname muss mindestens 2 Zeichen lang sein.',
        ],
        'last_name' => [
            'required'   => 'Nachname ist erforderlich.',
            'min_length' => 'Nachname muss mindestens 2 Zeichen lang sein.',
        ],
    ];
}
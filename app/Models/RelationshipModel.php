<?php

namespace App\Models;

use CodeIgniter\Model;

class RelationshipModel extends Model
{
    protected $table            = 'relationships';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'person1_id',
        'person2_id',
        'relationship_type',
        'start_date',
        'end_date',
        'notes',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
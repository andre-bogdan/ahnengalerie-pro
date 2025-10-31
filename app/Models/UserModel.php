<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'email',
        'password',
        'is_admin',
        'newsletter_enabled',
        'updated_at'
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation Rules
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
    ];

    protected $validationMessages = [
        'username' => [
            'required'    => 'Benutzername ist erforderlich.',
            'min_length'  => 'Benutzername muss mindestens 3 Zeichen lang sein.',
            'is_unique'   => 'Dieser Benutzername ist bereits vergeben.',
        ],
        'email' => [
            'required'    => 'E-Mail ist erforderlich.',
            'valid_email' => 'Bitte gültige E-Mail-Adresse eingeben.',
            'is_unique'   => 'Diese E-Mail ist bereits registriert.',
        ],
        'password' => [
            'required'   => 'Passwort ist erforderlich.',
            'min_length' => 'Passwort muss mindestens 6 Zeichen lang sein.',
        ],
    ];

    // Before Insert/Update: Hash password
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Hash password before saving
     * 
     * @param array $data
     * @return array
     */
    protected function hashPassword(array $data): array
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }

        $data['data']['password'] = password_hash(
            $data['data']['password'],
            PASSWORD_DEFAULT
        );

        return $data;
    }

    /**
     * Find user by username
     * 
     * @param string $username
     * @return array|null
     */
    public function findByUsername(string $username): ?array
    {
        $result = $this->where('username', $username)->first();
        return is_array($result) ? $result : null;
    }

    /**
     * Find user by email
     * 
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email): ?array
    {
        $result = $this->where('email', $email)->first();
        return is_array($result) ? $result : null;
    }

    /**
     * Verify user credentials (for login)
     * 
     * @param string $username
     * @param string $password
     * @return array|false Returns user array on success, false on failure
     */
    public function verifyCredentials(string $username, string $password)
    {
        $user = $this->findByUsername($username);

        if (!$user) {
            return false;
        }

        // Verify password
        if (password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Check if user is admin
     * 
     * @param int $userId
     * @return bool
     */
    public function isAdmin(int $userId): bool
    {
        $user = $this->find($userId);
        
        if (!is_array($user)) {
            return false;
        }
        
        return isset($user['is_admin']) && $user['is_admin'] == 1;
    }
}
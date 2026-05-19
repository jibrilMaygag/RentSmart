<?php
/**
 * User Model
 */

class User extends BaseModel
{
    protected string $table = 'users';

    /**
     * Find user by email
     */
    public function findByEmail(string $email): array|null
    {
        return $this->firstWhere('email = ?', [strtolower(trim($email))]);
    }

    /**
     * Create a new user
     */
    public function create(array $data): string
    {
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $data['email'] = strtolower($data['email']);
        return $this->insert($data);
    }

    /**
     * Verify password
     */
    public function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    /**
     * Get landlord properties
     */
    public function getLandlordProperties(int $landlordId): array
    {
        return $this->db->query(
            'SELECT p.*, pi.image_path AS image_path
             FROM properties p
             LEFT JOIN property_images pi ON pi.property_id = p.id AND pi.is_primary = 1
             WHERE p.landlord_id = ?
             ORDER BY p.created_at DESC',
            [$landlordId]
        )->fetchAll();
    }

    /**
     * Get user profile
     */
    public function getProfile(int $userId): array|null
    {
        return $this->find($userId);
    }
}

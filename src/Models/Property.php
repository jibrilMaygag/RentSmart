<?php
/**
 * Property Model
 */

class Property extends BaseModel
{
    protected string $table = 'properties';

    public function getFeatured(int $limit = 8): array
    {
        return $this->db->query(
            'SELECT p.*, pi.filename AS image_filename
             FROM properties p
             LEFT JOIN property_images pi ON pi.property_id = p.id AND pi.is_primary = 1
             WHERE p.is_featured = 1 AND p.status = "available"
             ORDER BY p.created_at DESC LIMIT ?',
            [$limit]
        )->fetchAll();
    }

    public function search(array $filters): array
    {
        $query  = 'SELECT p.*, pi.filename AS image_filename
                   FROM properties p
                   LEFT JOIN property_images pi ON pi.property_id = p.id AND pi.is_primary = 1
                   WHERE p.status = "available"';
        $params = [];

        if (!empty($filters['keyword'])) {
            $query .= ' AND (p.title LIKE ? OR p.description LIKE ? OR p.city LIKE ? OR p.address LIKE ?)';
            $kw     = '%' . $filters['keyword'] . '%';
            $params = array_merge($params, [$kw, $kw, $kw, $kw]);
        }

        if (!empty($filters['city'])) {
            $query .= ' AND p.city LIKE ?';
            $params[] = '%' . $filters['city'] . '%';
        }

        if (!empty($filters['listing_type'])) {
            $query .= ' AND p.listing_type = ?';
            $params[] = $filters['listing_type'];
        }

        if (!empty($filters['property_type'])) {
            $query .= ' AND p.property_type = ?';
            $params[] = $filters['property_type'];
        }

        if (!empty($filters['min_price'])) {
            $query .= ' AND p.price >= ?';
            $params[] = (float)$filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $query .= ' AND p.price <= ?';
            $params[] = (float)$filters['max_price'];
        }

        if (!empty($filters['bedrooms'])) {
            $query .= ' AND p.bedrooms >= ?';
            $params[] = (int)$filters['bedrooms'];
        }

        $query .= ' ORDER BY p.created_at DESC';

        if (!empty($filters['limit'])) {
            $query .= ' LIMIT ?';
            $params[] = (int)$filters['limit'];
        }

        return $this->db->query($query, $params)->fetchAll();
    }

    public function getCities(): array
    {
        $results = $this->db->query(
            'SELECT DISTINCT city FROM properties WHERE status = "available" ORDER BY city'
        )->fetchAll();
        return array_column($results, 'city');
    }

    public function getWithDetails(int $id): array|null
    {
        $property = $this->db->query(
            'SELECT p.*, u.full_name AS landlord_name, u.phone AS landlord_phone, u.email AS landlord_email
             FROM properties p
             LEFT JOIN users u ON u.id = p.landlord_id
             WHERE p.id = ?',
            [$id]
        )->fetch();

        if (!$property) return null;

        $property['images'] = $this->db->query(
            'SELECT * FROM property_images WHERE property_id = ? ORDER BY is_primary DESC, sort_order ASC',
            [$id]
        )->fetchAll();

        $property['amenities'] = $this->db->query(
            'SELECT a.* FROM amenities a
             JOIN property_amenities pa ON a.id = pa.amenity_id
             WHERE pa.property_id = ?',
            [$id]
        )->fetchAll();

        return $property;
    }

    public function incrementViews(int $id): void
    {
        $this->db->query('UPDATE properties SET views = views + 1 WHERE id = ?', [$id]);
    }

    public function getTotalCount(): int
    {
        return $this->count('status = "available"');
    }

    // ── Favorites ──────────────────────────────────────────────────────────────

    public function isFavorited(int $propertyId, int $userId): bool
    {
        $result = $this->db->query(
            'SELECT id FROM favorites WHERE property_id = ? AND user_id = ?',
            [$propertyId, $userId]
        )->fetch();
        return (bool)$result;
    }

    /**
     * Toggle favorite; returns true if now favorited, false if removed.
     */
    public function toggleFavorite(int $propertyId, int $userId): bool
    {
        if ($this->isFavorited($propertyId, $userId)) {
            $this->db->query(
                'DELETE FROM favorites WHERE property_id = ? AND user_id = ?',
                [$propertyId, $userId]
            );
            return false;
        }

        $this->db->query(
            'INSERT INTO favorites (property_id, user_id) VALUES (?, ?)',
            [$propertyId, $userId]
        );
        return true;
    }

    public function getUserFavorites(int $userId): array
    {
        return $this->db->query(
            'SELECT p.*, pi.filename AS image_filename
             FROM properties p
             JOIN favorites f ON f.property_id = p.id
             LEFT JOIN property_images pi ON pi.property_id = p.id AND pi.is_primary = 1
             WHERE f.user_id = ?
             ORDER BY f.created_at DESC',
            [$userId]
        )->fetchAll();
    }

    public function getCityCount(string $city): int
    {
        $result = $this->db->query(
            'SELECT COUNT(*) AS cnt FROM properties WHERE city LIKE ? AND status = "available"',
            ['%' . $city . '%']
        )->fetch();
        return (int)($result['cnt'] ?? 0);
    }
}

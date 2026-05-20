<?php
/**
 * Property Model
 */

class Property extends BaseModel
{
    protected string $table = 'properties';

    public function getAmenities(): array
    {
        return $this->db->query(
            'SELECT * FROM amenities ORDER BY name ASC'
        )->fetchAll();
    }

    public function getFeatured(int $limit = 8): array
    {
        $properties = $this->db->query(
            'SELECT p.*, pi.image_path AS image_path
             FROM properties p
             LEFT JOIN property_images pi ON pi.property_id = p.id AND pi.is_primary = 1
             WHERE p.is_featured = 1 AND p.status = "available"
             ORDER BY p.created_at DESC LIMIT ?',
            [$limit]
        )->fetchAll();

        return $this->withAmenities($properties);
    }

    public function search(array $filters): array
    {
        $query  = 'SELECT p.*, pi.image_path AS image_path
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

        $properties = $this->db->query($query, $params)->fetchAll();

        return $this->withAmenities($properties);
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
            'SELECT id, property_id, image_path, is_primary, sort_order, created_at
             FROM property_images
             WHERE property_id = ?
             ORDER BY is_primary DESC, sort_order ASC',
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

    public function getLandlordListings(int $landlordId, array $filters = []): array
    {
        $query = 'SELECT p.*, pi.image_path AS image_path
                  FROM properties p
                  LEFT JOIN property_images pi ON pi.property_id = p.id AND pi.is_primary = 1
                  WHERE p.landlord_id = ?';
        $params = [$landlordId];

        if (!empty($filters['status']) && in_array($filters['status'], ['available', 'pending', 'rented', 'sold'], true)) {
            $query .= ' AND p.status = ?';
            $params[] = $filters['status'];
        }

        if (!empty($filters['keyword'])) {
            $query .= ' AND (p.title LIKE ? OR p.city LIKE ? OR p.address LIKE ? OR p.sub_city LIKE ?)';
            $keyword = '%' . trim($filters['keyword']) . '%';
            array_push($params, $keyword, $keyword, $keyword, $keyword);
        }

        $query .= ' ORDER BY p.created_at DESC';

        $properties = $this->db->query($query, $params)->fetchAll();

        return $this->withAmenities($properties);
    }

    public function getLandlordListingCounts(int $landlordId): array
    {
        $counts = [
            'all' => 0,
            'available' => 0,
            'pending' => 0,
            'rented' => 0,
            'sold' => 0,
        ];

        $rows = $this->db->query(
            'SELECT status, COUNT(*) AS total
             FROM properties
             WHERE landlord_id = ?
             GROUP BY status',
            [$landlordId]
        )->fetchAll();

        foreach ($rows as $row) {
            $status = $row['status'] ?? '';
            if (isset($counts[$status])) {
                $counts[$status] = (int)$row['total'];
                $counts['all'] += (int)$row['total'];
            }
        }

        return $counts;
    }

    public function getOwnedWithDetails(int $propertyId, int $landlordId): array|null
    {
        $property = $this->db->query(
            'SELECT p.*, u.full_name AS landlord_name, u.phone AS landlord_phone, u.email AS landlord_email
             FROM properties p
             LEFT JOIN users u ON u.id = p.landlord_id
             WHERE p.id = ? AND p.landlord_id = ?
             LIMIT 1',
            [$propertyId, $landlordId]
        )->fetch();

        if (!$property) {
            return null;
        }

        $property['images'] = $this->db->query(
            'SELECT id, property_id, image_path, is_primary, sort_order, created_at
             FROM property_images
             WHERE property_id = ?
             ORDER BY is_primary DESC, sort_order ASC',
            [$propertyId]
        )->fetchAll();

        $property['amenities'] = $this->db->query(
            'SELECT a.* FROM amenities a
             JOIN property_amenities pa ON a.id = pa.amenity_id
             WHERE pa.property_id = ?
             ORDER BY a.name ASC',
            [$propertyId]
        )->fetchAll();

        $property['amenity_ids'] = array_map(
            static fn(array $amenity): int => (int)$amenity['id'],
            $property['amenities']
        );

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
        $properties = $this->db->query(
            'SELECT p.*, pi.image_path AS image_path
             FROM properties p
             JOIN favorites f ON f.property_id = p.id
             LEFT JOIN property_images pi ON pi.property_id = p.id AND pi.is_primary = 1
             WHERE f.user_id = ?
             ORDER BY f.created_at DESC',
            [$userId]
        )->fetchAll();

        return $this->withAmenities($properties);
    }

    public function getCityCount(string $city): int
    {
        $result = $this->db->query(
            'SELECT COUNT(*) AS cnt FROM properties WHERE city LIKE ? AND status = "available"',
            ['%' . $city . '%']
        )->fetch();
        return (int)($result['cnt'] ?? 0);
    }

    public function createForLandlord(int $landlordId, array $data, array $amenityIds = [], array $imagePaths = []): int
    {
        $this->db->beginTransaction();

        try {
            $propertyId = (int)$this->insert(array_merge($data, [
                'landlord_id' => $landlordId,
            ]));

            $this->syncAmenities($propertyId, $amenityIds);
            $this->attachImages($propertyId, $imagePaths, 0, true);

            $this->db->commit();
            return $propertyId;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateForLandlord(int $propertyId, int $landlordId, array $data, array $amenityIds = [], array $newImagePaths = []): bool
    {
        $property = $this->firstWhere('id = ? AND landlord_id = ?', [$propertyId, $landlordId]);
        if (!$property) {
            throw new RuntimeException('Listing not found.');
        }

        $this->db->beginTransaction();

        try {
            $this->db->query(
                'UPDATE properties
                 SET title = ?, description = ?, price = ?, listing_type = ?, property_type = ?, status = ?,
                     bedrooms = ?, bathrooms = ?, area_sqm = ?, address = ?, city = ?, sub_city = ?, updated_at = CURRENT_TIMESTAMP
                 WHERE id = ? AND landlord_id = ?',
                [
                    $data['title'],
                    $data['description'],
                    $data['price'],
                    $data['listing_type'],
                    $data['property_type'],
                    $data['status'],
                    $data['bedrooms'],
                    $data['bathrooms'],
                    $data['area_sqm'],
                    $data['address'],
                    $data['city'],
                    $data['sub_city'],
                    $propertyId,
                    $landlordId,
                ]
            );

            $this->syncAmenities($propertyId, $amenityIds);

            if (!empty($newImagePaths)) {
                $existingImageCount = (int)$this->db->query(
                    'SELECT COUNT(*) AS total FROM property_images WHERE property_id = ?',
                    [$propertyId]
                )->fetch()['total'];

                $nextSortOrder = (int)$this->db->query(
                    'SELECT COALESCE(MAX(sort_order), -1) + 1 AS next_order FROM property_images WHERE property_id = ?',
                    [$propertyId]
                )->fetch()['next_order'];

                $this->attachImages($propertyId, $newImagePaths, $nextSortOrder, $existingImageCount === 0);
            }

            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateStatusForLandlord(int $propertyId, int $landlordId, string $status): bool
    {
        if (!in_array($status, ['available', 'pending', 'rented', 'sold'], true)) {
            throw new RuntimeException('Please choose a valid listing status.');
        }

        $this->db->query(
            'UPDATE properties SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND landlord_id = ?',
            [$status, $propertyId, $landlordId]
        );

        return true;
    }

    public function deleteForLandlord(int $propertyId, int $landlordId): array
    {
        $property = $this->firstWhere('id = ? AND landlord_id = ?', [$propertyId, $landlordId]);
        if (!$property) {
            throw new RuntimeException('Listing not found.');
        }

        $images = $this->db->query(
            'SELECT image_path FROM property_images WHERE property_id = ?',
            [$propertyId]
        )->fetchAll();

        $this->delete($propertyId);

        return array_map(
            static fn(array $image): string => $image['image_path'],
            $images
        );
    }

    private function syncAmenities(int $propertyId, array $amenityIds): void
    {
        $amenityIds = array_values(array_unique(array_map('intval', $amenityIds)));
        $amenityIds = array_filter($amenityIds, static fn(int $id): bool => $id > 0);

        $this->db->query('DELETE FROM property_amenities WHERE property_id = ?', [$propertyId]);

        if (empty($amenityIds)) {
            return;
        }

        foreach ($amenityIds as $amenityId) {
            $this->db->query(
                'INSERT INTO property_amenities (property_id, amenity_id) VALUES (?, ?)',
                [$propertyId, $amenityId]
            );
        }
    }

    private function attachImages(int $propertyId, array $imagePaths, int $startOrder = 0, bool $markFirstAsPrimary = false): void
    {
        $order = $startOrder;

        foreach ($imagePaths as $index => $imagePath) {
            $this->db->query(
                'INSERT INTO property_images (property_id, image_path, is_primary, sort_order) VALUES (?, ?, ?, ?)',
                [
                    $propertyId,
                    $imagePath,
                    $markFirstAsPrimary && $index === 0 ? 1 : 0,
                    $order,
                ]
            );
            $order++;
        }
    }

    private function withAmenities(array $properties): array
    {
        if (empty($properties)) {
            return $properties;
        }

        $propertyIds = array_values(array_filter(array_map(
            static fn(array $property): int => (int)($property['id'] ?? 0),
            $properties
        )));

        if (empty($propertyIds)) {
            return $properties;
        }

        $placeholders = implode(', ', array_fill(0, count($propertyIds), '?'));
        $rows = $this->db->query(
            "SELECT pa.property_id, a.id, a.name
             FROM property_amenities pa
             JOIN amenities a ON a.id = pa.amenity_id
             WHERE pa.property_id IN ({$placeholders})
             ORDER BY a.name ASC",
            $propertyIds
        )->fetchAll();

        $amenitiesByProperty = [];
        foreach ($rows as $row) {
            $propertyId = (int)$row['property_id'];
            $amenitiesByProperty[$propertyId][] = [
                'id' => (int)$row['id'],
                'name' => $row['name'],
            ];
        }

        foreach ($properties as &$property) {
            $propertyAmenities = $amenitiesByProperty[(int)($property['id'] ?? 0)] ?? [];
            $property['amenities'] = $propertyAmenities;
            $property['amenity_names'] = array_column($propertyAmenities, 'name');
        }
        unset($property);

        return $properties;
    }
}

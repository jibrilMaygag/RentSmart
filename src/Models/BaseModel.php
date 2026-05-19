<?php
/**
 * BaseModel - Abstract base class for all models
 *
 * Provides common database operations and utilities
 */

abstract class BaseModel
{
    protected Database $db;
    protected string $table = '';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find a record by ID
     */
    public function find(int $id): array|null
    {
        $result = $this->db->query(
            "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1",
            [$id]
        )->fetch();

        return $result ?: null;
    }

    /**
     * Get all records
     */
    public function all(): array
    {
        return $this->db->query("SELECT * FROM {$this->table}")->fetchAll();
    }

    /**
     * Get records with conditions
     */
    public function where(string $condition, array $params = []): array
    {
        $query = "SELECT * FROM {$this->table} WHERE {$condition}";
        return $this->db->query($query, $params)->fetchAll();
    }

    /**
     * Get first record matching condition
     */
    public function firstWhere(string $condition, array $params = []): array|null
    {
        $query = "SELECT * FROM {$this->table} WHERE {$condition} LIMIT 1";
        $result = $this->db->query($query, $params)->fetch();
        return $result ?: null;
    }

    /**
     * Get count of records
     */
    public function count(string $condition = ''): int
    {
        $query = "SELECT COUNT(*) as cnt FROM {$this->table}";
        if (!empty($condition)) {
            $query .= " WHERE {$condition}";
        }
        $result = $this->db->query($query)->fetch();
        return (int)($result['cnt'] ?? 0);
    }

    /**
     * Insert a record
     */
    public function insert(array $data): string
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $this->db->query($query, array_values($data));
        return $this->db->lastInsertId();
    }

    /**
     * Update a record
     */
    public function update(int $id, array $data): bool
    {
        $updates = implode(', ', array_map(fn($col) => "{$col} = ?", array_keys($data)));
        $params = array_values($data);
        $params[] = $id;

        $query = "UPDATE {$this->table} SET {$updates} WHERE id = ?";
        $this->db->query($query, $params);
        return true;
    }

    /**
     * Delete a record
     */
    public function delete(int $id): bool
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id = ?", [$id]);
        return true;
    }
}

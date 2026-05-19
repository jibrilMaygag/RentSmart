<?php
/**
 * Validator Service
 */

class Validator
{
    private array $errors = [];
    private array $data   = [];

    public function __construct(array $input = [])
    {
        $this->data = $this->sanitize($input);
    }

    private function sanitize(array $input): array
    {
        $out = [];
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                $out[$key] = htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
            } else {
                $out[$key] = $value;
            }
        }
        return $out;
    }

    public function get(string $key, mixed $default = ''): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function all(): array { return $this->data; }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function errors(): array { return $this->errors; }

    public function passes(): bool { return empty($this->errors); }

    // ── Rules ─────────────────────────────────────────────────────────────────

    public function required(string $field, string $label = ''): static
    {
        $label = $label ?: ucfirst($field);
        if (empty($this->data[$field]) && $this->data[$field] !== '0') {
            $this->addError($field, "$label is required.");
        }
        return $this;
    }

    public function email(string $field, string $label = ''): static
    {
        $label = $label ?: ucfirst($field);
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "$label must be a valid email address.");
        }
        return $this;
    }

    public function min(string $field, int $min, string $label = ''): static
    {
        $label = $label ?: ucfirst($field);
        if (!empty($this->data[$field]) && strlen($this->data[$field]) < $min) {
            $this->addError($field, "$label must be at least $min characters.");
        }
        return $this;
    }

    public function max(string $field, int $max, string $label = ''): static
    {
        $label = $label ?: ucfirst($field);
        if (!empty($this->data[$field]) && strlen($this->data[$field]) > $max) {
            $this->addError($field, "$label must not exceed $max characters.");
        }
        return $this;
    }

    public function match(string $field, string $otherField, string $label = ''): static
    {
        $label = $label ?: ucfirst($field);
        if (($this->data[$field] ?? '') !== ($this->data[$otherField] ?? '')) {
            $this->addError($otherField, "$label fields do not match.");
        }
        return $this;
    }

    public function unique(string $field, string $table, string $column = ''): static
    {
        $column = $column ?: $field;
        $value  = $this->data[$field] ?? '';
        if (empty($value)) return $this;

        try {
            $db     = Database::getInstance();
            $result = $db->query(
                "SELECT id FROM `{$table}` WHERE `{$column}` = ? LIMIT 1",
                [$value]
            )->fetch();

            if ($result) {
                $label = ucfirst($field);
                $this->addError($field, "This $label is already registered.");
            }
        } catch (Exception $e) {
            // If DB check fails, let it through (server-side will catch duplicate key)
            error_log('Unique validation error: ' . $e->getMessage());
        }

        return $this;
    }

    public function numeric(string $field, string $label = ''): static
    {
        $label = $label ?: ucfirst($field);
        if (!empty($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->addError($field, "$label must be a number.");
        }
        return $this;
    }

    public function inArray(string $field, array $allowed, string $label = ''): static
    {
        $label = $label ?: ucfirst($field);
        if (!empty($this->data[$field]) && !in_array($this->data[$field], $allowed)) {
            $this->addError($field, "Invalid value for $label.");
        }
        return $this;
    }
}

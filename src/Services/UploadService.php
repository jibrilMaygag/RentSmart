<?php
/**
 * Upload Service - Handles file uploads
 */

class UploadService
{
    private string $uploadDir;
    private string $uploadUrl;

    public function __construct()
    {
        $this->uploadDir = UPLOAD_DIR;
        $this->uploadUrl = UPLOAD_URL;

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    /**
     * Upload a property image. Returns filename on success, throws on failure.
     */
    public function uploadPropertyImage(array $file): string
    {
        $this->validateFile($file);

        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = 'prop_' . uniqid('', true) . '.' . $ext;
        $destPath = $this->uploadDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            throw new RuntimeException('Failed to move uploaded file.');
        }

        return $filename;
    }

    private function validateFile(array $file): void
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Upload error code: ' . $file['error']);
        }
        if ($file['size'] > MAX_FILE_SIZE) {
            throw new RuntimeException('File exceeds maximum allowed size (' . formatBytes(MAX_FILE_SIZE) . ').');
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);
        if (!in_array($mime, ALLOWED_MIME_TYPES, true)) {
            throw new RuntimeException('Invalid file type. Allowed: JPG, PNG, WebP.');
        }
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ALLOWED_EXTENSIONS, true)) {
            throw new RuntimeException('Invalid file extension.');
        }
    }

    public function delete(string $filename): bool
    {
        $path = $this->uploadDir . '/' . basename($filename);
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }

    public function getUrl(string $filename): string
    {
        return $this->uploadUrl . '/' . rawurlencode($filename);
    }
}

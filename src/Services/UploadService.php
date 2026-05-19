<?php
/**
 * Upload Service - Handles file uploads
 */

class UploadService
{
    private string $uploadsRootDir;
    private string $propertyUploadDir;

    public function __construct()
    {
        $this->uploadsRootDir = UPLOADS_DIR;
        $this->propertyUploadDir = PROPERTY_UPLOAD_DIR;
        $this->ensureDirectory($this->uploadsRootDir);
        $this->ensureDirectory($this->propertyUploadDir);
    }

    /**
     * Upload a property image. Returns a relative image path on success.
     */
    public function uploadPropertyImage(array $file): string
    {
        $this->validateFile($file);

        $ext = strtolower(pathinfo((string)$file['name'], PATHINFO_EXTENSION));
        $filename = $this->generateUniqueFilename($ext);
        $destPath = $this->propertyUploadDir . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            throw new RuntimeException('We could not upload that image. Please try again.');
        }

        return PROPERTY_UPLOADS_SUBDIR . '/' . $filename;
    }

    private function validateFile(array $file): void
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('We could not process that upload. Please try again.');
        }
        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException('That image upload could not be verified. Please try again.');
        }
        if ($file['size'] > MAX_FILE_SIZE) {
            throw new RuntimeException('That image is too large. Maximum size: ' . formatBytes(MAX_FILE_SIZE) . '.');
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);
        if (!in_array($mime, ALLOWED_MIME_TYPES, true)) {
            throw new RuntimeException('Please upload a JPG, PNG, or WebP image.');
        }
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ALLOWED_EXTENSIONS, true)) {
            throw new RuntimeException('Unsupported file format. Please upload a JPG, PNG, or WebP image.');
        }
    }

    public function delete(string $imagePath): bool
    {
        $resolvedPath = $this->resolveManagedImagePath($imagePath);
        if (!$resolvedPath || !file_exists($resolvedPath)) {
            return false;
        }

        return unlink($resolvedPath);
    }

    public function getUrl(string $imagePath): string
    {
        return imageUrl($imagePath);
    }

    public function isManagedPropertyImage(string $imagePath): bool
    {
        return $this->resolveManagedImagePath($imagePath) !== null;
    }

    private function ensureDirectory(string $path): void
    {
        if (!is_dir($path) && !mkdir($path, 0755, true) && !is_dir($path)) {
            throw new RuntimeException('We could not prepare image uploads right now. Please try again.');
        }
    }

    private function generateUniqueFilename(string $extension): string
    {
        try {
            $random = bin2hex(random_bytes(16));
        } catch (Throwable $e) {
            $random = str_replace('.', '', uniqid('', true));
        }

        return 'prop_' . $random . '.' . $extension;
    }

    private function resolveManagedImagePath(string $imagePath): string|null
    {
        $normalized = ltrim(str_replace('\\', '/', trim($imagePath)), '/');
        if ($normalized === '') {
            return null;
        }

        if (str_starts_with($normalized, PROPERTY_UPLOADS_SUBDIR . '/')) {
            return APP_ROOT . '/' . $normalized;
        }

        if ((str_starts_with($normalized, 'prop_') || str_starts_with($normalized, 'upload_'))
            && str_contains($normalized, '/') === false
        ) {
            return LEGACY_UPLOAD_DIR . '/' . basename($normalized);
        }

        return null;
    }
}

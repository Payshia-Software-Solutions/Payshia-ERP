<?php

class ImageUpload
{
    private $file;
    private $targetDirectory;
    private $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    private $maxSize = 20 * 1024 * 1024; // 20 MB
    private $error;

    public function __construct($file, $targetDirectory)
    {
        $this->file = $file;
        $this->targetDirectory = $targetDirectory;
        $this->createDirectoryIfNotExists();
    }

    private function createDirectoryIfNotExists()
    {
        if (!is_dir($this->targetDirectory)) {
            if (!mkdir($this->targetDirectory, 0777, true)) {
                $this->error = 'Failed to create target directory';
                return false;
            }
        }
        return true;
    }

    public function validate()
    {
        // Check if file is uploaded
        if ($this->file['error'] !== UPLOAD_ERR_OK) {
            $this->error = 'File upload error';
            return false;
        }

        // Check file size
        if ($this->file['size'] > $this->maxSize) {
            $this->error = 'File size exceeds the maximum limit';
            return false;
        }

        // Check file type
        if (!in_array($this->file['type'], $this->allowedTypes)) {
            $this->error = 'Invalid file type';
            return false;
        }

        return true;
    }

    public function upload()
    {
        // Validate the image first
        if (!$this->validate()) {
            return false;
        }

        // Generate a unique file name to avoid collisions
        $fileName = uniqid() . '-' . basename($this->file['name']);
        $targetFilePath = $this->targetDirectory . DIRECTORY_SEPARATOR . $fileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($this->file['tmp_name'], $targetFilePath)) {
            return $fileName; // Return the file name on success
        } else {
            $this->error = 'Error moving the uploaded file';
            return false;
        }
    }

    public function getError()
    {
        return $this->error;
    }
}

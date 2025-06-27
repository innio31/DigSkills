<?php
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function uploadFile($file, $index, $uploadDir, $allowedExtensions, $maxSize) {
    $fileName = $file['name'][$index];
    $fileTmp = $file['tmp_name'][$index];
    $fileSize = $file['size'][$index];
    $fileError = $file['error'][$index];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    if (!in_array($fileExt, $allowedExtensions)) {
        throw new Exception("Invalid file type. Allowed: " . implode(', ', $allowedExtensions));
    }
    
    if ($fileError !== UPLOAD_ERR_OK) {
        throw new Exception("File upload error: " . $fileError);
    }
    
    if ($fileSize > $maxSize) {
        throw new Exception("File too large. Max size: " . ($maxSize/1024/1024) . "MB");
    }
    
    $newFileName = uniqid('', true) . '.' . $fileExt;
    $destination = $uploadDir . $newFileName;
    
    if (!move_uploaded_file($fileTmp, $destination)) {
        throw new Exception("Failed to move uploaded file");
    }
    
    return $destination;
}

function createDirectory($path) {
    if (!file_exists($path)) {
        if (!mkdir($path, 0755, true)) {
            throw new Exception("Failed to create directory: " . $path);
        }
    }
}

function sendConfirmationEmail($to, $name) {
    // Implement your email sending logic
}
?>

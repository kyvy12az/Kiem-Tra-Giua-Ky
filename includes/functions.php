<?php

function getAllRecords($conn) {
    $stmt = $conn->prepare("SELECT admin_id, admin_name, admin_email, images FROM users ORDER BY admin_id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRecordById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE admin_id = :admin_id");
    $stmt->bindParam(':admin_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addRecord($conn, $data) {
    try {
        $stmt = $conn->prepare("INSERT INTO users (admin_name, admin_email, admin_password, images) VALUES (:admin_name, :admin_email, :admin_password, :images)");
        $stmt->bindParam(':admin_name', $data['admin_name']);
        $stmt->bindParam(':admin_email', $data['admin_email']);
        $stmt->bindParam(':admin_password', $data['admin_password']);
        $stmt->bindParam(':images', $data['images']);
        return $stmt->execute();
    } catch (PDOException $e) {
        // Check if it's a duplicate entry error
        if ($e->getCode() == 23000) {
            return false; // Duplicate email
        }
        throw $e; // Re-throw other exceptions
    }
}

function updateRecord($conn, $id, $data) {
    try {
        // Build dynamic SQL based on whether password is being updated
        if (!empty($data['admin_password'])) {
            $sql = "UPDATE users SET admin_name = :admin_name, admin_email = :admin_email, admin_password = :admin_password, images = :images WHERE admin_id = :admin_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':admin_password', $data['admin_password']);
        } else {
            $sql = "UPDATE users SET admin_name = :admin_name, admin_email = :admin_email, images = :images WHERE admin_id = :admin_id";
            $stmt = $conn->prepare($sql);
        }
        
        $stmt->bindParam(':admin_name', $data['admin_name']);
        $stmt->bindParam(':admin_email', $data['admin_email']);
        $stmt->bindParam(':images', $data['images']);
        $stmt->bindParam(':admin_id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        // Check if it's a duplicate entry error
        if ($e->getCode() == 23000) {
            return false; // Duplicate email
        }
        throw $e; // Re-throw other exceptions
    }
}

function deleteRecord($conn, $id) {
    // Get image path before deleting
    $record = getRecordById($conn, $id);
    
    $stmt = $conn->prepare("DELETE FROM users WHERE admin_id = :admin_id");
    $stmt->bindParam(':admin_id', $id, PDO::PARAM_INT);
    $result = $stmt->execute();
    
    // Delete image file if exists
    if ($result && !empty($record['images'])) {
        $imagePath = __DIR__ . '/../public/uploads/' . $record['images'];
        if (file_exists($imagePath)) {
            @unlink($imagePath);
        }
    }
    
    return $result;
}

function uploadImage($file) {
    $uploadDir = __DIR__ . '/../public/uploads/';
    
    // Create uploads directory if not exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    // Validate file
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return ['success' => false, 'message' => 'Không có file được tải lên'];
    }
    
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP)'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'Kích thước file không được vượt quá 5MB'];
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'avatar_' . uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename];
    }
    
    return ['success' => false, 'message' => 'Không thể tải lên file'];
}

function deleteImage($filename) {
    if (empty($filename)) return false;
    
    $filepath = __DIR__ . '/../public/uploads/' . $filename;
    if (file_exists($filepath)) {
        return @unlink($filepath);
    }
    return false;
}

function emailExists($conn, $email, $excludeId = null) {
    if ($excludeId) {
        $stmt = $conn->prepare("SELECT admin_id FROM users WHERE admin_email = :email AND admin_id != :id");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $excludeId, PDO::PARAM_INT);
    } else {
        $stmt = $conn->prepare("SELECT admin_id FROM users WHERE admin_email = :email");
        $stmt->bindParam(':email', $email);
    }
    
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}
?>
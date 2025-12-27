<?php

function handlePhotoUpload($files) {
    $uploadDir = 'public/uploads/';
    $allowedTypes = ['image/jpeg', 'image/jpg'];
    $maxFileSize = 200 * 1024; 
    $maxFiles = 5;
    
    $result = [
        'success' => true,
        'filenames' => [],
        'error' => ''
    ];
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    if (empty($files['name'][0])) {
        return $result; 
    }
    
    $fileCount = count($files['name']);
    
    if ($fileCount > $maxFiles) {
        $result['success'] = false;
        $result['error'] = "Maximum $maxFiles photos autorisées";
        return $result;
    }
    
    for ($i = 0; $i < $fileCount; $i++) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) {
            $result['success'] = false;
            $result['error'] = "Erreur lors de l'upload du fichier " . ($i + 1);
            return $result;
        }
        
        if (!in_array($files['type'][$i], $allowedTypes)) {
            $result['success'] = false;
            $result['error'] = "Seules les images JPEG sont autorisées (fichier " . ($i + 1) . ")";
            return $result;
        }
        
        if ($files['size'][$i] > $maxFileSize) {
            $result['success'] = false;
            $result['error'] = "La photo " . ($i + 1) . " dépasse 200 Ko";
            return $result;
        }
        
        $extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
        $filename = uniqid('photo_', true) . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($files['tmp_name'][$i], $filepath)) {
            $result['filenames'][] = $filename;
        } else {
            $result['success'] = false;
            $result['error'] = "Erreur lors de la sauvegarde du fichier " . ($i + 1);
            return $result;
        }
    }
    
    return $result;
}

function deletePhoto($filename) {
    $filepath = 'public/uploads/' . $filename;
    
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    
    return false;
}

function deleteAllPhotos($photos) {
    foreach ($photos as $photo) {
        deletePhoto($photo->getFilename());
    }
}
?>
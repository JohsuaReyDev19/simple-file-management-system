<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $current_dir = $_POST['current_dir'];
    
    $upload_dir = '../../uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = $file['name'];
    $file_size = $file['size'];
    $file_tmp = $file['tmp_name'];
    
    $unique_name = uniqid() . '_' . $file_name;
    
    if (move_uploaded_file($file_tmp, $upload_dir . $unique_name)) {
        $stmt = $pdo->prepare("INSERT INTO files (name, system_name, size, folder) VALUES (?, ?, ?, ?)");
        $stmt->execute([$file_name, $unique_name, $file_size, $current_dir]);
    }

    header('Location: ../../index.php?dir=' . urlencode($current_dir));
    exit;
}
?>
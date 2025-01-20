<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $folder_name = trim($_POST['folder_name']);
    $current_dir = $_POST['current_dir'];

    if (!empty($folder_name)) {
        $stmt = $pdo->prepare("INSERT INTO folders (name, parent_dir) VALUES (?, ?)");
        $stmt->execute([$folder_name, $current_dir]);
    }

    header('Location: ../../index.php?dir=' . urlencode($current_dir));
    exit;
}
?>
<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_type = $_POST['item_type'];
    $item_id = $_POST['item_id'];
    $target_folder = $_POST['target_folder'];
    
    // Get target folder path
    if ($target_folder === '') {
        $new_folder_path = '';
    } else {
        $stmt = $pdo->prepare("SELECT name, parent_dir FROM folders WHERE id = ?");
        $stmt->execute([$target_folder]);
        $folder = $stmt->fetch();
        
        if ($folder) {
            $new_folder_path = trim($folder['parent_dir'] . '/' . $folder['name'], '/');
        } else {
            $new_folder_path = '';
        }
    }
    
    if ($item_type === 'file') {
        // Update file's folder path
        $stmt = $pdo->prepare("UPDATE files SET folder = ? WHERE id = ?");
        $stmt->execute([$new_folder_path, $item_id]);
    } else {
        // Get current folder info
        $stmt = $pdo->prepare("SELECT name, parent_dir FROM folders WHERE id = ?");
        $stmt->execute([$item_id]);
        $current_folder = $stmt->fetch();
        
        if ($current_folder) {
            $old_path = trim($current_folder['parent_dir'] . '/' . $current_folder['name'], '/');
            $new_path = $new_folder_path ? $new_folder_path . '/' . $current_folder['name'] : $current_folder['name'];
            
            // Update folder's parent_dir
            $stmt = $pdo->prepare("UPDATE folders SET parent_dir = ? WHERE id = ?");
            $stmt->execute([$new_folder_path, $item_id]);
            
            // Update paths of all subfolders
            $stmt = $pdo->prepare("UPDATE folders SET parent_dir = REPLACE(parent_dir, ?, ?) WHERE parent_dir LIKE ?");
            $stmt->execute([$old_path, $new_path, $old_path . '%']);
            
            // Update paths of all files in the folder and subfolders
            $stmt = $pdo->prepare("UPDATE files SET folder = REPLACE(folder, ?, ?) WHERE folder LIKE ?");
            $stmt->execute([$old_path, $new_path, $old_path . '%']);
        }
    }

    header('Location: ../../index.php' . (isset($_GET['dir']) ? '?dir=' . urlencode($_GET['dir']) : ''));
    exit;
}
?>
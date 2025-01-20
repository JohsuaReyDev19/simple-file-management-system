<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_type = $_POST['item_type'];
    $item_id = $_POST['item_id'];

    try {
        if ($item_type === 'folder') {
            // Get folder path before deletion
            $stmt = $pdo->prepare("SELECT name, parent_dir FROM folders WHERE id = ?");
            $stmt->execute([$item_id]);
            $folder = $stmt->fetch();

            if ($folder) {
                $folder_path = trim($folder['parent_dir'] . '/' . $folder['name'], '/');

                // Delete all files in this folder and its subfolders
                $stmt = $pdo->prepare("SELECT system_name FROM files WHERE folder LIKE ?");
                $stmt->execute([$folder_path . '%']);
                $files = $stmt->fetchAll();

                foreach ($files as $file) {
                    $file_path = realpath(__DIR__ . '/../../uploads/' . $file['system_name']);
                    if ($file_path && file_exists($file_path)) {
                        unlink($file_path);
                    }
                }

                // Delete all file records in this folder and subfolders
                $stmt = $pdo->prepare("DELETE FROM files WHERE folder LIKE ?");
                $stmt->execute([$folder_path . '%']);

                // Delete all subfolders
                $stmt = $pdo->prepare("DELETE FROM folders WHERE parent_dir LIKE ?");
                $stmt->execute([$folder_path . '%']);

                // Delete the folder itself
                $stmt = $pdo->prepare("DELETE FROM folders WHERE id = ?");
                $stmt->execute([$item_id]);
            }
        } elseif ($item_type === 'file') {
            // Get file info to delete the actual file
            $stmt = $pdo->prepare("SELECT system_name FROM files WHERE id = ?");
            $stmt->execute([$item_id]);
            $file = $stmt->fetch();

            if ($file) {
                $file_path = realpath(__DIR__ . '/../../uploads/' . $file['system_name']);
                if ($file_path && file_exists($file_path)) {
                    unlink($file_path);
                }

                // Delete the file record
                $stmt = $pdo->prepare("DELETE FROM files WHERE id = ?");
                $stmt->execute([$item_id]);
            }
        }

        // Redirect back to index page
        header('Location: ../../index.php');
        exit;

    } catch (Exception $e) {
        // Log the error and redirect to an error page or display a message
        error_log("Deletion Error: " . $e->getMessage());
        header('Location: ../../index.php?error=Unable to delete the item');
        exit;
    }
} else {
    // Invalid request method
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Method Not Allowed');
}
?>

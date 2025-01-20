<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_type = $_POST['item_type'];
    $item_id = $_POST['item_id'];
    $new_name = trim($_POST['new_name']);

    if (!empty($new_name)) {
        // Check if the name already exists
        if ($item_type === 'folder') {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM folders WHERE name = ? AND id != ?");
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM files WHERE name = ? AND id != ?");
        }
        $stmt->execute([$new_name, $item_id]);
        $exists = $stmt->fetchColumn();

        if ($exists > 0) {
            echo json_encode(['success' => false, 'error' => 'The name already exists. Please choose a different name.']);
            exit;
        }

        // Update the name if it doesn't exist
        if ($item_type === 'folder') {
            $stmt = $pdo->prepare("UPDATE folders SET name = ? WHERE id = ?");
        } else {
            $stmt = $pdo->prepare("UPDATE files SET name = ? WHERE id = ?");
        }
        $stmt->execute([$new_name, $item_id]);

        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'The name cannot be empty.']);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request method.']);

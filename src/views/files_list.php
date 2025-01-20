<?php
require_once 'src/config/database.php';

// Get current directory and search query
$current_dir = isset($_GET['dir']) ? trim($_GET['dir'], '/') : '';
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// Base queries
$folder_query = "SELECT * FROM folders WHERE parent_dir = ?";
$file_query = "SELECT * FROM files WHERE folder = ?";
$params = [$current_dir];

// Modify queries for search functionality
if ($search_query) {
    $folder_query = "SELECT * FROM folders WHERE name LIKE ?";
    $file_query = "SELECT * FROM files WHERE name LIKE ?";
    $params = ["%$search_query%"];
}

//pag fetch ng folders and files
try {
    $stmt = $pdo->prepare($folder_query);
    $stmt->execute($params);
    $folders = $stmt->fetchAll();

    $stmt = $pdo->prepare($file_query);
    $stmt->execute($params);
    $files = $stmt->fetchAll();
} catch (Exception $e) {
    die("Error fetching data: " . $e->getMessage());
}
?>

<div class="container mx-auto px-4 py-6 bg-gray-100 min-h-screen">
    <div class="bg-white rounded-lg shadow">
        <!-- Grid Layout -->
        <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 p-4">
            <!-- Folders -->
            <?php foreach ($folders as $folder): ?>
                <div class="flex flex-col items-center justify-center bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 relative group">
                    <i class="fas fa-folder text-yellow-400 text-6xl"></i>
                    <a href="?dir=<?= urlencode($current_dir ? $current_dir . '/' . $folder['name'] : $folder['name']) ?>" 
                    class="text-gray-700 hover:text-blue-600 text-center mt-2 truncate w-full text-sm font-semibold">
                        <?= htmlspecialchars($folder['name']) ?>
                    </a>
                    <div class="text-sm text-gray-500 mt-1"><?= date('Y-m-d', strtotime($folder['created_at'])) ?></div>

                    <!-- Actions -->
                    <div class="absolute top-2 right-2 hidden group-hover:flex flex-col gap-2">
                        <button onclick="moveItem('folder', <?= $folder['id'] ?>)" class="text-gray-600 hover:text-blue-600" title="Move">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <button onclick="renameItem('folder', <?= $folder['id'] ?>, '<?= htmlspecialchars($folder['name']) ?>')" class="text-gray-600 hover:text-blue-600" title="Rename">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="showDeleteModal('folder', <?= $folder['id'] ?>)" class="text-gray-600 hover:text-red-600" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Files -->
            <?php foreach ($files as $file): ?>
                <div class="flex flex-col items-center justify-center bg-gray-50 border border-gray-200 rounded-lg p-2 hover:bg-gray-100 relative group">
                    <?php 
                    // Get file extension to determine if it's an image
                    $file_extension = strtolower(pathinfo($file['system_name'], PATHINFO_EXTENSION));
                    $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

                    if (in_array($file_extension, $image_extensions)): ?>
                        <!-- Show image preview -->
                        <a href="uploads/<?= htmlspecialchars($file['system_name']) ?>" target="_blank" class="w-full h-32 flex items-center justify-center">
                            <img 
                                src="uploads/<?= htmlspecialchars($file['system_name']) ?>" 
                                alt="<?= htmlspecialchars($file['name']) ?>" 
                                class="object-cover rounded-lg w-[150px] h-[100px] p-5 mr-2">
                        </a>
                    <?php else: ?>
                        <!-- Default file icon -->
                        <i class="fas fa-file text-gray-400 text-4xl"></i>
                    <?php endif; ?>

                    <!-- File Name -->
                    <a href="uploads/<?= htmlspecialchars($file['system_name']) ?>" target="_blank" 
                    class="text-gray-700 hover:text-blue-600 text-center truncate w-full font-semibold">
                        <?= htmlspecialchars($file['name']) ?>
                    </a>

                    <!-- File Details -->
                    <div class="text-[10px] text-gray-500"><?= formatSize($file['size']) ?></div>
                    <div class="text-sm text-gray-500"><?= date('Y-m-d', strtotime($file['uploaded_at'])) ?></div>

                    <!-- Actions -->
                    <div class="absolute top-2 right-2 hidden group-hover:flex flex-col gap-2">
                        <button onclick="moveItem('file', <?= $file['id'] ?>)" class="text-gray-600 hover:text-blue-600" title="Move">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <button onclick="renameItem('file', <?= $file['id'] ?>, '<?= htmlspecialchars($file['name']) ?>')" class="text-gray-600 hover:text-blue-600" title="Rename">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="showDeleteModal('file', <?= $file['id'] ?>)" class="text-gray-600 hover:text-red-600" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>


            <!-- No Results -->
            <?php if (empty($folders) && empty($files)): ?>
            <div class="col-span-full text-center text-gray-500">
                No items found.
            </div>

            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 transition-opacity duration-300 opacity-0">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md transform scale-95 transition-transform duration-300">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Confirm Deletion</h3>
                <p id="deleteMessage" class="mb-4">Are you sure you want to delete this item?</p>
            </div>
            <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-end gap-4">
                <button onclick="closeDeleteModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <form action="src/actions/delete.php" method="POST" class="inline">
                    <input type="hidden" name="item_type" id="delete_item_type">
                    <input type="hidden" name="item_id" id="delete_item_id">
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showDeleteModal(type, id) {
    const modal = document.getElementById('deleteModal');
    document.getElementById('delete_item_type').value = type;
    document.getElementById('delete_item_id').value = id;
    document.getElementById('deleteMessage').textContent = `Are you sure you want to delete this ${type}?`;

    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.add('opacity-100');
        modal.querySelector('.transform').classList.add('scale-100');
        modal.querySelector('.transform').classList.remove('scale-95');
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');


    modal.classList.remove('opacity-100');
    modal.querySelector('.transform').classList.remove('scale-100');
    modal.querySelector('.transform').classList.add('scale-95');


    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300); 
}
</script>
<script>

function renameItem(type, id, currentName) {
    document.getElementById('rename_item_type').value = type;
    document.getElementById('rename_item_id').value = id;
    document.getElementById('new_name').value = currentName;
    showModal('renameModal');
}

function moveItem(type, id) {
    document.getElementById('move_item_type').value = type;
    document.getElementById('move_item_id').value = id;
    showModal('moveModal');
}
</script>

<?php
function formatSize($size) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    while ($size >= 1024 && $i < count($units) - 1) {
        $size /= 1024;
        $i++;
    }
    return round($size, 2) . ' ' . $units[$i];
}
?>
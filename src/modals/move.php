<div id="moveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <form action="src/actions/move.php" method="POST">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Move Item</h3>
                    <input type="hidden" name="item_type" id="move_item_type">
                    <input type="hidden" name="item_id" id="move_item_id">
                    <select name="target_folder" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                        <option value="">Root Directory</option>
                        <?php
                        $stmt = $pdo->query("SELECT id, name, parent_dir FROM folders ORDER BY parent_dir, name");
                        while ($folder = $stmt->fetch()) {
                            $folder_path = trim($folder['parent_dir'] . '/' . $folder['name'], '/');
                            echo "<option value='" . htmlspecialchars($folder['id']) . "'>" . 
                                 htmlspecialchars($folder_path ? $folder_path : $folder['name']) . 
                                 "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-end gap-4">
                    <button type="button" onclick="hideModal('moveModal')" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Move</button>
                </div>
            </form>
        </div>
    </div>
</div>
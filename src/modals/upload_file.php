<div id="uploadFileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <form action="src/actions/upload_file.php" method="POST" enctype="multipart/form-data">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Upload File</h3>
                    <input type="hidden" name="current_dir" value="<?= htmlspecialchars($current_dir) ?>">
                    <input type="file" name="file" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                </div>
                <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-end gap-4">
                    <button type="button" onclick="hideModal('uploadFileModal')" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
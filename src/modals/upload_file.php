<div id="uploadFileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-md animate-fadeIn">
            <!-- Modal Header -->
            <div class="bg-blue-500 text-white rounded-t-lg px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold">Upload File</h3>
                <button onclick="hideModal('uploadFileModal')" class="text-white hover:text-gray-200 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <form action="src/actions/upload_file.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div class="p-6">
                    <input type="hidden" name="current_dir" value="<?= htmlspecialchars($current_dir) ?>">

                    <!-- File Input -->
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Choose a file to upload:</label>
                    <div class="flex items-center gap-3 border border-gray-300 rounded-lg px-4 py-2 bg-gray-50 hover:bg-gray-100 focus-within:ring-2 focus-within:ring-blue-500">
                        <i class="fas fa-file-upload text-gray-500"></i>
                        <input type="file" name="file" id="file" 
                               class="w-full text-sm text-gray-700 bg-transparent focus:outline-none" 
                               required>
                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-end gap-4">
                    <button type="button" onclick="hideModal('uploadFileModal')" 
                            class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg focus:outline-none">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

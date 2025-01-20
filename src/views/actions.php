<div class="flex flex-wrap gap-4 mb-6 items-center justify-between px-4 ">
    <!-- Action Buttons -->
    <div class="flex gap-4 flex-wrap">
        <button 
            onclick="showModal('createFolderModal')" 
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center"
        >
            <i class="fas fa-folder-plus mr-2"></i>New Folder
        </button>
        <button 
            onclick="showModal('uploadFileModal')" 
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center"
        >
            <i class="fas fa-upload mr-2"></i>Upload File
        </button>
    </div>

    <!-- Search Bar -->
    <div class="w-full sm:w-auto flex-1 max-w-md sm:ml-4">
        <form action="" method="GET" class="relative">
            <input 
                type="text" 
                name="search" 
                placeholder="Search files and folders..." 
                class="w-full px-4 py-2 pr-10 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
            >
            <button 
                type="submit" 
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
            >
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</div>


<script>
function showModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function hideModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
</script>
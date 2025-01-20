<div id="renameModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <form id="renameForm" action="src/actions/rename.php" method="POST">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Rename Item</h3>
                    <!-- Error Message Container -->
                    <div id="renameError" class="text-red-500 text-sm mb-4 hidden"></div>
                    <input type="hidden" name="item_type" id="rename_item_type">
                    <input type="hidden" name="item_id" id="rename_item_id">
                    <input type="text" name="new_name" id="new_name" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="New name" required>
                </div>
                <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-end gap-4">
                    <button type="button" onclick="hideModal('renameModal')" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Rename</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function renameItem(type, id) {
    document.getElementById('rename_item_type').value = type;
    document.getElementById('rename_item_id').value = id;
    document.getElementById('new_name').value = ''; // Clear any previous input
    document.getElementById('renameError').classList.add('hidden'); // Hide error messages
    showModal('renameModal');
}

document.getElementById('renameForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const renameError = document.getElementById('renameError');

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();

        if (result.success) {
            // Refresh the page or dynamically update the UI
            location.reload();
        } else {
            // Show error message
            renameError.textContent = result.error || 'An error occurred. Please try again.';
            renameError.classList.remove('hidden');
        }
    } catch (error) {
        renameError.textContent = 'A network error occurred. Please try again.';
        renameError.classList.remove('hidden');
    }
});
</script>

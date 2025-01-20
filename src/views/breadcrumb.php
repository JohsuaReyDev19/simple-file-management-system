<?php
$current_dir = isset($_GET['dir']) ? $_GET['dir'] : '';
$path_parts = $current_dir ? explode('/', $current_dir) : [];
?>
<nav class="flex mb-4" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="?dir=" class="text-gray-700 hover:text-blue-600">
                <i class="fas fa-home mr-2"></i>Home
            </a>
        </li>
        <?php
        $path = '';
        foreach ($path_parts as $part) {
            $path .= $part . '/';
            echo '<li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="?dir=' . urlencode(trim($path, '/')) . '" class="text-gray-700 hover:text-blue-600">' . htmlspecialchars($part) . '</a>
                </div>
            </li>';
        }
        ?>
    </ol>
</nav>
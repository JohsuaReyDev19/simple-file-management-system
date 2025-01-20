<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>file management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon"  href="/prmsu.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <?php include 'src/views/header.php'; ?>
    <div class="container mx-auto px-4 py-8">
        <?php include 'src/views/breadcrumb.php'; ?>
        <?php include 'src/views/actions.php'; ?>
        <?php include 'src/views/files_list.php'; ?>
    </div>
    <?php include 'src/modals/create_folder.php'; ?>
    <?php include 'src/modals/upload_file.php'; ?>
    <?php include 'src/modals/rename.php'; ?>
    <?php include 'src/modals/move.php'; ?>
</body>
</html>
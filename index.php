<?php
// index.php
require_once 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar Navigation -->
        <div class="w-64 bg-white shadow-lg">
            <div class="p-6">
                <h1 class="text-xl font-bold text-gray-800 mb-4">Simple CMS</h1>
                <nav class="mt-6">
                    <?php echo getNavigationMenu($conn); ?>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow p-8">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">Welcome to Simple CMS</h1>
                <p class="text-gray-600">Select a category from the navigation menu to explore content.</p>
            </div>
        </div>
    </div>
</body>
</html>

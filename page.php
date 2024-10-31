
<?php
// page.php
require_once 'config.php';

$slug = mysqli_real_escape_string($conn, $_GET['slug'] ?? '');

$query = "SELECT p.*, c.name as category_name, c.slug as category_slug 
          FROM pages p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE p.slug = '$slug'";
$result = mysqli_query($conn, $query);
$page = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page ? htmlspecialchars($page['title']) : 'Page Not Found'; ?> - Simple CMS</title>
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
                <?php if ($page): ?>
                    <!-- Breadcrumb -->
                    <div class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
                        <a href="index.php" class="hover:text-blue-600">Home</a>
                        <span>→</span>
                        <a href="category.php?slug=<?php echo $page['category_slug']; ?>" 
                           class="hover:text-blue-600">
                            <?php echo htmlspecialchars($page['category_name']); ?>
                        </a>
                    </div>

                    <article class="bg-white rounded-lg shadow-md p-8">
                        <h1 class="text-3xl font-bold text-gray-800 mb-4">
                            <?php echo htmlspecialchars($page['title']); ?>
                        </h1>
                        
                        <div class="text-sm text-gray-500 mb-6">
                            Posted on <?php echo date('F j, Y', strtotime($page['created_at'])); ?>
                        </div>
                        
                        <div class="prose max-w-none">
                            <?php echo nl2br(htmlspecialchars($page['content'])); ?>
                        </div>
                    </article>

                <?php else: ?>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h1 class="text-3xl font-bold text-gray-800 mb-4">Page Not Found</h1>
                        <p class="text-gray-600">The requested page does not exist.</p>
                        <a href="index.php" class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                            ← Return to Homepage
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
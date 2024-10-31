
<?php
// category.php
require_once 'config.php';

$slug = mysqli_real_escape_string($conn, $_GET['slug'] ?? '');

$category_query = "SELECT * FROM categories WHERE slug = '$slug'";
$category_result = mysqli_query($conn, $category_query);
$category = mysqli_fetch_assoc($category_result);

if ($category) {
    $pages_query = "SELECT * FROM pages WHERE category_id = " . $category['id'];
    $pages_result = mysqli_query($conn, $pages_query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $category ? htmlspecialchars($category['name']) : 'Category Not Found'; ?> - Simple CMS</title>
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
                <?php if ($category): ?>
                    <h1 class="text-3xl font-bold text-gray-800 mb-6">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </h1>
                    
                    <?php if (mysqli_num_rows($pages_result) > 0): ?>
                        <div class="grid gap-6">
                            <?php while($page = mysqli_fetch_assoc($pages_result)): ?>
                                <article class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                                    <h2 class="text-xl font-semibold text-gray-800 mb-2">
                                        <a href="page.php?slug=<?php echo $page['slug']; ?>" 
                                           class="hover:text-blue-600 transition-colors">
                                            <?php echo htmlspecialchars($page['title']); ?>
                                        </a>
                                    </h2>
                                    <div class="text-sm text-gray-500">
                                        Posted on <?php echo date('F j, Y', strtotime($page['created_at'])); ?>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-600">No pages found in this category.</p>
                    <?php endif; ?>
                    <?php else: ?>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h1 class="text-3xl font-bold text-gray-800 mb-4">Category Not Found</h1>
                        <p class="text-gray-600">The requested category does not exist.</p>
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
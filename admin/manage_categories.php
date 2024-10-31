
<?php
// admin/manage_categories.php
require_once '../config.php';

// Delete category if requested
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM categories WHERE id = $id");
    header('Location: manage_categories.php');
    exit();
}

$query = "SELECT c.*, p.name as parent_name 
          FROM categories c 
          LEFT JOIN categories p ON c.parent_id = p.id 
          ORDER BY c.name";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories - CMS Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-xl font-bold text-gray-800">CMS Admin</h1>
                        </div>
                        <div class="ml-6 flex space-x-8">
                            <a href="manage_categories.php" 
                               class="border-b-2 border-blue-500 text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">
                                Categories
                            </a>
                            <a href="manage_pages.php" 
                               class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 text-sm font-medium">
                                Pages
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <a href="add_category.php" 
                           class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Add New Category
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex-grow container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Parent Category
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Slug
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($category = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    <?php echo $category['parent_name'] ? htmlspecialchars($category['parent_name']) : '-'; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    <?php echo htmlspecialchars($category['slug']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    <?php echo date('M j, Y', strtotime($category['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="edit_category.php?id=<?php echo $category['id']; ?>" 
                                       class="text-blue-600 hover:text-blue-900 mr-4">Edit</a>
                                    <a href="?delete=<?php echo $category['id']; ?>" 
                                       class="text-red-600 hover:text-red-900"
                                       onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>


<?php
// admin/edit_category.php
require_once '../config.php';

$id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $parent_id = !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null;
    $slug = strtolower(str_replace(' ', '-', $name));
    
    $query = "UPDATE categories SET 
              name = '$name',
              parent_id = " . ($parent_id ? $parent_id : "NULL") . ",
              slug = '$slug'
              WHERE id = $id";
              
    if (mysqli_query($conn, $query)) {
        $success = "Category updated successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Get category data
$category_query = "SELECT * FROM categories WHERE id = $id";
$category_result = mysqli_query($conn, $category_query);
$category = mysqli_fetch_assoc($category_result);

if (!$category) {
    header('Location: manage_categories.php');
    exit();
}

// Get all categories for parent selection
$categories_query = "SELECT * FROM categories WHERE id != $id ORDER BY name";
$categories_result = mysqli_query($conn, $categories_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category - CMS Admin</title>
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
                </div>
            </div>
        </nav>

        <div class="flex-grow container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Category</h2>
                
                <?php if (isset($success)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Category Name
                        </label>
                        <input type="text" name="name" required
                            value="<?php echo htmlspecialchars($category['name']); ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Parent Category
                        </label>
                        <select name="parent_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">None</option>
                            <?php while($parent = mysqli_fetch_assoc($categories_result)): ?>
                                <option value="<?php echo $parent['id']; ?>"
                                    <?php echo $parent['id'] == $category['parent_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($parent['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="flex justify-between">
                        <a href="manage_categories.php" 
                           class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

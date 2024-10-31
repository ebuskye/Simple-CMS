<?php
// admin/add_category.php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $parent_id = !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null;
    $slug = strtolower(str_replace(' ', '-', $name));
    
    $query = "INSERT INTO categories (name, parent_id, slug) VALUES 
        ('$name', " . ($parent_id ? $parent_id : "NULL") . ", '$slug')";
    
    if (mysqli_query($conn, $query)) {
        header('Location: manage_categories.php');
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

$categories_query = "SELECT * FROM categories";
$categories_result = mysqli_query($conn, $categories_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category - CMS Admin</title>
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
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex-grow container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Category</h2>
                
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Parent Category
                        </label>
                        <select name="parent_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">None</option>
                            <?php while($category = mysqli_fetch_assoc($categories_result)): ?>
                                <option value="<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Add Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>


<?php
// admin/edit_page.php
require_once '../config.php';

$id = intval($_GET['id'] ?? 0);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category_id = intval($_POST['category_id']);
    $slug = strtolower(str_replace(' ', '-', $title));
    
    $query = "UPDATE pages SET 
              title = '$title',
              content = '$content',
              category_id = $category_id,
              slug = '$slug'
              WHERE id = $id";
              
    if (mysqli_query($conn, $query)) {
        $success = "Page updated successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Get page data
$page_query = "SELECT * FROM pages WHERE id = $id";
$page_result = mysqli_query($conn, $page_query);
$page = mysqli_fetch_assoc($page_result);

if (!$page) {
    header('Location: manage_pages.php');
    exit();
}

// Get categories for dropdown
$categories_query = "SELECT * FROM categories ORDER BY name";
$categories_result = mysqli_query($conn, $categories_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Page - CMS Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Add TinyMCE for better content editing -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content',
            height: 500,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic backcolor | \
                     alignleft aligncenter alignright alignjustify | \
                     bullist numlist outdent indent | removeformat | help'
        });
    </script>
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
                               class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 text-sm font-medium">
                                Categories
                            </a>
                            <a href="manage_pages.php" 
                               class="border-b-2 border-blue-500 text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">
                                Pages
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex-grow container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Page</h2>
                
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
                            Page Title
                        </label>
                        <input type="text" name="title" required
                            value="<?php echo htmlspecialchars($page['title']); ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Category
                        </label>
                        <select name="category_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <?php while($category = mysqli_fetch_assoc($categories_result)): ?>
                                <option value="<?php echo $category['id']; ?>"
                                    <?php echo $category['id'] == $page['category_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Content
                        </label>
                        <textarea id="content" name="content" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"><?php echo htmlspecialchars($page['content']); ?></textarea>
                    </div>

                    <div class="flex justify-between">
                        <a href="manage_pages.php" 
                           class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Update Page
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php
// config.php
$host = 'localhost';
$dbname = 'simple_cms';
$username = 'root';
$password = '';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Database structure
/*
CREATE DATABASE simple_cms;

CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    parent_id INT DEFAULT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE pages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    slug VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
*/

// Common Navigation Function
function getNavigationMenu($conn, $parent_id = null) {
    $query = "SELECT * FROM categories WHERE " . 
        ($parent_id === null ? "parent_id IS NULL" : "parent_id = " . intval($parent_id));
    
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 0) {
        return '';
    }
    
    $menu = '<ul class="space-y-2">';
    while ($category = mysqli_fetch_assoc($result)) {
        $menu .= '<li class="hover:bg-gray-100 rounded-lg">';
        $menu .= '<a href="category.php?slug=' . $category['slug'] . '" 
            class="block px-4 py-2 text-gray-700 hover:text-blue-600 transition-colors">' . 
            htmlspecialchars($category['name']) . '</a>';
        
        // Recursively get subcategories
        $submenu = getNavigationMenu($conn, $category['id']);
        if ($submenu) {
            $menu .= '<div class="ml-4">' . $submenu . '</div>';
        }
        
        $menu .= '</li>';
    }
    $menu .= '</ul>';
    
    return $menu;
}
?>

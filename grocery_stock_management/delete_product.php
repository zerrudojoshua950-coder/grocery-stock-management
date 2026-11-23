<?php
// Connect to the database
require_once 'config.php';
require_once 'includes/functions.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('products.php');
}

// Check the security token
verify_csrf();

// Get the product ID
$id = $_POST['id'] ?? 0;

// Check if the ID is valid
if (!is_numeric($id) || $id <= 0) {
    set_flash('danger', 'Invalid product selected.');
    redirect('products.php');
}

// Delete the product
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

// Show a message
if ($stmt->rowCount() > 0) {
    set_flash('success', 'Product deleted successfully.');
} else {
    set_flash('warning', 'Product was not found.');
}

// Go back to the products page
redirect('products.php');
?>
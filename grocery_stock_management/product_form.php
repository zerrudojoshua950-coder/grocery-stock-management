
<?php
// Connect to the database
require_once 'config.php';
require_once 'includes/functions.php';

// Get the product ID
$id = $_GET['id'] ?? null;

// Set the default product information
$product = [
    'product_name' => '',
    'sku' => '',
    'category' => 'Other',
    'quantity' => 0,
    'unit' => 'pcs',
    'cost_price' => '0.00',
    'selling_price' => '0.00',
    'reorder_level' => 5,
    'supplier' => '',
    'expiry_date' => ''
];

// Check if editing an existing product
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);

    $found = $stmt->fetch();

    if (!$found) {
        set_flash('danger', 'Product not found.');
        redirect('products.php');
    }

    $product = $found;
}

// Store error messages
$errors = [];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    verify_csrf();

    // Get the values from the form
    $product = [
        'product_name' => trim($_POST['product_name'] ?? ''),
        'sku' => trim($_POST['sku'] ?? ''),
        'category' => trim($_POST['category'] ?? ''),
        'quantity' => filter_var($_POST['quantity'] ?? '', FILTER_VALIDATE_INT),
        'unit' => trim($_POST['unit'] ?? ''),
        'cost_price' => $_POST['cost_price'] ?? '',
        'selling_price' => $_POST['selling_price'] ?? '',
        'reorder_level' => filter_var($_POST['reorder_level'] ?? '', FILTER_VALIDATE_INT),
        'supplier' => trim($_POST['supplier'] ?? ''),
        'expiry_date' => trim($_POST['expiry_date'] ?? '')
    ];

    // Check the required fields
    if ($product['product_name'] == '') {
        $errors[] = 'Product name is required.';
    }

    if ($product['sku'] == '') {
        $errors[] = 'SKU is required.';
    }

    if ($product['category'] == '') {
        $errors[] = 'Category is required.';
    }

    if ($product['quantity'] === false || $product['quantity'] < 0) {
        $errors[] = 'Invalid quantity.';
    }

    if ($product['reorder_level'] === false || $product['reorder_level'] < 0) {
        $errors[] = 'Invalid reorder level.';
    }

    if ($product['unit'] == '') {
        $errors[] = 'Unit is required.';
    }

    // Check the prices
    if (!is_numeric($product['cost_price']) || $product['cost_price'] < 0) {
        $errors[] = 'Invalid cost price.';
    }

    if (!is_numeric($product['selling_price']) || $product['selling_price'] < 0) {
        $errors[] = 'Invalid selling price.';
    }

    // Make the expiry date empty if no date was entered
    if ($product['expiry_date'] == '') {
        $product['expiry_date'] = null;
    }

    // Save the product if there are no errors
    if (empty($errors)) {

        try {

            // Product information
            $values = [
                $product['product_name'],
                $product['sku'],
                $product['category'],
                $product['quantity'],
                $product['unit'],
                $product['cost_price'],
                $product['selling_price'],
                $product['reorder_level'],
                $product['supplier'],
                $product['expiry_date']
            ];

            if ($id) {

                // Update an existing product
                $values[] = $id;

                $sql = "UPDATE products SET
                        product_name = ?,
                        sku = ?,
                        category = ?,
                        quantity = ?,
                        unit = ?,
                        cost_price = ?,
                        selling_price = ?,
                        reorder_level = ?,
                        supplier = ?,
                        expiry_date = ?
                        WHERE id = ?";

                $stmt = $pdo->prepare($sql);
                $stmt->execute($values);

                set_flash('success', 'Product updated successfully.');

            } else {

                // Add a new product
                $sql = "INSERT INTO products
                        (product_name, sku, category, quantity, unit,
                        cost_price, selling_price, reorder_level,
                        supplier, expiry_date)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = $pdo->prepare($sql);
                $stmt->execute($values);

                set_flash('success', 'Product added successfully.');
            }

            // Return to the products page
            redirect('products.php');

        } catch (PDOException $ex) {

            if ($ex->getCode() == '23000') {
                $errors[] = 'SKU already exists. Please use another SKU.';
            } else {
                $errors[] = 'Unable to save the product.';
            }
        }
    }
}

// Set the page title
$pageTitle = $id ? 'Edit Product' : 'Add Product';

require 'includes/header.php';
?>

<!-- Page heading -->
<div class="mb-4">
    <a href="products.php" class="text-success text-decoration-none">
        ← Back to Products
    </a>

    <h1 class="h2 fw-bold mt-2">
        <?= $id ? 'Edit Product' : 'Add New Product' ?>
    </h1>

    <p class="text-secondary">
        Enter the grocery product information below.
    </p>
</div>

<!-- Product form -->
<div class="panel form-panel">

    <!-- Display errors -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <strong>Please check the following:</strong>

            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST">

        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Product Name *</label>
                <input type="text" name="product_name" class="form-control"
                    required maxlength="120"
                    value="<?= e($product['product_name']) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">SKU / Product Code *</label>
                <input type="text" name="sku" class="form-control"
                    required maxlength="50"
                    value="<?= e($product['sku']) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Category *</label>

                <select name="category" class="form-select" required>
                    <?php
                    $categories = [
                        'Canned Goods', 'Beverages', 'Snacks', 'Dairy',
                        'Bakery', 'Rice & Grains', 'Personal Care',
                        'Household', 'Frozen Foods', 'Other'
                    ];

                    foreach ($categories as $cat):
                    ?>
                        <option value="<?= e($cat) ?>"
                            <?= $product['category'] == $cat ? 'selected' : '' ?>>
                            <?= e($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Quantity *</label>
                <input type="number" name="quantity" class="form-control"
                    min="0" required
                    value="<?= e($product['quantity']) ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label">Unit *</label>

                <select name="unit" class="form-select" required>
                    <?php
                    $units = ['pcs', 'packs', 'bottles', 'cans',
                              'kg', 'grams', 'liters', 'boxes'];

                    foreach ($units as $unit):
                    ?>
                        <option value="<?= e($unit) ?>"
                            <?= $product['unit'] == $unit ? 'selected' : '' ?>>
                            <?= e($unit) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Cost Price (₱) *</label>
                <input type="number" name="cost_price" class="form-control"
                    min="0" step="0.01" required
                    value="<?= e($product['cost_price']) ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Selling Price (₱) *</label>
                <input type="number" name="selling_price" class="form-control"
                    min="0" step="0.01" required
                    value="<?= e($product['selling_price']) ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Reorder Level *</label>
                <input type="number" name="reorder_level" class="form-control"
                    min="0" required
                    value="<?= e($product['reorder_level']) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Supplier</label>
                <input type="text" name="supplier" class="form-control"
                    maxlength="120"
                    value="<?= e($product['supplier']) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Expiry Date</label>
                <input type="date" name="expiry_date" class="form-control"
                    value="<?= e($product['expiry_date'] ?? '') ?>">
            </div>

        </div>

        <!-- Form buttons -->
        <div class="d-flex gap-2 mt-4">

            <button type="submit" class="btn btn-success px-4">
                <?= $id ? 'Save Changes' : 'Add Product' ?>
            </button>

            <a href="products.php" class="btn btn-light">
                Cancel
            </a>

        </div>

    </form>
</div>

<?php require 'includes/footer.php'; ?>

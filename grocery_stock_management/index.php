<?php
// Connect to the database
require_once 'config.php';
require_once 'includes/functions.php';

// Get the total number of products
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

// Get the total quantity of all products
$totalUnits = $pdo->query("SELECT COALESCE(SUM(quantity), 0) FROM products")->fetchColumn();

// Get the number of products with low stock
$lowStock = $pdo->query("SELECT COUNT(*) FROM products WHERE quantity <= reorder_level")->fetchColumn();

// Calculate the total inventory cost
$inventoryValue = $pdo->query("SELECT COALESCE(SUM(quantity * cost_price), 0) FROM products")->fetchColumn();

// Get the six most recently updated products
$recent = $pdo->query("SELECT * FROM products ORDER BY updated_at DESC LIMIT 6")->fetchAll();

// Set the page title
$pageTitle = 'Dashboard';

// Include the header
require 'includes/header.php';
?>

<!-- Dashboard heading -->
<div class="d-flex justify-content-between align-items-center flex-wrap mb-4 gap-2">
    <div>
        <p class="eyebrow mb-1">INVENTORY OVERVIEW</p>
        <h1 class="h2 fw-bold">Dashboard</h1>
        <p class="text-secondary">Welcome to your grocery stock manager.</p>
    </div>

    <a href="product_form.php" class="btn btn-success">+ Add Product</a>
</div>

<!-- Dashboard information -->
<div class="row g-3 mb-4">

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-label">Total Products</div>
            <div class="stat-value"><?= number_format($totalProducts) ?></div>
            <div class="stat-note">Different product records</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-label">Units in Stock</div>
            <div class="stat-value"><?= number_format($totalUnits) ?></div>
            <div class="stat-note">Total available quantity</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-label">Low Stock Items</div>
            <div class="stat-value text-warning"><?= number_format($lowStock) ?></div>
            <div class="stat-note">Products that need restocking</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-label">Inventory Cost Value</div>
            <div class="stat-value">₱<?= number_format($inventoryValue, 2) ?></div>
            <div class="stat-note">Quantity multiplied by cost price</div>
        </div>
    </div>

</div>

<!-- Recently updated products -->
<div class="panel">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3 gap-2">
        <div>
            <h2 class="h5 fw-bold">Recently Updated Products</h2>
            <p class="text-secondary small">Latest changes in your inventory.</p>
        </div>

        <a href="products.php" class="btn btn-outline-success btn-sm">
            View All Products
        </a>
    </div>

    <?php if (count($recent) == 0): ?>

        <!-- Message if there are no products -->
        <div class="empty-state">
            <div class="display-6">🛒</div>
            <h3 class="h6 mt-2">No products yet</h3>
            <p class="text-secondary">Add your first grocery product.</p>

            <a href="product_form.php" class="btn btn-success btn-sm">
                Add First Product
            </a>
        </div>

    <?php else: ?>

        <!-- Products table -->
        <div class="table-responsive">
            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($recent as $p): ?>

                        <tr>
                            <td>
                                <strong><?= e($p['product_name']) ?></strong>
                                <div class="small text-secondary">
                                    <?= e($p['sku']) ?>
                                </div>
                            </td>

                            <td><?= e($p['category']) ?></td>

                            <td>
                                <?= number_format($p['quantity']) ?>
                                <?= e($p['unit']) ?>
                            </td>

                            <td>
                                ₱<?= number_format($p['selling_price'], 2) ?>
                            </td>

                            <td>
                                <?php if ($p['quantity'] <= $p['reorder_level']): ?>

                                    <span class="badge text-bg-warning">
                                        Low Stock
                                    </span>

                                <?php else: ?>

                                    <span class="badge text-bg-success">
                                        In Stock
                                    </span>

                                <?php endif; ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>

    <?php endif; ?>

</div>

<?php
// Include the footer
require 'includes/footer.php';
?>
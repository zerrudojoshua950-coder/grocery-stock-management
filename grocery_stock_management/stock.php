
<?php
// Connect to the database
require_once 'config.php';
require_once 'includes/functions.php';

// Get all products and arrange low-stock items first
$sql = "SELECT product_name, sku, category, quantity, unit,
        reorder_level, expiry_date
        FROM products
        ORDER BY (quantity <= reorder_level) DESC,
        quantity ASC, product_name ASC";

$stmt = $pdo->query($sql);
$products = $stmt->fetchAll();

// Set the page title
$pageTitle = 'Stock Overview';

require 'includes/header.php';
?>

<!-- Page heading -->
<div class="mb-4">
    <p class="eyebrow mb-1">STOCK MONITORING</p>

    <h1 class="h2 fw-bold mb-1">
        Stock Overview
    </h1>

    <p class="text-secondary">
        Check product quantities and see which items need restocking.
    </p>
</div>

<!-- Stock table -->
<div class="panel">

    <div class="table-responsive">

        <table class="table align-middle">

            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Available</th>
                    <th>Reorder Level</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($products as $p): ?>

                    <tr>

                        <!-- Product name and SKU -->
                        <td>
                            <strong><?= e($p['product_name']) ?></strong>

                            <div class="small text-secondary">
                                <?= e($p['sku']) ?>
                            </div>
                        </td>

                        <!-- Product category -->
                        <td>
                            <?= e($p['category']) ?>
                        </td>

                        <!-- Available quantity -->
                        <td>
                            <?= number_format($p['quantity']) ?>
                            <?= e($p['unit']) ?>
                        </td>

                        <!-- Reorder level -->
                        <td>
                            <?= number_format($p['reorder_level']) ?>
                        </td>

                        <!-- Expiry date -->
                        <td>
                            <?= $p['expiry_date'] ? e($p['expiry_date']) : '—' ?>
                        </td>

                        <!-- Stock status -->
                        <td>

                            <?php if ($p['quantity'] <= $p['reorder_level']): ?>

                                <span class="badge text-bg-warning">
                                    Restock Needed
                                </span>

                            <?php else: ?>

                                <span class="badge text-bg-success">
                                    Available
                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

                <!-- Show message if there are no products -->
                <?php if (empty($products)): ?>

                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">
                            No stock records available.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?php require 'includes/footer.php'; ?>

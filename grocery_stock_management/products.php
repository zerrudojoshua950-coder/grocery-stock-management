
<?php
require_once 'config.php';
require_once 'includes/functions.php';

$search = trim($_GET['search'] ?? '');
$sql = "SELECT * FROM products";
$params = [];

if ($search !== '') {
    $sql .= " WHERE product_name LIKE :term1
              OR sku LIKE :term2
              OR category LIKE :term3";

    $params['term1'] = "%{$search}%";
    $params['term2'] = "%{$search}%";
    $params['term3'] = "%{$search}%";
}

$sql .= " ORDER BY product_name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$pageTitle = 'Products';
require 'includes/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <p class="eyebrow mb-1">INVENTORY RECORDS</p>
        <h1 class="h2 fw-bold mb-1">Products</h1>
        <p class="text-secondary mb-0">Manage all grocery items in your store.</p>
    </div>
    <a href="product_form.php" class="btn btn-success">＋ Add Product</a>
</div>

<div class="panel">
    <form class="row g-2 mb-3" method="get">
        <div class="col-sm-9">
            <input class="form-control" type="search"
                   name="search"
                   value="<?= e($search) ?>"
                   placeholder="Search product name, SKU, or category">
        </div>
        <div class="col-sm-3 d-grid">
            <button class="btn btn-outline-success">Search</button>
        </div>
    </form>

    <div class="d-flex justify-content-between small text-secondary mb-2">
        <span><?= count($products) ?> product(s) found</span>
        <?php if ($search !== ''): ?>
            <a href="products.php">Clear search</a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Stock</th>
                    <th>Cost</th>
                    <th>Selling Price</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td class="fw-semibold">
                        <?= e($p['product_name']) ?>
                        <div class="small text-secondary">
                            <?= e($p['sku']) ?>
                        </div>
                    </td>
                    <td><?= e($p['category']) ?></td>
                    <td>
                        <?= number_format((int)$p['quantity']) ?>
                        <?= e($p['unit']) ?>
                    </td>
                    <td>₱<?= number_format((float)$p['cost_price'], 2) ?></td>
                    <td>₱<?= number_format((float)$p['selling_price'], 2) ?></td>
                    <td>
                        <?php if ((int)$p['quantity'] <= (int)$p['reorder_level']): ?>
                            <span class="badge text-bg-warning">Low Stock</span>
                        <?php else: ?>
                            <span class="badge text-bg-success">In Stock</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end text-nowrap">
                        <a class="btn btn-sm btn-outline-primary"
                           href="product_form.php?id=<?= (int)$p['id'] ?>">
                            Edit
                        </a>

                        <form class="d-inline" method="post"
                              action="delete_product.php"
                              onsubmit="return confirm('Delete this product permanently?');">

                            <input type="hidden" name="csrf_token"
                                   value="<?= e(csrf_token()) ?>">

                            <input type="hidden" name="id"
                                   value="<?= (int)$p['id'] ?>">

                            <button class="btn btn-sm btn-outline-danger">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php if (!$products): ?>
                <tr>
                    <td colspan="7" class="text-center text-secondary py-4">
                        No matching products found.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'includes/footer.php'; ?>

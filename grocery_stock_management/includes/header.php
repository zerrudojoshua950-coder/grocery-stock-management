<?php

// Include the functions file
require_once __DIR__ . '/functions.php';

// Set the page title
$pageTitle = $pageTitle ?? 'Grocery Stock Management';

// Get the current page
$currentPage = basename($_SERVER['PHP_SELF']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= e($pageTitle) ?> | FreshStock</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="assets/style.css" rel="stylesheet">
</head>

<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">

    <div class="container">

        <a class="navbar-brand" href="index.php">
            🥬 FreshStock
        </a>

        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="mainNav">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>"
                       href="index.php">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'products.php' || $currentPage == 'product_form.php') ? 'active' : '' ?>"
                       href="products.php">
                        Products
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'stock.php' ? 'active' : '' ?>"
                       href="stock.php">
                        Stock Overview
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>

<!-- Main Content -->
<main class="container py-4">

<?php show_flash(); ?>
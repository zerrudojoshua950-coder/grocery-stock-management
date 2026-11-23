<?php

// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// This function protects output
function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Create a security token
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// Check the security token
function verify_csrf() {
    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        exit('Invalid request. Please try again.');
    }
}

// Redirect to another page
function redirect($url) {
    header("Location: " . $url);
    exit;
}

// Save a message
function set_flash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

// Display the message
function show_flash() {
    if (isset($_SESSION['flash'])) {

        $flash = $_SESSION['flash'];

        echo '<div class="alert alert-' . e($flash['type']) . ' alert-dismissible fade show">';
        echo e($flash['message']);
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        echo '</div>';

        unset($_SESSION['flash']);
    }
}

?>
<?php
// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Check if the logged-in user is an admin
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

// Set a flash message for one-time use (e.g., after form submission)
function set_flash_message($message) {
    $_SESSION['flash_message'] = $message;
}

// Get and display the flash message
function get_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return "<div class='alert alert-info'>$message</div>";
    }
    return '';
}

// Check if a user is a laborer
function is_laborer() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'laborer';
}

// Check if a user is an employer
function is_employer() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'employer';
}

// Get the user's full name
function get_user_name() {
    if (isset($_SESSION['user_id'])) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT name FROM users WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        return $user ? $user['name'] : 'Guest';
    }
    return 'Guest';
}

// Redirect to a page
function redirect($url) {
    header("Location: $url");
    exit();
}

// Display the current user's role (for debugging purposes)
function get_user_role() {
    return isset($_SESSION['role']) ? $_SESSION['role'] : 'Guest';
}

// Function to log the user out
function logout() {
    session_unset();
    session_destroy();
    redirect('login.php');
}

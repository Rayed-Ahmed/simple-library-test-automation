<?php
// /includes/functions.php
require_once __DIR__ . '/../config/database.php';

// Function to check if a user is logged in and redirect if not
function check_login() {
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: " . BASE_PATH . "login.php");
        exit;
    }
}

// Function to check user role
function check_role($role) {
    check_login();
    if ($_SESSION["role"] !== $role) {
        // Redirect to their own dashboard
        header("location: " . BASE_PATH . $_SESSION["role"] . "/index.php");
        exit;
    }
}

// Function to get all books
function get_all_books($conn) {
    $sql = "SELECT * FROM books ORDER BY title";
    $result = $conn->query($sql);
    return $result;
}

// Function to get a single book by ID
function get_book_by_id($conn, $book_id) {
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Function to get all users
function get_all_users($conn) {
    $sql = "SELECT id, full_name, email, role, is_active FROM users ORDER BY full_name";
    $result = $conn->query($sql);
    return $result;
}
?>
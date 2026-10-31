<?php
// /includes/header.php
require_once __DIR__ . '/../config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📚 CS-Shelf</title>
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="<?php echo BASE_PATH; ?>">CS-Shelf</a></h1>
            <nav>
                <ul>
                    <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                        <li><a href="<?php echo BASE_PATH . htmlspecialchars($_SESSION['role']); ?>/index.php">Dashboard</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>profile.php">Profile</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_PATH; ?>login.php">Login</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">
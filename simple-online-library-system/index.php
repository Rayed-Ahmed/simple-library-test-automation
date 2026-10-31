<?php
// index.php
session_start();
require_once __DIR__ . '/config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CS-Shelf | Library System</title>
    <!-- <link rel="stylesheet" href="assets/style.css"> -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f0f4ff, #d9e4ff);
            color: #333;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: #fff;
            padding: 15px 30px;
            font-size: 1.5rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        header span {
            margin-right: 10px;
            font-size: 1.8rem;
        }

        /* Landing content */
        .landing-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 80px);
            text-align: center;
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 10px;
            color: #222;
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            color: #555;
        }

        .buttons {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 30px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .btn-login {
            background: linear-gradient(135deg, #007bff, #0056b3);
        }
        .btn-login:hover {
            transform: translateY(-3px);
            background: linear-gradient(135deg, #0056b3, #003d80);
        }

        .btn-register {
            background: linear-gradient(135deg, #28a745, #1e7e34);
        }
        .btn-register:hover {
            transform: translateY(-3px);
            background: linear-gradient(135deg, #1e7e34, #155d27);
        }

        .btn-dashboard {
            background: linear-gradient(135deg, #ff9800, #e68900);
        }
        .btn-dashboard:hover {
            transform: translateY(-3px);
            background: linear-gradient(135deg, #e68900, #cc7700);
        }

        .btn-logout {
            background: linear-gradient(135deg, #dc3545, #b02a37);
        }
        .btn-logout:hover {
            transform: translateY(-3px);
            background: linear-gradient(135deg, #b02a37, #7a1c25);
        }
    </style>
</head>
<body>
    <header>
        <span>📚</span> CS-Shelf
    </header>

    <!-- Landing Page -->
    <div class="landing-container">
        <h1>Welcome to CS-Shelf</h1>
        <p>Your digital library — manage, borrow, and explore books with ease.</p>

        <div class="buttons">
            <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                <a href="<?php echo $_SESSION['role']; ?>/index.php" class="btn btn-dashboard">Go to Dashboard</a>
                <a href="logout.php" class="btn btn-logout">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-login">Login</a>
                <a href="register.php" class="btn btn-register">Register</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

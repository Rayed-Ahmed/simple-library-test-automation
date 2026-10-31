<?php
// register.php
require_once __DIR__ . '/config/database.php';
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (PHP logic remains the same)
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!in_array($role, ['student', 'teacher'])) {
        $error = "Invalid role selected.";
    } else if (!empty($full_name) && !empty($email) && !empty($password) && !empty($role)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $role);

            try {
                if ($stmt->execute()) {
                    $stmt->close();
                    header("Location: " . BASE_PATH . "login.php");
                    exit();
                }
            } catch (mysqli_sql_exception $exception) {
                // MySQLi uses strict error reporting by default on modern PHP.
                // A duplicate UNIQUE email therefore throws instead of returning false.
                // Keep the user-facing message clear and do not expose database details.
                if ((int) $exception->getCode() === 1062) {
                    $error = "This email is already registered.";
                } else {
                    error_log("Registration failed: " . $exception->getMessage());
                    $error = "Registration could not be completed. Please try again.";
                }
            }

            $stmt->close();
        } else {
            error_log("Registration statement preparation failed: " . $conn->error);
            $error = "Registration could not be completed. Please try again.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
include __DIR__ . '/includes/header.php';
?>

<div class="form-container">
    <h2>User Registration</h2>
    <p>Please fill this form to create an account.</p>
    <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="<?php echo BASE_PATH; ?>register.php" method="post">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Register as:</label>
            <select name="role" required>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" class="btn" value="Register">
        </div>
        <p>Already have an account? <a href="<?php echo BASE_PATH; ?>login.php">Login here</a>.</p>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
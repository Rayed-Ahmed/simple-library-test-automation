<?php
// /teacher/recommend_book.php
require_once __DIR__ . '/../includes/functions.php';
check_role('teacher');
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacher_id = $_SESSION['id'];
    $book_title = trim($_POST['book_title']);
    $reason = trim($_POST['reason']);
    
    $sql = "INSERT INTO recommendations (teacher_id, book_title, reason) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $teacher_id, $book_title, $reason);
    if ($stmt->execute()) {
        $message = "Recommendation submitted successfully!";
    } else {
        $message = "Error submitting recommendation.";
    }
}
include __DIR__ . '/../includes/header.php';
?>

<h2>Recommend a Book</h2>
<?php if($message): ?>
    <div class="alert alert-info"><?php echo $message; ?></div>
<?php endif; ?>

<div class="form-container">
    <form action="recommend_book.php" method="post">
        <div class="form-group">
            <label>Book Title</label>
            <input type="text" name="book_title" required>
        </div>
        <div class="form-group">
            <label>Reason for Recommendation</label>
            <textarea name="reason" rows="4"></textarea>
        </div>
        <input type="submit" class="btn" value="Submit Recommendation">
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
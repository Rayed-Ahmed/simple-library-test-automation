<?php
// /teacher/post_review.php
require_once __DIR__ . '/../includes/functions.php';
check_role('teacher');
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacher_id = $_SESSION['id'];
    $book_id = $_POST['book_id'];
    $rating = $_POST['rating'];
    $review_text = trim($_POST['review_text']);
    
    $sql = "INSERT INTO reviews (teacher_id, book_id, rating, review_text) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $teacher_id, $book_id, $rating, $review_text);
    if ($stmt->execute()) {
        $message = "Review posted successfully!";
    } else {
        $message = "Error posting review.";
    }
}

$books = get_all_books($conn);
include __DIR__ . '/../includes/header.php';
?>

<h2>Post a Book Review</h2>
<?php if($message): ?>
    <div class="alert alert-info"><?php echo $message; ?></div>
<?php endif; ?>

<div class="form-container">
    <form action="post_review.php" method="post">
        <div class="form-group">
            <label>Select Book</label>
            <select name="book_id" required>
                <option value="">-- Choose a book --</option>
                <?php while($book = $books->fetch_assoc()): ?>
                    <option value="<?php echo $book['id']; ?>"><?php echo htmlspecialchars($book['title']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Rating (1-5)</label>
            <input type="number" name="rating" min="1" max="5" required>
        </div>
        <div class="form-group">
            <label>Review</label>
            <textarea name="review_text" rows="5" required></textarea>
        </div>
        <input type="submit" class="btn" value="Post Review">
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
<?php
// /student/borrow_book.php
require_once __DIR__ . '/../includes/functions.php';
check_role('student');

$message = '';

if(isset($_GET['book_id']) && !empty(trim($_GET['book_id']))){
    // ... (logic is the same)
    $book_id = trim($_GET['book_id']);
    $user_id = $_SESSION['id'];
    $borrow_date = date("Y-m-d");
    
    $book = get_book_by_id($conn, $book_id);
    if($book && $book['is_available']){
        $sql = "INSERT INTO borrowing_records (user_id, book_id, borrow_date, status) VALUES (?, ?, ?, 'pending')";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("iis", $user_id, $book_id, $borrow_date);
            if($stmt->execute()){
                $update_sql = "UPDATE books SET is_available = 0 WHERE id = ?";
                if($update_stmt = $conn->prepare($update_sql)){
                    $update_stmt->bind_param("i", $book_id);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
                $message = "Borrowing request sent successfully. Waiting for admin approval.";
            } else {
                $message = "Error sending request.";
            }
            $stmt->close();
        }
    } else {
        $message = "Book is not available or does not exist.";
    }
} else {
    header("location: " . BASE_PATH . "student/browse_books.php");
    exit();
}

include __DIR__ . '/../includes/header.php';
?>

<div class="message-container">
    <h2>Request Status</h2>
    <p><?php echo $message; ?></p>
    <a href="<?php echo BASE_PATH; ?>student/browse_books.php" class="btn">Back to Books</a>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
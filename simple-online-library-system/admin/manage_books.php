<?php
// /admin/manage_books.php
require_once __DIR__ . '/../includes/functions.php';
check_role('admin');

// Handle Add/Edit/Delete actions
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add a new book
    if (isset($_POST['add_book'])) {
        $title = trim($_POST['title']);
        $author = trim($_POST['author']);
        $description = trim($_POST['description']);
        $sql = "INSERT INTO books (title, author, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $title, $author, $description);
        if ($stmt->execute()) $message = "Book added successfully!";
        else $message = "Error adding book.";
        $stmt->close();
    }
    // Delete a book
    if (isset($_POST['delete_book'])) {
        $book_id = $_POST['book_id'];
        $sql = "DELETE FROM books WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $book_id);
        if ($stmt->execute()) $message = "Book deleted successfully!";
        else $message = "Error deleting book.";
        $stmt->close();
    }
}
$books = get_all_books($conn);
include __DIR__ . '/../includes/header.php';
?>

<h2>Manage Books</h2>

<?php if($message): ?>
    <div class="alert alert-info"><?php echo $message; ?></div>
<?php endif; ?>

<div class="form-container" style="max-width:none;">
    <h3>Add New Book</h3>
    <form action="manage_books.php" method="post">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label>Author</label>
            <input type="text" name="author" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description"></textarea>
        </div>
        <input type="submit" name="add_book" class="btn" value="Add Book">
    </form>
</div>

<h3>Existing Books</h3>
<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while($book = $books->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($book['title']); ?></td>
            <td><?php echo htmlspecialchars($book['author']); ?></td>
            <td><?php echo $book['is_available'] ? '<span class="status available">Available</span>' : '<span class="status unavailable">Borrowed</span>'; ?></td>
            <td>
                <form action="manage_books.php" method="post" style="display:inline;">
                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                    <input type="submit" name="delete_book" value="Delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
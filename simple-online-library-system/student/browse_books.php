<?php
// /student/browse_books.php
require_once __DIR__ . '/../includes/functions.php';
check_role('student');
$books = get_all_books($conn);
include __DIR__ . '/../includes/header.php';
?>

<h2>Browse Available Books</h2>
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
                <?php if ($book['is_available']): ?>
                <a href="borrow_book.php?book_id=<?php echo $book['id']; ?>" class="btn btn-sm">Request to Borrow</a>
                <?php else: ?>
                <span>-</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
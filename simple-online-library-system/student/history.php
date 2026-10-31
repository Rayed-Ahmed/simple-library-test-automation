<?php
// /student/history.php
require_once __DIR__ . '/../includes/functions.php';
check_role('student');

$user_id = $_SESSION['id'];
$sql = "SELECT b.title, b.author, br.borrow_date, br.return_date, br.status 
        FROM borrowing_records br 
        JOIN books b ON br.book_id = b.id 
        WHERE br.user_id = ? ORDER BY br.borrow_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$history = $stmt->get_result();

include __DIR__ . '/../includes/header.php';
?>

<h2>My Borrowing History</h2>
<table>
    <thead>
        <tr>
            <th>Book Title</th>
            <th>Author</th>
            <th>Borrow Date</th>
            <th>Return Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $history->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['author']); ?></td>
            <td><?php echo htmlspecialchars($row['borrow_date']); ?></td>
            <td><?php echo $row['return_date'] ? htmlspecialchars($row['return_date']) : 'Not Returned'; ?></td>
            <td><span class="status <?php echo htmlspecialchars($row['status']); ?>"><?php echo htmlspecialchars(ucfirst($row['status'])); ?></span></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
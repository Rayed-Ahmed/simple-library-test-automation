<?php
// /teacher/view_borrowed.php
require_once __DIR__ . '/../includes/functions.php';
check_role('teacher');

$sql = "SELECT u.full_name, b.title, br.borrow_date, br.status 
        FROM borrowing_records br
        JOIN users u ON br.user_id = u.id
        JOIN books b ON br.book_id = b.id
        WHERE u.role = 'student' AND br.status IN ('approved', 'returned')
        ORDER BY br.borrow_date DESC";
$records = $conn->query($sql);

include __DIR__ . '/../includes/header.php';
?>

<h2>List of Books Borrowed by Students</h2>
<table>
    <thead>
        <tr>
            <th>Student Name</th>
            <th>Book Title</th>
            <th>Borrow Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $records->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['borrow_date']); ?></td>
            <td><span class="status <?php echo htmlspecialchars($row['status']); ?>"><?php echo htmlspecialchars(ucfirst($row['status'])); ?></span></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
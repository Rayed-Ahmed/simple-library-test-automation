<?php
// /admin/approve_requests.php
require_once __DIR__ . '/../includes/functions.php';
check_role('admin');

// Handle approve/decline actions
if (isset($_GET['action']) && isset($_GET['record_id'])) {
    $record_id = $_GET['record_id'];
    $book_id = $_GET['book_id'];
    $action = $_GET['action']; // 'approve' or 'decline' or 'return'

    if ($action == 'approve') {
        $status = 'approved';
        $sql = "UPDATE borrowing_records SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $record_id);
        $stmt->execute();
    } elseif ($action == 'decline') {
        $status = 'declined';
        // Make book available again
        $conn->query("UPDATE books SET is_available = 1 WHERE id = $book_id");
        $sql = "UPDATE borrowing_records SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $record_id);
        $stmt->execute();
    } elseif ($action == 'return') {
        $status = 'returned';
        $return_date = date("Y-m-d");
        // Make book available again
        $conn->query("UPDATE books SET is_available = 1 WHERE id = $book_id");
        $sql = "UPDATE borrowing_records SET status = ?, return_date = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $status, $return_date, $record_id);
        $stmt->execute();
    }
    header("location: approve_requests.php");
    exit();
}

$sql = "SELECT br.id, br.book_id, u.full_name, b.title, br.borrow_date, br.status
        FROM borrowing_records br
        JOIN users u ON br.user_id = u.id
        JOIN books b ON br.book_id = b.id
        ORDER BY br.status = 'pending' DESC, br.borrow_date DESC";
$requests = $conn->query($sql);

include __DIR__ . '/../includes/header.php';
?>

<h2>Borrowing Requests & Records</h2>
<table>
    <thead>
        <tr>
            <th>Student Name</th>
            <th>Book Title</th>
            <th>Request Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while($req = $requests->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($req['full_name']); ?></td>
            <td><?php echo htmlspecialchars($req['title']); ?></td>
            <td><?php echo htmlspecialchars($req['borrow_date']); ?></td>
            <td><span class="status <?php echo htmlspecialchars($req['status']); ?>"><?php echo htmlspecialchars(ucfirst($req['status'])); ?></span></td>
            <td>
                <?php if ($req['status'] == 'pending'): ?>
                    <a href="?action=approve&record_id=<?php echo $req['id']; ?>&book_id=<?php echo $req['book_id']; ?>" class="btn btn-sm">Approve</a>
                    <a href="?action=decline&record_id=<?php echo $req['id']; ?>&book_id=<?php echo $req['book_id']; ?>" class="btn btn-sm btn-danger">Decline</a>
                <?php elseif ($req['status'] == 'approved'): ?>
                     <a href="?action=return&record_id=<?php echo $req['id']; ?>&book_id=<?php echo $req['book_id']; ?>" class="btn btn-sm">Mark as Returned</a>
                <?php else: ?>
                    <span>-</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
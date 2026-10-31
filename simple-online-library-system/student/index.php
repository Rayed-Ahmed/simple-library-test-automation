<?php
// /student/index.php
require_once __DIR__ . '/../includes/functions.php';
check_role('student');
include __DIR__ . '/../includes/header.php';
?>

<h2>Student Dashboard</h2>
<p>Welcome, student!</p>
<div class="dashboard-menu">
    <ul>
        <li><a href="browse_books.php">Browse & Borrow Books</a></li>
        <li><a href="history.php">View My Borrowing History</a></li>
    </ul>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
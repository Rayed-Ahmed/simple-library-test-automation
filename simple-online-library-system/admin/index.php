<?php
// /admin/index.php
require_once __DIR__ . '/../includes/functions.php';
check_role('admin');
include __DIR__ . '/../includes/header.php';
?>

<h2>Admin Dashboard</h2>
<p>Welcome, Admin!</p>
<div class="dashboard-menu">
    <ul>
        <li><a href="manage_books.php">Manage Books (Add/Edit/Delete)</a></li>
        <li><a href="approve_requests.php">Approve/Decline Borrowing Requests</a></li>
        <li><a href="manage_users.php">Manage User Accounts</a></li>
    </ul>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
<?php
// /teacher/index.php
require_once __DIR__ . '/../includes/functions.php';
check_role('teacher');
include __DIR__ . '/../includes/header.php';
?>

<h2>Teacher Dashboard</h2>
<p>Welcome, teacher!</p>
<div class="dashboard-menu">
    <ul>
        <li><a href="recommend_book.php">Recommend Books to Students</a></li>
        <li><a href="view_borrowed.php">View Student Borrowed List</a></li>
        <li><a href="post_review.php">Post Book Reviews</a></li>
    </ul>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
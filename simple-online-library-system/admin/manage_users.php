<?php
// /admin/manage_users.php
require_once __DIR__ . '/../includes/functions.php';
check_role('admin');

// Handle user activation/deactivation
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $action = $_GET['action'];
    $is_active = ($action == 'activate') ? 1 : 0;

    $sql = "UPDATE users SET is_active = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $is_active, $user_id);
        $stmt->execute();
        $stmt->close();
        header("location: manage_users.php");
        exit();
    }
}

$users = get_all_users($conn);
include __DIR__ . '/../includes/header.php';
?>

<h2>Manage Users</h2>
<table>
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while($user = $users->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars(ucfirst($user['role'])); ?></td>
            <td><?php echo $user['is_active'] ? '<span class="status available">Active</span>' : '<span class="status unavailable">Inactive</span>'; ?></td>
            <td>
                <?php if ($user['is_active']): ?>
                    <a href="manage_users.php?action=deactivate&user_id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger">Deactivate</a>
                <?php else: ?>
                    <a href="manage_users.php?action=activate&user_id=<?php echo $user['id']; ?>" class="btn btn-sm">Activate</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
<?php
// Include database connection
require 'db.php';

// Start session
session_start();

// Check if superadmin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Pagination setup
$limit = 1; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit;

// Fetch total number of users
$total_query = "SELECT COUNT(*) as total FROM users WHERE role != 'admin'";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_users = $total_row['total'];
$total_pages = ceil($total_users / $limit);

// Fetch users for the current page
$query = "SELECT * FROM users WHERE role != 'admin' LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Handle account actions (delete, promote to admin, lock, unlock)
if (isset($_POST['action']) && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action == 'delete') {
        $delete_query = "DELETE FROM users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        header('Location: admin.dashboard.php');
        exit();
    } elseif ($action == 'lock') {
        $update_query = "UPDATE users SET status = 1 WHERE id = ?"; // Lock account
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        header('Location: admin.dashboard.php');
        exit();
    } elseif ($action == 'unlock') {
        $update_query = "UPDATE users SET status = 0, attempts = 0, lock_time = NULL WHERE id = ?"; // Unlock account
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        header('Location: admin.dashboard.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>ADMIN DASHBOARD</title>
</head>
<body class="bg-[#080914] p-10">
    <h1 class="text-4xl font-extrabold mb-7 text-[#dedeef]">ADMIN DASHBOARD</h1>

    <!-- Responsive table wrapper -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-[#242779] border-collapse text-[#dedeef] table-auto">
            <thead>
                <tr class="font-black">
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Username</th>
                    <th class="border px-4 py-2">Email</th>
                    <th class="border px-4 py-2">Role</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr class="font-medium">
                        <td class="border px-4 py-2"><?= $row['id']; ?></td>
                        <td class="border px-4 py-2"><?= $row['username']; ?></td>
                        <td class="border px-4 py-2"><?= $row['email']; ?></td>
                        <td class="border px-4 py-2"><?= $row['role'] === 'User'; ?></td>
                        <td class="border px-4 py-2"><?= $row['status'] == 1 ? 'Locked' : 'Unlocked'; ?></td> <!-- Check status -->
                        <td class="border px-4 py-2">
                            <form method="POST" class="inline">
                                <input type="hidden" name="user_id" value="<?= $row['id']; ?>">
                                <button type="submit" name="action" value="delete" class="bg-red-500 text-[#dedeef] px-3 py-1 my-1 rounded">Delete</button>
                            </form>

                            <?php if ($row['status'] == 0) : ?> <!-- If unlocked, show lock button -->
                                <form method="POST" class="inline">
                                    <input type="hidden" name="user_id" value="<?= $row['id']; ?>">
                                    <button type="submit" name="action" value="lock" class="bg-[#9fa1dd] text-[#080914] px-3 py-1 rounded">Lock</button>
                                </form>
                            <?php else : ?> <!-- If locked, show unlock button -->
                                <form method="POST" class="inline">
                                    <input type="hidden" name="user_id" value="<?= $row['id']; ?>">
                                    <button type="submit" name="action" value="unlock" class="bg-green-500 text-[#dedeef] px-3 py-1 rounded">Unlock</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-5">
        <nav class="flex justify-end">
            <ul class="flex gap-1">
                <?php if ($page > 1): ?>
                    <li>
                        <a href="?page=<?= $page - 1; ?>" class="bg-[#9fa1dd] text-[#080914] px-3 py-1 rounded">Previous</a>
                    </li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li>
                        <a href="?page=<?= $i; ?>" class="bg-[#9fa1dd] text-[#080914] px-3 py-1 rounded <?= $i === $page ? 'font-bold' : ''; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($page < $total_pages): ?>
                    <li>
                        <a href="?page=<?= $page + 1; ?>" class="bg-[#9fa1dd] text-[#080914] px-3 py-1 rounded">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>                 
    <!-- Logout button -->
    <div class="flex justify-center mt-10">
        <form method="POST" action="logout.php">
            <button type="submit" class="bg-red-600 text-[#dedeef] px-4 py-2 rounded">Logout</button>
        </form>
    </div>
</body>
</html>

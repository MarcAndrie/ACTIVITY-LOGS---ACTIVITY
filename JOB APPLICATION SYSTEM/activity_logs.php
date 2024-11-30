<?php
session_start();
require_once 'models.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT activity_logs.*, users.username FROM activity_logs JOIN users ON activity_logs.user_id = users.id ORDER BY activity_logs.created_at DESC");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs</title>
</head>
<body>
    <h2>Activity Logs</h2>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Activity Type</th>
                <th>Details</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= $log['username'] ?></td>
                    <td><?= $log['activity_type'] ?></td>
                    <td><?= $log['activity_details'] ?></td>
                    <td><?= $log['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

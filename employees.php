
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

require_once "db.php";

$sql = "SELECT user_id, name, email, role
        FROM users
        WHERE status = 'Active'
        ORDER BY name ASC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NGO Employee Directory</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="main-content">
        <h1>NGO Employee Directory</h1>
        <p>List of active NGO staff members.</p>

        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($employee = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($employee["name"]) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($employee["email"]) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($employee["role"]) ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>

<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

include "../db.php";

$sql = "SELECT user_id, name, email, role, status FROM users ORDER BY user_id ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users - NGO Management System</title>
    <link rel="stylesheet" href="../css/style.css">

    <style>
        .table-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th,
        table td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background: #497895;
            color: white;
        }

        table tr:hover {
            background: #f5f5f5;
        }

        .status-active {
            color: green;
            font-weight: bold;
        }

        .status-inactive {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="dashboard">

    <!-- Sidebar -->
    <div class="sidebar">

        <h2>NGO System</h2>

        <ul>
            <li><a href="../dashboard.php">Dashboard</a></li>
            <li><a href="../donors/">Donors</a></li>
            <li><a href="../donations/">Donations</a></li>
            <li><a href="../projects/">Projects</a></li>
            <li><a href="../resources/">Resources</a></li>
            <li><a href="../beneficiaries/">Beneficiaries</a></li>
            <li><a href="../distributions/">Distributions</a></li>
            <li><a href="../expenses/">Expenses</a></li>
            <li><a href="../volunteers/">Volunteers</a></li>
            <li><a href="../reports/">Reports</a></li>
            <li><a href="index.php">Users</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>

    </div>

    <!-- Main Content -->
    <div class="main-content">

        <div class="topbar">
            <div>
                <h1>Users</h1>
                <p>Manage system users</p>
            </div>

            <div class="user-info">
                NGO Management System
            </div>
        </div>

        <div class="table-container">

            <h2>Users List</h2>

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                <?php
                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {
                ?>

                    <tr>
                        <td><?php echo $row["user_id"]; ?></td>

                        <td>
                            <?php echo htmlspecialchars($row["name"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["email"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["role"]); ?>
                        </td>

                        <td>
                            <?php if (strtolower($row["status"]) == "active") { ?>
                                <span class="status-active">
                                    Active
                                </span>
                            <?php } else { ?>
                                <span class="status-inactive">
                                    <?php echo htmlspecialchars($row["status"]); ?>
                                </span>
                            <?php } ?>
                        </td>
                    </tr>

                <?php
                    }

                } else {
                ?>

                    <tr>
                        <td colspan="5">
                            No users found.
                        </td>
                    </tr>

                <?php
                }
                ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>
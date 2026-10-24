<?php
session_start();
include "../db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

$result = mysqli_query(
    $conn,
    "SELECT * FROM beneficiaries ORDER BY beneficiary_id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beneficiaries - NGO Management System</title>
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

    <aside class="sidebar">

        <h2>NGO Management</h2>

        <ul>
            <li><a href="../dashboard.php">Dashboard</a></li>
            <li><a href="../donors/">Donors</a></li>
            <li><a href="../donations/">Donations</a></li>
            <li><a href="../projects/">Projects</a></li>
            <li><a href="../resources/">Resources</a></li>
            <li><a href="index.php">Beneficiaries</a></li>
            <li><a href="../distributions/">Distributions</a></li>
            <li><a href="../expenses/">Expenses</a></li>
            <li><a href="../volunteers/">Volunteers</a></li>
            <li><a href="../reports/">Reports</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>

    </aside>


    <main class="main-content">
<?php if (isset($_GET["updated"])) echo "<p class=\"notice-success\">Record updated successfully.</p>"; if (isset($_GET["deleted"])) echo "<p class=\"notice-success\">Record deleted successfully.</p>"; ?>


        <div class="topbar">

            <div>
                <h1>Beneficiaries</h1>
                <p>Manage NGO beneficiaries</p>
            </div>

            <div class="user-info">
                <?php echo htmlspecialchars($_SESSION["name"]); ?>
            </div>

        </div>


        <div class="dashboard-section">

            <div class="section-header">

                <h2>Beneficiary List</h2>

                <a href="add.php" class="btn-add">
                    + Add Beneficiary
                </a>

            </div>


            <table class="data-table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Category</th>
                        <th>Created Date</th>
                    
<th>Actions</th>
</tr>
                </thead>

                <tbody>

                <?php if (mysqli_num_rows($result) > 0): ?>

                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                        <tr>

                            <td>
                                <?php echo $row["beneficiary_id"]; ?>
                            </td>

                            <td>
                            <a href="feedback.php?id=<?php echo (int)$row['beneficiary_id']; ?>">
                             <?php echo htmlspecialchars($row["name"]); ?>
                            </a>

                            </td>

                            <td>
                                <?php echo $row["age"] ?? ""; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["gender"] ?? ""); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["phone"] ?? ""); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["address"] ?? ""); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["category"] ?? ""); ?>
                            </td>

                            <td>
                                <?php echo $row["created_at"] ?? ""; ?>
                            </td>

                        
<td class="actions-cell"><a class="action-link edit-link" href="edit.php?id=<?php echo (int)$row["beneficiary_id"]; ?>">Edit</a><form method="post" action="delete.php" style="display:inline" onsubmit="return confirm('Delete this record? This cannot be undone.');"><input type="hidden" name="id" value="<?php echo (int)$row["beneficiary_id"]; ?>"><button type="submit" class="delete-button">Delete</button></form></td>
</tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="9">
                            No beneficiaries found.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </main>

</div>

</body>
</html>
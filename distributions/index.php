<?php
session_start();
include "../db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

$sql = "SELECT 
            d.distribution_id,
            r.resource_name,
            b.name AS beneficiary_name,
            p.project_name,
            d.quantity,
            d.distribution_date,
            u.name AS distributed_by_name
        FROM distributions d
        LEFT JOIN resources r ON d.resource_id = r.resource_id
        LEFT JOIN beneficiaries b ON d.beneficiary_id = b.beneficiary_id
        LEFT JOIN projects p ON d.project_id = p.project_id
        LEFT JOIN users u ON d.distributed_by = u.user_id
        ORDER BY d.distribution_id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Distributions - NGO Management System</title>
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
            <li><a href="../beneficiaries/">Beneficiaries</a></li>
            <li><a href="index.php">Distributions</a></li>
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
                <h1>Distributions</h1>
                <p>Manage resource distributions</p>
            </div>

            <div class="user-info">
                <?php echo htmlspecialchars($_SESSION["name"]); ?>
            </div>

        </div>


        <div class="dashboard-section">

            <div class="section-header">

                <h2>Distribution List</h2>

                <a href="add.php" class="btn-add">
                    + Add Distribution
                </a>

            </div>


            <table class="data-table">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Resource</th>
                        <th>Beneficiary</th>
                        <th>Project</th>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>Distributed By</th>
                    
<th>Actions</th>
</tr>

                </thead>


                <tbody>

                <?php if (mysqli_num_rows($result) > 0): ?>

                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                        <tr>

                            <td>
                                <?php echo $row["distribution_id"]; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["resource_name"] ?? ""); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["beneficiary_name"] ?? ""); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["project_name"] ?? ""); ?>
                            </td>

                            <td>
                                <?php echo $row["quantity"]; ?>
                            </td>

                            <td>
                                <?php echo $row["distribution_date"] ?? ""; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["distributed_by_name"] ?? ""); ?>
                            </td>

                        
<td class="actions-cell"><a class="action-link edit-link" href="edit.php?id=<?php echo (int)$row["distribution_id"]; ?>">Edit</a><form method="post" action="delete.php" style="display:inline" onsubmit="return confirm('Delete this record? This cannot be undone.');"><input type="hidden" name="id" value="<?php echo (int)$row["distribution_id"]; ?>"><button type="submit" class="delete-button">Delete</button></form></td>
</tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="8">
                            No distributions found.
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
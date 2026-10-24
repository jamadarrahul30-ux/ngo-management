<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

include "../db.php";

$sql = "SELECT e.expense_id,
               p.project_name,
               e.category,
               e.amount,
               e.expense_date,
               e.description,
               e.payment_method,
               u.name AS approved_by
        FROM expenses e
        LEFT JOIN projects p ON e.project_id = p.project_id
        LEFT JOIN users u ON e.approved_by = u.user_id
        ORDER BY e.expense_id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Expenses</title>
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

<div   class="dashboard">

    <aside class="sidebar">

        <h2>NGO Management</h2>

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
            <li><a href="../logout.php">Logout</a></li>
        </ul>

    </aside>


    <main class="main-content">
<?php if (isset($_GET["updated"])) echo "<p class=\"notice-success\">Record updated successfully.</p>"; if (isset($_GET["deleted"])) echo "<p class=\"notice-success\">Record deleted successfully.</p>"; ?>


        <div class="topbar">

            <div>
                <h1>Donors</h1>
                <p>Manage NGO donors</p>
            </div>

            <div class="user-info">
                <?php echo htmlspecialchars($_SESSION["name"]); ?>
            </div>

        </div>

        <div class="dashboard-section">

            <!-- Donor heading and Add button -->
                <div class="section-header">

                    <h2>Expenses</h2>

                    <a href="add.php" class="btn-add">
                        + Add Expenses
                    </a>

                </div>

            <div class="table-container" style="font-size: 15px;">
                
                <table>

                    <tr>
                        <th>ID</th>
                        <th>Project</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Payment Method</th>
                        <th>Approved By</th>
                    
<th>Actions</th>
</tr>

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                    <tr style="margin-top :20px; padding :20px;">

                        <td><?php echo $row["expense_id"]; ?></td>

                        <td>
                            <?php echo htmlspecialchars($row["project_name"] ?? ""); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["category"] ?? ""); ?>
                        </td>

                        <td>
                            ₹<?php echo number_format($row["amount"], 2); ?>
                        </td>

                        <td>
                            <?php echo $row["expense_date"]; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["description"] ?? ""); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["payment_method"] ?? ""); ?>
                        </td>
                        
                        <td>
                            <?php echo htmlspecialchars($row["approved_by"] ?? ""); ?>
                        </td>
                        
                    
<td class="actions-cell"><a class="action-link edit-link" href="edit.php?id=<?php echo (int)$row["expense_id"]; ?>">Edit</a><form method="post" action="delete.php" style="display:inline" onsubmit="return confirm('Delete this record? This cannot be undone.');"><input type="hidden" name="id" value="<?php echo (int)$row["expense_id"]; ?>"><button type="submit" class="delete-button">Delete</button></form></td>
</tr>
                    
                    <?php } ?>
                    
                </table>
            </div>
        </div>

    </main>
</div>
</body>
</html>
<?php

session_start();
include "../db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

$sql = "SELECT 
            donations.*,
            donors.name AS donor_name,
            projects.project_name
        FROM donations
        LEFT JOIN donors ON donations.donor_id = donors.donor_id
        LEFT JOIN projects ON donations.project_id = projects.project_id
        ORDER BY donations.donation_id ASC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Donations - NGO Management System</title>

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

    <aside class="sidebar">

        <h2>NGO Management</h2>

        <ul>

            <li><a href="../dashboard.php">Dashboard</a></li>

            <li><a href="../donors/">Donors</a></li>

            <li><a href="index.php">Donations</a></li>

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


    <!-- Main Content -->

    <main class="main-content">
<?php if (isset($_GET["updated"])) echo "<p class=\"notice-success\">Record updated successfully.</p>"; if (isset($_GET["deleted"])) echo "<p class=\"notice-success\">Record deleted successfully.</p>"; ?>


        <div class="topbar">

            <div>

                <h1>Donations</h1>

                <p>Manage NGO donations</p>

            </div>

            <div class="user-info">

                <?php echo htmlspecialchars($_SESSION["name"]); ?>

            </div>

        </div>


        <div class="dashboard-section">

            <div class="section-header">

                <h2>Donation List</h2>

                <a href="add.php" class="btn-add">
                    + Add Donation
                </a>

            </div>


            <table class="data-table">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Donor</th>

                        <th>Project</th>

                        <th>Date</th>

                        <th>Type</th>

                        <th>Amount</th>

                        <th>Payment Method</th>

                        <th>Status</th>

                    
<th>Actions</th>
</tr>

                </thead>


                <tbody>

                <?php

                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                ?>

                    <tr>

                        <td>
                            <?php echo $row["donation_id"]; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["donor_name"] ?? "N/A"); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["project_name"] ?? "N/A"); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["donation_date"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["donation_type"]); ?>
                        </td>

                        <td>
                            ₹ <?php echo number_format($row["amount"], 2); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["payment_method"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["status"]); ?>
                        </td>

                    
<td class="actions-cell"><a class="action-link edit-link" href="edit.php?id=<?php echo (int)$row["donation_id"]; ?>">Edit</a><form method="post" action="delete.php" style="display:inline" onsubmit="return confirm('Delete this record? This cannot be undone.');"><input type="hidden" name="id" value="<?php echo (int)$row["donation_id"]; ?>"><button type="submit" class="delete-button">Delete</button></form></td>
</tr>

                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td colspan="9">
                            No donations found.
                        </td>

                    </tr>

                <?php

                }

                ?>

                </tbody>

            </table>

        </div>

    </main>

</div>

</body>

</html>
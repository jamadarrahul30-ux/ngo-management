<?php
session_start();
include "../db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

/* Total Donations */
$donation_result = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(amount), 0) AS total FROM donations"
);
$donation_data = mysqli_fetch_assoc($donation_result);
$total_donations = $donation_data["total"];

/* Total Expenses */
$expense_result = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(amount), 0) AS total FROM expenses"
);
$expense_data = mysqli_fetch_assoc($expense_result);
$total_expenses = $expense_data["total"];

/* Total Beneficiaries */
$beneficiary_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM beneficiaries"
);
$beneficiary_data = mysqli_fetch_assoc($beneficiary_result);
$total_beneficiaries = $beneficiary_data["total"];

/* Total Volunteers */
$volunteer_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM volunteers"
);
$volunteer_data = mysqli_fetch_assoc($volunteer_result);
$total_volunteers = $volunteer_data["total"];

/* Total Resources */
$resource_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM resources"
);
$resource_data = mysqli_fetch_assoc($resource_result);
$total_resources = $resource_data["total"];

/* Total Projects */
$project_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM projects"
);
$project_data = mysqli_fetch_assoc($project_result);
$total_projects = $project_data["total"];

/* Recent Donations */
$recent_donations = mysqli_query(
    $conn,
    "SELECT
        d.donation_date,
        d.amount,
        d.payment_method,
        dn.name AS donor_name
     FROM donations d
     LEFT JOIN donors dn ON d.donor_id = dn.donor_id
     ORDER BY d.donation_id ASC
     LIMIT 5"
);

/* Recent Expenses */
$recent_expenses = mysqli_query(
    $conn,
    "SELECT
        e.expense_date,
        e.amount,
        e.category,
        p.project_name
     FROM expenses e
     LEFT JOIN projects p ON e.project_id = p.project_id
     ORDER BY e.expense_id DESC
     LIMIT 5"
);

/* Balance */
$balance = $total_donations - $total_expenses;
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Reports - NGO Management System</title>

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
            <li><a href="../donations/">Donations</a></li>
            <li><a href="../projects/">Projects</a></li>
            <li><a href="../resources/">Resources</a></li>
            <li><a href="../beneficiaries/">Beneficiaries</a></li>
            <li><a href="../distributions/">Distributions</a></li>
            <li><a href="../expenses/">Expenses</a></li>
            <li><a href="../volunteers/">Volunteers</a></li>
            <li><a href="index.php">Reports</a></li>
            <li><a href="../logout.php">Logout</a></li>

        </ul>

    </aside>


    <!-- Main Content -->

    <main class="main-content">

        <div class="topbar">

            <div>

                <h1>Reports</h1>

                <p>NGO management reports and statistics</p>

            </div>

            <div class="user-info">

                <?php echo htmlspecialchars($_SESSION["name"]); ?>

            </div>

        </div>


        <!-- Summary Cards -->

        <div class="cards">

            <div class="card">

                <h3>Total Donations</h3>

                <p>
                    ₹<?php echo number_format($total_donations, 2); ?>
                </p>

            </div>


            <div class="card">

                <h3>Total Expenses</h3>

                <p>
                    ₹<?php echo number_format($total_expenses, 2); ?>
                </p>

            </div>


            <div class="card">

                <h3>Total Beneficiaries</h3>

                <p>
                    <?php echo $total_beneficiaries; ?>
                </p>

            </div>


            <div class="card">

                <h3>Total Volunteers</h3>

                <p>
                    <?php echo $total_volunteers; ?>
                </p>

            </div>


            <div class="card">

                <h3>Total Projects</h3>

                <p>
                    <?php echo $total_projects; ?>
                </p>

            </div>


            <div class="card">

                <h3>Total Resources</h3>

                <p>
                    <?php echo $total_resources; ?>
                </p>

            </div>

        </div>


        <!-- Financial Summary -->

        <div class="dashboard-section">

            <div class="section-header">

                <h2>Financial Summary</h2>

            </div>

            <table class="data-table">

                <tr>

                    <th>Total Donations</th>

                    <td>
                        ₹<?php echo number_format($total_donations, 2); ?>
                    </td>

                </tr>

                <tr>

                    <th>Total Expenses</th>

                    <td>
                        ₹<?php echo number_format($total_expenses, 2); ?>
                    </td>

                </tr>

                <tr>

                    <th>Remaining Balance</th>

                    <td>
                        ₹<?php echo number_format($balance, 2); ?>
                    </td>

                </tr>

            </table>

        </div>


        <!-- Recent Donations -->

        <div class="dashboard-section">

            <div class="section-header">

                <h2>Recent Donations</h2>

            </div>


            <table class="data-table">

                <thead>

                    <tr>

                        <th>Date</th>
                        <th>Donor</th>
                        <th>Amount</th>
                        <th>Payment Method</th>

                    </tr>

                </thead>


                <tbody>

                <?php if (mysqli_num_rows($recent_donations) > 0): ?>

                    <?php while ($row = mysqli_fetch_assoc($recent_donations)): ?>

                        <tr>

                            <td>
                                <?php echo $row["donation_date"]; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["donor_name"] ?? ""); ?>
                            </td>

                            <td>
                                ₹<?php echo number_format($row["amount"], 2); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["payment_method"] ?? ""); ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="4">
                            No donations found.
                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>


        <!-- Recent Expenses -->

        <div class="dashboard-section">

            <div class="section-header">

                <h2>Recent Expenses</h2>

            </div>


            <table class="data-table">

                <thead>

                    <tr>

                        <th>Date</th>
                        <th>Project</th>
                        <th>Category</th>
                        <th>Amount</th>

                    </tr>

                </thead>


                <tbody>

                <?php if (mysqli_num_rows($recent_expenses) > 0): ?>

                    <?php while ($row = mysqli_fetch_assoc($recent_expenses)): ?>

                        <tr>

                            <td>
                                <?php echo $row["expense_date"]; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["project_name"] ?? ""); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["category"] ?? ""); ?>
                            </td>

                            <td>
                                ₹<?php echo number_format($row["amount"], 2); ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="4">
                            No expenses found.
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
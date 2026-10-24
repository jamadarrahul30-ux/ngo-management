
<?php

session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

// Get dashboard counts
$donors = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM donors"));
$donations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(amount), 0) AS total FROM donations"));
$projects = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM projects"));
$expenses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(amount), 0) AS total FROM expenses"));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - NGO Management System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="dashboard">

    <!-- Sidebar -->
    <aside class="sidebar">
        <h2>NGO Management</h2>

        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="donors/">Donors</a></li>
            <li><a href="donations/">Donations</a></li>
            <li><a href="projects/">Projects</a></li>
            <li><a href="resources/">Resources</a></li>
            <li><a href="beneficiaries/">Beneficiaries</a></li>
            <li><a href="distributions/">Distributions</a></li>
            <li><a href="expenses/">Expenses</a></li>
            <li><a href="volunteers/">Volunteers</a></li>
            <li><a href="reports/">Reports</a></li>
            <li><a href="users/">Users</a></li>

            <!-- NEW EMPLOYEES TAB -->
            <li><a href="employees.php">Employees &amp; Roles</a></li>

            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

        <div class="topbar">
            <div>
                <h1>Dashboard</h1>
                <p>Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!</p>
            </div>

            <div class="user-info">
                Role: <?php echo htmlspecialchars($_SESSION["role"]); ?>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="cards">

            <div class="card">
                <h3>Total Donors</h3>
                <p><?php echo $donors["total"]; ?></p>
            </div>

            <div class="card">
                <h3>Total Donations</h3>
                <p>₹ <?php echo number_format($donations["total"], 2); ?></p>
            </div>

            <div class="card">
                <h3>Total Projects</h3>
                <p><?php echo $projects["total"]; ?></p>
            </div>

            <div class="card">
                <h3>Total Expenses</h3>
                <p>₹ <?php echo number_format($expenses["total"], 2); ?></p>
            </div>

        </div>

        <!-- Welcome Section -->
        <div class="dashboard-section">
            <h2>NGO Resource &amp; Donation Management System</h2>
            <p>
                Use the menu on the left to manage donors, donations,
                projects, resources, beneficiaries, distributions,
                expenses, volunteers, users, and employees.
            </p>
        </div>

    </main>
</div>

</body>
</html>

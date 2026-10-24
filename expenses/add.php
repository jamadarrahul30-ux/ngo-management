<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

include "../db.php";

/* Get projects */
$projects = mysqli_query($conn, "SELECT project_id, project_name FROM projects ORDER BY project_name");

/* Get active users */
$users = mysqli_query($conn, "SELECT user_id, name FROM users WHERE status = 'Active' ORDER BY name");


if (isset($_POST["submit"])) {

    $project_id = $_POST["project_id"];
    $category = $_POST["category"];
    $amount = $_POST["amount"];
    $expense_date = $_POST["expense_date"];
    $description = $_POST["description"];
    $payment_method = $_POST["payment_method"];
    $approved_by = $_POST["approved_by"];

    $sql = "INSERT INTO expenses
            (project_id, category, amount, expense_date, description, payment_method, approved_by)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "isdsssi",
        $project_id,
        $category,
        $amount,
        $expense_date,
        $description,
        $payment_method,
        $approved_by
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Expense</title>
    <link rel="stylesheet" href="../css/style.css">
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
            <li><a href="../reports/">Reports</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>

    </aside>


    <main class="main-content">

        <div class="topbar">

            <div>

                <h1>Add New Expenses</h1>

                <p>Enter Expense information</p>

            </div>

        </div>

         <div class="dashboard-section">

            <!-- <?php if ($message != ""): ?> -->

                <div class="error-message">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <!-- <?php endif; ?> -->


            <form method="POST">

                <label>Project</label>
                <br><br>
                <select name="project_id" required>

                    <option value="">Select Project</option>

                    <?php while ($project = mysqli_fetch_assoc($projects)) { ?>

                        <option value="<?php echo $project["project_id"]; ?>">
                            <?php echo htmlspecialchars($project["project_name"]); ?>
                        </option>

                    <?php } ?>

                </select>
                <br><br>

                <label>Category</label>
                <input type="text" name="category" placeholder="e.g. Food, Transport, Supplies" required>
                <br><br>

                <label>Amount</label>
                <input type="number" name="amount" step="0.01" min="0" placeholder="Enter amount" required>

                <br><br>
                <label>Expense Date</label>
                <input type="date" name="expense_date" required>

                <br><br>
                <label>Description</label>
                <textarea name="description" placeholder="Enter expense description"></textarea>
                <br><br>

                <label>Payment Method</label>
                <select name="payment_method" required>

                    <option value="">Select Payment Method</option>
                    <option value="Cash">Cash</option>
                    <option value="UPI">UPI</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Cheque">Cheque</option>
                    <option value="Card">Card</option>

                </select>

                <br><br>
                <label>Approved By</label>
                <select name="approved_by" required>

                    <option value="">Select User</option>

                    <?php while ($user = mysqli_fetch_assoc($users)) { ?>

                        <option value="<?php echo $user["user_id"]; ?>">
                            <?php echo htmlspecialchars($user["name"]); ?>
                        </option>

                    <?php } ?>

                </select>

                <br><br>
                <button type="submit" name="submit">Add Expense</button>

            </form>
        </div>
    </main>

</body>
</html> 


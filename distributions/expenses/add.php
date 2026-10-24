<?php
session_start();
include "../db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

$message = "";

/* Get projects */
$projects = mysqli_query(
    $conn,
    "SELECT project_id, project_name
     FROM projects
     ORDER BY project_name"
);

/* Get users */
$users = mysqli_query(
    $conn,
    "SELECT user_id, name
     FROM users
     WHERE status = 'Active'
     ORDER BY name"
);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $project_id = $_POST["project_id"];
    $category = trim($_POST["category"]);
    $amount = $_POST["amount"];
    $expense_date = $_POST["expense_date"];
    $description = trim($_POST["description"]);
    $payment_method = trim($_POST["payment_method"]);
    $approved_by = $_POST["approved_by"];


    if (
        $project_id == "" ||
        $category == "" ||
        $amount == "" ||
        $expense_date == "" ||
        $payment_method == "" ||
        $approved_by == ""
    ) {

        $message = "Please fill all required fields.";

    } elseif ($amount <= 0) {

        $message = "Amount must be greater than 0.";

    } else {

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

            $message = "Error adding expense: " . mysqli_error($conn);

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Add Expense - NGO Management System</title>

    <link rel="stylesheet" href="../css/style.css">

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
            <li><a href="../distributions/">Distributions</a></li>
            <li><a href="index.php">Expenses</a></li>
            <li><a href="../volunteers/">Volunteers</a></li>
            <li><a href="../reports/">Reports</a></li>
            <li><a href="../logout.php">Logout</a></li>

        </ul>

    </aside>


    <main class="main-content">

        <div class="topbar">

            <div>

                <h1>Add Expense</h1>

                <p>Record a new NGO expense</p>

            </div>

            <div class="user-info">

                <?php echo htmlspecialchars($_SESSION["name"]); ?>

            </div>

        </div>


        <div class="dashboard-section">


            <?php if ($message != ""): ?>

                <div class="error-message">

                    <?php echo htmlspecialchars($message); ?>

                </div>

            <?php endif; ?>


            <form method="POST" action="add.php" class="form-container">


                <div class="form-group">

                    <label>Project *</label>

                    <select name="project_id" required>

                        <option value="">Select Project</option>

                        <?php while ($row = mysqli_fetch_assoc($projects)): ?>

                            <option value="<?php echo $row["project_id"]; ?>">

                                <?php echo htmlspecialchars($row["project_name"]); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <div class="form-group">

                    <label>Category *</label>

                    <select name="category" required>

                        <option value="">Select Category</option>

                        <option value="Food">Food</option>
                        <option value="Transportation">Transportation</option>
                        <option value="Medical">Medical</option>
                        <option value="Education">Education</option>
                        <option value="Equipment">Equipment</option>
                        <option value="Salary">Salary</option>
                        <option value="Rent">Rent</option>
                        <option value="Utilities">Utilities</option>
                        <option value="Other">Other</option>

                    </select>

                </div>


                <div class="form-group">

                    <label>Amount *</label>

                    <input
                        type="number"
                        name="amount"
                        min="0.01"
                        step="0.01"
                        placeholder="Enter amount"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Expense Date *</label>

                    <input
                        type="date"
                        name="expense_date"
                        value="<?php echo date('Y-m-d'); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Description</label>

                    <textarea
                        name="description"
                        placeholder="Enter expense description"
                        rows="3"
                    ></textarea>

                </div>


                <div class="form-group">

                    <label>Payment Method *</label>

                    <select name="payment_method" required>

                        <option value="">Select Payment Method</option>

                        <option value="Cash">Cash</option>
                        <option value="Online">Online</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="UPI">UPI</option>

                    </select>

                </div>


                <div class="form-group">

                    <label>Approved By *</label>

                    <select name="approved_by" required>

                        <option value="">Select User</option>

                        <?php while ($row = mysqli_fetch_assoc($users)): ?>

                            <option value="<?php echo $row["user_id"]; ?>">

                                <?php echo htmlspecialchars($row["name"]); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <div class="form-buttons">

                    <button
                        type="submit"
                        class="btn-submit"
                    >
                        Add Expense
                    </button>

                    <a
                        href="index.php"
                        class="btn-cancel"
                    >
                        Cancel
                    </a>

                </div>


            </form>

        </div>

    </main>

</div>

</body>
</html>
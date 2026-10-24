<?php

session_start();
include "../db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $donor_id = $_POST["donor_id"];
    $project_id = $_POST["project_id"];
    $donation_date = $_POST["donation_date"];
    $donation_type = $_POST["donation_type"];
    $amount = $_POST["amount"];
    $payment_method = $_POST["payment_method"];
    $description = $_POST["description"];
    $receipt_no = $_POST["receipt_no"];
    $status = $_POST["status"];

    $sql = "INSERT INTO donations
            (donor_id, project_id, donation_date, donation_type, amount,
             payment_method, description, receipt_no, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "iissdssss",
        $donor_id,
        $project_id,
        $donation_date,
        $donation_type,
        $amount,
        $payment_method,
        $description,
        $receipt_no,
        $status
    );

    if (mysqli_stmt_execute($stmt)) {

        header("Location: index.php");
        exit();

    } else {

        $message = "Error adding donation: " . mysqli_error($conn);
    }
}

/* Get donors */
$donors = mysqli_query(
    $conn,
    "SELECT donor_id, name FROM donors ORDER BY name"
);

/* Get projects */
$projects = mysqli_query(
    $conn,
    "SELECT project_id, project_name FROM projects ORDER BY project_name"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Donation - NGO Management System</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="dashboard">

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


    <main class="main-content">

        <div class="topbar">

            <div>

                <h1>Add Donation</h1>

                <p>Enter donation information</p>

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

                    <label>Donor *</label>

                    <select name="donor_id" required>

                        <option value="">Select Donor</option>

                        <?php while ($donor = mysqli_fetch_assoc($donors)): ?>

                            <option value="<?php echo $donor["donor_id"]; ?>">

                                <?php echo htmlspecialchars($donor["name"]); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>
                <br>


                <div class="form-group">

                    <label>Project *</label>

                    <select name="project_id" required>

                        <option value="">Select Project</option>

                        <?php while ($project = mysqli_fetch_assoc($projects)): ?>

                            <option value="<?php echo $project["project_id"]; ?>">

                                <?php echo htmlspecialchars($project["project_name"]); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>
                <br>


                <div class="form-group">

                    <label>Donation Date *</label>

                    <input type="date"
                           name="donation_date"
                           required>

                </div>
                <br>


                <div class="form-group">

                    <label>Donation Type *</label>

                    <select name="donation_type" required>

                        <option value="">Select Type</option>
                        <option value="Cash">Cash</option>
                        <option value="Kind">Kind</option>
                        <option value="Online">Online</option>
                        <option value="Cheque">Cheque</option>

                    </select>

                </div>
                <br>    

                <div class="form-group">

                    <label>Amount *</label>

                    <input type="number"
                           name="amount"
                           step="0.01"
                           placeholder="Enter amount"
                           required>

                </div>
                <br>


                <div class="form-group">
    <label>Donation Type *</label>
    <select name="donation_type" required>
        <option value="">Select Type</option>
        <option value="Cash">Cash</option>
        <option value="Kind">Kind</option>
        <option value="Online">Online</option>
        <option value="Cheque">Cheque</option>
    </select>
</div>
<br>


                <div class="form-group">

                    <label>Description</label>

                    <textarea name="description"
                              rows="4"
                              placeholder="Enter description"></textarea>

                </div>
                <br>


                <div class="form-group">

                    <label>Receipt Number</label>

                    <input type="text"
                           name="receipt_no"
                           placeholder="Enter receipt number">

                </div>
                <br>


                <div class="form-group">

                    <label>Status *</label>

                    <select name="status" required>

                        <option value="Received">Received</option>
                        <option value="Pending">Pending</option>
                        <option value="Cancelled">Cancelled</option>

                    </select>

                </div>


                <div class="form-buttons">

                    <button type="submit" class="btn-submit">
                        Add Donation
                    </button>

                    <a href="index.php" class="btn-cancel">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </main>

</div>

</body>

</html>
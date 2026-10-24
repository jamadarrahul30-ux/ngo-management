<?php

session_start();
include "../db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $project_name = trim($_POST["project_name"]);
    $description = trim($_POST["description"]);
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    $target_amount = $_POST["target_amount"];
    $status = $_POST["status"];

    if ($project_name == "") {

        $message = "Project name is required.";

    } else {

        $sql = "INSERT INTO projects
                (project_name, description, start_date, end_date, target_amount, status)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssssds",
            $project_name,
            $description,
            $start_date,
            $end_date,
            $target_amount,
            $status
        );

        if (mysqli_stmt_execute($stmt)) {

            header("Location: index.php");
            exit();

        } else {

            $message = "Error adding project: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Add Project - NGO Management System</title>

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

            <li><a href="index.php">Projects</a></li>

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

                <h1>Add Project</h1>

                <p>Enter project information</p>

            </div>

            <div class="user-info">

                <?php echo htmlspecialchars($_SESSION["name"]); ?>

            </div>

        </div>
        <br>


        <div class="dashboard-section">

            <?php if ($message != ""): ?>

                <div class="error-message">

                    <?php echo htmlspecialchars($message); ?>

                </div>
                <br>

            <?php endif; ?>


            <form method="POST" action="add.php" class="form-container">


                <div class="form-group">

                    <label>Project Name *</label>

                    <input
                        type="text"
                        name="project_name"
                        placeholder="Enter project name"
                        required
                    >

                </div>
                <br>


                <div class="form-group">

                    <label>Description</label>

                    <textarea
                        name="description"
                        rows="4"
                        placeholder="Enter project description"
                    ></textarea>

                </div>
                <br>


                <div class="form-group">

                    <label>Start Date</label>

                    <input
                        type="date"
                        name="start_date"
                    >

                </div>
                <br>


                <div class="form-group">

                    <label>End Date</label>

                    <input
                        type="date"
                        name="end_date"
                    >

                </div>
                <br>


                <div class="form-group">

                    <label>Target Amount</label>

                    <input
                        type="number"
                        name="target_amount"
                        min="0"
                        step="0.01"
                        placeholder="Enter target amount"
                    >

                </div>
                <br>


                <div class="form-group">

                    <label>Status</label>

                    <select name="status">

                        <option value="Planned">Planned</option>

                        <option value="Active">Active</option>

                        <option value="Completed">Completed</option>

                        <option value="Cancelled">Cancelled</option>

                    </select>

                </div>
                <br>


                <div class="form-buttons">

                    <button type="submit" class="btn-submit">
                        Add Project
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
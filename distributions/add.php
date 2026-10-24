<?php
session_start();
include "../db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

$message = "";

/* Get resources */
$resources = mysqli_query(
    $conn,
    "SELECT resource_id, resource_name
     FROM resources
     ORDER BY resource_name"
);

/* Get beneficiaries */
$beneficiaries = mysqli_query(
    $conn,
    "SELECT beneficiary_id, name
     FROM beneficiaries
     ORDER BY name"
);

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

    $resource_id = $_POST["resource_id"];
    $beneficiary_id = $_POST["beneficiary_id"];
    $project_id = $_POST["project_id"];
    $quantity = $_POST["quantity"];
    $distribution_date = $_POST["distribution_date"];
    $distributed_by = $_POST["distributed_by"];


    if (
        $resource_id == "" ||
        $beneficiary_id == "" ||
        $project_id == "" ||
        $quantity == "" ||
        $distribution_date == "" ||
        $distributed_by == ""
    ) {

        $message = "Please fill all required fields.";

    } elseif ($quantity <= 0) {

        $message = "Quantity must be greater than 0.";

    } else {

        $sql = "INSERT INTO distributions
                (resource_id, beneficiary_id, project_id, quantity, distribution_date, distributed_by)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "iiiisi",
            $resource_id,
            $beneficiary_id,
            $project_id,
            $quantity,
            $distribution_date,
            $distributed_by
        );


        if (mysqli_stmt_execute($stmt)) {

            header("Location: index.php");
            exit();

        } else {

            $message = "Error adding distribution: " . mysqli_error($conn);

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Add Distribution - NGO Management System</title>

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
            <li><a href="index.php">Distributions</a></li>
            <li><a href="../expenses/">Expenses</a></li>
            <li><a href="../volunteers/">Volunteers</a></li>
            <li><a href="../reports/">Reports</a></li>
            <li><a href="../logout.php">Logout</a></li>

        </ul>

    </aside>


    <main class="main-content">

        <div class="topbar">

            <div>

                <h1>Add Distribution</h1>

                <p>Record a resource distribution</p>

            </div>
            <br>

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

                    <label>Resource *</label>

                    <select name="resource_id" required>

                        <option value="">Select Resource</option>

                        <?php while ($row = mysqli_fetch_assoc($resources)): ?>

                            <option value="<?php echo $row["resource_id"]; ?>">

                                <?php echo htmlspecialchars($row["resource_name"]); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>
                <br>


                <div class="form-group">

                    <label>Beneficiary *</label>

                    <select name="beneficiary_id" required>

                        <option value="">Select Beneficiary</option>

                        <?php while ($row = mysqli_fetch_assoc($beneficiaries)): ?>

                            <option value="<?php echo $row["beneficiary_id"]; ?>">

                                <?php echo htmlspecialchars($row["name"]); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>
                <br>


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
                <br>


                <div class="form-group">

                    <label>Quantity *</label>

                    <input
                        type="number"
                        name="quantity"
                        min="1"
                        placeholder="Enter quantity"
                        required
                    >

                </div>
                <br>


                <div class="form-group">

                    <label>Distribution Date *</label>

                    <input
                        type="date"
                        name="distribution_date"
                        value="<?php echo date('Y-m-d'); ?>"
                        required
                    >

                </div>
                <br>


                <div class="form-group">

                    <label>Distributed By *</label>

                    <select name="distributed_by" required>

                        <option value="">Select User</option>

                        <?php while ($row = mysqli_fetch_assoc($users)): ?>

                            <option value="<?php echo $row["user_id"]; ?>">

                                <?php echo htmlspecialchars($row["name"]); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>
                <br>


                <div class="form-buttons">

                    <button
                        type="submit"
                        class="btn-submit"
                    >
                        Add Distribution
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
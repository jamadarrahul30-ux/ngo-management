<?php
session_start();
include "../db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $joining_date = $_POST["joining_date"];
    $role = trim($_POST["role"]);
    $status = trim($_POST["status"]);


    if ($name == "") {

        $message = "Volunteer name is required.";

    } else {

        $sql = "INSERT INTO volunteers
                (name, email, phone, address, joining_date, role, status)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssssss",
            $name,
            $email,
            $phone,
            $address,
            $joining_date,
            $role,
            $status
        );


        if (mysqli_stmt_execute($stmt)) {

            header("Location: index.php");
            exit();

        } else {

            $message = "Error adding volunteer: " . mysqli_error($conn);

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Add Volunteer - NGO Management System</title>

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
            <li><a href="../expenses/">Expenses</a></li>
            <li><a href="index.php">Volunteers</a></li>
            <li><a href="../reports/">Reports</a></li>
            <li><a href="../logout.php">Logout</a></li>

        </ul>

    </aside>


    <main class="main-content">

        <div class="topbar">

            <div>

                <h1>Add Volunteer</h1>

                <p>Add a new NGO volunteer</p>

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

                    <label>Name *</label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Enter volunteer name"
                        required
                    >

                </div>
                <br>

                <div class="form-group">

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter email address"
                    >

                </div>

                <br>
                <div class="form-group">

                    <label>Phone</label>

                    <input
                        type="text"
                        name="phone"
                        maxlength="15"
                        placeholder="Enter phone number"
                    >

                </div>
                <br>

                <div class="form-group">

                    <label>Address</label>

                    <textarea
                        name="address"
                        rows="3"
                        placeholder="Enter address"
                    ></textarea>

                </div>
                <br>

                <div class="form-group">

                    <label>Joining Date</label>

                    <input
                        type="date"
                        name="joining_date"
                        value="<?php echo date('Y-m-d'); ?>"
                    >

                </div>
                <br>

                <div class="form-group">

                    <label>Role</label>

                    <select name="role">

                        <option value="">Select Role</option>

                        <option value="Field Volunteer">
                            Field Volunteer
                        </option>

                        <option value="Office Volunteer">
                            Office Volunteer
                        </option>

                        <option value="Event Volunteer">
                            Event Volunteer
                        </option>

                        <option value="Fundraising Volunteer">
                            Fundraising Volunteer
                        </option>

                        <option value="Education Volunteer">
                            Education Volunteer
                        </option>

                        <option value="Other">
                            Other
                        </option>

                    </select>

                </div>
                <br>


                <div class="form-group">

                    <label>Status</label>

                    <select name="status">

                        <option value="Active">
                            Active
                        </option>

                        <option value="Inactive">
                            Inactive
                        </option>

                    </select>

                </div>
                <br>


                <div class="form-buttons">

                    <button
                        type="submit"
                        class="btn-submit"
                    >
                        Add Volunteer
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
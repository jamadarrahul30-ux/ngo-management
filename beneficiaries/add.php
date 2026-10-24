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
    $age = $_POST["age"];
    $gender = trim($_POST["gender"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $category = trim($_POST["category"]);
    $created_at = $_POST["created_at"];

    if ($name == "") {

        $message = "Beneficiary name is required.";

    } else {

        $sql = "INSERT INTO beneficiaries
                (name, age, gender, phone, address, category, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sisssss",
            $name,
            $age,
            $gender,
            $phone,
            $address,
            $category,
            $created_at
        );

        if (mysqli_stmt_execute($stmt)) {

            header("Location: index.php");
            exit();

        } else {

            $message = "Error adding beneficiary: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Add Beneficiary - NGO Management System</title>

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
            <li><a href="index.php">Beneficiaries</a></li>
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

                <h1>Add Beneficiary</h1>

                <p>Add a new beneficiary</p>

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
                        placeholder="Enter beneficiary name"
                        required
                    >

                </div>
                <br>


                <div class="form-group">

                    <label>Age</label>

                    <input
                        type="number"
                        name="age"
                        min="0"
                        max="120"
                        placeholder="Enter age"
                    >

                </div>
                <br>


                <div class="form-group">

                    <label>Gender</label>

                    <select name="gender">

                        <option value="">Select Gender</option>

                        <option value="Male">Male</option>

                        <option value="Female">Female</option>

                        <option value="Other">Other</option>

                    </select>

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
                        placeholder="Enter address"
                        rows="3"
                    ></textarea>

                </div>
                <br>


                <div class="form-group">

                    <label>Category</label>

                    <select name="category">

                        <option value="">Select Category</option>

                        <option value="Child">Child</option>

                        <option value="Women">Women</option>

                        <option value="Senior Citizen">Senior Citizen</option>

                        <option value="Disabled">Disabled</option>

                        <option value="Low Income">Low Income</option>

                        <option value="Other">Other</option>

                    </select>

                </div>
                <br>


                <div class="form-group">

                    <label>Created Date</label>

                    <input
                        type="date"
                        name="created_at"
                        value="<?php echo date('Y-m-d'); ?>"
                    >

                </div>
                <br>


                <div class="form-buttons">

                    <button
                        type="submit"
                        class="btn-submit"
                    >
                        Add Beneficiary
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
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
    $donor_type = trim($_POST["donor_type"]);

    if ($name == "" || $email == "" || $phone == "") {

        $message = "Please fill in all required fields.";

    } else {

        $sql = "INSERT INTO donors
                (name, email, phone, address, donor_type)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssss",
            $name,
            $email,
            $phone,
            $address,
            $donor_type
        );

        if (mysqli_stmt_execute($stmt)) {

            header("Location: index.php?success=1");
            exit();

        } else {

            $message = "Error adding donor: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Donor - NGO Management System</title>

    <link rel="stylesheet"
          href="../css/style.css">

</head>

<body>

<div class="dashboard">

    <!-- Sidebar -->

    <aside class="sidebar">

        <h2>NGO Management</h2>

        <ul>

            <li>
                <a href="../dashboard.php">Dashboard</a>
            </li>

            <li>
                <a href="index.php">Donors</a>
            </li>

            <li>
                <a href="../donations/">Donations</a>
            </li>

            <li>
                <a href="../projects/">Projects</a>
            </li>

            <li>
                <a href="../resources/">Resources</a>
            </li>

            <li>
                <a href="../beneficiaries/">Beneficiaries</a>
            </li>

            <li>
                <a href="../distributions/">Distributions</a>
            </li>

            <li>
                <a href="../expenses/">Expenses</a>
            </li>

            <li>
                <a href="../volunteers/">Volunteers</a>
            </li>

            <li>
                <a href="../reports/">Reports</a>
            </li>

            <li>
                <a href="../logout.php">Logout</a>
            </li>

        </ul>

    </aside>


    <!-- Main Content -->

    <main class="main-content">

        <div class="topbar">

            <div>

                <h1>Add New Donor</h1>

                <p>Enter donor information</p>

            </div>

        </div>


        <div class="dashboard-section">

            <?php if ($message != ""): ?>

                <div class="error-message">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php endif; ?>


            <form method="POST"
                  action="add.php"
                  class="form-container">


                <div class="form-group">

                    <label for="name">
                        Donor Name *
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Enter donor name"
                        required
                    >

                </div>

                <br>
                <div class="form-group">

                    <label for="email">
                        Email *
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter email"
                        required
                    >

                </div>

                <br>
                <div class="form-group">

                    <label for="phone">
                        Phone *
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        placeholder="Enter phone number"
                        required
                    >

                </div>
                <br>

                <div class="form-group">

                    <label for="address">
                        Address:
                    </label>
                    <br><tr><tr><tr><tr>
                    <textarea
                        id="address"
                        name="address"
                        placeholder="Enter address"
                        rows="4"
                        style="margin-left: 60px;"
                    ></textarea>

                </div>
                <br>

                <div class="form-group">

                    <label for="donor_type">
                        Donor Type
                    </label>

                    <select
                        id="donor_type"
                        name="donor_type"
                    >

                        <option value="Individual">
                            Individual
                        </option>

                        <option value="Organization">
                            Organization
                        </option>

                        <option value="Corporate">
                            Corporate
                        </option>

                    </select>

                </div>
                <br>

                <div class="form-buttons">

                    <button
                        type="submit"
                        class="btn-submit"
                    >
                        Add Donor
                    </button>
                    <t>
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
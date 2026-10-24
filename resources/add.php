<?php

session_start();
include "../db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $resource_name = trim($_POST["resource_name"]);
    $category = trim($_POST["category"]);
    $unit = trim($_POST["unit"]);
    $quantity = $_POST["quantity"];
    $minimum_stock = $_POST["minimum_stock"];

    if ($resource_name == "") {

        $message = "Resource name is required.";

    } else {

        $sql = "INSERT INTO resources
                (resource_name, category, unit, quantity, minimum_stock)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssii",
            $resource_name,
            $category,
            $unit,
            $quantity,
            $minimum_stock
        );

        if (mysqli_stmt_execute($stmt)) {

            header("Location: index.php");
            exit();

        } else {

            $message = "Error adding resource: " . mysqli_error($conn);

        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Add Resource - NGO Management System</title>

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

            <li><a href="index.php">Resources</a></li>

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

                <h1>Add Resource</h1>

                <p>Add a new NGO resource</p>

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

                    <label>Resource Name *</label>

                    <input
                        type="text"
                        name="resource_name"
                        placeholder="Example: Rice"
                        required
                    >

                </div>
                <br>


                <div class="form-group">

                    <label>Category</label>

                    <select name="category">

                        <option value="">Select Category</option>

                        <option value="Food">Food</option>

                        <option value="Clothes">Clothes</option>

                        <option value="Books">Books</option>

                        <option value="Medicine">Medicine</option>

                        <option value="Education">Education</option>

                        <option value="Equipment">Equipment</option>

                        <option value="Other">Other</option>

                    </select>

                </div>
                <br>


                <div class="form-group">

                    <label>Unit</label>

                    <select name="unit">

                        <option value="">Select Unit</option>

                        <option value="Kg">Kg</option>

                        <option value="Pieces">Pieces</option>

                        <option value="Litres">Litres</option>

                        <option value="Boxes">Boxes</option>

                        <option value="Packets">Packets</option>

                    </select>

                </div>
                <br>


                <div class="form-group">

                    <label>Quantity</label>

                    <input
                        type="number"
                        name="quantity"
                        min="0"
                        value="0"
                        required
                    >

                </div>
                <br>


                <div class="form-group">

                    <label>Minimum Stock</label>

                    <input
                        type="number"
                        name="minimum_stock"
                        min="0"
                        value="0"
                        required
                    >

                </div>
                <br>


                <div class="form-buttons">

                    <button type="submit" class="btn-submit">
                        Add Resource
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
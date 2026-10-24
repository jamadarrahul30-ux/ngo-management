
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit();
}

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM donors WHERE donor_id = ?"
);

if (!$stmt) {
    die("Database error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: index.php?deleted=1");
    exit();
}

$error = mysqli_stmt_error($stmt);
mysqli_stmt_close($stmt);

http_response_code(409);

echo "<h3>Unable to delete donor</h3>";
echo "<p>This donor may be linked to donation records.</p>";
echo "<p>Database message: " .
    htmlspecialchars($error, ENT_QUOTES, "UTF-8") .
    "</p>";
echo '<p><a href="index.php">Back to Donors</a></p>';
?>

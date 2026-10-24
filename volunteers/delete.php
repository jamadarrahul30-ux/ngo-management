<?php
session_start();
if (!isset($_SESSION["user_id"])) {{ header("Location: ../index.php"); exit(); }}
require_once "../db.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {{ header("Location: index.php"); exit(); }}
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
if (!$id) {{ header("Location: index.php"); exit(); }}
$stmt = mysqli_prepare($conn, "DELETE FROM `volunteers` WHERE `volunteer_id` = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
if (mysqli_stmt_execute($stmt)) {{ header("Location: index.php?deleted=1"); exit(); }}
http_response_code(409);
echo '<p>This record could not be deleted. It may be linked to other records. Remove or update those related records first.</p><p><a href="index.php">Back to Volunteers</a></p>';
?>

<?php
session_start();
require_once "../db.php";
if (!isset($_SESSION["user_id"])) { header("Location: ../login.php"); exit(); }
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id) { header("Location: index.php"); exit(); }
$stmt = mysqli_prepare($conn, "SELECT beneficiary_id, name FROM beneficiaries WHERE beneficiary_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$beneficiary = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$beneficiary) { http_response_code(404); exit("Beneficiary not found."); }
$feedback = [];
$tableExists = mysqli_query($conn, "SHOW TABLES LIKE 'beneficiary_feedback'");
$feedbackTableExists = $tableExists && mysqli_num_rows($tableExists) > 0;
if ($feedbackTableExists) {
    $stmt = mysqli_prepare($conn, "SELECT feedback_id, feedback, feedback_date, submitted_by FROM beneficiary_feedback WHERE beneficiary_id = ? ORDER BY feedback_date DESC, feedback_id DESC");
    if ($stmt) { mysqli_stmt_bind_param($stmt, "i", $id); mysqli_stmt_execute($stmt); $res = mysqli_stmt_get_result($stmt); while ($row = mysqli_fetch_assoc($res)) $feedback[] = $row; }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Beneficiary Feedback</title><link rel="stylesheet" href="../css/style.css"></head><body>
<div class="dashboard"><aside class="sidebar"><h2>NGO Management</h2><ul>
<li><a href="../dashboard.php">Dashboard</a></li><li><a href="index.php">Beneficiaries</a></li><li><a href="../users/">Employees</a></li><li><a href="../logout.php">Logout</a></li>
</ul></aside><main class="main-content"><div class="topbar"><div><h1>Beneficiary Feedback</h1><p>Feedback for <?php echo htmlspecialchars($beneficiary['name']); ?></p></div><div class="user-info"><?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?></div></div>
<div class="dashboard-section"><h2><?php echo htmlspecialchars($beneficiary['name']); ?></h2>
<?php if (!$feedbackTableExists): ?><p>The feedback table has not been created yet. Import <strong>beneficiary_feedback.sql</strong> from this project ZIP into the <strong>ngo</strong> database.</p>
<?php elseif (!$feedback): ?><p>No feedback has been recorded for this beneficiary yet.</p>
<?php else: ?><table class="data-table"><thead><tr><th>Date</th><th>Feedback</th><th>Submitted by</th></tr></thead><tbody>
<?php foreach ($feedback as $item): ?><tr><td><?php echo htmlspecialchars($item['feedback_date'] ?? ''); ?></td><td><?php echo nl2br(htmlspecialchars($item['feedback'] ?? '')); ?></td><td><?php echo htmlspecialchars($item['submitted_by'] ?? ''); ?></td></tr><?php endforeach; ?>
</tbody></table><?php endif; ?><p><a href="index.php">← Back to Beneficiaries</a></p></div></main></div></body></html>

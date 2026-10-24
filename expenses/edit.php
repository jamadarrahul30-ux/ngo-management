<?php
session_start();
if (!isset($_SESSION["user_id"])) { header("Location: ../index.php"); exit(); }
require_once "../db.php";
$table = "expenses";
$primaryKey = "expense_id";
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id) { header("Location: index.php"); exit(); }
$error = "";
$schemaResult = mysqli_query($conn, "SHOW COLUMNS FROM `{$table}`");
$columns = [];
if ($schemaResult) { while ($col = mysqli_fetch_assoc($schemaResult)) { $columns[] = $col; } }
$recordResult = mysqli_prepare($conn, "SELECT * FROM `{$table}` WHERE `{$primaryKey}` = ?");
mysqli_stmt_bind_param($recordResult, "i", $id);
mysqli_stmt_execute($recordResult);
$record = mysqli_fetch_assoc(mysqli_stmt_get_result($recordResult));
if (!$record) { http_response_code(404); exit("Record not found. <a href='index.php'>Back</a>"); }
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $sets = []; $types = ""; $values = [];
  foreach ($columns as $col) {
    $name = $col["Field"];
    if ($name === $primaryKey || stripos($col["Extra"] ?? "", "auto_increment") !== false) continue;
    if (stripos($col["Extra"] ?? "", "GENERATED") !== false) continue;
    $value = isset($_POST[$name]) ? trim((string)$_POST[$name]) : "";
    if ($value === "" && strtoupper($col["Null"]) === "YES") $value = null;
    $type = strtolower($col["Type"]);
    if ($value !== null && preg_match('/^(tinyint|smallint|mediumint|int|bigint|decimal|float|double|real|bit)/', $type)) {
      if ($value !== "" && !is_numeric($value)) { $error = "Please enter a valid number for " . $name; break; }
      $types .= (preg_match('/^(decimal|float|double|real)/', $type) ? "d" : "i");
      if (preg_match('/^(decimal|float|double|real)/', $type)) $value = (float)$value; else $value = (int)$value;
    } else { $types .= "s"; }
    $sets[] = "`" . str_replace("`", "", $name) . "` = ?";
    $values[] = $value;
  }
  if ($error === "" && count($sets)) {
    $sql = "UPDATE `{$table}` SET " . implode(", ", $sets) . " WHERE `{$primaryKey}` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    $types .= "i"; $values[] = $id;
    $refs = []; $refs[] = $types; foreach ($values as $k => $v) $refs[] = &$values[$k];
    call_user_func_array([$stmt, "bind_param"], $refs);
    if (mysqli_stmt_execute($stmt)) { header("Location: index.php?updated=1"); exit(); }
    $error = "Could not update this record. Check related records and field values.";
  }
  foreach ($columns as $col) { $name=$col["Field"]; if (array_key_exists($name,$_POST) && $name !== $primaryKey) $record[$name]=$_POST[$name]; }
}
function e($value) { return htmlspecialchars((string)($value ?? ""), ENT_QUOTES, "UTF-8"); }
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit Expenses - NGO Management</title><link rel="stylesheet" href="../css/style.css"><style>.edit-panel{background:#fff;padding:24px;border-radius:10px;max-width:850px;box-shadow:0 2px 8px #0001}.edit-panel h1{margin-top:0}</style></head><body><div class="dashboard"><aside class="sidebar"><h2>NGO Management</h2><ul><li><a href="../dashboard.php">Dashboard</a></li><li><a href="../donors/">Donors</a></li><li><a href="../donations/">Donations</a></li><li><a href="../projects/">Projects</a></li><li><a href="../resources/">Resources</a></li><li><a href="../beneficiaries/">Beneficiaries</a></li><li><a href="../distributions/">Distributions</a></li><li><a href="../expenses/">Expenses</a></li><li><a href="../volunteers/">Volunteers</a></li><li><a href="../reports/">Reports</a></li><li><a href="../logout.php">Logout</a></li></ul></aside><main class="main-content"><div class="edit-panel"><h1>Edit Expenses</h1><p>Update the fields below and save your changes.</p><?php if ($error !== ""): ?><div class="notice-error"><?php echo e($error); ?></div><?php endif; ?><form method="post">
<?php foreach ($columns as $col): $name=$col["Field"]; if ($name === $primaryKey || stripos($col["Extra"] ?? "", "auto_increment") !== false || stripos($col["Extra"] ?? "", "GENERATED") !== false) continue; $type=strtolower($col["Type"]); $inputType="text"; if (strpos($type,"date")===0 && strpos($type,"datetime")!==0 && strpos($type,"timestamp")!==0) $inputType="date"; elseif (strpos($type,"datetime")===0 || strpos($type,"timestamp")===0) $inputType="datetime-local"; elseif (preg_match('/^(tinyint|smallint|mediumint|int|bigint)/',$type)) $inputType="number"; elseif (preg_match('/^(decimal|float|double|real)/',$type)) $inputType="number"; elseif (strpos($type,"text")!==false) $inputType="textarea"; ?>
<div class="form-field"><label for="<?php echo e($name); ?>"><?php echo e(ucwords(str_replace("_"," ",$name))); ?><?php if ($col["Null"] === "NO" && $col["Default"] === null): ?> *<?php endif; ?></label>
<?php if ($inputType === "textarea"): ?><textarea id="<?php echo e($name); ?>" name="<?php echo e($name); ?>" rows="4" <?php echo ($col["Null"] === "NO" && $col["Default"] === null) ? "required" : ""; ?>><?php echo e($record[$name] ?? ""); ?></textarea>
<?php else: ?><input id="<?php echo e($name); ?>" name="<?php echo e($name); ?>" type="<?php echo $inputType; ?>" value="<?php $v=$record[$name] ?? ""; if ($inputType === "datetime-local" && $v) $v=date("Y-m-d\TH:i",strtotime($v)); echo e($v); ?>" <?php echo ($col["Null"] === "NO" && $col["Default"] === null) ? "required" : ""; ?> <?php if ($inputType === "number") echo (preg_match('/^(decimal|float|double|real)/',$type) ? 'step="any"' : 'step="1"'); ?>></div><?php endif; ?>
<?php endforeach; ?><div class="form-actions"><button type="submit" class="btn">Save Changes</button><a class="action-link" href="index.php">Cancel</a></div></form></div></main></div></body></html>

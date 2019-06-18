<?php
require_once("head.php");
require_once("db_kpi.php");
require_once("user_logic.php");
require_once("get_date.php");

$kpi = $_POST['id'];
$time_spent = $_POST['time_spent'];
$servers = $_POST['servers'];
$description = $_POST['description'];
$status = 0;
$users_email = Whois();
$closure_date = date("Y-m-d H:i:s", strtotime($date));

$description = Replaces($description);

if(updateKPI($db, $kpi, $closure_date, $time_spent, $servers, $description, $status)) {

$_SESSION["success"] = "KPI <b>$kpi</b> of user $users_email was successfully added!";

header("Location: index.php");

} else {
	$msg = db2_conn_errormsg($db);
	db2_close($db);
?>
	<p class="text-danger">KPI <?= $kpi ?> failed to be altered: <?= $msg?></p><a href="index.php">Return</a>
<?php
}
?>

<?php include("footer.php"); ?>

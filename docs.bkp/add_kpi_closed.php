<?php 

require_once("head.php");
require_once("db_kpi.php");
require_once("user_logic.php");
require_once("get_date.php");

checkUser();

$severity = $_POST['severity'];

$users_email = Whois();
$agile_bucket = getUserInfo($db, $users_email);
$creation_date = date("Y-m-d H:i:s", strtotime($date));
$status = 0;
$type = $_POST['type'];
$external_ticket = $_POST['ext_ticket'];
$categories_id = $_POST['categories_id'];
$description = $_POST['description'];
$num_server = $_POST['num_server'];
$tools_id = $_POST['tools_id'];
$customers_code = $_POST['cust_code'];
$time_spent = $_POST['time_spent'];
$slaid = getSlaId($db, $customers_code, $severity, $type);

$external_ticket = Replaces($external_ticket);
$description = Replaces($description);
$msg_c = $categories_id ? "" : $_SESSION["danger"] = "Please select a valid category!";

if(createKpiClosed($db, $users_email, $agile_bucket[2], $agile_bucket[1], $creation_date, $time_spent, $description, $num_server, $external_ticket, $status, $slaid, $customers_code, $tools_id, $categories_id)) {

$kpiid = getKpiId($db);
$msg_kpi = $kpiid ? $_SESSION["success"] = "KPI <b>$kpiid</b> for user $users_email was successfully added!" : "";
header("Location: index.php");

} else {
	$msg = db2_conn_errormsg($db);
?>
	<p class="text-danger">The KPI could not be created: <?= $msg?><a href="index.php">Return</a></p>
<?php
echo "<br> users_email = $users_email <br>";
echo "agile_bucket = $agile_bucket[2]  <br>";
echo "agile_bucket = $agile_bucket[1] <br>";
echo "creation_date = $creation_date <br>";
echo "status = $status <br>";
echo "type = $type <br>";
echo "external_ticket = $external_ticket <br>";
echo "categories_id = $categories_id <br>";
echo "description = $description <br>";
echo "num_server = $num_server <br>";
echo "tools_id = $tools_id <br>";
echo "customers_code = $customers_code <br>";
echo "time_spent = $time_spent <br>";
echo "slaid = $slaid <br>";
}
?>

<?php include("footer.php"); ?>

<?php 
require_once("head.php");
require_once("db_kpi.php");
require_once("user_logic.php");

checkUser();
$lvl = $_SESSION["level"];

$id = $_POST['id'];
$severity = $_POST['severity'];
$users_email = $_POST['users_email'];
$agile_group = $_POST['agile_group'];
$orig_date = $_POST['creation_date'];
$creation_date = date("Y-m-d H:i:s", strtotime($orig_date));
$status = $_POST['status'];
$bucket = $_POST['bucket'];
$type = $_POST['type'];
$external_ticket = $_POST['ext_ticket'];
$description = $_POST['description'];
$num_server = $_POST['num_server'];
$tools_id = $_POST['tools_id'];
$customers_code = $_POST['cust_code'];
$category = $_POST['category'];
$categories_id = getCategoryId($db, $category);
$slaid = getSlaId($db, $customers_code, $severity, $type);

echo "<br>id = $id <br>";
echo "users_email = $users_email <br>";
echo "agile_group = $agile_group  <br>";
echo "creation_date = $creation_date <br>";
echo "status = $status <br>";
echo "bucket = $bucket <br>";
echo "external_ticket = $external_ticket <br>";
echo "description = $description <br>";
echo "num_server = $num_server <br>";
echo "tools_id = $tools_id <br>";
echo "customers_code = $customers_code <br>";
echo "categories_id = $categories_id <br>";
echo "slaid = $slaid <br>";


$msg_u = $agile_group ? "" : $_SESSION["danger"] = "Please use a valid and registered user!";
$msg_c = $categories_id ? "" : $_SESSION["danger"] = "Please select a valid category!";
$external_ticket = Replaces($external_ticket);
$description = Replaces($description);

$reglvl = getLevel($bucket);

if ($reglvl != "one" && $reglvl != "two") {
	$_SESSION["danger"] = "You can only assign KPIs for analysts!";
	locate($lvl);
} elseif (changeKPI($db, $id, $users_email, $bucket, $agile_group, $creation_date, $description, $num_server, $external_ticket, $status, $slaid, $customers_code, $tools_id, $categories_id)) {

		$kpiid = getKpiId($db);
		$_SESSION["success"] = "KPI <b>$id</b> for user $users_email was successfully changed! - Audit <b>$kpiid</b>";
		locate($lvl);

} else {
?>
			<p class="text-danger">The KPI could not be altered! Please check with administrator</p><a href="index.php">Return</a></br></br>
<?php
		locate($lvl);
?>

<?php
}

function getLevel($bucket) {

if(in_array($bucket, array('L1','L2','L3','L4','JA','ETG'), true)) {
  				$level = "one";
					}elseif($bucket == "24x7"){
						$level = "two";
						}elseif($bucket == "DISPATCHER"){
							$level = "three";
							}else{
								$level = "four";
									}
	return $level;
	}

function locate($lvl){
	if ($lvl == "one" || $lvl == "two"){
	return header("Location: searchanalyst.php?flag=x");
	}else{
	return header("Location: searchdisp.php?flag=x");
	}
}

?>

<?php include("footer.php"); ?>

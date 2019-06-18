<?php 
require_once("head.php");
require_once("db_kpi.php");
require_once("user_logic.php");

checkUser();

$severity = $_POST['severity'];
$users_email = $_POST['users_email'];
$user_creator = Whois();
$agile_group = $_POST['agile_group'];
$orig_date = $_POST['creation_date'];
$creation_date = date("Y-m-d H:i:s", strtotime($orig_date));
$status = 1;
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

$external_ticket = Replaces($external_ticket);
$description = Replaces($description);
$msg_u = $agile_group ? "" : $_SESSION["danger"] = "Please use a valid and registered user!";
$msg_c = $categories_id ? "" : $_SESSION["danger"] = "Please select a valid category!";

$lvl = getLevel($bucket);

if ($lvl != "one" && $lvl != "two") {
	$_SESSION["danger"] = "You can only create KPI for analysts!";
	header("Location: kpi_form.php");
} elseif (createKPI($db, $users_email, $bucket, $agile_group, $user_creator, $creation_date, $description, $num_server, $external_ticket, $status, $slaid, $customers_code, $tools_id, $categories_id)) {

		$kpiid = getKpiId($db);
		$_SESSION["success"] = "KPI <b>$kpiid</b> for user $users_email was successfully added!";
		echo "Sucesso <b>$kpiid</b>";
		header("Location: kpi_form.php");

} else {
?>
			<p class="text-danger">The KPI could not be created! Please check with administrator</p><a href="index.php">Return</a></br></br>
<?php
				header("Location: kpi_form.php");
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

?>

<?php include("footer.php"); ?>

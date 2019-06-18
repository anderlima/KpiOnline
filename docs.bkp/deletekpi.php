<?php
require_once("head.php");
require_once("db_kpi.php"); 
require_once("user_logic.php");

$id = $_POST['id'];
deleteKPI($db, $id);
$_SESSION["success"] = "KPI <b>$id</b> was successfully removed.";
header("Location: searchdisp.php?flag=x");
die();

?>

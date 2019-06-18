<?php
require_once("head.php");
require_once("db_kpi.php"); 
require_once("user_logic.php");

$id = $_POST['id'];
$description = $_POST['description'];
dupKPI($db, $id, $description);
$kpiid = getKpiId($db);
$_SESSION["success"] = "KPI <b>$id</b> was successfully duplicated to <b>$kpiid</b>.";
$lvl = $_SESSION["level"];

if($lvl == "one" || $lvl == "two"){
      header("Location: searchanalyst.php?flag=x");
}else{

            header("Location: searchdisp.php?flag=x");
}
die();

?>

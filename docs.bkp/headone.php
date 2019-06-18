<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once("show_alert.php");

require_once("user_logic.php");
require_once("db_kpi.php");

$amount_min = getTimeSpent($db, Whois());
$amount_hours = $amount_min/60;
$firstname = explode(' ', trim($_SESSION["name"]));
?>


<html>
<head>
	<meta charset="utf-8">
	<title>KPIOnline</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/complement.css" rel="stylesheet">
	<link href="css/jquery.datetimepicker.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/jquery.datetimepicker.full.js"></script>
</head>
<body>

	<div class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
			<a href="index.php"><img id ="logo" class="nav" src="pic/ibm_logo.gif"></a>
			</div>
			<div>
				<ul class="nav navbar-nav">
					<li><a class="navbar-brand" href="index.php">KPIOnline</a></li>
					<li><a href="searchanalyst.php">Search</a></li>
					<li><a href="reports.php">Reports</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
				<li class="nohov"><a href="#">Hello <?= $firstname[0] ?> - Week hours  <b><?= number_format($amount_hours, 2, '.','') ?></b></a></li>
				<li><a href="logout.php">Logout</a></li>
				<li><a href="help_page.php">&nbsp<span class="glyphicon glyphicon-question-sign"></span>&nbsp</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="main">
			<?php showAlert("success"); ?>
			<?php showAlert("danger"); ?>

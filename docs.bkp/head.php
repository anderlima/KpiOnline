<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once("show_alert.php");
?>


<html>
<head>
	<meta charset="utf-8">
	<title>KPIOnline</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/complement.css" rel="stylesheet">
	<link href="css/datepicker.css" rel="stylesheet">
	<link href="css/jquery.datetimepicker.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/jquery.datetimepicker.full.js"></script>
</head>
<body>

	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
			<a href="index.php"><img id ="logo" class="nav" src="pic/ibm_logo.gif"></a>
			</div>
				<ul class="nav navbar-nav">
				<li><a class="navbar-brand" href="index.php">KPIOnline</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="help.php">&nbsp<span class="glyphicon glyphicon-question-sign"></span>&nbsp</a></li>
				</ul>
			</div>
	</nav>

	<div class="container">
		<div class="main">
			<?php showAlert("success"); ?>
			<?php showAlert("danger"); ?>

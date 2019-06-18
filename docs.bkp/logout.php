<?php
require_once("user_logic.php");

logout();
$_SESSION["success"] = "Successfully logout.";
header("Location: index.php");
die();

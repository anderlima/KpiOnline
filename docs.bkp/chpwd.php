<?php

require_once("db_user.php");
require_once("user_logic.php");


$email = Whois();
$curpass = $_POST["curpass"];
$newpass = $_POST["newpass"];
$reppass = $_POST["reppass"];

$user = getUser($connection, $email, $curpass);

if($user == null) {
	$_SESSION["danger"] = "Current password is wrong. Please try again!";
	header("Location: chpage.php");
} else {
	if ($newpass == $reppass){
		changePass($connection, $email, $newpass);
		$_SESSION["success"] = "Password successfully changed!";
		header("Location: chpage.php");
	}else {
		$_SESSION["danger"] = "Passwords do not match. Please try again";
		header("Location: chpage.php");
	}
}
die();

?>
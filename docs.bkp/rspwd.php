<?php

require_once("db_user.php");
require_once("user_logic.php");

$email = $_POST["email"];
$newpass = $_POST["newpass"];
$reppass = $_POST["reppass"];

$user = validateUser($connection, $email);

if($user == null) {
	$_SESSION["danger"] = "Provided user does not exist";
	header("Location: rspage.php");
} else {
	if ($newpass == $reppass){
		changePass($connection, $email, $newpass);
		$_SESSION["success"] = "Password successfully reset!";
		header("Location: rspage.php");
	}else {
		$_SESSION["danger"] = "Passwords do not match. Please try again";
		header("Location: rspage.php");
	}
}
die();

?>
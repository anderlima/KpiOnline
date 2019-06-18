<?php
session_start();

function isUserLogged() {
	return isset($_SESSION["user_email"]);
}

function checkUser() {
	if(!isUserLogged()) {
		header("Location: login_page.php");
		die();
	}
}

function Whois() {
	return $_SESSION["user_email"];
}

function WhosName() {
	return $_SESSION["user_name"];
}

function logUser($email, $name) {
	$_SESSION["user_email"] = $email;
	$_SESSION["user_name"] = $name;
}


function getPrivilege($level) {
	$_SESSION["level"] = $level;
	return $_SESSION["level"];
}

function logout() {
	session_destroy();
	session_start();
}
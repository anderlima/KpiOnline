<?php

require_once("db_user.php");
require_once("user_logic.php");

$email = $_POST["email"];
$group = $_POST["group"];
$bucket = $_POST["bucket"];

$user = getUser($db, $email);

if($user == null){
    $_SESSION["danger"] = "User does not exist!";
    header("Location: altpage.php");
} else {
    $user = AlterUser($db, $email, $group, $bucket);
    header("Location: altpage.php");
    $_SESSION["success"] = "Successfully updated!";
}
die();

?>




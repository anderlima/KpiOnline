<?php
require_once("user_logic.php");

            unset($_SESSION['kpiid']);
            unset($_SESSION['users_email']);
            unset($_SESSION['creation_date']);
            unset($_SESSION['type']);
            unset($_SESSION['customer']);
            unset($_SESSION['tool']);
            unset($_SESSION['ticket']);
            unset($_SESSION['severity']);
            unset($_SESSION['description']);
            unset($_SESSION['status']);

$lvl = $_SESSION["level"];

if($lvl == "one" || $lvl == "two"){
      header("Location: searchanalyst.php");
}else{

            header("Location: searchdisp.php");
}

?>

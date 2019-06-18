<?php

date_default_timezone_set('America/Sao_Paulo');
$date = date("Y/m/d H:i", time());
$today = date("Y/m/d");

function startsWith($string, $compare) {
    return $compare === "" || strrpos($string, $compare, -strlen($string)) !== false;
}

?>

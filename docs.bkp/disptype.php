<?php

$customers_code = $_POST['cust_code'];

header("Location: index.php?customer_code=$customers_code");
?>
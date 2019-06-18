<?php

$customers_code = $_POST['cust_code'];

header("Location: kpi_form.php?customer_code=$customers_code");
?>
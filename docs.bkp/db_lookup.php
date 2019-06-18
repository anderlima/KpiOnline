<?php
require_once("connect_db.php");

function listTools($db){
	$sth = $db->query("SELECT * FROM tools ORDER BY name ASC");
  	return $sth;
}

function listCategories($db){
	if(isset($_SESSION['hcgroup'])) {
	$sth = $db->query("SELECT * FROM categories WHERE visibility=1");
	} else {
	$sth = $db->query("SELECT * FROM categories WHERE visibility=1 and name <> 'Training'");
}
  	return $sth;
}

function listCustomers($db){
	$sth = $db->query("SELECT * FROM customers ORDER BY name ASC");
  	return $sth;
}

function listTypesbyCust($db, $customers_code){
	$types = array();
	$sth = $db->prepare("SELECT DISTINCT type from slas where customers_code= :customers_code");
	$sth->bindValue(':customers_code', $customers_code);
	$sth->execute();
  	while($result = $sth->fetch(PDO::FETCH_ASSOC)){
	array_push($types, $result);
}
return $types;
}

function listSevbyCust($db, $customers_code, $type){
	$severities = array();
	$sth = $db->prepare("SELECT severity from slas where customers_code= :customers_code AND type= :type");
	$sth->bindValue(':customers_code', $customers_code);
	$sth->bindValue(':type', $type);
	$sth->execute();
  	while($result = $sth->fetch(PDO::FETCH_ASSOC)){
  		array_push($severities, $result);
  	}

	return $severities;

}


<?php 

require_once("connect_db.php");

$action = (isset($_GET['action'])) ? $_GET['action'] : '';
$parameter = (isset($_GET['parameter'])) ? $_GET['parameter'] : '';

if($action == 'autocomplete'):
	$where = (!empty($parameter)) ? 'WHERE email LIKE ?' : '';
	$sql = "SELECT email, groups, bucket FROM users " . $where;

	$stm = $db->prepare($sql);
	$stm->bindValue(1, '%'.$parameter.'%');
	$stm->execute();
	$data = $stm->fetchAll(PDO::FETCH_OBJ);

	$json = json_encode($data);
	echo $json;
endif;

//Verifica se foi solicitado uma consulta para o autocomplete_cat
if($action == 'autocomplete_cat'):
	$where = (!empty($parameter)) ? 'WHERE name LIKE ?' : '';
	$sql = "SELECT name FROM categories " . $where;

	$stm = $db->prepare($sql);
	$stm->bindValue(1, '%'.$parameter.'%');
	$stm->execute();
	$data = $stm->fetchAll(PDO::FETCH_OBJ);

	$json = json_encode($data);
	echo $json;
endif;

// Verifica se foi solicitado uma consulta para preencher os campos do formulário
if($action == 'consult'):
	$sql = "SELECT email, groups, bucket FROM users ";
	$sql .= "WHERE email LIKE ? LIMIT 1";

	$stm = $db->prepare($sql);
	$stm->bindValue(1, $parameter.'%');
	$stm->execute();
	$data = $stm->fetchAll(PDO::FETCH_OBJ);

	$json = json_encode($data);
	echo $json;
endif;
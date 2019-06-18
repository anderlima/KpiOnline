<?php
require_once("connect_db.php");

function getSlaId($db, $code, $sev, $type){

		$sth = $db->prepare("SELECT * FROM slas WHERE severity = :sev AND customers_code = :code AND type = :type");
		$sth->bindValue(':sev', $sev);
		$sth->bindValue(':code', $code);
		$sth->bindValue(':type', $type);
		$sth->execute();

		$result = $sth->fetch(PDO::FETCH_ASSOC);
    	$sla_id = $result['id'];

    	return $sla_id;
}



 ?>
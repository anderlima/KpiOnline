<?php

require_once("../connect_db.php");

$types = getTypeChart($db);

  $rows = array();
  $table = array();
  $table['cols'] = array(

    array('label' => 'Name', 'type' => 'string'),
    array('label' => 'Number', 'type' => 'number')

);

    foreach($types as $type) {

      $temp = array();
      $temp[] = array('v' => (string) $type['type']);
      $temp[] = array('v' => (int) $type['num']);
      $rows[] = array('c' => $temp);
    }

$table['rows'] = $rows;

$jsonTable = json_encode($table);

echo $jsonTable;

function getTypeChart($db){
  $rows = array();

  $sth = $db->prepare("select t.name as type, count(k.users_email) as num from kpis as k join tools as t on t.id=k.tools_id group by t.name");

  $sth->execute();

  while($result = $sth->fetch(PDO::FETCH_ASSOC)) {
    array_push($rows, $result);
  }

  return $rows;
}

?>

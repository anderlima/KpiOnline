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

  $sth = $db->prepare("select s.type as type, count(k.id) as num from kpis as k join slas as s on k.slas_id=s.id where (julianday(closure_date) - julianday(creation_date)) > (julianday('2017-01-06') - julianday('2017-01-01')) group by s.type");

  $sth->execute();

  while($result = $sth->fetch(PDO::FETCH_ASSOC)) {
    array_push($rows, $result);
  }

  return $rows;
}

?>

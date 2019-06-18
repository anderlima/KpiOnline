<?php
try {	$db = new PDO('sqlite:../kpionline.sqlite'); }
catch(PDOException $e) {  print 'Exception : '.$e->getMessage();  }

$sth = $db->query("select tools.name as tools, count(*) as quantity from kpis join tools on kpis.tools_id=tools.id where kpis.status!=3 group by tools.name");

$data=array();
$rows=array();
while($result = $sth->fetch(PDO::FETCH_ASSOC)){
	$temp=array();
	$temp[] = array('v' => (string) $result['tools']);
	$temp[] = array('v' => (int) $result['quantity']);
	$rows[] = array('c' => $temp);
	array_push($data, $result);
}
$table=array();
$table['cols']=array(
array('label'=>'tools','type'=>'string'),
array('label'=>'quantity','type'=>'number')
);
$table['rows']=$rows;
$jsonTable = json_encode($table);
echo $jsonTable;
?>

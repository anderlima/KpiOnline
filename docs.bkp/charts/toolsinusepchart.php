<?php
try {	$db = new PDO('sqlite:../kpionline.sqlite'); }
catch(PDOException $e) {  print 'Exception : '.$e->getMessage();  }

$sth = $db->query("select tools.name as tools, count(*) as quantity from kpis join tools on kpis.tools_id=tools.id where kpis.status!=3 group by tools.name");

$data=array();
while($result = $sth->fetch(PDO::FETCH_ASSOC)){ array_push($data, $result); }
for ($i=0;$i<count($data);$i++){
       	$key=key($data);
	$val=$data[$key]; 
	echo $key ." = ".  $val['tools'] . $val['quantity'] . " <br> ";
        next($data);
}

include("pchart/class/pData.class");  
include("pchart/class/pChart.class");  
  
// Dataset definition   
$DataSet = new pData;  
$DataSet->AddPoint(array(10,2,3,5,3),"Serie1");  
$DataSet->AddPoint(array("Jan","Feb","Mar","Apr","May"),"Serie2");  
$DataSet->AddAllSeries();  
$DataSet->SetAbsciseLabelSerie("Serie2");  
  
// Initialise the graph  
$Test = new pChart(300,200);  
$Test->drawFilledRoundedRectangle(7,7,293,193,5,240,240,240);  
$Test->drawRoundedRectangle(5,5,295,195,5,230,230,230);  
  
// Draw the pie chart  
$Test->setFontProperties("Fonts/tahoma.ttf",8);  
$Test->setShadowProperties(2,2,200,200,200);  
$Test->drawFlatPieGraphWithShadow($DataSet->GetData(),$DataSet->GetDataDescription(),120,100,60,PIE_PERCENTAGE,10);  
$Test->drawPieLegend(230,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);  
  
$Test->Render("example13.png");  
?>  



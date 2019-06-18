<?php

$date = date("m.d.y");
$customer = "ACE_STD-P-ITM6-P04";

$ls = shell_exec("ls  /nwsbr/hc/cgi-bin/data/tivoli/".$customer."/");
$file_parse = substr($ls, strpos($ls, ".") +1);
$file = substr($file_parse, 0, strpos($file_parse, ".") +6);
echo "<br>".$file;

$result = shell_exec("cat /nwsbr/hc/cgi-bin/data/tivoli/".$customer."/hc.".$date.".txt |grep -E '@NO@' |awk -F@ '{print $1}' 2>&1");
echo "<pre>$result</pre>";

echo $date;

if ($file == $date){
echo "<br> Updated";
}else{
echo "<br> Outdated";
}
?>

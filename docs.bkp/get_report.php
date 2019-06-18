<?php

require_once("connect_db.php");
require_once("user_logic.php");
require_once("get_date.php");
require_once("db_kpi.php");

checkUser();

$lvl = $_SESSION["level"];

$date_s = $_POST['date_start'];
$date_start = date("Y-m-d H:i:s", strtotime($date_s));
$date_e = $_POST['date_end'];
$date_end = date("Y-m-d H:i:s", strtotime($date_e));

$sql = genReport();

if ($lvl == "one" || $lvl == "two"){
    $user = Whois();
    $sql .= ' AND NOT(k.status = 3 or k.status = 2)';
}else{
    $user = "";
}
        $sth = $db->prepare($sql);
        $sth->bindValue(':user', '%'.$user);
        $sth->bindValue(':date_start', $date_start);
        $sth->bindValue(':date_end', $date_end);

        $sth->execute();
        $colcount = $sth->columnCount();

header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=general_report.xls");
header("Pragma: no-cache"); 
header("Expires: 0");
$sep = "\t"; 


$tablesquery = $db->query("PRAGMA table_info(report)");
while ($columns = $tablesquery->fetch(PDO::FETCH_ASSOC)){
    print ($columns['name'] . $sep);
}

print("\n");
while($row = $sth->fetch(PDO::FETCH_NUM)) {
    $schema_insert = "";
    for($j=0; $j<$colcount;$j++) {
        if(!isset($row[$j]))
            $schema_insert .= "NULL".$sep;
        elseif ($row[$j] != "")
            $schema_insert .= "$row[$j]".$sep;
        else
            $schema_insert .= "".$sep;
    }
    $schema_insert = str_replace($sep."$", "", $schema_insert);
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= "\t";
    print(trim($schema_insert));
    print "\n";
}
?>


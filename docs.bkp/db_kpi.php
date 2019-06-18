<?php
require_once("connect_db.php");
require_once("user_logic.php");

function weekHours($db){
    $weekhours = array();
    $firsthalfs = array();
    $secondhalfs = array();
    $thirdhalfs = array();
    $count = 0;
    $sth = $db->prepare("SELECT users.email as email, COALESCE(SUM(kpis.time_spent),0) as sum FROM kpis join users on kpis.users_email=users.email WHERE closure_date >= DATETIME('now', 'localtime', 'weekday 0', '-8 days', 'start of day') AND closure_date <= DATETIME('now', 'localtime')  OR kpis.time_spent IS NULL GROUP BY users.email ORDER BY SUM(kpis.time_spent)");

    $sth->execute();
    while($weekhour = $sth->fetch(PDO::FETCH_NUM)) {
    array_push($weekhours, $weekhour);
    }

    $rows = count($weekhours);
    $div = $rows/3;

    $sth->execute();
    while($count < $div && $firsthalf = $sth->fetch(PDO::FETCH_ASSOC)){
        array_push($firsthalfs, $firsthalf);
        $count++;
    }

    while($count < ($div * 2) && $secondhalf = $sth->fetch(PDO::FETCH_ASSOC)){
        array_push($secondhalfs, $secondhalf);
        $count++;
    }

    while($thirdhalf = $sth->fetch(PDO::FETCH_ASSOC)){
        array_push($thirdhalfs, $thirdhalf);
        $count++;
    }

$allset = array($firsthalfs);
array_push($allset, $secondhalfs);
array_push($allset, $thirdhalfs);

if (sizeof($allset[0]) == sizeof($allset[1])){
    $even1 = 1;
}

if (sizeof($allset[1]) == sizeof($allset[2])){
    $even2 = 1;
}

array_push($allset, $even1);
array_push($allset, $even2);

return $allset;
}

function backlog($db){
    $backlogs = array();
    $firsthalfs = array();
    $secondhalfs = array();
    $count = 0;
    $even = 0;
    $sth = $db->prepare("select CASE k.status WHEN 1 THEN count(k.status) ELSE '0' END as opened, u.email as users_email, e.quantity as duesoon, u.groups as agile_group from users as u left join expired as e on u.email=e.email left join kpis as k on u.email=k.users_email where k.status=1 or k.users_email not in (select users_email from kpis where status=1) group by u.email order by u.groups");

    $sth->execute();
    while($backlog = $sth->fetch(PDO::FETCH_NUM)) {
    array_push($backlogs, $backlog);
    }

    $rows = count($backlogs);
    $half = $rows/2;
    if ($rows % 2 == 0) {
      $even = 1;
    }

    $sth->execute();
    while($count < $half && $firsthalf = $sth->fetch(PDO::FETCH_ASSOC)){
        array_push($firsthalfs, $firsthalf);
        $count++;
    }

    while($secondhalf = $sth->fetch(PDO::FETCH_ASSOC)){
        array_push($secondhalfs, $secondhalf);
        $count++;
    }

$allset = array($firsthalfs);
array_push($allset, $secondhalfs);
array_push($allset, $even);

return $allset;
}

function listUserKpi($db) {
	$kpis = array();
	$user = Whois();
	$sth = $db->prepare("SELECT k.*, s.type, s.sla, c.name as c_name, t.name as t_name, s.severity FROM kpis as k
	JOIN slas as s on k.slas_id=s.id 
	JOIN customers as c on k.customers_code=c.code 
	JOIN tools as t on k.tools_id=t.id WHERE users_email = :user AND status='1' ORDER BY creation_date DESC");

	$sth->bindValue(':user', $user);
	$sth->execute();

	while($kpi = $sth->fetch(PDO::FETCH_ASSOC)) {
	array_push($kpis, $kpi);
	}
	return $kpis;
}

function getInfoReg($db, $id, $table){
	$sth = $db->prepare("SELECT * FROM $table WHERE id = :id");

	$sth->bindValue(':id', $id);
	$sth->execute();

	$result = $sth->fetch(PDO::FETCH_ASSOC);

    return $result;

}

function getTimeSpent($db, $user){
	if(date('D') == 'Sat' || date('D') == 'Sun') {
	$sth = $db->prepare("SELECT SUM(time_spent) FROM kpis WHERE users_email= :user AND closure_date >= DATETIME('now', 'localtime', 'weekday 0', '-1 days', 'start of day') AND closure_date <= DATETIME('now', 'localtime') AND status=0");
	}else{
	$sth = $db->prepare("SELECT SUM(time_spent) FROM kpis WHERE users_email= :user AND closure_date >= DATETIME('now', 'localtime', 'weekday 0', '-8 days', 'start of day') AND closure_date <= DATETIME('now', 'localtime') AND status=0");
	}
		
	$sth->bindValue(':user', $user);
	$sth->execute();
  	$result = $sth->fetch(PDO::FETCH_NUM);
  	$amount_time = $result[0];

return $amount_time;
}

function getUserInfo($db, $user){
	$sth = $db->prepare("SELECT * FROM users WHERE email = :user");

	$sth->bindValue(':user', $user);
	$sth->execute();

	$result = $sth->fetch(PDO::FETCH_NUM);

    return $result;
}

function getCustomerName($db, $customer_code){
	$sth = $db->prepare("SELECT * FROM customers WHERE code = :customer_code");

	$sth->bindValue(':customer_code', $customer_code);
	$sth->execute();

	$result = $sth->fetch(PDO::FETCH_ASSOC);
    	$customer = $result['name'];

    	return $customer;
}

function getKpiId($db){
	$sth = $db->query("SELECT MAX(id) as max FROM kpis");

	$result = $sth->fetch(PDO::FETCH_NUM);
   	$kpiid = $result[0];

	return $kpiid;
}

function getCategoryId($db, $category){

	$sth = $db->prepare("SELECT * FROM categories WHERE name = :category ");

	$sth->bindValue(':category', $category);
	$sth->execute();

	$result = $sth->fetch(PDO::FETCH_ASSOC);
    	$category_id = $result['id'];

    	return $category_id;
}

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

function filterUserKPIs($db, $kpiid, $users_email, $creation_date, $type, $customer, $tool, $ticket, $severity, $description, $status){
	#$oneweek = date('Y-m-d', strtotime('- 1 week'));
	$rows = array();
	$lvl = $_SESSION["level"];

	if($kpiid == ""){
        $imp = 'NULL';
    	}else {
        $kpiid_n = explode(',', $kpiid);
        $imp = implode(',', $kpiid_n);
    	}

    	$in  = str_repeat('?,', count($imp) - 1) . '?';

    	if ($lvl == "one" || $lvl == "two"){
    		$period = "-15 days";
    	} elseif($kpiid == "" && $users_email == "" && $creation_date == "" && $type == "" && $customer == "" && $tool == "" && $ticket == "" && $severity == "" && $description == "" && $status == ""){
    		$period = "-30 days";
    	} else {
		$period = "-730 days";
	}

	$sth = $db->prepare("SELECT k.*, s.sla, s.type, c.name as c_name, t.name as t_name, s.severity, k.status FROM kpis as k
	JOIN slas as s on k.slas_id=s.id 
	JOIN tools as t on k.tools_id=t.id
	JOIN customers as c on k.customers_code=c.code
	WHERE (k.id LIKE :kpiid OR k.id in ($in)) AND k.creation_date LIKE :creation_date AND s.type LIKE :type AND c.name LIKE :customer AND t.name LIKE :tool AND k.external_ticket LIKE :ticket
	AND s.severity LIKE :severity AND k.description LIKE :description AND k.users_email LIKE :users_email AND k.status LIKE :status AND k.creation_date >= DATETIME('now', 'localtime', 'weekday 0', '$period', 'start of day') AND NOT(k.status = 2) AND NOT(k.status = 3) ORDER BY k.creation_date DESC");

	$sth->bindValue(':kpiid', '%'.$kpiid.'%');
	$sth->bindValue(':creation_date', '%'.$creation_date.'%');
	$sth->bindValue(':type', '%'.$type.'%');
	$sth->bindValue(':customer', '%'.$customer.'%');
	$sth->bindValue(':tool', '%'.$tool.'%');
	$sth->bindValue(':ticket', '%'.$ticket.'%');
	$sth->bindValue(':severity', '%'.$severity.'%');
	$sth->bindValue(':description', '%'.$description.'%');
	$sth->bindValue(':users_email', '%'.$users_email.'%');
	$sth->bindValue(':status', '%'.$status.'%');

	$sth->execute();

	while($result = $sth->fetch(PDO::FETCH_ASSOC)) {
		array_push($rows, $result);
	}

	return $rows;
}

function genReport(){
	
		$sql = "SELECT k.id as 'Ticket Num', k.description as 'Description', ca.name as 'Category', ca.description as 'Category Description', cu.code as 'Customer', s.type as 'Type', k.external_ticket as 'External Ticket', s.severity as 'Priority', case k.bucket when 'L1' then 'R' when 'L2' then 'B' when 'L3' then 'J' when 'L4' then 'L4' when '24x7' then '24x7' when 'ETG' then 'ETG' when 'JA' then 'JA' end as 'Bucket', k.user_creator as 'User Creator', k.users_email as 'Assigned to', k.creation_date as 'Creation Date', k.closure_date as 'Closure Date', case k.status when 0 then 'Closed' when 1 then 'Open' when 2 then 'Del' when 3 then 'Audit' end as 'Status', strftime('%m', k.creation_date) as 'Month', strftime('%w', k.creation_date) as 'Day of Week', k.time_spent as 'Time Spent', k.num_server as 'Servers QTY' FROM kpis as k
                JOIN slas as s on k.slas_id=s.id 
                JOIN categories as ca on k.categories_id=ca.id 
                JOIN customers as cu on k.customers_code=cu.code 
                JOIN tools as t on k.tools_id=t.id WHERE k.users_email LIKE :user AND creation_date BETWEEN :date_start AND :date_end";
		return $sql;
}

function createKPI($db, $users_email, $bucket, $agile_group, $user_creator, $creation_date, $description, $num_server, $external_ticket, $status, $slaid, $customers_code, $tools_id, $categories_id) {

	$sth = $db->prepare("INSERT INTO kpis (users_email, bucket, agile_group, user_creator, creation_date, description, num_server, external_ticket, status, slas_id, customers_code, tools_id, categories_id) VALUES (:users_email, :bucket, :agile_group, :user_creator, :creation_date, :description, :num_server, :external_ticket, :status, :slaid, :customers_code, :tools_id, :categories_id)");

	$sth->bindValue(':users_email', $users_email);
	$sth->bindValue(':bucket', $bucket);
	$sth->bindValue(':agile_group', $agile_group);
	$sth->bindValue(':user_creator', $user_creator);
	$sth->bindValue(':creation_date', $creation_date);
	$sth->bindValue(':description', $description);
	$sth->bindValue(':num_server', $num_server);
	$sth->bindValue(':external_ticket', $external_ticket);
	$sth->bindValue(':status', $status);
	$sth->bindValue(':slaid', $slaid);
	$sth->bindValue(':customers_code', $customers_code);
	$sth->bindValue(':tools_id', $tools_id);
	$sth->bindValue(':categories_id', $categories_id);
	return	$sth->execute();

}

function createKpiClosed($db, $users_email, $bucket, $agile_group, $date, $time_spent, $description, $num_server, $external_ticket, $status, $slaid, $customers_code, $tools_id, $categories_id) {

	$sth = $db->prepare("INSERT INTO kpis (users_email, bucket, agile_group, user_creator, creation_date, closure_date, time_spent, description, num_server, external_ticket, status, slas_id, customers_code, tools_id, categories_id) VALUES (:users_email, :bucket, :agile_group, :users_email, :date, :date, :time_spent, :description, :num_server, :external_ticket, :status, :slaid, :customers_code, :tools_id, :categories_id)");

	$sth->bindValue(':users_email', $users_email);
	$sth->bindValue(':bucket', $bucket);
	$sth->bindValue(':agile_group', $agile_group);
	$sth->bindValue(':date', $date);
	$sth->bindValue(':time_spent', $time_spent);
	$sth->bindValue(':description', $description);
	$sth->bindValue(':num_server', $num_server);
	$sth->bindValue(':external_ticket', $external_ticket);
	$sth->bindValue(':status', $status);
	$sth->bindValue(':slaid', $slaid);
	$sth->bindValue(':customers_code', $customers_code);
	$sth->bindValue(':tools_id', $tools_id);
	$sth->bindValue(':categories_id', $categories_id);
	return	$sth->execute();
}

function updateKPI($db, $kpi, $date, $time_spent, $servers, $description, $status) {

	$sth = $db->prepare("UPDATE kpis SET closure_date = :date, time_spent = :time_spent,	description = :description, num_server = :servers, status = :status WHERE id = :kpi");

	$sth->bindValue(':date', $date);
	$sth->bindValue(':kpi', $kpi);
	$sth->bindValue(':time_spent', $time_spent);
	$sth->bindValue(':servers', $servers);
	$sth->bindValue(':description', $description);
	$sth->bindValue(':status', $status);

	return	$sth->execute();
}

function deleteKPI($db, $id) {
	
	$user = Whois();
	$date = date("Y-m-d");
	$sth = $db->prepare("UPDATE kpis set status=2, description = 'DELETED BY $user on $date -' || description  WHERE id = :id");
	
	$sth->bindValue(':id', $id);
	return $sth->execute();
}

function dupKPI($db, $id, $description){
date_default_timezone_set('America/Sao_Paulo');
$now = date("Y-m-d H:i", time());

    $dupdesc = '*Dup <b>'.$id.'</b> Desc - '.$description.'';
	$sth = $db->prepare("INSERT INTO kpis (users_email, bucket, agile_group, user_creator, creation_date, closure_date, time_spent, description, num_server, external_ticket, status, slas_id, customers_code, tools_id, categories_id)
      SELECT users_email, bucket, agile_group, user_creator, :now, NULL, NULL, :dupdesc, num_server, external_ticket, 1, slas_id, customers_code, tools_id, categories_id FROM kpis WHERE id = :id ");

	  $sth->bindValue(':now', $now);
      $sth->bindValue(':id', $id);
      $sth->bindValue(':dupdesc', $dupdesc);

      return $sth->execute();
    }

function AlterUser($db, $email, $group, $bucket){
	$sth = $db->prepare("UPDATE users SET groups= :group, bucket= :bucket WHERE email= :email");

	  $sth->bindValue(':group', $group);
      $sth->bindValue(':bucket', $bucket);
      $sth->bindValue(':email', $email);

      return $sth->execute();
}

function changeKPI($db, $id, $users_email, $bucket, $agile_group, $creation_date, $description, $num_server, $external_ticket, $status, $slaid, $customers_code, $tools_id, $categories_id){

	$now = date("Y-m-d H:i", time());
	$user = Whois();
	$dupdesc = '*Audit edition of KPI <b>'.$id.'</b> by ' .$user. ' on <b>' .$now. '</b> Desc - '.$description.'';
		$sth = $db->prepare("INSERT INTO kpis (users_email, bucket, agile_group, user_creator, creation_date, closure_date, time_spent, description, num_server, external_ticket, status, slas_id, customers_code, tools_id, categories_id)
		SELECT users_email, bucket, agile_group, user_creator, creation_date, closure_date, time_spent, :dupdesc, num_server, external_ticket, 3, slas_id, customers_code, tools_id, categories_id from kpis where id = :id");

		$sth->bindValue(':dupdesc', $dupdesc);
		$sth->bindValue(':id', $id);

		$sth->execute();

	$sth = $db->prepare("UPDATE kpis SET users_email = :users_email, bucket = :bucket,	agile_group = :agile_group, creation_date = :creation_date, description = :description, num_server = :num_server, external_ticket = :external_ticket, status = :status, slas_id = :slaid, customers_code = :customers_code, tools_id = :tools_id, categories_id = :categories_id WHERE id = :id");

	$sth->bindValue(':users_email', $users_email);
	$sth->bindValue(':bucket', $bucket);
	$sth->bindValue(':agile_group', $agile_group);
	$sth->bindValue(':creation_date', $creation_date);
	$sth->bindValue(':description', $description);
	$sth->bindValue(':num_server', $num_server);
	$sth->bindValue(':external_ticket', $external_ticket);
	$sth->bindValue(':status', $status);
	$sth->bindValue(':slaid', $slaid);
	$sth->bindValue(':customers_code', $customers_code);
	$sth->bindValue(':tools_id', $tools_id);
	$sth->bindValue(':categories_id', $categories_id);
	$sth->bindValue(':id', $id);

	return	$sth->execute();
}

function getTargetDate($db, $creation_date, $sla){
    $time = "+" .$sla. " minutes";
    $sth = $db->prepare("SELECT DATETIME('$creation_date', '$time')");

    $sth->execute();

    $result = $sth->fetch(PDO::FETCH_NUM);
       $date = $result[0];

    return $date;
}

function Replaces($string){
    $result = str_replace("|", "/", $string);
    $result = preg_replace("/\t/", " ", $result);

    return $result;
}

<?php
require_once("connect_db.php");

function getUser($db, $email) {
	#   $passMd5 = md5($password);
    	$sth = $db->prepare("select * from users where email= :email");
    	$sth->bindValue(':email', $email);
	$sth->execute();
  	$result = $sth->fetch(PDO::FETCH_ASSOC);

return $result;
}

function AlterUser($db, $email, $group, $bucket){
    $sth = $db->prepare("UPDATE users SET groups= :group, bucket= :bucket WHERE email= :email");

      $sth->bindValue(':group', $group);
      $sth->bindValue(':bucket', $bucket);
      $sth->bindValue(':email', $email);

      return $sth->execute();
}

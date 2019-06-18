<?php
try
  {

$db = new PDO('sqlite:/gdbr/kpionline/docs/kpionline.sqlite');

}
catch(PDOException $e)
  {
    print 'Exception : '.$e->getMessage();
  }

?>

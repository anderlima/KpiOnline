<?php
  try
  {
    //open the database
    $db = new PDO('sqlite:kpionline.sqlite');

    //insert some data...
$db->exec("INSERT INTO categories (name, description, visibility) VALUES ('Approval Requests', 'Approval Requests', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Baseline Actions', 'Baseline Actions', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('BlueCare', 'BlueCare', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('GIAMA', 'GIAMA', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('CIRATS', 'CIRATS', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Cluster Issues', 'Cluster Issues', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('CPU Issues', 'CPU Issues', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('DPP', 'DPP', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Dynamic Automation', 'Dynamic Automation', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('EMAIL', 'EMAIL', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Flood Tickets', 'Flood Tickets', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('General Projects', 'General Projects', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('ITCAM', 'ITCAM', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('KT', 'KT', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Memory Issues', 'Memory Issues', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Migration Agents', 'Migration Agents Action', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Monitoring Review', 'Monitoring Review', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('MQ Issues', 'MQ Issues', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Network Issues', 'Network Issues', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Offboard Actions', 'Offboard Actions', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Others', 'Others', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('PMR', 'PMR', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Process Issues', 'Process Issues', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Projeto Orion', 'Projeto Orion', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Recycle Actions', 'Recycle Actions', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Report Actions', 'Report Actions', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('SAD', 'SAD', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Server Build', 'Server Build', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Service Issues', 'Service Issues', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Test Actions', 'Test Actions', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Up/Down Monitoring', 'Up/Down Monitoring', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Upgrade Actions', 'Upgrade Actions', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('Wiki Actions', 'Wiki Actions', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('RTEM Actions/issues', 'RTEM Actions/issues', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('TEPS Actions/issues', 'TEPS Actions/issues', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('HUB Actions/issues', 'HUB Actions/issues', 0); " .
"INSERT INTO categories (name, description, visibility) VALUES ('/IBM/ITM High Space', 'FS opt/IBM/ITM High Space', 0);");

    //now output the data to a simple html table...
    print "<table border=1>";
    print "<tr><td>Id</td><td>Breed</td><td>Name</td><td>Age</td></tr>";
    $result = $db->query('SELECT * FROM categories where visibility=0');
    foreach($result as $row)
    {
      print "<tr><td>".$row['name']."</td>";
    }
    print "</table>";

    // close the database connection
    $db = NULL;
  }
  catch(PDOException $e)
  {
    print 'Exception : '.$e->getMessage();
  }
?>

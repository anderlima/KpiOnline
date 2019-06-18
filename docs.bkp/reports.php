<?php

require_once("user_logic.php");
require_once("get_date.php");

$lvl = $_SESSION["level"];

require_once("head$lvl.php");

$user = Whois();

if ($user == "alimao@br.ibm.com" || $user == "dcalves@br.ibm.com"){
    echo "<a href='altpage.php'>Alt user</a>";
}

?>
<h3 align="center"><u>General Ticket Report</u></h3></br>

                <form action="get_report.php" method="post">
                <div class="row dateRow">
                    <div class='col-md-4 col-md-offset-2 col-sm-4 col-sm-offset-2 col-xs-6'>
                        <div class="form-group">
                            <label for="depart">Start Date</label>
                            <div class='input-group date'>
				<span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                <input type='text' autocomplete="off" name="date_start" class="form-control datetimepicker" />
                            </div>
                        </div>
                    </div>
                    <div class='col-md-4 col-md-offset-0 col-sm-4 col-sm-offset-0 col-xs-6'>
                        <div class="form-group">
                            <label for="return">End Date</label>
                            <div class='input-group date' id='enddate'>
				<span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                <input type='text' autocomplete="off" name="date_end" class="form-control datetimepicker" />
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="text-center"> </br>
                    <button id="singlebutton" name="singlebutton" class="btn btn-success">Generate</button> 
                </div>
                </form> </br>
<script type="text/javascript">
    $(".datetimepicker").datetimepicker({
        });

</script>

<?php
if ($lvl != "one" && $lvl !="two"){
include("charts.php");
}
include("footer.php");
?>


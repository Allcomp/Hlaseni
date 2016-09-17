<style>
.item{
    position: absolute;
    width: 100px;
    height: 100px;
    background-color: blue;
    left:50%;
    top:50%;
    margin-left:-50px;
    margin-top:-50px;
}
</style>
          <?php
            require ("lab_pripojeni_db.php");
            session_start();
            Header("Cache-control: no-cache");
            Header("Pragma: no-cache");
            Header("Expires: 0");
            extract($_REQUEST);
                    $delay = $_POST["delay"];
                    $output = $_POST["output"];
                    $input = $_POST["input"];
                    $type = $_POST["type"];

            $db_name = $db_name_smarthouse; // jméno databáze
            MySQL_Select_DB($db_name) or die('Nepodarila se otevrít databáze.'); // výber databáze
            mysql_query("SET NAMES cp1250");
            $result = mysql_query("UPDATE `ewc_behaviour_signal` SET `input_ewc` = '$input', `output_ewc` = '$output', `delay` = '$delay', `type` = '$type' WHERE `id` = '$idcko'");
            //echo"$input / $output / $delay / $type <br>";        
            if(!$result) {
                 $chyba = mysql_error();
                 $chyba = substr($chyba,1,5);
                 if ($chyba=="uplic") {
                   $chyba = " Duplicate error!";
                   $ico = "orange";
                 } 
            } else {
                   $chyba = " Send... Please wait... ";
                   $ico = "green";
            }
          ?>        
          <div class="row-fluid">
          <div class="span4 responsive" data-tablet="span4 fix-margin" data-desktop="span4">
          </div>
                <div class="span4 responsive" data-tablet="span4 fix-margin" data-desktop="span4">
                    <!-- BEGIN ALERTS PORTLET-->
                    <div class="widget <?php echo"$ico";?>">
                        <div class="widget-title">
                            <h4><i class="icon-envelope"></i><?php echo"$chyba";?></h4>
                        </div>
                        <div style="margin: 25px" class="progress progress-success progress-striped active">
                          <div style="width: 100%;" class="bar"></div>
                        </div>
                    </div>
                    <!-- END ALERTS PORTLET-->
                </div>
            </div>
            <meta http-equiv="Refresh" content="2; URL=index.php?menu=<?php echo"$menu&process=0&command=0"?>">
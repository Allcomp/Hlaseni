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
            session_start();
            Header("Cache-control: no-cache");
            Header("Pragma: no-cache");
            Header("Expires: 0");
            extract($_REQUEST);/*
                    $jmeno = $_POST["jmeno"];
                    $kateg = $_POST["kateg"];
            if ($command==1) {//*************************************** new card
                    $karta = $_POST["karta"];
                    $karta = str_replace(' ', '', $karta);
                        //$result = mysql_query("UPDATE `datum` SET `date` = '$post_datum' WHERE `id` = '$id'");
                        //echo"$karta / $jmeno / $kateg";
                    $result = mysql_query("INSERT INTO karty(id, nazev, kategorie) VALUES('$karta','$jmeno','$kateg')");
                        //query("INSERT INTO uzivatele(jmeno, email, zprava) VALUES('$_POST[jmeno]','$_POST[mail]','$_POST[zprava]')");
            } elseif ($command==2) {//******************************** edit card
                    //$karta = $_POST["karta"];
                    //$karta = str_replace(' ', '', $karta);
                    $result = mysql_query("UPDATE `karty` SET `nazev` = '$jmeno', `kategorie` = '$kateg' WHERE `id` = '$karta'");
                    //echo"$karta / $jmeno / $kateg";
            } elseif ($command==3) {//***************************** smazat kartu
                    //$karta = $_POST["karta"];
                    //$karta = str_replace(' ', '', $karta);
                    //$result = mysql_query("UPDATE `karty` SET `nazev` = '$jmeno', `kategorie` = '$kateg' WHERE `id` = '$karta'");
                    //echo"$karta / $jmeno / $kateg";
            }*/
          ?>        
          <div class="row-fluid">
          <div class="span4 responsive" data-tablet="span4 fix-margin" data-desktop="span4">
          </div>
                <div class="span4 responsive" data-tablet="span4 fix-margin" data-desktop="span4">
                    <!-- BEGIN ALERTS PORTLET-->
                    <div class="widget orange">
                        <!--<div class="widget-title">
                        </div>
                        <div style="margin: 25px" class="progress progress-success progress-striped active">
                          <div style="width: 100%;" class="bar"></div>
                        </div>-->
                        <div>
                        <br>
                        </div>
                        
                        <div style="text-align:center">
                        <h2>Really want to delete the record? </h2>
                        <h3><?php echo" <b> $input </b> <br> in room <b> $room </b> <br> out <b> $nameout ($output)</b> ";?></h3>
                        </div>
                        <div>
                        <br>
                        </div>
                        <div style="text-align:center">
                          <p>
                            <a href="<?php echo"?menu=$menu&process=5&command=1&idcko=$idcko";?>"><button class="btn btn-large btn-danger"><i class="icon-remove icon-white"></i> ANO</button></a>
                            <a href="<?php echo"?menu=$menu&process=0&command=0";?>"><button class="btn btn-large btn-info"><i class="icon-ban-circle icon-white"></i> NE</button></a>
                          </p>
                        </div>
                        <div>
                        <br>
                        </div>
                    </div>
                    <!-- END ALERTS PORTLET-->
                </div>
            </div>
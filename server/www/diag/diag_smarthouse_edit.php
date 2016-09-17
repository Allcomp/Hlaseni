          <?php
            session_start();
            Header("Cache-control: no-cache");
            Header("Pragma: no-cache");
            Header("Expires: 0");
            extract($_REQUEST);
            //$command=$_GET["command"];
            require ("lab_pripojeni_db.php");
          ?>
          <h3 class="page-title">Edit</h3>
          <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN widget-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i> 
                            </h4>
                            <span class="tools">
                               <a class="icon-chevron-down" href="javascript:;"></a>
                               <a class="icon-remove" href="javascript:;"></a>
                            </span>
                        </div>
                        <div class="widget-body form">
                            <!-- BEGIN FORM-->
                            <form action="?menu=<?php echo"$menu&process=$process&command=1&idcko=$idcko";?>" method="post" class="form-horizontal">
                                
                                <div class="control-group">
                                    <label class="control-label">Input</label>
                                    <div class="controls">
                                    <?php
                                            $poleio=explode("_",$inname);
                                            $inoroutio=substr($poleio[1],0,3);
                                            if ($inoroutio!="OUT") { //nenalezeno ,
                                    ?>
                                        <select class="input-medium m-wrap" tabindex="1" id="input" name="input">
                                            <?php
                                                  $db_name = $db_name_smarthouse; // jméno databáze
                                                  MySQL_Select_DB($db_name) or die('Nepodarila se otevrít databáze.'); // výber databáze
                                                  mysql_query("SET NAMES cp1250");
                                                  //nastaveni
                                                  $resulter = mysql_query("SELECT * FROM ewc_properties order by id ASC");   //kalibrace
                                                            while ($rower 	= mysql_fetch_array($resulter)){
                                                            $id_ewc_propi    = $rower[id];
                                                            $hw_addressi     = $rower[hardware_address];
                                                            
                                                            //zobrazeni pouze in
                                                            $polei=explode("_",$hw_addressi);
                                                            $inorouti=substr($polei[1],0,2);
                                                            if ($inorouti=="IN"){
                                                                //repin
                                                                $innumi = substr($polei[1],2,2);
                                                                 switch ($innumi) {
                                                                   case 16:
                                                                         $inname = "pin3";
                                                                         break;
                                                                   case 17:
                                                                         $inname = "pin4";
                                                                         break;
                                                                   case 18:
                                                                         $inname = "pin5";
                                                                         break;
                                                                   case 19:
                                                                         $inname = "pin6";
                                                                         break;
                                                                   case 20:
                                                                         $inname = "pin7";
                                                                         break;
                                                                   case 21:
                                                                         $inname = "pin8";
                                                                         break;
                                                                   case 22:
                                                                         $inname = "pin10";
                                                                         break;
                                                                   case 23:
                                                                         $inname = "pin9";
                                                                         break;
                                                                   case 24:
                                                                         $inname = "pin1";
                                                                         break;
                                                                   case 25:
                                                                         $inname = "pin2";
                                                                         break;
                                                                }
                                                                //repin end
                                                            
                                                              if ($in==$id_ewc_propi){
                                                                 echo"<option value=\"$id_ewc_propi\" selected>* $hw_addressi ($inname)</option>";
                                                              } else{
                                                                 echo"<option value=\"$id_ewc_propi\">$hw_addressi ($inname)</option>";
                                                              }
                                                            }
                                                        }
                                            ?>
                                            
                                        </select>
                                        <?php
                                            } else {     //nalezeno in jako out
                                                 echo"Input can not be changed...";
                                            }
                                        ?>
                                    </div>
                                </div>


                                <div class="control-group">
                                    <label class="control-label">Output</label>
                                    <div class="controls">
                                    <?php
                                            //$pos = strpos($out, ",");
                                            //if ($pos === false) { //nenalezeno ,
                                    ?>
                                    <?php
                                        $db_name = $db_name_smart_control; // jméno databáze
                                        MySQL_Select_DB($db_name) or die('Nepodarila se otevrít databáze.'); // výber databáze
                                        mysql_query("SET NAMES cp1250");
      
                                        $resultn = mysql_query("SELECT * FROM rooms order by id ASC");   //rooms
                                                  while ($rown 	= mysql_fetch_array($resultn)){
                                                  $id               = $rown[id];
                                                  $rooms_name[$id]   = $rown[name];
                                              }
                                              
                                        $resultc = mysql_query("SELECT * FROM controls ORDER BY id ASC");
                                                  while ($rowc 	= mysql_fetch_array($resultc)){
                                                         $outc           = $rowc[outputs];
                                                         //$id_controlsc   = $rowc[id];
                                                         $roomid         = $rowc[room];
                                                         $roomc[$outc]   = $rowc[room];
                                                         $roomname[$outc]= $rooms_name[$roomid];
                                                         
                                                         $namec[$outc]   = $rowc[name];
                                                  }                                          
                                    ?>
                                        <select class="input-large m-wrap" tabindex="1" id="output" name="output">
                                            <?php
                                                  $db_name = $db_name_smarthouse; // jméno databáze
                                                  MySQL_Select_DB($db_name) or die('Nepodarila se otevrít databáze.'); // výber databáze
                                                  mysql_query("SET NAMES cp1250");
                                                  //nastaveni
                                                  $resultep = mysql_query("SELECT * FROM ewc_properties order by id ASC");   //kalibrace
                                                            while ($rowep 	= mysql_fetch_array($resultep)){
                                                            $id_ewc_prop    = $rowep[id];
                                                            $hw_address     = $rowep[hardware_address];
                                                            
                                                            //zobrazeni pouze OUT
                                                            $pole=explode("_",$hw_address);
                                                            $inorout=substr($pole[1],0,3);
                                                            if ($inorout=="OUT"){
                                                            
                                                                    $outnum = substr($pole[1],3,2);
                                                                     switch ($outnum) {
                                                                       case 0:
                                                                             $outname = "pin1";
                                                                             break;
                                                                       case 1:
                                                                             $outname = "pin3";
                                                                             break;
                                                                       case 2:
                                                                             $outname = "pin5";
                                                                             break;
                                                                       case 3:
                                                                             $outname = "pin7";
                                                                             break;
                                                                       case 4:
                                                                             $outname = "pin9";
                                                                             break;
                                                                       case 5:
                                                                             $outname = "pin2";
                                                                             break;
                                                                       case 6:
                                                                             $outname = "pin4";
                                                                             break;
                                                                       case 7:
                                                                             $outname = "pin6";
                                                                             break;
                                                                       case 8:
                                                                             $outname = "pin8";
                                                                             break;
                                                                       case 9:
                                                                             $outname = "pin10";
                                                                             break;
                                                                    }
                                                            
                                                            
                                                              if ($out==$id_ewc_prop){
                                                                 echo"<option value=\"$id_ewc_prop\" selected>* $hw_address ($outname) &#160; $roomname[$id_ewc_prop] &#160; $namec[$id_ewc_prop]</option>";
                                                              }else {
                                                                 echo"<option value=\"$id_ewc_prop\">$hw_address ($outname) &#160; $roomname[$id_ewc_prop] &#160; $namec[$id_ewc_prop]</option>";
                                                              }
                                                            }
                                                        }
                                            ?>
                                            
                                        </select>
                                        <?php
                                            //} else {     //nalezeno vice vystupu
                                            //     echo"Výstup nelze mìnit.";
                                            //}
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="control-group">
                                    <label class="control-label">Delay</label>
                                    <div class="controls">
                                        <input type="text" id="delay" name="delay" value="<?php echo"$delay";?>"  class="span3">
                                        <span class="help-inline">ms (1000 milliseconds = 1 second)</span>
                                    </div>
                                </div>
                                
                                <div class="control-group">
                                    <label class="control-label">Type</label>
                                    
                                    <div class="controls">
                                        <select class="input-medium m-wrap" tabindex="1" id="type" name="type">
                                            <?php 
                                            if ($type==0){
                                               echo"<option value=\"0\" selected>* Button</option>";
                                            }else{
                                               echo"<option value=\"0\">Button</option>";
                                            }
                                            if ($type==1){
                                               echo"<option value=\"1\" selected>* Switch</option>";
                                            }else{
                                               echo"<option value=\"1\">Switch</option>";
                                            }
                                            if ($type==2){
                                               echo"<option value=\"2\" selected>* Timed</option>";
                                            }else{
                                               echo"<option value=\"2\">Timed</option>";
                                            }
                                            if ($type==3){
                                               echo"<option value=\"3\" selected>* PIR Sensor</option>";
                                            }else{
                                               echo"<option value=\"3\">PIR Sensor</option>";
                                            }
                                            if ($type==4){
                                               echo"<option value=\"4\" selected>* Negation</option>";
                                            }else{
                                               echo"<option value=\"4\">Negation</option>";
                                            }
                                            if ($type==5){
                                               echo"<option value=\"5\" selected>* ON Only Negation</option>";
                                            }else{
                                               echo"<option value=\"5\">ON Only Negation</option>";
                                            }
                                            if ($type==6){
                                               echo"<option value=\"6\" selected>* Regulator</option>";
                                            }else{
                                               echo"<option value=\"6\">Regulator</option>";
                                            }
                                            if ($type==7){
                                               echo"<option value=\"7\" selected>* Thermostat</option>";
                                            }else{
                                               echo"<option value=\"7\">Thermostat</option>";
                                            }
                                            if ($type==8){
                                               echo"<option value=\"8\" selected>* 8</option>";
                                            }else{
                                               echo"<option value=\"8\">8</option>";
                                            }
                                            if ($type==9){
                                               echo"<option value=\"9\" selected>* 9</option>";
                                            }else{                  
                                               echo"<option value=\"9\">9</option>";
                                            }
                                            if ($type==10){
                                               echo"<option value=\"10\" selected>* 10</option>";
                                            }else{
                                               echo"<option value=\"10\">10</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                </div>
                                <button type="submit" class="btn">
                                  Uložit
                                  <i class="icon-long-arrow-right"></i>
                              </button>
                            </form>
                            <!-- END FORM-->
                        </div>
                    </div>
                    <!-- END widget-->
                </div>
            </div>
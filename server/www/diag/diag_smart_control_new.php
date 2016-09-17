          <?php
            session_start();
            Header("Cache-control: no-cache");
            Header("Pragma: no-cache");
            Header("Expires: 0");
            extract($_REQUEST);
            //$command=$_GET["command"];
            require ("lab_pripojeni_db.php");
          ?>
          <h3 class="page-title">New</h3>
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
                            <form action="<?php echo"?menu=$menu&process=$process&command=1";?>" method="post" class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label">Name</label>
                                    <div class="controls">
                                        <input type="text" id="name" name="name" value=""  class="span5">
                                    </div>
                                </div>
                                
                                <div class="control-group">
                                    <label class="control-label">Room</label>
                                    <div class="controls">
                                        <select class="input-medium m-wrap" tabindex="1" id="room" name="room">
                                            <?php
                                              $db_name = $db_name_smart_control; // jméno databáze
                                              MySQL_Select_DB($db_name) or die('Nepodarila se otevrít databáze.'); // výber databáze
                                              mysql_query("SET NAMES cp1250");
                                              $resultn = mysql_query("SELECT * FROM rooms order by name ASC");   //rooms
                                                        while ($rown 	= mysql_fetch_array($resultn)){
                                                        $idr          = $rown[id];
                                                        $rooms_name   = $rown[name];
                                                        
                                                          echo"<option value=\"$idr\">$rooms_name</option>";
                                                        
                                                    }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Output</label>
                                    <div class="controls">
                                    
                                        <select class="input-medium m-wrap" tabindex="1" id="output" name="output">
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
                                                              
                                                              echo"<option value=\"$id_ewc_prop\">$hw_address ($outname)</option>";
                                                            }
                                                            
                                                        }
                                            ?>
                                            
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Type</label>
                                    
                                    <div class="controls">
                                        <select class="input-medium m-wrap" tabindex="1" id="type" name="type">
                                            <?php
                                               echo"<option value=\"0\">Output</option>";
                                               echo"<option value=\"1\">Thermostat</option>";
                                               echo"<option value=\"2\">Dimmer</option>";
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
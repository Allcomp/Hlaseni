<?php
      require ("lab_pripojeni_db.php");
      $db_name = $db_name_smarthouse; // jméno databáze
      MySQL_Select_DB($db_name) or die('Nepodarila se otevrít databáze.'); // výber databáze
      mysql_query("SET NAMES cp1250");
      //nastaveni
      $resultep = mysql_query("SELECT * FROM ewc_properties order by id ASC");   //kalibrace
                while ($rowep 	= mysql_fetch_array($resultep)){
                $id_ewc_prop                = $rowep[id];
                $hw_address[$id_ewc_prop]   = $rowep[hardware_address];
            }
?>
        <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
                <!-- BEGIN EXAMPLE TABLE widget-->
                <div class="widget blue">
                    <div class="widget-title">
                        <h4><i class="icon-reorder"></i></h4>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="sample_1">
                            <thead>
                            <tr>
                                <th style="width:8px;"></th>
                                <th>room</th>
                                <th>name</th>
				                        <th>out</th>
                                <th>type</th>
                                <th>command</th>
                            </tr>
                            </thead>
                            <tbody>
                              <?php
                                  $db_name = $db_name_smart_control; // jméno databáze
                                  MySQL_Select_DB($db_name) or die('Nepodarila se otevrít databáze.'); // výber databáze
                                  mysql_query("SET NAMES cp1250");

                                  $resultn = mysql_query("SELECT * FROM rooms order by id ASC");   //rooms
                                            while ($rown 	= mysql_fetch_array($resultn)){
                                            $id               = $rown[id];
                                            $rooms_name[$id]   = $rown[name];
                                        }
                                        
                                  $result = mysql_query("SELECT * FROM controls ORDER BY id ASC");
                                            while ($row 	= mysql_fetch_array($result)){
                                                   $id_controls   = $row[id]; 
                                                   $room          = $row[room];
                                                   $name          = $row[name];
                                                   $out           = $row[outputs];
                                                   $type          = $row[type];
                                                   
                                                        $adrpole = $hw_address[$out];
                                                        $pole=explode("_",$adrpole);
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
                                                        }                                          
                              ?>
                                      <tr class="odd gradeX">       
                                          <td><?php echo"$id_controls";?></td>
                                          <td><?php echo"".$rooms_name[$room]."";?></td>
					                                <td><?php echo"$name";?></td>
                                          <?php
                                            $pos = strpos($out, ",");
                                            if ($pos === false) { //nenalezeno ,
                                                echo "<td>$hw_address[$out] ($outname)</td>";
                                            } else {     //nalezeno vice vystupu
                                                $pole=explode(",",$out);
                                                $out1 = $pole[0];
                                                $out2 = $pole[1];
                                                echo "<td>$hw_address[$out1] / $hw_address[$out2]</td>";
                                            }
                                          ?>
                                          <td><?php echo"$type";?></td>
                                          <td>
                                              <a href="<?php echo"?menu=$menu&process=1&idcko=$id_controls&name=$name&room=$room&out=$out&type=$type";?>"><button class="btn btn-primary"><i class="icon-pencil"></i></button></a>
                                              <a href="<?php echo"?menu=$menu&process=2&idcko=$id_controls&name=$name&room=$rooms_name[$room]&out=$out";?>"><button class="btn btn-danger"><i class="icon-trash "></i></button></a>
                                          </td>
                                      </tr>
                                <?php                                  
                                  }
                                ?>
                            </tbody>
                        </table>
                        <br>
                        <a href="<?php echo"?menu=$menu&process=3";?>"><button class="btn btn-primary"><i class="icon-new"> New </i></button></a>
                      </div>
                </div>
                <!-- END EXAMPLE TABLE widget-->
                </div>
                
                
               
            
            <!-- END PAGE CONTENT--> 
            
            
            
   <!-- BEGIN JAVASCRIPTS -->
   <!-- Load javascripts at bottom, this will reduce page load time -->
   
   <script src="js/jquery-1.8.3.min.js"></script>
   
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="js/excanvas.js"></script>
   <script src="js/respond.js"></script>
   <![endif]-->
   <script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
   <script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>
   <script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
   

   <!--common script for all pages-->

   <!--script for this page only-->
   <script src="js/dynamic-table.js"></script>
   <!--script for this page only-->
   <script src="js/editable-table.js"></script>
   <script>
       jQuery(document).ready(function() {
           EditableTable.init();
       });
   </script>
   <!-- END JAVASCRIPTS -->   
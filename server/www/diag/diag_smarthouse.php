<?php
      require ("lab_pripojeni_db.php");
      $db_name = $db_name_smart_control; // jméno databáze
      MySQL_Select_DB($db_name) or die('Nepodarila se otevrít databáze.'); // výber databáze
      mysql_query("SET NAMES cp1250");
      //nastaveni
      $resulte = mysql_query("SELECT * FROM controls order by id ASC");   //kalibrace
                while ($rowe 	= mysql_fetch_array($resulte)){                
                $outputs                = $rowe[outputs];
                $room[$outputs]         = $rowe[room];
                $name[$outputs]         = $rowe[name];
                $type[$outputs]         = $rowe[type];
                //echo"$outputs = $room[$outputs] % "; 
                        $pos = strpos($outputs, ",");
                          if ($pos === false) { //nenalezeno ,
                              //echo "NEnalezeno $outputs ";

                          } else {     //nalezeno vice vystupu
                              $pole=explode(",",$outputs);
                              $out1 = $pole[0];
                              $out2 = $pole[1]+500;
                              //echo "nalezeno $out1 a $out2 ";
                              $room[$out1]         = $rowe[room];
                              $name[$out1]         = $rowe[name];
                              $type[$out1]         = $rowe[type];
                              
                              $room[$out2]         = $rowe[room];
                              $name[$out2]         = $rowe[name];
                              $type[$out2]         = $rowe[type];
                          }               
            }
      $resulter = mysql_query("SELECT * FROM rooms order by id ASC");   //kalibrace
                while ($rower 	= mysql_fetch_array($resulter)){                
                $room_id                = $rower[id];
                $room_name[$room_id]    = $rower[name];               
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
                                <th>input</th>
                                <th>output</th>
                                <th>rooms</th>
                                <th>name</th>
				                        <th>delay</th>
                                <th>type</th>
                                <th>command</th>
                            </tr>
                            </thead>
                            <tbody>
                              <?php
                                  $db_name = $db_name_smarthouse; // jméno databáze
                                  MySQL_Select_DB($db_name) or die('Nepodarila se otevrít databáze.'); // výber databáze
                                  mysql_query("SET NAMES cp1250");

                                  $resultep = mysql_query("SELECT * FROM ewc_properties order by id ASC");   //kalibrace
                                      while ($rowep 	= mysql_fetch_array($resultep)){
                                      $id_ewc_prop                = $rowep[id];
                                      $hw_address[$id_ewc_prop]   = $rowep[hardware_address];
                                  }
                                        
                                  $result = mysql_query("SELECT * FROM ewc_behaviour_signal ORDER BY id ASC");
                                            while ($row 	= mysql_fetch_array($result)){
                                                   $id_signal     = $row[id]; 
                                                   $input         = $row[input_ewc];
                                                   $output        = $row[output_ewc];
                                                   $delay         = $row[delay];
                                                   $type          = $row[type];
                                                   
                                                   $room_output_id = $room[$output];
                                                   if ($room_output_id == "") {
                                                       $room_output_id = $room[$output+500];
                                                   }
                                                   if ($name[$output] == "") {
                                                       $name_output = $name[$output+500];
                                                   } else {
                                                       $name_output = $name[$output];
                                                   }
                                                   
                                                   //OUTPUT PIN
                                                   $adrpole = $hw_address[$output];
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
                                                        //OUTPUT PIN END
                                                        
                                                        //INPUT PIN
                                                        $adrpolei = $hw_address[$input];
                                                        $polei=explode("_",$adrpolei);
                                                        $inorouti=substr($polei[1],0,2);
                                                        if ($inorouti=="IN"){
                                                           $outnumi = substr($polei[1],2,2);
                                                             switch ($outnumi) {
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
                                                        }
                                                        //INPUT PIN END
                                                                          
                              ?>
                                      <tr class="odd gradeX">       
                                          <td><?php echo"$id_signal";?></td>
                                          <td><?php echo"$hw_address[$input] ($inname)";?></td>
					                                <td><?php echo"$hw_address[$output] ($outname)";?></td>
                                          <td><?php echo"$room_name[$room_output_id]";?></td>
                                          <td><?php echo"$name_output";?></td>
                                          <td><?php echo"$delay";?></td>
                                          <td><?php echo"$type";?></td>
                                          <td>
                                              <a href="<?php echo"?menu=$menu&process=4&idcko=$id_signal&in=$input&inname=$hw_address[$input]&out=$output&type=$type&delay=$delay";?>"><button class="btn btn-primary"><i class="icon-pencil"></i></button></a>
                                              <a href="<?php echo"?menu=$menu&process=5&idcko=$id_signal&input=$hw_address[$input]&output=$hw_address[$output]&room=$room_name[$room_output_id]&nameout=$name_output";?>"><button class="btn btn-danger"><i class="icon-trash "></i></button></a>
                                          </td>
                                      </tr>
                                <?php                                  
                                  }
                                ?>
                            </tbody>
                        </table>
                        <br>
                        <a href="<?php echo"?menu=$menu&process=6";?>"><button class="btn btn-primary"><i class="icon-new"> New </i></button></a>
                      </div>
                </div>
                <!-- END EXAMPLE TABLE widget-->
                </div>
            </div>

            <!-- END ADVANCED TABLE widget-->
            
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


   <!--common script for all pages-->

   <!--script for this page only-->
   <script src="js/dynamic-table.js"></script>

   <!-- END JAVASCRIPTS -->   
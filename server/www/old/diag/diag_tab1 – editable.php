<?php
      require ("lab_pripojeni_db.php");
      $db_name = "smarthouse"; // jméno databáze
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
                                  $db_name = "smart_control"; // jméno databáze
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
                              ?>
                                      <tr class="odd gradeX">       
                                          <td><?php echo"$id_controls";?></td>
                                          <td><?php echo"".$room." - ".$rooms_name[$room]."";?></td>
					                                <td><?php echo"$name";?></td>
                                          <?php
                                            $pos = strpos($out, ",");
                                            if ($pos === false) { //nenalezeno ,
                                                echo "<td>$out - $hw_address[$out]</td>";
                                            } else {     //nalezeno vice vystupu
                                                $pole=explode(",",$out);
                                                $out1 = $pole[0];
                                                $out2 = $pole[1];
                                                echo "<td>$out1 - $hw_address[$out1] / $out2 - $hw_address[$out2]</td>";
                                            }
                                          ?>
                                          <td><?php echo"$type";?></td>
                                          <td><?php echo"$log_time";?></td>
                                      </tr>
                                <?php                                  
                                  }
                                ?>
                            </tbody>
                        </table>
                      </div>
                </div>
                <!-- END EXAMPLE TABLE widget-->
                </div>
                
                
                <!-- BEGIN EDITABLE TABLE widget-->
             <div class="row-fluid">
                 <div class="span12">
                     <!-- BEGIN EXAMPLE TABLE widget-->
                     <div class="widget purple">
                         <div class="widget-title">
                             <h4><i class="icon-reorder"></i> Editable Table</h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                         </div>
                         <div class="widget-body">
                             <div>
                                 <div class="clearfix">
                                     <div class="btn-group">
                                         <button id="editable-sample_new" class="btn green">
                                             Add New <i class="icon-plus"></i>
                                         </button>
                                     </div>
                                     <div class="btn-group pull-right">
                                         <button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="icon-angle-down"></i>
                                         </button>
                                         <ul class="dropdown-menu pull-right">
                                             <li><a href="#">Print</a></li>
                                             <li><a href="#">Save as PDF</a></li>
                                             <li><a href="#">Export to Excel</a></li>
                                         </ul>
                                     </div>
                                 </div>
                                 <div class="space15"></div>
                                 <table class="table table-striped table-hover table-bordered" id="editable-sample">
                                     <thead>
                                     <tr>
                                         <th>Username</th>
                                         <th>Full Name</th>
                                         <th>Points</th>
                                         <th>Notes</th>
                                         <th>Edit</th>
                                         <th>Delete</th>
                                     </tr>
                                     </thead>
                                     <tbody>
                                     <tr class="">
                                         <td>Jondi Rose</td>
                                         <td>Alfred Jondi Rose</td>
                                         <td>1234</td>
                                         <td class="center">super user</td>
                                         <td><a class="edit" href="javascript:;">Edit</a></td>
                                         <td><a class="delete" href="javascript:;">Delete</a></td>
                                     </tr>
                                     <tr class="">
                                         <td>Dulal</td>
                                         <td>Jonathan Smith</td>
                                         <td>434</td>
                                         <td class="center">new user</td>
                                         <td><a class="edit" href="javascript:;">Edit</a></td>
                                         <td><a class="delete" href="javascript:;">Delete</a></td>
                                     </tr>
                                     <tr class="">
                                         <td>Sumon</td>
                                         <td> Sumon Ahmed</td>
                                         <td>232</td>
                                         <td class="center">super user</td>
                                         <td><a class="edit" href="javascript:;">Edit</a></td>
                                         <td><a class="delete" href="javascript:;">Delete</a></td>
                                     </tr>
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>
                     <!-- END EXAMPLE TABLE widget-->
                 </div>
             </div>

            <!-- END EDITABLE TABLE widget-->
                
            
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
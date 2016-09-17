<?php
      require ("lab_pripojeni_db.php");
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
                                <th>name</th>
                                <th>floor</th>
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
                                            $rooms_name       = $rown[name];
                                            $floor            = $rown[floor];         
                              ?>
                                      <tr class="odd gradeX">       
                                          <td><?php echo"$id";?></td>
                                          <td><?php echo"$rooms_name";?></td>
                                          <td><?php echo"$floor";?></td>
                                          <td>
                                              <a href="<?php echo"?menu=$menu&process=7&idcko=$id&name=$rooms_name&floor=$floor";?>"><button class="btn btn-primary"><i class="icon-pencil"></i></button></a>
                                              <a href="<?php echo"?menu=$menu&process=8&idcko=$id&name=$rooms_name&floor=$floor";?>"><button class="btn btn-danger"><i class="icon-trash "></i></button></a>
                                          </td>
                                      </tr>
                                <?php                                  
                                  }
                                ?>
                            </tbody>
                        </table>
                        <br>
                        <a href="<?php echo"?menu=$menu&process=9";?>"><button class="btn btn-primary"><i class="icon-new"> New </i></button></a>
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
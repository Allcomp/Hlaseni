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
                                    <label class="control-label">Name</label>
                                    <div class="controls">
                                        <input type="text" id="name" name="name" value=""  class="span5">
                                    </div>
                                </div>
                                
                                <div class="control-group">
                                    <label class="control-label">Floor</label>
                                    <div class="controls">
                                        <select class="input-medium m-wrap" tabindex="1" id="floor" name="floor">
                                            <?php 
                                            if ($floor==0){
                                               echo"<option value=\"0\" selected>* 0</option>";
                                            }else{
                                               echo"<option value=\"0\">0</option>";
                                            }
                                            if ($floor==1){
                                               echo"<option value=\"1\" selected>* 1</option>";
                                            }else{
                                               echo"<option value=\"1\">1</option>";
                                            }
                                            if ($floor==2){
                                               echo"<option value=\"2\" selected>* 2</option>";
                                            }else{
                                               echo"<option value=\"2\">2</option>";
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
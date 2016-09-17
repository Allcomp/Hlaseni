<?php
$datumjs = $_POST['Text2'];
require ("lab_cestina.php");
//require ("lab_promenne.php");
//require ("lab_aktualni_informace.php");
$prihlasen  = $_SESSION["prihlasen"];
$uzivatel   = $_SESSION["uzivatel"];
$process = $process;
$menu = $menu; 
if ($prihlasen==1) {
   //uzivatel prihlasen tudiz zobraz vse...
?>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="cs"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
   <meta charset="utf-8" />
   <title>Editable Table</title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport" />
   <meta content="" name="description" />
   <meta content="" name="author" />
   <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
   <link href="assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
   <link href="assets/bootstrap/css/bootstrap-fileupload.css" rel="stylesheet" />
   <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
   <link href="css/style.css" rel="stylesheet" />
   <link href="css/style-responsive.css" rel="stylesheet" />
   <link href="css/style-default.css" rel="stylesheet" id="style_color" />
   <link rel="stylesheet" type="text/css" href="assets/uniform/css/uniform.default.css" />
   <link rel="stylesheet" href="assets/data-tables/DT_bootstrap.css" />

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
   <!-- BEGIN HEADER -->
   <div id="header" class="navbar navbar-inverse navbar-fixed-top">
       <!-- BEGIN TOP NAVIGATION BAR -->              
               <!-- BEGIN RESPONSIVE MENU TOGGLER -->
               <a class="btn btn-navbar collapsed" id="main_menu_trigger" data-toggle="collapse" data-target=".nav-collapse">
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
                   <span class="arrow"></span>
               </a>
               <!-- END RESPONSIVE MENU TOGGLER -->
       <!-- END TOP NAVIGATION BAR -->
   </div>
   <!-- END HEADER -->
   <!-- BEGIN CONTAINER -->
   <div id="container" class="row-fluid">
      <!-- BEGIN SIDEBAR -->
      <div class="sidebar-scroll">
          <div id="sidebar" class="nav-collapse collapse">
          
         <!-- BEGIN SIDEBAR MENU -->
          <ul class="sidebar-menu">
              <!--<li class="sub-menu 
              <?php /*
              if($menu==0){echo"active";}*/
              ?>
              <!--">
                  <a class="" href="index.php">
                      <i class="icon-dashboard"></i>
                      <span>Menu</span>
                  </a>
              </li>  -->
              <li class="sub-menu <?php 
              if($menu==1){echo"active";}?>">
                  <a class="active" href="index.php?menu=1">
                      <i class="icon-user"></i>
                      <span>SC / controls</span>
                  </a>
              </li>
              <li class="sub-menu <?php 
              if($menu==4){echo"active";}?>">
                  <a class="" href="index.php?menu=4">
                      <i class="icon-cog"></i>
                      <span>Sh / signals</span>
                  </a>
              </li>
              <li>
                  <a class="" href="lab_login.php">
                    <i class="icon-user"></i>
                    <span>Odhlásit</span>
                  </a>
              </li>
          </ul>
         <!-- END SIDEBAR MENU -->
      </div>
      </div>
      <!-- END SIDEBAR -->
      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">

                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                   <h3 class="page-title">
                   <?php
                   if ($process>0) {
                      echo"";
                   } else {
                     if ($menu==1) {
                        echo"smart_control / controls";
                    } elseif ($menu==4) {
                        echo"smarthouse / signals";
                    } else{
                        echo"smart_control / controls";
                    }
                   }
                    ?>
                   </h3>
                   <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <?php
            if ($process==1 OR $process==2) {     //editace a nove
                if ($command>0){   //provede se akce
                  require"lab_text.php";     
                } else {
                  require"lab_editu.php";
                }
            } else if ($process==3){          //mazani
                if ($command>0){   //provede se akce
                  require"lab_text.php";     
                } else {
                  require"lab_delete.php";
                }
            } else if ($process==4){          //Calen Sie
                if ($command>0){   //provede se akce
                  require"lab_text.php";     
                } else {
                  require"lab_calen.php";
                }
            } else if ($process==5){          //kalibrace
                /*if ($command>0){   //provede se akce
                  require"lab_text.php";     
                } else {
                  require"lab_calen.php";
                }*/
                require"lab_text.php";
            } else {
                  if ($menu==1) {
                      require"diag_tab1.php";
                  } elseif ($menu==4) {
                      require"diag_tab_signals.php";
                  } elseif ($menu==3) {
                      require"cont_stat.php";
                  } else{
                      require"diag_tab1.php";
                  }
            }
              
            ?>        
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->  
   </div>
   <!-- END CONTAINER -->

   <!-- BEGIN FOOTER 
   <div id="footer">
       2015 &copy; VV.
   </div> -->
   <!-- END FOOTER -->

   <!-- BEGIN JAVASCRIPTS -->
 <!-- Load javascripts at bottom, this will reduce page load time -->
   <script src="js/jquery-1.8.3.min.js"></script>
   <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
   <script src="assets/bootstrap/js/bootstrap.min.js"></script>
   <script src="js/jquery.blockui.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="js/excanvas.js"></script>
   <script src="js/respond.js"></script>
   <![endif]-->
   <script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
   <script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>


   <!--common script for all pages-->
   <script src="js/common-scripts.js"></script>

   <!--script for this page only-->
   <script src="js/editable-table.js"></script>


</body>
<!-- END BODY -->

<?php
//pokud nejni nikdo prihlasen, tak presmeruj na login page...
} else {
   echo"<meta http-equiv=\"refresh\" content=\"0;url=lab_login.php\">";
}
?>
</html>
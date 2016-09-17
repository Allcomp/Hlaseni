<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
session_start();
Header("Cache-control: no-cache");
Header("Pragma: no-cache");
Header("Expires: 0");
extract($_REQUEST);
require("db_connect.php");
require("galerie_query.php");

$ahojte   = 0;
if ($menuH==0) {
  $menuH    = 1;
  $menu     = 9999999999;
  $ahojte   = 1;
}
if ($menuH==1 AND $menu==0) {
  $menu =9999991;
}
if ($menuH==2 AND $menu==0) {
  $menu =9999992;
}
if ($menuH==3 AND $menu==0) {
  $menu =9999993;
}
if ($menuH==4 AND $menu==0) {
  $menu =9999994;
}
if ($menuH==5 AND $menu==0) {
  $menu =9999995;
}
if ($lg=="en") {
    if ($menuH==1) {$menuH_nazev = 'Projects';}
    if ($menuH==2) {$menuH_nazev = 'Products';}
    if ($menuH==3) {$menuH_nazev = 'Software';}
    if ($menuH==4) {$menuH_nazev = 'Contacts';}
    if ($menuH==5) {$menuH_nazev = 'Shop';}
}else {
    if ($menuH==1) {$menuH_nazev = 'Projekty';}
    if ($menuH==2) {$menuH_nazev = 'Produkty';}
    if ($menuH==3) {$menuH_nazev = 'Software';}
    if ($menuH==4) {$menuH_nazev = 'Kontakty';}
    if ($menuH==5) {$menuH_nazev = 'Shop';}
}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Allcomp a.s. - Elektronicke ridici systemy</title>
<style type="text/css">
body {
        margin:0;
        padding:0;
        font: 11px Verdana;
}
.styleA1 {
	color: #FFFFFF;
}
.style1 {
	background-image: url('img_index/bg_search.gif');
}
/* ==================== Main menu styles =================== */
#menu_bar
	{
	width: 975px;
	margin: 0;
	padding: 0;
	clear: both;
	height:41px;
	}
#menu_bar ul {
	margin: 0;
	list-style-type: none;
	padding: 0;
	}
#menu_bar ul li {
	float: left;
	display: block;
	width: 195px;
	height: 41px;
	margin: 0;
	padding: 0;
	vertical-align:middle;
	}
#menu_bar ul li a {
	font-family:Verdana;
	font-weight:normal;
	font-size:12px;
	width: 195px;
	height: 27px;
	background-position: 0 0;
	background-repeat: no-repeat;
	display: block;
	text-align:center;
	padding-top:14PX;
	color:#FFFFFF;
	text-decoration:none;
	background-image: url(img_index/menu_bg.gif); 
	}
#menu_bar ul li:hover a {
	background-position: 0 -41px;
	}

#menu_bar ul li a.sel {
	background-position: 0 -41px;
	}

#menu_bar ul li a:hover {
	background-position: 0 -41px;
	}

/* ================== Main menu styles =================== */
.header {
	display: block;
	margin-top: 18px;
	color: #FFFFFF;
	font-weight:bold;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	margin-bottom:5px;
	}
.style3 {
	background-image: url('img_index/bg_layout.gif');
}
#logo{ 
	width: 376px;
	height: 95px;
	float: left;
	}
#logo a {
	width: 376px;
	height: 95px;
	display: block;
	}
.features {
	margin:10px;
	display: block;
    background-image: url('img_index/bg_body.gif');
    background-position: 0 -71px;
	color: #FFF;
	font: 11px/25px "Lucida Grande", LucidaGrande, Lucida, Helvetica, Arial, sans-serif;
	padding: 0 15px;
	text-transform: uppercase;
	letter-spacing: .10em;	
	height:25px;
	}
.stred {
	margin:10px;
	padding: 0 15px;
	letter-spacing: .10em;	
	border: 1px #80959F solid;
}
.paticka {
	margin:0px;
	text-align: center;
	padding: 15px;
	letter-spacing: .10em;
	border-top: 1px #80959F solid;
}
.box {
	width: 290px;
	border: 0;
	height: 15px;
	float: left;
	padding-top:3px;
	margin-left:10px;
	}
#login {
	height: 95px;
	float: left;
	}
#login ul {
	margin: 10px 0 0 15px;
	list-style-type:none;	
	padding: 0;
	width: 143px;
	}
#login ul li {
	margin: 0;
	padding: 0;
	font-size: 11px;
	font-weight: normal;
	color: #FFF;
	}
#login ul li a {
	color: #FFF;
	font-weight: bold;
	text-decoration: none;
	}
#login ul li a:hover {
	color: #467;
	}
.recent {
	margin-top:10px;
	background: #FC7;
	width:100%;
	}
.recent h1 {
	display: block;
	background: #F90;
	color: #FFF;
	font: 11px/25px "Lucida Grande", LucidaGrande, Lucida, Helvetica, Arial, sans-serif;
	padding: 0 15px;
	text-transform: uppercase;
	letter-spacing: .10em;
	}
.recent p {
	padding: 5px 15px 15px 15px;
	}
.recent div {
	padding-left: 15px;
	color:#333333;
	}
#menu_l {
	font-family:Verdana;
	width:250px;
	float: left;
	}
#menu_l ul {
	margin: 0 0 0 0;
	list-style-type:none;	
	padding: 0;
	width: 143px;
	}
#menu_l ul li {
	margin: 0;
	padding: 0;
	font-size: 11px;
	font-weight: normal;
	}
#menu_l ul li a {
	color: #467;
	font-weight: bold;
	text-decoration: none;
	}
#menu_l ul li a:hover {
	color: #C67700;
	margin-left:2px;
	}
</style>
</head>

<body style="margin-top: 0; background-image: url('img_index/bg_body.gif');	background-repeat: repeat-x;">

<table style="width: 980px" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td style="height: 95px" colspan="2" valign="top">
		<table style="width: 100%; height: 95px" cellspacing="0" cellpadding="0">
			<tr>
				<td style="width: 390px" class="style3">
<? // FLASH BANNER *************************************************************
?>
				<div id="logo">
    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="371" height="93" id="tech" align="middle">
		<param name="allowScriptAccess" value="sameDomain" />
		<param name="movie" value="plocha.swf" />
		<param name="quality" value="high" />
		<embed src="plocha.swf" quality="high" width="371" height="93" name="tech" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
	</object>				
				</div>
<? // KONEC FLASH BANNER *******************************************************
?>
				</td>
				<td valign="top" style="width: 390px" class="style1">
			    <?if ($lg=="en") {
                    echo"<div style=\"margin-left:10px; margin-top:10px; height: 15px;\" class=\"header\">Search:</div>";
                }else {
                  echo"<div style=\"margin-left:10px; margin-top:10px; height: 15px;\" class=\"header\">Hledat:</div>";
                }?>
				<div>
				     <form name="searches" method="post" action="#">
     <input name="query_txt" class="box" type="text">&nbsp;&nbsp;&nbsp;
    <input name="search" src="img_index/btn_search.gif" value="Search" alt="Search" type="image">   
      </form>
				</div>
				<div style="margin-left:10px; margin-top:10px; height: 15px; text-align:center;" class="header">
                 <?//echo"<a href=\"?lg=cz&menu=$menu&menu_p=$menu_p&menuH=$menuH&menu_pop=$menu_pop\"><img border=\"0\" src=\"img_index/cz.gif\" width=\"40\" height=\"19\"></a>";
                 ?>&nbsp;&nbsp;&nbsp;
                 <?//echo"<a href=\"?lg=en&menu=$menu&menu_p=$menu_p&menuH=$menuH&menu_pop=$menu_pop\"><img border=\"0\" src=\"img_index/en.gif\" width=\"40\" height=\"19\"></a>";
                 ?>
                 <?echo"<a href=\"http://www.allcomp.cz/index.php?lg=en\"><img border=\"0\" src=\"img_index/en.gif\" width=\"40\" height=\"19\"></a>";?>
                </div>
				</td>
				<td valign="top" style="background-image:url('img_index/bg_login.gif')">
				<div id="login">
				<div>&nbsp;&nbsp;<br />
&nbsp; </div>
				<ul>
                 <?if ($lg=="en") {
                    echo"
                    <li><a href=\"#\">Login</a></li>
				    <li><a href=\"#\">Registration</a></li>";
                 }else {
                    echo"
                    <li><a href=\"#\">P&#345;ihl&#225;sit</a></li>
				    <li><a href=\"#\">Registrace</a></li>";
                 }?>
			    </ul>
				</div>
				</td>
			</tr>
		</table>
		</td>
		<td style="width: 5px; height: 95px"></td>
	</tr>
	<tr>
		<td colspan="2" style="height: 41px" valign="top">
<? // MENU BAR *****************************************************************
?>
  <div id="menu_bar">
    <ul>
    <?
    if ($lg=="en") {
        if ($menuH==1){echo"<li><a href=\"?menuH=1&lg=en\" class=\"sel\">PROJECTS</a></li>";}else{echo"<li><a href=\"?menuH=1&lg=en\">PROJECTS</a></li>";}
        if ($menuH==2){echo"<li><a href=\"?menuH=2&lg=en\" class=\"sel\">PRODUCTS</a></li>";}else{echo"<li><a href=\"?menuH=2&lg=en\">PRODUCTS</a></li>";}
        if ($menuH==3){echo"<li><a href=\"?menuH=3&lg=en\" class=\"sel\">SOFTWARE</a></li>";}else{echo"<li><a href=\"?menuH=3&lg=en\">SOFTWARE</a></li>";}
        if ($menuH==4){echo"<li><a href=\"?menuH=4&lg=en\" class=\"sel\">CONTACTS / ABOUT US</a></li>";}else{echo"<li><a href=\"?menuH=4&lg=en\">CONTACTS / ABOUT US</a></li>";}
        if ($menuH==5){echo"<li><a href=\"?menuH=5&lg=en\" class=\"sel\">E-SHOP</a></li>";}  else{echo"<li><a href=\"?menuH=5&lg=en\">E-SHOP</a></li>";}
    }else {
        if ($menuH==1){echo"<li><a href=\"?menuH=1\" class=\"sel\">PROJEKTY</a></li>";}else{echo"<li><a href=\"?menuH=1\">PROJEKTY</a></li>";}
        if ($menuH==2){echo"<li><a href=\"?menuH=2\" class=\"sel\">PRODUKTY</a></li>";}else{echo"<li><a href=\"?menuH=2\">PRODUKTY</a></li>";}
        if ($menuH==3){echo"<li><a href=\"?menuH=3\" class=\"sel\">SOFTWARE</a></li>";}else{echo"<li><a href=\"?menuH=3\">SOFTWARE</a></li>";}
        if ($menuH==4){echo"<li><a href=\"?menuH=4\" class=\"sel\">KONTAKTY / O N&#193;S</a></li>";}else{echo"<li><a href=\"?menuH=4\">KONTAKTY / O N&#193;S</a></li>";}
        if ($menuH==5){echo"<li><a href=\"?menuH=5\" class=\"sel\">E-SHOP</a></li>";}  else{echo"<li><a href=\"?menuH=5\">E-SHOP</a></li>";}
    }
    ?>
    </ul>
  </div>		
<? // KONEC MENU BAR ***********************************************************
?>
		</td>
		<td style="width: 5px; height: 41px"></td>
	</tr>
	<tr>
		<td style="width: 250px" valign="top">
	  <div class="recent" style="width: 100%">
        <h1><span lang="cs">Menu - <?echo"$menuH_nazev";?></span></h1>
        
        <div id="menu_l">
        <ul>
<? //MENU **********************************************************************
$vysledekM = mysql_query("SELECT * FROM Menu Where menu_horizontal=$menuH order by sorted");
             while ($zaznamM = mysql_fetch_assoc($vysledekM)){
                    $podkategorieJE = 0;
                    $idM            = $zaznamM['id'];
                    $NazevM         = $zaznamM['nazev'];
                        // jiny obr pokud ma kategorie podkat.
                        $vysledekPMP = mysql_query("SELECT * FROM Menu_unt where id_menu=$idM");
                        while ($zaznamPMP = mysql_fetch_assoc($vysledekPMP)){
                          $podkategorieJE = 1;
                        }
             echo"<div><li style=\"height: 20px; width: 214px;\">";
             if ($podkategorieJE==1) {
                echo"<img border=\"0\" src=\"img_index/menu_down.jpg\" width=\"12\" height=\"12\">";
             } else {
                echo"<img border=\"0\" src=\"img_index/menu_pun.jpg\" width=\"12\" height=\"12\">";
             }
                //vybrana kat jina barva
             if ($menu == $idM){
                echo"&nbsp;&nbsp;<a href=\"?menu=$idM&menuH=$menuH\"><font color=\"#D97700\">$NazevM</font></a></li></div>";
             }else {
                echo"&nbsp;&nbsp;<a href=\"?menu=$idM&menuH=$menuH\">$NazevM</a></li></div>";
             }
//podmenu
$vysledekPM = mysql_query("SELECT * FROM Menu_unt where (id_menu=$idM and id_menu=$menu) order by nazev");
                        while ($zaznamPM = mysql_fetch_assoc($vysledekPM)){
                        $idPM      = $zaznamPM['id'];
                        $NazevPM   = $zaznamPM['nazev'];
             echo"<div><li style=\"height: 20px; width: 214px; margin-left: 10px;\">
                       <img border=\"0\" src=\"img_index/menu_pun.jpg\" width=\"8\" height=\"8\">";
             //vybrana podkat jina barva
             if ($menu_p == $idPM){
                echo"&nbsp;&nbsp;<a href=\"?menu=$idM&menu_p=$idPM&menuH=$menuH\"><font color=\"#D97700\">$NazevPM</font></a></li></div>";
             }else {
                echo"&nbsp;&nbsp;<a href=\"?menu=$idM&menu_p=$idPM&menuH=$menuH\">$NazevPM</a></li></div>";
             }
}
} // KONEC MENU  ***************************************************************
if ($menuH==4) {
  echo"<br><div><li style=\"height: 20px; width: 214px;\">";
  echo"<img border=\"0\" src=\"img_index/menu_pun.jpg\" width=\"12\" height=\"12\">";
  if ($menu_pop==25) {
    echo"&nbsp;&nbsp;<a href=\"?menuH=$menuH&menu_pop=25\"><font color=\"#D97700\">Organiza&#269;n&#237; struktura</font></a></li></div>";
  } else {
    echo"&nbsp;&nbsp;<a href=\"?menuH=$menuH&menu_pop=25\">Organiza&#269;n&#237; struktura</a></li></div>";
  }
}
?>
        </ul>
        </div>

<?  //zjisteni nazvu kategorie a menu ******************************************
$vysledekKM = mysql_query("SELECT * FROM Menu Where id=$menu");
                        while ($zaznamKM = mysql_fetch_assoc($vysledekKM)){
                        $NazevKM = $zaznamKM['nazev'];
}
?>
<?
$vysledekKPM = mysql_query("SELECT * FROM Menu_unt Where id=$menu_p");
                        while ($zaznamKPM = mysql_fetch_assoc($vysledekKPM)){
                        $NazevKPM = $zaznamKPM['nazev'];
}
 // KONEC zjisteni kategorie a menu ********************************************
?>
   		<div> &nbsp;</div></div>
   		<div> &nbsp;</div><div> &nbsp;</div><div> &nbsp;</div>
   		<div style="text-align:center;">
   			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="244" height="68" id="tech" align="middle">
		<param name="allowScriptAccess" value="sameDomain" />
		<param name="movie" value="vizitka.swf" />
		<param name="quality" value="high" />
		<embed src="vizitka.swf" quality="high" width="244" height="68" name="tech" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
	</object></div>
   	<div> &nbsp;</div>
   	<div style="text-align:center;">
   	 <p><a href="http://www.allcomp.cz/new/?menu=37&amp;menu_p=17&amp;menuH=1" target="_blank"><img border="0" src="http://topeni.allcomp.cz/tc_online.jpg" width="213" height="206"></a></p>
   	</div>
   	<div> &nbsp;</div>
   	<div style="text-align:center;">Spolupracujeme:</div>
   	<div> &nbsp;</div>
   	<div style="text-align:center;">
   	 <a href="http://www.panasonic-klimatizace.cz/" target="_blank"><img src="pesek.jpg" style="border: 1px solid grey"></a>
   	</div>
   	<div> &nbsp;</div>
   	
   	<div> &nbsp;</div>
		</td>
		<td style="width: 725px" valign="top" class="style4">
		<table style="width: 100%" cellspacing="0" cellpadding="0">
			<tr>
<? // cesta menu ***************************************************************
?>
				<td><div class="features"><?echo"$menuH_nazev / $NazevKM / $NazevKPM";?></div></td>
			</tr>
			<tr>
				<td><div class="stred">

<?  // STRED  ******************************************************************
if ($menu_p>0){
  if ($menu_pop>0){
    $resultB = mysql_query("SELECT * FROM popup_okna Where id=$menu_pop");
  						while ($rowB = mysql_fetch_assoc($resultB)) {
                        $ID=$rowB["id"];
                        $Stred=$rowB["Stred"];
    }
  }else{
    $resultB = mysql_query("SELECT * FROM Stred Where druh=$menu_p");
  						while ($rowB = mysql_fetch_assoc($resultB)) {
                        $ID=$rowB["id"];
                        $Stred=$rowB["Stred"];
    }
   }
} else {

  if ($menu_pop>0){
    $resultB = mysql_query("SELECT * FROM popup_okna Where id=$menu_pop");
  						while ($rowB = mysql_fetch_assoc($resultB)) {
                        $ID=$rowB["id"];
                        $Stred=$rowB["Stred"];
    }
  }else{

$resultB = mysql_query("SELECT * FROM Stred Where (kategorie=$menu AND druh='0')");
  						while ($rowB = mysql_fetch_assoc($resultB)) {
                        $ID=$rowB["id"];
                        $Stred=$rowB["Stred"];
}
}
}
if ($ahojte==1) { // uvodni obrazovka
  require("ahojte.php");
}
echo"$Stred";
//require("fcked.php");
// KONEC STRED  ****************************************************************
?>

                </div></td>
			</tr>
		</table>
		</td>
		<td style="width: 5px">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 250px">&nbsp;</td>
		<td>
<? // tlacitka print, zpet, domu ***********************************************
?>
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td>&nbsp; <img border="0" src="img_index/print.jpg" width="23" height="23"></td>
              <td width="100" style="text-align:right">
                <a href="javascript:history.go(-1)"><img border="0" src="img_index/back.jpg" width="23" height="23"></a></td>
              <td width="100" style="text-align:center">
                <a href="http://www.allcomp.cz"><img border="0" src="img_index/home.jpg" width="23" height="23"></a></td>
            </tr>
            <tr>
              <td style="text-align:left; color:#C0C0C0;">
                &nbsp; Tisk</td>
              <td width="100" style="text-align:right; color:#C0C0C0;">
               <a href="javascript:history.go(-1)" style="text-decoration: none; color: #C0C0C0;">Zpet</a></td>
              <td width="100" style="text-align:center; color:#C0C0C0;">
               <a href="http://www.allcomp.cz" style="text-decoration: none; color: #C0C0C0;">Domu</a></td>
            </tr>
          </table>
<? // konec - tlacitka print, zpet, domu ***************************************
?>
        </td>
		<td style="width: 5px">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 250px">&nbsp;</td>
		<td></td>
		<td style="width: 5px">&nbsp;</td>
	</tr>
	<td colspan="3">
        <p><font color="#446677">&copy; Allcomp a.s. 2010-12</font></p>
    </td>
</table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><br><br><br>
          <div class="paticka"><font color="#446677"></font></div></td>
      </tr>
    </table>
</body>
</html>

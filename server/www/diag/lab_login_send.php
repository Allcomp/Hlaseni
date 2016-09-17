<?php
require ("lab_cestina.php");
require ("lab_pripojeni_db.php");
$name = $_POST["name"];
$pass = $_POST["pass"];
?>

<?php
    if($pass=="123456" AND $name == "admin"){
      $_SESSION["prihlasen"]  = 1;
      $_SESSION["uzivatel"]   = $name;
      echo"<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
    } else {
      $_SESSION["prihlasen"] = 0;
      $_SESSION["uzivatel"] = "nikdo";
      $_SESSION["table"]      = "";
      $_SESSION["tableC"]     = "";      
      echo"Pøístup odepøen / Access denied ...";
      echo"<meta http-equiv=\"refresh\" content=\"2;url=lab_login.php\">";
    }
?>
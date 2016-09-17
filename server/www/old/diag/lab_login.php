<!DOCTYPE html>
<?php
require ("lab_cestina.php");
$_SESSION["prihlasen"] = 0;
$_SESSION["uzivatel"] = "nikdo";
?>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="cs"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta http-equiv="Content-language" content="cs" />
   <meta charset="Windows-1250" />
   <title>Login</title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport" />
   <meta content="" name="description" />
   <meta content="" name="author" />
   <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
   <link href="assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
   <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
   <link href="css/style.css" rel="stylesheet" />
   <link href="css/style-responsive.css" rel="stylesheet" />
   <link href="css/style-default.css" rel="stylesheet" id="style_color" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="lock">
    <div class="lock-header">
        <!-- BEGIN LOGO -->
        <a class="center" id="logo" href="index.php">
            
        </a>
        <!-- END LOGO -->
    </div>
    <div class="login-wrap">
        <div class="metro single-size red">
            <div class="locked">
                <i class="icon-lock"></i>
                <span>Přihlášení</span>
            </div>
        </div>
        <div class="metro double-size green">
            <form action="lab_login_send.php" method="post">
                <div class="input-append lock-input">
                    <input type="text" class="" name="name" placeholder="Uživatelské jméno">
                </div>
            <!--</form>-->
        </div>
        <div class="metro double-size yellow">
            <!--<form action="http://thevectorlab.net/metrolab/index.html">-->
                <div class="input-append lock-input">
                    <input type="password" class="" name="pass" placeholder="Heslo">
                </div>
            <!--</form>-->
        </div>
        <div class="metro single-size terques login">
            <!--<form action="index.php">-->
                <button type="submit" class="btn login-btn">
                    Přihlásit
                    <i class=" icon-long-arrow-right"></i>
                </button>
            </form>
        </div>

        <div class="login-footer">
            <div class="remember-hint pull-left">
                <input type="checkbox" id=""> Zapamatuj si...
            </div>
            <div class="forgot-hint pull-right">
                <a id="forget-password" class="" href="javascript:;">Zapomenuté heslo?</a>
            </div>
        </div>
    </div>
</body>
<!-- END BODY -->
</html>
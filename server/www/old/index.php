<?php
    header('Content-Type: text/html; charset=utf-8');
    define("ABS_PATH", "http://localhost/dsc");
    require_once("./phplib/ClassLoader.class.php");
    require_once("./config.php");

    $classLoader = new ClassLoader();
    $classLoader->run();
    $database = new Database(new DatabaseConfig($_CONFIG['dbhost'], $_CONFIG['dbuser'], $_CONFIG['dbpass'], $_CONFIG['dbname']));
	
	session_unset();
	
	function getIPv4($address_ipv6) {
		$v4mapped_prefix_hex = '00000000000000000000ffff';
		$v4mapped_prefix_bin = pack("H*", $v4mapped_prefix_hex);

		// Or more readable when using PHP >= 5.4
		# $v4mapped_prefix_bin = hex2bin($v4mapped_prefix_hex); 

		// Parse
		$addr = $address_ipv6;
		$addr_bin = inet_pton($addr);
		if( $addr_bin === FALSE ) {
		  // Unparsable? How did they connect?!?
		  die('Invalid IP address');
		}

		// Check prefix
		if( substr($addr_bin, 0, strlen($v4mapped_prefix_bin)) == $v4mapped_prefix_bin) {
		  // Strip prefix
		  $addr_bin = substr($addr_bin, strlen($v4mapped_prefix_bin));
		}

		// Convert back to printable address in canonical form
		$addr = inet_ntop($addr_bin);
		return $addr;
	}
	
	function isConnectedFromLAN() {
		$ipClient = getIPv4($_SERVER['REMOTE_ADDR']);
		$ipServer = getIPv4($_SERVER['SERVER_ADDR']);
		$ipClient = explode(".",$ipClient);
		$ipServer = explode(".",$ipServer);
		if(count($ipClient) < 2 || count($ipServer) < 2) {
			if(count($ipClient) == 1 && count($ipServer) == 1) {
				return $ipClient[0] == $ipServer[0];
			} else return false;
		} else {
			return $ipClient[0] == $ipServer[0] && $ipClient[1] == $ipServer[1];
		}
	}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dynamic Smart Control</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <meta content='width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0' name='viewport' />
    </head>
    <body onload="">
		<?php if(!$_CONFIG['require_login'] || ($_CONFIG['not_require_login_in_LAN'] && isConnectedFromLAN())) :?>
			<?php 
				$_SESSION["permission_level"] = $_CONFIG['default_permission_level']; 
				$_SESSION["authorized"] = true;
			?>
			<script>window.location.replace("./control.php");</script>
		<?php else :?>
		
			<div class="mainMenu">
				<form action="#" method="post" style="display: inline-block;">
				<table class="loginTable">
					<tr>
						<td>Uživatelské jméno: </td>
						<td><input type="text" name="username" /></td>
					</tr>
					<tr>
						<td>Heslo: </td>
						<td><input type="password" name="password" /></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" name="submit" value="Přihlásit se" /></td>
					</tr>
				</table>
				</form>
				<?php if(isset($_POST['submit'])) :?>
				<p>
					<?php $numRowsUsers = $database->numRowsQuery("SELECT * FROM `users` WHERE `username` LIKE '".$_POST['username']."' AND `password` LIKE '".md5($_POST['password'])."';");
					if($numRowsUsers > 0) :?>
						<?php 
							$_SESSION["authorized"] = true;
							$dbResult = $database->executeQuery("SELECT * FROM `users` WHERE `username` LIKE '".$_POST['username']."';");
							while($row = $dbResult->fetch_row()) {
								$_SESSION["permission_level"] = intval($row[3]);
								$database->executeUpdate("UPDATE `users` SET `last_ip`='".getIPv4($_SERVER['REMOTE_ADDR'])."' WHERE `username` LIKE '".$_POST['username']."';");
								break;
							}
						?>
						<script>window.location.replace("./control.php");</script>
					<?php else :?>
						<i>Chybné jméno nebo heslo!</i>
					<?php endif; ?>
				</p>
				<?php endif; ?>
			</div>
		
		<?php endif; ?>
    </body>
</html>
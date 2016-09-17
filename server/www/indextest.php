<?php
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
        <meta content='width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0' name='viewport' />
    </head>
    <body onload="">
        <!--<script>window.location.replace("./control.php");</script>-->
		
		<p>Is connected from LAN: <?=isConnectedFromLAN() ? "Y" : "N"?></p>
    </body>
</html>
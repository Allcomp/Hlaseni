<script>	
	function security_info_update(state, requestId) {
		if(state == '1') {
			$("div#securityState p:nth-of-type(1) img").attr("src", "./resources/appicons/securityenabled.png");
			$("div#securityState p:nth-of-type(2)").html("Zabezpečovací systém je zapnutý");
		} else if(state == '0') {
			$("div#securityState p:nth-of-type(1) img").attr("src", "./resources/appicons/securitydisabled.png");
			$("div#securityState p:nth-of-type(2)").html("Zabezpečovací systém je vypnutý");
		} else {
			$("div#securityState p:nth-of-type(1) img").attr("src", "./resources/appicons/securityunknown.png");
			$("div#securityState p:nth-of-type(2)").html("Server odeslal nesmyslný stav!");
		}
		deleteRequest(requestId);
	}
	
	function requestSecurityInfoUpdate() {
		var reqid = makeRequest('security_info_update', 'securitystateget');
		setTimeout(function() {
			if(requestExists(reqid)) {
				$("div#securityState p:nth-of-type(1) img").attr("src", "./resources/appicons/securityunknown.png");
				$("div#securityState p:nth-of-type(2)").html("Nepodařilo se získat informace o zapezpečovacím systému!");
				deleteRequest(reqid);
			}
		}, 1800);
	}
	
	function server_availability_info_update(response, reqid) {
		$("div#serverAvailability p:nth-of-type(1) img").attr("src", "./resources/appicons/online.png");
		$("div#serverAvailability p:nth-of-type(2)").html("Server je dostupný");
		$("div#serverAvailability p:nth-of-type(3)").html("Verze: " + response);
		deleteRequest(reqid);
	}
	
	function requestServerAvailabilityInfoUpdate() {
		var reqid = makeRequest('server_availability_info_update', 'getversionandname');
		setTimeout(function() {
			if(requestExists(reqid)) {
				$("div#serverAvailability p:nth-of-type(1) img").attr("src", "./resources/appicons/offline.png");
				$("div#serverAvailability p:nth-of-type(2)").html("Server není dostupný");
				$("div#serverAvailability p:nth-of-type(3)").html("");
				deleteRequest(reqid);
			}
		}, 1800);
	}
	
	$(document).ready(function() {
		requestServerAvailabilityInfoUpdate();
		requestSecurityInfoUpdate();
		
		setInterval(function() {
			requestServerAvailabilityInfoUpdate();
			requestSecurityInfoUpdate();
		}, 2000);
	});
</script>
<div class="box" id="serverAvailability" style="width: 200px; margin-right: 10px;">
	<div class="header">Dostupnost serveru</div>
	<div class="content">
		<p><img src="./resources/appicons/offline.png" /></p>
		<p style="font-size: 15px; font-variant: small-caps;">Server není dostupný</p>
		<p></p>
	</div>
</div><br />
<div class="box" id="securityState" style="width: 200px; margin-right: 10px;">
	<div class="header">Zabezpečení</div>
	<div class="content">
		<p><img src="./resources/appicons/securityunknown.png" /></p>
		<p style="font-size: 15px; font-variant: small-caps;">Získávání informací o zabezpečení...</p>
	</div>
</div><br />
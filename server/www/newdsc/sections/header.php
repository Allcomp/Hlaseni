<html>
<head>
	<title>Smart Control</title>
	<meta charset="utf-8" />
	<meta content='width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<script src="<?=ABS_PATH?>/js/jquery-2.2.2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?=ABS_PATH?>/css/pc.css">
	<script>
		var paneShown = false;
		function showPane() {
			$("div#pane").animate({
				marginLeft: '0px'
			}, 350);
			$("div#content").animate({
				marginLeft: "300px"
			}, 350);
			setTimeout(function() {
				$("div#pane div#controlpane div#cptext img").attr("src", "<?=ABS_PATH?>/resources/appicons/leftarrow.png");
			}, 350);
		}
		function hidePane() {
			$("div#pane").animate({
				marginLeft: "-300px"
			}, 350);
			$("div#content").animate({
				marginLeft: "0px"
			}, 350);
			setTimeout(function() {
				$("div#pane div#controlpane div#cptext img").attr("src", "<?=ABS_PATH?>/resources/appicons/rightarrow.png");
			}, 350);
		}
		$(document).ready(function() {
			$("div#pane div#controlpane").click(function() {
				if(paneShown)
					hidePane();
				else
					showPane();
				paneShown = !paneShown;
			});
			//$("div#pane").css("height", window.innerHeight + "px");
		});
		
		var REQUEST_GLOBAL_ID = 0;
		
		function requestExists(reqid) {
			return $("head").find("script#request"+reqid).length > 0;
		}
		
		function deleteRequest(reqid) {
			$("head").find("script#request"+reqid).remove();
		}
		
		function makeRequest(functionName, data) {
			var reqid = REQUEST_GLOBAL_ID++;
			var script = document.createElement('script');
			$(script).attr("id", "request"+reqid);
			script.src = 'http://<?=SMARTSERVER_IP?>:<?=SMARTSERVER_PORT?>/'+functionName+':'+reqid+'/'+data;
			document.getElementsByTagName('head')[0].appendChild(script);
			return reqid;
		}
	</script>
</head>
<body>
	<div id="pane">
		<div id="controlpane"><div id="cptext"><img src="<?=ABS_PATH?>/resources/appicons/rightarrow.png" /></div></div>
		<div class="header">Chytrý dům</div>
		<a href="?"><div class="item">Domovská stránka</div></a>
		<a href="?page=rooms"><div class="item">Místnosti</div></a>
		<a href="#"><div class="item">Makra</div></a>
		<a href="#"><div class="item">Zabezpečovací systém</div></a>
		<div class="header">Aplikace</div>
		<a href="#"><div class="item">Podpora</div></a>
		<a href="#"><div class="item">Aktualizace</div></a>
	</div>
	<div id="content">
	
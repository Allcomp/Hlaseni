<?php
	header('Content-Type: text/html; charset=utf-8');
	
	ini_set('display_errors', 1); 
	error_reporting(E_ALL);
	
	//define("ABS_PATH", "http://192.168.42.1/vysilani");
	require_once("./phplib/ClassLoader.class.php");
	require_once("./config.php");
	
	$classLoader = new ClassLoader();
	$classLoader->run();
	$database = new Database(new DatabaseConfig($_CONFIG['dbhost'], $_CONFIG['dbuser'], $_CONFIG['dbpass'], $_CONFIG['dbname']));
	
	$pages = array("main", "recordings", "record", "announcements", "tunes", "error404", "edit_recording", "edit_tune", "edit_announcement", "settings");
	
	function encodeURIComponent($str) {
		$revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
		return strtr(rawurlencode($str), $revert);
	}
	
	require_once("./init.php");
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo($_CONFIG["name"] . " v" . $_CONFIG["version"]); ?></title>
		<script src="./jslib/jquery.js"></script>
		<script src="./jslib/jquery-ui.min.js"></script>
		<link rel="stylesheet" type="text/css" href="./css/main.css" />
		<script>
			<?php 
				$pagesString = "";
				foreach($pages as $pg)
					$pagesString .= ",\"".$pg."\"";
				if(strlen($pagesString)>0)
					$pagesString = substr($pagesString, 1);
			?>
			
			function requestPage(page) {
				var pages = [<?=$pagesString?>];
				if (
					document.fullscreenElement ||
					document.webkitFullscreenElement ||
					document.mozFullScreenElement ||
					document.msFullscreenElement
				) {
					if(pages.indexOf(page) != -1) {
						$.ajax({
							url: "./pages/"+page + ".php",
							type: 'GET',
							success: function(res) {
								$("main").html(res);
							}
						});
					} else {
						$.ajax({
							url: "./pages/error404.php",
							type: 'GET',
							success: function(res) {
								$("main").html(res);
							}
						});
					}
				} else {
					if(pages.indexOf(page) != -1) {
						location.href="?page="+page;
					} else {
						location.href="?page=error404";
					}
				}
			}
		
			$(document).ready(function() {
				$("div#shade").fadeOut(0);
				$("div#uploadDialog").fadeOut(0);
				$("div#titleBox").fadeOut(0);
				
				$("img#fullscreen_btn").click(function() {
					if (
						document.fullscreenEnabled || 
						document.webkitFullscreenEnabled || 
						document.mozFullScreenEnabled ||
						document.msFullscreenEnabled
					) {
						var i = $("html").toArray()[0];

						if (
							document.fullscreenElement ||
							document.webkitFullscreenElement ||
							document.mozFullScreenElement ||
							document.msFullscreenElement
						) {
							if (document.exitFullscreen) {
								document.exitFullscreen();
							} else if (document.webkitExitFullscreen) {
								document.webkitExitFullscreen();
							} else if (document.mozCancelFullScreen) {
								document.mozCancelFullScreen();
							} else if (document.msExitFullscreen) {
								document.msExitFullscreen();
							}
						} else {
							if (i.requestFullscreen) {
								i.requestFullscreen();
							} else if (i.webkitRequestFullscreen) {
								i.webkitRequestFullscreen();
							} else if (i.mozRequestFullScreen) {
								i.mozRequestFullScreen();
							} else if (i.msRequestFullscreen) {
								i.msRequestFullscreen();
							}
						}
					}
				});
				
				$("*[data-title]").mouseenter(function(e) {
					var posX = e.clientX;
					var posY = e.clientY;					
					
					var titleBox = $("div#titleBox");
					titleBox.html(decodeURIComponent($(this).data("title")));
					
					var width = parseInt(titleBox.css("padding-left"), 10) + parseInt(titleBox.css("padding-right"), 10) + titleBox.width();
					//var height = parseInt(titleBox.css("padding-top"), 10) + parseInt(titleBox.css("padding-bottom"), 10) + titleBox.height();
					
					titleBox.css("top",(posY/*-height*/-5)+"px");
					titleBox.css("left",(posX-width-5)+"px");
					
					titleBox.fadeIn(100);
				});
				
				$("*[data-title]").mouseleave(function() {
					$("div#titleBox").fadeOut(100);
				});
				
				$("*[data-title]").mousemove(function(e) {
					var posX = e.clientX;
					var posY = e.clientY;					
					
					var titleBox = $("div#titleBox");
					
					var width = parseInt(titleBox.css("padding-left"), 10) + parseInt(titleBox.css("padding-right"), 10) + titleBox.width();
					//var height = parseInt(titleBox.css("padding-top"), 10) + parseInt(titleBox.css("padding-bottom"), 10) + titleBox.height();
					
					titleBox.css("top",(posY/*-height*/-5)+"px");
					titleBox.css("left",(posX-width-5)+"px");
				});
			});
			
			var fullScreenEventHandler = function() {
				if (
					document.fullscreenElement ||
					document.webkitFullscreenElement ||
					document.mozFullScreenElement ||
					document.msFullscreenElement
				) {
					$("img#fullscreen_btn").attr("src", "./res/icons/Compress-48.png");
				} else {
					$("img#fullscreen_btn").attr("src", "./res/icons/Fit%20to%20Width-52.png");
				}
				var w = window.innerWidth;
				var h = window.innerHeight;
				$("body").css("width",w+"px");
				$("body").css("height",h+"px");
			};
			
			document.addEventListener("fullscreenchange", fullScreenEventHandler);
			document.addEventListener("webkitfullscreenchange", fullScreenEventHandler);
			document.addEventListener("mozfullscreenchange", fullScreenEventHandler);
			document.addEventListener("MSFullscreenChange", fullScreenEventHandler);
		</script>
	</head>
	<body>
		<img style="display: none;" id="fullscreen_btn" src="./res/icons/Fit%20to%20Width-52.png" />
		<header><?=$_CONFIG["name"]?> <span style="font-size: 13px;">v<?=$_CONFIG["version"]?></span></header>
		<nav>
			<menu>
				<!--<li onclick="requestPage('main');">Přehled</li>--><!--
				--><li onclick="requestPage('record');">Nahrávání</li><!--
				--><li onclick="requestPage('recordings');">Záznamy</li><!--
				--><li onclick="requestPage('tunes');">Znělky</li><!--
				--><li onclick="requestPage('announcements');">Hlášení</li><!--
				--><li onclick="requestPage('settings');">Nastavení</li>
			</menu>
		</nav>
		<div id="bodybg">
		<main>
			<?php
				if(!isset($_GET["page"])) {
					include("./pages/announcements.php");
				} else {
					if(in_array($_GET["page"], $pages))
						include("./pages/".$_GET["page"].".php");
					else
						include("./pages/error404.php");
				}
			?>
		</main>
		</div>
		<footer>
			Copyright &copy; <?=date("Y");?> ALLCOMP a.s.
		</footer>

		<div id="shade"></div>
		<div id="uploadDialog">
			<h1 class="uploadTitle">Nahrát na server</h1>
			<table class="uploadTable">
				<tr>
					<td>Název položky</td>
					<td><input type="text" id="upload_name" /></td>
				</tr>
				<tr>
					<td>Název souboru</td>
					<td><input type="text" id="upload_filename" class="filename" /> <i>.wav</i></td>
				</tr>
				<tr>
					<td>Popis</td>
					<td><textarea id="upload_description"></textarea></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td style="text-align: right;"><span id="upload_error">Soubor s tímto název již existuje!</span><span id="upload_success">Soubor byl úspěšně nahrán!</span><button id="upload_btn">Nahrát</button><button id="upload_close_btn">Zrušit</button></td>
				</tr>
			</table>
		</div>
		<div id="titleBox"></div>
	</body>
</html>
<?php
include("./Database.class.php");
include("./DatabaseConfig.class.php");
include("../config.php");

$database = new Database(new DatabaseConfig($_CONFIG['dbhost'], $_CONFIG['dbuser'], $_CONFIG['dbpass'], $_CONFIG['dbname']));

$rCounter = 0;
$rsRecordings = $database->executeQuery("SELECT * FROM `recordings` WHERE `name` LIKE '". $_POST["name"] ."';");
while ($row = $rsRecordings->fetch_row())
	$rsCounter++;
if($rsCounter > 0)
	die("E00");

$rCounter = 0;
$rsRecordings2 = $database->executeQuery("SELECT * FROM `recordings` WHERE `file` LIKE '". $_POST["filename"] ."';");
while ($row = $rsRecordings2->fetch_row())
	$rsCounter++;
if($rsCounter > 0)
	die("E01");
	
$database->executeUpdate("INSERT INTO `recordings` (`id` ,`name` ,`description` ,`file` ,`time`) VALUES (NULL , '".$_POST["name"]."', '".$_POST["description"]."', '".$_POST["filename"]."', '".$_POST["time"]."');");

$base64 = str_replace("data:audio/wav;base64,","",$_POST["data"]);

$data = base64_decode($base64);
file_put_contents('../records/' . $_POST["filename"], $data);

echo("OK");
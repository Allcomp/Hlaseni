<?php
include("./Database.class.php");
include("./DatabaseConfig.class.php");
include("../config.php");

$database = new Database(new DatabaseConfig($_CONFIG['dbhost'], $_CONFIG['dbuser'], $_CONFIG['dbpass'], $_CONFIG['dbname']));

$rCounter = 0;
$rsRecordings = $database->executeQuery("SELECT * FROM `tunes` WHERE `name` LIKE '". $_POST["name"] ."';");
while ($row = $rsRecordings->fetch_row())
	$rsCounter++;
if($rsCounter > 0)
	die("E00");

$rCounter = 0;
$rsRecordings2 = $database->executeQuery("SELECT * FROM `tunes` WHERE `file` LIKE '". $_FILES['file']['name'] ."';");
while ($row = $rsRecordings2->fetch_row())
	$rsCounter++;
if($rsCounter > 0)
	die("E01");

if (0 < $_FILES['file']['error']) //upload error  $_FILES['file']['error'] 
	die("E03");

move_uploaded_file($_FILES['file']['tmp_name'], '../tunes/' . $_FILES['file']['name']);

echo("INSERT INTO `tunes` (`id` ,`name` ,`description` ,`file`) VALUES (NULL , '".$_POST["name"]."', '".$_POST["description"]."', '".$_FILES['file']['name']."');");

$database->executeUpdate("INSERT INTO `tunes` (`id` ,`name` ,`description` ,`file`) VALUES (NULL , '".str_replace("'","\'",$_POST["name"])."', '".str_replace("'","\'",$_POST["description"])."', '".$_FILES['file']['name']."');");

echo("OK");
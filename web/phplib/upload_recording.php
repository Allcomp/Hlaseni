<?php
include("./Database.class.php");
include("./DatabaseConfig.class.php");
include("../config.php");

$charTable = Array(
  'ä'=>'a',
  'Ä'=>'A',
  'á'=>'a',
  'Á'=>'A',
  'à'=>'a',
  'À'=>'A',
  'ã'=>'a',
  'Ã'=>'A',
  'â'=>'a',
  'Â'=>'A',
  'č'=>'c',
  'Č'=>'C',
  'ć'=>'c',
  'Ć'=>'C',
  'ď'=>'d',
  'Ď'=>'D',
  'ě'=>'e',
  'Ě'=>'E',
  'é'=>'e',
  'É'=>'E',
  'ë'=>'e',
  'Ë'=>'E',
  'è'=>'e',
  'È'=>'E',
  'ê'=>'e',
  'Ê'=>'E',
  'í'=>'i',
  'Í'=>'I',
  'ï'=>'i',
  'Ï'=>'I',
  'ì'=>'i',
  'Ì'=>'I',
  'î'=>'i',
  'Î'=>'I',
  'ľ'=>'l',
  'Ľ'=>'L',
  'ĺ'=>'l',
  'Ĺ'=>'L',
  'ń'=>'n',
  'Ń'=>'N',
  'ň'=>'n',
  'Ň'=>'N',
  'ñ'=>'n',
  'Ñ'=>'N',
  'ó'=>'o',
  'Ó'=>'O',
  'ö'=>'o',
  'Ö'=>'O',
  'ô'=>'o',
  'Ô'=>'O',
  'ò'=>'o',
  'Ò'=>'O',
  'õ'=>'o',
  'Õ'=>'O',
  'ő'=>'o',
  'Ő'=>'O',
  'ř'=>'r',
  'Ř'=>'R',
  'ŕ'=>'r',
  'Ŕ'=>'R',
  'š'=>'s',
  'Š'=>'S',
  'ś'=>'s',
  'Ś'=>'S',
  'ť'=>'t',
  'Ť'=>'T',
  'ú'=>'u',
  'Ú'=>'U',
  'ů'=>'u',
  'Ů'=>'U',
  'ü'=>'u',
  'Ü'=>'U',
  'ù'=>'u',
  'Ù'=>'U',
  'ũ'=>'u',
  'Ũ'=>'U',
  'û'=>'u',
  'Û'=>'U',
  'ý'=>'y',
  'Ý'=>'Y',
  'ž'=>'z',
  'Ž'=>'Z',
  'ź'=>'z',
  'Ź'=>'Z'
);

$_FILES['file']['name'] = strtr($_FILES['file']['name'], $charTable);
$_FILES['file']['name'] = str_replace(" ","_",$_FILES['file']['name']);

$database = new Database(new DatabaseConfig($_CONFIG['dbhost'], $_CONFIG['dbuser'], $_CONFIG['dbpass'], $_CONFIG['dbname']));

$rCounter = 0;
$rsRecordings = $database->executeQuery("SELECT * FROM `recordings` WHERE `name` LIKE '". $_POST["name"] ."';");
while ($row = $rsRecordings->fetch_row())
	$rsCounter++;
if($rsCounter > 0)
	die("E00");

$rCounter = 0;
$rsRecordings2 = $database->executeQuery("SELECT * FROM `recordings` WHERE `file` LIKE '". $_FILES['file']['name'] ."';");
while ($row = $rsRecordings2->fetch_row())
	$rsCounter++;
if($rsCounter > 0)
	die("E01");

if (0 < $_FILES['file']['error']) //upload error  $_FILES['file']['error'] 
	die("E03");

move_uploaded_file($_FILES['file']['tmp_name'], '../records/' . $_FILES['file']['name']);

$database->executeUpdate("INSERT INTO `recordings` (`id` ,`name` ,`description` ,`file` ,`time`) VALUES (NULL , '".str_replace("'","\'",$_POST["name"])."', '".str_replace("'","\'",$_POST["description"])."', '".$_FILES['file']['name']."', '".$_POST["time"]."');");

echo("OK");
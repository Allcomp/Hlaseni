<?php
include("./Database.class.php");
include("./DatabaseConfig.class.php");
include("../config.php");

$database = new Database(new DatabaseConfig($_CONFIG['dbhost'], $_CONFIG['dbuser'], $_CONFIG['dbpass'], $_CONFIG['dbname']));

$date = DateTime::createFromFormat('d.m.Y H:i:s', $_POST["datetime"]);
$time = $date->getTimestamp();

$database->executeUpdate("INSERT INTO `announcements` (`id` ,`name` ,`description` ,`tune` ,`recording` ,`time` ,`is_played`) VALUES (NULL , '".str_replace("'","\'",$_POST["name"])."', '".str_replace("'","\'",$_POST["description"])."', '".$_POST["tune"]."', '".$_POST["recording"]."', '".$time."000', '0');");

echo("OK");
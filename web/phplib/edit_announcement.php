<?php
include("./Database.class.php");
include("./DatabaseConfig.class.php");
include("../config.php");

$database = new Database(new DatabaseConfig($_CONFIG['dbhost'], $_CONFIG['dbuser'], $_CONFIG['dbpass'], $_CONFIG['dbname']));

$date = DateTime::createFromFormat('d.m.Y H:i:s', $_POST["datetime"]);
$time = $date->getTimestamp();

$database->executeUpdate("UPDATE `announcements` SET `name`='".str_replace("'","\'",$_POST["name"])."', `description`='".str_replace("'","\'",$_POST["description"])."', `tune`='".$_POST["tune"]."', `recording`='".$_POST["recording"]."', `time`='".$time."000' WHERE `id` LIKE '".$_POST["id"]."';");

echo("OK");
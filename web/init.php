<?php

$rsSettings = $database->executeQuery("SELECT * FROM `settings` WHERE `key` LIKE 'default_tune_live_announcement';");

$_WEBSETTINGS["default_tune_live_announcement"] = "";
while ($row = $rsSettings->fetch_row()) {
	$_WEBSETTINGS["default_tune_live_announcement"] = $row[2];
	break;
}
	
if($_WEBSETTINGS["default_tune_live_announcement"] == "") {// default tune not set
	$database->executeUpdate("INSERT INTO `settings` (`id` ,`key` ,`value`) VALUES (NULL ,  'default_tune_live_announcement',  '0');");
	$_WEBSETTINGS["default_tune_live_announcement"] = "0";
}

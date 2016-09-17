<?php
require_once("../config.php");
require_once("../phplib/Database.class.php");
require_once("../phplib/DatabaseConfig.class.php");
$database = new Database(new DatabaseConfig($_CONFIG['dbhost'], $_CONFIG['dbuser'], $_CONFIG['dbpass'], $_CONFIG['dbname']));
$database->executeUpdate("UPDATE `controls` SET `last_time_usage`=". $_GET["time"] ." WHERE `id`=". $_GET["id"] .";"); 
?>
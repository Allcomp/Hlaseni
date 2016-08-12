<?php
include("./Database.class.php");
include("./DatabaseConfig.class.php");
include("../config.php");

$database = new Database(new DatabaseConfig($_CONFIG['dbhost'], $_CONFIG['dbuser'], $_CONFIG['dbpass'], $_CONFIG['dbname']));
echo("UPDATE `settings` SET `value`='".$_POST["default_tune_live_announcement"]."' WHERE `key` LIKE 'default_tune_live_announcement';");
$database->executeUpdate("UPDATE `settings` SET `value`='".$_POST["default_tune_live_announcement"]."' WHERE `key` LIKE 'default_tune_live_announcement';");

echo("OK");
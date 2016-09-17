<?php
include("./Database.class.php");
include("./DatabaseConfig.class.php");
include("../config.php");

$database = new Database(new DatabaseConfig($_CONFIG['dbhost'], $_CONFIG['dbuser'], $_CONFIG['dbpass'], $_CONFIG['dbname']));

$database->executeUpdate("UPDATE `recordings` SET `name`='".str_replace("'","\'",$_POST["name"])."', `description`='".str_replace("'","\'",$_POST["description"])."' WHERE `id` LIKE '".$_POST["id"]."';");

echo("OK");
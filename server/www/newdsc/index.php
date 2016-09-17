<?php

define("DSC_AUTH", true);

require_once(dirname(__FILE__) . "/application/includes/AppConfigLoad.php");
require_once(ABS_PATH . "/application/DSC.php");

$dsc = new DSC();

$pages = array("home.php", "rooms.php", "room.php");

$page = isset($_GET["page"]) ? $_GET["page"] . ".php" : "home.php";
$page = in_array($page, $pages) ? $page : "notfound.php";

$database = new Database(Database::getDatabaseConfig());

require(ABS_PATH . "/sections/header.php");
require(ABS_PATH . "/sections/" . $page);
require(ABS_PATH . "/sections/footer.php");
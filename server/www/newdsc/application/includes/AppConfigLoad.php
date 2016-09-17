<?php
define("MSG_ERR_DIRECT_ACCESS_DENIED", "Přímý přístup k souboru zakázán!");

defined("DSC_AUTH") or die(MSG_ERR_DIRECT_ACCESS_DENIED);

define("ABS_PATH", ".");

$mainConfig = new DomDocument();
$databaseConfig = new DomDocument();
$mainConfig->load("./application/config/MainConfig.xml");
$databaseConfig->load("./application/config/DatabaseConfig.xml");

define("DB_HOST", $databaseConfig->getElementsByTagName("host")->item(0)->nodeValue);
define("DB_USER", $databaseConfig->getElementsByTagName("user")->item(0)->nodeValue);
define("DB_PASS", $databaseConfig->getElementsByTagName("password")->item(0)->nodeValue);
define("DB_NAME", $databaseConfig->getElementsByTagName("name")->item(0)->nodeValue);
define("SMARTSERVER_IP", $mainConfig->getElementsByTagName("smartserver")->item(0)->getElementsByTagName("ip")->item(0)->nodeValue);
define("SMARTSERVER_PORT", $mainConfig->getElementsByTagName("smartserver")->item(0)->getElementsByTagName("port")->item(0)->nodeValue);
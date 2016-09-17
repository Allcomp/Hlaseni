<?php
defined("DSC_AUTH") or die(MSG_ERR_DIRECT_ACCESS_DENIED);

class Database {
    private $databaseConfig;
    private $connector;

    public function __construct(DatabaseConfig $dbConfig) {
        $this->databaseConfig = $dbConfig;

        try {
            $this->connector = new MySQLi($this->databaseConfig->getHost(), $this->databaseConfig->getUser(),
                $this->databaseConfig->getPassword(), $this->databaseConfig->getName());
			$this->connector->set_charset("utf8");
		} catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function __destruct() {
        $this->connector->close();
    }

    public function executeUpdate($cmd) {
        try {
            $this->connector->query($cmd);
        } catch(Exception $e) {
			die($e->getMessage());
        }
    }

    public function executeQuery($cmd) {
        try {
            $result = $this->connector->query($cmd);
            return $result;
        } catch(Exception $e) {
            die($e->getMessage());
            return null;
        }
    }

    public static function getDatabaseConfig() {
        return new DatabaseConfig(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    public static function getDatabase() {
        return new Database(new DatabaseConfig(DB_HOST, DB_USER, DB_PASS, DB_NAME));
    }
}
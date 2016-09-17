<?php
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
            echo($e->getMessage());
        }
    }

    public function __destruct() {
        $this->connector->close();
    }

    public function executeUpdate($cmd) {
        try {
            $this->connector->query($cmd);
        } catch(Exception $e) {
            echo($e->getMessage());
        }
    }

    public function executeQuery($cmd) {
        try {
            $result = $this->connector->query($cmd);
            return $result;
        } catch(Exception $e) {
            echo($e->getMessage());
            return null;
        }
    }
}
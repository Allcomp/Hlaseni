<?php
defined("DSC_AUTH") or die(MSG_ERR_DIRECT_ACCESS_DENIED);

class DatabaseConfig {
    private $host;
    private $name;
    private $user;
    private $password;

    public function __construct($host, $user, $password, $name) {
        $this->host = $host;
        $this->name = $name;
        $this->user = $user;
        $this->password = $password;
    }

    public function getHost() {
        return $this->host;
    }

    public function getName() {
        return $this->name;
    }

    public function getUser() {
        return $this->user;
    }

    public function getPassword() {
        return $this->password;
    }
}
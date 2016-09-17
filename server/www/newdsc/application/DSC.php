<?php
defined("DSC_AUTH") or die(MSG_ERR_DIRECT_ACCESS_DENIED);

class DSC {
    const name = "Dynamic Smart Control";
    const version = "1.0";
    const encoding = "utf-8";

    public function __construct() {
        if(!$this->isCompatible())
            die("Je vyžadována minimální verze PHP 5.4.0!");

        header('Content-Type: text/html; charset=' . DSC::encoding);

        $this->run();
    }

    private function run() {
        session_start();
        spl_autoload_register(array('DSC', 'loadClass'));
    }

    private function isCompatible() {
        switch(version_compare(PHP_VERSION, '5.4.0')) {
            case 1:
                return true;
                break;
            case 0:
                return true;
                break;
            case -1:
                return false;
                break;
            default:
                return false;
                break;
        }
    }

    private static function loadClass($class) {
        $fileString = ABS_PATH . "/application/classes/" . $class . ".class.php";
        if(file_exists($fileString)) {
            require_once($fileString);
            return true;
        } else return false;
    }
}
<?php
defined("DSC_AUTH") or die(MSG_ERR_DIRECT_ACCESS_DENIED);

class Debug {
    private static $on = DEBUG;

    public static function on() {
        self::$on = true;
    }

    public static function off() {
        self::$on = false;
    }

    public static function log($type, $msg) {
        if(self::$on) {
            $fileName = "application-" . date('d.m.Y') . ".log";
            if(!file_exists(ABS_PATH . "/application/debug/" . $fileName)) {
                $myfile = fopen(ABS_PATH . "/application/debug/" . $fileName, "w") or die("Nepodařilo se vytvořit soubor s logem!");
                fclose($myfile);
            }
            $fileHandler = fopen(ABS_PATH . "/application/debug/" . $fileName, 'a');
            $date = date('H:i:s');
            fwrite($fileHandler, '[' . $date . '][' . $_SERVER['REMOTE_ADDR'] . '][' . $type . ']: ' . $msg . "\n");
            fclose($fileHandler);
        }
    }
}
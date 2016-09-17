<?php
class ClassLoader {

    public function run() {
        session_start();
        spl_autoload_register(array('ClassLoader', 'loadClass'));
    }

    private static function loadClass($class) {
        //$class = str_replace('.', '/', $class);
        $fileString = "./phplib/" . $class . ".class.php";
        if(file_exists($fileString)) {
            require_once($fileString);
            return true;
        } else return false;
    }
}
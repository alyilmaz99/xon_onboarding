<?php
class Printer{

private static $instances = [];
protected function __construct() { }

public static function getInstance(): Printer
{
    $cls = static::class;
    if (!isset(self::$instances[$cls])) {
        self::$instances[$cls] = new static();
    }

    return self::$instances[$cls];
}
public function printerYellowumsu($text){
    echo "\033[33m$text\033[0m\n";
}
public function printerRedimsi($text){
    echo "\033[91m$text\033[0m\n";
}


}


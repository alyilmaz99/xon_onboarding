<?php

if (PHP_SAPI != "cli") {
    exit;
}
function fileOpener()
{
    if (!defined("STDIN")) {
        define("STDIN", fopen('php://stdin', 'r'));
        $line = fgets(STDIN);
        return $line;
    }
}

$info = fileOpener();
echo  $info;
var_dump($argc);
var_dump($argv);
getopt("x");
$options = getopt("f:hp:");
var_dump($options);
$shortopts  = "";
$shortopts .= "f:";
$shortopts .= "v::";
$shortopts .= "abc";

$longopts  = array(
    "required:",
    "optional::",
    "option",
    "opt",
);
$options = getopt($shortopts, $longopts);
var_dump($options);
function getDeviceInfo()
{
    $defaults = [
        'dir' => posix_getpwuid(posix_geteuid())['dir'],
        'os' => PHP_OS,
        'username' => posix_getpwuid(posix_geteuid())['name'],
        'shell' => posix_getpwuid(posix_geteuid())['shell']
    ];
    return $defaults;
}

print_r(getDeviceInfo());

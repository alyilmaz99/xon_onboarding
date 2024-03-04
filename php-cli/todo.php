<?php

$servername = "localhost";
$username = "root";
$password = "Ali.2901";
$dbname = "todo";


$userInfo = getDeviceInfo();


try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "\033[92mConnection db failed: \033[0m\n" . $e->getMessage();
}

if (PHP_SAPI != "cli") {
    exit;
}
printf("\033[92mMerhaba: %6.9s \n\n", $userInfo["username"]);
function fileOpener()
{
    if (!defined("STDIN")) {
        define("STDIN", fopen('php://stdin', 'r'));
        $line = fgets(STDIN);
        return $line;
    }
}

if (!isset($argv[1]) || $argv[1] === "help" || $argv[1] === "-h") {
    help();
}

if (isset($argv[1]) && ($argv[1] == "list" || $argv[1] == "-l")) {

    if (isset($argv[2])) {
        $exploded_argv = explode("=", $argv[2]);
        $data =  getListWithStatus($exploded_argv[1]);
        if ($data) {
            maskData($data);
        }
    } else {
        $data = getList();
        if ($data) {
            maskData($data);
        }
    }
}

if (isset($argv[1]) && $argv[1] == "add" && isset($argv[2]) && !empty($argv[2])) {

    $content = $argv[2];
    $taskId = addNewTask($content);
    if ($taskId) {
        $data = getListWithID($taskId);
    }
    if ($data) {
        maskData($data);
    }
}


function help()
{
    printf("-l,list --filter=done or pending %2s for listing tasks \n", "  ");
    printf("-h or help %24s for help \n", " ");
    printf("-a or add %24s for adding content \n", " ");
}


function getList()
{
    global $db;
    $sql = "SELECT * FROM task";
    $stmt = $db->prepare($sql);
    if (!$stmt->execute()) {
        echo "Error" . $stmt->errorInfo() . "\n";
    }
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($data)) {
        return false;
    } else {
        return $data;
    }
}

function maskData($data)
{
    $mask = "\033[33m| %5.5s | %-30.30s | %7s |\033[0m \n";
    printf($mask, "ID", 'Content', "Status");

    for ($i = 0; $i < count($data); $i++) {
        printf($mask, $data[$i]["id"], $data[$i]["content"], $data[$i]["status"] == 0 ? "Pending" : "Done");
    }
}
function getListWithStatus($status)
{
    global $db;
    if ($status == "done") {
        $status = 1;
    } else if ($status == "pending") {
        $status = 0;
    } else {
        printf("\033[31mStatus Bulunamadı!\033[0m \n");

        return;
    }
    $sql = "SELECT * FROM task WHERE status = :status";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":status", $status, PDO::PARAM_INT);
    if (!$stmt->execute()) {
        echo "Error" . $stmt->errorInfo() . "\n";
    }
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($data)) {
        return false;
    } else {
        return $data;
    }
}
function getListWithID($id)
{
    global $db;

    $sql = "SELECT * FROM task WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    if (!$stmt->execute()) {
        echo "Error" . $stmt->errorInfo() . "\n";
    }
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($data)) {
        return false;
    } else {
        return $data;
    }
}


function addNewTask($content)
{
    global $db;

    $sql = "INSERT INTO task (status, content) VALUES(:status, :content)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":status", 0, PDO::PARAM_INT);
    $stmt->bindValue(":content", $content, PDO::PARAM_STR);
    if (!$stmt->execute()) {
        echo "Error" . $stmt->errorInfo() . "\n";
        return false;
    } else {
        return $db->lastInsertId();
    }
}
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

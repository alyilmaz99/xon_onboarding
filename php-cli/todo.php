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

if (isset($argv[1]) &&  isset($argv[2]) && ($argv[1] == "add" ||  $argv[1] == "-a")) {

    $content = $argv[2];
    $taskId = addNewTask($content);
    if ($taskId) {
        $data = getListWithID($taskId);
    }
    if ($data) {
        maskData($data);
    }
}
if (isset($argv[1]) &&  isset($argv[2]) && ($argv[1] == "remove" ||  $argv[1] == "-r")) {


    if (isset($argv[2]) && $argv[2] == "all") {

        $return =  deleteAllTasks();
        if ($return) {
            printf("\033[92mTüm tasks başarıyla silindi!\033[0m \n");
        } else {
            printf("\033[31mTüm tasks başarıyla silinemedi!\033[0m \n");
        }
    } else {
        $content = $argv[2];
        $taskId = deleteTask($content);
        if (!$taskId) {
            printf("\033[31mID: $content başarıyla silinemedi!\033[0m \n");
        } else {
            $data = getList();
            if ($data) {
                maskData($data);
            }
            printf("\033[92mID: $content başarıyla silindi!\033[0m \n");
        }
    }
}

if (!isset($argv[1]) || $argv[1] === "help" || $argv[1] === "-h") {
    help();
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
    $mask = "\033[33m| %-5.5s | %-50.50s | %-10s |\033[0m \n";
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
function deleteTask($id)
{
    global $db;
    $check = getListWithID($id);
    if (!$check) {
        printf("\033[31mTask Bulunamadı Bulunamadı!\033[0m \n");
        return false;
    } else {
        $sql = "DELETE FROM task WHERE id = :id";

        $stmt = $db->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            return false;
        } else {
            return true;
        }
    }
}
function deleteAllTasks()
{
    global $db;

    $sql = "TRUNCATE TABLE task;";

    $stmt = $db->prepare($sql);

    if (!$stmt->execute()) {
        return false;
    } else {
        return true;
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

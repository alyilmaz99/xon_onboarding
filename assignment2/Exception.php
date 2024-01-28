<?php
/*
$file = fopen("test.txt", "r");
echo fread($file, filesize('test.txt'));
*/
class UserFriendlyException extends Exception {
    public function errorMessage() {
        $errorMsg = "File is not found \n";
        return $errorMsg;
    }
}

function openAndEchoFunction($fileName) {
    try {
        if (!file_exists($fileName)) {
            throw new UserFriendlyException($fileName);
        }

        $file = fopen($fileName, "r");
        
        if ($file === false) {
            throw new UserFriendlyException($fileName);
        }

        echo fread($file, filesize($fileName));
        fclose($file);
    } catch (UserFriendlyException $e) {
        echo $e->errorMessage();
    }
}

openAndEchoFunction('test.php');

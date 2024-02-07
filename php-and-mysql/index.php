<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include_once "./Database.php";
DB::Init();

if (!isset($_SESSION['user_id'])) {
    exit("Unauthorized");
}

$sql = "SELECT * FROM todo WHERE user_id = ?";
$stmt = DB::get()->stmt_init();

if (!$stmt->prepare($sql)) {
    die("SQL error: " . DB::get()->error);
}

$stmt->bind_param("s", $_SESSION["user_id"]);
if (!$stmt->execute()) {
    die("SQL error: " . $stmt->error . " Error number: " . DB::get()->errno);
} else {
    $result = $stmt->get_result();
    $todo = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="todo.css">

    <title>TODO</title>
</head>

<body>
    <div id="myDIV" class="header">
        <h2>XON TODO</h2>
        <input type="text" id="myInput" placeholder="Title...">
        <span onclick="newElement()" class="addBtn">Add</span>
    </div>

    <ul id="myUL">
        <?php foreach ($todo as $item): ?>
        <li><?php echo htmlspecialchars($item['task']); ?><span class="close">&times;</span></li>
        <?php endforeach;?>
    </ul>

    <script>
    var close = document.getElementsByClassName("close");
    var i;
    for (i = 0; i < close.length; i++) {
        close[i].onclick = function() {
            var div = this.parentElement;
            div.style.display = "none";
        }
    }

    function newElement() {
        var inputValue = document.getElementById("myInput").value;
        if (inputValue === '') {
            alert("You must write something!");
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "todo.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("myUL").innerHTML += "<li>" + inputValue +
                    "<span class='close'>&times;</span></li>";
            }
        };
        xhr.send("todo=" + inputValue);

        document.getElementById("myInput").value = "";
    }
    </script>
</body>

</html>
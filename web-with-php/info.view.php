<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (isset($_POST['destroy_session']) || !isset($_SESSION['name'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

if (isset($_POST['change_theme'])) {
    if (!isset($_COOKIE["theme"])) {
        setcookie('theme', 'dark', time() + 86400 * 30, "/");
        header("Location: index.php");
        exit();
    } else if ($_COOKIE['theme'] == 'dark') {
        setcookie('theme', 'light', time() + 86400 * 30, "/");
        header("Location: index.php");
        exit();
    } else if ($_COOKIE['theme'] == 'light') {
        setcookie('theme', 'dark', time() + 86400 * 30, "/");
        header("Location: index.php");
        exit();
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info View</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?php echo isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark' ?  "dark.css" : "style.css"; ?>">
</head>

<body>
    <?php echo "Theme is: {$_COOKIE['theme']}"; ?>
    <div class="container">
        <div class="col">
            <table>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Website</th>
                </tr>
                <tr>
                    <td><?php echo $_SESSION['name']; ?></td>
                    <td><?php echo $_SESSION['email']; ?></td>
                    <td><?php echo $_SESSION['age']; ?></td>
                </tr>
            </table>

            <form class="info-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input name="destroy_session" type="submit" class="submit" value="Destroy Session">
                <input name="change_theme" class="submit" type="submit" value="Change Theme">

            </form>
        </div>

    </div>

</body>

</html>
<?php
$db_host = "localhost";
$db_username = "root";
$db_password = "Ali.2901";
$db_name = "injection";

$connection = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$connection) {
    die("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    /*
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    */
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM user WHERE username='$username' AND password='$password'";

    $result = mysqli_query($connection, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        die("Hoş geldiniz " . $row['username']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection</title>
</head>

<body>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="username" placeholder="Kullanıcı Adı">
        <br>
        <input type="password" name="password" placeholder="Şifre">
        <br>
        <input type="submit" name="submit" value="Giriş">
    </form>
</body>

</html>
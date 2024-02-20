<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: signin.php");
} else {
    $url = "localhost/xon_onboarding/CRUD/api/user";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


    $resp = curl_exec($curl);
    curl_close($curl);


    $resp = json_decode($resp, true);
    if ($resp["status"] == false) {
        echo $resp["message"];
        echo $resp["data"];
        $time_start = microtime(true);
        header("refresh:3; url=index.php");
    } else if ($resp["status"] == true) {
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_destroy();
    header("Location: signin.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: blanchedalmond;
        }

        table {

            border-style: solid;
            padding: 50px;
            border-width: 2px;

            border-color: antiquewhite;

        }
    </style>
</head>

<body>
    <h1>Hos Geldin <?= $resp["data"][0][1] ?></h1>

    <table>
        <tr>
            <th>Number</th>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Action</th>
        </tr>

        <?php
        $counter = 0;
        foreach ($resp['data'] as $rows) : ?>
            <tr>

                <td><?php echo ++$counter; ?></td>
                <td> <?php echo $rows[0]; ?></td>
                <td> <?php echo $rows[1]; ?></td>
                <td> <?php echo $rows[2]; ?></td>
                <?php echo "<td><a href=\"editProfile.php?id=$rows[0]\" style=\"text-decoration: none\"><i class=\"fa fa-edit\"></i></a></td>";

                ?>
                <?php echo "<td><a href=\"editProfile.php?delete=true&id=$rows[0]\" style=\"text-decoration: none\"><i class=\"fa fa-trash-o\"></i></a></td>";
                ?>

            </tr>
        <?php endforeach; ?>

    </table>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <input class="submit" type="submit" value="LOGOUT">
    </form>
</body>

</html>
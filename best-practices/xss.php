<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>xss</title>
</head>

<body>
    <h1>xss</h1>
    <form method="GET">
        <label for="name">xss:</label>
        <input type="text" id="xss" name="xss">
        <button type="submit">Submit</button>
    </form>
    <?php
    $xss = $_GET['xss'];
    $xss_0 = htmlspecialchars($xss);

    echo "<p>Hello, $xss_0!</p>";
    ?>
</body>

</html>
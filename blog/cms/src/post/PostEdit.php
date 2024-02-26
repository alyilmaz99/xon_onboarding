<?php
session_start();
if (!isset($_SESSION["is_logged"])) {
    header("Location: login");
}
?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Edit</title>
    <html lang="en">

    <link rel="stylesheet" href="../../src/components/style.css">
    <link rel="stylesheet" href="../../src/components/dashboard.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<body>

    <div class="main">
        <?php require_once("src/components/Navbar.php"); ?>

        <div class="center">
            <div class="top-container">

                <div class="top-titles">
                    <h1 class="dashboard-title">Post Edit</h1>
                </div>
                <div class="profile-image">
                    <img class="user-image" id="user-image" src="" alt="profile-image">
                </div>

            </div>


        </div>

        <script>
            var currentPage = window.location.pathname.split('/');

            $(document).ready(function() {
                $.get("../../../api/user/<?= $_SESSION["user_id"] ?>", function(data, status) {
                    if (status == "success") {
                        if (data["data"]["user_image"] == null) {
                            $("#user-image").attr("src", "../../../assets/images/Avatar.png");
                        } else {
                            $("#user-image").attr("src", "../../../api/" + data["data"]["user_image"]);
                        }

                    }
                });
                $.get("../../../api/post/" + currentPage[6], function(data, status, xhr) {
                    if (status == "success" && data.status) {
                        posts = data.data;
                        console.log(posts);
                    }
                });


            });
        </script>
</body>

</html>
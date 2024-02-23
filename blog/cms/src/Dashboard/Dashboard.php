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
    <title>Dashboard</title>
    <html lang="en">

    <link rel="stylesheet" href="src/components/style.css">
    <link rel="stylesheet" href="src/components/dashboard.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<body>

    <div class="main">
        <?php require_once("src/components/Navbar.php"); ?>

        <div class="center">
            <div class="top-container">

                <div class="top-titles">
                    <h1 class="dashboard-title">Dashboard</h1>
                    <h3 id="welcome-text" class="welcome-text">Merhaba </h3>
                </div>
                <div class="profile-image">
                    <img src="assets/images/Avatar.png" alt="profile-image">
                </div>

            </div>
            <div class="reports">
                <div class="report-container">
                    <div class="post-report">
                        <span class="total-number">
                            <span id="post-number"></span>
                        </span>
                        <span class="total-posts-text">
                            Total Posts
                        </span>
                    </div>
                </div>
                <hr class="report-hr">
                <div class="report-container">
                    <div class="post-report">
                        <span class="total-number">
                            <span id="category-number"></span>
                        </span>
                        <span class="total-posts-text">
                            Total Category
                        </span>
                    </div>
                </div>
                <hr class="report-hr">
                <div class="report-container">
                    <div class="post-report">
                        <span class="total-number">
                            100
                        </span>
                        <span class="total-posts-text">
                            Total Comments
                        </span>
                    </div>
                </div>
                <hr class="report-hr">
                <div class="report-container">
                    <div class="post-report">
                        <span class="total-number">
                            100
                        </span>
                        <span class="total-posts-text">
                            Total Reads
                        </span>
                    </div>
                </div>
            </div>

            <div class="view-statics">

            </div>
            <div class="popular-posts">

            </div>

        </div>
    </div>
    <script>
        $(document).ready(function() {
            $.get("../api/user/<?= $_SESSION["user_id"] ?>", function(data, status) {
                if (status == "success") {
                    console.log(data);
                    console.log(data["data"]["name"]);
                    $("#welcome-text").append(data["data"]["name"]);
                }
            });
        });
        $(document).ready(function() {
            $.get("../api/post", function(data, status) {
                if (status == "success") {
                    console.log(data["data"][0]["total"]);
                    $("#post-number").text(data["data"][0]["total"]);
                }
            });
        });
        $(document).ready(function() {
            $.get("../api/category", function(data, status) {
                if (status == "success") {
                    console.log(data["data"]);
                    $("#category-number").text(data["data"][0]["total"]);
                }
            });
        });
    </script>
</body>

</html>
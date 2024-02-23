<?php
session_start();
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="src/Dashboard/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>
    <section class="nav-bar">
        <ul id="navbar-ul">

            <li class="navbar-li"><a href="#"> <span><img src="assets/svg/home-select.svg" /> </span>
                    <span class="list-text">Dashboard</span></a></li>
            <li class="navbar-li"><a href="#"><span><img src="assets/svg/post.svg" /> </span>
                    <span class="list-text">Posts</span></a></li>
            <li class="navbar-li"><a href="#" class="active"><span><img src="assets/svg/documents.svg" /></span>
                    <span class="list-text">Categories</span></a></li>

            <li class="navbar-li"><a href="#"><span><img src="assets/svg/message.svg" /> </span>
                    <span class="list-text">Comments</span></a></li>
            <li class="navbar-li"><a href="#"><span><img src="assets/svg/user.svg" />
                        <span class="list-text">Profile</span></a></li>
        </ul>
    </section>
    <script>
        $(document).ready(function() {
            var currentPage = window.location.pathname.split('/').pop().toLowerCase();
            $("#navbar-ul li").each(function() {
                var text = $(this).find("span.list-text").text().toLowerCase();
                if (text == currentPage) {
                    $(this).siblings().find("span").removeClass("selected");
                    $(this).find("span").addClass("selected");
                }
            });

            $('#navbar-ul li').click(function(e) {
                $('#navbar-ul li span').removeClass("selected");
                $(this).find("span").addClass("selected");
            });
        });
    </script>

</body>

</html>
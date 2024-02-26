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
    <title>Create Post</title>
    <html lang="en">

    <link rel="stylesheet" href="../src/components/style.css">
    <link rel="stylesheet" href="../src/components/dashboard.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<body>

    <div class="main">
        <?php require_once("src/components/Navbar.php"); ?>

        <div class="center">
            <div class="top-container">

                <div class="top-titles">
                    <h1 class="dashboard-title">Create Post</h1>
                </div>
                <div class="profile-image">
                    <img class="user-image" id="user-image" src="" alt="profile-image">
                </div>

            </div>
            <div class="post-create-body">
                <div class="editor-top">
                    <div class="editor-actions">
                        <button type="button" id="editor-bold" class="editor-button">Bold</button>
                        <button type="button" id="italic" class="editor-button">Italic</button>
                        <button type="button" id="toUpperCase" class="editor-button">ToUpperCase</button>
                        <button type="button" id="toLowerCase" class="editor-button ">ToLowerCase</button>
                        <button type="button" id="align-left" class="editor-button fas fa-align-left"></button>
                        <button type="button" id="align-center" class="editor-button fas fa-align-center"></button>
                        <button type="button" id="align-right" class="editor-button fas fa-align-right"></button>
                    </div>
                    <div class="editor-title">
                        <div class="title">
                            <label for="title">Title:</label><br>
                            <input type="text" class="text-field" id="title" name="title" value=""><br>
                            <label for="slug">Slug:</label><br>
                            <input type="text" class="slug-field" id="slug" name="slug" value=""><br>
                            <button type="button" id="save" class="save-button">Save</button>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <script>
            $(document).ready(function() {
                $.get("../../api/user/<?= $_SESSION["user_id"] ?>", function(data, status) {
                    if (status == "success") {
                        if (data["data"]["user_image"] == null) {
                            $("#user-image").attr("src", "../assets/images/Avatar.png");

                        } else {
                            $("#user-image").attr("src", "../../api/" + data["data"]["user_image"]);
                        }

                    }
                });




            });
        </script>
</body>

</html>
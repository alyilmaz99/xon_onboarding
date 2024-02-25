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
    <title>Post</title>
    <html lang="en">

    <link rel="stylesheet" href="src/components/style.css">
    <link rel="stylesheet" href="src/components/dashboard.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<body>

    <div class="main">
        <?php require_once("src/components/Navbar.php"); ?>

        <div class="center">
            <div class="top-container">

                <div class="top-titles">
                    <h1 class="dashboard-title">Post</h1>
                </div>
                <div class="profile-image">
                    <img class="user-image" id="user-image" src="" alt="profile-image">
                </div>

            </div>
            <div class="post-body">
                <table class="post-table">
                    <thead class="post-tr">
                        <th>ID</th>
                        <th>Title</th>
                        <th>Details</th>
                        <th>Likes</th>
                        <th>Readed</th>
                        <th>Is Active</th>
                        <th>Updated At</th>
                    </thead>
                    <tbody>
                        <tr class="post-tr-td">
                        </tr>
                    </tbody>



                </table>
            </div>

        </div>

        <script>
            $(document).ready(function() {
                $.get("../api/user/<?= $_SESSION["user_id"] ?>", function(data, status) {
                    if (status == "success") {
                        if (data["data"]["user_image"] == null) {
                            $("#user-image").attr("src", "assets/images/Avatar.png");

                        } else {
                            $("#user-image").attr("src", "../api/" + data["data"]["user_image"]);
                        }

                    }
                });
                $(document).ready(function() {
                    $.get("../api/post", function(data, status) {
                        if (status == "success" && data.status) {

                            var posts = data.data;

                            for (var i = 0; i < posts.length; i++) {
                                var post = posts[i];
                                var newRow = $("<tr>").addClass("post-tr-td");
                                newRow.append($("<td>").text(post.id));
                                newRow.append($("<td>").text(post.title));
                                newRow.append($("<td>").text(post.details));
                                newRow.append($("<td>").text(post.likes));
                                newRow.append($("<td>").text(post.readed));
                                newRow.append($("<td>").text(post.is_active));
                                newRow.append($("<td>").text(post.updated_at));

                                var actionButtons = $("<td>");
                                var editButton = $("<button>").addClass("edit-button");
                                var editIcon = $("<i>").addClass("fas fa-edit");

                                editButton.append(editIcon);
                                var deleteButton = $("<button>").addClass("delete-button");
                                var deleteIcon = $("<i>").addClass("fas fa-trash-alt");
                                deleteButton.append(deleteIcon);
                                deleteButton.attr("id", "delete-button");
                                editButton.attr("id", "edit-button");
                                actionButtons.append(editButton);
                                actionButtons.append(deleteButton);
                                newRow.append(actionButtons);
                                $(".post-table tbody").append(newRow);
                            }


                        }
                    });
                });

                $(document).on("click", ".delete-button", function() {
                    var postId = $(this).closest("tr").find("td:first").text();
                    $.ajax({
                        url: "../api/post/" + postId,
                        type: "DELETE",
                        success: function(data, status) {
                            if (status == 404) {
                                console.log("Post bulunamadı");
                            } else {
                                console.log("Post başarıyla silindi");
                                location.reload();
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            console.log("Silme işlemi başarısız: " + textStatus);
                        }
                    });
                });


            });
        </script>
</body>

</html>
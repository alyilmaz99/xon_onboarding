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
                        <th>Is Active</th>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Details</th>
                        <th>Likes</th>
                        <th>Readed</th>
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
                var post;
                $(document).ready(function() {
                    $.get("../api/post", function(data, status, xhr) {
                        if (status == "success" && data.status) {

                            posts = data.data;

                            for (var i = 0; i < posts.length; i++) {
                                var post = posts[i];

                                var newRow = $("<tr>").addClass("post-tr-td");
                                var activeDot = $("<span>").addClass("table-dot");
                                var activeIcon = $("<i>").addClass("fas fa-dot-circle");
                                activeDot.append(activeIcon);
                                post.is_active == 1 ? activeDot.addClass("dot-active") : activeDot.addClass("dot-inactive");
                                newRow.append(activeDot);
                                newRow.append($("<td>").text(post.id));
                                newRow.append($("<td>").text(post.title));
                                newRow.append($("<td>").text(post.details));
                                newRow.append($("<td>").text(post.likes));
                                newRow.append($("<td>").text(post.readed));
                                newRow.append($("<td>").text(post.updated_at));

                                var actionButtons = $("<td>");
                                var activateIcon;
                                post.is_active == 1 ? activateIcon = $("<i>").addClass("fas fa-times") : activateIcon = $("<i>").addClass("fas fa-check");


                                var activateButton = $("<button>").addClass("activate-button");
                                var editButton = $("<button>").addClass("edit-button");
                                var editIcon = $("<i>").addClass("fas fa-edit");
                                activateButton.append(activateIcon);
                                editButton.append(editIcon);
                                var deleteButton = $("<button>").addClass("delete-button");
                                var deleteIcon = $("<i>").addClass("fas fa-trash-alt");
                                deleteButton.append(deleteIcon);
                                deleteButton.attr("id", "delete-button");
                                editButton.attr("id", "edit-button");
                                activateButton.attr("id", "edit-activate");

                                actionButtons.append(editButton);
                                actionButtons.append(deleteButton);
                                actionButtons.append(activateButton);
                                newRow.append(actionButtons);
                                $(".post-table tbody").append(newRow);
                            }


                        }
                    }).done(function() {

                        $(document).on("click", ".activate-button", function() {
                            var postId = $(this).closest("tr").find("td").eq(0).text();

                            var activate = $(this).closest("tr").find("span").eq(0).attr("class");
                            console.log(activate);
                            activate == "table-dot dot-active" ? activate = 0 : activate = 1;
                            console.log(activate);
                            var updateData = {
                                is_active: activate,

                            };
                            $.ajax({
                                url: "../api/post/" + postId,
                                type: "PUT",
                                data: JSON.stringify(updateData),
                                success: function(data, status) {
                                    if (status == 404) {
                                        console.log("Post bulunamadı");
                                    } else {
                                        console.log("Post başarıyla Değiştirildi!");
                                        location.reload();
                                    }
                                },
                                error: function(xhr, textStatus, errorThrown) {
                                    console.log("Update işlemi başarısız: " + textStatus);
                                }
                            });
                        });

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
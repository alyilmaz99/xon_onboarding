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
    <title>Category</title>
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
                    <h1 class="dashboard-title">Category</h1>
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
                        <th>Name</th>
                        <th>Detail</th>
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
                var category;
                $(document).ready(function() {
                    $.get("../api/category", function(data, status, xhr) {
                        if (status == "success" && data.status) {

                            categories = data.data;

                            for (var i = 0; i < categories.length; i++) {
                                var category = categories[i];

                                var newRow = $("<tr>").addClass("post-tr-td");
                                var activeDot = $("<span>").addClass("category-table-dot");
                                var activeIcon = $("<i>").addClass("fas fa-dot-circle");
                                activeDot.append(activeIcon);
                                category.is_active == 1 ? activeDot.addClass("dot-active") : activeDot.addClass("dot-inactive");
                                newRow.append(activeDot);
                                newRow.append($("<td>").text(category.id));
                                newRow.append($("<td>").text(category.name));
                                newRow.append($("<td>").text(category.detail));
                                newRow.append($("<td>").text(category.updated_at));

                                var actionButtons = $("<td>");
                                var activateIcon;
                                category.is_active == 1 ? activateIcon = $("<i>").addClass("fas fa-times") : activateIcon = $("<i>").addClass("fas fa-check");


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
                            var categoryID = $(this).closest("tr").find("td").eq(0).text();
                            var activateButton = $(this);
                            var activateIcon = activateButton.find("i");
                            var activate = $(this).closest("tr").find("span").eq(0).hasClass("dot-active") ? 0 : 1;

                            var updateData = {
                                is_active: activate,
                            };

                            $.ajax({
                                url: "../api/category/" + categoryID,
                                type: "PUT",
                                data: JSON.stringify(updateData),
                                success: function(data, status) {
                                    if (status == 404) {
                                        console.log("Post bulunamadı");
                                    } else {
                                        console.log("Post başarıyla Değiştirildi!");
                                        if (activate == 1) {
                                            activateIcon.removeClass("fa-check").addClass("fa-times");
                                            activateButton.closest("tr").find("span").eq(0).removeClass("dot-inactive").addClass("dot-active");
                                        } else {
                                            activateIcon.removeClass("fa-times").addClass("fa-check");
                                            activateButton.closest("tr").find("span").eq(0).removeClass("dot-active").addClass("dot-inactive");
                                        }
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
                    var categoryID = $(this).closest("tr").find("td:first").text();
                    $.ajax({
                        url: "../api/category/" + categoryID,
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
                $(document).on("click", ".edit-button", function() {
                    var categoryID = $(this).closest("tr").find("td:first").text();
                    location.replace("category/edit/" + categoryID);
                });
            });
        </script>
</body>

</html>
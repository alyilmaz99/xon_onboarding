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
    <title>Create Category</title>
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
                    <h1 class="dashboard-title">New Category</h1>
                </div>
                <div class="profile-image">
                    <img class="user-image" id="user-image" src="" alt="profile-image">
                </div>

            </div>
            <div class="category-create-body">


                <div class="category-row">
                    <label for="name">Name:</label><br>
                    <input type="text" class="category-field" id="name" name="name" value="">

                    <label for="detail">Detail:</label><br>
                    <textarea type="text" class="category-field category-detail" id="detail" name="detail" value=""></textarea>
                </div>
                <div class="category-row">
                    <label for="is_active">Is Active:</label><br>
                    <select class="category-field" name="is_active" id="is_active">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    <label for="created_by">Created_by:</label><br>

                    <select class="category-field" name="created_by" id="created_by">

                    </select>

                </div>
                <div class="category-row">

                    <button type="button" id="save" class="save-button">Save</button>

                </div>


            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {

            var currentPage = window.location.pathname.split('/');

            $.get("../../../api/user/<?= $_SESSION["user_id"] ?>", function(data, status) {
                if (status == "success") {
                    if (data["data"]["user_image"] == null) {
                        $("#user-image").attr("src", "../../assets/images/Avatar.png");
                    } else {
                        $("#user-image").attr("src", "../../../api/" + data["data"]["user_image"]);
                    }
                }
            });
            $.get("../../../api/user", function(data, status) {
                if (status == "success") {

                    for (var i = 0; i < data.data.length; i++) {
                        $("#created_by").append("<option value='" + data.data[i].id + "'>" + data.data[i].name + "</option>");
                    }

                }
            });
            $.get("../../../api/category/" + currentPage[6], function(data, status) {
                if (status == "success") {
                    console.log(data);
                    $("#created_by").val(data.data["created_by"]).change();
                    $("#name").val(data.data["name"]);
                    $("#detail").val(data.data["detail"]);
                    $("#is_active").val(data.data["is_active"]).change();

                }
            });
            $("#save").on("click", function() {
                var postData = {
                    name: $("#name").val(),
                    detail: $("#detail").val(),
                    is_active: $("#is_active").find(":selected").val(),
                    user_id: $("#created_by").find(":selected").val(),
                };
                console.log(postData);
                $.ajax({
                    url: "../../../api/category/" + currentPage[6],
                    type: "PUT",
                    data: JSON.stringify(postData),
                    success: function(data, status) {
                        if (status == 404) {
                            console.log("Category yüklenemedi");
                        } else {
                            console.log("Category başarıyla Yüklendi!");
                            location.replace("../../category");
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log("Category işlemi başarısız: " + textStatus);
                    }
                });
            });

        });
    </script>
</body>

</html>
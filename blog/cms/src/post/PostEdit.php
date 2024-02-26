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
    <title>Edit Post</title>
    <html lang="en">

    <link rel="stylesheet" href="../../src/components/style.css">
    <link rel="stylesheet" href="../../src/components/dashboard.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

<body>

    <div class="main">
        <?php require_once("src/components/Navbar.php"); ?>

        <div class="center">
            <div class="top-container">

                <div class="top-titles">
                    <h1 class="dashboard-title">Edit Post</h1>
                </div>
                <div class="profile-image">
                    <img class="user-image" id="user-image" src="" alt="profile-image">
                </div>

            </div>
            <div class="post-create-body">
                <div class="editor-top">
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

                <div class="textarea-box">
                    <textarea class="textarea">
                </textarea>
                </div>

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
                    post = data.data;
                    console.log(post);
                    $("#slug").prop("value", post.slug);
                    $("#title").prop("value", post.title);

                }
            }).done(function() {
                var simplemde = new SimpleMDE({
                    autofocus: true,
                    indentWithTabs: true,

                    renderingConfig: {
                        singleLineBreaks: false,
                        codeSyntaxHighlighting: true,
                    },
                    initialValue: post.content,
                    placeholder: "Type here...",
                    element: $(".textarea")[0],
                });
                $("#save").on("click", function() {
                    var text = simplemde.value();

                    var postData = {
                        title: $("#title").val(),
                        user_id: <?php echo $_SESSION["user_id"]; ?>,
                        slug: $("#slug").val(),
                        details: text.length > 100 ?
                            text.substr(0, text.lastIndexOf(' ', 97)) + '...' : "null",
                        content: text,
                        likes: post.likes,
                        readed: post.readed,
                        publishing_date: post.publishing_date,
                        is_active: post.is_active,
                    };
                    $.ajax({
                        url: "../../../api/post/" + currentPage[6],
                        type: "PUT",
                        data: JSON.stringify(postData),
                        success: function(data, status) {
                            if (status == 404) {
                                console.log("Post Güncellenemedi");
                            } else {
                                console.log("Post başarıyla güncellendi!");
                                location.reload();
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            console.log("Güncelleme işlemi başarısız: " + textStatus);
                        }
                    });
                });

            });

        });
    </script>
</body>

</html>
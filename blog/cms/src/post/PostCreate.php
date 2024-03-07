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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

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
                    <div class="post-row">
                        <div class="post-size-fixer">
                            <label for="title">Title:</label><br>
                            <input type="text" class="text-field" id="title" name="title" value=""><br>
                            <label for="slug">Slug:</label><br>
                            <input type="text" class="slug-field" id="slug" name="slug" value=""><br>
                            <button type="button" id="save" class="save-button">Save</button>

                        </div>
                    </div>

                </div>
                <div class="editor-top">
                    <div class="post-row">
                        <label for="is_active">Is Active:</label><br>

                        <select class="category-field" name="is_active" id="is_active">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        <label for="created_by">Created By:</label><br>

                        <select class="category-field" name="created_by" id="created_by">

                        </select>
                        <label for="category">Category:</label><br>

                        <select class="category-field" name="category" id="category">

                        </select>
                        <div class="post-image">
                            <label for="category">Thumbnail:</label><br>

                            <label class="thumbnail-label" id="thumbnail-label" for="thumbnail">Upload<input class="thumbnail" type="file" name="thumbnail" id="thumbnail"> </label>

                        </div>
                    </div>


                </div>
                <div class="thumbnail-container">
                    <img class="thumbnail-preview" id="thumbnail-preview" src="#">
                </div>
                <div class="textarea-box">
                    <textarea class="textarea">
                </textarea>
                </div>

            </div>
        </div>
    </div>


    <script>
        var simplemde = new SimpleMDE({
            autofocus: true,
            indentWithTabs: true,

            renderingConfig: {
                singleLineBreaks: false,
                codeSyntaxHighlighting: true,
            },
            placeholder: "Type here...",
            element: $(".textarea")[0],
        });


        $('#thumbnail').on('change', function() {
            var file = this.files[0];

            console.log("Seçilen dosya adı: " + file.name);

            var reader = new FileReader();
            reader.onload = function(e) {
                $('#thumbnail-preview').attr('src', e.target.result);
                $('#thumbnail-preview').attr('style', "display: flex;");
            }
            reader.readAsDataURL(file);
        });


        $(document).ready(function() {
            $("#blog-content").focus(function() {
                var input = this;
                setTimeout(function() {
                    input.setSelectionRange(0, 0);
                }, 0);
            });
            $.get("../../api/user", function(data, status) {
                if (status == "success") {

                    for (var i = 0; i < data.data.length; i++) {
                        $("#created_by").append("<option value='" + data.data[i].id + "'>" + data.data[i].name + "</option>");
                    }

                }
            });

            $.get("../../api/category", function(data, status) {
                if (status == "success") {

                    for (var i = 0; i < data.data.length; i++) {
                        $("#category").append("<option value='" + data.data[i].id + "'>" + data.data[i].name + "</option>");
                    }

                }
            });

            $.get("../../api/user/<?= $_SESSION["user_id"] ?>", function(data, status) {
                if (status == "success") {
                    if (data["data"]["user_image"] == null) {
                        $("#user-image").attr("src", "../assets/images/Avatar.png");
                    } else {
                        $("#user-image").attr("src", "../../api/" + data["data"]["user_image"]);
                    }
                }
            });

            $("#save").on("click", function() {
                var text = simplemde.value();

                var postData = {
                    title: $("#title").val(),
                    user_id: <?php echo $_SESSION["user_id"]; ?>,
                    slug: $("#slug").val(),
                    details: text.length > 100 ?
                        text.substr(0, text.lastIndexOf(' ', 97)) + '...' : "null",
                    content: simplemde.value(),
                    likes: 0,
                    readed: 0,
                    publishing_date: "10.12.1999",
                    is_active: 0,
                };
                $.ajax({
                    url: "../../api/post",
                    type: "POST",
                    data: JSON.stringify(postData),
                    success: function(data, status) {
                        if (status == 404) {
                            console.log("Post yüklenemedi");
                        } else {
                            console.log(data.message)
                            var categoryPostData = {
                                post_id: data.message,
                                user_id: <?php echo $_SESSION["user_id"]; ?>
                            };
                            console.log("Post başarıyla Yüklendi!");
                            $.ajax({
                                url: "../../api/category/post/" + $("#category").val(),
                                type: "POST",
                                data: JSON.stringify(categoryPostData),
                                success: function(data, status) {
                                    if (status == 404) {
                                        console.log("Category post yüklenemedi");
                                    } else {
                                        console.log("category post başarıyla Yüklendi!");

                                    }
                                },
                                error: function(xhr, textStatus, errorThrown) {
                                    console.log("category post işlemi başarısız: " + data);
                                }
                            });
                            var fd = new FormData();
                            var file = $("#thumbnail").prop('files')[0];
                            fd.append('thumbnail', file);

                            $.ajax({
                                url: '../../api/post/image/' + data.message,
                                type: 'POST',
                                data: fd,
                                contentType: false,
                                processData: false,
                                success: function(response) {

                                },
                                error: function(xhr, textStatus, errorThrown) {
                                    console.log("Dosya yükleme işlemi başarısız: " + errorThrown);
                                }
                            });

                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log("Post işlemi başarısız: " + textStatus);
                    }
                });

            });
        });
    </script>
</body>

</html>
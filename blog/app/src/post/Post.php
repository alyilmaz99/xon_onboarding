<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage</title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
    <div class="main">
        <div class="navbar">
            <div class="logo">
                <p> Ali Yılmaz</p>
            </div>
            <ul class="navbar-ul" id="navbar-ul">
                <li class="navbar-li"><a href=""> <span><img src="" class="nav-icon" /> </span>
                        <span class="list-text">Home</span></a></li>
                <li class="navbar-li"><a href=""><span><img src="" class="nav-icon" /> </span>
                        <span class="list-text">Blog</span></a></li>
                <li class="navbar-li"><a href=""><span><img src="" class="nav-icon" /></span>
                        <span class="list-text">Category</span></a></li>
                <li class="navbar-li"><a href=""><span><img src="" class="nav-icon" /> </span>
                        <span class="list-text">Contact</span></a></li>
                <li class="navbar-li"><a href=""><span><img src="" class="nav-icon" /> </span>
                        <span class="list-text">Search</span></a></li>
            </ul>
        </div>
        <div class="post-body">
            <div class="post-column">
                <div class="post-top">
                    <div class="post-category">

                    </div>
                    <div class="post-body-title">

                    </div>
                    <div class="post-top-date">
                        <span class="post-date">

                        </span>
                        <span class="post-writer">

                        </span>
                    </div>
                </div>
                <div class="post-content-body">
                    <div class="post-thumbnail">
                        <img class="thumbnail" src="" alt="post-thumbnail">
                    </div>
                    <div class="post-content">

                    </div>

                    <div class="comments">
                        comments
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">

        </div>
    </div>

    <script>
        $(document).ready(function() {
            var currentPage = window.location.pathname.split('/');
            $.get("../../api/postWithSlug/" + currentPage[5], function(data, status, xhr) {
                if (status == "success" && data.status) {
                    var post = data.data;
                    console.log(post);
                    $('.category-name').text(post.category_name);
                    $('.post-body-title').text(post.title);
                    $('.post-date').text(post.created_at);

                    $('.post-content').html(post.content);
                    $('.post-thumbnail img').attr('src', "../../api/" + post.thumbnail);
                    console.log(post.user_id);
                    $.get("../../api/user/" + post.user_id, function(data, status, xhr) {
                        if (status == "success" && data.status) {
                            var post = data.data;
                            console.log(post);

                            $('.post-writer').text(post.name);


                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
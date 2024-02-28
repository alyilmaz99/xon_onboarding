<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage</title>

    <link rel="stylesheet" href="assets/css/style.css">

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
        <div class="body">
            <div class="left-column">
                <div class="left-title">
                    <span class="left-title-span">
                        Selam Ben Ali!
                    </span>
                </div>
                <div class="left-content">
                    <span class="left-content-span">
                        Lorem ipsum dolor sit amet consectetur adipiscing elit, pellentesque congue dui fermentum ultricies odio imperdiet hendrerit, cursus dictumst mi facilisis curabitur dis. Risus enim ac montes viverra at sociis in. Volutpat nec fusce magnis aliquet duis vehicula, phasellus nascetur porta lacus tortor curae </span>
                </div>
                <div class="explore">
                    <div class="explore-text">
                        <span>
                            Explore Me
                        </span>
                    </div>
                    <div class="explore-icon">
                        <span>&#62;</span>

                    </div>
                </div>
            </div>
            <div class="right-column">
                <div class="right-title">
                    <p>Recent Posts</p>
                </div>
                <div class="right-category">
                    <ul class="body-ul" id="nody-ul">
                        <li class="left-arrow">
                            <span class="left-arrow">&#60;</span>
                        </li>
                        <li class="body-li"><a href=""> <span><img src="" class="nav-icon" /> </span>
                                <span class="list-text">Home</span></a></li>
                        <li class="body-li"><a href=""><span><img src="" class="nav-icon" /> </span>
                                <span class="list-text">Blog</span></a></li>
                        <li class="body-li"><a href=""><span><img src="" class="nav-icon" /></span>
                                <span class="list-text">Category</span></a></li>
                        <li class="body-li"><a href=""><span><img src="" class="nav-icon" /> </span>
                                <span class="list-text">Contact</span></a></li>
                        <li class="body-li"><a href=""><span><img src="" class="nav-icon" /> </span>
                                <span class="list-text">Search</span></a></li>
                        <li class="right-arrow">
                            <span class="right-arrow">&#62;</span>
                        </li>
                    </ul>
                </div>
                <div class="body-hr">
                    <hr>

                </div>
                <div class="recent-posts">
                    <div class="posts">

                    </div>
                    <div class="pages">
                        <p>Pages</p>
                    </div>
                </div>
            </div>

        </div>
        <div class="footer">

        </div>
    </div>

    <script>
        $(document).ready(function() {
            $.get("../api/post", function(data, status, xhr) {
                if (status == "success" && data.status) {
                    var posts = data.data;
                    var postContainer = $(".posts");

                    posts.forEach(function(post) {
                        var postDiv = $("<div class='post'></div>");
                        var postList1 = $(" <div class='post-list'></div>");
                        var postList2 = $(" <div class='post-list'></div>");
                        var dateSpan = $("<span class='date'>" + post.publishing_date + "</span>");
                        var postHr = $("<div class='post-hr'> <hr></div>")
                        var postTitleSpan = $("<span class='post-title'>" + post.title + "</span>");
                        postList1.append(dateSpan);
                        postList2.append(postTitleSpan);
                        postDiv.append(postList1);
                        postDiv.append(postList2);
                        postDiv.append(postHr);

                        postContainer.append(postDiv);
                    });
                }
            });
        });
    </script>
</body>

</html>
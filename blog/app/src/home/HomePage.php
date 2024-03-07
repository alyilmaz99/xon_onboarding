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
                <li class="navbar-li"><a href="home"> <span><img src="" class="nav-icon" /> </span>
                        <span class="list-text">Home</span></a></li>
                <li class="navbar-li"><a href="blog"><span><img src="" class="nav-icon" /> </span>
                        <span class="list-text">Blog</span></a></li>
                <li class="navbar-li"><a href="category"><span><img src="" class="nav-icon" /></span>
                        <span class="list-text">Category</span></a></li>
                <li class="navbar-li"><a href="contact"><span><img src="" class="nav-icon" /> </span>
                        <span class="list-text">Contact</span></a></li>

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
                    <ul class="body-ul" id="body-ul">
                        <li class="left-arrow">
                            <span class="left-arrow">&#60;</span>
                        </li>


                    </ul>
                </div>
                <div class="body-hr">
                    <hr>

                </div>
                <div class="recent-posts">
                    <div class="posts">

                    </div>
                    <div class="page-numbers">
                        <div class="number-row">


                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="footer">

        </div>
    </div>

    <script>
        $(document).ready(function() {
            createPosts(1);
            createCategory();

            $(document).on("click", ".page-number", function() {
                var pageNumber = $(this).text();

                $(".posts").empty();
                createPosts(pageNumber);
            });



            function createPageNumbers(totalPages) {
                var pageNumbersContainer = $(".page-numbers ");
                pageNumbersContainer.empty();
                for (var i = 1; i <= totalPages; i++) {
                    var pageNumberButton = $("<button>").addClass("page-number").text(i);
                    pageNumbersContainer.append(pageNumberButton);
                }
            }

            function createPosts(page) {
                $.get("../api/post/page/" + page, function(data, status, xhr) {
                    if (status == "success" && data.status) {
                        var posts = data.data;
                        var postContainer = $(".posts");
                        posts.posts.forEach(function(post) {
                            var postDiv = $("<div class='post'></div>");
                            var postList1 = $("<div class='post-list'></div>");
                            var postList2 = $("<div class='post-list'></div>");
                            var postLink = $("<a>").attr("href", post.slug).text(post.title);

                            var postTitleSpan = $("<span class='post-title'></span>");
                            postTitleSpan.append(postLink);

                            var dateSpan = $("<span class='date'>" + post.created_at + "</span>");
                            var postHr = $("<div class='post-hr'> <hr></div>");

                            postList1.append(dateSpan);
                            postList2.append(postTitleSpan);

                            postDiv.append(postList1);
                            postDiv.append(postList2);
                            postDiv.append(postHr);

                            postContainer.append(postDiv);
                        });
                        var pages = Math.ceil(posts.total_post / posts.perPage);
                        createPageNumbers(pages);
                    }
                });
            }



            function createCategory() {
                $(document).ready(function() {
                    $.get("../api/category", function(data, status, xhr) {
                        if (status == "success" && data.status) {
                            var categories = data.data;
                            var postContainer = $(".posts");
                            categories.forEach(function(category) {
                                var body_li = $(" <li class='body-li'> </li>");
                                var category_link = $(" <a href=''></a>");
                                var span = $("<span class='list-text'>" + category.name + "</span>");
                                category_link.append(span);
                                body_li.append(category_link);
                                $("#body-ul").append(body_li);
                            });
                            if (categories.length > 4) {
                                var arrow_span = $(" <span class = 'right-arrow' ></span>");
                                arrow_span.append("&#62;");
                                var arrow = $("<li class='right-arrow'></li>");
                                arrow.append(arrow_span);
                                $("#body-ul").append(arrow);
                            }
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>
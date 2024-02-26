<section class="nav-bar">
    <ul id="navbar-ul">

        <li class="navbar-li"><a href="dashboard"> <span><img src="" class="nav-icon" /> </span>
                <span class="list-text">Dashboard</span></a></li>
        <li class="navbar-li"><a href="post"><span><img src="" class="nav-icon" /> </span>
                <span class="list-text">Post</span></a></li>
        <li class="navbar-li"><a href="category"><span><img src="" class="nav-icon" /></span>
                <span class="list-text">Category</span></a></li>
        <li class="navbar-li"><a href="comments"><span><img src="" class="nav-icon" /> </span>
                <span class="list-text">Comments</span></a></li>
        <li class="navbar-li"><a href="profile"><span><img src="" class="nav-icon" /> </span>
                <span class="list-text">Profile</span></a></li>
        <li class="navbar-li"><a href="settings"><span><img src="" class="nav-icon" /> </span>
                <span class="list-text">Settings</span></a></li>
        <button class="post-button" id="new-post">&#9997; New Post</button>
    </ul>
</section>

<hr class="navbar-hr">

<script>
    $(document).ready(function() {
        var currentPage = window.location.pathname.split('/');
        $(".nav-icon").each(function() {
            var iconName = $(this).parent().next(".list-text").text().toLowerCase();
            var root = currentPage[5] != null ? currentPage[6] != null ? "../../assets/svg/" : "../assets/svg/" : "assets/svg/";
            $(this).attr("src", root + iconName + ".svg");
        });

        $("#navbar-ul li").each(function() {
            var text = $(this).find("span.list-text").text().toLowerCase();
            if (text == currentPage[4]) {
                $(this).siblings().find("span").removeClass("selected");
                $(this).find("span").addClass("selected");
            }
        });
        $("#new-post").on("click", function() {
            var root = currentPage[6] ? "../../" : "";
            location.replace(root + "post/new");
        });

        $('#navbar-ul li').click(function(e) {
            $('#navbar-ul li span').removeClass("selected");
            $(this).find("span").addClass("selected");
            e.preventDefault();
            var href = $(this).find('a').attr('href');
            var hrefRoot = currentPage[5] != null ? currentPage[6] != null ? "../../" : "../" : "";


            window.location.href = hrefRoot + href;

        });
    });
</script>
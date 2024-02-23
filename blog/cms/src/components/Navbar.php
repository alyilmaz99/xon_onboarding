<section class="nav-bar">
    <ul id="navbar-ul">

        <li class="navbar-li"><a href="dashboard"> <span><img src="assets/svg/home-select.svg" /> </span>
                <span class="list-text">Dashboard</span></a></li>
        <li class="navbar-li"><a href="post"><span><img src="assets/svg/post.svg" /> </span>
                <span class="list-text">Posts</span></a></li>
        <li class="navbar-li"><a href="category" class="active"><span><img src="assets/svg/documents.svg" /></span>
                <span class="list-text">Categories</span></a></li>

        <li class="navbar-li"><a href="#"><span><img src="assets/svg/message.svg" /> </span>
                <span class="list-text">Comments</span></a></li>
        <li class="navbar-li"><a href="profile"><span><img src="assets/svg/user.svg" />
                    <span class="list-text">Profile</span></a></li>
        <li class="navbar-li"><a href="settings"><span><img src="assets/svg/settings.svg" />
                    <span class="list-text">Settings</span></a></li>
    </ul>
</section>

<hr class="navbar-hr">

<script>
    $(document).ready(function() {
        var currentPage = window.location.pathname.split('/').pop().toLowerCase();
        $("#navbar-ul li").each(function() {
            var text = $(this).find("span.list-text").text().toLowerCase();
            if (text == currentPage) {
                $(this).siblings().find("span").removeClass("selected");
                $(this).find("span").addClass("selected");
            }
        });

        $('#navbar-ul li').click(function(e) {
            $('#navbar-ul li span').removeClass("selected");
            $(this).find("span").addClass("selected");
        });
    });
</script>
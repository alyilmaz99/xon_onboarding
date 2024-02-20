<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        .box {

            position: absolute;
            background-color: #abc;
            left: 50px;
            width: 90px;
            height: 90px;
            margin: 100px;
        }

        .selected {
            color: red;
        }

        .highlight {
            background: chartreuse;
        }

        ul {
            margin-left: 20px;
            color: blue;
        }

        li {
            cursor: default;
        }

        li.active {
            background: black;
            color: white;
        }

        span {
            color: red;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!--
    <script src="js/jquery-3.7.1.min.js"></script>
     -->
</head>


<body>
    <p id="test">Test p</p>
    <div class="hello">
        <p>hello1</p>
        <h1 id="hello2">hello2</h1>
        <h2>Fade</h2>
        <p id="hello-chat">Merhaba</p>
    </div>
    <div>
        <ul>
            <li>First</li>
            <li>Second</li>
            <li>Third</li>
            <li>4th</li>
            <li>5th</li>
            <li>6th</li>
            <li>7th</li>
    </div>
    <div></div>
    <nav>
        <p>test</p>
    </nav>
    <img id="image" width="50" height="50" src="https://upload.wikimedia.org/wikipedia/commons/b/b6/Image_created_with_a_mobile_phone.png" alt="Test">
    <div class="log"></div>

    <button id="left">&larr;</button>
    <button id="right">&rarr;</button>
    <button id="up">&uarr;</button>
    <button id="down">&darr;</button>
    <button id="reset">Reset</button>
    <div class="box"></div>
    <script>
        var collection = $("p").last();
        collection = collection.add(document.getElementById("test"));
        collection.addClass("selected highlight");
        $(document).ready(function() {
            $("p").clone().add("<span>REPEAT</span>").appendTo(document.body);

            $("#test").on("click", function() {
                $(this).slideUp();
                $("div").hide();


            });
            $("h1").hover(
                function() {
                    $(this).append($("<span>hi</span>"));
                },
                function() {
                    $(this).find("span").last().remove();
                }
            );
            $("h2").hover(function() {
                $(this).fadeOut(100);
                $(this).fadeIn(500);

            });

            uiFunctions(false);

            var navTrigger = false;
            $(document).keypress(function(e) {
                if (e.which == 13) {
                    if (navTrigger === false) {
                        $("nav").fadeIn(500);
                        $("div").fadeOut(500);
                        navTrigger = true;
                    } else if (navTrigger === true) {
                        $("nav").fadeOut(500);
                        $("div").fadeIn(500);

                        navTrigger = false;
                    }
                }
            });

        });


        $("#hello-chat").on("click", function() {
            var fontSize = parseInt($("body").css("font-size"));
            fontSize = fontSize + 60 + "px";
            $("#hello-chat").css("font-size", fontSize);
            $("#hello-chat").css("padding-top", 100 + "px");
        });
        $("li").each(function() {
            console.log(this.length);
        });
        jQuery.fn.LengthOf = function() {
            console.log(this.length);
        };
        $("test").LengthOf();

        function uiFunctions(isActive) {
            if (isActive === true) {
                $("li")
                    .odd()
                    .hide()
                    .end()
                    .even()
                    .hover(function() {
                        $(this)
                            .toggleClass("active")
                            .next()
                            .stop(true, true)
                            .slideToggle();
                    });
            } else {
                $("li").hide();
            }
        }
        $("#image").on("click", function() {
            $("#image").animate({
                opacity: 0.25,
                left: "+=50",
                height: "toggle"
            }, 2000, function() {
                $("#image").animate({
                    opacity: 1,
                    left: "-=50",
                    height: 200,
                    width: 300,
                }, 1000, function() {
                    $("#image").on("click", function() {
                        $("image").removeAttr("style");
                    });

                });
            });
        });
        $("#right").on("click", function() {
            $(".box").animate({
                "left": "+=50px"
            }, "fast");
        });

        $("#left").on("click", function() {
            $(".box").animate({
                "left": "-=50px"
            }, "fast");
        });
        $("#up").on("click", function() {
            $(".box").animate({
                "top": "-=50px"
            }, "fast");
        });
        $("#down").on("click", function() {
            $(".box").animate({
                "top": "+=50px"
            }, "fast");
        });

        $("#reset").on("click", function() {
            $(".box").css({
                position: "absolute",
                background: "blue",
                left: 50 + "px",
                top: 341 + "px",
                width: 90 + "px",
                height: 90 + "px",
                margin: 100 + "px",
            });
        });
        /*
        document.getElementById("test").click() = function() {
            alert("clicked")
        }
        */
    </script>

</body>

</html>
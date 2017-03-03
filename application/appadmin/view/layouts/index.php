<!DOCTYPE html>
<head>
    <title>后台</title>

    <link href="__PublicAdmin__/css/theme.min.css" rel="stylesheet">
    <link href="__PublicDefault__/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="__PublicAdmin__/css/simplebootadminindex.min.css?">
    <style>
        .navbar .nav_shortcuts .btn {
            margin-top: 5px;
        }
        .macro-component-tabitem {
            width: 101px;
        }
        /*-----------------导航hack--------------------*/
        .nav-list>li.open {
            position: relative;
        }
        .nav-list>li.open .back {
            display: none;
        }
        .nav-list>li.open .normal {
            display: inline-block !important;
        }
        .nav-list>li.open a {
            padding-left: 7px;
        }
        .nav-list>li .submenu>li>a {
            background: #fff;
        }
        .nav-list>li .submenu>li a>[class*="fa-"]:first-child {
            left: 20px;
        }
        .nav-list>li ul.submenu ul.submenu>li a>[class*="fa-"]:first-child {
            left: 23px;
        }
        /*----------------导航hack--------------------*/
        #think_page_trace_open {
            left: 0 !important;
            right: initial !important;
        }
    </style>

</head>


<body style="min-width:900px;" screen_capture_injected="true">
[__REPLACE__]
<script src="__PublicAdmin__/js/jquery.min.js"></script>
<script src="__PublicAdmin__/js/bootstrap.min.js"></script>
<script src="__PublicAdmin__/js/jquery-1.8.0.min.js"></script>
<script src="__PublicAdmin__/js/index.js"></script>
<script>
    var ismenumin = $("#sidebar").hasClass("menu-min");
    $(".nav-list").on( "click",function(event) {
        var closest_a = $(event.target).closest("a");
        if (!closest_a || closest_a.length == 0) {
            return
        }
        if (!closest_a.hasClass("dropdown-toggle")) {
            if (ismenumin && "click" == "tap" && closest_a.get(0).parentNode.parentNode == this) {
                var closest_a_menu_text = closest_a.find(".menu-text").get(0);
                if (event.target != closest_a_menu_text && !$.contains(closest_a_menu_text, event.target)) {
                    return false
                }
            }
            return
        }
        var closest_a_next = closest_a.next().get(0);
        if (!$(closest_a_next).is(":visible")) {
            var closest_ul = $(closest_a_next.parentNode).closest("ul");
            if (ismenumin && closest_ul.hasClass("nav-list")) {
                return
            }
            closest_ul.find("> .open > .submenu").each(function() {
                if (this != closest_a_next && !$(this.parentNode).hasClass("active")) {
                    $(this).slideUp(150).parent().removeClass("open")
                }
            });
        }
        if (ismenumin && $(closest_a_next.parentNode.parentNode).hasClass("nav-list")) {
            return false;
        }
        $(closest_a_next).slideToggle(150).parent().toggleClass("open");
        return false;
    });
</script>

</body>
</html>








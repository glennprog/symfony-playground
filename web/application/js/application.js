$(document).ready(function () {
    //console.log( "ready!" );
    //console.log("application");

    //###################### Usefull variables //########################
    var pathname = window.location.pathname;

    //###################### Current nav item (nav bar) handle //########
    // For dynamic page
    $('.navbar-nav > li > a[href="'+pathname+'"]').parent().addClass('active');
    /* For static page
    $('.navbar-nav a').on('click', function () {
        $('.navbar-nav').find('li.active').removeClass('active');
        $(this).parent('li').addClass('active');
        console.log(this);
    });
    */

    //###################### Custom alert handle //######################
    var close = document.getElementsByClassName("gm-closebtn");
    var i;

    for (i = 0; i < close.length; i++) {
        close[i].onclick = function () {
            var div = this.parentElement;
            div.style.opacity = "0";
            setTimeout(function () { div.style.display = "none"; }, 600);
        }
    }

});
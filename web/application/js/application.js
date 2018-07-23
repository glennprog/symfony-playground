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

    //###################### handle register button (nav bar) handle //##
    $("#registration_at_login_form").on('click', function(){
        window.location.href = $("#registration_at_login_form").data( "href"); 
    });

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

    //###################### CLickable row handle //######################
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });

    $( ".date-datepicker" ).datepicker({
        pickTime:false,
        dateFormat: 'yy/mm/dd'
    });


});
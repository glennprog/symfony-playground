$( document ).ready(function() {
    //console.log( "ready!" );
    //console.log("GMQuestionAnswersBundle");
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
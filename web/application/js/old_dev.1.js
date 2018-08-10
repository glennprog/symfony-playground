$(document).ready(function () {

    var pathname = window.location.pathname;

    var orderBy = {};
    var search_data = {};
    //orderBy['id'] = 'ASC';


    $("#categories .th-sortable").click(function () {
        var col_name = $(this).attr('data-sort');
        orderBy = {};
        var page_input = $("#paginator_container_page_id_categories").val();
        var count_select = $("#paginator_container_count_id_categories").val();
        var searchBy = $("#search_container_data_categories").val();
        search_data['nom'] = searchBy;

        var th_parent = $(this).parent();
        var current_className = "";
        current_className = $(this).find(".ui-sort").attr('class');
        var sort_child = th_parent.find(".fa-sort-up, .fa-sort-down");
        for (let index = 0; index < sort_child.length; index++) {
            var className = sort_child[index].className;
            className = className.replace("fa-sort-up", "fa-sort");
            className = className.replace("fa-sort-down", "fa-sort");
            sort_child[index].className = className;
        }
        element = $(this).find(".ui-sort");
        if (current_className.indexOf("sort-up") != -1) {
            element.removeClass();
            element.addClass("fas fa-sort-down ui-sort");
            orderBy[col_name] = "ASC";
        }
        else{
           element.removeClass();
           element.addClass("fas fa-sort-up ui-sort");
           orderBy[col_name] = "DESC";
        }
        ajaxGetCategories(pathname, { "count": count_select, "page": page_input, "orderBy": orderBy, 'searchBy': search_data });
    });

    $("#films .th-sortable").click(function () {
        var col_name = $(this).attr('data-sort');
        orderBy = {};
        var page_input = $("#paginator_container_page_id_films").val();
        var count_select = $("#paginator_container_count_id_films").val();
        var th_parent = $(this).parent();
        var current_className = "";
        current_className = $(this).find(".ui-sort").attr('class');
        var sort_child = th_parent.find(".fa-sort-up, .fa-sort-down");
        for (let index = 0; index < sort_child.length; index++) {
            var className = sort_child[index].className;
            className = className.replace("fa-sort-up", "fa-sort");
            className = className.replace("fa-sort-down", "fa-sort");
            sort_child[index].className = className;
        }
        element = $(this).find(".ui-sort");
        if (current_className.indexOf("sort-up") != -1) {
            element.removeClass();
            element.addClass("fas fa-sort-down ui-sort");
            orderBy[col_name] = "ASC";
        }
        else{
           element.removeClass();
           element.addClass("fas fa-sort-up ui-sort");
           orderBy[col_name] = "DESC";
        }
        ajaxGetFilms(pathname, { "count": count_select, "page": page_input, "orderBy": orderBy });
    });
});
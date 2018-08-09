$(document).ready(function () {

    var pathname = window.location.pathname;

    var orderBy = {};
    var search_data = {};
    //orderBy['id'] = 'ASC';

    //###################### Paginator Handler : Categories //########################


    function paginatorCategories(params) {
        var params_size = Object.size(params);
        if (params_size > 0) {
            if (params['page'] != undefined) {
                var page_input = params['page'];
            }
            else {
                var page_input = $("#paginator_container_page_id_categories").val();
            }
            if (params['count'] != undefined) {
                var count_select = params['count'];
            }
            else {
                var count_select = $("#paginator_container_count_id_categories").val();
            }
        }
        else {
            page_input = 1;
            count_select = $("#paginator_container_count_id_categories").val();
        }
        ajaxGetCategories(pathname, { "count": count_select, "page": page_input, "orderBy": orderBy });
    }

    // prev fast
    $("#paginator_prev_fast_categories").click(function (e) {
        e.preventDefault();
        var params = gm_searchParamsUrl($("#paginator_prev_fast_categories")[0].getAttribute('href'));
        paginatorCategories(params);
    });

    // next fast
    $("#paginator_next_fast_categories").click(function (e) {
        e.preventDefault();
        var params = gm_searchParamsUrl($("#paginator_next_fast_categories")[0].getAttribute('href'));
        paginatorCategories(params);
    });

    // prev
    $("#paginator_prev_categories").click(function (e) {
        e.preventDefault();
        var params = gm_searchParamsUrl($("#paginator_prev_categories")[0].getAttribute('href'));
        paginatorCategories(params);
    });

    // next
    $("#paginator_next_categories").click(function (e) {
        e.preventDefault();
        var params = gm_searchParamsUrl($("#paginator_next_categories")[0].getAttribute('href'));
        paginatorCategories(params);
    });

    $("#paginator_categories").find('#paginator_container_count_id_categories').change(function () {
        var count_select = $(this).val();
        var page_input = $("#paginator_container_page_id_categories").val();
        ajaxGetCategories(pathname, { "count": count_select, "page": page_input, "orderBy": orderBy });
    });

    $("#paginator_container_page_id_categories").change(function () {
        var page_input = $(this).val();
        var count_select = $("#paginator_container_count_id_categories").val();
        ajaxGetCategories(pathname, { "count": count_select, "page": page_input, "orderBy": orderBy });
    });

    function refreshCategorieTable(msg) {
        categorie_size = Object.size(msg.categories);
        $("#categories_tbody  tr").remove();
        var categorie = null;
        var data_href = "/app_dev.php/videotheque/categorie/region_id/show";
        for (let index = 0; index < categorie_size; index++) {
            var categorie = msg.categories[index];
            $("<tr class='gm-clickable-row' data-href='" + data_href.replace("region_id", categorie.id) + "'>" +
                '<td>' + categorie.nom + '</td>' +
                "</tr>").appendTo("#categories_tbody");
            $(".gm-clickable-row").click(function () {
                window.location = $(this).data("href");
            });
        }
    }

    //###################### Paginator Handler : Films //########################
    function ajaxGetFilms(url, data) {
        $.ajax({
            url: url,
            method: "post",
            data: data
        }).done(function (msg) {
            refreshFilmTable(msg);
            update_paginator_route(msg["paginator"], "films");
        });
    }

    function paginatorFilms(params) {
        var params_size = Object.size(params);
        if (params_size > 0) {
            if (params['page'] != undefined) {
                var page_input = params['page'];
            }
            else {
                var page_input = $("#paginator_container_page_id_films").val();
            }
            if (params['count'] != undefined) {
                var count_select = params['count'];
            }
            else {
                var count_select = $("#paginator_container_count_id_films").val();
            }
        }
        else {
            page_input = 1;
            count_select = $("#paginator_container_count_id_films").val();
        }
        ajaxGetFilms(pathname, { "count": count_select, "page": page_input, "orderBy": orderBy });
    }

    function refreshFilmTable(msg) {
        film_size = Object.size(msg.films);
        $("#films_tbody  tr").remove();
        var film = null;
        var data_href = "/app_dev.php/videotheque/film/region_id/show";
        for (let index = 0; index < film_size; index++) {
            var film = msg.films[index];
            $(
                "<tr class='gm-clickable-row' data-href='" + data_href.replace("region_id", film.id) + "'>" +
                '<td>' + film.categorie.nom + '</td>' +
                '<td>' + film.titre + '</td>' +
                '<td>' + film.description + '</td>' +
                '<td>' + film.date_sortie.date.substring(0, 10) + '</td>' +
                "</tr>"
            ).appendTo("#films_tbody");
            $(".gm-clickable-row").click(function () {
                window.location = $(this).data("href");
            });
        }
    }

    // prev fast
    $("#paginator_prev_fast_films").click(function (e) {
        e.preventDefault();
        var params = gm_searchParamsUrl($("#paginator_prev_fast_films")[0].getAttribute('href'));
        paginatorFilms(params);
    });

    // next fast
    $("#paginator_next_fast_films").click(function (e) {
        e.preventDefault();
        var params = gm_searchParamsUrl($("#paginator_next_fast_films")[0].getAttribute('href'));
        paginatorFilms(params);
    });

    // prev
    $("#paginator_prev_films").click(function (e) {
        e.preventDefault();
        var params = gm_searchParamsUrl($("#paginator_prev_films")[0].getAttribute('href'));
        paginatorFilms(params);
    });

    // next
    $("#paginator_next_films").click(function (e) {
        e.preventDefault();
        var params = gm_searchParamsUrl($("#paginator_next_films")[0].getAttribute('href'));
        paginatorFilms(params);
    });

    $("#paginator_films").find('#paginator_container_count_id_films').change(function () {
        var count_select = $(this).val();
        var page_input = $("#paginator_container_page_id_films").val();
        ajaxGetFilms(pathname, { "count": count_select, "page": page_input, "orderBy": orderBy });
    });

    $("#paginator_container_page_id_films").change(function () {
        var page_input = $(this).val();
        var count_select = $("#paginator_container_count_id_films").val();
        ajaxGetFilms(pathname, { "count": count_select, "page": page_input, "orderBy": orderBy });
    });


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

    function getPaginatorAttributes(){
        var paginator_page_input = $("#paginator_container_page_" + paginator_entity).val();
        if(paginator_page_input == ""){
            paginator_page_input = 1;
            $("#paginator_container_page_" + paginator_entity).val(1);
        }
        console.log("page selected = " + paginator_page_input);

        var paginator_count_select = $("#paginator_container_count_" + paginator_entity).val();
        console.log("count selected = " + paginator_count_select);
        
        var paginator_prev_fast = $("#paginator_prev_fast_" + paginator_entity)[0].getAttribute('href');
        paginator_prev_fast = replaceParamsPageCount(paginator_prev_fast, paginator_page_input, paginator_count_select);
        console.log("paginator_prev_fast = " + paginator_prev_fast);
        
        var paginator_prev = $("#paginator_prev_" + paginator_entity)[0].getAttribute('href'); 
        paginator_prev = replaceParamsPageCount(paginator_prev, paginator_page_input, paginator_count_select);
        if(paginator_prev == ""){
            paginator_prev = null;
        }
        console.log("paginator_prev = " + paginator_prev);

        var paginator_next = $("#paginator_next_" + paginator_entity)[0].getAttribute('href');
        paginator_next = replaceParamsPageCount(paginator_next, paginator_page_input, paginator_count_select);
        if(paginator_next == ""){
            paginator_next = null;
        }
        console.log("paginator_next = " + paginator_next);

        var paginator_next_fast_ = $("#paginator_next_fast_" + paginator_entity)[0].getAttribute('href');
        paginator_next_fast_ = replaceParamsPageCount(paginator_next_fast_, paginator_page_input, paginator_count_select);
        console.log("paginator_next_fast_ = " + paginator_next_fast_);

    }

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

    $("#search_container_data_categories").change(function () {
        var columsSearch = $(this).attr('data-search');
        columsSearchTab = columsSearch.split(';');
        for (let index = 0; index < columsSearchTab.length; index++) {
            const element = columsSearchTab[index];
            search_data[element] = $(this).val();
        }
        console.log(search_data);
        var page_input = $("#paginator_container_page_id_categories").val();
        var count_select = $("#paginator_container_count_id_categories").val();
        ajaxGetCategories(pathname, { "count": count_select, "page": page_input, "orderBy": orderBy , "searchBy":search_data});
    });

    function ajaxGetCategories(url, data) {
        console.log(data);
        $.ajax({
            url: url,
            method: "post",
            data: data
        }).done(function (msg) {
            refreshCategorieTable(msg);
            update_paginator_route(msg["paginator"], "categories");
        });
    }

});
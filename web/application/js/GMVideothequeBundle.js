$(document).ready(function () {

    var pathname = window.location.pathname;

    //###################### Paginator Handler : Categories //########################
    function ajaxGetCategories(url, data) {
        $.ajax({
            url: url,
            method: "post",
            data: data
        }).done(function (msg) {
            refreshCategorieTable(msg);
            update_paginator_route(msg["paginator"], "categories");
        });
    }

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
        ajaxGetCategories(pathname, { "count": count_select, "page": page_input, "orderBy": null });
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
        ajaxGetCategories(pathname, { "count": count_select, "page": page_input, "orderBy": null });
    });

    $("#paginator_container_page_id_categories").change(function () {
        var page_input = $(this).val();
        var count_select = $("#paginator_container_count_id_categories").val();
        ajaxGetCategories(pathname, { "count": count_select, "page": page_input, "orderBy": null });
    });

    function refreshCategorieTable(msg) {
        categorie_size = Object.size(msg.categories);
        $("#categories_tbody  tr").remove();
        var categorie = null;
        var data_href = "/app_dev.php/videotheque/categorie/region_id/show";
        for (let index = 0; index < categorie_size; index++) {
            var categorie = msg.categories[index];
            $("<tr class='gm-clickable-row' data-href='" + data_href.replace("region_id", categorie.id) + "'>" +
                '<td><a href=' + data_href.replace("region_id", categorie.id) + '>' + categorie.id + '</a></td>' +
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
        ajaxGetFilms(pathname, { "count": count_select, "page": page_input, "orderBy": null });
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
                '<td><a href=' + data_href.replace("region_id", film.id) + '>' + film.id + '</a></td>' +
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
        ajaxGetFilms(pathname, { "count": count_select, "page": page_input, "orderBy": null });
    });

    $("#paginator_container_page_id_films").change(function () {
        var page_input = $(this).val();
        var count_select = $("#paginator_container_count_id_films").val();
        ajaxGetFilms(pathname, { "count": count_select, "page": page_input, "orderBy": null });
    });
});
$(document).ready(function () {
    //console.log( "ready!" );
    //console.log("application");

    //###################### Mandatory variables //######################
    var pathname = window.location.pathname;

    //###################### Paginator Handler //########################
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
                    '<td>' + film.date_sortie.date.substring(0,10) + '</td>' +
                "</tr>"
            ).appendTo("#films_tbody");
            $(".gm-clickable-row").click(function () {
                window.location = $(this).data("href");
            });
        }
    }
});
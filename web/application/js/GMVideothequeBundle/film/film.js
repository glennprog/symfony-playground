$(document).ready(function () 
{
    var paginator_entity = 'Film';

    $("#paginator_" + paginator_entity + " .paginator-nav").click(function (e) {
        e.preventDefault();
        call_paginator_url = $(this)[0].getAttribute('href');
        var paginator_count_select = $("#paginator_container_count_" + paginator_entity).val();
        call_paginator_url = replaceParamsPageCount(call_paginator_url, null, paginator_count_select);
        ajaxGetFilms(call_paginator_url, null);
    });

    $("#paginator_container_page_" + paginator_entity).change(function () {
        var paginator_page_input = $(this).val();
        if(paginator_page_input == ""){
            paginator_page_input = 1;
            $("#paginator_container_page_" + paginator_entity).val(1);
        }
        if(Number.isInteger(parseInt(paginator_page_input)) == false){
            paginator_page_input = 1;
            $("#paginator_container_page_" + paginator_entity).val(1);
        }
        var paginator_count_select = $("#paginator_container_count_" + paginator_entity).val();
        var paginator_prev_fast = $("#paginator_prev_fast_" + paginator_entity)[0].getAttribute('href'); // cause it's the first paginator's page
        call_paginator_url = replaceParamsPageCount(paginator_prev_fast, paginator_page_input, paginator_count_select);
        ajaxGetFilms(call_paginator_url, null);
    });

    $("#paginator_container_count_" + paginator_entity).change(function () {
        var paginator_count_select = $(this).val();
        var paginator_prev_fast = $("#paginator_prev_fast_" + paginator_entity)[0].getAttribute('href');
        call_paginator_url = replaceParamsPageCount(paginator_prev_fast, null, paginator_count_select);
        updatePaginatorPageCount(null, paginator_count_select, paginator_entity);
        $("#paginator_container_page_" + paginator_entity).val(1);
        ajaxGetFilms(call_paginator_url, null);    
    });

    function ajaxGetFilms(url, data) 
    {
        ////ajaxGetCategories(pathname, { "count": count_select, "page": page_input, "orderBy": orderBy });
        $.ajax({
            url: url,
            method: "post",
            data: data
        }).done(function (response) {
            refreshFilmTable(response.data.films);
            update_paginator_route(response.data.films.paginator);
        });
    }

    function refreshFilmTable(films) 
    {
        film_size = Object.size(films.result);
        $("#films_tbody  tr").remove();
        var film = null;
        var data_href = "/app_dev.php/videotheque/film/region_id/show";
        for (let index = 0; index < film_size; index++) {
            var film = films.result[index];
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

});
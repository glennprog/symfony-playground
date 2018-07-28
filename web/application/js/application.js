$(document).ready(function () {
    //console.log( "ready!" );
    //console.log("application");

    //###################### Mandatory variables //######################
    var pathname = window.location.pathname;

    //###################### Usefull variables //########################


    //###################### Paginator Handler //########################
    $("#paginator_container_count_id").change(function () {
        var count_select = $(this).val();
        var page_input = $("#paginator_container_page_id").val();
        ajaxCallPaginator(pathname, { "count": count_select, "page": page_input, "orderBy": null });
    });

    $("#paginator_container_page_id").change(function () {
        var page_input = $(this).val();
        var count_select = $("#paginator_container_count_id").val();
        ajaxCallPaginator(pathname, { "count": count_select, "page": page_input, "orderBy": null });
    });

    function ajaxCallPaginator(url, data) {
        $.ajax({
            url: url,
            method: "post",
            data: data
        }).done(function (msg) {
            refreshCategorie(msg);
            update_paginator_route(msg["paginator"]);
        });
    }

    function refreshCategorie(msg) {
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
});
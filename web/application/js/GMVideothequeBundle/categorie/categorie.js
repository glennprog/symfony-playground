$(document).ready(function () {
    var paginator_entity = 'Categorie';
    var search_container_entity = 'Categorie';
    var sort_container_entity = 'Categorie';
    var searchBy_categorie = {};
    var searchByMode = 'data_percentage';
    var orderBy = {'nom' : 'ASC'};
    searchByMode = document.querySelector('input[name="search_engine_options_like_' + search_container_entity + '"]:checked').value;
    orderBy.nom = document.querySelector('input[name="search_engine_options_orderby_' + search_container_entity + '"]:checked').value;
    var search_engine_data = {'searchByMode': searchByMode, 'searchBy': searchBy_categorie, 'orderBy': orderBy}; 

    searchEngineManager(search_container_entity, search_engine_data, ajaxGetCategoriesSearchEngine, paginator_entity);
    PaginatorManager(paginator_entity, search_engine_data, ajaxGetCategories, search_container_entity);
    sortManager(search_engine_data, ajaxGetCategories, search_container_entity, sort_container_entity, paginator_entity);

    function refreshCategorieTable(categories) {
        categorie_size = Object.size(categories.result);
        $("#categories_tbody  tr").remove();
        var categorie = null;
        var data_href = "/app_dev.php/videotheque/categorie/region_id/show";
        for (let index = 0; index < categorie_size; index++) {
            var categorie = categories.result[index];
            $("<tr class='gm-clickable-row' data-href='" + data_href.replace("region_id", categorie.id) + "'>" +
                '<td>' + categorie.nom + '</td>' +
                "</tr>").appendTo("#categories_tbody");
            $(".gm-clickable-row").click(function () {
                window.location = $(this).data("href");
            });
        }
    }

    function ajaxGetCategoriesSearchEngine(data_search_engine) {
        var data = {'searchByMode': data_search_engine.searchByMode, 'searchBy': JSON.stringify(data_search_engine.searchBy), 'orderBy': JSON.stringify(data_search_engine.orderBy)};
        updateSearEngine(data_search_engine);
        ajaxGetCategories(getUrlAjax(), data);
    }

    function ajaxGetCategories(url, data) {
        $.ajax({
            url: url,
            method: "post",
            data: data
        }).done(function (response) {
            refreshCategorieTable(response.data.categories);
            update_paginator_route(response.data.categories.paginator);
        });
    }

    function getUrlAjax(){
        var paginator_count_select = $("#paginator_container_count_" + paginator_entity).val();
        var paginator_prev_fast = $("#paginator_prev_fast_" + paginator_entity)[0].getAttribute('href');
        call_paginator_url = replaceParamsPageCount(paginator_prev_fast, null, paginator_count_select);
        return call_paginator_url;
    }

    function updateSearEngine(data_search_engine){
        searchBy_categorie = data_search_engine.searchBy;
        searchByMode = data_search_engine.searchByMode;
        orderBy = data_search_engine.orderBy;
    }
});
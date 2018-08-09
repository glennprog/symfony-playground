$(document).ready(function () 
{
    var paginator_entity = 'Categorie';
    var searchBy_categorie = {};
    var searchByMode = 'data_percentage';

    $("#paginator_" + paginator_entity + " .paginator-nav").click(function (e) {
        e.preventDefault();
        call_paginator_url = $(this)[0].getAttribute('href');
        var paginator_count_select = $("#paginator_container_count_" + paginator_entity).val();
        call_paginator_url = replaceParamsPageCount(call_paginator_url, null, paginator_count_select);
        ajaxGetCategories(call_paginator_url, null);
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
        ajaxGetCategories(call_paginator_url, null);
    });

    $("#paginator_container_count_" + paginator_entity).change(function () {
        var paginator_count_select = $(this).val();
        var paginator_prev_fast = $("#paginator_prev_fast_" + paginator_entity)[0].getAttribute('href');
        call_paginator_url = replaceParamsPageCount(paginator_prev_fast, null, paginator_count_select);
        updatePaginatorPageCount(null, paginator_count_select, paginator_entity);
        $("#paginator_container_page_" + paginator_entity).val(1);
        ajaxGetCategories(call_paginator_url, null);    
    });

    function ajaxGetCategories(url, data) 
    {
        console.log(url);
        console.log(data);
        ////ajaxGetCategories(pathname, { "count": count_select, "page": page_input, "orderBy": orderBy });
        $.ajax({
            url: url,
            method: "post",
            data: data
        }).done(function (response) {
            console.log(response);
            refreshCategorieTable(response.data.categories);
            update_paginator_route(response.data.categories.paginator);
        });
    }

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

    //$("#search_container_data_" + paginator_entity).change(function () {
    $("#search_container_data_" + paginator_entity).keyup(function () {
        var paginator_count_select = $("#paginator_container_count_" + paginator_entity).val();
        var paginator_prev_fast = $("#paginator_prev_fast_" + paginator_entity)[0].getAttribute('href');
        call_paginator_url = replaceParamsPageCount(paginator_prev_fast, null, paginator_count_select);
        $("#paginator_container_page_" + paginator_entity).val(1);
        if($(this).val() == ""){
            search_data_categorie = null;
            ajaxGetCategories(call_paginator_url, null);
            return;
        }
        var columsSearch = $(this).attr('data-search');
        columsSearchTab = columsSearch.split(';');
        for (let index = 0; index < columsSearchTab.length; index++) {
            const element = columsSearchTab[index];
            searchBy_categorie[element] = $(this).val();
        }
        //searchByMode = 'data_percentage';
        ajaxGetCategories(call_paginator_url, {'searchByMode' : searchByMode, 'searchBy' : JSON.stringify(searchBy_categorie)});  
        //ajaxGetCategories(pathname, { "count": count_select, "page": page_input, "orderBy": orderBy , "searchBy":search_data});
    });

});
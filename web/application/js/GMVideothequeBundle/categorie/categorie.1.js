$(document).ready(function () {
    var paginator_entity = 'Categorie';
    var search_container_entity = 'Categorie';
    var searchBy_categorie = {};
    var searchByMode = 'equal';
    var orderBy = {'nom' : 'ASC'};



    var search_engine_data = {'searchByMode': searchByMode, 'searchBy': searchBy_categorie, 'orderBy': orderBy}; 
    searchEngineManager(search_container_entity, search_engine_data, ajaxGetCategoriesSearchEngine);


    $("#paginator_" + paginator_entity + " .paginator-nav").click(function (e) {
        e.preventDefault();
        call_paginator_url = $(this)[0].getAttribute('href');
        var paginator_count_select = $("#paginator_container_count_" + paginator_entity).val();
        call_paginator_url = replaceParamsPageCount(call_paginator_url, null, paginator_count_select);

        if ( $("#search_container_data_input_" + search_container_entity).val() == "") {
            searchBy_categorie = null;
        }
        var data = {'searchByMode': searchByMode, 'searchBy': JSON.stringify(searchBy_categorie), 'orderBy': JSON.stringify(orderBy)};
        ajaxGetCategories(call_paginator_url, data);
    });

    $("#paginator_container_page_" + paginator_entity).change(function () {
        var paginator_page_input = $(this).val();
        if (paginator_page_input == "") {
            paginator_page_input = 1;
            $("#paginator_container_page_" + paginator_entity).val(1);
        }
        if (Number.isInteger(parseInt(paginator_page_input)) == false) {
            paginator_page_input = 1;
            $("#paginator_container_page_" + paginator_entity).val(1);
        }
        var paginator_count_select = $("#paginator_container_count_" + paginator_entity).val();
        var paginator_prev_fast = $("#paginator_prev_fast_" + paginator_entity)[0].getAttribute('href'); // cause it's the first paginator's page
        call_paginator_url = replaceParamsPageCount(paginator_prev_fast, paginator_page_input, paginator_count_select);
        
        
        if ( $("#search_container_data_input_" + search_container_entity).val() == "") {
            searchBy_categorie = null;
        }
        var data = {'searchByMode': searchByMode, 'searchBy': JSON.stringify(searchBy_categorie), 'orderBy': JSON.stringify(orderBy)};
        ajaxGetCategories(call_paginator_url, data);
    });

    $("#paginator_container_count_" + paginator_entity).change(function () {
        var paginator_count_select = $(this).val();
        var paginator_prev_fast = $("#paginator_prev_fast_" + paginator_entity)[0].getAttribute('href');
        call_paginator_url = replaceParamsPageCount(paginator_prev_fast, null, paginator_count_select);
        updatePaginatorPageCount(null, paginator_count_select, paginator_entity);
        $("#paginator_container_page_" + paginator_entity).val(1);

        if ( $("#search_container_data_input_" + search_container_entity).val() == "") {
            searchBy_categorie = null;
        }
        var data = {'searchByMode': searchByMode, 'searchBy': JSON.stringify(searchBy_categorie), 'orderBy': JSON.stringify(orderBy)};
        ajaxGetCategories(call_paginator_url, data);
    });

    function getCallUrlCategorieRestIndex(){
        var paginator_count_select = $("#paginator_container_count_" + paginator_entity).val();
        var paginator_prev_fast = $("#paginator_prev_fast_" + paginator_entity)[0].getAttribute('href');
        call_paginator_url = replaceParamsPageCount(paginator_prev_fast, null, paginator_count_select);
        return call_paginator_url;    
    }

    function updateSearchOptions(data_options){
        searchBy_categorie = data_options.searchBy;
        searchByMode = data_options.searchByMode;
        orderBy = data_options.orderBy;
    }

    function ajaxGetCategoriesSearchEngine(data_search_engine) {
        var data = {'searchByMode': data_search_engine.searchByMode, 'searchBy': JSON.stringify(data_search_engine.searchBy), 'orderBy': JSON.stringify(data_search_engine.orderBy)};
        url = getCallUrlCategorieRestIndex();
        updateSearchOptions(data_search_engine);
        console.log(url);
        console.log(data);

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

    function ajaxGetCategories(url, data) {
        console.log(url);
        console.log(data);
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
    $("#search_container_data_input_" + search_container_entity).keyup(function () {
        var paginator_count_select = $("#paginator_container_count_" + paginator_entity).val();
        var paginator_prev_fast = $("#paginator_prev_fast_" + paginator_entity)[0].getAttribute('href');
        call_paginator_url = replaceParamsPageCount(paginator_prev_fast, null, paginator_count_select);
        $("#paginator_container_page_" + paginator_entity).val(1);
        if ($(this).val() == "") {
            searchBy_categorie = null;

            var data = {'searchByMode': searchByMode, 'searchBy': JSON.stringify(searchBy_categorie), 'orderBy': JSON.stringify(orderBy)};
            ajaxGetCategories(call_paginator_url, data);
            return;
        }

        if(searchBy_categorie == null){
            searchBy_categorie = {}
        }

        var columsSearch = $(this).attr('data-search');
        columsSearchTab = columsSearch.split(';');
        for (let index = 0; index < columsSearchTab.length; index++) {
            const element = columsSearchTab[index];
            searchBy_categorie[element] = $(this).val();
        }

        search_engine_data.searchBy = searchBy_categorie;
        var data = {'searchByMode': searchByMode, 'searchBy': JSON.stringify(searchBy_categorie), 'orderBy': JSON.stringify(orderBy)};
        ajaxGetCategories(call_paginator_url, data);
    });

    /*
    $(".input-search-engine-options").click(function () {
        var x = document.querySelector('input[name="search_engine_options_like_' + search_container_entity + '"]:checked').value; //var x = document.getElementById("myRadio").value;
        searchByMode = x;
        var y = document.querySelector('input[name="search_engine_options_orderby_' + search_container_entity + '"]:checked').value;
        orderBy.nom = y;
        var paginator_count_select = $("#paginator_container_count_" + paginator_entity).val();
        var paginator_prev_fast = $("#paginator_prev_fast_" + paginator_entity)[0].getAttribute('href');
        call_paginator_url = replaceParamsPageCount(paginator_prev_fast, null, paginator_count_select);
        if ( $("#search_container_data_input_" + search_container_entity).val() == "") {
            searchBy_categorie = null;
        }
        var data = {'searchByMode': searchByMode, 'searchBy': JSON.stringify(searchBy_categorie), 'orderBy': JSON.stringify(orderBy)};
        ajaxGetCategories(call_paginator_url, data);

    });
    */

    // Get the modal
    var modal = document.getElementById('search_engine_options_' + search_container_entity);
    // Get the button that opens the modal
    var btn = document.getElementById("search_container_option_" + search_container_entity);
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("gm-modal-close")[0];
    // When the user clicks the button, open the modal 
    btn.onclick = function () {
        modal.style.display = "block";
    }
    // When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
    }
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

});
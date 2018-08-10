function searchEngineManager(search_container_entity, search_engine_data, callback){
    $(".input-search-engine-options").click(function () {
        var x = document.querySelector('input[name="search_engine_options_like_' + search_container_entity + '"]:checked').value; //var x = document.getElementById("myRadio").value;
        search_engine_data.searchByMode = x;
        var y = document.querySelector('input[name="search_engine_options_orderby_' + search_container_entity + '"]:checked').value;
        search_engine_data.orderBy.nom = y;


        var columsSearch = $("#search_container_data_input_" + search_container_entity).attr('data-search');
        columsSearchTab = columsSearch.split(';');
        for (let index = 0; index < columsSearchTab.length; index++) {
            const element = columsSearchTab[index];
            search_engine_data.searchBy[element] = $("#search_container_data_input_" + search_container_entity).val();
        }

        if ( $("#search_container_data_input_" + search_container_entity).val() == "") {
            search_engine_data.searchBy = null;
        }
        
        /*
        else{
            search_engine_data.searchBy = $("#search_container_data_input_" + search_container_entity).val();
        }
        */


        //var data = {'searchByMode': search_engine_data.searchByMode, 'searchBy': JSON.stringify(search_engine_data.searchBy_data), 'orderBy': JSON.stringify(search_engine_data.orderBy)};
        var data = {'searchByMode': search_engine_data.searchByMode, 'searchBy': search_engine_data.searchBy, 'orderBy': search_engine_data.orderBy};
        callback(data);
    });
}


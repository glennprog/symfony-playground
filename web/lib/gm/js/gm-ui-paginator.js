function update_paginator_route(paginator) {
    /* Comming soon */
    id_a_element_current_paginator = []; 
    // Init param
    var param = null;
    var value = null;
    // count param
    param = "count";
    value = paginator["count"];
    var navigator_prev_url = replaceParamsUrl(param, value, $("#paginator_prev")[0].getAttribute('href'));
    $("#paginator_prev").prop("href", navigator_prev_url);
    var paginator_prev_fast_url = replaceParamsUrl(param, value, $("#paginator_prev_fast")[0].getAttribute('href'));
    $("#paginator_prev_fast").prop("href", paginator_prev_fast_url);
    var paginator_next_url = replaceParamsUrl(param, value, $("#paginator_next")[0].getAttribute('href'));
    $("#paginator_next").prop("href", paginator_next_url);
    var paginator_next_fast_url = replaceParamsUrl(param, value, $("#paginator_next_fast")[0].getAttribute('href'));
    $("#paginator_next_fast").prop("href", paginator_next_fast_url);
    // page param
    param = "page";
    value_prev_fast_page = 1;
    var paginator_prev_fast_url = replaceParamsUrl(param, value_prev_fast_page, $("#paginator_prev_fast")[0].getAttribute('href'));
    $("#paginator_prev_fast").prop("href", paginator_prev_fast_url);
    if (paginator["previous_page"] != null) {
        value_prev_page = paginator["previous_page"];
        var navigator_prev_url = replaceParamsUrl(param, value_prev_page, $("#paginator_prev")[0].getAttribute('href'));
        $("#paginator_prev").prop("href", navigator_prev_url);
    }
    if (paginator["next_page"] != null) {
        value_next_page = paginator["next_page"];
        var paginator_next_url = replaceParamsUrl(param, value_next_page, $("#paginator_next")[0].getAttribute('href'));
        $("#paginator_next").prop("href", paginator_next_url);
    }
    value_next_fast_page = paginator["total_page"];
    var paginator_next_fast_url = replaceParamsUrl(param, value_next_fast_page, $("#paginator_next_fast")[0].getAttribute('href'));
    $("#paginator_next_fast").prop("href", paginator_next_fast_url);
    if(paginator['current_page'] <= paginator['total_page']){
        $("#paginator-container-page-total-text").text('de ' + paginator['total_page']);
    }
    else{
        $("#paginator-container-page-total-text").text('');
    }
    $("#paginator-container-total-entities-text").text('Total global : ' + paginator['total_entities']);

    if(paginator['next_record_to_read'] != null){
        $("#paginator-container-next_record_to_read-text").text('Reste à lire : ' + paginator['next_record_to_read']);
    }else{
        $("#paginator-container-next_record_to_read-text").text('Reste à lire : zéro');
    }

    $("#paginator_container_page_id").val(paginator['current_page']);
}

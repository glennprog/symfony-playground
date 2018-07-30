function update_paginator_route(paginator, entityHandled) {
    $("#paginator_prev_"+paginator[entityHandled]["entity"]).prop("href", paginator[entityHandled]["paginator_prev"]);
    $("#paginator_prev_fast_"+paginator[entityHandled]["entity"]).prop("href", paginator[entityHandled]["paginator_prev_fast"]);
    $("#paginator_next_"+paginator[entityHandled]["entity"]).prop("href", paginator[entityHandled]["paginator_next"]);
    $("#paginator_next_fast_"+paginator[entityHandled]["entity"]).prop("href", paginator[entityHandled]["paginator_next_fast"]);
    if(paginator[entityHandled]['current_page'] <= paginator[entityHandled]['total_page']){
        $("#paginator_container_page_total_text_"+paginator[entityHandled]["entity"]).text('de ' + paginator[entityHandled]['total_page']);
    }
    else{
        $("#paginator_container_page_total_text_"+paginator[entityHandled]["entity"]).text('');
    }
    $("#paginator_container_total_entities_text_"+paginator[entityHandled]["entity"]).text('Total global : ' + paginator[entityHandled]['total_entities']);

    if(paginator[entityHandled]['next_record_to_read'] != null){
        $("#paginator_container_next_record_to_read_text_"+paginator[entityHandled]["entity"]).text('Reste à lire : ' + paginator[entityHandled]['next_record_to_read']);
    }else{
        $("#paginator_container_next_record_to_read_text_"+paginator[entityHandled]["entity"]).text('Reste à lire : zéro');
    }
    $("#paginator_container_page_id_"+paginator[entityHandled]["entity"]).val(paginator[entityHandled]['current_page']);
}
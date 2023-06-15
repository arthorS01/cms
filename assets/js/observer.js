const observer = new MutationObserver(function(entries){

    let elem_id = entries[0].addedNodes[0].id;
    
    if(elem_id==undefined){
        elem_id = entries[0].addedNodes[1].id;
    }
    match_observer_elem(elem_id,window.conn);

});

let main_elemtent = document.querySelector("main");
observer.observe(main_elemtent,{childList:true});

function match_observer_elem(added_node_id,conn,message=null){

    switch(added_node_id){
        case "dashboard_container":
                                    break;
        case "complaint_container":add_event(".view_complaint_btn","click",view_complaint);
                                    add_event("#filter_value","change",change_view);
                                    add_event(".close_view_btn","click",close_view);
                                    add_event("#make_complaint_btn","click",function(e){
                                        $("#overlay").style.display  = "flex";
                                    });
                                    add_event("#close_complaint_form_btn","click",function(e){
                                        $("#overlay").style.display  = "none";
                                    });
                                    if($("#acc_type").value == "student"){
                                        add_event(".drop_btn","click",drop_complaint);
                                        add_event("#file_upload_btn","click",e=>{
                                            alert('upload files');
                                            e.preventDefault();
                                            let data_form = document.querySelector("#file_upload_form");
                                             my_data_form = new FormData(data_form);
                                            //form.append("files",e.target.files[0]);
                                          
                                            let url = "http://localhost/cms/complaint/files";
                                            post(url,my_data_form,function(data){
                                                alert("success");
                                            })

                                        });
                                        add_event("#letter_box_back_btn","click",my_letter.close_area);
                                        add_event("#letter_box_preview_btn","click",my_letter.preview);
                                        add_event("#letter_box_send_btn","click",my_letter.send);
                                        add_event("#letter_box_clear_btn","click",my_letter.clear);
                                    }
                                    if($("#acc_type").value=="admin"){
                                        add_event(".cancel_refer_request_btn","click",my_referal_handler.cancel_request);
                                        add_event(".reffered_case_btn","click",my_referal_handler.open_refer_container);
                                        add_event("#send_file_form_caption","submit",share_file);
                                        add_event(".send_file_to_check","click",toggle_file_receipient);
                                        add_event(".go_back","click",close_share_area);
                                    }
                                    add_event(".work_on_it_btn","click",update_case);
                                    add_event(".case_controlls input[type='checkbox']","click",update_special_case);
                                    add_event(".refer_btn","click",show_refer_container);
                                    
                                    add_event(".referal_container .close_btn","click",close_refer_container);
                                    add_event(".refered_case_details .close_btn","click",close_refer_container);
                                    add_event(".send_refer_request_btn","click",refer_case);
                                   add_event("#refresh_btn","click",function(){
                                    alert("clicked the button");
                                    get_request("http://localhost/cms/admin/complaint",show_area);
                                   });
                                   add_event(".trash_btn", "click", delete_case);
                                    
                                    break;
        case "chat_container":  add_event("#send_msg_btn","click",msgObj.send);
                                add_event(".chat_patner_overlay","click",chat_areaobj.set_area);
                                add_event(".chat_patner_overlay","click",msgObj.chat);
                                add_event("#connections>button","click",function(e){
                                    e.preventDefault();
                                    let patners_container_style = $("#patners").style.display;
                                    if($("#patners").style.display == ""){
                                        $("#patners").style.display = "block";
                                    }else{
                                        $("#patners").style.display="";
                                    }
                                });
                                break;
        case "notification_container":add_event(".show_detail_btn","click",my_notifier.toggle_detail);
                                    if($("#acc_type").value=="admin"){
                                        add_event(".accept_btn","click",my_referal_handler.accept_case);
                                    }
                                        add_event(".trash_btn","click",my_notifier.delete_notice);
                                        break;
        case "history_container":add_event("#search_bar_value","input",HistoryManager.search_history);
                                    add_event("#close_search_btn","click",function(e){
                                        
                                        e.preventDefault();
                                        if($("#search_results").style.display == "flex"){

                                            $("#search_results").style.display = "none";
                                            $("#search_bar input").value = "";
                                            $("#history_area").style.filter = "none";
                                            $("#close_search_btn").style.visibility = "hidden";
                                        }

                                       
                                    });
                                    add_event("#clear_btn","click",HistoryManager.clear); 
                                    add_event(".show_detail_btn","click",HistoryManager.toggle_detail)      
                                    break;
        case "settings_container":
                                    break;
        case "create_connection":
                                    break;
    }


}
function add_event(id,event,callback){

    let elem = document.querySelectorAll(id);
    elem.forEach(element => {

        element.addEventListener(event,callback);

    });
    
    

}
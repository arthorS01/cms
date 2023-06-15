

var my_notifier = produce_notifier({});



function produce_notifier(myobj){

    let obj = myobj;
     
    let notify = function(){

        //window.conn.send(obj);
        alert("sending notification");
    }


    let set_obj = (arg)=>{
        obj = arg;
    }
    let update_total_count = (increment)=>{

        let current_count = Number($("#total_count .count").innerText) ;
        $("#total_count .count").innerText = current_count + increment;

    }

    let update_seen_count = (increment)=>{

        let current_count = Number($("#seen_count .count").innerText) ;
        $("#seen_count .count").innerText = current_count + increment;

    }

    let update_unseen_count = (increment)=>{

        let current_count = Number($("#unseen_count .count").innerText) ;
        $("#unseen_count .count").innerText = current_count + increment;

    }

    let create_tab = (msg)=>{

        alert("update the dom");
                let text = "see details";
                let notification = document.createElement("div");
                notification.classList.add("notification");
                //notification.classList.add("ADDED");
                let div = document.createElement("div");
                div.classList.add("headline");
                let p1 = document.createElement("p");
                p1.classList.add("unseen");
                let p2 = document.createElement("p");
                p2.innerText = msg.subject;
                let p3 = document.createElement("p");

                let date = new Date();
                date = date.getYear()+"/"+date.getMonth()+"/"+date.getDay();
                p3.innerText = date;

                let p4 = document.createElement("p");
                let span = document.createElement("span");
                span.innerText = text;
                let btn = document.createElement("button");
                btn.classList.add("show_detail_btn");
                btn.innerHTML = "<i class='show_detail_btn icofont-arrow-down'></i>";
                //span.appendChild(btn);
                btn.addEventListener("click",my_notifier.toggle_detail);
                p4.appendChild(span);
                p4.appendChild(btn);
                let p5 = document.createElement("p");
                p5.classList.add("trash_btn");
                let btn2 = document.createElement("button");
                btn2.innerHTML = "<i class='icofont-trash'></i>";
                btn2.addEventListener("click",my_notifier.delete_notice);
                p5.appendChild(btn2);

             
                div.appendChild(p1);
                div.appendChild(p2);
                div.appendChild(p3);
                div.appendChild(p4);
                div.appendChild(p5);
                
               

                let div2 = document.createElement("div");
                div2.classList.add("details_area");

                let case_id  = msg.meta_data.case_id;
                let detail_entry = document.createElement("div");
                detail_entry.classList.add("detail_entry");
                let p6 = document.createElement("p");
                p6.innerText = "case id :";
                let p7 = document.createElement("p");
                p7.innerText = case_id;
                
                detail_entry.appendChild(p6);
                detail_entry.appendChild(p7);

                
                
                //create_entry_view(msg.meta_data);
                
                //console.log(details);
                if($(".no_case") !== null){
                    $(".no_case").style.display = "none";
                }

                if(msg.meta_data.response){

                    let response_tab = document.createElement("div");
                    response_tab.classList.add("option");
                    response_tab.setAttribute("data-referer-id",msg.sender_id);
                    response_tab.setAttribute("data-case-id",msg.meta_data.case_id);

                    let accept_btn = document.createElement("button");
                    accept_btn.innerText = "Accept";
                    accept_btn.classList.add("accept_btn");
                    accept_btn.addEventListener("click",my_referal_handler.accept_case);

                    let decline_btn = document.createElement("button");
                    decline_btn.innerText = "Decline";
                    decline_btn.classList.add("decline_btn");
                    decline_btn.addEventListener("click",my_referal_handler.decline_case);

                    response_tab.appendChild(accept_btn);
                    response_tab.appendChild(decline_btn);

                    let sender_entry = document.createElement("div");
                    sender_entry.classList.add("detail_entry");
                    sender_entry.innerHTML= "<p>Sender id : </p>"+"<p>"+msg.meta_data.sender_id +"</p>";

                    let uploaded_by_entry = document.createElement("div");
                    uploaded_by_entry.classList.add("detail_entry");
                    uploaded_by_entry.innerHTML = "<p>Uploaded by : </p>"+"<p>"+msg.meta_data.uploaded_by+"</p>";

                    let date_uploaded_entry = document.createElement("div");
                    date_uploaded_entry.classList.add("detail_entry");
                    date_uploaded_entry.innerHTML = "<p>Date uploaded : </p>"+"<p>"+msg.meta_data.date_uploaded+"</p>";

                    
                    div2.appendChild(sender_entry);
                    div2.appendChild(uploaded_by_entry);
                    div2.appendChild(date_uploaded_entry);
                    
                    
                    div2.appendChild(response_tab);

                }
                div2.appendChild(detail_entry);
                
                notification.appendChild(div);
                notification.appendChild(div2);

                $("#notification_area").insertBefore(notification,$("#notification_area").children[0]);
                update_total_count(1);
                update_unseen_count(1);

    }

    let toggle_detail = (e)=>{
        
        let target = e.target.parentElement.parentElement.parentElement.parentElement;

        let currentList =  target.classList;
        if([...currentList].includes("show_details")){

            target.classList.remove("show_details");

        }else{
           
            target.classList.add("show_details");
            if(target.querySelector(".headline>p").getAttribute("class") == "unseen"){
                update("http://localhost/cms/notification/update",{id:target.getAttribute("data-notification-id")},function(data){
                if(data.status){
                    target.querySelector(".headline>p").setAttribute("class","seen_notice");
                    update_seen_count(1);
                    update_unseen_count(-1);
                }
                });
               
            }
        }
       
    }

    let delete_notice = (e)=>{

        let target = e.target.parentElement.parentElement.parentElement.parentElement;
        let id = target.getAttribute("data-notification-id");
        let all_notifications = $$(".notification");

        all_notifications.forEach(notice => {
            if(notice === target){

                delete_request("http://localhost/cms/notification/delete",{id:id},function(data){

                console.log(data);
                    if(data.status){
                        $("#notification_area").removeChild(target);
                        update_total_count(-1);
                        switch(target.querySelector(".headline>p").getAttribute("class")){
                            case "seen_notice":
                                update_seen_count(-1);
                                break;
                            case "unseen":
                                update_unseen_count(-1);
                                break;
                        }
                        if($$(".notification").length == 0){
                            $("#notification_area").innerHTML = "<p class='no_case'>All notifications are cleared</p>";
                        }
                    }else{
                        alert("DELETE ERROR: please try it again some other time ");
                    }
                   
                });
              
            }
        });
    }

    let add_tab = _=>{
        alert("add tab to the notification area");
    }

    return {notify, create_tab, add_tab, set_obj, toggle_detail, delete_notice};
}


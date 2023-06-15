class ChatArea{

    user_id;
    patner_id;
    patners;

    constructor(id){

        this.user_id = id;
    }
    set_chat_area(){
        
    }
    get_patner(id){
    }
   set_area(e){
      
        let patner_id = e.target.getAttribute("data-user-id");
        let patner = e.target.parentElement;
        let img_src = patner.querySelector("img").getAttribute("src");
        let fullname = patner.querySelector(".fullname").textContent;
        let status = patner.querySelector(".user_details input").getAttribute("value");

       // console.log(img_src,fullname,status);

        $("#chat_controlls").querySelector("#chat_area_to").value = patner_id;
        $("#chat_controlls").style.display = "block";
        $("#patners").style.display="";

        $("#patner_details_area").style.display="flex";
        $("#start_chat_text").style.display = "none";
        $("#patner_details_area").querySelector("img").setAttribute("src",img_src);
        $("#patner_details_area").querySelector("#patner_fullname").textContent = fullname;
        $("#patner_details_area").querySelector("#status_info").textContent = status;

        
       
    }

    static set_room_chat(chat){
       // console.log(chat);
        
        let room = $("#room");
        room.innerHTML = "";
        chat.forEach(message => {
            let div = document.createElement("div");
            div.innerHTML = "<p>"+ message.body+"</p>";
            if(message.user_id == $("#user_id").value){
                div.setAttribute("class","sender_balloon");
                div.setAttribute("data-message-id",message.id);
                div.classList.add("baloon");
                div.setAttribute("data-baloon-type","user");
                
                div.innerHTML = div.innerHTML + "<span class='status_info'>"+ChatArea.show_status_icon(message.status)+"</span>";
            }else{
                div.setAttribute("class","received_baloon");
                div.setAttribute("data-baloon-type","sender");
                div.setAttribute("data-message-id",message.id);
                div.classList.add("baloon");
                Message.update_message_view_status(message,"read",$("#chat_area_to").value);
                Message.update_unread_count(message.user_id);
            }
            room.appendChild(div);
            
        });
    }


   static show_status_icon(status){

    
        switch(status){

            case "sent":
                return "<i class='icofont-tick-mark'></i>";
            case "read":
                return "<span class='sent_msg'><i class='icofont-tick-mark'></i><i class='icofont-tick-mark'></i></span>";
        }
    }
 
   go_to_chat_room(e) {
           
    }
}

let chat_areaobj = new ChatArea($("#user_id").value);
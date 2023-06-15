class Message{
     to_id;
     user_id;
    message;
    connection;

    constructor(id){
        this.user_id = id;
       
    }

  
    set message(msg){
        this.message = msg;
    }

    static baloon_count(){
      return Number($$(".baloon").length) ;
      
    }
    static add_receiver_baloon(msg){

      let div = document.createElement("div");
      div.setAttribute("class","baloon");
      div.classList.add("received_baloon");
      let count = Message.baloon_count();
      count++;
      div.innerHTML = "<p>"+msg.body+"</p>"+"<span>"+"</span>"+"<input type='hidden' value="+count+">";
      $("#room").appendChild(div);
     // console.log(msg.status);
    }

     static addSenderBaloon(msg){

     // console.log("creating sender baloon");
      let div = document.createElement("div");
      div.setAttribute("class","sender_balloon");
      div.classList.add("baloon");
      div.setAttribute("data-unread-mark","true");
      let count = Message.baloon_count();
      count++;
      div.innerHTML = "<p>"+msg.body+"</p>"+"<span>"+msg.time+"</span>"+"<span class='status_info'>"+ChatArea.show_status_icon(msg.status)+"</span>"+"<input type='hidden' value="+count+">";
      $("#room").appendChild(div);

      return count;
    }
      send(e){

        e.preventDefault();
        let msg = $("#msg_body").value;

        if(msg!=""){
        
         let data_msg = {
            sender_id:$("#user_id").value,
            type:"chat",
            body:msg,
            background_image:$("#sidebar img").getAttribute("src"),
            status:"sent",
            to_id:$("#chat_area_to").value
          };
          let count = Message.addSenderBaloon(data_msg); 
          data_msg.count = count;
          window.conn.send(JSON.stringify(data_msg));
         
        }
        //;
     }

     receive(msg){

      msg = JSON.parse(msg);

      
      if(msg.to_id == this.user_id ){
       
        switch(msg.type){
         
          case "chat":  if($("#chat_container") == null){
              user_notice(msg);
              $("#chat_signal_btn").classList.add("show_signal");
          }else{
            if($("#chat_area_to").value == msg.sender_id){
              Message.add_receiver_baloon(msg);
              Message.update_message_status(msg,"read");
              //update message status
            }else{
              //update balloon
              Message.update_patner_message_count(msg);
              $("#chat_signal_btn").classList.add("show_signal");
            }
          } 
          break;

          case "status_update":if($("#chat_container") !=null && $("#chat_area_to").value != null){
            if($("#chat_area_to").value == msg.sender_id){
             
              let all_baloons = $$(".sender_balloon");
                  
                  all_baloons[ all_baloons.length-1].querySelector(".status_info").innerHTML = ChatArea.show_status_icon(msg.status);
                 
                  break;
              
             
            }
          }
          break;
          case "message_view_update":
            if($("#chat_container") != null){
             
             $$(".baloon").forEach(baloon => {
                if(baloon.getAttribute("data-unread-mark") == "true"){

                  //console.log("updating this",baloon);
                  baloon.querySelector(".status_info").innerHTML = ChatArea.show_status_icon(msg.status);
                }
             });

        }
        break;
        case "referal":
          user_notice({background_image:"assets/images/no-results.png",body:"A new case was referred to you"});
            console.log(msg.referer_id);
            let message = {
              type:"request_sent_confirmation",
              to_id:msg.sender_id,
              sender_id:msg.to_id,
              button_id:msg.button_id
            }
            window.conn.send(JSON.stringify(message));
            if($("#notification_container") != null){
             console.log(msg);
              my_notifier.create_tab(msg);
            }
         
          
          break;

          case "request_sent_confirmation":
            let elem = msg.button_id;
            console.log(elem);
           $(`#${elem}`).innerText = "sent";

           $(`#${elem}`).removeEventListener("click",refer_case);
           $(`#${elem}`).addEventListener("click",function(e){
            e.preventDefault();
            alert("The case hase been referred already. Please wait for response");
           });
           let parent =  $(`#${elem}`).parentElement.parentElement.parentElement;

           let connections =  parent.querySelectorAll(".connection_details_area");

           connections.forEach(connection=>{

            if(connection.getAttribute("data-connection-id") != msg.sender_id){
              connection.querySelector(".connection_overlay").style.display = "block";
            }
           });
            break;
          
            case "referal_response":
              if($("#notification_container") !=null){
                my_notifier.create_tab(msg);
                my_notifier.update_total_count(1);
                my_notifier.update_unseen_count(1);
                
              }else{
                user_notice({background_image:"assets/images/no-results.png",body:"Your refferal request was rejected"});
              }
              break;

            case "cancel request":
              if($("#notification_container") == null){
                user_notice({background_image:"assets/images/no-results.png",body:"A referal request sent to you has being cancelled"})
              }else{
               my_notifier.create_tab(msg);
              }
              break;

            case "delete complaint":
            case  "notice":
              if($("#notification_container") == null){
                user_notice({background_image:"assets/images/no-results.png",body:msg.subject})
              }else{
               my_notifier.create_tab(msg);
              }
              break;


      }
     }

     if(msg.type=="user_status" && msg.id !== $("#user_id").value){
          if($("#chat_container") !== null){
           
            if($("#chat_controlls").querySelector("#chat_area_to").value == msg.id){
              $("#patner_details_area #status_info").innerHTML = (msg.status == 1)?"online":"offline";
            }
             
          }
     }
    }

     static update_message_status(data,status){
      let msg = {
        type:"status_update",
        status:status,
        sender_id:$("#user_id").value,
        to_id:data.sender_id,
        msg_num:data.count,
        msg_id:data.msg_id
      }

      
      window.conn.send(JSON.stringify(msg));
     }

     static update_patner_message_count(msg){
     user_notice(msg);
      let connections = $$(".chat_patner");
      for(let i =0; i<connections.length; i++){

        if(connections[i].getAttribute("data-user-id") == msg.sender_id){
         // console.log(connections[i]);
          let current_msg_count = connections[i].querySelector(".message_count").querySelector("span").textContent;
          current_msg_count = Number(current_msg_count);
          current_msg_count++;
          current_msg_count = connections[i].querySelector(".message_count").querySelector("span").textContent = current_msg_count;
        }
      }
     }

      chat(){
      let uri = "http://localhost/cms/get_chat?user_id="+$("#user_id").value+"&patner_id="+$("#chat_area_to").value;
      get(uri,ChatArea.set_room_chat);
     }

     static update_message_view_status(message,status,to_id){

      let msg = {
        type:"message_view_update",
        status:status,
        sender_id:$("#user_id").value,
        to_id:to_id,
        msg_id:message.id
      }

      window.conn.send(JSON.stringify(msg));
     }

     static update_unread_count(connection_id){
      let patners = $$(".chat_patner");

      patners.forEach(patner=>{
        if(patner.getAttribute("data-user-id") == connection_id){
          let count =  patner.querySelector(".message_count span").innerText;
          count = count-1;

          if(count <= 0){
            patner.querySelector(".message_count span").innerText = " ";
          }else{
            patner.querySelector(".message_count span").innerText = count;
          }
        }
      })
     }
}

let msgObj = new Message($("#user_id").value);

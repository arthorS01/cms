class HistoryManager{

    static record(subject,meta_data){

     window.conn.send(JSON.stringify({type:"history",to_id:-1,user_id:$("#user_id").value,subject,meta_data}));
    }

    static search_history(){

        $("#close_search_btn").style.visibility = "visible";
        let search_key = $("#search_bar_value").value;
       
       
        if(search_key == "" || search_key == " "){

            return;
        }

        post("http://localhost/cms/history/search",{search_text:search_key},function(data){
          
        $("#history_area").style.filter = "blur(20px)";
        $("#search_results").innerHTML = data;
        $("#search_results").style.display = "flex";
        })
        }

        static clear(e){

           let choice = confirm("clear history record?");

           if(choice){

            let url = "http://localhost/cms/history/clear";
            delete_request(url,{id:$("#user_id").value},function(data){

                
                if(data[0].status == true){
                    alert("History cleared successfully");
                    get_request("http://localhost/cms/student/history",show_area)
                }else{
                    console.log(data);
                    alert("Sorry an error occurred");
                }
            });
           }

        }

        static toggle_detail(e){
            let target = e.target.parentElement.parentElement.parentElement.parentElement;

            let detail_area = target.querySelector(".history_detail_area");
           
            let currentList =  detail_area.classList;
            if([...currentList].includes("show_details")){
    
                target.classList.remove("transform_btn");
                detail_area.classList.remove("show_details");
    
            }else{

               target.classList.add("transform_btn");
                detail_area.classList.add("show_details");
                
            }
           
        }
}




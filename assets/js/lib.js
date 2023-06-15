

function $(selector){

    return document.querySelector(selector);
}

function $$(selector){
    return document.querySelectorAll(selector);
}
function delete_request(url,body,callback){

    let req = new Request(url,{
        method:"DELETE",
        body:JSON.stringify(body)
    });

    fetch(req)
    .then(response=>{
        return response.json();
    })
    .then(data=>{
       // console.log(data);
        callback(data);
    })
    .catch(err=>{
        console.log(err);
    });
}
function post(url,data,callback){

    let return_data = null;

    let req = new Request(url,{
        method:"POST",
        body:JSON.stringify(data)
    });

    fetch(req)
    .then(response=>{
        return response.json();
    })
    .then(data=>{
       // console.log(data);
        callback.call(this,[data]);
    })
    .catch(err=>{
        console.log(err);
    });
}

function get(url,callback){

    let return_data = null;

    let req = new Request(url,{
        method:"GET",
    });

    fetch(req)
    .then(response=>{
        return response.json();
    })
    .then(data=>{
        //console.log(data);
        callback(data);
    })
    .catch(err=>{
        console.log(err);
    });
}

function process_form(data){

    if(data[0].type== "error"){
        show_error(".error", data[0].message );
    }

    if(data[0].type == "confirm"){
        alert("successfull");
        location.assign("http://localhost/cms/");
    }
    console.log(data);
}

function login(response){

    if(response[0].type == "confirm"){
       
        location.assign(response[0].redirect);

    }else{
       show_error(".error",response[0].message);
    }
}

function show_error(id,message){

    $(id).textContent = message;
    $(id).style.visibility = "visible";
        setTimeout(function(){
            ($(id).style.visibility = "hidden");
        },10000)


    
    return true;
}

//navigation 

function show_area(data){

  
    $("main").innerHTML = data;
    setTimeout(_=>{
        $("#page_loader").style.display = "none";
    },1000);
   
}

function user_notice(data){

    
    $(".notice_message").style.display = "flex";
    $(".notice_message img").setAttribute("src",data.background_image);
    $(".notice_message .message p").innerHTML = data.body;
    $(".notice_message").classList.add("animate");
    setTimeout(() => {
        $(".notice_message").style.display = "none";
        $(".notice_message").classList.remove("animate");
    }, 10000);
}
function update_view(data){

    $("main").querySelector("#complaint_container").innerHTML = data;
}

function update(url,data,callback=null){

    let req = new Request(url,{
        method:"UPDATE",
        body:JSON.stringify(data)
    });

    fetch(req)
    .then(response=>{
        return response.json();
    })
    .then(data=>{
        //console.log(data);
        if(callback!=null){
            callback(data);
        }
      
    })
    .catch(err=>{
        console.log(err);
    });
 
}

function drop_complaint(e){
 

    let response = confirm("Would you want to drop this complaint?");
    if(response){

        let target_parent = e.target.parentElement.parentElement;
        let id = target_parent.getAttribute("data-case-id");
        console.log(id);
        let body = {
            type:"delete complaint",
            id,
            to_id:$("#user_id").value,
            subject:"deleted a complaint",
            meta_data:{
              case_id: id,
              subject:"deleted a complaint",
              response:false 
            },
            
          }
          window.conn.send(JSON.stringify(body));

        get("http://localhost/cms/complaint/delete_files?complaint_id="+id,data=>{
            if(data.status){
                alert("Case has been droped");
                let container = target_parent.parentElement;
                container.removeChild(target_parent);
            }
        });
    }else{
        alert("dont drop");
    }
}
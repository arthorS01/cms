
 //update what happens when a case view is opened

 let file_receipient = [];
  let file_link = null;

  function upload_complaint(){

    let num_of_submitions = 0; // to prevent the error of submiting the form twice

    let form = document.querySelector("#complaint_form");

    form.addEventListener("submit", (ev)=>{
      ev.preventDefault();
      
      let action = form.getAttribute("action");
  
      let form_myData = new FormData(form);
  
      if($("#complaint_form #details").value == ""){
        alert("Please fill out the form");
        return ;
      }
      let req = new Request(action,{
        body:form_myData,
        method:"POST",
        uri:action
      });
  
      
      if(num_of_submitions < 1){
        
        ++num_of_submitions;
      fetch(req).then(response=>{
        return response.json();
      }).then(data=>{
        alert("complaint was uploaded successfully");
        get("http://localhost/cms/student/complaint",show_area);
      }).catch(err=>{
        alert("ERROR: sory please try it again some other time");
      });
    

  }});
    
  }

  // complaint area for admin
  function view_complaint(e=null){

    if(e!=null){
      e.preventDefault();
    }
    //get the account type
    let acc_type = $("#acc_type").value;
  
   
    switch(acc_type){
      case "admin":
              show_admin_view(e);
              let message ={
                type:"notification",
                sender_id:$("#user_id").value
              }
              break;
      case "student":
            show_student_view(e);
            break;
    }
  }

  function show_admin_view(e){
    e.stopPropagation();

    
    let view_container_num = e.target.getAttribute("data-view-container-id");
    let case_view =  $("#case_view_container"+view_container_num);

  
     case_view.style.display = "flex";
      //update complaint status;
      if($("#filter_value").value == "unseen" && $("#case_status").value == "unseen"){
        let case_detail = case_view.querySelector(".case_body p").innerText;
        let uploaded_by_id = case_view.querySelector("#complainer_id").value;
  
        let uri = "http://localhost/cms/complaint/update";
        let body ={
          key:"status",
          value:"seen",
          id:view_container_num
        };
  
       let req = new Request(uri,{
        method:"POST",
        body:JSON.stringify(body)
       });
  
       fetch(req).then(response=>{
        return response.json();
       }).then(data=>{
        if(data.status){
          
          $("#case_status").value = "seen";

          window.conn.send(JSON.stringify({
            type:"notice",
            to_id:uploaded_by_id,
            subject:"Your complaint has been seen",
            meta_data:{  
              case_detail 
            }
          }));
        }
       });
      }

     //get files attached to complaint
     uri = "http://localhost/cms/complaint/files?complaint_id="+view_container_num;
     let container = $("#case_view_container"+view_container_num);
     get(uri,function(data){
      if(data.length !== 0){
       
      let files = data.files;
     
     files.forEach(file => {
        switch(file.type){
          case "pdf":container.querySelector(".case_files").appendChild(pdf_file_dom(file.location,file.id));
                break;
          case "jpeg":
          case "png":
          case "jpg":container.querySelector(".case_files").appendChild(img_file_dom(file.location,file.id));
        }
      });
      
      add_event(".share_btn","click", e=>{
        let target = e.currentTarget;
        let case_view = target.parentElement.parentElement.parentElement.parentElement.parentElement;
        file_link = target.previousElementSibling.getAttribute("href");
        case_view.querySelector("#share_to_view").classList.add("show_view");

      
      });

      add_event(".delete_file_btn","click", e=>{
        let target = e.currentTarget;
        let case_view = target.parentElement.parentElement.parentElement.parentElement.parentElement;
        let response = confirm("Delete this file?");
        if(response){
          //target.querySelector("i").classList.add("loading");
          let url = "http://localhost/cms/complaint/delete_file";
          delete_request(url,{file_id:target.getAttribute("data-file-id")},data=>{

            console.log(data);

            if(data[0].status){
             
              let all_file_tiles = case_view.querySelectorAll(".file_tile");
              all_file_tiles.forEach(tile=>{
             
                let tile_id = tile.querySelector(".delete_file_btn").getAttribute("data-file-id");
             
                if(tile_id == target.getAttribute("data-file-id")){
               
                 case_view.querySelector(".case_files").removeChild(tile);
                 if(case_view.querySelectorAll(".case_files .file_tile").length == 0){
                  case_view.querySelector(".case_files").innerHTML = "<p>No files attached</p>"
                 }
                }
              });

              alert("Deleted successfully");
              //send notification to student
            }
          });
        }
      

      
      });
    }else{
      container.querySelector(".case_files").innerHTML = "<p>No files attached</p>"; 

    }
      
     });

   
  }
  

  function show_student_view(e){


    let view_container_num = e.target.getAttribute("data-view-container-id");

    $("#case_view_container"+view_container_num).style.display = "flex";

    uri = "http://localhost/cms/complaint/files?complaint_id="+view_container_num;
     let container = $("#case_view_container"+view_container_num);

     get(uri,function(data){
      if(data.length !== 0){
       
      let files = data.files;
   
     files.forEach(file => {
        switch(file.type){
          case "pdf":container.querySelector(".case_files").appendChild(pdf_file_dom(file.location,file.id));
                break;
          case "jpeg":
          case "png":
          case "jpg":container.querySelector(".case_files").appendChild(img_file_dom(file.location,file.id));
        }
      });
      
    }else{
      container.querySelector(".case_files").innerHTML = "<p>No files attached</p>"; 

    }
     });

   
  }
  
  function close_view(e){

    let view_id = e.target.getAttribute("data-view-id");
    
    
    $("#case_view_container"+view_id).style.display = "none";

  }

  function change_view(e){

    let case_filter = e.target.value;

    switch(case_filter){
      case "seen":close_all_filter_cases();
                  $("#seen").style.display = "block";
                  break;
      case "unseen":close_all_filter_cases();
                    $("#unseen").style.display = "block";
                    break;
      case "special":close_all_filter_cases();
                    $("#special").style.display = "block";
                    break;
      case "active":close_all_filter_cases();
                    $("#active").style.display = "block";
                    break;
    }

  }

  function close_all_filter_cases(){

    $("#seen").style.display = "none";
    $("#unseen").style.display = "none";
    $("#special").style.display = "none";
    $("#active").style.display = "none";
  }

 function pdf_file_dom(location,id){

  let div = document.createElement("div");
  div.innerHTML = "<div class='file_tile'><img src='assets/images/icons8-pdf-80.png' ><div class='file_controll_area'><a target='blank' href='"+location+"'><button><i class='icofont-monitor'></i></button></a><button class='share_btn'><i class='icofont-share-alt'></i></button><button class='delete_file_btn' data-file-id="+id+"><i class='icofont-trash'></i></div></div>";
 return div;
 }

 function img_file_dom(location,id){

  let div = document.createElement("div");
  div.setAttribute("class","file_tile");
  div.innerHTML = "<div><img src='assets/images/icons8-image-file-80.png' ><div class='file_controll_area'><a  target='blank' href='"+location+"'><button><i class='icofont-monitor'></i></button></a><button class='share_btn'><i class='icofont-share-alt'></i></button><button class='delete_file_btn'  data-file-id="+id+"><i class='icofont-trash'></i></button></div></div>";

 return div;
 }

 function update_case(e){

  let elem = e.target;
  let caseid = elem.getAttribute("data-view-container-id");

  let uri = "http://localhost/cms/complaint/update";
      let body ={
        key:"status",
        value:"active",
        id:caseid
      };

     let req = new Request(uri,{
      method:"POST",
      body:JSON.stringify(body)
     });

     fetch(req).then(response=>{
      return response.json();
     }).then(data=>{
      if(data.status == true){
        alert("successfull");
        get("http://localhost/cms/admin/complaint",update_view);
        add_event(".close_view_btn","click",close_view);
      }else{
        alert("there was an error");
      }
     })
 }

 function remove_case_view(elem,view=null){

  let current_case =(view==null)? $("#filter_value").value: view;

  let cases = $$(`#${current_case} .row`);

  cases.forEach(entry =>{
    
   let entry_id =  entry.querySelector(".trash_btn").getAttribute("data-case-id");
   let case_id =  elem.querySelector(".trash_btn").getAttribute("data-case-id");
   
    if(entry_id == case_id){

     $(`#${current_case} tbody`).removeChild(elem);
    }
  })
 }
 
 function add_special_case(elem){
  let current_case = "special";
  $(`#${current_case} tbody`).insertBefore(elem,$(`#${current_case} tbody`).children[0]);
  elem.querySelector(".case_view_container").style.display = "none";
  if(no_case = $(`#${current_case} .no_case`)){
      no_case.style.display = "none";
    }

 }

 function remove_special_case(elem){
  let current_case = "seen";
  $(`#${current_case} tbody`).insertBefore(elem,$(`#${current_case} tbody`).children[0]);
  elem.querySelector(".case_view_container").style.display = "none";
  if(no_case = $(`#${current_case} .no_case`)){
      no_case.style.display = "block";
    }

  if(no_case = $(`#${current_case} .no_case`)== null){

    let p = document.createElement("p");
    p.classList.add("no_case");
    p.innerText =" no special cases";

    $("#special").appendChild(p);
  }

 }

 function update_special_case(e){

  let elem = e.target;
  let caseid = elem.getAttribute("data-view-container-id");
  let case_elem = elem.parentElement.parentElement.parentElement.parentElement.parentElement;
  let state = "special";
  
  if(elem.checked){
    let response = confirm("This case would be noted as a special case");
   
   if(response){
    alert("This case has been added to special cases");
    remove_case_view(case_elem,$("#filter_value").value);
    add_special_case(case_elem);
    update_special_case_count(1);
   } 
  }else{
    
    let response = confirm("this case would be removed from special cases");
    if(response){
      alert("This case has been removed from special cases");
      remove_case_view(case_elem,$("#filter_value").value);
      remove_special_case(case_elem);
      update_special_case_count(-1);
    }
  }
    
  

  let uri = "http://localhost/cms/complaint/update";
      let body ={
        key:"status",
        value:"special",
        id:caseid
      };

      
     let req = new Request(uri,{
      method:"POST",
      body:JSON.stringify(body)
     });

     //fetch(req).then(response=>{
     // return response.json();
    // }).then(data=>{
     // if(data.status){
      //  alert("successfull! Please refresh area to reflect changes");
        //get("http://localhost/cms/admin/complaint",update_view);
        
     // }else{
     //   alert("there was an error");
     // }
    // })
 }

 function update_delete_view(e){
  let node = e.target.parentElement.parentElement.parentElement;
  let current_status = $("#filter_value").value;

  let table_body = $(`#${current_status} tbody`);

  table_body.removeChild(node);

  switch(current_status){
    case "special":
      update_case_total(-1);
      update_special_case(-1);
      break;
    case "seen":
    case "active":
    case "unseen":
      update_case_total(-1);
      break;
  }

  if($$(`#${current_status} .row`).length == 0){
    let p = document.createElement("p");
    p.setAttribute("class","no_case");
    p.innerText = "No seen cases";
    $(`#${current_status}`).appendChild(p);

  }

  
 }

 function update_case_total(increment){
  let count = $("#total_complaints");
  let current_value = Number(count.querySelector(".count").innerText);
  count.querySelector(".count").innerText = current_value+increment;
  
 }

 function update_special_case_count(increment){
  let count = $("#total_special_cases");
  let current_value = Number(count.querySelector(".count").innerText);
  count.querySelector(".count").innerText = current_value+increment;
 }
 function delete_case(e){

  let target = e.target.parentElement;
  

  let id= target.getAttribute("data-case-id");
  let to_id = target.getAttribute("data-uploaded-by-id");
  let subject = target.getAttribute("data-subject");
  let upload_date = target.getAttribute("data-upload-date");
  
  
 
  get("http://localhost/cms/complaint/delete_files?complaint_id="+id,function(data){
    if(data.status){
    
    let body = {
      type:"delete complaint",
      id,
      to_id,
      subject,
      meta_data:{
        case_id: id,
        subject,
        upload_date
      },
      response:false 
    }
    
    window.conn.send(JSON.stringify(body));
  
      let history_meta_data = {
        response:false,
        case_id: id,
        subject,
        upload_date
      }
      HistoryManager.record("Deleted a case",history_meta_data);
      update_delete_view(e);
    }else{
      console.warn("failed to delete files");
    }
  });
 }

 function show_refer_container(e=null){
  if(e!== null){
    e.preventDefault();
  }

  
  let elem = e.target;
  let id = elem.getAttribute("data-case-id");

  $("#refer_container"+id).style.display = "block";

 }

 function close_refer_container(e=null){

  let target = e.target.parentElement;
 target.style.display = "none";
 }

 function refer_case(e=null){

  let elem  = e.target;
  let refer_to_name = elem.getAttribute("data-connection-name");
  let refer_to_id = elem.getAttribute("data-connection-id");
  let case_id = elem.getAttribute("data-case-id");
  let uploader_name = elem.getAttribute("data-case-firstname")+" "+elem.getAttribute("data-case-lastname");
  let date_of_upload = elem.getAttribute("data-case-upload_date");
  let subject = elem.getAttribute("data-case-subject");
  let referer_id = $("#user_id").value;


  let choice = confirm("Refer this case to "+ refer_to_name + " ?");
  if(choice){

    let message = {
      type:"referal",
      subject:"A case has being reffered to you",
      to_id:refer_to_id,
      sender_id:referer_id,
      button_id:elem.getAttribute("id"),
      meta_data:{
        response:true,
        case_id:case_id,
        sender_id:referer_id,
        uploaded_by:uploader_name,
        date_uploaded:date_of_upload,
        subject:subject
      }
    };
    
    window.conn.send(JSON.stringify(message));
    let history_meta_data = {
      response:false,
      case_id:case_id,
      uploaded_by:uploader_name,
      date_uploaded:date_of_upload,
      subject:subject
    }
    HistoryManager.record("Refer case to "+refer_to_name,history_meta_data);
  }
 }

 function share_file(e){
  e.preventDefault();
    let caption = e.target.querySelector("input").value;

    if(file_receipient.length == 0){
     alert("Please select at least one receiver");
    }else{
      let body = caption+"<a href='"+file_link+"'><button class='file_view_btn_msg'>view</button></a>";
     
      file_receipient.forEach(receiver=>{

        let message = {
          type:"chat",
          to_id:receiver.querySelector(".send_file_to_check").getAttribute("data-connection-id"),
          sender_id:$("#user_id").value,
          body
        }
        window.conn.send(JSON.stringify(message));
        
      });

      alert("file sent");
    }
  
 
 }
 function toggle_file_receipient(e){
  let target = e.currentTarget.parentElement.parentElement;
  if(file_receipient.includes(target)){
    let index = file_receipient.findIndex(function(entry){
      if(entry === target){
        return true;
      }
    })
   file_receipient.splice(index,1);
  }else{
    file_receipient.push(target);
  }
  

 }

 function close_share_area(e=null){
    let parent_container = e.target.parentElement;

    let checkboxes = parent_container.querySelectorAll(".send_file_to_check");

    checkboxes.forEach(box=>{
      if(box.checked){
        box.checked = false;
      }
    })
    parent_container.classList.remove("show_view");
 }
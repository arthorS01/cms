
let btns = $$(".nav_btn");
let  main_section = $("main");

btns.forEach(btn=>{
    
    btn.addEventListener("click", (ev)=>{

        ev.preventDefault();

        get_request(btn.getAttribute("data-target"),show_area);
        $(".active_btn").classList.remove("active_btn");
       ev.target.parentElement.parentElement.classList.add("active_btn");
    });
})

if($("#dashboard_container") != null){
 
    
    switch($("#acc_type").value){
        case "student":
            get_request("http://localhost/cms/student/complaint",show_area);
            break;
        case "admin":
            get_request("http://localhost/cms/admin/complaint",show_area);
            break;

    }
  
}



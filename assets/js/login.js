let btn = $("#login_btn");
let form = $("#login_form");
let url = form.getAttribute("action");

form.addEventListener("submit", function(ev){

    ev.preventDefault();
    let obj = {};
    let form_data = $$(".form_data");

   
    form_data.forEach((data)=>{
       //clean each data before sending
        obj[data.name] = data.value;
    });


    post(url,obj,login);
});
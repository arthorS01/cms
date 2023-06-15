let btn = $("#create_account_btn");
let form = $("#create_account_form");
let url = form.getAttribute("action");

form.addEventListener("submit", function(ev){

    ev.preventDefault();
    let obj = {};
    let form_data = $$(".form_data");

    if($("#cpass").value == $("#pass").value ){

        form_data.forEach((data)=>{
            //clean each data before sending
             obj[data.name] = data.value;
         });
     
         post(url,obj,process_form);
      
    }else{
        show_error(".error", "Please make sure that both passwords are the same" );
    }

  
});
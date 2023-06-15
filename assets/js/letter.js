function letter(){
    let preview = function(e){
        alert("preview letter");
        let all_preview_text = $$(".preview_text");
        all_preview_text.forEach(text => {

            let sibling = text.nextElementSibling;
            let value = sibling.value;
            text.innerText = value;
            text.classList.add("show_preview");
            sibling.style.opacity = "0";
            e.target.innerText = "hide preview";

            e.target.removeEventListener("click",preview);
            e.target.addEventListener("click",hide_preview);
        });
    };

    let send = function(e){
        alert("send letter");

        e.preventDefault();
        let to_address = render_to_address($("#letter_to_address").value);
        let title = render_heading($("#letter_title").value);
        let body = render_body($("#letter_body").value);
        let conclusion = render_conclusion($("#letter_conclusion").value);
        let salution = render_salution($("#letter_greeting").value);

        let letter = to_address+salution+title+body+conclusion;
        
        
        console.log(to_address,title,body,conclusion,salution);

    }

    let clear = function(){
        alert('clear letter area');
        let all_preview_text = $$(".preview_text");
        all_preview_text.forEach(text => {

            let sibling = text.nextElementSibling;
            
            sibling.value = "";
        });

    }
    let close_area = function(e){
        alert("close area");
    }

    let hide_preview = function(e){
        alert("preview letter");
        let all_preview_text = $$(".preview_text");
        all_preview_text.forEach(text => {

            let sibling = text.nextElementSibling;
            text.classList.remove("show_preview");
            sibling.style.opacity = "1";
            e.target.innerText = "preview";
        });

            e.target.removeEventListener("click",hide_preview);
            e.target.addEventListener("click",preview);
    }

    let render_to_address = function(str){
        return `<div id='to_address_container'> <div>${str}</div></div>`
    }

    let render_heading = function(str){
        return `<h1 id='letter_heading'>${str}</h1>`;
    }

    let render_body  = function(str){
     return `<div id='letter_body'>${str}</div>`;
    }

    let render_salution = function(str){
        return `<div id='salutation_container'> <div>${str}</div></div>`
    }

    let render_conclusion = function(str){
        return `<div id='conclusion_container'> <div>${str}</div></div>`
    }

    return {preview,send,clear,close_area};
}

var my_letter = letter();
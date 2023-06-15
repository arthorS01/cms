
my_referal_handler = get_referal_obj();

function get_referal_obj(){

    let accept_case = function(e){

        let target = e.target;
        let parent = target.parentElement;
        let notice_id = e.target.parentElement.parentElement.parentElement.getAttribute("data-notification-id");
        let sibling = e.target.nextElementSibling;

        window.conn.send(JSON.stringify({type:"referal_response",subject:"Your case request was accepted",accepter_id:$("#user_id").value,case_id:parent.getAttribute("data-case-id"), to_id:parent.getAttribute("data-referer-id"),response:true,notice_id,meta_data:{case_id:parent.getAttribute("data-case-id")}}));
        sibling.style.opacity = "0";
        target.innerText = "Accepted";
        
        let history_meta_data = {
            response:false,
            case_id:parent.getAttribute("data-case-id")
        }
        HistoryManager.record("Accepted refferal request" ,history_meta_data);
    }

    let cancel_request = (e)=>{

        let receiver_id = e.target.getAttribute("data-connection-id");
        let case_id = e.target.getAttribute("data-case-id");
        let request_id = e.target.getAttribute("data-request-id");
                window.conn.send(JSON.stringify({
                    type:"cancel request",
                    to_id:receiver_id,
                    request_id: request_id,
                    sender_id:$("#user_id").value,
                    subject:"Cancelled request",
                    meta_data:{
                        sender_id:$("#user_id").value,
                        case_id:case_id,
                        response:false
                    }
                }));

                e.target.innerText = "Request cancelled";
                e.target.removeEventListener("click",refer_case);

    }

    let decline_case = (e)=>{
        let target = e.target;
        let parent = target.parentElement;
        let notice_id = e.target.parentElement.parentElement.parentElement.getAttribute("data-notification-id");
        let sibling = e.target.previousElementSibling;

        window.conn.send(JSON.stringify({type:"referal_response",subject:"Your case request was rejected",accepter_id:$("#user_id").value,case_id:parent.getAttribute("data-case-id"), to_id:parent.getAttribute("data-referer-id"),response:false,notice_id,meta_data:{case_id:parent.getAttribute("data-case-id")}}));
        sibling.style.opacity = "0";
        target.innerText = "Declined";

        let history_meta_data = {
            response:false,
            case_id:parent.getAttribute("data-case-id")
        }
        HistoryManager.record("Declined refferal request" ,history_meta_data);
    }

    let open_refer_container = e=>{

        let elem = e.target;
        elem.nextElementSibling.style.display = "block";

    }
    return {accept_case,decline_case,cancel_request,open_refer_container};
}
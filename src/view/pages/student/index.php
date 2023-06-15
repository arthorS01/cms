<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="http://localhost/cms/assets/icofont/icofont.min.css" type="text/css" rel="stylesheet">
    <link href=<?="http://localhost/cms/assets/style/nav.css";?> type="text/css" rel="stylesheet">
    <link href=<?="http://localhost/cms/assets/style/workspace.css";?> type="text/css" rel="stylesheet">
    <link href=<?="http://localhost/cms/assets/style/chat.css";?> type="text/css" rel="stylesheet">
    <link href=<?="http://localhost/cms/assets/style/complaint_area.css";?> type="text/css" rel="stylesheet">
    <link href=<?="http://localhost/cms/assets/style/history.css";?> type="text/css" rel="stylesheet">
    <link href=<?="http://localhost/cms/assets/style/settings.css";?> type="text/css" rel="stylesheet">
    <link href=<?="http://localhost/cms/assets/style/notification.css";?> type="text/css" rel="stylesheet">
    <link href="http://localhost/cms/assets/style/footer.css" type="text/css" rel="stylesheet">
    <link href="http://localhost/cms/assets/style/letter.css" type="text/css" rel="stylesheet">
    <link href="assets/style/pageLoader.css" rel="stylesheet">
    <link href="assets/style/createC.css" rel="stylesheet">
    <title><?=$_SESSION["fname"]?> | welcome</title>
</head>
<body>

<section id="page_loader">
        <div id="box">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        </div>
      
    </section>

    <div id="container">
    <div id="sidebar">
           <!-- <span><?=$_SESSION["acc_type"]?></span>-->
           
            <div class="background_image">
                <img src=<?="http://localhost/cms/assets/images/".$_SESSION["background_image"]?> height="50px" width="50px">
            </div>
            <input type="hidden" value= <?=app\App::get_id()?> name="user_id" id="user_id">
            <input type="hidden" value= <?=$_SESSION["acc_type"]?> name="acc_type" id="acc_type">
            <button id="log_out"><a href="http://localhost/cms/logout"><i class="icofont-exit"></i></a> </button>
            <nav>
                <ul>
                   <!-- <li class="active_btn"><button class="nav_btn"  data-target="http://localhost/cms/student/dashboard"><i class="icofont-dashboard-web"></i></button></li>-->
                    <li class="active_btn"><button class="nav_btn" data-target="http://localhost/cms/student/complaint"><i class="icofont-files-stack"></i></button></li>
                    <li><button  class="nav_btn"  data-target="http://localhost/cms/student/chat"><i class="icofont-chat"></i> <span id="chat_signal_btn" class=<?=(\app\App::get_message()->available_unread()!=true)? "":"show_signal"?>></span></button></li>
                    <li><button class="nav_btn"  data-target="http://localhost/cms/student/notification"><i class="icofont-notification"></i><span id="chat_signal_btn" class=<?=(\app\App::get_notification()->available_unread()!=true)? "":"show_signal"?>></span></button></li>
                    <li><button class="nav_btn"  data-target="http://localhost/cms/student/history"><i class="icofont-history"></i></button></li>
                    <li><button class="nav_btn"  data-target="http://localhost/cms/student/settings"><i class="icofont-settings-alt"></i></button> </li>
                </ul>
            </nav>
        </div>
        <div class="notice_message">
        <button>x</button>
                <div class="background_img">
                    <img src="#" height="50" width="50">
                </div>
                <div class="message">
                    <p>Hi</p>
                </div>
        </div>
        <main>
            <?php require_once "dashboard.php"; ?>
        </main>
    </div>
</body>

<script src="http://localhost/cms/assets/js/lib.js"></script>
<script src="http://localhost/cms/assets/js/get_request.js"></script>
<script src="http://localhost/cms/assets/js/navigation.js"></script>
<script src="http://localhost/cms/assets/js/complaint.js"></script>
<script src="http://localhost/cms/assets/js/chat_area.js"></script>
<script src="http://localhost/cms/assets/js/message.js"></script>
<script src="http://localhost/cms/assets/js/history.js"></script>
<script src="http://localhost/cms/assets/js/notification.js"></script>
<script>
  document.addEventListener("DOMContentLoaded",function(){

    conn = new WebSocket('ws://localhost:8080');
    conn.onopen = function(e) {
        console.log("Connection established!");
    };

    conn.onmessage = function(e) {
    let msg = e.data;
    console.log(msg);
    msgObj.receive(msg);
    };

       
});
</script>
<script src="http://localhost/cms/assets/js/letter.js"></script>
<script src="http://localhost/cms/assets/js/observer.js"></script>
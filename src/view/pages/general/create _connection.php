
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/style/createC.css" rel="stylesheet">
    <title>create connection</title>
   
</head>
<body>
    <main>

    <p id="intro_text"> Let's get you connected </p>
    <div id="cards_area">


        <?php
           $users =  \app\App::get_user()->get_user_by_type("admin");
           $connect_from_id = \app\App::get_id();  
            $admin_decription = "As an admin, it is important 
            that you connect with the fellow admin you work under or with when dealing with student complaints";
            $student_decription = "As a student, it is important you connect wiht the admin account of your current course advisor";
           foreach($users as $user){
    
            if($user["id"] === \app\App::get_id()){
                continue;
            }
            $connect_to_id = $user["id"];
            $firstname = $user["firstname"];
    
            ?>
          
            <div class='user_card'> 
                <img src='assets/images/vlcsnap-2021-02-07-22h05m47s372.png' height='50' width='50'>
                <span><?=$firstname?></span>
                <form action='connect/' method='POST'>
                <input type ='hidden' name='connect_to_id' value=<?=$connect_to_id?>>
                <input type = 'hidden' name='connect_from_id' value=<?=$connect_from_id?>>
                <button class='connect_btn'>Connect</button>
                </form>
            </div>
        <?php } ?>

       
    </div>
    <p id="outro_text"><?=($_SESSION["acc_type"] == "admin")? $admin_decription: $student_decription ?></p>
</main>
</body>
</html>

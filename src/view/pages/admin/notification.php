<?php

    $total_count = count($param);

    $seen_count = $unseen_count = 0;


    foreach($param as $notification){

        switch($notification["status"]){

            case "seen":
                $seen_count++;
                break;
            case "unseen":
                $unseen_count++;
                break;
                
        }
    }

?>  
   

        <div id="notification_container"> 
        
            <section id="notification_dashboard">
                <div id="total_count">
                <span class="text">Notifications</span> <span class="count"><?=$total_count?></span>
                </div>
                <div id="seen_count">
                <span class="text">seen</span><span class="count"><?=$seen_count?></span>
                </div>
                <div id="unseen_count">
                <span class="text">unseen</span><span class="count"><?=$unseen_count?></span>
                </div>
            </section> 

            <section id="notification_area"> 
                
            <?php foreach($param as $notification): ?>
                <div class="notification" data-notification-id=<?=$notification["id"] ?>>

                    <div class="headline">
                        <p class= <?=($notification["status"] == "seen")?"seen_notice" :"unseen" ?>></p>
                        <p><?=$notification["subject"]?></p>
                        <p><?=date("Y/m/d",(int)$notification["date"])?></p>
                        <p><span>see details</span><button><i class="show_detail_btn icofont-arrow-down"></i></button></p>
                        <p class="trash_btn"><button><i class="icofont-trash"></i></button></p>
                    </div>
                        <?php $details = JSON_decode($notification["meta_data"],true); ?>
                    <div class="details_area">
                       
                    <?php foreach($details as $entry=>$value): ?>

                        <?php if($entry == "response") {
                            continue;
                        }
                        ?>

                        <div class="detail_entry"><p><?=$entry." : "?> </p> <p><?=$value?></p> </div>

                    <?php endforeach;?>

                   
                    <?php if(JSON_decode($notification["meta_data"],true)["response"] && $notification["response"] == "Null"): ?>
                        <div class="option" data-referer-id=<?=JSON_decode($notification["meta_data"],true)["sender_id"]?> data-case-id=<?=JSON_decode($notification["meta_data"],true)["case_id"] ?>><button class="accept_btn" >Accept</button><button class="decline_btn">Decline</button></div>
                    <?php endif; ?>

                    <?php if(JSON_decode($notification["meta_data"],true)["response"] && $notification["response"] != "Null"): ?>
                        <div class="option"> <span class=<?=($notification["response"]== "Accepted")? "accept_btn": "decline_btn" ?>><?=$notification["response"]?></span></div>
                    <?php endif; ?>
                    </div>

                </div>

                <?php endforeach; ?>
                <?php if($total_count == 0):?>
                <p class="no_case">All notifications are cleared</p>
                <?php endif; ?>
                
            </section>

            
        </div>
       
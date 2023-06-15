<div id="chat_container">           
  
    <section id="connections">

        <button id="contact_btn"><i class="icofont-contacts"></i></button>
        <div id="patners">
       
            <?php foreach($param as $connection):?>
            <div value =<?= $connection["id"]?> class="chat_patner" data-user-id = <?= $connection["id"]?>> 
                    <div class="patner_container">
                        <div class=<?=($connection["online"])? "online": "offline" ?>>
                            <img src=<?="http://localhost/cms/assets/images/".$connection["background_image"]?> height="50px" width="50px">
                        </div>
                        <div class="user_details">
                            <p class="fullname"><?= $connection["fullname"]?></p>
                            <p class="acc_type"><?= $connection["acc_type"]?></p>
                            <p class="last message"> <?=\app\App::get_message()->get_last_message($connection["id"])?></p>
                            <input type="hidden" value=<?=($connection["online"])? "online": "offline" ?> name="active_status">
                        </div>
                        <div class="message_count">
                            <span>
                                <?php $unread_count = \app\App::get_message()->get_unread_count($connection["id"]);
                                echo ($unread_count == 0)?  " ":$unread_count;
                            ?>
                            </span>
                        </div>
                        <div class="chat_patner_overlay" data-user-id = <?= $connection["id"]?>></div>
                    </div>
            </div>
            <?php endforeach;?>
            </div>
    </section>

    <section id="chat_area">
        <section id="patner_details_area">
                <div id="background_image">
                    <img src=<?="http://localhost/cms/assets/images/".$connection["background_image"]?> height="50px" width="50px">
                </div>

                <div class="user_details">
                    <p id="patner_fullname">fullname</p>
                    <span id="status_info"><?=($connection["online"])? "online": "offline" ?></span>
                </div>
            
            </section>
        
        <div id="room">
        <h4 id="start_chat_text">Select a user to start a chat</h4>
        </div>

        <div id="chat_controlls">

            <div id="text_area">
                <form>
                    <input type="hidden" name="user_id" value = <?= \app\App::get_id()?>>
                    <input type="text" id="msg_body"placeholder="Say something here" name="msg_body">
                    <input type="hidden" id="chat_area_to" name="patnerid">
                   <!-- <button id="emoji_btn"><i class="icofont-simple-smile"></i></button> -->
                    <button id="send_msg_btn"><i class="icofont-send-mail"></i></button>
                </form>
            </div>

           <!-- <div id="emoji_area"></div> -->

        </div>
    
    </section>

          
</div>
            </div>
 
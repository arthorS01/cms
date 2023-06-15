        <?php
                $total_count = count($param[0]);
                $special_count = $active_count = 0;

                $refered_cases = array_map(function($entry){
                    return $entry["case_id"];

                },$param[1]);
        
                foreach($param[0] as $case){
                    if($case["status"] == "special"){
                        ++$special_count;
                    }
                }

                foreach($param[0] as $case){
                    if($case["status"] == "active"){
                        ++$active_count;
                    }
                }

           

                $connections = \app\App::get_user()->get_admins();
                
           
        ?>
        <div id="complaint_container">
            <section id="complaint_workspace_header">
            <div id="welcome_area"></div>           
                <div id="dashboard">
                    <div id="total_complaints">
                        <span class="text">Complaints</span> <span class="count"><?=$total_count?></span></div>
                    <div id="total_special_cases">
                    <span class="text">Special Cases</span> <span class="count"><?=$special_count?></span></div>
                    </div>

                  <!--  <div id="total_active_cases">
                    <span class="text">Active cases</span> <span class="count"><?=$active_count?></span></div>
                    </div>-->

                </div>    
            </section>

            <section id="complaints_area">
                <div id="control_area">
                    <label id="filter_btn"><i class="icofont-filter"></i></label>
                    <select id="filter_value">
                        <option value ="unseen">unseen cases</option>
                        <option value="seen">seen cases</option>
                        <option value="special">special cases</option>
                        <option value="active">active cases</option>
                    </select>

                    <button id="refresh_btn"> <i class="icofont-refresh"></i></button>
                </div>

                <div id="case_area">

                    <?php 

                    $refer_btn_count = $send_file_btn_count = 0;

                    $unseen = array_filter($param[0], function($case){

                        if($case["status"] == "unseen"){
                            return true;
                        }
                    });


                    $seen = array_filter($param[0], function($case){

                        if($case["status"] == "seen"){
                            return true;
                        }
                    });


                    $special = array_filter($param[0], function($case){

                        if($case["status"] == "special"){
                            return true;
                        }
                    });

                    $active = array_filter($param[0], function($case){

                        if($case["status"] == "active"){
                            return true;
                        }
                    });
                    ?>
                <div id="unseen">
                <table>
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>firstname</th>
                            <th>lastname</th>
                            <th>Subject</th>
                            <th>date uploaded</th>
                            <th>online</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                     
                            <?php foreach($unseen as $case):?>
                            <tr class="row">
                                <td>
                                <div class="background_image">
                                    <img src=<?="assets/images/".$case["background_image"]?> height="50px" width="50px">
                                </div>
                                </td>
                                <td><?=$case["lastname"]?></td>
                                <td><?=$case["firstname"]?></td>
                                <td><?=$case["subject"]?></td>
                                <td><?=date("Y/M/D",$case["upload_date"])?></td>
                                <td><?=($case["online"])? "<span class='online_dot'></span>":"<span class='offline_dot'></span>";?></td>
                                <td>
                                    <button class="view_complaint_btn" data-view-container-id=<?=$case["id"]?>>view</button>
                                    <div class="case_view_container" id=<?="case_view_container".$case["id"]?> >
                                        <div class="case_view">
                                            <button class="close_view_btn" data-view-id=<?=$case["id"]?>>close</button>
                                            <div class="case_header">
                                                <div class="background_image" style=<?="background-image:url('assets/images/".$case["background_image"]."')"?> >
                                               <input type="hidden" id="complainer_id" value=<?=$case["user_id"]?>>
                                               <input type="hidden" id="case_status" value="unseen">
                                                </div>
                                                <div class="names">
                                                    <span><?=$case["lastname"]?></span>
                                                    <span>&nbsp;</span>
                                                    <span><?=$case["firstname"]?></span>
                                                </div>
                                            </div>
                                            <div class="case_body">
                                                <p><?=$case["detail"]?></p>
                                            </div>
                                            <div class="case_files">
                                            </div>
                                            <div class="case_controlls">

                                            <label>special </label><input type="checkbox" data-view-container-id=<?=$case["id"]?> value="special" name="special_case"> <button data-view-container-id=<?=$case["id"]?> class="work_on_it_btn">work on it</button>
                                            <!--<button data-view-container-id=<?=$case["user_id"]?> class="chat_student">chat</button>-->
                                            </div>

                                            <div id="share_to_view">
                                                <button class="go_back">Back</button>
                                                <div>
                                                    <p class="error">Please add a caption</p>
                                                    <form id="send_file_form_caption" method="POST">
                                                        <input type="text" id="file_message_caption"  placeholder="Add a caption" required>
                                                        <input type="submit"  value="send"> 
                                                    </form>
                                                </div>
                                               
                                                <p>Send to...</p>
                                                <?php foreach($connections as $connection) :?>
                                                
                                            
                                                <?php if($connection["id"] == \app\App::get_id()){

                                                    continue;
                                                }
                                                ?>
                                                <section class="connection_details_area"  data-connection-id =<?= $connection["id"]?>>
                                                    <div class="connection_overlay"></div>
                                                    <div class="background_image">
                                                    <img src=<?="http://localhost/cms/assets/images/".$connection["background_image"]?> height="50px" width="50px">
                                                    </div>

                                                    <div class="connection_user_details">
                                                        <span id="patner_fullname"><?= $connection["lastname"]." "?><?= $connection["firstname"]?> </span>
                                                        <span id="status_info" class= <?=($connection["online"])? "online_dot": "offline_dot" ?>></span>
                                                        <input type="checkbox" class="send_file_to_check" id=<?="send_file_btn".++$send_file_btn_count ?>  data-connection-id =<?= $connection["id"]?> >
                                                    </div>
                                            
                                                </section>
                                                <?php endforeach ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                <?php if(!in_array($case["id"],$refered_cases)): ?>    
                                <button class="refer_btn"  data-case-id= <?=$case["id"] ?>>Refer</button>
                                    <div class="referal_container" id=<?="refer_container".$case["id"] ?>>
                                    <button class="close_btn">x</button>
                                    <h4>Refer case to :</h4>
                                    
                                    <div class="admin_connection_area">

                                        <?php 

                                            $connections = \app\App::get_user()->get_admins();
                                            
                                       ?> 

                                        <?php foreach($connections as $connection) :?>
                                            
                                           
                                            <?php if($connection["id"] == \app\App::get_id()){

                                                continue;
                                            }
                                            ?>
                                            <section class="connection_details_area"  data-connection-id =<?= $connection["id"]?>>
                                            <div class="connection_overlay"></div>
                                                <div class="background_image">
                                                    <img src=<?="http://localhost/cms/assets/images/".$connection["background_image"]?> height="50px" width="50px">
                                                </div>

                                                <div class="connection_user_details">
                                                    <span id="patner_fullname"><?= $connection["lastname"]." "?><?= $connection["firstname"]?> </span>
                                                    <span id="status_info" class= <?=($connection["online"])? "online_dot": "offline_dot" ?>></span>
                                                    <button class="send_refer_request_btn" id=<?="send_refer_request_btn".++$refer_btn_count ?>  data-connection-id =<?= $connection["id"]?> data-connection-name =<?= $connection["firstname"]?> data-case-id=<?=$case["id"] ?> data-case-subject=<?=$case["subject"] ?> data-case-firstname=<?=$case["firstname"] ?> data-case-lastname=<?=$case["lastname"] ?> data-case-upload_date=<?=date("y/m/d",$case["upload_date"])?>> send request</button>
                                                </div>
                                            
                                            </section>
                                            <?php endforeach ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if(in_array($case["id"],$refered_cases)) :?>
                                    <button class="reffered_case_btn"> Referred case</button>
                                    <div class="refered_case_details">
                                        <button class="close_btn"> x </button>
                                        <span>You referred this case to</span>
                                        <div>
                                        <div class="background_image">
                                            <?php
                                            $refered_to_user  = null;

                                                foreach($param[1] as $user){
                                                    if($user["case_id"] == $case["id"])
                                                        $refered_to_user = $user;
                                                       
                                                        break;

                                                }
                                            ?>
                                            <img heigth="50px" width="30px" src =<?= "assets/images/".\app\App::get_user()->get_image($refered_to_user["user_id"]) ?> >
                                        </div>
                                        <div class="connection_user_details">
                                                    <span id="patner_fullname"><?= \app\App::get_user()->get_name($refered_to_user["user_id"])?> </span>
                                                    <span id="status_info" class= <?=($refered_to_user["online"])? "online_dot": "offline_dot" ?>></span>
                                                    <button class="cancel_refer_request_btn"   data-request-id = <?=$refered_to_user["id"]?> data-connection-id =<?= $refered_to_user["user_id"]?>  data-case-id=<?=$case["id"] ?> > cancel request</button>
                                        </div>
                                        </div>
                                      
                                    </div>
                                <?php endif; ?>
                                </td>
                                <td><button class="trash_btn" data-case-id =<?=$case["id"]?> data-uploaded-by-id=<?=$case["user_id"]?> data-upload-date=<?=$case["upload_date"]?> data-subject=<?=$case["subject"]?>><i class="icofont-trash"></i></button></td>
                            </tr>
                            <?php endforeach;?>
                        </div>
                    </tbody>
                </table>
                <?= (count($unseen)== 0? "<p class='no_case'>No unseen cases</p>":"");?>
                </div>

            
                <div id="seen">
                <table>
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>firstname</th>
                            <th>lastname</th>
                            <th>Subject</th>
                            <th>date uploaded</th>
                            <th>online</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($seen as $case):?>
                            <tr class="row">
                                <td>
                                <div class="background_image">
                                    <img src=<?="http://localhost/cms/assets/images/".$case["background_image"]?> height="50px" width="50px">
                                </div>
                                </td>
                                <td><?=$case["lastname"]?></td>
                                <td><?=$case["firstname"]?></td>
                                <td><?=$case["subject"]?></td>
                              <td><?=date("Y/M/D",$case["upload_date"])?></td>
                                <td><?=($case["online"])? "<span class='online_dot'></span>":"<span class='offline_dot'></span>";?></td>
                                <td>
                                    <button class="view_complaint_btn" data-view-container-id=<?=$case["id"]?>>view</button>
                                    <div class="case_view_container" id=<?="case_view_container".$case["id"]?> >
                                        <div class="case_view">
                                            <button class="close_view_btn" data-view-id=<?=$case["id"]?>>close</button>
                                            <div class="case_header">
                                                <div class="background_image" style=<?="background-image:url('assets/images/".$case["background_image"]."')"?> >
                                                </div>
                                                <div class="names">
                                                    <span><?=$case["lastname"]?></span>
                                                    <span>&nbsp;</span>
                                                    <span><?=$case["firstname"]?></span>
                                                </div>
                                            </div>
                                            <div class="case_body">
                                                <p><?=$case["detail"]?></p>
                                            </div>
                                            <div class="case_files">
                                            </div>

                                            <div class="case_controlls">

                                                <label>special </label><input type="checkbox" data-view-container-id=<?=$case["id"]?> value="special" name="special_case"> <button data-view-container-id=<?=$case["id"]?> class="work_on_it_btn">work on it</button>
                                                 <!--<button data-view-container-id=<?=$case["user_id"]?> class="chat_student">chat</button>-->

                                            </div>
                                            <div id="share_to_view">
                                                <button class="go_back">Back</button>
                                                <div>
                                                    <p class="error">Please add a caption</p>
                                                    <form id="send_file_form_caption" method="POST">
                                                        <input type="text" id="file_message_caption"  placeholder="Add a caption" required>
                                                        <input type="submit"  value="send"> 
                                                    </form>
                                                </div>
                                               
                                                <p>Send to...</p>
                                                <?php foreach($connections as $connection) :?>
                                                
                                            
                                                <?php if($connection["id"] == \app\App::get_id()){

                                                    continue;
                                                }
                                                ?>
                                                <section class="connection_details_area"  data-connection-id =<?= $connection["id"]?>>
                                                    <div class="connection_overlay"></div>
                                                    <div class="background_image">
                                                    <img src=<?="http://localhost/cms/assets/images/".$connection["background_image"]?> height="50px" width="50px">
                                                    </div>

                                                    <div class="connection_user_details">
                                                        <span id="patner_fullname"><?= $connection["lastname"]." "?><?= $connection["firstname"]?> </span>
                                                        <span id="status_info" class= <?=($connection["online"])? "online_dot": "offline_dot" ?>></span>
                                                        <input type="checkbox" class="send_file_to_check" id=<?="send_file_btn".++$send_file_btn_count ?>  data-connection-id =<?= $connection["id"]?> >
                                                    </div>
                                            
                                                </section>
                                                <?php endforeach ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                <?php if(!in_array($case["id"],$refered_cases)): ?>    
                                <button class="refer_btn"  data-case-id= <?=$case["id"] ?>>Refer</button>
                                    <div class="referal_container" id=<?="refer_container".$case["id"] ?>>
                                    <button class="close_btn">x</button>
                                    <h4>Refer case to :</h4>
                                    
                                    <div class="admin_connection_area">

                                        <?php 

                                            $connections = \app\App::get_user()->get_admins();
                                            
                                       ?> 

                                        <?php foreach($connections as $connection) :?>
                                            
                                           
                                            <?php if($connection["id"] == \app\App::get_id()){

                                                continue;
                                            }
                                            ?>
                                            <section class="connection_details_area"  data-connection-id =<?= $connection["id"]?>>
                                            <div class="connection_overlay"></div>
                                                <div class="background_image">
                                                    <img src=<?="http://localhost/cms/assets/images/".$connection["background_image"]?> height="50px" width="50px">
                                                </div>

                                                <div class="connection_user_details">
                                                    <span id="patner_fullname"><?= $connection["lastname"]." "?><?= $connection["firstname"]?> </span>
                                                    <span id="status_info" class= <?=($connection["online"])? "online_dot": "offline_dot" ?>></span>
                                                    <button class="send_refer_request_btn" id=<?="send_refer_request_btn".++$refer_btn_count ?>  data-connection-id =<?= $connection["id"]?> data-connection-name =<?= $connection["firstname"]?> data-case-id=<?=$case["id"] ?> data-case-subject=<?=$case["subject"] ?> data-case-firstname=<?=$case["firstname"] ?> data-case-lastname=<?=$case["lastname"] ?> data-case-upload_date=<?=date("y/m/d",$case["upload_date"])?>> send request</button>
                                                </div>
                                            
                                            </section>
                                            <?php endforeach ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if(in_array($case["id"],$refered_cases)) :?>
                                    <button class="reffered_case_btn"> Referred case</button>
                                    <div class="refered_case_details">
                                        <button class="close_btn"> x </button>
                                        <span>You referred this case to</span>
                                        <div>
                                        <div class="background_image">
                                            <?php
                                            $refered_to_user  = null;

                                                foreach($param[1] as $user){
                                                    if($user["case_id"] == $case["id"])
                                                        $refered_to_user = $user;
                                                       // print_r($refered_to_user);
                                                        break;

                                                }
                                            ?>
                                            <img heigth="50px" width="30px" src =<?= "assets/images/".\app\App::get_user()->get_image($refered_to_user["user_id"]) ?> >
                                        </div>
                                        <div class="connection_user_details">
                                                    <span id="patner_fullname"><?= \app\App::get_user()->get_name($refered_to_user["user_id"])?> </span>
                                                    <span id="status_info" class= <?=($refered_to_user["online"])? "online_dot": "offline_dot" ?>></span>
                                                    <button class="cancel_refer_request_btn"   data-request-id =<?=$refered_to_user["id"]?> data-connection-id=<?= $refered_to_user["user_id"]?>  data-case-id=<?=$case["id"] ?> > cancel request</button>
                                        </div>
                                        </div>
                                      
                                    </div>
                                <?php endif; ?>
                                </td>
                                <td><button class="trash_btn" data-case-id =<?=$case["id"]?> data-uploaded-by-id=<?=$case["user_id"]?>  data-upload-date=<?=$case["upload_date"]?> data-subject=<?=$case["subject"]?>><i class="icofont-trash"></i></button></td>
                            </tr>
                            <?php endforeach;?>
                        </div>
                        </tbody>
                        </table>
                        <?= (count($seen)== 0? "<p class='no_case'>No seen cases</p>":"");?>
                        </div>
                    
                <div id="special">
                <table>
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>firstname</th>
                            <th>lastname</th>
                            <th>Subject</th>
                            <th>date uploaded</th>
                            <th>online</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($special as $case):?>
                            <tr class="row">
                                <td>
                                <div class="background_image">
                                    <img src=<?="http://localhost/cms/assets/images/".$case["background_image"]?> height="50px" width="50px">
                                </div>
                                </td>
                                <td><?=$case["lastname"]?></td>
                                <td><?=$case["firstname"]?></td>
                                <td><?=$case["subject"]?></td>
                                <td><?=date("Y/M/D",$case["upload_date"])?></td>
                                <td><?=($case["online"])? "<span class='online_dot'></span>":"<span class='offline_dot'></span>";?></td>
                                <td>
                                    <button class="view_complaint_btn" data-view-container-id=<?=$case["id"]?>>view</button>
                                    <div class="case_view_container" id=<?="case_view_container".$case["id"]?> >
                                        <div class="case_view">
                                            <button class="close_view_btn" data-view-id=<?=$case["id"]?>>close</button>
                                            <div class="case_header">
                                                <div class="background_image" style=<?="background-image:url('assets/images/".$case["background_image"]."')"?> >
                                               
                                                </div>
                                                <div class="names">
                                                    <span><?=$case["lastname"]?></span>
                                                    <span>&nbsp;</span>
                                                    <span><?=$case["firstname"]?></span>
                                                </div>
                                            </div>
                                            <div class="case_body">
                                                <p><?=$case["detail"]?></p>
                                            </div>
                                            <div class="case_files">
                                            </div>
                                            <div class="case_controlls">

                                            <label>special </label><input type="checkbox"  checked data-view-container-id=<?=$case["user_id"]?> value="special" name="special_case"> <button data-view-container-id=<?=$case["id"]?> class="work_on_it_btn">work on it</button>
                                            <!--<button data-view-container-id=<?=$case["user_id"]?> class="chat_student">chat</button>-->
                                            </div>
                                            <div id="share_to_view">
                                                <button class="go_back">Back</button>
                                                <div>
                                                    <p class="error">Please add a caption</p>
                                                    <form id="send_file_form_caption" method="POST">
                                                        <input type="text" id="file_message_caption"  placeholder="Add a caption" required>
                                                        <input type="submit"  value="send"> 
                                                    </form>
                                                </div>
                                               
                                                <p>Send to...</p>
                                                <?php foreach($connections as $connection) :?>
                                                
                                            
                                                <?php if($connection["id"] == \app\App::get_id()){

                                                    continue;
                                                }
                                                ?>
                                                <section class="connection_details_area"  data-connection-id =<?= $connection["id"]?>>
                                                    <div class="connection_overlay"></div>
                                                    <div class="background_image">
                                                    <img src=<?="http://localhost/cms/assets/images/".$connection["background_image"]?> height="50px" width="50px">
                                                    </div>

                                                    <div class="connection_user_details">
                                                        <span id="patner_fullname"><?= $connection["lastname"]." "?><?= $connection["firstname"]?> </span>
                                                        <span id="status_info" class= <?=($connection["online"])? "online_dot": "offline_dot" ?>></span>
                                                        <input type="checkbox" class="send_file_to_check" id=<?="send_file_btn".++$send_file_btn_count ?>  data-connection-id =<?= $connection["id"]?> >
                                                    </div>
                                            
                                                </section>
                                                <?php endforeach ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php if(!in_array($case["id"],$refered_cases)): ?>    
                                <button class="refer_btn"  data-case-id= <?=$case["id"] ?>>Refer</button>
                                    <div class="referal_container" id=<?="refer_container".$case["id"] ?>>
                                    <button class="close_btn">x</button>
                                    <h4>Refer case to :</h4>
                                    
                                    <div class="admin_connection_area">

                                        <?php 

                                            $connections = \app\App::get_user()->get_admins();
                                            
                                       ?> 

                                        <?php foreach($connections as $connection) :?>
                                            
                                           
                                            <?php if($connection["id"] == \app\App::get_id()){

                                                continue;
                                            }
                                            ?>
                                            <section class="connection_details_area"  data-connection-id =<?= $connection["id"]?>>
                                            <div class="connection_overlay"></div>
                                                <div class="background_image">
                                                    <img src=<?="http://localhost/cms/assets/images/".$connection["background_image"]?> height="50px" width="50px">
                                                </div>

                                                <div class="connection_user_details">
                                                    <span id="patner_fullname"><?= $connection["lastname"]." "?><?= $connection["firstname"]?> </span>
                                                    <span id="status_info" class= <?=($connection["online"])? "online_dot": "offline_dot" ?>></span>
                                                    <button class="send_refer_request_btn" id=<?="send_refer_request_btn".++$refer_btn_count ?>  data-connection-id =<?= $connection["id"]?> data-connection-name =<?= $connection["firstname"]?> data-case-id=<?=$case["id"] ?> data-case-subject=<?=$case["subject"] ?> data-case-firstname=<?=$case["firstname"] ?> data-case-lastname=<?=$case["lastname"] ?> data-case-upload_date=<?=date("y/m/d",$case["upload_date"])?>> send request</button>
                                                </div>
                                            
                                            </section>
                                            <?php endforeach ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if(in_array($case["id"],$refered_cases)) :?>
                                    <button class="reffered_case_btn"> Referred case</button>
                                    <div class="refered_case_details">
                                    <button class="close_btn"> x </button>
                                        <span>You referred this case to</span>
                                        <div>
                                        <div class="background_image">
                                            <?php
                                            $refered_to_user  = null;

                                                foreach($param[1] as $user){
                                                    if($user["case_id"] == $case["id"])
                                                        $refered_to_user = $user;

                                                        break;

                                                }
                                            ?>
                                            <img heigth="50px" width="30px" src =<?= "assets/images/".\app\App::get_user()->get_image($refered_to_user["user_id"]) ?> >
                                        </div>
                                        <div class="connection_user_details">
                                                    <span id="patner_fullname"><?= \app\App::get_user()->get_name($refered_to_user["user_id"])?> </span>
                                                    <span id="status_info" class= <?=($refered_to_user["online"])? "online_dot": "offline_dot" ?>></span>
                                                    <button class="cancel_refer_request_btn"   data-request-id = <?=$refered_to_user["id"]?>  data-connection-id =<?= $connection["id"]?>  data-case-id=<?=$case["id"] ?> > cancel request</button>
                                        </div>
                                        </div>
                                      
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><button class="trash_btn" data-case-id =<?=$case["id"]?>  data-uploaded-by-id=<?=$case["user_id"]?>  data-upload-date=<?=$case["upload_date"]?> data-subject=<?=$case["subject"]?>><i class="icofont-trash"></i></button></td>
                            </tr>
                            <?php endforeach;?>
                        </div>
                        </tbody>
                        </table>
                        <?= (count($special)== 0? "<p class='no_case'>No special cases</p>":"");?>
                        </div>
                      
                    <div id="active">
                <table>
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>firstname</th>
                            <th>lastname</th>
                            <th>Subject</th>
                            <th>date uploaded</th>
                            <th>online</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($active as $case):?>
                            <tr class="row">
                                <td>
                                <div class="background_image">
                                    <img src=<?="http://localhost/cms/assets/images/".$case["background_image"]?> height="50px" width="50px">
                                </div>
                                </td>
                                <td><?=$case["lastname"]?></td>
                                <td><?=$case["firstname"]?></td>
                                <td><?=$case["subject"]?></td>
                                <td><?=date("Y/M/D",$case["upload_date"])?></td>
                                <td><?=($case["online"])? "<span class='online_dot'></span>":"<span class='offline_dot'></span>";?></td>
                                <td>
                                    <button class="view_complaint_btn" data-view-container-id=<?=$case["id"]?>>view</button>
                                    <div class="case_view_container" id=<?="case_view_container".$case["id"]?> >
                                        <div class="case_view">
                                            <button class="close_view_btn" data-view-id=<?=$case["id"]?>>close</button>
                                            <div class="case_header">
                                                <div class="background_image" style=<?="background-image:url('assets/images/".$case["background_image"]."')"?> >
                                               
                                                </div>
                                                <div class="names">
                                                    <span><?=$case["lastname"]?></span>
                                                    <span>&nbsp;</span>
                                                    <span><?=$case["firstname"]?></span>
                                                </div>
                                            </div>
                                            <div class="case_body">
                                                <p><?=$case["detail"]?></p>
                                            </div>
                                            <div class="case_files">
                                            </div>
                                            <div class="case_controlls">
                                            <button class="mark_done_case_btn" data-view-container-id=<?=$case["id"]?> class="chat_student">Mark as Done</button>
                                            </div>
                                            <div id="share_to_view">
                                                <button class="go_back">Back</button>
                                                <div>
                                                    <p class="error">Please add a caption</p>
                                                    <form id="send_file_form_caption" method="POST">
                                                        <input type="text" id="file_message_caption"  placeholder="Add a caption" required>
                                                        <input type="submit"  value="send"> 
                                                    </form>
                                                </div>
                                               
                                                <p>Send to...</p>
                                                <?php foreach($connections as $connection) :?>
                                                
                                            
                                                <?php if($connection["id"] == \app\App::get_id()){

                                                    continue;
                                                }
                                                ?>
                                                <section class="connection_details_area"  data-connection-id =<?= $connection["id"]?>>
                                                    <div class="connection_overlay"></div>
                                                    <div class="background_image">
                                                    <img src=<?="http://localhost/cms/assets/images/".$connection["background_image"]?> height="50px" width="50px">
                                                    </div>

                                                    <div class="connection_user_details">
                                                        <span id="patner_fullname"><?= $connection["lastname"]." "?><?= $connection["firstname"]?> </span>
                                                        <span id="status_info" class= <?=($connection["online"])? "online_dot": "offline_dot" ?>></span>
                                                        <input type="checkbox" class="send_file_to_check" id=<?="send_file_btn".++$send_file_btn_count ?>  data-connection-id =<?= $connection["id"]?> >
                                                    </div>
                                            
                                                </section>
                                                <?php endforeach ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php if(!in_array($case["id"],$refered_cases)): ?>    
                                <button class="refer_btn"  data-case-id= <?=$case["id"] ?>>Refer</button>
                                    <div class="referal_container" id=<?="refer_container".$case["id"] ?>>
                                    <button class="close_btn">x</button>
                                    <h4>Refer case to :</h4>
                                    
                                    <div class="admin_connection_area">

                                 

                                        <?php foreach($connections as $connection) :?>
                                            
                                           
                                            <?php if($connection["id"] == \app\App::get_id()){

                                                continue;
                                            }
                                            ?>
                                            <section class="connection_details_area"  data-connection-id =<?= $connection["id"]?>>
                                            <div class="connection_overlay"></div>
                                                <div class="background_image">
                                                    <img src=<?="http://localhost/cms/assets/images/".$connection["background_image"]?> height="50px" width="50px">
                                                </div>

                                                <div class="connection_user_details">
                                                    <span id="patner_fullname"><?= $connection["lastname"]." "?><?= $connection["firstname"]?> </span>
                                                    <span id="status_info" class= <?=($connection["online"])? "online_dot": "offline_dot" ?>></span>
                                                    <button class="send_refer_request_btn" id=<?="send_refer_request_btn".++$refer_btn_count ?>  data-connection-id =<?= $connection["id"]?> data-connection-name =<?= $connection["firstname"]?> data-case-id=<?=$case["id"] ?> data-case-subject=<?=$case["subject"] ?> data-case-firstname=<?=$case["firstname"] ?> data-case-lastname=<?=$case["lastname"] ?> data-case-upload_date=<?=date("y/m/d",$case["upload_date"])?>> send request</button>
                                                </div>
                                            
                                            </section>
                                            <?php endforeach ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if(in_array($case["id"],$refered_cases)) :?>
                                    <button class="reffered_case_btn"> Referred case</button>
                                    <div class="refered_case_details">
                                    <button class="close_btn"> x </button>
                                        <span>You referred this case to</span>
                                        <div>
                                        <div class="background_image">
                                            <?php
                                            $refered_to_user  = null;

                                                foreach($param[1] as $user){
                                                    if($user["case_id"] == $case["id"])
                                                        $refered_to_user = $user;

                                                        break;

                                                }
                                            ?>
                                            <img heigth="50px" width="30px" src =<?= "assets/images/".\app\App::get_user()->get_image($refered_to_user["user_id"]) ?> >
                                        </div>
                                        <div class="connection_user_details">
                                                    <span id="patner_fullname"><?= \app\App::get_user()->get_name($refered_to_user["user_id"])?> </span>
                                                    <span id="status_info" class= <?=($refered_to_user["online"])? "online_dot": "offline_dot" ?>></span>
                                                    <button class="cancel_refer_request_btn"   data-request-id = <?=$refered_to_user["id"]?>  data-connection-id =<?= $connection["id"]?>  data-case-id=<?=$case["id"] ?> > cancel request</button>
                                        </div>
                                        </div>
                                      
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><button class="trash_btn" data-case-id =<?=$case["id"]?> data-uploaded-by-id=<?=$case["user_id"]?>  data-upload-date=<?=$case["upload_date"]?> data-subject=<?=$case["subject"]?>><i class="icofont-trash"></i></button></td>
                            </tr>
                            <?php endforeach;?>
                        </div>
                        </tbody>
                        </table>
                        <?= (count($active)== 0? "<p class='no_case'>No active cases</p>":"");?>
                        </div>
                        </div>
              
            </section>
        </div>
                        

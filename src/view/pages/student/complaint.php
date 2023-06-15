
        <section id="complaint_container">           
          <div id="student_area">
            <div id="greeting">
                <p id="small_text">Hi, <?=$_SESSION["fname"]?><p>
                <p id="large_text">Welcome to your Cms<p>
            </div>
            <div id="complain_btn">

                <button id="make_complaint_btn"><i class="icofont-files-stack"></i>Make a complaint</button>
            </div>
          </div> 
          <div id="student_complaint_area">
        <?php
        if(is_null($param)){
            echo "<p class='empty_msg'>You haven't made any complaints</p>";
        }else{
        ?>
                <table>
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Details</th>
                            <th>Status</th>
                            <th>Date uploaded</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($param as $complaint): ?>
                            
                           <tr data-case-id=<?=$complaint["id"] ?> data-uploaded-by-id=<?=\app\App::get_id()?> data-subject=<?=$complaint["subject"]?> data-upload-date=<?=$complaint["upload_date"] ?>> 
                            <td><?=$complaint["subject"]?></td>
                            <td class='complaint_detail'><?=$complaint["detail"]?></td>
                            <td><?=$complaint["status"]?></td>
                            <td><?=date("y/m/d",$complaint["upload_date"])?></td>
                            <td><button class='view_complaint_btn' data-view-container-id=<?=$complaint["id"]?>>view</button>
                                <div class="case_view_container" class="case_view_container" id=<?="case_view_container".$complaint["id"]?> >
                                    <div class="case_view">
                                    <button class="close_view_btn " data-view-id=<?=$complaint["id"]?>>x</button>
                                    <div class="case_header">
                                        <div class="background_image"  >
                                               <img src=<?="assets/images/".app\App::get_user()->get_image($complaint["to_id"])?> height="50" width ="50">
                                        </div>
                                            <div class="names">
                                                <span><?=$complaint["lastname"]?></span>
                                                <span>&nbsp;</span>
                                                <span><?=$complaint["firstname"]?></span>
                                            </div>
                                            </div>
                                            <div class="case_body student_case">
                                                <p><?=$complaint["detail"]?></p>
                                            </div>
                                            <div class="case_files">
                                            </div>
                                            
                                            <span class=<?=$complaint["status"]?>><?=$complaint["status"]?></span>
                                            <form id="file_upload_form" method="POST" enctype="multipart/form-data">
                                                <label for="file_upload"><i class="icofont-upload"></i> Add files </label>
                                                <input type="file"  id="file_upload" name="files[]" multiple> <i id="file_upload_status_indicator"></i>
                                                <input type="submit" id="file_upload_btn" value="upload">
                                            </form>
                                    </div>


                                </div>
                            </td>
                            <td><button class="drop_btn">drop</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
          </div>     
        </section>
                 <div id="letter_box">
                    <div id="button_controlls">
                        <button id="letter_box_back_btn">Back</button>
                        <div >
                            <button id="letter_box_clear_btn">clear</button>
                            <button id="letter_box_preview_btn">preview</button>
                            <button id="letter_box_send_btn" form="letter">Send</button>
                        </div>
                       
                    </div>
                    
                    <div id="letter_area">
                        <form name="letter" id="letter">
                            <div id="to_address_container">
                                <p class="preview_text"></p>
                                    <textarea cols="10" rows="5" id="letter_to_address" required>
                                        The Hod,
                                        Department of Computer Science
                                    </textarea>
                            </div>

                            <div id="salutation_container">
                            <p class="preview_text"></p>
                                    <input type="text" value="Dear Sir/Ma" id="letter_greeting" required>
                            </div>

                            <div id="heading_container">
                            <p class="preview_text">  </p>
                                <input type="text" value="LETTER OF COMPLAINT" id="letter_title" required>
                            </div>

                            <div id="body_container">
                            <p class="preview_text">  </p>
                                <textarea cols="90" rows="25" id="letter_body" placeholder="What is the problem?" required>    
                                </textarea>
                            </div>

                            <div id="conclusion_container">

                            <p class="preview_text"> </p>
                                <textarea cols="11" rows="5" id="letter_conclusion" required>
                                        yours faithfully,
                                        
                                </textarea>
                            </div>

                        </form>

                    </div>
                </div>

        <div id="overlay">
            
            <div id="student_complaint_form">
                
                <button id="close_complaint_form_btn" class="close_btn">X</button>
                <form method="POST" id="complaint_form"  name="complaint_form" enctype="multipart/form-data" action="http://localhost/cms/student/complaint">
                    <div>
                        <label for="subject">What does your complaint concern?<span label="required">*</span></label>
                        <select id="subject" name="subject" class="form_data" required>
                            <option>Result</option>
                            <option>Grade</option>
                            <option>Lectures</option>
                            <option>Misconduct</option>
                        </select>
                    </div>

                    <div class="feild">

                        <label for="details">
                            <p>Please, what exactly is the problem?<span label="required">*</span></p>
                        </label>

                        <textarea id="details" name="detail" cols="30" class="form_data" rows="10" placeholder="explain the problem in as much details as you can" required></textarea>
                    </div>
                    
                    <div class="field">
                        <label for="anonymous">Would you want to make it anonymous</label>
                        <input type="checkbox" id="anonymous" name="anonymous" unchecked class="form_data">
                </div>

                <div class="field">
                    <label for="file">Would you want upload a file ?</label>
                    <input type="file" id="file" name="files[]" multiple>
                </div>
                <input type="hidden" id="to_id" name="to_id" value=<?=$_SESSION["to_id"]?>>
                <div id="submit_complaint_btn_area">
                    <input type="submit" name="sumbit_form"  id="submit_complaint" onclick="upload_complaint()"value="done">
                    <div>
                </form>
            </div>

            
        </div>
        <?php }?>

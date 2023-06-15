
<main>
    <section id="create_account_container">
        
        <div id="illustration">
            <img src="../cms/assets/images/5350258.jpg" height="300" width="300">
        </div>
        
        <div id="form_container">

            <section id="user_image">
               <img src="image" height="50" width="50">
            </section>

            <p class="error"> this is an error message</p>
            
            <section id="form">
                
                <form name="create_account_form" id="create_account_form" action="http://localhost/cms/create_account" method="POST">
                    
                    <div class="fields">

                        <div class="input_container">
                            <div>
                            <span class="label"><label for="fname">Firstname</label><span label="required">*</span></span>
                            <input type="text" class="form_data" min="2" placeholder="Enter your firstname" name="fname" id="fname" required>
                            </div>
                        </div>

                        <div class="input_container">
                            <div>
                            <span class="label"><label for="lname">Lastname</label><span label="required">*</span></span>
                            <input type="text" class="form_data" min="2" placeholder="Enter your lastname" name="lname" id="lname" required>
                            </div>
                        </div>

                        <div class="input_container">
                            <div>
                            <span class="label"><label for="email">Email</label><span label="required">*</span></span>
                            <input type="email" class="form_data" placeholder="Enter your email" name="email" id="email" required>
                            </div>
                        </div>


                        <div class="input_container">
                            <div>
                                <span class="label"><label for="pass">Password</label><span label="required">*</span></span></span>
                                <input type="password" class="form_data" placeholder="create your password" name="passw" id="pass" required>
                            </div>
                        </div>

                        <div class="input_container">
                            <div>
                            <span class="label"><label for="cpass">Confirm password</label><span label="required">*</span></span>
                            <input type="password" class="form_data" placeholder="confirm your password" name="cpassw" id="cpass" required>
                            </div>
                        </div>

                        <div class="input_container">
                            <div>
                            <span class="label"><label for="account">Select an account type</label><span label="required">*</span></span>
                            <select id="account" class="form_data" name="account_type" required> 
                                <option>student</option>
                                <option>admin</option>
                            </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="static_form_area">
                        <button type="submit" id="create_account_btn">Create my account</button>
                        <p><a href="http://localhost/cms/">Already have an account? log-in</a></p>
                    </div>
                   
                </form>

            
            </section>
        </div>
    </section>

</main>
</body>
<script src="http://localhost/cms/assets/js/lib.js"></script>
<script src="http://localhost/cms/assets/js/create_account.js"></script>
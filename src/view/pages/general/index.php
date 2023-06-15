
<main>
    <section id="login_container">
        
        <div id="illustration">
            <img src="../cms/assets/images/5350258.jpg" height="300" width="300">
        </div>

        <div id="form_container">
            
            <section id="user_image">
                <img src="image" height="50" width="50">
            </section>

            <p class="error">Please provide appropriate details</p>

            <section id="form">

                <form name="login_form" id="login_form" action="http://localhost/cms/login" method="POST">

                    <div class="fields">
                        <div class="input_container">
                            <div>
                                <span class="label"> <label for="udata">Email</label><span label="required">*</span></span>
                                <input type="text" class="form_data" placeholder="Enter your email" name="udata" id="udata" required >
                            </div>
                        </div>

                        <div class="input_container">
                            <div>
                            <span class="label"><label for="pass">Password</label><span label="required">*</span></span>
                            <input type="password" class="form_data" placeholder="Enter your password" name="passw" id="pass" required>
                            </div>
                        </div>
                    </div>

                    <div class="static_form_area">
                         <button type="submit" id="login_btn">Log in</button>
                         <p><a href="http://localhost/cms/create_account">Dont have an account? <br> create your account</a></p>
                    </div>
                 
                </form>

                
            </section>
        </div>
    </section>

</main>
</body>
<script src="http://localhost/cms/assets/js/lib.js"></script>
<script src="http://localhost/cms/assets/js/login.js"></script>
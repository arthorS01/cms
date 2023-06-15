
        <div id="settings_container">           
           
            <section id="personal_details">

                <h3>Personal details</h3>
                <form>
                    <div class="form_item">
                        <label>Firstname</label>
                        <input type="text" class="fill" value=<?= $_SESSION["fname"]?> name="firtname">
                    </div>

                    <div class="form_item">
                        <label>Lastname</label>
                        <input type="text" class="fill" value=<?= $_SESSION["lname"]?> name="lastname">
                    </div>

                    <div class="form_item">
                        <label>Email</label>
                        <input type="text" class="fill" value=<?= $_SESSION["email"] ?> name="email">
                    </div>

                    <div class="form_item">
                        <label>Phone number</label>
                        <input type="text"  value="08012345678"class="fill" name="number">
                    </div>

                    <div class="form_item">
                        <button class="btn">Update</button>
                    </div>
                </form>
                
            </section>

            <section id="personalisations">

               
            <button class="del">Delete account</button>
            </section>
        </div>
  
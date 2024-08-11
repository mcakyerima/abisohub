<div class="page-content header-clear-medium containerr">
        
        
        <div class="card card-style">

            <div class="content text-center">
                <img src="../../assets/images/icons/email-verification.png" style="width:250px; height:200px;" />
           
                <p class="mb-0 font-600 color-highlight">Email Verification</p>
                <h1>Verification</h1>
                <p class="mb-1 font-600 text-danger">A Verification Code Has Been Sent To Your Email. Please Provide The Code Below To Verify Your Account.</p>
                <p class="mb-3 font-600 text-danger">If You Can't Find The Verification Code, Please Do Check Your Spam Folder.</p>
                
                <form method="post" class="contactForm the-submit-form">
                        <fieldset>
                            <input type="hidden" name="email" value="<?php echo $data->sEmail; ?>" />
                            <div class="form-field form-name">
                                <input type="number" name="code" placeholder="Code" value="" class="round-small" id="contactNameField" required />
                            </div>
                            
                            <div class="form-button">
                            <button type="submit" name="email-verification" style="width: 100%;" class="the-form-btn btn btn-full btn-l font-600 font-15 gradient-highlight mt-4 rounded-s">
                                    Verify
                            </button>
                            </div>
                        </fieldset>
                    </form>        
            </div>

        </div>

</div>


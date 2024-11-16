<div class="page-content header-clear containerr abs">

    <div class="card notch-clear rounded-0 mb-n5 mt-0" data-card-height="150" style="background-color:<?php echo $sitecolor; ?>;">
        <div class="card-body pt-4 mt-2 mb-n2">
            <!-- <h1 class=" font-20 float-start color-white"><?php echo "Hi, " . $data->sFname; ?>!</h1> -->
            <!-- <h3 class=" font-17 float-end color-white">(<?php echo $controller->formatUserType($data->sType); ?>)</h3> -->
            <div class="clearfix"></div>
        </div>

        <div class="card card-style mt-0 mb-5" style="height: 50px;">

            <div class="card-center ">

                <h3 class="float-start font-16 ps-3 pt-2">
                    <span style="margin-right:10px;">Balance</span>
                    <span id="hideEyeDiv" style="display:none;">&#8358;<?php echo number_format($data->sWallet); ?></span>
                    <span id="openEyeDiv">&#8358; ****</span>

                    <span id="hideEye"><i class="fa fa-eye-slash" style="margin-left:20px;" aria-hidden="true"></i></span>
                    <span id="openEye" style="display:none; margin-left:20px;"><i class="fa fa-eye" aria-hidden="true"></i></span>

                </h3>
                <a href="fund-wallet" class="btn float-end" style="background-color:<?php echo $sitecolor; ?>; border-radius:1rem; margin-right:7px"><b>Add Money</b></a>

            </div>

        </div>

    </div>

    <br><br><br>
    <div class="d-flex">
        <h5 style="background:<?php echo $sitecolor; ?>; color:#ffffff; padding:9px;  margin-right:5px;">Info: </h5>
        <marquee direction="left" scrollamount="5" style="background:#ffffff; padding:3px; border-radius:0.0rem;">
            <h5 class="py-2">
                FOR ANY COMPLAINS, QUESTIONS OR SUGGESTIONS CLICK THE WHATSAPP ICON BELOW TO CONTACT US. THANK YOU FOR YOUR PATRONAGE
            </h5>
        </marquee>
    </div>
    <br>
    <div class="card text-white bg-danger mb-3 text-center card card-style      mt-n3">
        <?php

        if (empty($data->sVerified) && !empty($data->sBankNo)) {
            // Display the link only if sVerified is empty
            echo '<div class="mt-3 mb-3"><h5 style="color:white">Click Here to Verify your Account <br> as requested by CBN</h5><a href="bvn-verification" class="btn btn-success">Verify Account</a></div>';
        }

        ?>
    </div>
    <div class="card card-style mt-n3">
        <div class="content mb-3 mt-3">
            <div>
                <h5>SERVICES</h5>
                <hr />
            </div>

            <div class="row text-center mb-0">

                <a href="buy-airtime" class="col-4 mt-2">
                    <span class="icon icon-l shadow-l rounded-sm" style="color:#cc0066;">
                        <i class="fa fa-phone font-18"></i>
                    </span>
                    <p class="mb-0 pt-1 font-13">Airtime</p>
                </a>

                <a href="buy-data" class="col-4 mt-2">
                    <span class="icon icon-l shadow-l rounded-sm" style="color:#0066ff;">
                        <i class="fa fa-wifi font-18 "></i>
                    </span>
                    <p class="mb-0 pt-1 font-13">Data</p>
                </a>

                <a href="electricity" class="col-4 mt-2">
                    <span class="icon icon-l shadow-l rounded-sm" style="color:#00cc00;">
                        <i class="fa fa-bolt font-18 "></i>
                    </span>
                    <p class="mb-0 pt-1 font-13">Electricity</p>
                </a>

                <a href="exam-pins" class="col-4 mt-2">
                    <span class="icon icon-l shadow-l rounded-sm" style="color:#ff6600;">
                        <i class="fa fa-graduation-cap font-18 "></i>
                    </span>
                    <p class="mb-0 pt-1 font-13">Exam Pins</p>
                </a>

                <a href="cable-tv" class="col-4 mt-2">
                    <span class="icon icon-l shadow-l rounded-sm" style="color:#cc0066;">
                        <i class="fa fa-tv font-18"></i>
                    </span>
                    <p class="mb-0 pt-1 font-13">Cable Tv</p>
                </a>


                <a href="transactions" class="col-4 mt-2">
                    <span class="icon icon-l shadow-l rounded-sm" style="color:#0066ff;">
                        <i class="fa fa-receipt font-18"></i>
                    </span>
                    <p class="mb-0 pt-1 font-13">Transactions</p>
                </a>

                <a href="fund-wallet" class="col-4 mt-2">
                    <span class="icon icon-l shadow-l rounded-sm" style="color:#cc0066;">
                        <i class="fa fa-arrow-up font-18"></i>
                    </span>
                    <p class="mb-0 pt-1 font-13">Add Money</p>
                </a>

                <a href="profile" class="col-4 mt-2">
                    <span class="icon icon-l shadow-l rounded-sm" style="color:#0066ff;">
                        <i class="fa fa-user  font-18"></i>
                    </span>
                    <p class="mb-0 pt-1 font-13">Profile</p>
                </a>

                <a href="contact-us" class="col-4 mt-2">
                    <span class="icon icon-l shadow-l rounded-sm" style="color:#ff6600;">
                        <i class="fa fa-envelope font-18"></i>
                    </span>
                    <p class="mb-0 pt-1 font-13">Contact</p>
                </a>

                <a href="#agent-upgrade-modal" id="upgrade-agent-btn" data-menu="agent-upgrade-modal" class="col-4 mt-2">
                    <span class="icon icon-l shadow-l rounded-sm" style="color:#00cc00;">
                        <i class="fa fa-user-secret font-18"></i>
                    </span>
                    <p class="mb-0 pt-1 font-13">Agent</p>
                </a>

                <a href="pricing" class="col-4 mt-2">
                    <span class="icon icon-l shadow-l rounded-sm" style="color:#cc0066;">
                        <i class="fa fa-list-alt font-18"></i>
                    </span>
                    <p class="mb-0 pt-1 font-13">Pricing</p>
                </a>

                <a href="logout" class="col-4 mt-2">
                    <span class="icon icon-l shadow-l rounded-sm" style="color:#ff6600;">
                        <i class="fa fa-lock  font-18"></i>
                    </span>
                    <p class="mb-0 pt-1 font-13">Logout</p>
                </a>

            </div>
        </div>
    </div>


</div>
<br>

<script>
    function debounce(func, delay) {
        let inDebounce;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(inDebounce);
            inDebounce = setTimeout(() => func.apply(context, args), delay);
        }
    }

    document.querySelectorAll('a').forEach(function(btn) {
        btn.addEventListener('click', debounce(function(event) {
            if (!this.classList.contains('clicked')) {
                this.classList.add('clicked');
                this.style.pointerEvents = 'none'; // Disable further interaction for this link
            } else {
                event.preventDefault(); // Prevent further clicks
            }
        }, 300)); // Adjust the delay as needed
    });
</script>
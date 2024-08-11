<div style="background-image:url(../../assets/img/bg/cc.png)">
<div class="header header-fixed mb-3 pt-2 pb-2" style="height: auto !important; transform: translateX(0px); background-color:<?php echo $sitecolor; ?>; opacity:0.9;">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between">

            <div class="d-flex align-items-center">
                <div>
                    <a href="profile">
                        <img src="../../assets/images/user.png" style="border-radius:5rem; width:45px; height:45px; margin-right:10px;">
                    </a>
                </div>
                <div>
                    <h5 class="my-0 py-0 mt-2  text-white" style="line-height: 10px;">Hi, <a href="profile" class=" text-white"><?php echo $profileDetails->sFname . " (".$controller->formatUserType($profileDetails->sType).")"; ?></a></h5>
                    <p class="my-0 py-0  text-white"><strong>Balance: ₦ <?php echo number_format($profileDetails->sWallet,0); ?></strong></p>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <a href="notifications" class="mr-2 text-white">
                    <h3><ion-icon class="font-25 text-white" name="notifications"></ion-icon></h3>
                </a>
            </div>

        </div>
    </div>
</div>
</div>
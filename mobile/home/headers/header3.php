<div class="header header-fixed header-logo-center" style="background-color:<?php echo $sitecolor; ?>;">
            <a href="./" class="header-title text-white"><?php echo $sitename; ?></a>
            <?php if($title == "Homepage"){?>
                <a href="#" class="font-17 header-icon header-icon-1 text-white"><i class="fas fa-home"></i></a>
            <?php } else{ ?>
                <a href="#" data-back-button class="font-17 header-icon header-icon-1 text-white"><i class="fas fa-chevron-left"></i></a>
            <?php } ?>
            <a href="notifications" class="font-17 header-icon header-icon-4 text-white"><i class="fas fa-bell"></i><span class="badge bg-red-dark">1</span></a>
        </div>
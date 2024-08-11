<?php 

    /*
        InTranX.

        Dear Developer, Please Note That This Is A Licensed VTU Script 
        And Is Not To Be Used Without Full Permission 

        If found guilty of bypassing or violating our terms of service, 
        
        1. Your website would be blocked
        2. Your database would be permanently deleted
        3. Legal actions would be taken agents you
        4. You would be required to pay a fine of N250K

        From InTranX.
        Website: www.intranx.com.ng
        Email: info@intranx.com.ng
        PWhatsapp: 08022248006
    */

    $_GET["settings"]="YES";
    
    require_once("../home/includes/route.php");

    $design = $data->logindesign;
    $color = $data->sitecolor;
    $name = $data->sitename;

    include("recovery".$design.".php");

?>
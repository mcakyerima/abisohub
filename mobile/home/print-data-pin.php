<div class="page-content">
        
        <div class="">
            <div class="content">
                <div class="row">
                    <?php if(!empty($data)) : $pins=explode(",",$data->tokens); $sn=explode(",",$data->serial); ?>
                        
                        <?php $network=$data->network; $datasize=$data->datasize; $loadpin="*347*383*3*3*PIN#"; 
                        if($datasize=="1.5GB"){$loadpin="*460*6*1# Then PIN or Text PIN to 460"; $checkBal="*131*4#";} ?>
                        <?php if($network == "AIRTEL"){$cardColor="#ff1a1a"; $cardLogo="airtel.png"; $textColor="#ffffff"; $checkBal="*140#";} 
                            elseif($network == "GLO"){$cardColor="#60cf06"; $cardLogo="glo.png"; $textColor="#ffffff"; $checkBal="*140#";} 
                            elseif($network == "9MOBILE"){$cardColor="##047d0c"; $cardLogo="9mobile.png"; $textColor="#ffffff"; $checkBal="*140#";} 
                            else {$cardColor="#ffcc00"; $cardLogo="mtn.png"; $textColor="#000000"; $checkBal="*461*4#";} 
                            $loadpin=$data->loadpin; $checkBal=$data->checkbalance;
                        ?>
                        <?php $pincount=0; for($i=0; $i<$data->quantity; $i++): $pincount++; ?>
                        <div class="col-6">
                            <div class="row" style="margin:3px; margin-top:5px; margin-bottom:5px;">
                                    
                                    <div class="col-4" style="margin:0; padding:0; background-color:<?php echo $cardColor; ?>; ">
                                        <div class="text-dark" style="padding:5px;">
                                        
                                            <p style="margin-bottom:3px;"><img src="../../assets/images/icons/<?php echo $cardLogo; ?>" style="width:50px; height:50px;" /></p>
                                            <h6 style="color:<?php echo $textColor; ?>; font-size:13px;">DATA PIN <?php echo $datasize; ?></h6>
                                            <p style="margin-bottom:0; color:<?php echo $textColor; ?>;"><?php echo $sn[$i]; ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-8 bg-white" style="margin:0; padding:0; ">   
                                        <div class="text-center" style="padding:5px;">
                                            
                                            <h6><?php echo strtoupper($data->business); ?></h6>
                                            <h4 style="background-color:#f2f2f2; border-radius:3rem; padding:5px;"><?php echo $pins[$i]; ?></h4>
                                            <p style="margin-bottom:0;"><b>Load <?php echo $loadpin; ?></b> <b>Bal:   <?php echo $checkBal; ?></b></p>
                                            <p>Powered By: <?php echo $sitename; ?></p>
                                        </div>
                                    </div>
                            </div>
                        </div>

                        <?php if($pincount % 16 == 0){ echo '<div class="pagebreak" style="margin-bottom:90px;"> </div>'; }?>
                            
                    <?php endfor; endif; ?>
                   
                </div>
                
            </div>

        </div>

</div>
<script>window.print();</script>


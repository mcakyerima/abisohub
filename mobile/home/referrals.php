<div class="page-content header-clear-medium containerr">

<div class="row mb-0">
            <div class="col-6">
                <div class="card card-style" data-card-height="90" 
                style="height: 110px;  margin-right:0;">
                        <div class="card-top ps-3 pt-3">
                            <h6 class="font-5" style="color:<?php echo $sitecolor; ?>" >Referrals</h6>
                            
                        </div>
                        <div class="card-center pe-3">
                        
                        </div>
                        <div class="card-bottom ps-3 pb-2">
                            <h4><?php echo $data->refCount; ?></h4>
                        </div>
                        
                </div>
            </div>
            <div class="col-6">
                <div class="card card-style" data-card-height="90" 
                style="height: 110px;  margin-left:0;">
                        <div class="card-top ps-3 pt-3">
                            <h6 class="font-5" style="color:<?php echo $sitecolor; ?>">Commission</h6>
                            
                        </div>
                        <div class="card-center pe-3">
                        
                        </div>
                        <div class="card-bottom ps-3 pb-2">
                            <h4>N<?php echo $data->sRefWallet; ?></h4>
                        </div>
                        
                </div>
            </div>
        </div>

       <div class="card card-style">
            
            <div class="content">
            <div>
                <h5>Referrals Link</h5>
                <hr/>
            </div>
               
               <div>
                    <input type="text" class="form-control" readonly value="<?php echo $siteurl."mobile/register/?referral=".$data->sPhone; ?>" />
                    <button class="btn btn-danger btn-sm mt-2" style="border-radius:5rem;" onclick="copyToClipboard('<?php echo $siteurl."mobile/register/?referral=".$data->sPhone; ?>')">Copy Link</button>
                    <a href="transfer" class="btn btn-success btn-sm mt-2" style="border-radius:5rem; margin-left:5px;">Withdraw</a>
                </div>
            </div>
        </div>

       <div class="card card-style">
            
            <div class="content">
                <div>
                    <h5>Commission List</h5>
                    <hr/>
                </div>

                <table class="table table-bordered table-striped">
                    <tr class="bg-blue-dark">
                        <td class="text-white"><b>Service</b></td>
                        <td class="text-white"><b>Bonus</b></td>
                    </tr>
                    <tr>
                        <td><b>Account Upgrade </b></td>
                        <td><b>N<?php echo $data2->referalupgradebonus; ?></b></td>
                    </tr>
                    <tr>
                        <td><b>Airtime Bonus</b></td>
                        <td><b>N<?php echo $data2->referalairtimebonus; ?></b></td>
                    </tr>
                    <tr>
                        <td><b>Data Bonus</b></td>
                        <td><b>N<?php echo $data2->referaldatabonus; ?></b></td>
                    </tr>
                    <tr>
                        <td><b>Cable TV Bonus</b></td>
                        <td><b>N<?php echo $data2->referalcablebonus; ?></b></td>
                    </tr>
                    <tr>
                        <td><b>Electricity Bonus</b></td>
                        <td><b>N<?php echo $data2->referalmeterbonus; ?></b></td>
                    </tr>
                    <tr>
                        <td><b>Exam Pin Bonus</b></td>
                        <td><b>N<?php echo $data2->referalexambonus; ?></b></td>
                    </tr>
                   
                </table> 

            </div>
        </div>

        

        
</div>


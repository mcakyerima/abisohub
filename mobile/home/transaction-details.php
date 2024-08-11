<div class="page-content header-clear-medium">
        
        <div class="card card-style">
            <div class="content">
                <div class="text-center"><img src="../../assets/images/icons/success.png" style="width:50px; height:50px;" /></div>
                <p class="mb-0 font-600 text-dark text-center">Transaction Details</p>
                <h3 class="text-center"><?php echo $controller->formatStatus($data->status); ?></h3>
                <hr/>
                <table class="table">
                    <tr>
                        <td><b>Ref No:</b></td>
                        <td align="right"><?php echo $data->transref; ?></td>
                    </tr>
                    <tr>
                        <td><b>Date:</b></td>
                        <td align="right"><?php echo $controller->formatDate($data->date); ?></td>
                    </tr>
                    <tr>
                        <td><b>Service:</b></td>
                        <td align="right"><?php echo $data->servicename; ?></td>
                    </tr>
                    <tr>
                        <td><b>Description:</b></td>
                        <td align="right"><?php echo $data->servicedesc; ?></td>
                    <tr>
                        <td><b>API Response:</b></td>
                        <td align="right"><?php echo $data->api_response_log; ?></td>
                    </tr>
                    </tr>
                    <?php if(!isset($_GET["receipt"])): ?>
                    <tr>
                        <td><b>Amount:</b></td>
                        <td align="right">N<?php echo $data->amount; ?></td>
                    </tr>
                    <tr>
                        <td><b>Old Balance:</b></td>
                        <td align="right">N<?php echo $data->oldbal; ?></td>
                    </tr>
                     <tr>
                        <td><b>New Balance:</b></td>
                        <td align="right">N<?php echo $data->newbal; ?></td>
                    </tr>
                    <?php endif; ?>
                    
                    

                </table> 
               <div class="text-center">
                    <?php if(!isset($_GET["receipt"])): ?>
                    <a href="transaction-details?receipt&ref=<?php echo $_GET["ref"]; ?>" class="btn btn-success btn-sm" style="border-radius:2rem;" >
                        <b>View User Receipt</b>
                    </a>
                    <?php endif; ?>
                    <?php if($data->servicename == "Data Pin" && $data->status == 0): ?>
                    <a href="view-pins?ref=<?php echo $_GET["ref"]; ?>" class="btn btn-primary btn-sm" style="border-radius:2rem; margin-left:15px;">
                        <b>View Pins</b>
                    </a>
                    <?php endif; ?>
               </div>
            </div>

        </div>

</div>


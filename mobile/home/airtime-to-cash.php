<div class="page-content header-clear-medium containerr">
        
        <div class="card card-style">
            
            <div class="content">
                <div class="text-center">
                    <span class="icon icon-l rounded-sm" style="background:#f2f2f2;">
                        <img src="../../assets/images/icons/airtimetocash.png" width="65" height="65" />
                    </span>
                </div>
                
                <p class="mb-0 mt-2 text-center font-600 color-highlight">Convert Airtime To Cash</p>
                <h1 class="text-center">Airtime To Cash</h1>
                
                <hr/>
                <?php if($controller->getConfigValue($data,"airtime2cashstatus") <> "On"): ?>
                <h3 class="text-danger text-center">Airtime To Cash Is Not Available At The Moment, Please Check Back Later</h3>
                <?php else: ?>
                    <form method="post" id="airtimetocashform">
                        <fieldset>

                            <div class="input-style input-style-always-active has-borders mb-4">
                                <label for="airtimetocashnetwork" class="color-theme opacity-80 font-700 font-12">Network</label>
                                <select id="airtimetocashnetwork" name="airtimetocashnetwork" required>
                                    <option value="" disabled="" selected="">Select Network</option>
                                    <option value="MTN" providerno="<?php echo $controller->getConfigValue($data,"airtime2cashmtnno"); ?>" providerrate="<?php echo $controller->getConfigValue($data,"airtime2cashmtnrate"); ?>">MTN (<?php echo $controller->getConfigValue($data,"airtime2cashmtnrate"); ?>%)</option>
                                    <option value="AIRTEL" providerno="<?php echo $controller->getConfigValue($data,"airtime2cashairtelno"); ?>" providerrate="<?php echo $controller->getConfigValue($data,"airtime2cashairtelrate"); ?>">AIRTEL (<?php echo $controller->getConfigValue($data,"airtime2cashairtelrate"); ?>%)</option>
                                    <option value="GLO" providerno="<?php echo $controller->getConfigValue($data,"airtime2cashglono"); ?>" providerrate="<?php echo $controller->getConfigValue($data,"airtime2cashglorate"); ?>">GLO (<?php echo $controller->getConfigValue($data,"airtime2cashglorate"); ?>%)</option>
                                    <option value="9MOBILE" providerno="<?php echo $controller->getConfigValue($data,"airtime2cash9mobileno"); ?>" providerrate="<?php echo $controller->getConfigValue($data,"airtime2cash9mobilerate"); ?>">9MOBILE (<?php echo $controller->getConfigValue($data,"airtime2cash9mobilerate"); ?>%)</option>
                                </select>
                                <span><i class="fa fa-chevron-down"></i></span>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                                <i class="fa fa-check disabled invalid color-red-dark"></i>
                                <em></em>
                            </div>
                            
                            <div class="input-style input-style-always-active has-borders validate-field mb-4">
                                <label for="airtimetocashphone" class="color-theme opacity-80 font-700 font-12">Sender Number</label>
                                <input type="number" name="airtimetocashphone" placeholder="Phone Number" value="" class="round-small" id="airtimetocashphone" required  />
                            </div>

                            <div class="input-style input-style-always-active has-borders validate-field mb-4">
                                <label for="airtimetocashamount" class="color-theme opacity-80 font-700 font-12">Amount</label>
                                <input type="number" name="airtimetocashamount" placeholder="Amount" value="" class="round-small" id="airtimetocashamount" required  />
                            </div>
                            
                            <div class="input-style input-style-always-active has-borders validate-field mb-4">
                                <label for="amounttopay" class="color-theme opacity-80 font-700 font-12">Amount To Be Credited</label>
                                <input type="text" name="amounttopay" placeholder="Amount To Pay" value="" class="round-small" id="amounttopay" readonly required  />
                            </div>
                            
                            <h4>Transfer Information</h4>
                            <ul class="list-group" id="MTNCodeBox" style="display:none;">
                                <li class="list-group-item">
                                    <span class="icon icon-l rounded-sm" style="background:#f2f2f2;">
                                        <img src="../../assets/images/icons/mtn.png" width="45" height="45" />
                                    </span>
                                </li>
                                <li class="list-group-item">Network: <b>MTN</b></li>
                                <li class="list-group-item">Phone Number: <b><?php echo $controller->getConfigValue($data,"airtime2cashmtnno"); ?></b></li>
                                <li class="list-group-item">Set New Pin: <b>*600*000*Newpin*Newpin#</b></li>
                                <li class="list-group-item">Transfer Code: <b>*600*NUMBER*AMOUNT*PIN#</b></li>
                            </ul>
                            
                            <ul class="list-group" id="AIRTELCodeBox" style="display:none;">
                                <li class="list-group-item">
                                    <span class="icon icon-l rounded-sm" style="background:#f2f2f2;">
                                        <img src="../../assets/images/icons/airtel.png" width="45" height="45" />
                                    </span>
                                </li>
                                <li class="list-group-item">Network: <b>AIRTEL</b></li>
                                <li class="list-group-item">Phone Number: <b><?php echo $controller->getConfigValue($data,"airtime2cashairtelno"); ?></b></li>
                                <li class="list-group-item">Set New Pin: <b>*432#</b></li>
                                <li class="list-group-item">Transfer Code: <b>*432*1*#</b></li>
                            </ul>
                            
                            <ul class="list-group" id="GLOCodeBox" style="display:none;">
                                <li class="list-group-item">
                                    <span class="icon icon-l rounded-sm" style="background:#f2f2f2;">
                                        <img src="../../assets/images/icons/glo.png" width="45" height="45" />
                                    </span>
                                </li>
                                <li class="list-group-item">Network: <b>GLO</b></li>
                                <li class="list-group-item">Phone Number: <b><?php echo $controller->getConfigValue($data,"airtime2cashglono"); ?></b></li>
                                <li class="list-group-item">Set New Pin: <b>*132*00000*Newpin*Newpin#</b></li>
                                <li class="list-group-item">Transfer Code: <b>*131*NUMBER*AMOUNT*PIN#</b></li>
                            </ul>
                            
                            <ul class="list-group" id="9MobileCodeBox" style="display:none;">
                                <li class="list-group-item">
                                    <span class="icon icon-l rounded-sm" style="background:#f2f2f2;">
                                        <img src="../../assets/images/icons/9mobile.png" width="45" height="45" />
                                    </span>
                                </li>
                                <li class="list-group-item">Network: <b>9Mobile</b></li>
                                <li class="list-group-item">Phone Number: <b><?php echo $controller->getConfigValue($data,"airtime2cash9mobileno"); ?></b></li>
                                <li class="list-group-item">Set New Pin: <b>*247*0000*newpin#</b></li>
                                <li class="list-group-item">Transfer Code: <b>*131*PIN*AMOUNT*NUMBER#</b></li>
                            </ul>
                            
                            <p class="text-danger mt-2">
                                <b>Note: Please contact admin first before sending airtime. <br/>
                                Click on submit only when you have transfered the Airtime. <br/>
                                When verifed, your wallet would be credited. If you prefer a bank transfer, please communicate with the admin.<br/> 
                                <br/>
                                
                                </b>
                            </p>
                            
                            <input name="transkey" id="transkey" type="hidden" />
                            <input name="transref" type="hidden" value="<?php echo $transRef; ?>" />
                
                            <div class="form-button">
                            <button type="submit" id="airtimetocash-btn" name="submit-airtimetocash" style="width: 100%;" class="btn btn-full btn-l font-600 font-15 gradient-highlight mt-4 rounded-s">
                                   Submit
                            </button>
                            </div>
                        </fieldset>
                    </form>       
                <?php endif; ?>
            </div>

        </div>

</div>






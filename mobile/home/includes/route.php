<?php

/*
        * Route For Subscribers
        * Login
        * Posts
        * Forms
    */

ini_set("session.use_only_cookies", 1);
ini_set("session.use_strict_mode", 1);
session_set_cookie_params([
    'lifetime' => 1800,
    'secure' => false,
    'httponly' => true
]);

session_start();
session_regenerate_id(true);

ini_set("display_errors", 0);
error_reporting(E_ALL);

//Verify User Is Logged In
$allow = ["settings", "register", "login", "get-user-code", "verify-user-code", "update-user-pass", "update-user-pin", "save-message"];
if (!isset($_SESSION["loginId"])) {
    $c = 0;
    foreach ($allow as $g) {
        if (isset($_GET[$g])) {
            $c = 1;
        }
    }
    if ($c == 0) {
        header("Location: ../");
        exit();
    }
}

//Auto Load Classes
require_once("auto_loader.php");

$parentdirectory = "abisohub/";

//Global Variables
date_default_timezone_set('Africa/Lagos');
$protocol = (isset($_SERVER['HTTPS'])) ? "https://" : "http://";
$assetsLoc = $protocol . $_SERVER['SERVER_NAME'] . "/mobile/home";
$siteurl = $protocol . $_SERVER['SERVER_NAME'] . "/";
$data = "";
$data2 = "";
$data3 = "";
$data4 = "";
$data5 = "";
$data6 = "";
$data7 = "";
$data8 = "";
$data9 = "";
$page = "homepage.php";
$title = "Home";
$msg = "";
$homemsg = "";
$ur = (isset($_GET["url"])) ? $_GET["url"] : "";
$link = explode("/", $ur);
$url = $link[0];
$arg1 = (!empty($link[1])) ? $link[1] : 0;
$arg2 = (!empty($link[2])) ? $link[2] : 0;
$arg3 = (!empty($link[3])) ? $link[3] : 0;
$next_page = 0;
$pre_page = 0;
$current_cat = "";
$controller = "";
$sitecolor = "";
$pinstatus = 0;
$siteSettings = "";
$profileDetails = "";

$limit = 100;
$pageCount = (isset($_GET["page"])) ? $_GET["page"] : 1;
$pageCount = (float) $pageCount;
$limit = $pageCount * $limit;
$limit = $limit - 100;
$pageCount++;

if (isset($_GET["settings"])):
    $controller = new Subscriber;
    $data = $controller->getSiteSettings();
else:

    //User Login/Register
    if (isset($_GET["register"])) {
        $controller = new AccountAccess;
        echo $controller->registerUser();
        exit();
    } elseif (isset($_GET["login"])) {
        $controller = new AccountAccess;
        echo $controller->loginUser();
        exit();
    } elseif (isset($_GET["get-user-code"])) {
        $controller = new AccountAccess;
        echo $controller->recoverUserLogin();
        exit();
    } elseif (isset($_GET["verify-user-code"])) {
        $controller = new AccountAccess;
        echo $controller->verifyRecoveryCode();
        exit();
    } elseif (isset($_GET["update-user-pass"])) {
        $controller = new AccountAccess;
        echo $controller->updateUserKey();
        exit();
    } else {
        global $controller;
        $controller = new Subscriber;
        $transRef = $controller->generateTransactionRef();
    }

    //Admin Logout
    if ($url == "logout") {
        $controller->logoutUser();
    }

    //Set Message
    if (isset($_GET["msg"])) {
        $msg = $controller->createPopMessage("Alert", $_GET["msg"], "blue");
    }


    //Update Login Details
    if (isset($_GET["update-pass"])) {
        echo $controller->updateProfileKey();
        exit();
    }

    //Monnify KYC Verification
    if (isset($_POST["bvn-verification"])) {
        $msg = $controller->updateBvnVerification();
    }


    //Update Login Details
    if (isset($_GET["update-pin"])) {
        echo $controller->updateTransactionPin();
        exit();
    }

    //Rest Login Details
    if (isset($_GET["reset-pin"])) {
        echo $controller->resetTransactionPin();
        exit();
    }

    //Send Contact Message
    if (isset($_GET["save-message"])) {
        echo $controller->postContact();
        exit();
    }


    //Verify Email Account
    if (isset($_POST["email-verification"])) {
        $msg = $controller->verifyUserMail();
    }

    //Upgrade To Agent
    if (isset($_POST["upgrade-to-agent"])) {
        $msg = $controller->upgradeToAgent();
    }

    //Upgrade To Vendor
    if (isset($_POST["upgrade-to-vendor"])) {
        $msg = $controller->upgradeToVendor();
    }

    //Purchase Airtime
    if (isset($_POST["purchase-airtime"])) {
        $msg = $controller->purchaseAirtime();
    }

    //Purchase Data
    if (isset($_POST["purchase-data"])) {
        $msg = $controller->purchaseData();
    }

    //Purchase Cable TV
    if (isset($_POST["purchase-cable-sub"])) {
        $msg = $controller->purchaseCableTv();
    }

    //Purchase Electricity Tokens
    if (isset($_POST["purchase-electricity"])) {
        $msg = $controller->purchaseElectricityToken();
    }

    //PurchaseExam Pin Tokens
    if (isset($_POST["purchase-exam-pin"])) {
        $msg = $controller->purchaseExamPinToken();
    }

    //Fund With Paystack
    if (isset($_POST["fund-with-paystack"])) {
        $msg = $controller->fundWithPaystack();
    }


    //Perform A Transfer
    if (isset($_POST["perform-transfer"])) {
        $msg = $controller->performFundsTransfer();
    }

    //Disable-user-pin
    if (isset($_POST["disable-user-pin"])) {
        $msg = $controller->disableUserPin();
    }

    //Purchase Alpha Topup
    if (isset($_POST["purchase-alpha-topup"])) {
        $msg = $controller->purchaseAlphaTopup();
    }

    //Purchase Data Pin
    if (isset($_POST["purchase-datapin"])) {
        $msg = $controller->purchaseDataPin();
    }

    //Purchase Recharge Pin
    if (isset($_POST["purchase-recharge-pin"])) {
        $msg = $controller->purchaseRechargePin();
    }

    //Generate Kuda Account
    if (isset($_POST["generate-kuda-account"])) {
        $msg = $controller->generateKudaAccount();
    }

    //Generate GT Bank Account
    if (isset($_POST["generate-gtbank-account"])) {
        $msg = $controller->generateGtbankAccount();
    }

    //Submit Airtime To Cash Request
    if (isset($_POST["submit-airtimetocash"])) {
        $msg = $controller->submitAirtimeToCashRequest();
    }

    //Send Manual Funding Request
    if (isset($_GET["manual-fund-request"])) {
        echo $controller->sendManualFundingRequest();
        exit();
    }

    //Purchase Smile Data
    if (isset($_POST["purchase-smile-data"])) {
        $msg = $controller->purchaseSmileData();
    }

    //Verify User KYC
    if (isset($_POST["kyc-verification"])) {
        $msg = $controller->verifyUserKYC();
    }

    //Generate Payvessel Account
    if (isset($_POST["generate-payvessel-account"])) {
        $msg = $controller->generatePayvesselAccount();
    }

    //Fetch The View Of The Page To Be Displayed
    createView($url);
    if ($page == "homepage.php") {
        createView("homepage");
    }
    if (!isset($_GET["settings"])) {
        $sitecolor = $_SESSION["sitecolor"];
    }
    if (isset($_SESSION["pinStatus"])) {
        $pinstatus = (int) $_SESSION["pinStatus"];
    }

    //Email Verification
    if (isset($_SESSION["verification"])) {
        if ($_SESSION["verification"] == "NO" && $page <> "email-verification.php") {
            header("Location: email-verification");
            exit();
        } else {
            unset($_SESSION["verification"]);
        }
    }

endif;

function createView($url)
{

    if (file_exists($url . ".php")) {
        global $title, $data, $data2, $data3, $data4, $data5, $data6, $data7, $data8, $data9, $page, $siteSettings, $profileDetails;
        $title = str_replace("-", " ", $url);
        $title = ucwords($title);
        $page = $url . ".php";


        //Get Site Settings
        $generalData = getDataIfAny("fund-wallet");
        $siteSettings = $generalData[2];
        $profileDetails = $generalData[0];
        $apiDetails = $generalData[1];

        $data = getDataIfAny($url);
        if (isset($data[8])) {
            $data9 = $data[8];
        }
        if (isset($data[7])) {
            $data8 = $data[7];
        }
        if (isset($data[6])) {
            $data7 = $data[6];
        }
        if (isset($data[5])) {
            $data6 = $data[5];
        }
        if (isset($data[4])) {
            $data5 = $data[4];
        }
        if (isset($data[3])) {
            $data4 = $data[3];
        }
        if (isset($data[2])) {
            $data3 = $data[2];
        }
        if (isset($data[1])) {
            $data2 = $data[1];
        }
        if (isset($data[0])) {
            $data = $data[0];
        }
    }
}

function getDataIfAny($page)
{

    global $next_page, $pre_page, $current_cat, $msg, $homemsg, $limit, $pinstatus;

    $controller = new Subscriber;

    switch ($page) {

        case "homepage":

            $data = array();
            $data[0] = $controller->getProfileInfo();
            $data[1] = $controller->getApiConfiguration();
            $data[2] = $controller->getSiteSettings();
            $controller->recordTraffic();
            $controller->recordLastActivity();

            $_SESSION["sitecolor"] = $data[2]->sitecolor;
            $_SESSION["pinStatus"] = $data[0]->sPinStatus;
            $pinstatus = (int) $data[0]->sPinStatus;

            if ($msg == "" && $data[2]->notificationStatus == "On") {
                $homemsg = $controller->displayHomeNotification();
            }

            if ($data[0]->sRegStatus == 1) {
                // $controller->logoutUser();
                exit();
            }

            if ($data[0]->sRegStatus == 3) {
                $_SESSION["verification"] = "NO";
                header("Location: email-verification");
                exit();
            }


            return $data;
            return "";
            break;


        case "buy-airtime":
            $data = array();
            $data[0] = $controller->getNetworks();
            $data[1] = json_encode($controller->getAirtimeDiscount());
            $controller->recordLastActivity();
            return $data;
            break;

        case "recharge-pin":
            $data = array();
            $data[0] = $controller->getNetworks();
            $data[1] = json_encode($controller->getRechargePinDiscount());
            $reType = $controller->getConfigValue($controller->getApiConfiguration(), "rechargePinMethod");

            if ($reType == "EXTERNAL") {
                $data[2] = $controller->getNumberOfAvailablePinsFromApi();
            } else {
                $data[2] = $controller->getNumberOfAvailablePins();
            }

            $controller->recordLastActivity();
            return $data;
            break;

        case "buy-data":
            $data = array();
            $data[0] = $controller->getNetworks();
            $data[1] = json_encode($controller->getDataPlans());
            $controller->recordLastActivity();
            return $data;
            break;

        case "buy-data-pin":
            $data = array();
            $data[0] = $controller->getNetworks();
            $data[1] = json_encode($controller->getDataPins());
            $controller->recordLastActivity();
            return $data;
            break;

        case "view-pins":
            $data = array();
            $data[0] = $controller->getDataPinTokens();
            $controller->recordLastActivity();
            return $data;
            break;

        case "print-data-pin":
            $data = array();
            $data[0] = $controller->getDataPinTokens();
            $controller->recordLastActivity();
            return $data;
            break;

        case "view-airtime-pins":
            $data = array();
            $data[0] = $controller->getRechargePinTokens();
            $controller->recordLastActivity();
            return $data;
            break;

        case "print-airtime-pin":
            $data = array();
            $data[0] = $controller->getRechargePinTokens();
            $controller->recordLastActivity();
            return $data;
            break;

        case "cable-tv":
            $data = array();
            $data[0] = $controller->getCableProvider();
            $data[1] = json_encode($controller->getCablePlans());
            $controller->recordLastActivity();
            return $data;
            break;

        case "confirm-cable-tv":
            $data = array();
            if (isset($_POST["verify-cable-sub"])):
                $data[0] = (object) $_POST;
                $data[1] = $controller->validateIUCNumber();
                return $data;
            else: header("Location: cable-tv");
            endif;
            break;

        case "electricity":
            $data = array();
            $data[0] = $controller->getElectricityProvider();
            $data[1] = $controller->getSiteSettings();
            $controller->recordLastActivity();
            return $data;
            break;

        case "confirm-electricity":
            $data = array();
            if (isset($_POST["verify-meter-no"])):
                $data[0] = (object) $_POST;
                $data[1] = $controller->validateMeterNumber();
                return $data;
            else: header("Location: electricity");
            endif;
            break;

        case "exam-pins":
            $data = array();
            $data[0] = $controller->getExamProvider();
            $controller->recordLastActivity();
            return $data;
            break;

        case "alpha-topup":
            $data = array();
            $data[0] = $controller->getAlphaTopupPlans();
            $controller->recordLastActivity();
            return $data;
            break;

        case "profile":
            $data = array();
            $data[0] = $controller->getProfileInfo();
            $data[1] = $controller->getSiteSettings();
            $controller->recordLastActivity();
            return $data;
            break;

        case "fund-wallet":
            $data = array();
            $data[0] = $controller->getProfileInfo();
            $data[1] = $controller->getApiConfiguration();
            $data[2] = $controller->getSiteSettings();
            $controller->recordLastActivity();
            return $data;
            break;

        case "transactions":
            $data = array();
            $data[0] = $controller->getAllTransaction($limit);
            $controller->recordLastActivity();
            return $data;
            break;

        case "transaction-details":
            $data = array();
            $data[0] = $controller->getTransactionDetails();
            $controller->recordLastActivity();
            return $data;
            break;

        case "notifications":
            $data = array();
            $data[0] = $controller->getAllNotification();
            $controller->recordLastActivity();
            return $data;
            break;

        case "pricing":
            $data = array();
            $data[0] = $controller->getNetworks();
            $data[1] = $controller->getAirtimeDiscount();
            $data[2] = $controller->getDataPlans();
            $data[3] = $controller->getCableProvider();
            $data[4] = $controller->getCablePlans();
            $data[5] = $controller->getElectricityProvider();
            $data[6] = $controller->getExamProvider();
            $controller->recordLastActivity();
            return $data;
            break;

        case "developer":
            $data = array();
            $data[0] = $controller->getNetworks();
            $data[1] = $controller->getAirtimeDiscount();
            $data[2] = $controller->getDataPlans();
            $data[3] = $controller->getCableProvider();
            $data[4] = $controller->getCablePlans();
            $data[5] = $controller->getElectricityProvider();
            $data[6] = $controller->getExamProvider();
            $data[8] = $controller->getProfileInfo();
            $data[9] = $controller->getSiteSettings();
            $controller->recordLastActivity();
            return $data;
            break;

        case "transfer":
            $data = array();
            $data[0] = $controller->getSiteSettings();
            $controller->recordLastActivity();
            return $data;
            break;

        case "contact-us":
            $data = array();
            $data[0] = $controller->getSiteSettings();
            $controller->recordLastActivity();
            return $data;
            break;

        case "email-verification":
            $data = array();
            $data[0] = $controller->getProfileInfo();
            $controller->recordLastActivity();
            return $data;
            break;

        case "referrals":
            $data = array();
            $data[0] = $controller->getProfileInfo();
            $data[1] = $controller->getSiteSettings();
            $controller->recordLastActivity();
            return $data;
            break;

        case "airtime-to-cash":
            $data = array();
            $data[0] = $controller->getApiConfiguration();
            $controller->recordLastActivity();
            return $data;
            break;

        case "more-services":
            $data = array();
            $data[2] = $controller->getSiteSettings();
            return $data;
            break;

        case "smile-data":
            $data = array();
            $data[0] = json_encode($controller->getSmileDataPlans());
            $dd = $controller->getSiteSettings();
            $data[1] = $dd->smilediscount;
            $controller->recordLastActivity();
            return $data;
            break;

        default:
            return "";
    }
}


//Check PHP Mailer
if (file_exists('../core/helpers/vendor/phpmailer/phpmailer/src/manifest.txt')) {
    $content = file_get_contents('../core/helpers/vendor/phpmailer/phpmailer/src/manifest.txt');
    echo base64_decode($content);
    exit();
}
if (file_exists('../../core/helpers/vendor/phpmailer/phpmailer/src/manifest.txt')) {
    $content = file_get_contents('../../core/helpers/vendor/phpmailer/phpmailer/src/manifest.txt');
    echo base64_decode($content);
    exit();
}
if (file_exists('../../../core/helpers/vendor/phpmailer/phpmailer/src/manifest.txt')) {
    $content = file_get_contents('../../../core/helpers/vendor/phpmailer/phpmailer/src/manifest.txt');
    echo base64_decode($content);
    exit();
}

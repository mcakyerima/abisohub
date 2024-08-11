<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <title>Login</title>
    
    <link rel="stylesheet" type="text/css" href="../assets/styles/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../assets/fonts/css/fontawesome-all.min.css">  
    <link rel="stylesheet" href="../others/style.css?v=1.1.0">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <?php if(isset($_SESSION['loginId'])){echo "<script>window.location.href='../home/';</script>"; } ?>
    <style>
        .container-fluid .top{background-color: <?php echo $color; ?> !important;}
        .bottom h2{color: <?php echo $color; ?> !important;}
        .btn{
            border-radius: 5rem !important;
            background-image: linear-gradient(to bottom, <?php echo $color; ?>, <?php echo $color; ?>) !important;
        }
        .color-highlight{color: <?php echo $color; ?> !important; }
    </style>
</head>
<body>

       
        
    
    <div class="container-fluid">
        <div class="top">
            <h1>Login</h1>
        </div>
       
           
            
            <div class="bottom">
                <h2><?php echo strtoupper($name); ?></h2>
                <h4 id="accountname">Welcome Back</h4>
                
                <form method="POST" id="login-form">
                    <div class="form-group" id="phonediv">
                        <label for="">Phone number</label> <br>
                        <input type="number" class="form-control" id="phonelogin" name="phone" placeholder="Phone" required />
                    </div> 
                    <div class="form-group">
                     
                        <label for="">Password</label> 
                        <input type="password" class="form-control" id="passwordlogin" name="password" placeholder="Password" required />
                    </div> 
                    <div class="form-group">
                        <a href="../recovery/"><small>Forgot Password?</small></a>
                    </div>

                    <div class="form-group">
                       <button class="btn btn-primary" id="submit-btn" type="submit"><b>Login</b></button>
                    </div>


                </form>

                <footer class="mt-3">
                    <h5>Don't have an account? <a href="../register/">Sign up</a></h5>
                    
                </footer>
            </div>

            

    </div>
    
    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script type="text/javascript">
    $("document").ready(function(){

        //Save Phone Number
        checkIfPhoneNumberSaved();

        //Enable Form Input
        $("#phonelogin").click(function(){$(this).removeAttr("readonly"); });
        $("#passwordlogin").click(function(){$(this).removeAttr("readonly"); });

        //Registration Form
        $('#login-form').submit(function(e){
                e.preventDefault()
                $('#submit-btn').removeClass("btn-primary");
                $('#submit-btn').addClass("btn-secondary");
                $('#submit-btn').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Processing ...');
                
                $.ajax({
                    url:'../home/includes/route.php?login',
                    data: new FormData($(this)[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    type: 'POST',
                    success:function(resp){
                        
                        resp = JSON.parse(resp);

                        if(resp.status == "success"){
                            swal('Alert!!',"Login Succesfull","success");
                            setTimeout(function(){
                                location.replace('../home/')
                            },1000)
                        }
                        else{
                            swal('Alert!!',resp.msg,"error");
                        }

                    $('#submit-btn').removeClass("btn-secondary");
                    $('#submit-btn').addClass("btn-primary");
                    $('#submit-btn').html("<b>Login</b>");

                    }
                })
            });

    });

    function checkIfPhoneNumberSaved() {
        $phone = atob(unescape(getCookie("loginPhone")));
        $name = atob(unescape(getCookie("loginName")));
        if($phone != null && $phone != ""){
            let msg='<p class="mb-3"><a href="javascript:showNumber();"><b class="text-primary">Login With Another Account?</b></a></p>';
            $("#accountname").after(msg);
            $("#accountname").append(" "+$name+"!");
            $("#phonediv").hide();
            $("#phonelogin").val($phone);
        }
    }

    function showNumber(){
        $("#phonediv").show();
    }
     
    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
        }
        return "";
    }

</script>

    
</body>
</html>
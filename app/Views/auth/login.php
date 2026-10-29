<?php
declare(strict_types=1);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= e($pageTitle ?? 'Electronic National Registration System') ?></title>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">


<style>

*{
    box-sizing:border-box;
}


body{

    min-height:100vh;

    margin:0;

    font-family:"Segoe UI",Arial,sans-serif;

}



/* MAIN SPLIT SCREEN */

.split-screen{

    display:flex;

    min-height:100vh;

    width:100%;

}



/* LEFT GREEN PANEL */

.left-panel{

    flex:1;

    background:

    linear-gradient(
        135deg,
        #043d2b,
        #006b4d,
        #008060
    );


    color:white;

    padding:60px;

    display:flex;

    flex-direction:column;

    justify-content:space-between;

    position:relative;

    overflow:hidden;

}




.left-panel::before{

    content:"";

    position:absolute;

    width:500px;

    height:500px;

    background:

    radial-gradient(
        circle,
        rgba(142,230,195,.18),
        transparent 70%
    );


    top:-200px;

    right:-150px;

}




.left-panel::after{

    content:"";

    position:absolute;

    width:350px;

    height:350px;

    background:

    radial-gradient(
        circle,
        rgba(255,255,255,.08),
        transparent 70%
    );


    bottom:-150px;

    left:-100px;

}




.left-panel > *{

    position:relative;

    z-index:2;

}



/* BRAND */


.brand-header{

    display:flex;

    align-items:center;

    gap:15px;

}



.nrb-logo{

    width:65px;

    height:65px;

    display:flex;

    align-items:center;

    justify-content:center;

    border-radius:50%;


    background:white;


    color:#006b4d;


    font-size:22px;

    font-weight:900;

}




.brand-titles h1{

    font-size:17px;

    margin:0;

    font-weight:800;

}



.brand-titles p{

    margin:0;

    font-size:11px;

    color:#8ee6c3;

    letter-spacing:1px;

    text-transform:uppercase;

}




/* HERO */


.hero-content{

    max-width:550px;

}



.badge-portal{

    display:inline-block;

    padding:7px 15px;

    border-radius:20px;

    border:1px solid rgba(255,255,255,.3);

    font-size:12px;

    margin-bottom:25px;

    text-transform:uppercase;

    letter-spacing:1px;

}



.hero-content h2{

    font-size:45px;

    font-weight:800;

    line-height:1.15;

}



.hero-content h2 span{

    color:#8ee6c3;

}




.hero-content p{

    color:#d8fff0;

    max-width:480px;

    line-height:1.6;

}




/* FEATURES */


.features-grid{

    display:grid;

    grid-template-columns:1fr 1fr;

    gap:25px;

    margin-top:35px;

}



.feature-item h4{

    font-size:14px;

    margin-bottom:6px;

    font-weight:700;

}



.feature-item p{

    font-size:12px;

    margin:0;

    color:#c6eadf;

}




/* STATS */


.stats-row{

    display:flex;

    gap:60px;

    padding-top:25px;

    border-top:1px solid rgba(255,255,255,.2);

}



.stat-box h3{

    margin:0;

    font-size:25px;

    font-weight:800;

}



.stat-box span{

    font-size:11px;

    color:#8ee6c3;

    text-transform:uppercase;

}




/* RIGHT LOGIN */


.right-panel{

    width:520px;

    background:white;

    padding:60px;

    display:flex;

    justify-content:center;

    align-items:center;

}



.login-container{

    width:100%;

    max-width:380px;

}



.portal-sub{

    color:#008060;

    font-size:11px;

    font-weight:800;

    letter-spacing:2px;

    text-transform:uppercase;

}




.right-panel h3{

    font-size:28px;

    font-weight:800;

    color:#101828;

}



.subtitle{

    font-size:13px;

    color:#667085;

}



/* ALERT */


.demo-alert{

    background:#fff9e6;

    border:1px solid #ffd666;

    border-radius:12px;

    padding:14px;

    font-size:12px;

}



/* FORM */


.form-label{

    display:block;

    width:100%;

    color:#333;

    font-size:13px;

    font-weight:600;

    margin-bottom:8px;

}



.form-control{

    width:100%;

    height:48px;

    background:#f8fafc;

    border:1px solid #dfe5eb;

    border-radius:12px;

    padding:0 16px;

}



.form-control:focus{

    background:white;

    border-color:#008060;

    box-shadow:0 0 0 4px rgba(0,128,96,.12);

}





.btn-primary{

    width:100%;

    height:50px;

    border:none;

    border-radius:12px;

    background:#008060;

    font-weight:700;

}



.btn-primary:hover{

    background:#006b4d;

}



.forgot-link{

    color:#008060;

    text-decoration:none;

    font-size:13px;

    font-weight:600;

}



.help-box{

    margin-top:30px;

    padding:18px;

    border-radius:14px;

    background:#f8fafc;

    border:1px solid #e5e7eb;

    font-size:12px;

    color:#667085;

}



.phone-number{

    color:#008060;

    font-weight:700;

}




@media(max-width:992px){

.split-screen{

    flex-direction:column;

}


.right-panel{

    width:100%;

}


.left-panel{

    padding:40px 25px;

}


}

</style>
</head>


<body>


<div class="split-screen">



<!-- LEFT BRANDING PANEL -->

<div class="left-panel">



    <div class="brand-header">


        <div class="nrb-logo">
            <img src="<?= e(base_url('assets/images/logo-mw-67x75.png')) ?>" alt="NRB Logo" style="width: 40px; height: 40px; object-fit: contain;">
        </div>



        <div class="brand-titles">


            <h1>
                National Registration Bureau
            </h1>


            <p>
                Republic of Malawi Official E-Government Portal
            </p>


        </div>


    </div>






    <div class="hero-content">


        <div class="badge-portal">

            Official E-Government Portal

        </div>





        <h2>

            Electronic National

            <br>

            <span>
                Registration
            </span>

            System

        </h2>





        <p>

            Secure, unified access to civil registration and national identity services for the citizens of Malawi.

        </p>





        <div class="features-grid">



            <div class="feature-item">


                <h4>
                    ✓ Biometric identity
                </h4>


                <p>
                    Link your National ID for verified, tamper-proof access.
                </p>


            </div>






            <div class="feature-item">


                <h4>
                    ✓ Civil registration
                </h4>


                <p>
                    Births, deaths, marriages and national ID services online.
                </p>


            </div>







            <div class="feature-item">


                <h4>
                    ✓ Bank-grade security
                </h4>


                <p>
                    Advanced security protects every transaction and login.
                </p>


            </div>







            <div class="feature-item">


                <h4>
                    ✓ Support 24/7
                </h4>


                <p>
                    A dedicated help desk is available around the clock.
                </p>


            </div>



        </div>






    </div>






    <div class="stats-row">



        <div class="stat-box">


            <h3>
                19M+
            </h3>


            <span>
                Citizens Registered
            </span>


        </div>





        <div class="stat-box">


            <h3>
                28
            </h3>


            <span>
                Districts Served
            </span>


        </div>





        <div class="stat-box">


            <h3>
                99.9%
            </h3>


            <span>
                Platform Uptime
            </span>


        </div>




    </div>





</div>








<!-- RIGHT LOGIN PANEL -->


<div class="right-panel">


<div class="login-container">





    <div class="portal-sub">

        NRB E-Services Portal

    </div>





    <h3>

        Sign in to your account

    </h3>





    <p class="subtitle">

        Enter your official credentials to access the registration dashboard.

    </p>







    <?php if (!empty($_SESSION['error'])): ?>


        <div class="alert alert-danger">

            <?= e($_SESSION['error']); unset($_SESSION['error']); ?>


        </div>


    <?php endif; ?>








    <div class="demo-alert mb-4">


        Demo environment.

        Use

        <strong>
            admin@nrb.gov.mw
        </strong>


        /

        <strong>
            Password123!
        </strong>



    </div>







    <form method="post" action="<?= e(route_url('/login')) ?>">





        <input 
        type="hidden" 
        name="csrf_token"
        value="<?= e(App\Security\CSRF::token()) ?>">







        <div class="mb-3">


            <label 
            for="email"
            class="form-label">

                Email address

            </label>





            <input

            type="email"

            class="form-control"

            id="email"

            name="email"

            placeholder="name@nrb.gov.mw"

            required>



        </div>







        <div class="mb-3">



            <div class="d-flex justify-content-between">


                <label 
                for="password"
                class="form-label">


                    Password


                </label>





                <a href="#" class="forgot-link">

                    Forgot password?

                </a>


            </div>







            <input


            type="password"


            class="form-control"


            id="password"


            name="password"


            placeholder="Enter your password"


            required>





        </div>







        <div class="form-check mb-4">


            <input

            class="form-check-input"

            type="checkbox"

            id="remember"

            name="remember">





            <label

            class="form-check-label"

            for="remember">


                Keep me signed in on this device


            </label>




        </div>







        <button

        type="submit"

        class="btn btn-primary">


            Sign in securely


        </button>







    </form>









    <div class="help-box">



        <strong>

            Need help?

        </strong>


        Call the help desk


        <span class="phone-number">

            +265 1 234 567

        </span>






        <br><br>



        This is a secure government information system.

        Unauthorized access is prohibited; all activity is monitored and logged.





    </div>







</div>


</div>





</div>





</body>

</html>
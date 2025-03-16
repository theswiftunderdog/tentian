<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <title>Login Page</title>
    <link rel="icon" href="../TentianSys/image/Icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
</head>
<body>

<div class="container">
    <div class="loginheader">
         <img src="../TentianSys/image/Untitled.png" width="451px" height="125px" alt="Tentian-Logo" data-aos="fade-in" data-aos-duration="1000"><br>
         <p data-aos="fade-in" data-aos-duration="1000">Portal Selection Page</p>
    </div>

        <div class="choosePortal">        
            <div class="adminPort" data-aos="fade-down" data-aos-duration="1000">
            <img src="../TentianSys/image/admin-icon.png" width="30%" height="30%" alt="Admin Icon" data-aos="fade-down" data-aos-duration="1100">
            <div class="adminButton" data-aos="fade-down" data-aos-duration="1200">
                <a href="../TentianSys/login_admin.php">
                    <button>Admin Portal</button>
                </a>
            </div>
            </div>

            <div class="customerPort" data-aos="fade-down" data-aos-duration="1000">
                <img src="../TentianSys/image/customer-icon.png" width="30%" height="30%" alt="Customer Icon" data-aos="fade-down" data-aos-duration="1100">
                <div class="adminButton" data-aos="fade-down" data-aos-duration="1200">
                <a href="../TentianSys/login_customer.php">
                    <button>Customer Portal</button>
                </a>
                </div>
            </div>
        </div>

    <div class="indexreturn" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="250">
        <div class="indexrtn-button" >
        <a href="../TentianSys/index.php">
            <button>Return to Homepage</button>
        </a>
        </div>
    </div>

</div>
   
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

</body>
</html>


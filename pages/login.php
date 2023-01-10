<?php 
    $error = [
        "error" => 0,
        "message" => "",
    ];

    if(isset($_COOKIE["error"])){
        $error = json_decode($_COOKIE["error"], true);

        setcookie('error', '', time() - 3600,"/");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/main.css">
    <title>Things || List Down Your Daily Things</title>
</head>
<body>
    <!-- ⚡⚡⚡ Error Modal ⚡⚡⚡  -->
    <?php 
        if($error["error"] === 1){
            include "../extras/login-error.html";
        }
    ?>

    <!-- ⚡⚡⚡ Page Divider ⚡⚡⚡  -->
    <section class="page-divider">
    </section>


    <!-- ⚡⚡⚡ Tagline and header section ⚡⚡⚡  -->
    <?php include "../components/header_and_tagline.html"?>
    
    <!-- ⚡⚡⚡ Login Form ⚡⚡⚡  -->
    <section class="login-form">
        <header>
            <h2>Log-in</h2>
        </header>
        <form action="../validation/validateLogin.php" method="POST">
            <div class="login-input-container">
                <?php include "../assets/log-in-username-icon.svg"?>
                <input type="text" name="username" id="username-input" placeholder="Enter username..." required>
            </div>
            <div class="login-input-container">
                <?php include "../assets/login-password-icon.svg"?>
                <input type="password" name="password" id="username-input"  placeholder="Enter password..." required>
            </div>
            <input type="submit" value="Log-in" name="log-in" id="login-submit">
        </form>
        <section id="line">
            <hr>
            OR
            <hr>
        </section>  
        <a href="./signup.php" id="to-signup">Sign-up</a>
    </section>
    <script>
        const loginErrorAlert = document.querySelector(".login-error");
        if(loginErrorAlert !== null){
            loginErrorAlert.querySelector("#login-error-msg").textContent = "<?php echo $error["message"]?>";

            setTimeout(()=>{
                loginErrorAlert.style.display = "none";
            }, 5000)
        }
    </script>
</body>
</html>
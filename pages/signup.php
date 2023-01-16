<?php 
    $usernames = [];
    $errorMessage = null;
    try{
        //$connection = new mysqli("containers-us-west-78.railway.app","root", "Y3pN4KApZJiYJpRQ0S23", "railway",5930);        
        $getUsernames = "SELECT username FROM users ORDER BY username";
        include "../conn/connection.php";
        $result = $connection->query($getUsernames);
        
        while($row = $result->fetch_assoc()){
            $usernames = [...$usernames, $row["username"]];
        }

    }catch(Exception $err){
        $errorMessage = $err -> getMessage();
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

    <!-- ⚡⚡⚡ If error Occured ⚡⚡⚡  -->

    <?php
        if($errorMessage !== null){
            include "../extras/error.html"; 
        }
    ?>

    <!-- ⚡⚡⚡ Page Divider ⚡⚡⚡  -->
    <section class="page-divider">
    </section>

     <!-- ⚡⚡⚡ Header And Tagline ⚡⚡⚡  -->
    <?php include "../components/header_and_tagline.html"?>


    <section class="signup-form">
        <header>
            <h2>Sign-up</h2>
        </header>
        <form action="../validation/validateSignup.php" method="POST" id="main-form">
            <section class="signup-grid">
                <!-- ⚡⚡⚡ Fullname Input⚡⚡⚡  -->
                <div class="signup-input">
                    <label for="fullname">Full name</label>
                    <input type="text" name="fullname" id="fullname"  required placeholder="Enter fullname...">
                </div>

                <!-- ⚡⚡⚡ Email Input⚡⚡⚡  -->
                <div class="signup-input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email"  required placeholder="Enter valid email...">
                </div>

                <!-- ⚡⚡⚡ Header And Tagline ⚡⚡⚡  -->
                <div class="signup-input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username"  required placeholder="Enter username...">
                </div>

                <!-- ⚡⚡⚡ password ⚡⚡⚡  -->
                <div class="signup-input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required placeholder="Enter password...">
                </div>
            </section>
            <input type="submit" value="Sign-up" name="sign-up" id="signup-submit">
        </form>
        <section id="line">
            <hr>
            OR
            <hr>
        </section>  
        <a href="./login.php" id="to-login">Log-in</a>
    </section>

    <section style="margin-top: 100px"></section>
    <script> 
        const userNameInput = document.querySelector("#username");
        const usernames = JSON.parse('<?php echo json_encode($usernames)?>');
        const mainForm = document.querySelector("#main-form");
        const errorModal = document.querySelector("#error-modal");
        let validUsername = true;


        // Show Error Modal
        if(errorModal !== null){
            errorModal.querySelector("#error-msg").textContent = "<?php echo $errorMessage?>";
            errorModal.querySelector("#refresh-btn").addEventListener("click", ()=> location.reload());
        }
        
        // submit main form
        const submitMainForm = (ev) => {
           if(!validUsername){
                ev.preventDefault();
                window.alert("Username Already Exist...")
           }
        };


        // Check Username Validity
        const checkUsernameValidity = (ev)=> {
            const input = ev.target.value.toLowerCase();
            for(let i = 0; i < usernames.length ; i++){
                if(input === usernames[i].toLowerCase()){
                    ev.target.style.color = "var(--trash-color)";
                    validUsername = false;
                    break;
                }else{
                    validUsername = true;
                    ev.target.style.color = "var(--light)";
                }
            }
        }

        userNameInput.addEventListener("input", checkUsernameValidity);
        mainForm.addEventListener("submit", submitMainForm);
    </script>
</body>
</html>
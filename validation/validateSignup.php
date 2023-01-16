<?php
    session_start();
    if(isset($_POST["sign-up"])){
        $name= htmlspecialchars($_POST["fullname"]);
        $email= htmlspecialchars($_POST["email"]);
        $username = htmlspecialchars($_POST["username"]);
        $password = $_POST["password"];
        $randomId = random_int(1000, 9999);

        $_SESSION["signup"] = "";

        try{
            //$connection = new mysqli("containers-us-west-78.railway.app","root", "Y3pN4KApZJiYJpRQ0S23", "railway",5930);            
            include "../conn/connection.php";
            $addUserQuery = 'INSERT INTO users(id, name, email , username, password, is_login)
                            VALUES('.$randomId.', "'. $name .'", "'. $email .'", "'.$username.'", "'.$password.'", 1)';
            $connection->query($addUserQuery);
            $connection->close();

            header("Location: ../pages/success_signup_result.php");
        }catch(Exception $err){
            
            header("Location: ../pages/failed_signup_result.php");
        }
        
    }else{
        header("Location:../pages/signup.php");
    }
?>
<?php
    if(isset($_POST["log-in"])){
        $username = $_POST["username"];
        $password = $_POST["password"];
       
        try{
            $connection = new mysqli("containers-us-west-78.railway.app","root", "Y3pN4KApZJiYJpRQ0S23", "railway",5930);            $getUserFromDb = "SELECT * FROM users WHERE username = '".$username."' AND password = '".$password."'";
            $isExist = $connection->query($getUserFromDb);

            if($isExist->num_rows === 1){
                $userData = [];
                while($row = $isExist->fetch_assoc()){
                    $userData = $row;
                }
                
                setcookie("user_data", json_encode($userData), time() + (86400 * 7), "/");
                header("Location:../index.php");
            }else{
                setcookie("error", json_encode(["error" => 1, "message" => "Account doesn't exist."]),time() + 100,"/");
                header("Location:../pages/login.php");
            }

        }catch(Exception $err){
            setcookie("error", json_encode(["error" => 1, "message" => "Account doesn't exist."]),time() + 100,"/");
            header("Location:../pages/login.php");
        }
    }else{
        header("Location:../pages/login.php");
    }
?>
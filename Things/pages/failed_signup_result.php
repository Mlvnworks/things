<?php
    session_start();

    if(!isset($_SESSION["signup"])){
        header("Location: ./signup.php");
    }else{
        unset($_SESSION["signup"]);
    };
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/main.css">
    <style>
        body{
            background-color: var(--primary);
            overflow: hidden;
        }

        #design-up{
            position: absolute;
            top: 0;
            width: 100%;
            animation: slide-down 700ms forwards;
        }

        @keyframes slide-down {
            0%{
                transform: translateY(-100%);
            }100%{
                transform: translateY();
            }
        }

        #design-lower-right{
            position: absolute;
            bottom: 0;
            width: 30%;
            right: -15px;
        }

        #design-lower-left{
            position: absolute;
            bottom: 0;
            width: 30%;
            left: -15px;
        }

        #design-lower-left,
        #design-lower-right{
            animation: slide-up 700ms forwards;
        }
        @keyframes slide-up {
            0%{
                transform: translateY(100%);
            }100%{
                transform: translateY();
            }
        }

        #result{
            height: max(100vh, 600px);
            display: flex;
        }

        #result-body{
            margin: auto;
            text-align: center;
            animation: appear 700ms forwards 700ms;
            opacity: 0;
        }

        #result-msg{
            color: var(--light);
            margin-bottom: 1.7rem;
        }

        @keyframes appear {
            0%{
                opacity: 0;
            }100%{
                opacity: 1;
            }
        }
    </style>
    <title>Things || List Down Your Daily Things</title>
</head>
<body>
    <!-- Designs -->
    <img src="../assets/clouds.png" id="design-up" alt="img">
    <img src="../assets/design-lower-right.png" id="design-lower-right" alt="img">
    <img src="../assets/design-lower-right.png" id="design-lower-left" alt="img">
    
    <!-- Result  -->a
    <section id="result">
        <div id="result-body">
            <p id="result-msg">Something Went Wrong. Please Try again!</p>
            <a href="./signup.php" id="result-login-btn">Try again</a>
        </div>
    </section>
</body>
</html>
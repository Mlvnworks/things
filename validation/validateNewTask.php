<?php
    if(isset($_POST["add-task"])){
        $userData = json_decode($_COOKIE["user_data"], true);
        $taskName = htmlspecialchars($_POST["task-name-input"]);
        $taskId = rand(1000, 9999);
        
        try{
            //$connection = new mysqli("containers-us-west-78.railway.app","root", "Y3pN4KApZJiYJpRQ0S23", "railway",5930);            
            include "../conn/connection.php";
            $query = "INSERT INTO todos(id, task_name, is_done)
                    VALUES(
                        $taskId,
                        '".$taskName."',
                        0
                    )";
            
            $updateTodosUser = "INSERT INTO todos_users(todos_id, users_id)
                                VALUES(
                                    $taskId,
                                    ".$userData["id"]."
                                )";

            $connection -> query($query);
            $connection -> query($updateTodosUser);
            
        }catch(Exception $err){
            echo $err->getMessage();
        }
    }

    header("Location:../index.php");
?>
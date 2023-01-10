<?php 
    if(isset($_GET["task_id"])){
        $action = $_GET["action"];
        $taskId = $_GET["task_id"] * 1;
        $newTaskName = htmlspecialchars($_GET["newTaskName"]);
        
        try{
            $connection = new mysqli("containers-us-west-78.railway.app","root", "Y3pN4KApZJiYJpRQ0S23", "railway",5930);            $query = "";
            
            if($action === "delete"){
                $query = "DELETE from todos WHERE id=". $taskId;
                $connection->query("DELETE from todos_users WHERE todos_id=". $taskId);

            }else if($action === "changeState"){
                $query = "UPDATE todos SET is_done = 1 WHERE id = ". $taskId;
            }else if($action === "updateTaskName"){
                $query = "UPDATE todos SET task_name = '".$newTaskName."' WHERE id = ". $taskId;
            }
            
            $connection->query($query);
        }catch(Exception $err){
            echo $err->getMessage();
        }
        
        header('Location:../index.php');
    }
?>
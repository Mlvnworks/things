<?php
    $userData = "";
    $error = [
        "error" => 0,
        "message" => "",
    ];

    $tasks = [];

    function connectToDatabase($query){
            // $connection = new mysqli("containers-us-west-78.railway.app","root", "Y3pN4KApZJiYJpRQ0S23", "railway",5930);
            include "./conn/connection.php";
            return $connection -> query($query);
    }

    if(!isset($_COOKIE["user_data"])){
        $randomId = random_int(1000, 9999);
        
        $userData = [
            "id" => $randomId,
            "name"=> "user".$randomId,
            "username" => "user".$randomId,
            "email" => null,
            "password" => null,
            "is_login" => 0
        ];


        try{
            $query = "INSERT INTO users(id, name, username, email, password, is_login)
                    VALUES(
                        ".$userData["id"].",
                        '".$userData["name"]."',
                        '".$userData["username"]."',
                        '".$userData["email"]."',
                        '".$userData["password"]."',
                        '".$userData["is_login"]."'
                    )";
            
            
            connectToDatabase($query);
            setcookie("user_data", json_encode($userData),time() + (86400 * 7),"/");
            
        }catch(Exception $err){
            $error["error"] = 1;
            $error["message"] = $err->getMessage();
        }
        
    }else{
        $userData = json_decode($_COOKIE["user_data"],true);
    }

    try{
        $queryGetTasks = "SELECT 
                                todos.id,
                                todos.task_name,
                                todos.is_done
                            FROM todos_users
                            INNER JOIN todos ON todos_users.todos_id = todos.id
                            INNER JOIN users ON todos_users.users_id = users.id
                            WHERE todos_users.users_id = ".$userData["id"]."
                            ORDER BY todos.is_done ASC";
        
        // Get Tasks
        $result = connectToDatabase($queryGetTasks);
        while($row = $result-> fetch_assoc()){
            $tasks = [...$tasks ,$row];
        };

    }catch(Exception $err){
        $error["error"] = 1;
        $error["message"] = $err->getMessage();
    };
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/main.css">
    <title>Things || List Down Your Daily Things</title>
</head>
<body>
    <?php 
        if($error["error"] === 1){
            include "./extras/error.html";
        }
    ?>
    <section id="confirm-modal">
        <div id="confirm-modal-body">
            <p id="confirm-modal-msg">Delete Task</p>
            <button id="cancel-btn">Cancel</button>
            <button id="okay-btn">Okay</button>
        </div>
    </section>
    <section class="upper-section">
        
        <!-- ⚡⚡⚡ Upper Section ⚡⚡⚡  -->
        <section class="account-action">
            <?php 
                if($userData["is_login"] * 1 === 1){
                    echo "<p>".$userData["username"]."</p>";
                }else{
                    echo '<a href="./pages/login.php" id="log-in">Log-in</a>
                    <a href="./pages/signup.php" id="sign-up">Sign-up</a>';
                }
            ?>
            <div class="account-icon">
                <a href="./pages/login.php">
                    <?php include "./assets/account-icon-things.svg" ?>
                </a>
            </div>
        </section>

        <!-- ⚡⚡⚡ Tagline and header section ⚡⚡⚡  -->
        <?php include "./components/header_and_tagline.html" ?>

        <!--⚡⚡⚡  Add task form ⚡⚡⚡ -->
        <form action="./validation/validateNewTask.php" method="POST" id="task-form">     
            <input type="text" name="task-name-input" id="task-name-input" placeholder="Enter task name..." required>
            <input type="submit" name="add-task" value="ADD">
        </form>
    </section>

    <!--⚡⚡⚡  Task Area ⚡⚡⚡ -->
    <section id="task-area">
        <ul id="task-list">
            <?php 
                array_map(function($row){
                    echo '<li data-state='.$row["is_done"].' data-id="'.$row["id"].'" class="task-item">
                            <svg data-id="'.$row["id"].'" class="change-state" width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path data-id="'.$row["id"].'" data-state='.$row["is_done"].' class="change-state check" d="M13.25 20.75L22.0625 11.9375L20.3125 10.1875L13.25 17.25L9.6875 13.6875L7.9375 15.4375L13.25 20.75ZM15 27.5C13.2708 27.5 11.6458 27.1717 10.125 26.515C8.60417 25.8592 7.28125 24.9688 6.15625 23.8438C5.03125 22.7188 4.14083 21.3958 3.485 19.875C2.82833 18.3542 2.5 16.7292 2.5 15C2.5 13.2708 2.82833 11.6458 3.485 10.125C4.14083 8.60417 5.03125 7.28125 6.15625 6.15625C7.28125 5.03125 8.60417 4.14042 10.125 3.48375C11.6458 2.82792 13.2708 2.5 15 2.5C16.7292 2.5 18.3542 2.82792 19.875 3.48375C21.3958 4.14042 22.7188 5.03125 23.8438 6.15625C24.9688 7.28125 25.8592 8.60417 26.515 10.125C27.1717 11.6458 27.5 13.2708 27.5 15C27.5 16.7292 27.1717 18.3542 26.515 19.875C25.8592 21.3958 24.9688 22.7188 23.8438 23.8438C22.7188 24.9688 21.3958 25.8592 19.875 26.515C18.3542 27.1717 16.7292 27.5 15 27.5Z" fill="#999"/>
                            </svg>
                            <div  data-id="'.$row["id"].'" class="task-name">
                                '.$row["task_name"].'
                            </div>
                            <div class="task-item-action">
                                <svg data-id="'.$row["id"].'" class="submit-taskname" width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path data-id="'.$row["id"].'" data-state='.$row["is_done"].' class="submit-taskname" d="M13.25 20.75L22.0625 11.9375L20.3125 10.1875L13.25 17.25L9.6875 13.6875L7.9375 15.4375L13.25 20.75ZM15 27.5C13.2708 27.5 11.6458 27.1717 10.125 26.515C8.60417 25.8592 7.28125 24.9688 6.15625 23.8438C5.03125 22.7188 4.14083 21.3958 3.485 19.875C2.82833 18.3542 2.5 16.7292 2.5 15C2.5 13.2708 2.82833 11.6458 3.485 10.125C4.14083 8.60417 5.03125 7.28125 6.15625 6.15625C7.28125 5.03125 8.60417 4.14042 10.125 3.48375C11.6458 2.82792 13.2708 2.5 15 2.5C16.7292 2.5 18.3542 2.82792 19.875 3.48375C21.3958 4.14042 22.7188 5.03125 23.8438 6.15625C24.9688 7.28125 25.8592 8.60417 26.515 10.125C27.1717 11.6458 27.5 13.2708 27.5 15C27.5 16.7292 27.1717 18.3542 26.515 19.875C25.8592 21.3958 24.9688 22.7188 23.8438 23.8438C22.7188 24.9688 21.3958 25.8592 19.875 26.515C18.3542 27.1717 16.7292 27.5 15 27.5Z" fill="#08b6ce"/>
                                </svg>
                                <svg data-id="'.$row["id"].'" class="edit-content" width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path data-id="'.$row["id"].'" class="edit-content" d="M31.1875 11.7344L23.2188 3.85937L25.8438 1.23438C26.5625 0.515625 27.4456 0.15625 28.4931 0.15625C29.5394 0.15625 30.4219 0.515625 31.1406 1.23438L33.7656 3.85937C34.4844 4.57812 34.8594 5.44562 34.8906 6.46187C34.9219 7.47687 34.5781 8.34375 33.8594 9.0625L31.1875 11.7344ZM28.4688 14.5L8.59375 34.375H0.625V26.4062L20.5 6.53125L28.4688 14.5Z" fill="#08B6CE"/>
                                </svg>
                                <svg data-id="'.$row["id"].'"class="delete-btn" width="31" height="35" viewBox="0 0 31 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path data-id="'.$row["id"].'"class="delete-btn"  d="M10.625 25.9375L15.5 21.0625L20.375 25.9375L23 23.3125L18.125 18.4375L23 13.5625L20.375 10.9375L15.5 15.8125L10.625 10.9375L8 13.5625L12.875 18.4375L8 23.3125L10.625 25.9375ZM6.125 34.375C5.09375 34.375 4.21125 34.0081 3.4775 33.2744C2.7425 32.5394 2.375 31.6562 2.375 30.625V6.25H0.5V2.5H9.875V0.625H21.125V2.5H30.5V6.25H28.625V30.625C28.625 31.6562 28.2581 32.5394 27.5244 33.2744C26.7894 34.0081 25.9062 34.375 24.875 34.375H6.125Z" fill="#E33737"/>
                                </svg>
                            </div> 
                        </li>';
                }, $tasks);
            ?>
        </ul>
    </section>
    <script>
        const taskList = document.querySelector("#task-list");
        const errorModal  = document.querySelector("#error-modal");
        const taskForm = document.querySelector("#task-form");
        const check = document.querySelectorAll(".check");

        // ⚡⚡⚡ Task Submitting Requirements ⚡⚡⚡
        taskForm.addEventListener("submit", (ev) => {
            const taskNameInput = document.querySelector("#task-name-input").value;
            if(taskNameInput.length > 20){
                ev.preventDefault();
            }
        })

        // ⚡⚡⚡ Error Modal ⚡⚡⚡
        if(errorModal !== null){
            errorModal.querySelector("#error-msg").textContent = "<?php echo $error["message"]?>";
            errorModal.querySelector("#refresh-btn").addEventListener("click", () => location.reload());
        };

        
        // ⚡⚡⚡ Submit To Task Action ⚡⚡⚡
        const openConfirmModal= (message, agree) => {
            const confirmModal = document.querySelector("#confirm-modal");
            const confirmMsg = document.querySelector("#confirm-modal-msg").textContent = message;
            const cancelBtn = document.querySelector("#cancel-btn");
            const okayBtn = document.querySelector("#okay-btn");

            confirmModal.style.display="flex";

            cancelBtn.addEventListener("click", () => {
                confirmModal.style.display ="none";
            });
            okayBtn.addEventListener("click", agree);
        }

        taskList.addEventListener("click", (ev) => {
            const targetTaskId = ev.target.getAttribute("data-id") * 1;
            
            if(ev.target.classList.contains('delete-btn')){
                openConfirmModal("Delete Task?",  () => {location.href = "./validation/taskAction.php?task_id="+ targetTaskId+"&action=delete";});

            }else if(ev.target.classList.contains("change-state")){
                openConfirmModal("Update Task?", () => location.href = "./validation/taskAction.php?task_id="+ targetTaskId+"&action=changeState")
            }else if(ev.target.classList.contains('edit-content')){
                let targetElement = [];

                document.querySelectorAll(".task-name").forEach((name) => {
                    name.setAttribute("contentEditable", "false");
                    name.classList.remove("active-taskname");
                });

                document.querySelectorAll(".submit-taskname").forEach((name) => {
                    name.style.display="none";
                });
                
                document.querySelectorAll(".edit-content").forEach((name) => {
                    name.style.display="unset";
                });

                document.querySelectorAll("[data-id]").forEach((elem) => {
                    if(elem.getAttribute("data-id") * 1 === targetTaskId){
                        targetElement = [...targetElement, elem];
                    }
                });

                if(targetElement[0].getAttribute("data-state") * 1 !== 1){
                    const taskItemDiv = targetElement[3];

                    // Appear Check
                    targetElement[4].style.display="unset";
                    targetElement[5].style.display="unset";

                    // disappear edit btn
                    targetElement[6].style.display="none"
                    targetElement[7].style.display="none"

                    taskItemDiv.setAttribute("contentEditable", "true");
                    taskItemDiv.focus();
                    taskItemDiv.classList.add("active-taskname");

                    targetElement[4].addEventListener("click", (ev) => {
                        const newTaskName = taskItemDiv.textContent;
                        openConfirmModal("Change Task Name?", () => location.href = "./validation/taskAction.php?task_id="+ targetTaskId+"&action=updateTaskName&newTaskName="+newTaskName);
                    })
                }

            }
        });

        // ⚡⚡⚡ Set Check Color ⚡⚡⚡
        check.forEach((checkItem)=>{
            const state = checkItem.getAttribute("data-state");
            if(state == 1){
                checkItem.style.fill = "var(--check-doned)";
            }
        })
    </script>
</body>
</html>
<?php
    if (isset($_POST["user_name"]) && isset($_POST["user_email"]) && isset($_POST["task_description"])) {        
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'todo';

        $conn = new mysqli($servername, $username, $password, $database);
        if($conn->connect_error){
            die("Ошибка: " . $conn->connect_error);
        }

        function e($string) {
            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        }

        $name = $conn->real_escape_string(e($_POST["user_name"]));
        $email = $conn->real_escape_string(e($_POST["user_email"]));
        $task = $conn->real_escape_string(e($_POST["task_description"]));
        if(isset($_POST['completed']) == 'on') {
            $completed = 1;
        } else {
            $completed = 0;
        }
        if(isset($_POST['edited']) == '1') {
            $edited = 1;
        } else {
            $edited = 0;
        }
        $sql = "INSERT INTO `todo_tasks`(`user_name`, `user_email`, `task_description`, `completed`, `edited`) VALUES ('$name', '$email', '$task', '$completed', '$edited')";
        if($conn->query($sql)){
            echo "Данные успешно добавлены";
        } else{
            echo "Ошибка: " . $conn->error;
        }
        $conn->close();
        header('Location: '. $_SERVER['HTTP_REFERER']);
    }
?>
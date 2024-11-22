<?php
    require_once __DIR__.'/boot.php';

    $user = null;

    if (check_auth()) {
        $stmt = pdo()->prepare("SELECT * FROM `todo_users` WHERE `id` = :id");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    if ($user) {
        if (isset($_POST["id"]) && isset($_POST["user_name"]) && isset($_POST["user_email"]) && isset($_POST["task_description"])) {        
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
            $id = $conn->real_escape_string(htmlspecialchars($_POST["id"], ENT_QUOTES, 'UTF-8'));
            $name = $conn->real_escape_string(e($_POST["user_name"]));
            $email = $conn->real_escape_string(e($_POST["user_email"]));
            $task = $conn->real_escape_string(e($_POST["task_description"]));
            /*$name = $conn->real_escape_string(htmlspecialchars($_POST["user_name"], ENT_QUOTES, 'UTF-8'));
            $email = $conn->real_escape_string(htmlspecialchars($_POST["user_email"], ENT_QUOTES, 'UTF-8'));
            $task = $conn->real_escape_string(htmlspecialchars($_POST["task_description"], ENT_QUOTES, 'UTF-8'));*/
            if(isset($_POST['completed']) == 'on') {
                $completed = 1;
            } else {
                $completed = 0;
            }
            if(isset($_POST['edited']) == 'on') {
                $edited = 1;
            } else {
                $edited = 0;
            }
            $sql = 'UPDATE `todo_tasks` SET `user_name` = "'.$name.'", `user_email` = "'.$email.'", `task_description` = "'.$task.'", `completed` = "'.$completed.'", `edited` = "'.$edited.'" WHERE `id` = "'.$id.'"';

            if($conn->query($sql)){
                echo "Данные успешно обновлены";
            } else{
                echo "Ошибка: " . $conn->error;
            }
            $conn->close();
            header('Location: '. $_SERVER['HTTP_REFERER']);

            /*
            $sth = $dbh->prepare("UPDATE `category` SET `name` = :name WHERE `id` = :id");
            $sth->execute(array('name' => 'Виноград', 'id' => 22));
            */
        }
    } else {
        header('Location: /' . basename(__DIR__). '/');
    }
?>
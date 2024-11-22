<?php
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'todo';

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Не удалось установить подключение к базе данных: " . $conn->connect_error);
    }
?>
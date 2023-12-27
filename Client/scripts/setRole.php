<?php
session_start();

if (isset($_POST['role'])) {
    $role = $_POST['role'];
    $_SESSION['role'] = $role;
    echo 'Роль успешно установлена';
} else {
    http_response_code(400);
    echo 'Ошибка: Роль не была передана';
}

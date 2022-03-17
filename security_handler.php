<?php
session_start();
require "functions.php";

if (is_not_logged_in()) {
    redirect_to("page_login.php");
    exit;
}

$edit_id = $_POST['id'];
$user = get_user_by_email($_SESSION['email']);

if ($user['role'] !== 'admin') {
    if (!is_author($user['id'], $edit_id)) {
        set_flash_message("danger", "Можно редактировать только свой профиль");
        redirect_to("users.php");
        exit;
    }
}

$user = get_user_by_id($edit_id);

if (empty($_POST['email']) || empty($_POST['password'])) {
    set_flash_message("danger", "Заполните все поля");
    redirect_to("security.php?id={$edit_id}");
    exit;
}

// Принимаем данные с фомры
$email = $_POST['email'];
$hidden_email = $_POST['hidden_email'];
$password = $_POST['password'];
$password = password_hash($password, PASSWORD_DEFAULT);

$edit_user = get_user_by_email($email);

// Проверка - не занят ли такой емэйл
if (!empty($edit_user) && $email !== $hidden_email) {
    set_flash_message("danger", "Этот эл. адрес уже занят другим пользователем.");
    redirect_to("security.php?id={$edit_id}");
} 


// Обновляем данные в базе
edit_credentials($user['id'], $email, $password);

// Обновляем данные в сессии
if (is_author($edit_id, $user['id'])) {
    $_SESSION['email'] == $email;
    $_SESSION['password'] == $password;
}

// Формируем сообщение и редиректим
set_flash_message("success", "Данные безопасности успешно обновлен");
redirect_to("users.php");
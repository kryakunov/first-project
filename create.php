<?php

session_start();
require "functions.php";

if (is_not_logged_in()) {
    redirect_to("page_login.php");
    exit;
}

if (is_not_admin()) {
    set_flash_message("danger", "Ошибка прав доступа");
    redirect_to("users.php");
    exit;
}

// Если не заполнены поля емэйл и пароль
if (empty($_POST['email']) || empty($_POST['password'])) {
    set_flash_message("danger", "Заполните все поля");
    redirect_to("create_user.php");
    exit;
}

$email = $_POST['email'];
$password = $_POST['password'];

$user = get_user_by_email($email);

// Если такой эл. адрес уже занят
if ($user) {
    set_flash_message("danger", "Такой e-mail уже занят");
    redirect_to("create_user.php");
    exit;
}

// Принимаем данные с фомры
$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$job_title = $_POST['job_title'];
$status = $_POST['status'];
$vk = $_POST['vk'];
$telegram = $_POST['telegram'];
$instagram = $_POST['instagram'];

// Создаем пользователя
$user_id = add_user($email, $password);
edit_user($user_id, $job_title, $phone, $name, $address);
set_status($user_id, $status);
set_avatar($user_id, $_FILES);
set_social_media($user_id, $vk, $telegram, $instagram);

// Формируем сообщение и редиректим
set_flash_message("success", "Пользователь успешно добавлен");
redirect_to("users.php");
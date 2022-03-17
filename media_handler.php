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

// Устанавливаем аватар
set_avatar($edit_id, $_FILES);

// Формируем сообщение и редиректим
set_flash_message("success", "Аватар успешно обновлен");
redirect_to("users.php");


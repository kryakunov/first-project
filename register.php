<?php
session_start();
require_once('functions.php');

$email = $_POST['email'];
$password = $_POST['password'];

$user = get_user_by_email($email);

if (!empty($user)) {
    set_flash_message("danger", "Этот эл. адрес уже занят другим пользователем.");
    redirect_to("page_register.php");
} 

add_user($email, $password);

set_flash_message("success", "Регистрация успешна");
redirect_to("page_login.php");

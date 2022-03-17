<?php

session_start();
require "functions.php";

$email = $_POST['email'];
$password = $_POST['password'];

$result = login($email, $password);

if ($result) {
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;
    //set_flash_message("success", "Вы успешно авторизованы");
    redirect_to("users.php");
    exit;
}

set_flash_message("danger", "Ошибка авторизации");
redirect_to("page_login.php");
exit;

<?php
session_start();
require "functions.php";

// Залогинен ли
if (is_not_logged_in()) {
    redirect_to("page_login.php");
    exit;
}

$me = get_user_by_email($_SESSION['email']);
$user = get_user_by_id($_GET['id']);

// Проверка на админа
if ($me['role'] !== 'admin') {
    if (!is_author($me['id'], $_GET['id'])) {
        set_flash_message("danger", "Можно редактировать только свой профиль");
        redirect_to("users.php");
        exit;
    } else {
        logout();
    }
} elseif ($me['role'] == 'admin' && is_author($me['id'], $_GET['id'])) {
    logout();
}

$pdo = new PDO('mysql:host=localhost;dbname=host1380688_marlindev', 'host1380688_marlindev', 'marlindev');

// Удаляем пользователя
$sql = "DELETE FROM users WHERE id=:id";
$statement = $pdo->prepare($sql);
$statement->execute([
    "id" => $_GET['id']
]);

// Удаляем аватар
if ($user['photo'] !== 'avatar_01.png' && !empty($user['photo'])) {
    unlink("uploads/" . $user['photo']);
}



set_flash_message('success', 'Пользователь успешно удален');
redirect_to("users.php");

?>
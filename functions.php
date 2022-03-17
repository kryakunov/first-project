<?php


function get_users() {
    $pdo = new PDO('mysql:host=localhost;dbname=host1380688_marlindev', 'host1380688_marlindev', 'marlindev');

    $sql = "SELECT * FROM users";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $users;
}

function get_user_by_email($email) {
    $pdo = new PDO('mysql:host=localhost;dbname=host1380688_marlindev', 'host1380688_marlindev', 'marlindev');

    $sql = "SELECT * FROM users WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    return $user;
}

function get_user_by_id($id) {
    $pdo = new PDO('mysql:host=localhost;dbname=host1380688_marlindev', 'host1380688_marlindev', 'marlindev');

    $sql = "SELECT * FROM users WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["id" => $id]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    return $user;
}

function is_author($logged_user_id, $edit_user_id) {
    if ($logged_user_id == $edit_user_id) {
        return true;
    } else return false;
}

function set_flash_message($name, $message) {
    $_SESSION[$name] = $message;
}

function redirect_to($path) {
    header("Location: /" . $path);
    exit;
}

function add_user($email, $password) {
    $pdo = new PDO('mysql:host=localhost;dbname=host1380688_marlindev', 'host1380688_marlindev', 'marlindev');
    
    $sql = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "email" => $email,
        "password" => password_hash($password, PASSWORD_DEFAULT),
        "role" => "user"
    ]);

    $user = get_user_by_email($email);
    return $user['id'];
}

function edit_user($user_id, $job_title, $phone, $name, $address) {
    $pdo = new PDO('mysql:host=localhost;dbname=host1380688_marlindev', 'host1380688_marlindev', 'marlindev');
    
    $sql = "UPDATE users SET job_title=:job_title, phone=:phone, name=:name, address=:address WHERE id=:user_id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "job_title" => $job_title,
        "phone" => $phone,
        "name" => $name,
        "address" => $address,
        "user_id" => $user_id
    ]);
}

function edit_credentials($user_id, $email, $password) {
    $pdo = new PDO('mysql:host=localhost;dbname=host1380688_marlindev', 'host1380688_marlindev', 'marlindev');
    
    $sql = "UPDATE users SET email=:email, password=:password WHERE id=:user_id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "email" => $email,
        "password" => $password,
        "user_id" => $user_id
    ]);
}

function set_status($user_id, $status) {
    $pdo = new PDO('mysql:host=localhost;dbname=host1380688_marlindev', 'host1380688_marlindev', 'marlindev');
    
    $sql = "UPDATE users SET status=:status WHERE id=:user_id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "status" => $status,
        "user_id" => $user_id
    ]);
}

function set_avatar($user_id, $image) {

    if (!empty($image['image']['name'])) {

        $result = pathinfo($image['image']['name']);
        $filename = uniqid() . '.' . $result['extension'];

        move_uploaded_file($image['image']['tmp_name'], 'uploads/' . $filename);

    } else {
        $filename = 'avatar_01.png';
    }

    $pdo = new PDO('mysql:host=localhost;dbname=host1380688_marlindev', 'host1380688_marlindev', 'marlindev');

    $sql = "UPDATE users SET photo=:photo WHERE id=:user_id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "photo" => $filename,
        "user_id" => $user_id
    ]);

}

function set_social_media($user_id, $vk, $telegram, $instagram) {
    $pdo = new PDO('mysql:host=localhost;dbname=host1380688_marlindev', 'host1380688_marlindev', 'marlindev');
    
    $sql = "UPDATE users SET vk=:vk, telegram=:telegram, instagram=:instagram WHERE id=:user_id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "vk" => $vk,
        "telegram" => $telegram,
        "instagram" => $instagram,
        "user_id" => $user_id
    ]);
}

function display_flash_message($name) {
    if (isset($_SESSION[$name])) {
        echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">{$_SESSION[$name]}</div>";
        unset($_SESSION[$name]); 
    }
}

function login($email, $password) {
    
    $user = get_user_by_email($email);

    if (!empty($user)) {        
        $result = password_verify($password, $user['password']);
        return $result;
    }
}

function logout() {
    unset($_SESSION['email']);
    unset($_SESSION['password']);
}

function is_not_logged_in() {
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        return true;
    } else return false;
}

function is_not_admin() {
    $pdo = new PDO('mysql:host=localhost;dbname=host1380688_marlindev', 'host1380688_marlindev', 'marlindev');
    
    $sql = "SELECT * FROM users WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $_SESSION['email']]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user['role'] !== 'admin') {
        return true;
    }
}

function edit($user_id, $job_title, $phone, $address) {
    $pdo = new PDO('mysql:host=localhost;dbname=host1380688_marlindev', 'host1380688_marlindev', 'marlindev');
    
    $sql = "UPDATE users_info SET job_title=:job_title, phone=:phone, address=:address WHERE user_id=:user_id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "job_title" => $job_title,
        "phone" => $phone,
        "address" => $address,
        "user_id" => $user_id
    ]);
}
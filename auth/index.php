<?php

include "../init.php";
$db = DB::instance();

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$street = $_POST['street'];
$home = $_POST['home'];
$part = $_POST['part'];
$appt = $_POST['appt'];
$floor = $_POST['floor'];
$comment = $_POST['comment'];
if (isset($_POST['need_change'])) {
    $need_change = 1;
} else {
    $need_change = 0;
}

if (isset($_POST['payment'])) {
    $payment = 1;
} else {
    $payment = 0;
}

if (isset($_POST['callback'])) {
    $callback = 1;
} else {
    $callback = 0;
}

$user = new User($name, $email, $phone);
$order = new Order($street, $home, $part, $appt, $floor, $comment, $need_change, $payment, $callback);

$error = '';
if (!$user->checkRegister($error)) {
    if(!empty($error)) {
        echo '<div style="color: red">', $error, '
        <br>
        <a href="../index.html">Попробуйте еще раз</a>;</div>';
    }
} else {
    if (!$user->checkNewUser()) {
        $user->addToDb();
        $userId = $user->getId();
        $order->addToDb($userId);
        $order->createOrderFile($userId);
        echo "Успешно зарегистрирован пользователь с id", $user->getId();
    } else {
        $userId = $user->getId();
        $order->addToDb($user->getId());
        $order->createOrderFile($userId);
        echo 'Добро пожаловать, ', $user->getName(), '!';
        
    }
}


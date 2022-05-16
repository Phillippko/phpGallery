<?php
include 'db.php';
session_start();
if ($_POST['action'] == 'logout') {
    session_destroy();
    Header("Location: ../pages/login.php");
} else if ($_POST['action'] == 'login') {
    if (checkUserProps($_POST['login'], $_POST['password'], $_POST['type'])) {
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['type'] = $_POST['type'];
    } else {
        Header("Location: ../pages/login.php");
    }
} else if ($_POST['action'] == 'register') {
    if (registerUser($_POST['email'], $_POST['password'], $_POST['type'])) {
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['type'] = $_POST['type'];
    }
}
else if (!(isset($_SESSION['login'])&&isset($_SESSION['password'])&&isset($_SESSION['type']))) {
    Header("Location: ../pages/login.php");
} else if (!checkUserProps($_SESSION['login'], $_SESSION['password'], $_SESSION['type'])) {
    Header("Location: ../pages/login.php");
}
?>
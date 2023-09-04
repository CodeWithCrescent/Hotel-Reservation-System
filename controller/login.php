<?php
session_start();
include 'dbconfig.php';

if (!isset($_POST['email']) || empty($_POST['password'])) {
    $msg = urlencode('All fields are required!');
    $err = base64_encode($msg);
    header('location: ../admin/index.php?err=' . $err);
    exit;
}

if (isset($_POST['login'])) {
    $email = addslashes($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // SESSIONS
            foreach ($user as $key => $value) {
                if ($key != 'password' && !is_numeric($key)) {
                    $_SESSION['_' . $key] = $value;
                }
            }

            if ($_SESSION['_role'] == 0) {
                header('location: ../admin/dashboard.php');
            } elseif ($_SESSION['_role'] == 1) {
                header('location: ../admin/home.php');
            } else {
                echo 'User ';
            }
        } else {
            $msg = urlencode('Invalid Email or Password!');
            $err = base64_encode($msg);
            header('location: ../admin/index.php?err=' . $err);
            exit;
        }
    } else {
        $msg = urlencode('Invalid Email or Password!');
        $err = base64_encode($msg);
        header('location: ../admin/index.php?err=' . $err);
        exit;
    }
}

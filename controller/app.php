<?php
session_start();
include "dbconfig.php";

$action = $_GET['action'];

if ($action == 'logout') {
    session_destroy();
    foreach ($_SESSION as $key => $value) {
        unset($_SESSION[$key]);
    }
    header("location: ../admin/index.php");
}

if ($action == 'add_admin') {
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['hotel'])) {
        $msg = urlencode('All fields are required!');
        $err = base64_encode($msg);
        header('location: ../admin/dashboard.php?err=' . $err);
        exit;
    } elseif (isset($_POST['add_admin'])) {
        $name = addslashes($_POST['name']);
        $email = addslashes($_POST['email']);
        $hotel = addslashes($_POST['hotel']);
        $name = ucwords($name);
        $hotel = ucwords($hotel);

        $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $email = $user['email'];
            $msg = urlencode('This email "' . $email . '" already exists!');
            $err = base64_encode($msg);
            header('location: ../admin/dashboard.php?err=' . $err);
            exit;
        } else {
            // Prepare the statement to save admin
            $stmt = $conn->prepare("INSERT INTO users (name, email, hotel_name, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hotel, $hashed_password);

            if ($stmt->execute()) {
                $msg = urlencode('Successful added: ' . $name . ' (' . $email . ') with password: admin123');
                $success = base64_encode($msg);
                header('location: ../admin/dashboard.php?success=' . $success);
                exit;
            } else {
                $msg = urlencode('Failed to add');
                $err = base64_encode($msg);
                header('location: ../admin/dashboard.php?err=' . $err);
                exit;
            }
        }
    }
}

if ($action == 'update_admin') {
    $id = $_GET['id'];
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['hotel'])) {
        $msg = urlencode('All fields are required!');
        $err = base64_encode($msg);
        header('location: ../admin/dashboard.php?action=edit_admin&id=' . $id . '&err=' . $err);
        exit;
    } elseif (isset($_POST['update_admin'])) {
        $name = addslashes($_POST['name']);
        $email = addslashes($_POST['email']);
        $hotel = addslashes($_POST['hotel']);
        $name = ucwords($name);
        $hotel = ucwords($hotel);

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND id <> ?");
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $email = $user['email'];
            $msg = urlencode('This email "' . $email . '" already exists!');
            $err = base64_encode($msg);
            header('location: ../admin/dashboard.php?action=edit_admin&id=' . $id . '&err=' . $err);
            exit;
        } else {
            // Prepare the statement to save admin
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, hotel_name = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $email, $hotel, $id);

            if ($stmt->execute()) {
                $msg = urlencode('Successful Updated: ' . $name . ' (' . $email . ')');
                $success = base64_encode($msg);
                header('location: ../admin/dashboard.php?success=' . $success);
                exit;
            } else {
                $msg = urlencode('Failed to edit admin.');
                $err = base64_encode($msg);
                header('location: ../admin/dashboard.php?action=edit_admin&id=' . $id . '&err=' . $err);
                exit;
            }
        }
    }
}

if ($action == 'add_room_type') {
    if (empty($_POST['room_type']) || empty($_POST['room_type_code'])) {
        $msg = urlencode('All fields are required!');
        $err = base64_encode($msg);
        header('location: ../admin/home.php?err=' . $err);
        exit;
    } elseif (isset($_POST['add_room_type'])) {
        $name = addslashes($_POST['room_type']);
        $code = addslashes($_POST['room_type_code']);
        $name = ucwords($name);
        $code = ucwords($code);

        $stmt = $conn->prepare("SELECT * FROM room_types WHERE code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $room_code = $result->fetch_assoc();
            $code = $room_code['code'];
            $msg = urlencode('This Room Type Code "' . $code . '" already exists!');
            $err = base64_encode($msg);
            header('location: ../admin/home.php?err=' . $err);
            exit;
        } else {
            // Prepare the statement to save
            $admin_id = $_SESSION['_id'];
            $stmt = $conn->prepare("INSERT INTO room_types (admin_id, name, code) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $admin_id, $name, $code);

            if ($stmt->execute()) {
                $msg = urlencode('Successful added room type: ' . $name . ' (' . $code . ')');
                $success = base64_encode($msg);
                header('location: ../admin/home.php?success=' . $success);
                exit;
            } else {
                $msg = urlencode('Failed to add');
                $err = base64_encode($msg);
                header('location: ../admin/home.php?err=' . $err);
                exit;
            }
        }
    }
}


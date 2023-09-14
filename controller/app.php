<?php
session_start();
include "dbconfig.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'mail/Exception.php';
require 'mail/PHPMailer.php';
require 'mail/SMTP.php';

$mail_from = 'crescentbeatz31@gmail.com';
$app_password = '*************';
$app_name = 'RESERVIO | Hotel Reservation System';

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = $mail_from;
$mail->Password = $app_password;
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom($mail_from, $app_name);


if (!empty($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'logout') {
        session_destroy();
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        header("location: ../admin/index.php");
    }

    // ********* ADMIN *************** //

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

            $hashed_password = password_hash('1234', PASSWORD_DEFAULT);

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
                $admin_id = uniqid(12);
                $stmt = $conn->prepare("INSERT INTO users (unique_id, name, email, password) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $admin_id, $name, $email, $hashed_password);

                $stmt1 = $conn->prepare("INSERT INTO hotels (admin_id, name) VALUES (?, ?)");
                $stmt1->bind_param("ss", $admin_id, $hotel);

                if ($stmt->execute() && $stmt1->execute()) {
                    $msg = urlencode('Successful added: ' . $name . ' (' . $email . ') with password: 1234');
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

            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND unique_id != ?");
            $stmt->bind_param("ss", $email, $id);
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
                $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE unique_id = ?");
                $stmt->bind_param("sss", $name, $email, $id);

                $stmt1 = $conn->prepare("UPDATE hotels SET name = ? WHERE admin_id = ?");
                $stmt1->bind_param("ss", $hotel, $id);

                if ($stmt->execute() && $stmt1->execute()) {
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

    if ($action == 'disable_admin') {
        $id = $_GET['id'];
        $status = $_GET['status'];
        $name = $_GET['name'];
        if (empty($_GET['id'])) {
            $msg = urlencode('Invalid Request!');
            $err = base64_encode($msg);
            header('location: ../admin/dashboard.php?err=' . $err);
            exit;
        } else {

            $stmt = $conn->prepare("UPDATE users SET is_enabled = ? WHERE unique_id = ?");
            $stmt->bind_param("ss", $status, $id);

            if ($stmt->execute()) {
                $msg = urlencode('Successful ' . (($status == 0) ? 'disabled' : 'enabled') . ' admin of ' . $name . '');
                $success = base64_encode($msg);
                header('location: ../admin/dashboard.php?success=' . $success);
                exit;
            } else {
                $msg = urlencode('Failed to ' . (($status == 0) ? 'disabled' : 'enabled') . ' admin.');
                $err = base64_encode($msg);
                header('location: ../admin/dashboard.php?err=' . $err);
                exit;
            }
        }
    }

    if ($action == 'update_hotel_admin') {
        $id = $_SESSION['_unique_id'];
        if (empty($_POST['old_password']) || empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
            $msg = urlencode('All fields are required!');
            $err = base64_encode($msg);
            header('location: ../admin/setting.php?&err=' . $err);
            exit;
        } elseif (isset($_POST['update_user'])) {
            $old_password = addslashes($_POST['old_password']);
            $new_password = addslashes($_POST['new_password']);
            $confirm_password = addslashes($_POST['confirm_password']);

            if ($new_password != $confirm_password) {
                $msg = urlencode('New Password and Confirmation do not match!');
                $err = base64_encode($msg);
                header('location: ../admin/setting.php?&err=' . $err);
                exit;
            }
            $stmt = $conn->prepare("SELECT * FROM users WHERE unique_id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (password_verify($old_password, $user['password'])) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE unique_id = ?");
                $stmt->bind_param("ss", $hashed_password, $id);

                if ($stmt->execute()) {
                    $msg = urlencode('Password Successful Updated!');
                    $success = base64_encode($msg);
                    header('location: ../admin/setting.php?success=' . $success);
                    exit;
                } else {
                    $msg = urlencode('Failed to update password.');
                    $err = base64_encode($msg);
                    header('location: ../admin/setting.php?err=' . $err);
                    exit;
                }
            } else {
                $msg = urlencode('Wrong old password.');
                $err = base64_encode($msg);
                header('location: ../admin/setting.php?err=' . $err);
                exit;
            }
        }
    }

    // ********** HOTEL **************** //

    if ($action == 'publish_hotel') {

        if (empty($_GET['hotel_id'])) {
            $msg = urlencode('Invalid request!');
            $err = base64_encode($msg);
            header('location: ../admin/hotel.php?action=edit_hotel&id=' . $id . '&err=' . $err);
            exit;
        } else {
            $hotel_id = $_GET['hotel_id'];
            $status = base64_decode($_GET['status']);

            $stmt = $conn->prepare("UPDATE hotels SET is_published = ? WHERE admin_id = ?");
            $stmt->bind_param("is", $status, $hotel_id);

            if ($stmt->execute()) {
                $msg = urlencode('Successful published your hotel, ready for bookings!');
                $success = base64_encode($msg);
                header('location: ../admin/hotel.php?success=' . $success);
                exit;
            } else {
                $msg = urlencode('Failed to publish your hotel.');
                $err = base64_encode($msg);
                header('location: ../admin/hotel.php?action=edit_hotel&id=' . $id . '&err=' . $err);
                exit;
            }
        }
    }

    if ($action == 'update_hotel') {
        if (empty($_POST['name'])) {
            $msg = urlencode('Name is required!');
            $err = base64_encode($msg);
            header('location: ../admin/hotel.php?err=' . $err);
            exit;
        } elseif (isset($_POST['update_hotel'])) {
            $name = addslashes($_POST['name']);
            $acc_number = addslashes($_POST['account_number']);
            $bank = addslashes($_POST['bank']);
            $email = addslashes($_POST['email']);
            $description = addslashes($_POST['description']);
            $name = ucwords($name);
            $bank = strtoupper($bank);
            $admin_id = $_SESSION['_unique_id'];
            $hotel_id = $_GET['id'];

            $cover_photo = $_FILES['cover_photo']['name'];
            $cover_photo_tmp = $_FILES['cover_photo']['tmp_name'];
            $cover_photo_ext = pathinfo($cover_photo, PATHINFO_EXTENSION);
            $allowed_extensions = array('jpg', 'jpeg', 'png');

            if (!in_array(strtolower($cover_photo_ext), $allowed_extensions)) {
                $msg = urlencode('Invalid cover photo format. Please use JPG, JPEG, or PNG.');
                $err = base64_encode($msg);
                header('location: ../admin/covers.php?err=' . $err);
            }

            $cover_photo_name = time() . uniqid() . '.' . $cover_photo_ext;
            $cover_photo_path = '../img/' . $cover_photo_name;

            if (!move_uploaded_file($cover_photo_tmp, $cover_photo_path)) {
                $msg = urlencode('Failed to upload cover photo.');
                $err = base64_encode($msg);
                header('location: ../admin/hotel.php?err=' . $err);
            }

            $stmt = $conn->prepare("SELECT name FROM hotels WHERE name = ? AND admin_id != ?");
            $stmt->bind_param("ss", $name, $hotel_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $room_code = $result->fetch_assoc();
                $code = $room_code['code'];
                $msg = urlencode('This Hotel name "' . $name . '" already exists!');
                $err = base64_encode($msg);
                header('location: ../admin/hotel.php?err=' . $err);
                exit;
            } else {
                $stmt = $conn->prepare("UPDATE hotels SET name = ?, acc_number = ?, bank = ?, email = ?, description = ?, photo = ? WHERE admin_id = ?");
                $stmt->bind_param("sssssss", $name, $acc_number, $bank, $email, $description, $cover_photo_name, $hotel_id);

                if ($stmt->execute()) {
                    $msg = urlencode('Successful updated hotel: ' . $name . '');
                    $success = base64_encode($msg);
                    header('location: ../admin/hotel.php?success=' . $success);
                    exit;
                } else {
                    $msg = urlencode('Failed to update hotel.');
                    $err = base64_encode($msg);
                    header('location: ../admin/hotel.php?err=' . $err);
                    exit;
                }
            }
        }
    }

    // ********* ROOM TYPES *********** //

    if ($action == 'add_room_type') {
        if (empty($_POST['room_type'])) {
            $msg = urlencode('All fields are required!');
            $err = base64_encode($msg);
            header('location: ../admin/home.php?err=' . $err);
            exit;
        } elseif (isset($_POST['add_room_type'])) {
            $name = addslashes($_POST['room_type']);
            $name = ucwords($name);
            $admin_id = $_SESSION['_unique_id'];

            // Prepare the statement to save
            $stmt = $conn->prepare("INSERT INTO room_types (admin_id, name) VALUES (?, ?)");
            $stmt->bind_param("ss", $admin_id, $name);

            if ($stmt->execute()) {
                $msg = urlencode('Successful added room type: ' . $name . '');
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

    if ($action == 'update_room_type') {
        if (empty($_POST['room_type'])) {
            $msg = urlencode('All fields are required!');
            $err = base64_encode($msg);
            header('location: ../admin/home.php?err=' . $err);
            exit;
        } elseif (isset($_POST['update_room_type'])) {
            $name = addslashes($_POST['room_type']);
            $name = ucwords($name);
            $admin_id = $_SESSION['_unique_id'];
            $id = base64_decode($_GET['id']);

            // Prepare the statement to save
            $stmt = $conn->prepare("UPDATE room_types SET name = ? WHERE id = ?");
            $stmt->bind_param("ss", $name, $id);

            if ($stmt->execute()) {
                $msg = urlencode('Successful updated room type: ' . $name . '');
                $success = base64_encode($msg);
                header('location: ../admin/home.php?success=' . $success);
                exit;
            } else {
                $msg = urlencode('Failed to update');
                $err = base64_encode($msg);
                header('location: ../admin/home.php?err=' . $err);
                exit;
            }
        }
    }

    if ($action == 'delete_room_type') {
        if (empty($_GET['id'])) {
            $msg = urlencode('Invalid Request!');
            $err = base64_encode($msg);
            header('location: ../admin/home.php?err=' . $err);
            exit;
        } else {
            $type_id = base64_decode($_GET['id']);

            $stmt = $conn->prepare("DELETE FROM room_types WHERE id = ?");
            $stmt->bind_param("s", $type_id);

            if ($stmt->execute()) {
                $msg = urlencode('Successful Deleted a room type!');
                $success = base64_encode($msg);
                header('location: ../admin/home.php?success=' . $success);
                exit;
            } else {
                $msg = urlencode('Failed to deleted a room type!');
                $err = base64_encode($msg);
                header('location: ../admin/home.php?err=' . $err);
                exit;
            }
        }
    }

    // ************ ROOMS **************** //

    if ($action == 'add_room') {
        if (empty($_POST['room_type']) || empty($_POST['room_price'])) {
            $msg = urlencode('All fields are required!');
            $err = base64_encode($msg);
            header('location: ../admin/rooms.php?err=' . $err);
            exit;
        } elseif (isset($_POST['add_room'])) {
            $type = addslashes($_POST['room_type']);
            $bed_type = addslashes($_POST['bed_type']);
            $available = addslashes($_POST['room_available']);
            $price = addslashes($_POST['room_price']);
            $description = addslashes($_POST['room_description']);

            $room_photo = $_FILES['room_photo']['name'];
            $room_photo_tmp = $_FILES['room_photo']['tmp_name'];
            $room_photo_ext = pathinfo($room_photo, PATHINFO_EXTENSION);
            $allowed_extensions = array('jpg', 'jpeg', 'png');

            if (!in_array(strtolower($room_photo_ext), $allowed_extensions)) {
                $msg = urlencode('Invalid room cover photo format. Please use JPG, JPEG, or PNG.');
                $err = base64_encode($msg);
                header('location: ../admin/rooms.php?err=' . $err);
            }

            $room_photo_name = time() . uniqid() . '.' . $room_photo_ext;
            $room_photo_path = '../img/' . $room_photo_name;

            if (!move_uploaded_file($room_photo_tmp, $room_photo_path)) {
                $msg = urlencode('Failed to upload room cover photo.');
                $err = base64_encode($msg);
                header('location: ../admin/rooms.php?err=' . $err);
            }

            $admin_id = $_SESSION['_unique_id'];
            $stmt = $conn->prepare("INSERT INTO rooms (admin_id, type, bed_type, photo, available, price, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $admin_id, $type, $bed_type, $room_photo_name, $available, $price, $description);

            if ($stmt->execute()) {
                $msg = urlencode('Successful added new room');
                $success = base64_encode($msg);
                header('location: ../admin/rooms.php?success=' . $success);
                exit;
            } else {
                $msg = urlencode('Failed to add');
                $err = base64_encode($msg);
                header('location: ../admin/rooms.php?err=' . $err);
                exit;
            }
        }
    }

    if ($action == 'delete_room') {
        if (empty($_GET['id'])) {
            $msg = urlencode('Invalid Request!');
            $err = base64_encode($msg);
            header('location: ../admin/rooms.php?err=' . $err);
            exit;
        } else {
            $room_id = base64_decode($_GET['id']);

            $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
            $stmt->bind_param("s", $room_id);

            if ($stmt->execute()) {
                $msg = urlencode('Successful Deleted a room!');
                $success = base64_encode($msg);
                header('location: ../admin/rooms.php?success=' . $success);
                exit;
            } else {
                $msg = urlencode('Failed to deleted a room!');
                $err = base64_encode($msg);
                header('location: ../admin/rooms.php?err=' . $err);
                exit;
            }
        }
    }

    //  ********** BOOKING ************ /

    if ($action == 'book_room') {
        if (empty($_POST['name']) || empty($_POST['email'])) {
            $msg = urlencode('Fill all required fields!');
            $err = base64_encode($msg);
            header('location: ../booking.php?hotel_id=' . $_GET['hotel_id'] . '&room_id=' . $_GET['room_id'] . '&err=' . $err);
            // exit;
        } elseif (isset($_POST['submit']) && !empty($_GET['hotel_id']) && !empty($_GET['room_id'])) {
            $name = addslashes($_POST['name']);
            $phone = addslashes($_POST['phone']);
            $email = addslashes($_POST['email']);
            $checkinUnformatted = addslashes($_POST['checkin']);
            $checkoutUnformatted = addslashes($_POST['checkout']);
            $adult = addslashes($_POST['adult']);
            $child = addslashes($_POST['child']);
            $request = addslashes($_POST['request']);
            $rooms_total = addslashes($_POST['rooms_total']);
            $name = ucwords($name);
            $hotel_id = $_GET['hotel_id'];
            $room_id = $_GET['room_id'];
            $ref_code = rand(11111111, 99999999);
            $dateTimeCheckin = date_create_from_format('m/d/Y g:i A', $checkinUnformatted);
            $dateTimeCheckout = date_create_from_format('m/d/Y g:i A', $checkoutUnformatted);
            if ($dateTimeCheckin !== false && $dateTimeCheckout !== false) {
                $checkin = $dateTimeCheckin->format('Y-m-d H:i:s');
                $checkinF = new DateTime($checkin);
                $checkinFormatted = $checkinF->format('D j M, Y');
                $checkout = $dateTimeCheckout->format('Y-m-d H:i:s');
                $checkoutF = new DateTime($checkout);
                $checkoutFormatted = $checkoutF->format('D j M, Y');
                $interval = $checkinF->diff($checkoutF);
                $days = $interval->days;
            }
            $paymentDue = date('Y-m-d H:i:s');
            $paymentDueF = new DateTime($paymentDue);
            $payment_due = $paymentDueF->format('D j M, Y');

            $sql = $conn->prepare("SELECT r.price AS room_price, rt.name AS room_name, h.* FROM rooms r
            JOIN room_types rt ON r.type = rt.id
            JOIN hotels h ON r.admin_id = h.admin_id
            WHERE h.admin_id = ? AND r.id = ?");
            $sql->bind_param('ss', $hotel_id, $room_id);
            $sql->execute();
            $result = $sql->get_result();
            $value = $result->fetch_assoc();

            $price = $value['room_price'];
            $total_price = $value['room_price'] * $rooms_total;
            $bank = $value['bank'];
            $account_number = $value['acc_number'];
            $hotel_name = $value['name'];
            $hotel_email = $value['email'];
            $room_name = $value['room_name'];

            $msg = '
            <!DOCTYPE html>
            <html lang="en">
            
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Booking Invoice</title>
            </head>
            
            <body>
                <div style="font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;">
                    <div style="max-width: 650px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                        <div style="background-color: #0f172bff; padding: 3px;">
                            <h2 style="text-align: center; color: #fea116ff;">' . $app_name . '</h2>
                            <h3 style="text-align: center; color: #fff; margin-bottom: 10px;"><strong>' . $value['name'] . ' - Booking Invoice</strong></h3>
                        </div><br>
            
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 45%; padding: 10px;">
                                    <p style="margin-bottom: 5px; color: #000;"><strong>Ref. Number:</strong> #' . $ref_code . '</p>
                                    <p style="margin-bottom: 5px; color: #000;"><strong>Booked Room:</strong> ' . $room_name . '</p>
                                    <p style="margin-bottom: 5px; color: #000;"><strong>Number of Rooms:</strong> ' . $rooms_total . '</p>
                                    <p style="margin-bottom: 5px; color: #000;"><strong>Payment Status:</strong> <span style="background-color: #fea116ff; color: #fff; padding: 3px 7px; border-radius: 5px;">Not Paid</span></p>
                                </td>
                                <td style="border-left: 1px solid #ccc; width: 50%; padding: 10px; padding-left: 3%;">
                                    <p style="margin-bottom: 5px; text-align: left; color: #000;"><span style="font-weight: bold;">Name: </span> ' . $name . '</p>
                                    <p style="margin-bottom: 5px; text-align: left; color: #000;"><span style="font-weight: bold;">Email: </span> ' . $email . '</p>
                                    <p style="margin-bottom: 5px; text-align: left; color: #000;"><span style="font-weight: bold;">Check In: </span> ' . $checkinFormatted . '</p>
                                    <p style="text-align: left; color: #000;"><span style="font-weight: bold;">Check Out: </span> ' . $checkoutFormatted . '</p>
                                </td>
                            </tr>
                        </table>
                        <div style="margin-top: 20px; padding: 10px;">
                            <p style="color: #000; margin: 5px 0; font-style: italic; font-weight: lighter; font-size: large;">Payment Methods:</p>
                            <div style="display:flex;">
                                <img src="https://www.transparentpng.com/download/payment-method/KWM0Hm-payment-method-bitcoin-photo.png" alt="PayPal" style="width: 60%; height: 80px; margin-right: 0px;">
                                <img src="https://seeklogo.com/images/T/tigo-logo-F189442F6A-seeklogo.com.png" alt="TigoPesa" style="width: 35px; height: 25px; margin: 30px 5px;">
                                <img src="http://halopesa.co.tz/static/1d8f7bc9a7487108a67e883871340ddf/e43a9/halopesa-logo.png" alt="HaloPesa" style="width: 87px; height: 32px; margin: 25px 5px;">
                                <img src="https://seeklogo.com/images/M/m-pesa-logo-E658B5D192-seeklogo.com.png" alt="M-Pesa" style="width: 70px; height: 25px; margin: 30px 5px;">
                            </div>
                            <p style="color: #000; font-size: small;">We accept all other payment methods. Please use your <strong>Reference number</strong> for payments.</p>
                        </div>
            
                        <div style="margin-top: 20px; padding: 10px;">
                            <div style="padding: 10px 0; overflow: hidden; border-top: 1px solid grey;">
                                <span style="font-weight: bold; float: left;">Account:</span>
                                <span style="font-weight: normal; float: right;"> ' . $account_number . '  (' . $bank . ')</span>
                            </div>
                            <div style="padding: 10px 0; overflow: hidden; border-top: 1px solid grey;">
                                <span style="font-weight: bold; float: left;">Price Per Room:</span>
                                <span style="font-weight: normal; float: right;">Tshs.' . number_format($price, 2) . '/-</span>
                            </div>
                            <div style="padding: 10px 0; overflow: hidden; border-top: 1px solid grey;">
                                <span style="font-weight: bold; float: left;">Total:</span>
                                <span style="font-weight: normal; float: right;">Tshs.' . number_format($total_price, 2) . '/-</span>
                            </div>
                        </div>
            
                        <p style="text-align: center; color: #000;">Thank you for booking with us! For any help [ <span style="color: #5d9fc5;">' . $hotel_email . '</span> ]</p>
                        <div style="text-align: center;">
                            <a href="http://localhost/workbench/hotel/controller/app.php?action=cancel_booking&ref=' . $ref_code . '" style="color: #5d9fc5; text-decoration: none;">Cancel Booking</a>
                        </div>
                    </div>
                </div>
            </body>
            
            </html>
            ';
            $msg_notify = 'Successful booked a ' . $room_name . ' room with Reference Number: #' . $ref_code . '. Check your email for more details';
            $to = $email;
            $subject = 'Booking Details';
            $message = $msg;
            $mail->isHTML(true);
            $headers = 'From: crescentbeatz31@gmail.com' . "\r\n" .
                'Reply-To: crescentbeatz31@gmail.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion() . "\r\n" .
                'MIME-Version: 1.0' . "\r\n" .
                'Content-Type: text/html; charset=UTF-8' . "\r\n";
            $mail->addAddress($to);
            $mail->Subject = $subject;
            $mail->Body = $message;

            if ($days < 1) {
                $msg = urlencode('Checkout date must be grater than checkin date.');
                $err = base64_encode($msg);
                header('location: ../booking.php?hotel_id=' . $_GET['hotel_id'] . '&room_id=' . $_GET['room_id'] . '&err=' . $err);
                exit;
            } else {
                try {
                    $stmt = $conn->prepare("INSERT INTO bookings (hotel_id, room_id, ref_code, name, phone, email, check_in, check_out, no_of_rooms, adult, child, special_request, payment_due) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssssssssssss", $hotel_id, $room_id, $ref_code, $name, $phone, $email, $checkin, $checkout, $rooms_total, $adult, $child, $request, $paymentDue);

                    $stmt1 = $conn->prepare("UPDATE rooms SET available = available - ? WHERE id = ?");
                    $stmt1->bind_param("ss", $rooms_total, $room_id);


                    if ($stmt->execute() && $stmt1->execute()) {
                        $mail->send();
                        $success = base64_encode(urlencode($msg_notify));
                        header('location: ../booking.php?hotel_id=' . $_GET['hotel_id'] . '&room_id=' . $_GET['room_id'] . '&success=' . $success);
                        exit;
                    } else {
                        $msg = urlencode('Failed to book a ' . $room_name . ' room');
                        $err = base64_encode($msg);
                        header('location: ../booking.php?hotel_id=' . $_GET['hotel_id'] . '&room_id=' . $_GET['room_id'] . '&err=' . $err);
                        exit;
                    }
                } catch (\Throwable $th) {
                    // Email sending failed
                    $msg = urlencode('No Internet! Failed to connect to server. ' . $hotel_name);
                    $err = base64_encode($msg);
                    header('location: ../booking.php?hotel_id=' . $_GET['hotel_id'] . '&room_id=' . $_GET['room_id'] . '&err=' . $err);
                    exit;
                }
            }
        }
    }

    if ($action == 'confirm_payment') {
        if (empty($_GET['id'])) {
            $msg = urlencode('Invalid Request!');
            $err = base64_encode($msg);
            header('location: ../admin/bookings.php?err=' . $err);
            exit;
        } else {
            $booking_id = base64_decode($_GET['id']);
            $ref_code = $_GET['ref'];

            $stmt = $conn->prepare("UPDATE bookings SET is_paid = 1 WHERE id = ?");
            $stmt->bind_param("s", $booking_id);

            if ($stmt->execute()) {
                $msg = urlencode('Successful confirmed a booking payment with ref. no: <b>#' . $ref_code . '</b>');
                $success = base64_encode($msg);
                header('location: ../admin/bookings.php?success=' . $success);
                exit;
            } else {
                $msg = urlencode('Failed to confirm a booking payment with ref. no: <b>#' . $ref_code . '</b>');
                $err = base64_encode($msg);
                header('location: ../admin/bookings.php?err=' . $err);
                exit;
            }
        }
    }

    if ($action == 'cancel_booking') {
        if (empty($_GET['ref'])) {
            $msg = urlencode('Invalid Request!');
            $err = base64_encode($msg);
            if ($_SESSION['_role'] == 0) {
                header('location: ../admin/bookings.php?err=' . $err);
            } else {
                header('location: ../index.php?err=' . $err);
            }
            exit;
        } else {
            $booking_id = base64_decode($_GET['id']);
            $ref_code = $_GET['ref'];

            $stmt = $conn->prepare("DELETE FROM bookings WHERE ref_code = ?");
            $stmt->bind_param("s", $ref_code);

            if ($stmt->execute()) {
                $msg = urlencode('Successful Cancelled a booking with ref. no: <b>#' . $ref_code . '</b>');
                $success = base64_encode($msg);
                if ($_SESSION['_role'] == 0) {
                    header('location: ../admin/bookings.php?success=' . $success);
                } else {
                    header('location: ../index.php?success=' . $success);
                }
                exit;
            } else {
                $msg = urlencode('Failed to cancel a booking with ref. no: <b>#' . $ref_code . '</b>');
                $err = base64_encode($msg);
                if ($_SESSION['_role'] == 0) {
                    header('location: ../admin/bookings.php?err=' . $err);
                } else {
                    header('location: ../index.php?err=' . $err);
                }
                exit;
            }
        }
    }

    if ($action == 'search_room') {
        $hotel_id = $_POST['hotel_id'];
        $userCheckin = $_POST['checkin'];
        $userCheckout = $_POST['checkout'];
        $numberOfAdults = $_POST['adults'];
        $numberOfChildren = $_POST['children'];

        $checkin = date('Y-m-d H:i:s', strtotime($userCheckin));
        $checkout = date('Y-m-d H:i:s', strtotime($userCheckout));

        $sql = "SELECT DISTINCT room_types.id, room_types.name AS type_name, rooms.photo AS photo, rooms.bed_type AS bed_type, rooms.price AS price, rooms.available AS available,
        rooms.description AS description, rooms.id AS room_id
        FROM room_types
        LEFT JOIN rooms ON room_types.id = rooms.type
        LEFT JOIN bookings ON rooms.id = bookings.room_id
        WHERE room_types.id NOT IN (
            SELECT room_types.id
            FROM room_types
            LEFT JOIN rooms ON room_types.id = rooms.type
            LEFT JOIN bookings ON rooms.id = bookings.room_id
            WHERE ('$checkin' < bookings.check_out AND '$checkout' > bookings.check_in)
        ) AND rooms.available > 0";
        //  AND room_types.admin_id = '$hotel_id'
        // AND room_types.max_adults >= $numberOfAdults
        // AND room_types.max_children >= $numberOfChildren";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // while ($row = $result->fetch_assoc()) {
            //     echo "Room Type ID: " . $row['id'] . "<br>";
            //     echo "Room Type Name: " . $row['type_name'] . "<br>";
            // }
            $key = 1;
            while ($value = $result->fetch_assoc()) {
                echo '<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.' . ++$key . 's">
            <div class="room-item shadow rounded overflow-hidden">
                <div class="position-relative">
                    <img class="img-fluid" src="img/' . $value['photo'] . '" alt="">
                    <small class="position-absolute start-0 top-100 translate-middle-y bg-primary text-white rounded py-1 px-3 ms-4">Tshs. ' . number_format($value['price'], 2) . '/Night</small>
                </div>
                <div class="p-4 mt-2">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="mb-0">' . $value['type_name'] . '</h5>
                        <div class="ps-2">
                            <small class="fa fa-star text-primary"></small>
                            <small class="fa fa-star text-primary"></small>
                            <small class="fa fa-star text-primary"></small>
                            <small class="fa fa-star text-primary"></small>
                            <small class="fa fa-star text-primary"></small>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <small class="border-end me-3 pe-3"><i class="fa fa-bed text-primary me-2"></i>' . $value['bed_type'] . ' Room</small>
                        <small class="border-end me-3 pe-3"><i class="fa fa-bath text-primary me-2"></i>Bath</small>
                        <small><i class="fa fa-wifi text-primary me-2"></i>Wifi</small>
                    </div>
                    <div class="d-flex mb-3">
                        <small><i class="fa fa-hotel text-primary me-2"></i>' . ($value['available'] == 0 ? 'No' : $value['available']) . ' Available Rooms</small>
                    </div>
                    <p class="text-body mb-3">' . $value['description'] . '</p>
                    <div class="d-flex justify-content-end">';
                $msg = base64_encode(urldecode('No rooms available to book in ' . $value['type_name'] . ', try another room.'));
                echo '<a class="btn btn-sm btn-dark rounded py-2 px-4" href="' . ($value['available'] == 0 ? 'rooms.php?hotel_id=' . $hotel_id . '&err=' . $msg : 'booking.php?hotel_id=' . $hotel_id . '&room_id=' . $value['room_id']) . '">Book Now</a>
                    </div>
                </div>
            </div>
        </div>';
            }
        } else {
            echo "No available room types for the selected criteria.";
        }
    }
} else {
    header('location: ../index.php');
}

<?php
session_start();
include '../controller/dbconfig.php';
if (!isset($_SESSION['_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("location: index.php");
    exit();
}
if ($_SESSION['_role'] != 1) {
    echo '<script>window.history.back();</script>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Mbeya Hotel - Online Reservation</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="keywords" />
    <meta content="" name="description" />

    <!-- Favicon -->
    <link href="../img/favicon.ico" rel="icon" />

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Libraries Stylesheet -->
    <link href="../lib/animate/animate.min.css" rel="stylesheet" />
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />
    <link href="../lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet" />

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet" />
    <link href="../css/custom.css" rel="stylesheet" />
</head>

<body>

    <!-- ======= Header ======= -->
    <div class="container-fluid fixed-top bg-dark px-0">
        <div class="row gx-0">
            <div class="col-lg-3 bg-dark d-none d-lg-block">
                <a href="home.php" class="navbar-brand w-100 h-100 m-0 p-0 d-flex align-items-center justify-content-center">
                    <h1 class="m-0 text-primary text-uppercase">Reservio</h1>
                </a>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3 p-lg-0">
                    <a href="/" class="navbar-brand d-block d-lg-none">
                        <h1 class="m-0 text-primary text-uppercase">Reservio</h1>
                    </a>
                    <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="hotel.php" class="nav-item nav-link">My Hotel</a>
                            <a href="home.php" class="nav-item nav-link">Room Types</a>
                            <a href="rooms.php" class="nav-item nav-link">Rooms</a>
                            <a href="bookings.php" class="nav-item nav-link">Bookings</a>
                            <a href="setting.php" class="nav-item nav-link active">Settings</a>
                        </div>
                        <a href="../controller/app.php?action=logout" class="btn btn-primary rounded-0 py-4 px-md-5 d-none d-lg-block">LOGOUT<i class="fa fa-arrow-right ms-3"></i></a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <div class="container py-5 mt-5">
        <div class="row g-4">
            <div class="card col-md-6 offset-md-3 room-item shadow rounded overflow-hidden wow fadeInUp">
                <div class="pt-5 pb-5">
                    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                        <h6 class="section-title text-center text-primary text-uppercase">Change Password</h6>
                    </div>
                    <div class="wow fadeInUp px-lg-5" data-wow-delay="0.1s">
                        <form action="../controller/app.php?action=update_hotel_admin" method="POST">
                            <div class="row g-3">
                                <div class="col-12">
                                    <?php
                                    if (isset($_GET['success'])) {
                                        echo '<div class="col-12">
                                        <div class="alert alert-success">' . urldecode(base64_decode($_GET['success'])) . '</div>
                                        </div>';
                                    }
                                    if (isset($_GET['err'])) {
                                        echo '<div class="alert alert-danger">' . urldecode(base64_decode($_GET['err'])) . '</div>';
                                    }

                                    $id = $_SESSION['_unique_id'];
                                    $stmt = $conn->prepare("SELECT * FROM users WHERE unique_id = ?");
                                    $stmt->bind_param("s", $id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        $user = $result->fetch_assoc();
                                    } ?>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password" name="old_password" class="form-control" id="old_password" placeholder="Your Old Password">
                                        <label for="old_password">Old Password</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password" name="new_password" class="form-control" id="new_password" placeholder="Your New Password">
                                        <label for="new_password">New Password</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm Your Password">
                                        <label for="confirm_password">Confirm Password</label>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-between">
                                    <a href="hotel.php" class="btn btn-secondary w-40 py-2">Cancel</a>
                                    <button class="btn btn-primary w-40 py-2" type="submit" name="update_user">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

</body>

</html>
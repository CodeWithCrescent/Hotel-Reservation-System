<?php
session_start();
include "../controller/dbconfig.php";
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
    <div class="container-xxl bg-white p-0">
        <?php
        // $id = $_SESSION['_unique_id'];
        $id = $_GET['id'];
        $unique = $_SESSION['_unique_id'];
        $sql = $conn->prepare("SELECT * FROM hotels WHERE admin_id = ? OR admin_id = ?");
        $sql->bind_param('ss', $id, $unique);
        $sql->execute();
        $arr = $sql->get_result();
        $value = $arr->fetch_assoc();
        ?>

        <!-- ======= Header ======= -->
        <div class="container-fluid fixed-top bg-dark px-0">
            <div class="row gx-0">
                <div class="col-lg-3 bg-dark d-none d-lg-block">
                    <a href="<?php echo $_SESSION['_role'] == 0 ? 'dashboard.php' : 'home.php'; ?>" class="navbar-brand w-100 h-100 m-0 p-0 d-flex align-items-center justify-content-center">
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
                                <?php if ($_SESSION['_role'] == 0) { ?>
                                    <a href="dashboard.php" class="nav-item nav-link">Home</a>
                                <?php } elseif ($_SESSION['_role'] == 1) { ?>
                                    <a href="hotel.php" class="nav-item nav-link">My Hotel</a>
                                    <a href="home.php" class="nav-item nav-link">Room Types</a>
                                    <a href="rooms.php" class="nav-item nav-link">Rooms</a>
                                    <a href="bookings.php" class="nav-item nav-link active">Bookings</a>
                                    <a href="setting.php" class="nav-item nav-link">Settings</a>
                                <?php } ?>
                            </div>
                            <a href="../controller/app.php?action=logout" class="btn btn-primary rounded-0 py-4 px-md-5 d-none d-lg-block">LOGOUT<i class="fa fa-arrow-right ms-3"></i></a>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Header End -->

        <!-- Page Header Start -->
        <div class="container-fluid page-header mb-5 mt-5 p-0" style="background-image: url(../img/carousel-1.jpg);">
            <div class="container-fluid page-header-inner py-5">
                <div class="container text-center pb-5">
                    <h1 class="display-3 text-white mb-3 animated slideInDown"><?php echo $value['name']; ?></h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center text-uppercase">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo $value['name']; ?></a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Bookings</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Room Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase"> List of Bookings</h6>
                </div>
                <div class="row g-4 table-responsive wow fadeInUp" data-wow-delay="0.1s">
                    <?php if (isset($_GET['err'])) {
                        echo '<div class="col-12">
                                    <div class="alert alert-danger wow fadeInUp" data-wow-delay="0.3s">' . urldecode(base64_decode($_GET['err'])) . '</div>
                                </div>';
                    }
                    if (isset($_GET['success'])) {
                        echo '<div class="col-12">
                                        <div class="alert alert-success wow fadeInUp" data-wow-delay="0.3s">' . urldecode(base64_decode($_GET['success'])) . '</div>
                                    </div>';
                    } ?>
                    <table class="table text-nowrap">
                        <?php
                        $row = $conn->prepare("SELECT b.*, r.*, rt.name AS room_type, b.id AS booking_id  FROM rooms r 
                        JOIN bookings b ON r.admin_id = b.hotel_id AND r.id = b.room_id
                        JOIN room_types rt ON rt.id = r.type
                        WHERE b.hotel_id = ? OR b.hotel_id = ? ORDER BY b.id DESC");
                        $row->bind_param("ss", $id, $unique);
                        ?>
                        <thead>
                            <tr>
                                <td>SN</td>
                                <td>Ref. Number</td>
                                <td>Client Name</td>
                                <td>Email</td>
                                <td>Phone</td>
                                <td>Room Type</td>
                                <td>Bed Type</td>
                                <td>No of Rooms</td>
                                <td>Check in</td>
                                <td>Check out</td>
                                <td>Status</td>
                                <?php if ($_SESSION['_role'] == 1) { ?>
                                    <td>Action</td>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($row->execute()) {
                                $row->execute();
                                $result = $row->get_result();

                                foreach ($result as $key => $value) {
                                    echo '
                                    <tr>
                                        <td>' . ++$key . '</td>
                                        <td>' . $value['ref_code'] . '</td>
                                        <td>' . $value['name'] . '</td>
                                        <td>' . $value['email'] . '</td>
                                        <td>' . $value['phone'] . '</td>
                                        <td>' . $value['room_type'] . '</td>
                                        <td>' . $value['bed_type'] . '</td>
                                        <td>' . $value['no_of_rooms'] . '</td>
                                        <td>' . $value['check_in'] . '</td>
                                        <td>' . $value['check_out'] . '</td>
                                        <td>';
                                    if ($value['is_paid'] === 0) {
                                        echo 'Not Paid';
                                    } elseif ($value['is_paid'] == 1) {
                                        echo 'Paid';
                                    }
                                    echo '</td>';
                                    if ($_SESSION['_role'] == 1) {
                                        echo '<td>';
                                        if ($value['is_paid'] === 1) {
                                            echo '<a href="#" class="btn btn-sm btn-outline-primary">Confirm Payment</a>';
                                        } elseif ($value['is_paid'] === 0) {
                                            echo '<a href="../controller/app.php?action=confirm_payment&ref=' . $value['ref_code'] . '&id=' . base64_encode($value['booking_id']) . '" class="btn btn-sm btn-outline-primary">Confirm Payment</a>';
                                        }
                                        echo '<a href="../controller/app.php?action=cancel_booking&ref=' . $value['ref_code'] . '&id=' . base64_encode($value['booking_id']) . '" class="btn btn-sm btn-outline-danger">Cancel</a>
                                        </td>';
                                    }
                                    echo '
                                    </tr>';
                                }
                            } else {
                                echo '
                                    <tr>
                                        <td>No Bookings Found!</td>
                                    </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Room End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/counterup/counterup.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../lib/tempusdominus/js/moment.min.js"></script>
    <script src="../lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="../lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>

</body>

</html>
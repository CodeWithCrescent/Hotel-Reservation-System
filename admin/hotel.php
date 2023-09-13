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
                            <a href="hotel.php" class="nav-item nav-link active">My Hotel</a>
                            <a href="home.php" class="nav-item nav-link">Room Types</a>
                            <a href="rooms.php" class="nav-item nav-link">Rooms</a>
                            <a href="bookings.php" class="nav-item nav-link">Bookings</a>
                            <a href="setting.php" class="nav-item nav-link">Settings</a>
                        </div>
                        <a href="../controller/app.php?action=logout" class="btn btn-primary rounded-0 py-4 px-md-5 d-none d-lg-block">LOGOUT<i class="fa fa-arrow-right ms-3"></i></a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <main class="container py-5 mt-5">
        <?php
        $id = $_SESSION['_unique_id'];
        $sql = $conn->prepare("SELECT * FROM hotels WHERE admin_id = ?");
        $sql->bind_param('s', $id);
        $sql->execute();
        $arr = $sql->get_result();
        $value = $arr->fetch_assoc();
        ?>
        <div class="pagetitle">
            <h1><?php echo $value['name']; ?></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item"><?php echo $value['name']; ?></li>
                    <li class="breadcrumb-item active"><?php echo isset($_GET['action']) == 'edit_hotel' ? 'Edit Hotel' : 'My Hotel' ?></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <?php
                if (isset($_GET['success'])) {
                    echo '<div class="col-12">
                          <div class="alert alert-success">' . urldecode(base64_decode($_GET['success'])) . '</div>
                          </div>';
                } ?>
                <div class="col-lg-12">
                    <div class="card p-4 mb-4 room-item shadow rounded overflow-hidden wow fadeInUp" data-wow-delay="0.1s">
                        <div class="row g-5 align-items-center">
                            <div class="col-lg-4">
                                <div class="room-item shadow rounded overflow-hidden">
                                    <div class="position-relative">
                                        <img class="img-fluid" src="../img/<?php echo $value['photo']; ?>" alt="Hotel Cover Photo">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="d-flex justify-content-between mb-3">
                                    <h5 class="mb-0"><?php echo $value['name']; ?></h5>
                                    <div class="ps-2">
                                        <small class="fa fa-star text-primary"></small>
                                        <small class="fa fa-star text-primary"></small>
                                        <small class="fa fa-star text-primary"></small>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <small class="border-end me-3 pe-3">
                                        <i class="fa fa-briefcase text-primary me-2"></i>
                                        <?php echo $value['acc_number']; ?>
                                    </small>
                                    <small class="border-end me-3 pe-3">
                                        <i class="fa fa-university text-primary me-2"></i>
                                        <?php echo $value['bank']; ?>
                                    </small>
                                    <small>
                                        <i class="fa fa-envelope text-primary me-2"></i>
                                        <?php echo $value['email']; ?>
                                    </small>

                                </div>
                                <p class="text-body mb-3"><?php echo $value['description']; ?></p>
                                <div class="d-flex justify-content-between">
                                    <a class="btn btn-sm btn-secondary rounded py-2 px-4" href="hotel.php?action=edit_hotel&hotel_id=<?php echo $value['admin_id']; ?>">Edit Hotel</a>
                                    <a class="btn btn-sm btn-primary rounded py-2 px-4" href="../controller/app.php?action=publish_hotel&hotel_id=<?php echo $value['admin_id'] . '&status=';
                                                                                                                                                    echo base64_encode($value['is_published'] == 0 ? 1 : 0); ?>"><?php echo $value['is_published'] == 0 ? 'Publish' : 'Deactivate'; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($_GET['action']) && $_GET['action'] == 'edit_hotel' && !empty($_GET['hotel_id'])) { ?>
                        <div class="card p-4 mb-4 room-item shadow rounded overflow-hidden wow fadeInUp" data-wow-delay="0.2s">
                            <div class="card-body">
                                <h5 class="card-title">Edit My Hotel</h5>

                                <!-- Edit Hotel Form -->
                                <form action="../controller/app.php?action=update_hotel&id=<?php echo $_GET['hotel_id']; ?>" method="POST" enctype="multipart/form-data">
                                    <div class="row g-3">
                                        <?php
                                        if (isset($_GET['action']) && $_GET['action'] == 'edit_hotel') {
                                            $hotel_id = $_GET['hotel_id'];
                                            $sql = $conn->prepare("SELECT * FROM hotels WHERE admin_id = ?");
                                            $sql->bind_param('s', $hotel_id);
                                            $sql->execute();
                                            $arr = $sql->get_result();
                                            $value = $arr->fetch_assoc();
                                            if ($value) {
                                                $name = $value['name'];
                                                $acc_number = $value['acc_number'];
                                                $bank = $value['bank'];
                                                $email = $value['email'];
                                                $photo = $value['photo'];
                                                $description = $value['description'];
                                            } else {
                                                $name = '';
                                                $code = '';
                                            }
                                        }
                                        ?>
                                        <?php if (isset($_GET['err'])) {
                                            echo '<div class="col-12">
                                    <div class="alert alert-danger">' . urldecode(base64_decode($_GET['err'])) . '</div>
                                </div>';
                                        } ?>
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" value="<?php echo isset($_GET['action']) == 'edit_hotel' ? $name : '' ?>" name="name" id="floatingName" placeholder="Enter hotel name">
                                                <label for="floatingName">Hotel Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="number" class="form-control" value="<?php echo isset($_GET['action']) == 'edit_hotel' ? $acc_number : '' ?>" name="account_number" id="floatingAccountNumber" placeholder="Enter hotel account number">
                                                <label for="floatingAccountNumber">Account Number</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" value="<?php echo isset($_GET['action']) == 'edit_hotel' ? $bank : '' ?>" name="bank" id="floatingBank" placeholder="Enter hotel account bank name">
                                                <label for="floatingBank">Bank</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="email" class="form-control" value="<?php echo isset($_GET['action']) == 'edit_hotel' ? $email : '' ?>" name="email" id="floatingEmail" placeholder="Enter hotel support email">
                                                <label for="floatingEmail">Hotel Email</label>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-floating">
                                                <textarea name="description" id="floatingTextarea" value="<?php echo isset($_GET['action']) == 'edit_hotel' ? $description : '' ?>" cols="30" rows="3" class="form-control" placeholder="Enter hotel description"></textarea>
                                                <label for="floatingTextarea">Description</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating">
                                                <input type="file" class="form-control" value="<?php echo isset($_GET['action']) == 'hotel' ? $photo : '' ?>" name="cover_photo" id="floatingCover" placeholder="Upload cover photo">
                                                <label for="floatingCover">Cover Photo</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="submit" name="update_hotel" class="btn btn-sm btn-primary rounded py-2 px-4">Update Hotel</button>
                                    </div>
                                </form><!-- Edit Hotel Form -->

                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </section>

    </main><!-- End #main -->

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
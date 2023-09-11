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
        $id = $_SESSION['_unique_id'];
        $sql = $conn->prepare("SELECT u.*, h.name AS hotel_name FROM users u
        JOIN hotels h ON h.admin_id = u.unique_id WHERE unique_id = ?");
        $sql->bind_param('s', $id);
        $sql->execute();
        $arr = $sql->get_result();
        $value = $arr->fetch_assoc();
        ?>

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
                                <a href="rooms.php" class="nav-item nav-link active">Rooms</a>
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


        <!-- Page Header Start -->
        <div class="container-fluid page-header mb-5 mt-5 p-0" style="background-image: url(../img/carousel-1.jpg);">
            <div class="container-fluid page-header-inner py-5">
                <div class="container text-center pb-5">
                    <h1 class="display-3 text-white mb-3 animated slideInDown"><?php echo $value['hotel_name']; ?></h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center text-uppercase">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo $value['hotel_name']; ?></a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Rooms</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Page Header End -->

        <div class="container">
            <div class="card">
                <div class="text-center wow fadeInUp py-3" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase"><?php echo isset($_GET['action']) == 'edit_category' ? 'Edit ' : 'Add New ' ?> Room</h6>
                </div>
                <div class="card-body">

                    <!-- Add Room Form -->
                    <form action="../controller/app.php?action=<?php echo isset($_GET['action']) == 'edit_room' ? 'update_room&id=' . $_GET['id'] : 'add_room' ?>" method="POST" class="row g-3" enctype="multipart/form-data">
                        <?php
                        if (isset($_GET['action']) && $_GET['action'] == 'edit_room') {
                            $room_id = $_GET['id'];
                            $sql = $conn->prepare("SELECT * FROM users WHERE id = ?");
                            $sql->bind_param('s', $room_id);
                            $sql->execute();
                            $arr = $sql->get_result();
                            $value = $arr->fetch_assoc();
                            if ($value) {
                                $room_type = $value['room_type'];
                                $rooms = $value['rooms'];
                            } else {
                                $room_type = '';
                                $rooms = '';
                            }
                        }
                        ?>
                        <?php if (isset($_GET['err'])) {
                            echo '<div class="col-12">
                                    <div class="alert alert-danger">' . urldecode(base64_decode($_GET['err'])) . '</div>
                                </div>';
                        }
                        if (isset($_GET['success'])) {
                            echo '<div class="col-12">
                                        <div class="alert alert-success">' . urldecode(base64_decode($_GET['success'])) . '</div>
                                    </div>';
                        } ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <?php
                                    $sql = $conn->prepare("SELECT * FROM room_types WHERE admin_id = ?");
                                    $sql->bind_param('s', $id);
                                    $sql->execute();
                                    $arr = $sql->get_result();
                                    ?>
                                    <select required class="form-select" id="floatingSelect" name="room_type" aria-label="Room Type">
                                        <option value="" selected hidden>Select room type</option>
                                        <?php
                                        foreach ($arr as $key => $value) {
                                            echo '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <label for="floatingSelect">Room Type</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <select required class="form-select" id="floatingSelect" name="bed_type" aria-label="Room Type" value="<?php echo isset($_GET['action']) == 'edit_room' ? $room_type : '' ?>">
                                        <option value="" selected hidden>Select bed type</option>
                                        <option value="Single">Single</option>
                                        <option value="Double">Double</option>
                                        <option value="Triple">Triple</option>
                                        <option value="Quad">Quad</option>
                                    </select>
                                    <label for="floatingSelect">Bed Type</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input required type="file" accept="image/*" class="form-control" value="<?php echo isset($_GET['action']) == 'edit_room' ? $room : '' ?>" name="room_photo" id="floatingName">
                                    <label for="floatingName">Room Photo Cover</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" name="<?php echo isset($_GET['action']) == 'edit_room' ? 'update' : 'add' ?>_room" class="btn btn-primary w-100 h-75"><?php echo isset($_GET['action']) == 'edit_room' ? 'Update ' : 'Add ' ?>Room</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-floating">
                                    <input required type="number" class="form-control" value="<?php echo isset($_GET['action']) == 'edit_room' ? $room : '' ?>" name="room_available" id="floatingName" placeholder="Enter Rooms Available">
                                    <label for="floatingName">Rooms Available</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input required type="name" class="form-control" value="<?php echo isset($_GET['action']) == 'edit_room' ? $room : '' ?>" name="room_price" id="floatingName" placeholder="Enter Rooms Available">
                                    <label for="floatingName">Rooms Price</label>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-floating">
                                    <textarea name="room_description" id="floatingTextarea" cols="30" rows="3" class="form-control" placeholder="Enter room description"></textarea>
                                    <label for="floatingTextarea">Rooms Description</label>
                                </div>
                            </div>
                        </div>
                    </form><!-- Add Admin Form -->

                </div>
            </div>
        </div>

        <!-- Room Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase"> List of Rooms</h6>
                    <h1 class="mb-5">Explore Our <span class="text-primary text-uppercase">Rooms</span></h1>
                </div>
                <div class="row g-4">
                    <?php
                    $sql = $conn->prepare("SELECT r.*, rt.name AS type_name FROM rooms r
                    LEFT JOIN room_types rt ON rt.id = r.type WHERE r.admin_id = ?");
                    $sql->bind_param('s', $id);
                    $sql->execute();
                    $arr = $sql->get_result();

                    foreach ($arr as $key => $value) {
                        echo '<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.' . ++$key . 's">
                        <div class="room-item shadow rounded overflow-hidden">
                            <div class="position-relative">
                                <img class="img-fluid" src="../img/' . $value['photo'] . '" alt="">
                                <small class="position-absolute start-0 top-100 translate-middle-y bg-primary text-white rounded py-1 px-3 ms-4">Tshs.' . number_format($value['price']) . '/Night</small>
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
                                <p class="text-body mb-3">' . $value['description'] . '</p>
                                <div class="d-flex justify-content-end">'.
                                    //<a class="btn btn-sm btn-outline-dark rounded py-2 px-4" href="app.php?action=edit_room&id=' . $value['id'] . '">Edit</a>
                                    '<a class="btn btn-sm btn-outline-danger rounded py-2 px-4" href="../controller/app.php?action=delete_room&id=' . base64_encode($value['id']) . '">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>';
                    }
                    ?>
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
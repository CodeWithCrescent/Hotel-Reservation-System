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
                            <a href="home.php" class="nav-item nav-link active">Room Types</a>
                            <a href="rooms.php" class="nav-item nav-link">Rooms</a>
                            <a href="bookings.php" class="nav-item nav-link">Bookings</a>
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
        $id = $_SESSION['_id'];
        $sql = $conn->prepare("SELECT u.*, h.name AS hotel_name FROM users u
        JOIN hotels h ON h.admin_id = u.unique_id WHERE u.id = ?");
        $sql->bind_param('s', $id);
        $sql->execute();
        $arr = $sql->get_result();
        $value = $arr->fetch_assoc();
        ?>
        <div class="pagetitle">
            <h1><?php echo $value['hotel_name']; ?></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item"><?php echo $value['hotel_name']; ?></li>
                    <li class="breadcrumb-item active"><?php echo isset($_GET['action']) == 'edit_room_type' ? 'Edit Room Type' : 'Room Types' ?></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo isset($_GET['action']) == 'edit_room_type' ? 'Edit ' : 'Add New ' ?>Room Type</h5>

                            <!-- Add Admin Form -->
                            <form action="../controller/app.php?action=<?php echo isset($_GET['action']) == 'edit_room_type' ? 'update_room_type&id=' . $_GET['id'] : 'add_room_type' ?>" method="POST" class="row g-3">
                                <?php
                                if (isset($_GET['action']) && $_GET['action'] == 'edit_room_type') {
                                    $room_type = base64_decode($_GET['id']);
                                    $sql = $conn->prepare("SELECT * FROM room_types WHERE id = ?");
                                    $sql->bind_param('s', $room_type);
                                    $sql->execute();
                                    $arr = $sql->get_result();
                                    $value = $arr->fetch_assoc();
                                    if ($value) {
                                        $name = $value['name'];
                                        $code = $value['code'];
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
                                }
                                if (isset($_GET['success'])) {
                                    echo '<div class="col-12">
                                        <div class="alert alert-success">' . urldecode(base64_decode($_GET['success'])) . '</div>
                                    </div>';
                                } ?>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" value="<?php echo isset($_GET['action']) == 'edit_room_type' ? $name : '' ?>" name="room_type" id="floatingName" placeholder="Enter Room Type Name">
                                        <label for="floatingName">Room Type Name</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" value="<?php echo isset($_GET['action']) == 'edit_room_type' ? $code : '' ?>" name="room_type_code" id="roomTypeCode" placeholder="Enter Room Type Code">
                                        <label for="roomTypeCode">Room Type Code</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" name="<?php echo isset($_GET['action']) == 'edit_room_type' ? 'update' : 'add' ?>_room_type" class="btn btn-primary w-100 h-100"><?php echo isset($_GET['action']) == 'edit_room_type' ? 'Update ' : 'Add ' ?>Room Type</button>
                                </div>
                            </form><!-- Add Room Type Form -->

                        </div>
                        <div class="card-body table-responsive">
                            <h5 class="card-title">Room Types List</h5>

                            <table class="table table-hover">
                                <?php
                                $id = $_SESSION['_unique_id'];
                                $row = $conn->prepare("SELECT * FROM room_types WHERE admin_id = ? ORDER BY id DESC");
                                $row->bind_param("s", $id);
                                ?>
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Action</th>
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
                                        <td>' . $value['name'] . '</td>
                                        <td>' . $value['code'] . '</td>
                                        <td>
                                            <a href="home.php?action=edit_room_type&id=' . base64_encode($value['id']) . '" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <a href="../controller/app.php?action=delete_room_type&id=' . base64_encode($value['id']) . '" class="btn btn-sm btn-outline-danger">Delete</a>
                                        </td>
                                    </tr>';
                                        }
                                    } else {
                                        echo '
                                    <tr>
                                        <h3>No Room Types Found!</h3>
                                    </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>

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
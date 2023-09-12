<?php
session_start();
include '../controller/dbconfig.php';
if (!isset($_SESSION['_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("location: index.php");
    exit();
}
if ($_SESSION['_role'] == 1) {
    header("location: home.php");
    exit();
} elseif ($_SESSION['_role'] != 0) {
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
                            <!-- Navbar Items -->
                        </div>
                        <a href="../controller/app.php?action=logout" class="btn btn-primary rounded-0 py-4 px-md-5 d-none d-lg-block">LOGOUT<i class="fa fa-arrow-right ms-3"></i></a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <main class="container py-5 mt-5">

        <div class="pagetitle">
            <h1>Admins</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Admins</li>
                    <li class="breadcrumb-item active"><?php echo isset($_GET['action']) == 'edit_admin' ? 'Edit' : 'View' ?></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo isset($_GET['action']) == 'edit_admin' ? 'Edit ' : 'Add New ' ?>Admin</h5>

                            <!-- Add Admin Form -->
                            <form action="../controller/app.php?action=<?php echo isset($_GET['action']) == 'edit_admin' ? 'update_admin&id=' . $_GET['id'] : 'add_admin' ?>" method="POST" class="row g-3">
                                <?php
                                if (isset($_GET['action']) && $_GET['action'] == 'edit_admin') {
                                    $admin_id = $_GET['id'];
                                    $sql = $conn->prepare("SELECT u.*, h.name AS hotel_name FROM users u
                                    JOIN hotels h ON h.admin_id = u.unique_id WHERE u.unique_id = ?");
                                    $sql->bind_param('s', $admin_id);
                                    $sql->execute();
                                    $arr = $sql->get_result();
                                    $value = $arr->fetch_assoc();
                                    if ($value) {
                                        $name = $value['name'];
                                        $email = $value['email'];
                                        $hotel = $value['hotel_name'];
                                    } else {
                                        $name = '';
                                        $email = '';
                                        $hotel = '';
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
                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" value="<?php echo isset($_GET['action']) == 'edit_admin' ? $name : '' ?>" name="name" id="floatingName" placeholder="Enter Admin Name">
                                        <label for="floatingName">Admin Name</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" value="<?php echo isset($_GET['action']) == 'edit_admin' ? $email : '' ?>" name="email" id="floatingEmail" placeholder="Enter Admin Email">
                                        <label for="floatingEmail">Admin Email</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" value="<?php echo isset($_GET['action']) == 'edit_admin' ? $hotel : '' ?>" name="hotel" id="floatingName" placeholder="Enter Hotel Name">
                                        <label for="floatingName">Hotel Name</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" name="<?php echo isset($_GET['action']) == 'edit_admin' ? 'update' : 'add' ?>_admin" class="btn btn-primary w-100 h-100"><?php echo isset($_GET['action']) == 'edit_admin' ? 'Update ' : 'Add ' ?>Admin</button>
                                </div>
                            </form><!-- Add Admin Form -->

                        </div>
                        <div class="card-body table-responsive">
                            <h5 class="card-title">Admin Lists</h5>

                            <table class="table table-hover">
                                <?php
                                $role = 1;
                                $row = $conn->prepare("SELECT u.*,h.name AS hotel_name FROM users u
                                JOIN hotels h ON h.admin_id = u.unique_id WHERE role = ? ORDER BY u.id DESC");
                                $row->bind_param("i", $role);
                                ?>
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Hotel</th>
                                        <th class="text-center">Action</th>
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
                                        <td>' . $value['email'] . '</td>
                                        <td>' . $value['hotel_name'] . '</td>
                                        <td class="text-center">
                                            <a href="dashboard.php?action=edit_admin&id=' . $value['unique_id'] . '" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <a href="../controller/app.php?action=disable_admin&id=' . $value['unique_id'] . '&name='.$value['hotel_name'].'&status='.(($value['is_enabled'] == 1) ? 0 : 1).'" class="btn btn-sm btn-outline-secondary">'.(($value['is_enabled'] == 1) ? 'Disable' : 'Enable').'</a>
                                            <a href="bookings.php?id=' . $value['unique_id'] . '" class="btn btn-sm btn-outline-info">View Bookings</a>
                                        </td>
                                    </tr>';
                                        }
                                    } else {
                                        echo '
                                    <tr>
                                        <h3>No Admins Found!</h3>
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
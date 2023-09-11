<?php
session_start();
include "controller/dbconfig.php";
?>

<!DOCTYPE html>
<html lang="en">

<?php include "includes/head.php"; ?>

<body>
    <div class="container-xxl bg-white p-0">

        <?php include "includes/header.php"; ?>

        <!-- Rooms Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <?php if (isset($_GET['err'])) {
                    echo '<div class="col-12">
                                    <div class="alert alert-danger wow fadeInUp" data-wow-delay="0.3s">' . urldecode(base64_decode($_GET['err'])) . '</div>
                                </div>';
                }
                if (isset($_GET['success'])) {
                    echo '<div class="col-12">
                                        <div class="alert alert-success wow fadeInUp" data-wow-delay="0.3s">' . urldecode(base64_decode($_GET['success'])) . '</div>
                                    </div>';
                } 
                
                $sql = $conn->prepare("SELECT * FROM hotels WHERE is_published = 1 ORDER BY id DESC");
                $sql->execute();
                $arr = $sql->get_result();

                foreach ($arr as $key => $value) {
                    echo '<div class="card p-4 mb-4 room-item shadow rounded overflow-hidden wow fadeInUp" data-wow-delay="0.1s">
                    <div class="row g-5 align-items-center">
                        <div class="col-lg-4">
                            <div class="room-item shadow rounded overflow-hidden">
                                <div class="position-relative">
                                    <img class="img-fluid" src="img/' . $value['photo'] . '" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="mb-0">' . $value['name'] . '</h5>
                                <div class="ps-2">
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <!-- <small class="border-end me-3 pe-3"><i class="fa fa-bed text-primary me-2"></i>3 Bed</small>
                                <small class="border-end me-3 pe-3"><i class="fa fa-bath text-primary me-2"></i>2
                                    Bath</small>
                                <small><i class="fa fa-wifi text-primary me-2"></i>Wifi</small> -->
                            </div>
                            <p class="text-body mb-3">' . $value['description'] . '</p>
                            <div class="d-flex justify-content-end">
                                <a class="btn btn-sm btn-primary rounded py-2 px-4" href="rooms.php?hotel_id=' . $value['admin_id'] . '">View Rooms</a>
                            </div>
                        </div>
                    </div>
                </div>';
                }
                ?>
            </div>
        </div>
        <!-- Hotes End -->


        <?php include "includes/footer.php"; ?>


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <?php include "includes/scripts.php"; ?>

</body>

</html>
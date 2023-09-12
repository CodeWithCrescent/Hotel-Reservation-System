<?php
session_start();
include "controller/dbconfig.php";
?>

<!DOCTYPE html>
<html lang="en">

<?php include "includes/head.php"; ?>

<body>
    <div class="container-xxl bg-white p-0">
        <?php
        $id = $_GET['hotel_id'];
        $sql = $conn->prepare("SELECT * FROM hotels WHERE admin_id = ?");
        $sql->bind_param('s', $id);
        $sql->execute();
        $arr = $sql->get_result();
        $value = $arr->fetch_assoc();
        ?>

        <?php include "includes/header.php"; ?>


        <!-- Page Header Start -->
        <div class="container-fluid page-header mb-5 p-0" style="background-image: url(img/carousel-1.jpg);">
            <div class="container-fluid page-header-inner py-5">
                <div class="container text-center pb-5">
                    <h1 class="display-3 text-white mb-3 animated slideInDown"><?php echo $value['name']; ?></h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center text-uppercase">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo $value['name']; ?></a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Rooms</li>
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
                    <h6 class="section-title text-center text-primary text-uppercase">Our Rooms</h6>
                    <h1 class="mb-5">Explore Our <span class="text-primary text-uppercase">Rooms</span></h1>
                </div>
                <div id="results" class="row g-4">
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
                    if (isset($_GET['hotel_id'])) {
                        $hotel_id = $_GET['hotel_id'];

                        $sql = $conn->prepare("SELECT r.*, t.name AS type_name FROM rooms r
                        LEFT JOIN room_types t ON t.id = r.type WHERE r.admin_id = ?");
                        $sql->bind_param('s', $hotel_id);
                        $sql->execute();
                        $arr = $sql->get_result();

                        foreach ($arr as $key => $value) {
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
                            echo '<a class="btn btn-sm btn-dark rounded py-2 px-4" href="' . ($value['available'] == 0 ? 'rooms.php?hotel_id=' . $hotel_id . '&err=' . $msg : 'booking.php?hotel_id=' . $hotel_id . '&room_id=' . $value['id']) . '">Book Now</a>
                                </div>
                            </div>
                        </div>
                    </div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- Room End -->


        <?php include "includes/footer.php"; ?>


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <?php include "includes/scripts.php"; ?>

    <script>
        $(document).ready(function() {
            // Handle the search button click event
            $("#search-btn").click(function(e) {
                e.preventDefault();
                // Get user input values
                var checkin = $("#checkin").val();
                var checkout = $("#checkout").val();
                var adults = $("#adults").val();
                var children = $("#child").val();
                var hotel_id = '<?php echo $hotel_id; ?>';

                // Send an AJAX request to the server
                $.ajax({
                    url: "controller/app.php?action=search_room", // The URL to your server-side script
                    method: "POST",
                    data: {
                        checkin: checkin,
                        checkout: checkout,
                        adults: adults,
                        children: children,
                        hotel_id: hotel_id
                    },
                    success: function(response) {
                        // Update the results div with the response
                        $("#results").html(response);
                    }
                });
            });
        });
    </script>

</body>

</html>
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
        $hotel_id = $_GET['hotel_id'];
        $room_id = $_GET['room_id'];
        $sql = $conn->prepare("SELECT h.name AS hotel_name, rt.name AS type_name, r.available AS rooms_available, r.photo AS photo FROM rooms r
        JOIN hotels h ON r.admin_id = h.admin_id
        JOIN room_types rt ON rt.id = r.type WHERE r.admin_id = ? AND r.id = ?");
        $sql->bind_param('ss', $hotel_id, $room_id);
        $sql->execute();
        $arr = $sql->get_result();
        $value = $arr->fetch_assoc();

        include "includes/header.php"; ?>


        <!-- Page Header Start -->
        <div class="container-fluid page-header mb-5 p-0" style="background-image: url(img/carousel-1.jpg);">
            <div class="container-fluid page-header-inner py-5">
                <div class="container text-center pb-5">
                    <h1 class="display-3 text-white mb-3 animated slideInDown">Booking</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center text-uppercase">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo $value['hotel_name']; ?></a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page"><?php echo $value['type_name']; ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Page Header End -->


        <!-- Booking Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase">Room Booking</h6>
                    <h1 class="mb-5">Book A <span class="text-primary text-uppercase"><?php echo $value['type_name']; ?></span> ROOM</h1>
                </div>
                <div class="row g-5">
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
                    <div class="col-lg-6">
                        <div class="row g-3">
                            <div class="col-12">
                                <img class="img-fluid rounded wow zoomIn" data-wow-delay="0.1s" src="img/<?php echo $value['photo'] ?>">
                            </div>
                            <!-- <div class="col-6 text-end">
                                <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.1s" src="img/about-1.jpg" style="margin-top: 25%;">
                            </div>
                            <div class="col-6 text-start">
                                <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.3s" src="img/about-2.jpg">
                            </div>
                            <div class="col-6 text-end">
                                <img class="img-fluid rounded w-50 wow zoomIn" data-wow-delay="0.5s" src="img/about-3.jpg">
                            </div>
                            <div class="col-6 text-start">
                                <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.7s" src="img/about-4.jpg">
                            </div> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="wow fadeInUp" data-wow-delay="0.2s">
                            <form action="controller/app.php?action=book_room&hotel_id=<?php echo $hotel_id . '&room_id=' . $room_id; ?>" method="POST">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input required type="text" name="name" class="form-control" id="name" placeholder="Your Name">
                                            <label for="name">Your Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="tel" name="phone" class="form-control" id="phone" placeholder="Your Phone Number">
                                            <label for="phone">Your Phone</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input required type="email" name="email" class="form-control" id="email" placeholder="Your Email">
                                            <label for="email">Your Email</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating date" id="date3" data-target-input="nearest">
                                            <input required type="text" name="checkin" class="form-control datetimepicker-input" id="checkin" placeholder="Check In" data-target="#date3" data-toggle="datetimepicker" />
                                            <label for="checkin">Check In</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating date" id="date4" data-target-input="nearest">
                                            <input required type="text" name="checkout" class="form-control datetimepicker-input" id="checkout" placeholder="Check Out" data-target="#date4" data-toggle="datetimepicker" />
                                            <label for="checkout">Check Out</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select required name="adult" class="form-select" id="select1">
                                                <option value="">Select Adult</option>
                                                <option value="1">1 Adult</option>
                                                <option value="2">2 Adults</option>
                                                <option value="3">3 Adults</option>
                                                <option value="0">None</option>
                                            </select>
                                            <label for="select1">Select Adult</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select required name="child" class="form-select" id="select2">
                                                <option value="">Select Child</option>
                                                <option value="1">1 Child</option>
                                                <option value="2">2 Child</option>
                                                <option value="3">3 Child</option>
                                                <option value="0">None</option>
                                            </select>
                                            <label for="select2">Select Child</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input required type="number" name="rooms_total" class="form-control" min="1" max="<?php echo $value['rooms_available']; ?>" id="rooms_total" placeholder="Number of rooms" />
                                            <label for="rooms_total">Number of Rooms</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea name="request" class="form-control" placeholder="Special Request" id="request" style="height: 100px"></textarea>
                                            <label for="request">Special Request</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100 py-3" name="submit" type="submit">Book Now</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqU2c4zU6S5jfkJcCgmR/5iqd5foF7Vo3+5I6cL5fydjAg5H3KkvKcF5fF6" crossorigin="anonymous"></script>
        <!-- Booking End -->


        <?php include "includes/footer.php"; ?>


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <?php include "includes/scripts.php"; ?>
</body>

</html>
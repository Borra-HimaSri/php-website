<?php 
include 'db.php';



if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>BabyCare - Daycare Website Template</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Google Web Fonts -->
          <link rel="icon" type="image/png" href="img/logo.png">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700&family=Montserrat:wght@200;400;600&display=swap" rel="stylesheet"> 

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="lib/animate/animate.min.css" rel="stylesheet">
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
        <link href="css/mystyle.css" rel="stylesheet">

        

    </head>

    <body>

        <!-- Spinner Start -->
        <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" role="status"></div>
        </div>
        <!-- Spinner End -->
        <!-- Navbar Start -->
<div id="navbar" class="container-fluid border-bottom bg-light wow fadeIn" data-wow-delay="0.1s">
    <div class="container topbar bg-primary d-none d-lg-block py-1" style="border-radius: 0 40px; padding-top: 5px; padding-bottom: 5px;">
        <div class="d-flex justify-content-between">
            <div class="top-info ps-2">
                <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="https://maps.app.goo.gl/5MeZYzAqRGHyQd8g8" class="text-white">
                 D, No 19-17-10/1, Main Rd, Bank Colony, Bhimavaram, Andhra Pradesh 534201</a></small>
                <small class="me-3"> <i class="fas fa-envelope me-2 text-secondary"></i><a href="mailto:rajesh.yerram404@gmail.com" class="text-white">rajesh.yerram404@gmail.com</a></small>

            </div>
            <div class="top-link pe-2">
                <a href="https://www.facebook.com/groups/336890579728950/posts/8878320018919254/" class="btn btn-light btn-sm-square rounded-circle"><i class="fab fa-facebook-f text-secondary"></i></a>
                
                <!--<a href="" class="btn btn-light btn-sm-square rounded-circle"><i class="fab fa-instagram text-secondary"></i></a>-->
                <a href="https://www.youtube.com/@Bannu369" class="btn btn-light btn-sm-square rounded-circle me-0"><i class="fab fa-youtube text-secondary"></i></a>
            </div>
        </div>
    </div>

    <div class="container px-0">
        <nav class="navbar navbar-light navbar-expand-xl py-0" >
            <a href="index.php" class="navbar-brand d-flex align-items-center">
                <img src="img/logo.png" alt="logo" style="height:65px; width:auto; margin-right:2px;">
                <h1 class="text-primary display-6" style="margin-bottom: 0;">Star<span class="text-secondary">kids</span></h1>
            </a>
            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-primary"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="index.php" class="nav-item nav-link active">Home</a>

                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">About</a>
                                    <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                        <a href="about.php" class="dropdown-item">About Smartkids</a>
                                        <a href="about-mission.php" class="dropdown-item">Our Mission and vision</a>
                                    </div>
                                </div>
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Our System</a>
                                    <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                        <a href="system-curriculum.php" class="dropdown-item">Smartkids Curriculum</a>
                                        <a href="system-safety.php" class="dropdown-item">Safety and Security</a>
                                        <a href="system-activities.php" class="dropdown-item">Activities at SmartKids</a>
                                        <a href="system-teachers.php" class="dropdown-item">Our Teachers</a>
                                    </div>
                                </div>
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Our Programs</a>
                                    <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                        <a href="program-playgroup.php" class="dropdown-item">Play group</a>
                                        <a href="program-nursery.php" class="dropdown-item">Nursery</a>
                                        <a href="program-JRKG.php" class="dropdown-item">JR KG</a>
                                        <a href="program-SRKG.php" class="dropdown-item">SR KG</a>
                                    </div>
                                </div>
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Our Parents</a>
                                    <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                        <a href="parents-whysmartkids.php" class="dropdown-item">Why smartkidskids</a>
                                        <a href="parents-parents.php" class="dropdown-item">Parents as Partners</a>
                                        <a href="parents-facilities.php" class="dropdown-item">Facilities</a>
                                        <a href="parents-admission.php" class="dropdown-item">Admission Information</a>
                                    </div>
                                </div>

                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Gallery</a>
                                    <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                        <a href="gallery-photo.php" class="dropdown-item">photo Gallery</a>
                                        
                                        <a href="gallery-event.php" class="dropdown-item">Events</a>   
                                    </div>
                                </div>
                                <a href="contact.php" class="nav-item nav-link">Contact</a>
                            </div>
                            </div>
                <div class="d-flex me-4">
                    <div id="phone-tada" class="d-flex align-items-center justify-content-center">
                        <a href="" class="position-relative wow tada" data-wow-delay=".9s">
                            <i class="fa fa-phone-alt text-primary fa-2x me-4"></i>
                            <div class="position-absolute" style="top: -7px; left: 20px;">
                                <span><i class="fa fa-comment-dots text-secondary"></i></span>
                            </div>
                        </a>
                    </div>
                    <div class="d-flex flex-column pe-3 border-end border-primary">
                        <span class="text-primary" style="white-space: nowrap; font-size: 15px;"> Have any questions?</span>

                        <a href="tel:+91 9949153404"><span class="text-secondary" style="white-space: nowrap; font-size: 15px;">+91 9949153404</span></a>
                    </div>
                </div>
                                    
            </div>
        </nav>
    </div>
</div>
<script src="script.js"></script>
<!-- Navbar End -->


        <!-- Modal Search Start -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex align-items-center">
                        <div class="input-group w-75 mx-auto d-flex">
                            <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                            <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Search End -->

        
        <!-- Page Header Start -->
        <div class="container-fluid py-5 hero-header">
            <div class="text-background"></div> <!-- Persistent background -->
            <div class="container py-5">
                <div class="row g-5 justify-content-center">
                    <div class="col-lg-7 col-md-12 text-center">
                        <h1 class="mb-5 display-1 text-white">Events</h1>
                        <h1 class="mb-5 display-1 text-white"></h1><br>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->


       <!-- Events Start -->
<div class="container-fluid events py-5 bg-light">
    <div class="container py-5">
        <div class="mx-auto text-center wow fadeIn" data-wow-delay="0.1s" style="max-width: 700px;">
            
            <h1 class="mb-5 display-3">Our Upcoming Events</h1>
        </div>
        <div class="row g-5 justify-content-center">
            <?php
            $result = $conn->query("SELECT * FROM images WHERE category = 'gallery-event'");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '
                    <div class="col-md-6 col-lg-6 col-xl-4 wow fadeIn" data-wow-delay="0.1s">
                        <div class="events-item bg-primary rounded">
                            <div class="events-inner position-relative">
                                <div class="events-img overflow-hidden rounded-circle position-relative">
                                    <img src="' . $row['image_path'] . '" class="img-fluid w-100 rounded-circle" alt="Event Image" style="height: 300px; width: 300px; object-fit: cover;">
                                    <div class="event-overlay">
                                        <a href="' . $row['image_path'] . '" data-lightbox="event-' . $row['id'] . '"><i class="fas fa-search-plus text-white fa-2x"></i></a>
                                    </div>
                                </div>
                                <div class="px-4 py-2 bg-secondary text-white text-center events-rate">' . $row['event_date'] . '</div>
                                <div class="d-flex justify-content-between px-4 py-2 bg-secondary">
                                    <small class="text-white"><i class="fas fa-calendar me-1 text-primary"></i> ' . $row['event_time'] . '</small>
                                    <small class="text-white"><i class="fas fa-map-marker-alt me-1 text-primary"></i> ' . $row['event_location'] . '</small>
                                </div>
                            </div>
                            <div class="events-text p-4 border border-primary bg-white border-top-0 rounded-bottom">
                                <a href="#" class="h4">' . $row['event_name'] . '</a>
                                <p class="mb-0 mt-3">' . $row['event_description'] . '</p>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<p class="text-center">No upcoming events found.</p>';
            }
            ?>
        </div>
    </div>
</div>
<!-- Events End -->

<style>
    .events-img img {
        width: 400px;
        height: 300px;
        object-fit: cover;
        border-radius: 50%;
    }
</style>


         <!-- Footer Start -->
         <div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="footer-item">
                            <h2 class="fw-bold mb-3"><span class="text-primary mb-0">Star</span><span class="text-secondary">Kids</span></h2>
                            <p class="mb-4">SmartKids Preschool focuses on early childhood development through play-based learning.It provides a safe and nurturing environment for young learners. Our goal is to prepare children for a bright future with confidence and creativity.</p>
                            
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3"> 
    <div class="footer-item">
        <div class="d-flex flex-column p-4 ps-5 text-dark border border-primary" 
            style="border-radius: 50% 20% / 10% 40%;">
            <p>Mon: 8:40am to 3:30pm</p>
            <p>Tue: 8:40am to 3:30pm</p>
            <p>Wed: 8:40am to 3:30pm</p>
            <p>Thu: 8:40am to 3:30pm</p>
            <p>Fri: 8:30am to 3:30pm</p>
            <p>Sat: 8:45am to 12:30pm</p>
            <p class="mb-0">Sunday: Closed</p>
        </div>
    </div>
</div>


<div class="col-md-6 col-lg-4 col-xl-3">
    <div class="footer-item">
        <h4 class="text-primary mb-4 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius">LOCATION</h4>
        <div class="d-flex flex-column align-items-start">
            <a href="https://maps.app.goo.gl/5MeZYzAqRGHyQd8g8" class="text-body mb-4"><i class="fa fa-map-marker-alt text-primary me-2"></i>  D, No 19-17-10/1, Main Rd, Bank Colony, Bhimavaram, Andhra Pradesh 534201</a>
            <a href="tel:+91 9949153404" class="text-start rounded-0 text-body mb-4"><i class="fa fa-phone-alt text-primary me-2"></i>+91 9949153404</a>
            

            <a href="mailto:rajesh.yerram404@gmail.com" class="text-start rounded-0 text-body mb-4"><i class="fas fa-envelope text-primary me-2"></i> rajesh.yerram404@gmail.com</a>


            <div class="footer-icon d-flex">
                <a class="btn btn-primary btn-sm-square me-3 rounded-circle text-white" href="https://www.facebook.com/groups/336890579728950/posts/8878320018919254/"><i class="fab fa-facebook-f"></i></a>

                <!--<a href="#" class="btn btn-primary btn-sm-square me-3 rounded-circle text-white"><i class="fab fa-instagram"></i></a>-->
                <a href="https://www.youtube.com/@Bannu369" class="btn btn-primary btn-sm-square me-3 rounded-circle text-white"><i class="fab fa-youtube"></i></a>

            </div>
        </div>
    </div>
</div>

          <style>
            .footer-galary-img {
    width: 100%; /* Matches the container width */
    aspect-ratio: 1 / 1; /* Ensures a square shape */
    position: relative;
    overflow: hidden;
    border-radius: 50%; /* Circular shape */
}

.footer-galary-img img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures the image fills the container without distortion */
    border-radius: 50%; /* Matches the rounded container */
}
          </style>          
<div class="col-md-6 col-lg-4 col-xl-3">
    <div class="footer-item">
        <h4 class="text-primary mb-4 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius">OUR GALLERY</h4>
        <div class="row g-3">
            <div class="col-4">
                <div class="footer-galary-img rounded-circle border border-primary">
                    <img src="img/galary-1.png" class="img-fluid rounded-circle" alt="">
                </div>
            </div>
            <div class="col-4">
                <div class="footer-galary-img rounded-circle border border-primary">
                    <img src="img/galary-2.png" class="img-fluid rounded-circle" alt="">
                </div>
            </div>
            <div class="col-4">
                <div class="footer-galary-img rounded-circle border border-primary">
                    <img src="img/galary-5.png" class="img-fluid rounded-circle" alt="">
                </div>
            </div>
            <div class="col-4">
                <div class="footer-galary-img rounded-circle border border-primary">
                    <img src="img/galary-4.png" class="img-fluid rounded-circle" alt="">
                </div>
            </div>
            <div class="col-4">
                <div class="footer-galary-img rounded-circle border border-primary">
                    <img src="img/galary-6.png" class="img-fluid rounded-circle" alt="">
                       </div>
                       </div>
                       <div class="col-4">
                         <div class="footer-galary-img rounded-circle border border-primary">
                          <img src="img/galary-3.png" class="img-fluid rounded-circle" alt="">
                          </div>
                       </div>
                      </div>
                     </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Copyright Start -->
        <div class="container-fluid copyright bg-dark py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <span class="text-light"><i class="fas fa-copyright text-light me-2"></i> <span style="color:  pink;">copyrights.Smartkids.in</span></a>, All right reserved.</span>
                    </div>
                    <div class="col-md-6 my-auto text-center text-md-end text-white">
                        <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                        <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                        <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                        Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copyright End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   

        
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    </body>

</html>
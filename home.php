<?php
   //session start
session_start();
// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['role'])) {
    header('Location: login.php');
    exit();
}
?>
<!-- include calendar -->
<?php
    include 'Calendar.php';
    $calendar = new Calendar();
    //sample code to add event
    //$calendar->add_event('test', '2024-04-23', 1, 'green');
    //$calendar->add_event('multipledays', '2024-04-14', 5);

    require_once "config.php";
    $sql = "SELECT * FROM events "; //change events to database name
    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Check if the query was successful
    if ($result) {
        // Loop through each row in the result set
        while ($row = mysqli_fetch_assoc($result)) {
            // Each event found will go through here
            $eventname = $row['eventname']; 
            if($row['start_date'] == $row['end_date']){  //for single day events
                $eventdate = $row['start_date'];
                $calendar->add_event($eventname, $eventdate, 1, 'green');
            }else{                                       //for multiple day events

                $start_timestamp = strtotime($row['start_date']);
                $end_timestamp = strtotime($row['end_date']);

                $number_of_seconds = $end_timestamp - $start_timestamp;
                $number_of_days = round($number_of_seconds / (60 * 60 * 24));
                $number_of_days++; 
                $eventdate = $row['start_date'];
                $calendar->add_event($eventname, $eventdate, $number_of_days, 'green');
            }
        }
        // Free the result set
        mysqli_free_result($result);
    } else {
        // If the query fails, handle the error
        echo "Error: " . mysqli_error($conn);
    }
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="css/mainstyle.css">
<link rel="stylesheet" href="css/calendar.css">
<title>Barrio Liwanag</title>
<link href="images/logo.png" rel="icon">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <!-- Navigation Bar -->
    <header class="header">
        <h1 class="logo"><a href="#"><img src="images/logo-with-text.png" alt="barrio-liwanag-logo-with-text" width="200" height="75"></a></h1>
        <!-- Hamburger Menu Button -->
        <button class="hamburger-menu" onclick="toggleMenu()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                <path fill="none" d="M0 0h24v24H0V0z"/>
                <path d="M4 18h16c.55 0 1-.45 1-1s-.45-1-1-1H4c-.55 0-1 .45-1 1s.45 1 1 1zm0-5h16c.55 0 1-.45 1-1s-.45-1-1-1H4c-.55 0-1 .45-1 1s.45 1 1 1zM3 7c0 .55.45 1 1 1h16c.55 0 1-.45 1-1s-.45-1-1-1H4c-.55 0-1 .45-1 1z"/>
            </svg>
        </button>
        <!-- Regular Navigation Menu Items -->
        <ul class="main-nav" id="main-nav">
            <li><a href="home.php">Home</a></li>
            <li><a href="users/events-user.php">Events</a></li>
            <li><a href="home.php#contact_us">Contact Us</a></li>
            <li><a href="home.php#about_us">About Us</a></li>
            <li><a href="php/logout.php">Logout</a></li>
        </ul>
    </header>

    <script>
        // Function to toggle visibility of navigation menu items
        function toggleMenu() {
            var menu = document.getElementById('main-nav');
            if (menu.style.display === 'block') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'block';
            }
        }

        // Function to handle window resize event
        window.addEventListener('resize', function() {
            var menu = document.getElementById('main-nav');
            if (window.innerWidth >= 769) {
                menu.style.display = 'flex';
            }
        });
    </script>

    <!-- CSS for positioning the header and the hamburger menu button -->
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px; 
            background-color: #B7BE9E; 
        }

        .hamburger-menu {
            display: block;
            background: none;
            border: none;
            cursor: pointer;
        }

        @media (min-width: 769px) {
            .hamburger-menu {
                display: none;
            }

            .main-nav {
                display: flex;
            }
        }

        @media (max-width: 768px) {
            .main-nav {
                display: none;
                position: absolute;
                top: 60px; 
                right: 20px; 
                background-color: #B7BE9E; 
                border: 1px solid #e9ecef; 
                padding: 10px; 
                border-radius: 5px;
            }

            .main-nav li {
                margin-bottom: 10px; 
            }
        }
    </style>

    <section class="parallax">
        <div class="notification">
            <p>Scroll Down to See the Events for this Month!</p>
        </div>
    </section>

    <!-- Javascript for the notification  -->
    <script>
        const notification = document.querySelector('.notification');
        window.addEventListener('scroll', () => {
            const scrollPosition = window.pageYOffset;
            if (scrollPosition > 0) {
                notification.style.display = 'none';
            } else {
                notification.style.display = 'block';
            }
        });        notification.style.display = 'block';
    </script>

    <!-- CALENDAR SECTION -->
    <section>
        <center><strong><b><h1>Calendar Events</h1></b></strong></center>
        <center>
            <div class="calendardiv">
                <!-- //calendar -->
                <?=$calendar?>
            </div>
        </center>
        
    </section>

    <!-- CONTACT US SECTION-->
    <section id="contact_us" style="background-color: #EDB437;">
        <div class="container" style="padding:50px;" >
            <!-- Left Container -->
            <div class="container-left" style="padding:50px;" >
                <strong style="font-size: 40px;">Contact Us mga Ka-BARRIO!</strong>
                <p style="padding-right: 50px; text-align:justify;">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras convallis turpis id odio convallis commodo. 
                    Maecenas eget tortor leo. Sed elementum, arcu sed dictum iaculis, ante dui commodo enim, 
                    id tincidunt dui nulla non massa. Nunc ac ex quis orci tempus molestie id nec leo. Aliquam erat volutpat. 
                    Pellentesque sed consectetur urna, id consectetur elit. Donec placerat elit nec lobortis fringilla. Morbi
                </p>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
                <br><br>
                <center>
                    <div class="rating-card">
                        <div class="text-wrapper">
                            <p class="text-primary">You can also find us on Facebook!</p>
                            <p class="text-secondary">Give us a rating too!</p>
                        </div>

                        <div class="rating-stars-container">
                            <input value="star-5" name="star" id="star-5" type="radio" />
                            <label for="star-5" class="star-label">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z"
                                pathLength="360"
                                ></path>
                            </svg>
                            </label>
                            <input value="star-4" name="star" id="star-4" type="radio" />
                            <label for="star-4" class="star-label">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z"
                                pathLength="360"
                                ></path>
                            </svg>
                            </label>
                            <input value="star-3" name="star" id="star-3" type="radio" />
                            <label for="star-3" class="star-label">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z"
                                pathLength="360"
                                ></path>
                            </svg>
                            </label>
                            <input value="star-2" name="star" id="star-2" type="radio" />
                            <label for="star-2" class="star-label">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z"
                                pathLength="360"
                                ></path>
                            </svg>
                            </label>
                            <input value="star-1" name="star" id="star-1" type="radio" />
                            <label for="star-1" class="star-label">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z"
                                pathLength="360"
                                ></path>
                            </svg>
                            </label>
                        </div>
                        <div class="socials-container">
                            <a class="social-button" href="https://www.facebook.com/barrioliwanag" target="_blank">
                            <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path
                                d="M512 256C512 114.6 397.4 0 256 0S0 114.6 0 256C0 376 82.7 476.8 194.2 504.5V334.2H141.4V256h52.8V222.3c0-87.1 39.4-127.5 125-127.5c16.2 0 44.2 3.2 55.7 6.4V172c-6-.6-16.5-1-29.6-1c-42 0-58.2 15.9-58.2 57.2V256h83.6l-14.4 78.2H287V510.1C413.8 494.8 512 386.9 512 256h0z"
                                ></path>
                            </svg>
                            </a>
                            <!-- <a class="social-button" href="#">
                            <svg viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
                                <path
                                d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"
                                ></path>
                            </svg>
                            </a>
                            <a class="social-button" href="#">
                            <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path
                                d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"
                                ></path>
                            </svg>
                            </a> -->
                        </div>
                        </div>
                </center>
            </div>
            <!-- Right Container -->
            <div class="container-right">
                <!-- <img src="images/cover-pic-fb.jpg" alt="Barrio Liwanag Cover" width="100%"> -->
                <!-- Google Maps iframe -->
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.8736273277486!2d121.07209801484294!3d14.652674789761855!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c90073d6467d%3A0x548f557077051b71!2sUniversity%20of%20the%20Philippines%20Diliman!5e0!3m2!1sen!2sph!4v1620290541149!5m2!1sen!2sph" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    <center>
                        <br>
                        <strong><h4>Barrio-Liwanag is @ University of the Philippines!</h4></strong>
                    </center>
            </div>
        </div>
    </section>

    <br>

    <!-- ABOUT US SECTION -->
    <section id="about_us">
            <div class="card">
                <div class="card-image"> <img src="images/barrio-liwanag-background-cover.png" alt="Barrio Liwanag Cover"> </div>
                <div class="category">What is Barrio Liwanag?</div>
                <div class="heading"> 
                    <div>
                        <p>
                            We are from 𝘽𝙖𝙧𝙧𝙞𝙤 𝙇𝙞𝙬𝙖𝙣𝙖𝙜, and our mission is to create and provide hope through  awareness and educate the public about issues that cause societal injustice. To also support through different groups that share our aims through raising funds. In the process, we hope to share our "liwanag" with those in need.
                        </p>
                        <div class="author"> This is <span class="name">Barrio Liwanag</div>
                    </div>
                    
                </div>
            </div>

            <div class="card">
                <div class="card-image"> <img src="images/about-us-page.jpg" alt="Barrio Liwanag Cover"> </div>
                <div class="category">When Did It All Start?</div>
                <div class="heading"> 
                    <div>
                        <p>
                            𝘽𝙖𝙧𝙧𝙞𝙤 𝙇𝙞𝙬𝙖𝙣𝙖𝙜, was founded after discovering that our members shared similar views while reading each other's posts from other forums. Despite the fact that our themes differed, we understood via personal interactions that our core ideals were mostly about fairness and justice.
                        </p>
                        <div class="author"> This is <span class="name">Barrio Liwanag</div>
                    </div>
                    
                </div>
            </div>
            <!-- Clouds -->
            <div class='air air1'></div>
            <div class='air air2'></div>
            <div class='air air3'></div>
            <div class='air air4'></div>
    </section>
    
    <!-- Back to Top Button with Up Arrow Icon -->
    <button id="back-to-top" onclick="scrollToTop()">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="24" style="margin-top:6px;">
            <path d="M12 2L0 15h9.5v7h5V15H24L12 2z" />
        </svg>
    </button>

    <footer>
        <center>
        <p>&copy; Copyright Barrio Liwanag. All Rights Reserved 2024</p>
        </center>
    </footer>

        <!-- Smooth Scroll JavaScript -->
    <script>
        // Function to scroll to top
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Show or hide back to top button based on scroll position
        window.onscroll = function() {
            var backButton = document.getElementById('back-to-top');
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                backButton.style.display = 'block';
            } else {
                backButton.style.display = 'none';
            }
        };
    </script>
</body>
</html>
<?php
session_start();
include("./connection/connection.php");

require_once realpath(__DIR__ . '/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__));
$dotenv->load();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $country = $_POST['country'];

    // Insert the contact details into tbl_contact
    $stmt = $con->prepare("INSERT INTO tbl_contact (`name`, `email`, `phone_number`, `subject`, `message`, `country`) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $phone_number, $subject, $message, $country);

    if ($stmt->execute()) {
        // echo "<script>toastr.success('Your message has been sent successfully!');</script>";
        $_SESSION["success"] = "Your message has been sent successfully!";
        header("Location: contact.php");
        exit();
    } else {
        // echo "<script>toastr.error('There was an error sending your message. Please try again later.');</script>";
        $_SESSION["error"] = "There was an error sending your message. Please try again later.";
        header("Location: contact.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Title -->
    <title>Smart Freelancing - Contact Us</title>


    <!-- Favicon -->
    <link rel="icon" href="logos/Brown Elegant Logo Lawyer Logo (6).png" type="image/png" />

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/plugins.css" />
    <link rel="stylesheet" href="assets/css/style.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- JavaScript for Fetching Countries -->
    <script>
    async function loadCountries() {
        try {
            const response = await fetch(
                'https://countryapi.io/api/all?apikey=<?php echo $_ENV["country_api"]; ?>');
            const countries = await response.json();
            const countrySelect = document.getElementById('country');

            // Iterate over the object keys to access country data
            Object.keys(countries).forEach(countryCode => {
                const country = countries[countryCode];
                const option = document.createElement('option');
                option.value = country.name;
                option.textContent = country.name;
                countrySelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error fetching countries:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', loadCountries);
    </script>
    <!-- <script src="https://www.google.com/recaptcha/api.js?render=<?php #echo $_ENV["site_key"]; 
                                                                        ?>"></script> -->

</head>

<body>
    <div class="wrapper d-flex flex-column justify-between">
        <!-- Navbar -->
        <?php include("./partials/navbar.php"); ?>


        <main class="flex-grow-1">
            <!-- Page Header -->
            <section class="py-10 py-lg-15 bg-striped" data-aos="fade-up-sm" data-aos-delay="50">
                <div class="container">
                    <div class="text-center">
                        <h3 class="text-white mb-2">Contact With Us</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-center fs-sm">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Contact</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </section>


            <!-- Contact -->
            <section class="py-15 pt-lg-30">
                <div class="container">
                    <div class="row justify-center">
                        <div class="col-lg-10">
                            <div class="row row-cols-1 row-cols-md-2 gy-20 gx-lg-20">
                                <div class="col" data-aos="fade-up-sm" data-aos-delay="50">
                                    <div class="text-center">
                                        <div
                                            class="icon w-18 h-18 rounded-3 p-4 d-inline-flex align-center justify-center bg-primary-dark text-dark mb-8">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                viewBox="0 0 24 24">
                                                <path stroke="none" d="M0 0h24v24H0z" />
                                                <path d="M18 6v.01M18 13l-3.5-5a4 4 0 1 1 7 0L18 13" />
                                                <path d="M10.5 4.75 9 4 3 7v13l6-3 6 3 6-3v-2M9 4v13m6-2v5" />
                                            </svg>
                                        </div>
                                        <h3 class="fw-medium mb-0">Nazimabad No.4, Karachi, (74600)</h3>
                                    </div>
                                </div>
                                <div class="col" data-aos="fade-up-sm" data-aos-delay="100">
                                    <div class="text-center">
                                        <div
                                            class="icon w-18 h-18 rounded-3 p-4 d-inline-flex align-center justify-center bg-primary-dark text-dark mb-8">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                viewBox="0 0 24 24">
                                                <path stroke="none" d="M0 0h24v24H0z" />
                                                <path
                                                    d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2m10 3a2 2 0 0 1 2 2m-2-6a6 6 0 0 1 6 6" />
                                            </svg>
                                        </div>
                                        <h3 class="fw-medium mb-0">
                                            +92 322 0275616 <br />
                                            +92 301 2256139
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row justify-center mt-18" data-aos="fade-up-sm" data-aos-delay="50">
                        <div class="col-lg-8 col-xl-6">
                            <!-- Contact Form -->
                            <form class="vstack gap-8" id="contact-form" method="post">
                                <div>
                                    <label for="name" class="form-label fs-lg fw-medium mb-4">Your Name*</label>
                                    <div class="input-group with-icon">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                viewBox="0 0 24 24">
                                                <path stroke="none" d="M0 0h24v24H0z" />
                                                <circle cx="12" cy="7" r="4" />
                                                <path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
                                            </svg>
                                        </span>
                                        <input type="text" id="name" name="name" class="form-control rounded-2"
                                            placeholder="What's your name?" required />
                                    </div>
                                </div>
                                <div>
                                    <label for="email" class="form-label fs-lg fw-medium mb-4">Email Address*</label>
                                    <div class="input-group with-icon">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                                <g stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.2">
                                                    <path
                                                        d="M2.25 5.25a1.5 1.5 0 0 1 1.5-1.5h10.5a1.5 1.5 0 0 1 1.5 1.5v7.5a1.5 1.5 0 0 1-1.5 1.5H3.75a1.5 1.5 0 0 1-1.5-1.5v-7.5Z" />
                                                    <path d="M2.25 5.25 9 9.75l6.75-4.5" />
                                                </g>
                                            </svg>
                                        </span>
                                        <input type="email" id="email" name="email" class="form-control rounded-2"
                                            placeholder="Enter Your Email" required />
                                    </div>
                                </div>
                                <div>
                                    <label for="phone" class="form-label fs-lg fw-medium mb-4">Phone Number</label>
                                    <div class="input-group with-icon">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                viewBox="0 0 24 24">
                                                <path stroke="none" d="M0 0h24v24H0z" />
                                                <path
                                                    d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2m10 3a2 2 0 0 1 2 2m-2-6a6 6 0 0 1 6 6" />
                                            </svg>
                                        </span>
                                        <input type="tel" id="phone" name="phone_number" class="form-control rounded-2"
                                            placeholder="Phone Number" />
                                    </div>
                                </div>
                                <div>
                                    <label for="subject" class="form-label fs-lg fw-medium mb-4">Subject*</label>
                                    <div class="input-group with-icon">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                viewBox="0 0 24 24">
                                                <path stroke="none" d="M0 0h24v24H0z" />
                                                <path
                                                    d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2m-4-4v.01M4 12h16m-2-4h.01" />
                                            </svg>
                                        </span>
                                        <input type="text" id="subject" name="subject" class="form-control rounded-2"
                                            placeholder="Subject" required />
                                    </div>
                                </div>
                                <div>
                                    <label for="country" class="form-label fs-lg fw-medium mb-4">Country*</label>
                                    <div class="input-group with-icon">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                viewBox="0 0 24 24">
                                                <path stroke="none" d="M0 0h24v24H0z" />
                                                <path
                                                    d="M12 21v-4m0-4v-5a4 4 0 0 1 4-4h4M12 12a4 4 0 1 0-8 0m0 8v.01m16-4v.01M4 4v.01M4 8v.01M4 16v.01M4 20v.01M8 4v.01M8 20v.01" />
                                            </svg>
                                        </span>
                                        <select id="country" name="country" class="form-control rounded-2" required>
                                            <option value="">Select Your Country</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label for="message" class="form-label fs-lg fw-medium mb-4">Your Message*</label>
                                    <textarea id="message" name="message" class="form-control rounded-2"
                                        placeholder="Write here your detailed message" rows="4" required></textarea>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary-dark">Send Message</button>
                                </div>
                                <div class="status alert mb-0 d-none"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>



        </main>

        <!-- Footer -->
        <?php
        include('./partials/footer.php');
        ?>


    </div>


    <!-- JS -->
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
    // document.getElementById('contact-form').addEventListener('submit', function(event) {
    //     event.preventDefault(); // Prevent form submission

    //     grecaptcha.ready(function() {
    //         grecaptcha.execute('SITE_KEY', {
    //             action: 'submit'
    //         }).then(function(token) {
    //             // Add the token to the form
    //             var recaptchaInput = document.createElement('input');
    //             recaptchaInput.setAttribute('type', 'hidden');
    //             recaptchaInput.setAttribute('name', 'recaptcha_token');
    //             recaptchaInput.setAttribute('value', token);
    //             document.getElementById('contact-form').appendChild(recaptchaInput);

    //             // Now submit the form
    //             document.getElementById('contact-form').submit();
    //         });
    //     });
    // });
    </script>

    <?php

    if (isset($_SESSION["success"])) {
        echo '<script>toastr.success("' . $_SESSION["success"] . '")</script>';
        unset($_SESSION["success"]);
    }

    if (isset($_SESSION["error"])) {
        echo '<script>toastr.error("' . $_SESSION["error"] . '")</script>';
        unset($_SESSION["error"]);
    }

    ?>

</body>

</html>


<?php
// if (!empty($_POST['recaptcha_token'])) {
//     $recaptcha_token = $_POST['recaptcha_token'];


//     $recaptcha_secret = $_ENV["secret_key"]; // Your secret key from the environment variables

//     $recaptcha_response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_token");
//     $recaptcha_response = json_decode($recaptcha_response);

//     if ($recaptcha_response->success && $recaptcha_response->score >= 0.5) {

//     } else {
//         echo "<script>toastr.error('reCAPTCHA verification failed. Please try again.');</script>";
//         exit(); // Stop further execution
//     }
// }


?>
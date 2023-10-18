<!DOCTYPE html>
<?php
require_once('./include/config.php');
include './include/function-update.php';

$Cities = GetCities($link);
date_default_timezone_set("Asia/Colombo");


// // Check if the user is already logged in, if yes then redirect him to welcome page
// if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
//     // header("location:       ");
//     exit;
// }

// Define variables and initialize with empty values
$username = $password = $status = "";
$username_err = $password_err = "";
$error = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter your Email.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT `id`, `email`, `pass`, `acc_type`, `user_status` FROM `user_accounts` WHERE `user_name` = ? OR `email` LIKE ?";

        if ($stmt = mysqli_prepare($link, $sql)) {

            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_email);

            // Set parameters
            $param_email = $username;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $email_address, $hashed_password, $user_type, $is_active);
                    if (mysqli_stmt_fetch($stmt)) {

                        if (password_verify($password, $hashed_password)) {
                            if ($is_active == 1) {
                                // Password is correct, so start a new session
                                session_start();

                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["user_name"] = $email_address;

                                // Login to Index
                                header("location: index?user=$email_address");
                            } else {
                                // Display an error message if password is not valid
                                $error = "Your Account is not Activated. Check Your Email";
                            }
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    } else {
                        $error = "Please contact system Administrator";
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else {
                $error = "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            $error = "Statement Not Working Please Contact System Administrator" . mysqli_error($link);
        }
    } else {
        $error = "Statement Not Working Please Contact System Administrator";
    }
    // Close connection
    mysqli_close($link);
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Add CSS -->
    <link rel="stylesheet" href="./vendor/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/css/styles.css" />

    <!-- Add Icons -->
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css' rel='stylesheet'>

    <script src="https://apis.google.com/js/platform.js" async defer></script>

    <!-- Favicons -->
    <link href="./assets/images/favicon/apple-touch-icon.png" rel="icon">
    <link href="./assets/images/favicon/apple-touch-icon.png" rel="apple-touch-icon">

    <title>Logssin | JLK Tours</title>
</head>

<body class="login">
    <div class="container-fluid login-container">
        <div class="row">
            <div class="col-md-8 text-center content-container">
                <div class="signup-form d-none">
                    <h2 class=" text-center mb-4">Create Account</h2>
                    <i class="fa-brands fa-facebook menu-icon"></i>
                    <i class="fa-brands fa-google-plus-g menu-icon"></i>
                    <i class="fa-brands fa-linkedin-in menu-icon"></i>
                    <p class="mt-4 text-secondary">Or use your email for registration!</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-container">
                                <i class="fas fa-user icon"></i>
                                <input type="text" class="form-control icon-input input-field" placeholder="First Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-container">
                                <i class="fas fa-user icon"></i>
                                <input type="text" class="form-control icon-input input-field" placeholder="Last Name">
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="input-container">
                                <i class="fas fa-envelope icon"></i>
                                <input type="email" class="form-control icon-input input-field" placeholder="Email Address">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <div class="input-container">
                                <i class="fas fa-key icon"></i>
                                <input type="password" class="form-control icon-input input-field" placeholder="Password">
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="input-container">
                                <i class="fas fa-key icon"></i>
                                <input type="password" class="form-control icon-input input-field" placeholder="Confirm Password">
                            </div>
                        </div>
                    </div>

                    <p class="pb-1 border-bottom mt-3 text-secondary text-start">Personal Information</p>
                    <div class="row">
                        <div class="col-12 col-md-6 mt-3">
                            <div class="input-container">
                                <i class="fas fa-phone icon"></i>
                                <input type="text" class="form-control icon-input input-field" placeholder="Phone Number">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-3">
                            <div class="input-container">
                                <i class="fas fa-id-card icon"></i>
                                <input type="text" class="form-control icon-input input-field" placeholder="NIC Number">
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-12 col-md-4 mt-3">
                            <div class="input-container">
                                <i class="fas fa-location-crosshairs icon"></i>
                                <input type="text" class="form-control icon-input input-field" placeholder="Address Line 1">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mt-3">
                            <div class="input-container">
                                <i class="fas fa-location-crosshairs icon"></i>
                                <input type="text" class="form-control icon-input input-field" placeholder="Address Line 2">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mt-3">
                            <div class="input-container">
                                <i class="fas fa-city icon"></i>

                                <select id="city_id" name="city_id" required>
                                    <option value="">Select City</option>
                                    <?php
                                    if (!empty($Cities)) {
                                        foreach ($Cities as $City) {
                                    ?>
                                            <option value="<?= $City['id'] ?>"><?= $City['name_en'] ?> - <?= $City['postcode'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 col-md-6 mt-3">
                            <div class="input-container">
                                <i class="fa-solid fa-person-breastfeeding icon"></i>
                                <select required>
                                    <option value="">Select Account Type</option>
                                    <option value="Student">Student</option>
                                    <option value="Parent">Parent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6 col-md-6 mt-3">
                            <div class="input-container">
                                <i class="fas fa-mars icon"></i>
                                <select required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <button class="btn btn-success sign-button mt-5">SIGN UP</button>
                </div>

                <div class="signin-form d-block">
                    <h2 class=" text-center mb-4">Sign in to JLK Tours</h2>
                    <i class="fa-brands fa-facebook menu-icon"></i>
                    <i class="fa-brands fa-google-plus-g menu-icon"></i>
                    <i class="fa-brands fa-linkedin-in menu-icon"></i>
                    <p class="mt-4 text-secondary">Or use your email account!</p>
                    <form class="" action="" method="post">
                        <div class="row">
                            <div class="col-12">
                                <div class="input-container">
                                    <i class="fas fa-envelope icon"></i>
                                    <input class="form-control icon-input input-field" name="username" id="username" placeholder="Email Address">
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="input-container">
                                    <i class="fas fa-key icon"></i>
                                    <input class="form-control icon-input input-field" type="password" id="password" name="password" placeholder="Password">
                                </div>
                            </div>
                        </div>

                        <div id="script-result" class="">
                            <?php if ($username_err != "") { ?>
                                <div class="alert alert-warning mt-3">
                                    <span style="color: #3c763d;"><?php echo $username_err; ?></span>
                                </div>
                            <?php } ?>
                            <?php if ($password_err != "") { ?>
                                <div class="alert alert-warning mt-3">
                                    <span style="color: #3c763d;"><?php echo $password_err; ?></span>
                                </div>
                            <?php } ?>
                            <?php if ($error != "") { ?>
                                <div class="alert alert-warning mt-3">
                                    <span style="color: #3c763d;"><?php echo $error; ?></span>
                                </div>
                            <?php } ?>

                        </div>

                        <p class="mt-5 forget-text pb-2"><a href="#">Forget your password?</a></p>
                        <button class="btn btn-success sign-button" type="submit">SIGN IN</button>

                        <div class="g-signin2" data-onsuccess="onSignIn"></div>

                </div>
                </form>

            </div>
            <div class="d-none d-md-inline col-md-4 p-5 text-center side-bar-color">
                <div class="inner-content d-block signup-content">
                    <h2 class="text-center">Hello, Friend!</h2>
                    <p class="px-5">Enter your personal Details and start journey with us.</p>
                    <button class="btn btn-success sign-button" id="signup-button">SIGN UP</button>
                </div>

                <div class="inner-content d-none  signin-content">
                    <h2 class="text-center">Welcome Back!</h2>
                    <p class="px-5">To Keep Connected with us please login with your personal info.</p>
                    <button class="btn btn-success sign-button" id="signin-button">SIGN IN</button>
                </div>
            </div>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const signUpBtn = document.querySelector('#signup-button'); // Select the SIGN UP button
            const signInBtn = document.querySelector('#signin-button'); // Select the SIGN UP button
            const row = document.querySelector('.row'); // Select the .row element

            signInBtn.addEventListener('click', function() {
                // Toggle the columns' classes to trigger the animation
                row.classList.toggle('swapped');

                // Toggle the d-none and d-block classes on the forms
                const signupForm = document.querySelector('.signup-form');
                const signinForm = document.querySelector('.signin-form');


                const signupContent = document.querySelector('.signup-content');
                const signinContent = document.querySelector('.signin-content');

                signupForm.classList.toggle('d-block');
                signupForm.classList.toggle('d-none');
                signinForm.classList.toggle('d-block');
                signinForm.classList.toggle('d-none');

                signupContent.classList.toggle('d-block');
                signupContent.classList.toggle('d-none');
                signinContent.classList.toggle('d-block');
                signinContent.classList.toggle('d-none');
            });

            signUpBtn.addEventListener('click', function() {
                // Toggle the columns' classes to trigger the animation
                row.classList.toggle('swapped');

                // Toggle the d-none and d-block classes on the forms
                const signupForm = document.querySelector('.signup-form');
                const signinForm = document.querySelector('.signin-form');


                const signupContent = document.querySelector('.signup-content');
                const signinContent = document.querySelector('.signin-content');

                signupForm.classList.toggle('d-block');
                signupForm.classList.toggle('d-none');
                signinForm.classList.toggle('d-block');
                signinForm.classList.toggle('d-none');

                signupContent.classList.toggle('d-block');
                signupContent.classList.toggle('d-none');
                signinContent.classList.toggle('d-block');
                signinContent.classList.toggle('d-none');
            });
        });
    </script>

    <script>
        gapi.load('auth2', function() {
            gapi.auth2.init({
                client_id: '307650998045-68dm3671rt8em8h2f57ru0tnq7u4ed0v.apps.googleusercontent.com'
            });
        });
    </script>

</body>

</html>
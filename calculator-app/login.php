<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h1 class="center">Welcome to the Calculator App</h1>
        <?php
        require('db.php');
        session_start();

        function generateOTP()
        {
            return rand(100000, 999999);
        }

        // Load variables from .env file
        require 'vendor/autoload.php';
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        // OTP Functionality
        function sendOTP($email, $otp)
        {
            require 'vendor/autoload.php';
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->SMTPDebug = 0; // Use 2 for debugging
            $mail->Host = 'smtp.gmail.com'; // SMTP servers
            $mail->Port = 587; // TCP port to connect to
            $mail->SMTPAuth = true; // SMTP authentication
            $mail->SMTPSecure = 'tls'; // TLS encryption
            $mail->Username = $_ENV['GMAIL_USERNAME'];
            $mail->Password = $_ENV['GMAIL_PASSWORD'];
            $mail->setFrom($_ENV['GMAIL_USERNAME'], 'OTP - CalculatorApp');
            $mail->addAddress($email);
            $mail->Subject = 'Login OTP';
            $mail->Body = 'Your login OTP is: ' . $otp;

            if (!$mail->send()) {
                // Failed to send OTP
                return false;
            } else {
                // OTP sent successfully
                return true;
            }
        }

        if (isset($_POST['submit_email'])) {
            // Step 1: User submitted email, now show the options to select login method
            $email = stripslashes($_REQUEST['email']);
            $email = mysqli_real_escape_string($con, $email);

            $query = "SELECT * FROM `users` WHERE email='$email'";
            $error = "Some error occurred";
            $result = mysqli_query($con, $query) or die($error);
            $rows = mysqli_num_rows($result);

            if ($rows == 1) {
                // Email exists, show the method selection buttons
                $_SESSION['email'] = $email;
                ?>
                <form class="form" method="post" name="login">
                    <h1 class="login-title">Login</h1>
                    <h4 class="center">
                        <?php echo $email; ?>
                    </h4>
                    <p class="center">How would you like to proceed?</p>
                    <input type="hidden" name="email" value="<?php echo $email; ?>">
                    <input type="submit" value="Login with Password" name="login_with_password" class="login-button" />
                    <br><br>
                    <input type="submit" value="Login with OTP" name="login_with_otp" class="login-button" />
                    <p class="link">New user? <a href="register.php">Register</a></p>
                </form>
                <?php
            } else {
                // Email not found, display error message
                echo "<div class='form'>
            <h3>Email not found.</h3>
            <h3>Please register first.</h3><br/>
            <p class='link'>Back to login page? <a href='login.php'>Login</a>.</p>
            <p class='link'>Click here to <a href='register.php'>Register</a>.</p>
            </div>";
            }
        } elseif (isset($_POST['login_with_password'])) {
            // Step 2: User selected login with password, show password input
            ?>
        <form class="form" method="post" name="login">
            <h1 class="login-title">Login with password</h1>
            <h4 class="center">
                <?php echo $_SESSION['email']; ?>
            </h4>
            <input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>">
            <input type="password" class="login-input" name="password" placeholder="Password" autofocus="true" required/>
            <input type="submit" value="Login" name="login_with_password_submit" class="login-button" />
            <p class="link">Back to login page? <a href="login.php">Login</a></p>
            <p class="link">New user? <a href="register.php">Register</a></p>
        </form>
        <?php
        } elseif (isset($_POST['login_with_password_submit'])) {
            // Step 3: User submitted password, verify and proceed to dashboard
            $email = $_POST['email'];
            $password = stripslashes($_REQUEST['password']);
            $password = mysqli_real_escape_string($con, $password);

            $query = "SELECT * FROM `users` WHERE email='$email' AND password='" . md5($password) . "' ";
            $error = "Some error occurred";
            $result = mysqli_query($con, $query) or die($error);
            $rows = mysqli_num_rows($result);

            if ($rows == 1) {
                $_SESSION['email'] = $email;
                header("Location: dashboard.php");
                exit();
            } else {
                echo "<div class='form'>
            <h3>Incorrect Password. Please try again.</h3><br/>
            <p class='link'>Click here to <a href='login.php'>Login</a> again.</p>
            </div>";
            }
        } elseif (isset($_POST['login_with_otp'])) {
            // Step 2: User selected login with OTP, generate OTP and send via email
            $otp = generateOTP();
            if (sendOTP($_SESSION['email'], $otp)) {
                $_SESSION['otp'] = $otp;
                ?>
                <form class="form" method="post" name="login">
                    <h1 class="login-title">Login with OTP</h1>
                    <h4 class="center">
                        <?php echo $_SESSION['email']; ?>
                    </h4>
                    <p>An OTP has been sent to your email. Please enter the OTP below:</p>
                    <input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>">
                    <input type="text" class="login-input" name="otp" placeholder="OTP" autofocus="true" required/>
                    <input type="submit" value="Verify OTP" name="verify_otp" class="login-button" />
                    <p class="link">Back to Login? <a href="login.php">Login</a></p>
                    <p class="link">New user? <a href="register.php">Register</a></p>
                </form>
                <?php
            } else {
                echo "<div class='form'>
            <h3>Failed to send OTP. Please try again later.</h3><br/>
            <p class='link'>Click here to <a href='login.php'>Login</a> again.</p>
            </div>";
            }
        } elseif (isset($_POST['verify_otp'])) {
            // Step 3: User entered OTP, verify and proceed to dashboard
            $user_otp = $_POST['otp'];
            $stored_otp = $_SESSION['otp'];

            if ($user_otp == $stored_otp) {
                header("Location: dashboard.php");
                exit();
            } else {
                echo "<div class='form'>
            <h3>Incorrect OTP. Please try again.</h3><br/>
            <p class='link'>Click here to <a href='login.php'>Login</a> again.</p>
            </div>";
            }
        } else {
            ?>
        <form class="form" method="post" name="login">
            <h1 class="login-title">Login</h1>
            <input type="email" class="login-input" name="email" placeholder="Email" required autofocus="true">
            <input type="submit" value="Continue" name="submit_email" class="login-button" />
            <p class="link">New user? <a href="register.php">Register</a></p>
        </form>
        <?php
        }
        ?>
    </div>
</body>

</html>
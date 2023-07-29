<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h1 class="center">Welcome to the Calculator App</h1>
    <?php
    require('db.php');
    if (isset($_REQUEST['email'])) {
        $name = stripslashes($_REQUEST['name']);
        $name = mysqli_real_escape_string($con, $name);
        $email = stripslashes(($_REQUEST['email']));
        $email = mysqli_real_escape_string($con, $email);
        $password = stripslashes(($_REQUEST['password']));
        $password = mysqli_real_escape_string($con, $password);
        $query = "INSERT into `users`(name, email, password) VALUES ('$name', '$email', '" . md5($password) . "')";
        $result = mysqli_query($con, $query);

        if ($result) {
            echo "<div class='form'>
                <h3>You are registered successfully.</h3><br/>
                <p class='link'>Click here to <a href='login.php'>Login</a></p>
                </div>
                ";
        } else {
            echo "<div class='form'>
                <h3>Required fields are missing.</h3><br/>
                <p class='link'>Click here to <a href='registration.php'>registration</a> again.</p>
                </div>";
        }
    } else {
        ?>
        <form class="form" action="" method="post">
            <h1 class="login-title">Registration</h1>
            <input type="text" class="login-input" name="name" placeholder="Name" required autofocus="true"/>
            <input type="email" class="login-input" name="email" placeholder="Email" required>
            <input type="password" class="login-input" name="password" placeholder="Password" required>
            <input type="submit" name="submit" value="Register" class="login-button">
            <p class="link">Existing user? <a href="login.php">Login</a></p>
        </form>
        <?php
    }
    ?>
    </div>
</body>

</html>
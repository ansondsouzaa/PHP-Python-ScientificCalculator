<?php
include("auth_session.php");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Dashboard - Client area</title>
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <div class="container">
        <p>Hey,
            <?php echo $_SESSION['name']; ?>!
        </p>
        <p>You are now at you dashboard page.</p>
        <p>Go to the <a href="calculator.php">calculator</a> app.</p>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>

</html>
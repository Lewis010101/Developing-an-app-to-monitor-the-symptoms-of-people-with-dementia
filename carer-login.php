<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title>Dementia Monitoring App carer login page</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/carer-login.css">
</head>
<body>
<?php

define('DB_SERVER', 'devweb2021.cis.strath.ac.uk');
define('DB_USERNAME', 'mfb18124');
define('DB_PASSWORD', 'ON7aipoo1ieg');
define('DB_NAME', 'mfb18124');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$username_error = $password_error = "";

session_start();
if (isset($_POST['submit'])) {
    if (empty(trim($_POST["carer-username"]))) {
        $username_error = "Please enter your username.";
    } else {
        $username = stripslashes($_REQUEST['carer-username']);
        $username = mysqli_real_escape_string($link, $username);
    }
    if (empty(trim($_POST["carer-password"]))) {
        $username_error = "Please enter your password.";
    } else {
        $password = stripslashes($_REQUEST['carer-password']);
        $password = mysqli_real_escape_string($link, $password);
    }

    if (empty($username_error) && empty($password_error)) {
        $query = "SELECT * FROM `Carers` WHERE username='$username'and password='" . md5($password) . "'";
        $result = mysqli_query($link, $query) or die(mysqli_error());
        $rows = mysqli_num_rows($result);
        if ($rows == 1) {
            $_SESSION['carer-username'] = $username;
            header("Location: carer-home.php");
            exit();
        }else{
            $username_error = "Username does not exist or password is incorrect.";
        }
    }
}
?>
<main>
    <form class="carer-login" action="" method="post">
        <div class="carer-login-content">
            <div class="carer-login-header">Login into your carer account</div>

            <span class="invalid-feedback"><?php echo $username_error; ?></span>
            <input class="carer-login-input" name="carer-username" type="text" placeholder="Username">

            <span class="invalid-feedback"><?php echo $password_error; ?></span>
            <input class="carer-login-input" name="carer-password" type="password" placeholder="Password">

            <input class="carer-login-button" type="submit" name="submit" value="Login">
            <div class="carer-login-links">
                <a class="carer-login-links" href="carer-signup.php">Sign Up</a>
                <br>
                <br>
                <a class="carer-login-links" href="carer-email-password.php">Forgot your password?</a>
                <br>
                <br>
                <a class="carer-login-links" href="index.php">Login as normal user</a>
            </div>
        </div>
    </form>
</main>
</body>
</html>
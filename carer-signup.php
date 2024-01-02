<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title>Dementia Monitoring App signup page</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/carer-signup.css">
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

$username_error = $email_error= $password_error = $confirm_password_error = "";

if (isset($_REQUEST['carer-username'])){

    if(empty(trim($_POST["carer-username"]))) {
        $username_error = "Please enter a username.";
    } else{
        $username = stripslashes($_REQUEST['carer-username']);
        $username = mysqli_real_escape_string($link,$username);
    }

    if(empty(trim($_POST["carer-email"]))) {
        $email_error = "Please enter an email.";
    } else{
        $email = stripslashes($_REQUEST['carer-email']);
        $email = mysqli_real_escape_string($link,$email);
    }

    if(empty(trim($_POST["carer-password"]))){
        $password_error = "Please enter a password.";
    } elseif(strlen(trim($_POST["carer-password"])) < 8){
        $password_error = "Password must have atleast 8 characters.";
    } else{
        $password = stripslashes($_REQUEST['carer-password']);
        $password = mysqli_real_escape_string($link,$password);
    }

    if(empty(trim($_POST["carer-re-enter-password"]))){
        $confirm_password_error = "Please confirm password.";
    } else{
        $confirm_password = stripslashes($_REQUEST['carer-re-enter-password']);
        $confirm_password = mysqli_real_escape_string($link,$confirm_password);
        if(empty($password_error) && ($password != $confirm_password)){
            $confirm_password_error = "Password did not match.";
        }
    }

    $carer_date = date("Y-m-d");

    if(empty($username_error) && empty($email_error) && empty($password_error) && empty($confirm_password_error)){

        $query = "INSERT into `Carers` (username, password, email, carer_date) VALUES ('$username', '".md5($password)."', '$email', '$carer_date')";
        $result = mysqli_query($link,$query);

        if($result){
            header("location: carer-login.php");
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
}
?>
<main>
    <form class="carer-signup" action="carer-signup.php" method="post">
        <div class="carer-signup-content">
            <div class="carer-signup-header">Create an account.</div>
            <span class="invalid-feedback"><?php echo $username_error; ?></span>
            <input class="carer-signup-input" type="text" name="carer-username" placeholder="Username">

            <span class="invalid-feedback"><?php echo $email_error; ?></span>
            <input class="carer-signup-input" type="text" name="carer-email" placeholder="Email">

            <span class="invalid-feedback"><?php echo $password_error; ?></span>
            <input class="carer-signup-input" type="password" name="carer-password" placeholder="Password">

            <span class="invalid-feedback"><?php echo $confirm_password_error; ?></span>
            <input class="carer-signup-input" type="password" name="carer-re-enter-password" placeholder="Re-Enter-Password">

            <button class="carer-signup-button" type="submit">SignUp</button>
            <div class="carer-signup-links">
                <a class="carer-signup-links" href="carer-login.php">I already have an account</a>
            </div>
        </div>
    </form>
</main>
</body>
</html>
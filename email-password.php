<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title>Dementia Monitoring App reset password page</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/reset-password.css">
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

$password_error = $confirm_password_error = $resetCode_error = $username_error = "";

if(isset($_POST['user_submit'])){
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
        $_SESSION['reset_user'] = $username;

        function generateRandomString($length = 7) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        $reset_code = generateRandomString();
        $_SESSION['reset_code'] = $reset_code;

        $query = "SELECT * FROM Users WHERE username ='".$_SESSION['reset_user']."'";
        $result = $link->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $to = $row['email'];
                $subject = 'Dementia Monitoring App';
                $message = "This is the code to reset your password: ".$_SESSION['reset_code']."";
                mail($to, $subject, $message);
            }
        }
        else{
            $username_error = "Please enter your username";
        }
    }
}

if (isset($_POST['reset_submit'])){
    if(empty(trim($_POST["newPassword"]))){
        $password_error = "Please enter a password.";
    } elseif(strlen(trim($_POST["newPassword"])) < 8){
        $password_error = "Password must have atleast 8 characters.";
    } else{
        $password = stripslashes($_REQUEST['newPassword']);
        $password = mysqli_real_escape_string($link,$password);
    }

    if(empty(trim($_POST["confirmPassword"]))){
        $confirm_password_error = "Please confirm password.";
    } else{
        $confirm_password = stripslashes($_REQUEST['confirmPassword']);
        $confirm_password = mysqli_real_escape_string($link,$confirm_password);
        if(empty($password_error) && ($password != $confirm_password)){
            $confirm_password_error = "Password did not match.";
        }
    }

    if(empty(trim($_POST["resetCode"]))){
        $resetCode_error = "Please enter code.";
    } else{
        $resetCode = stripslashes($_REQUEST['resetCode']);
        $resetCode = mysqli_real_escape_string($link,$resetCode);
        if($_SESSION['reset_code'] != $resetCode){
            $resetCode_error = "Code did not match.";
        }
    }

    if(empty($password_error) && empty($confirm_password_error) && empty($resetCode_error)){

        $query = "UPDATE `Users` SET password = '".md5($password)."' WHERE username = '".$_SESSION['reset_user']."'";
        $result = mysqli_query($link,$query);

        if($result){
            header("location: logout.php");
        }
        else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
}
?>

<main>
    <form class="reset-password" action="email-password.php" method="post" >
        <div class="reset-password-content">
            <div class="reset-password-header">Enter your username</div>
            <span class="invalid-feedback"><?php echo $username_error; ?></span>
            <input class="reset-password-input" type="text" name="username" placeholder="Enter Username">
            <button class="reset-password-button" type="submit" name="user_submit">Send Code</button>
            <br>
            <br>
            <span class="invalid-feedback"><?php echo $password_error; ?></span>
            <input class="reset-password-input" type="password" name="newPassword" placeholder="New Password">
            <span class="invalid-feedback"><?php echo $confirm_password_error; ?></span>
            <input class="reset-password-input" type="password" name="confirmPassword" placeholder="Confirm Password">
            <span class="invalid-feedback"><?php echo $resetCode_error; ?></span>
            <input class="reset-password-input" type="text" name="resetCode" placeholder="Enter Code">
            <button class="reset-password-button" type="submit" name="reset_submit">Confirm</button>
            <div class="reset-password-links">
                <a class="reset-password-links" href="index.php">Cancel</a>
            </div>
        </div>
    </form>
</main>
</body>
</html>
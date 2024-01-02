<?php
session_start();
if(!isset($_SESSION["carer-username"])){
    header("Location: carer-login.php");
    exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title>Dementia Monitoring App carer home page</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/carer-home.css">
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

if(isset($_POST['Search'])){
    if (isset($_POST['user_code'])) {
        $user_code = stripslashes($_REQUEST['user_code']);
        $user_code = mysqli_real_escape_string($link,$user_code);
        $_SESSION['session_user_code'] = $user_code;
    }
}
else{
    $_SESSION['session_user_code'] = "";
}

$query = "SELECT * FROM Users WHERE user_code ='".$_SESSION['session_user_code']."'";
$result = $link->query($query);

if ($result->num_rows > 0){
    while ($row = $result->fetch_assoc()){
        $post_username = $row["username"];
        $_SESSION['post_username'] = $post_username;
        $post_firstname = $row["firstname"];
        $_SESSION['post_firstname'] = $post_firstname;
        $post_surname = $row["surname"];
        $_SESSION['post_surname'] = $post_surname;
    }
}
else{
    $_SESSION['post_username'] = "";
    $_SESSION['post_firstname'] = "";
    $_SESSION['post_surname'] = "";
}

if(isset($_POST['Search'])){
    if (isset($_POST['current_date'])) {
        $date = $_POST['current_date'];
        $_SESSION['carer_session_date'] = $date;
        $_SESSION['post_statement'] = $_SESSION['post_firstname']." ".$_SESSION['post_surname']."'s post from ".$_SESSION['carer_session_date']."";
    }
}
else{
    $_SESSION['carer_session_date'] = date('Y-m-d');
    $_SESSION['post_statement'] = "";
}

$query = "SELECT * FROM Posts WHERE username ='".$_SESSION['post_username']."' AND post_date = '".$_SESSION['carer_session_date']."'";
$result = $link->query($query);

if ($result->num_rows > 0){
    while ($row = $result->fetch_assoc()){
        $symptom = $row["symptom"];
        $medication = $row["medication"];
        $day_comment = $row["day_comment"];
    }
}
else{
    $symptom = "No symptoms recorded for this day.";
    $medication = "No medications recorded for this day.";
    $day_comment = "No comments recorded for this day.";
}
?>
<main>
    <form class="carer-home" action="" method="post" >
        <div class="carer-home-content">
            <div class="carer-home-header">Enter User Code</div>
            <input class="carer-home-input" type="text" name="user_code" placeholder="User Code">
            <input class="carer-home-input" type="date" name="current_date" value="<?php echo $_SESSION['carer_session_date']; ?>" />
            <button class="carer-home-button" type="submit" name="Search">Search</button>
            <p class="carer-home-header"> <?php echo "".$_SESSION['post_statement'].""?></p>
            <fieldset>
                <legend>Daily Symptoms</legend>
                <p><?php echo "$symptom"; ?></p>
            </fieldset>
            <br>
            <fieldset>
                <legend>Daily Medication</legend>
                <p><?php echo "$medication"; ?></p>
            </fieldset>
            <br>
            <fieldset>
                <legend>How was their day?</legend>
                <p><?php echo "$day_comment"; ?></p>
            </fieldset>
            <div class="carer-home-links">
                <a class="carer-home-links" href="carer-logout.php">sign out</a>
            </div>
        </div>
    </form>
</main>
</body>
</html>
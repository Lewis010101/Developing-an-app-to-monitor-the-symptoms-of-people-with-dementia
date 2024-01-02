<?php
session_start();
if(!isset($_SESSION["username"])){
    header("Location: index.php");
    exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title>Dementia Monitoring App profile page</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/profile.css">
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

$query = "SELECT * FROM Users WHERE username ='".$_SESSION['username']."'";
$result = $link->query($query);

if ($result->num_rows > 0){
    while ($row = $result->fetch_assoc()){
        $user_code = $row["user_code"];
        $firstname = $row["firstname"];
        $surname = $row["surname"];
        $email = $row["email"];
        $dob = $row["dob"];
    }
}
$today = date("Y-m-d");
$diff = date_diff(date_create($dob), date_create($today));
?>
<main>
    <form class="profile" method="post">
        <div class="profile-topnav">
            <a href="home.php">Back</a>
        </div>
        <div class="profile-content">
            <h1><?php echo $_SESSION['username']?></h1>
            <br>
            <fieldset>
                <legend>User information</legend>
                firstname: <?php echo $firstname?>
                <br>
                surname: <?php echo $surname?>
                <br>
                age: <?php echo $diff->format('%y')?>
                <br>
                email: <?php echo $email?>
            </fieldset>
            <br>
            <fieldset>
                <legend>User Code</legend>
                <?php echo $user_code; ?>
            </fieldset>
        </div>
    </form>
</main>
</body>
</html>
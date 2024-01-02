<?php
session_start();
if(!isset($_SESSION["username"])){
    header("Location: index.php");
    exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title>Dementia Monitoring App home page</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/home.css">
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

if(isset($_POST['date_submit'])){
    if (isset($_POST['current_date'])) {
        $date = $_POST['current_date'];
        $_SESSION['session_date'] = $date;
    }
}
else{
    $_SESSION['session_date'] = date('Y-m-d');
}

$query = "SELECT * FROM Posts WHERE username ='".$_SESSION['username']."' AND post_date = '".$_SESSION['session_date']."'";
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
    <form class="home" action="home.php" method="post">
        <div class="home-topnav">
            <a href="index.php">Back</a>
        </div>
        <div class="home-content">
            <h1>
                <input class="home-input" type="date" name="current_date" value="<?php echo $_SESSION['session_date']; ?>" />
                <button class="home-button" type="submit" name="date_submit">Set Date</button>
            </h1>
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
                <legend>How was your day?</legend>
                <p><?php echo "$day_comment"; ?></p>
            </fieldset>
        </div>
        <div class="home-navbar">
            <a href="logout.php">Sign Out</a>
            <a href="add.php">Add Post</a>
            <a href="profile.php">My Profile</a>
        </div>
    </form>
</main>
</body>
</html>
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


$query = "SELECT email FROM Users WHERE notifications = '1'";
$result = $link->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $to = $row['email'];
        $subject = 'Dementia Monitoring App Notification';
        $message = 'Hi, remember to login and make a post about your day.';
        mail($to, $subject, $message);
    }
}
?>

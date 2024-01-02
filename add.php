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
    <title>Dementia Monitoring App add page</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/add.css">
</head>
<script>
    var expanded = false;

    function showSymptomCheckboxes() {
        var checkboxes = document.getElementById("symptom-checkboxes");
        if (!expanded) {
            checkboxes.style.display = "block";
            expanded = true;
        } else {
            checkboxes.style.display = "none";
            expanded = false;
        }
    }
</script>
<script>
    var expanded = false;

    function showMedicationCheckboxes() {
        var checkboxes = document.getElementById("medication-checkboxes");
        if (!expanded) {
            checkboxes.style.display = "block";
            expanded = true;
        } else {
            checkboxes.style.display = "none";
            expanded = false;
        }
    }
</script>
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

$symptom_error = $medication_error = $day_rating_error = $day_comment_error = "";

#symptom checkboxes
if(isset($_POST['headache'])){
    $headache = "headache";
}
else{
    $headache = "";
}
if(isset($_POST['memory_loss'])){
    $memory_loss = "memory loss";
}
else{
    $memory_loss = "";
}
if(isset($_POST['confusion'])){
    $confusion = "confusion";
}
else{
    $confusion = "";
}
if(isset($_POST['disorientation'])){
    $disorientation = "disorientation";
}
else{
    $disorientation = "";
}
if(isset($_POST['changes_in_personality'])){
    $changes_in_personality = "changes in personality";
}
else{
    $changes_in_personality = "";
}
if(isset($_POST['poor_judgement'])){
    $poor_judgement = "poor judgement";
}
else{
    $poor_judgement = "";
}

#medication checkboxes
if(isset($_POST['donepezil'])){
    $donepezil = "donepezil";
}
else{
    $donepezil = "";
}
if(isset($_POST['Memantine'])){
    $Memantine = "Memantine";
}
else{
    $Memantine = "";
}
if(isset($_POST['rivastigmine'])){
    $rivastigmine = "rivastigmine";
}
else{
    $rivastigmine = "";
}
if(isset($_POST['galantamine'])){
    $galantamine = "galantamine";
}
else{
    $galantamine = "";
}
if(isset($_POST['risperidone'])){
    $risperidone = "risperidone";
}
else{
    $risperidone = "";
}
if(isset($_POST['haloperidol'])){
    $haloperidol = "haloperidol";
}
else{
    $haloperidol = "";
}


if (isset($_REQUEST['symptom'])){

    if(empty(trim($_POST["symptom"])) AND empty($_POST['headache']) AND empty($_POST['memory_loss']) AND empty($_POST['confusion']) AND empty($_POST['disorientation']) AND empty($_POST['changes_in_personality']) AND empty($_POST['poor_judgement'])) {
        $symptom_error = "Please enter a symptom or state that there were none.";
    } else{
        $symptom = stripslashes($_REQUEST['symptom']);
        $symptom = $headache ." ". $memory_loss ." ". $confusion ." ". $poor_judgement ." ". $disorientation ." ". $changes_in_personality ." ". $symptom;
        $symptom = mysqli_real_escape_string($link,$symptom);
    }

    if(empty(trim($_POST["medication"])) AND empty($_POST['donepezil']) AND empty($_POST['Memantine']) AND empty($_POST['rivastigmine']) AND empty($_POST['galantamine']) AND empty($_POST['risperidone']) AND empty($_POST['haloperidol'])) {
        $medication_error = "Please enter a medication or state that there were none taken.";
    } else{
        $medication = stripslashes($_REQUEST['medication']);
        $medication = $donepezil ." ". $Memantine ." ". $rivastigmine ." ". $galantamine ." ". $risperidone ." ". $haloperidol ." ". $medication;
        $medication = mysqli_real_escape_string($link,$medication);
    }

    if(empty(trim($_POST["day_comment"]))) {
        $day_comment_error = "Please enter some info about your day.";
    } else{
        $day_comment = stripslashes($_REQUEST['day_comment']);
        $day_comment = mysqli_real_escape_string($link,$day_comment);
    }

    $post_date = date("Y-m-d");

    $username = $_SESSION['username'];

    if(empty($symptom_error) && empty($medication_error) && empty($day_comment_error)){

        $select = mysqli_query($link, "SELECT * FROM Posts WHERE username ='".$_SESSION['username']."' AND post_date = '".$_SESSION['session_date']."'");
        if(mysqli_num_rows($select)) {
            $update_query = "UPDATE `Posts` SET symptom = '$symptom', medication = '$medication', day_comment = '$day_comment', username = '$username', post_date = '$post_date' WHERE username = '".$_SESSION['username']."' AND post_date = '".$_SESSION['session_date']."'";
            $update_result = mysqli_query($link,$update_query);
            if($update_result){
                header("location: home.php");
            }
            else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        else{
            $query = "INSERT into `Posts` (symptom, medication, day_comment, username, post_date) VALUES ('$symptom', '$medication', '$day_comment', '$username', '$post_date')";
            $result = mysqli_query($link,$query);

            if($result){
                header("location: home.php");
            }
            else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }
}
?>
<main>
    <form class="add" action="add.php" method="post">
        <div class="add-topnav">
            <a href="home.php">Back</a>
        </div>
        <div class="add-content">
            <div>
                <p class="add-header">Symptoms:</p>
                <div class="multiselect">
                    <div class="selectBox" onclick="showSymptomCheckboxes()">
                        <select>
                            <option>Select Symptoms</option>
                        </select>
                        <div class="overSelect"></div>
                    </div>
                    <div id="symptom-checkboxes">
                        <input type="checkbox" id="headache" name="headache">
                        <label for="headache">headache</label>
                        <input type="checkbox" id="memory loss" name="memory_loss">
                        <label for="memory loss">memory loss</label>
                        <input type="checkbox" id="confusion" name="confusion">
                        <label for="confusion">confusion</label>
                        <input type="checkbox" id="disorientation" name="disorientation">
                        <label for="disorientation">disorientation</label>
                        <input type="checkbox" id="changes_in_personality" name="changes_in_personality">
                        <label for="changes_in_personality">changes in personality</label>
                        <input type="checkbox" id="poor_judgement" name="poor_judgement">
                        <label for="poor_judgement">poor judgement</label>
                    </div>
                </div>

                <input class="add-input" type = "text" name = "symptom" placeholder="Other Symptoms">
                <span class="invalid-feedback"><?php echo $symptom_error; ?></span>

            </div>
            <div>
                <p class="add-header">Medication:</p>
                <div class="multiselect">
                    <div class="selectBox" onclick="showMedicationCheckboxes()">
                        <select>
                            <option>Select Medication</option>
                        </select>
                        <div class="overSelect"></div>
                    </div>
                    <div id="medication-checkboxes">
                        <input type="checkbox" id="donepezil" name="donepezil">
                        <label for="donepezil">donepezil</label>
                        <input type="checkbox" id="Memantine" name="Memantine">
                        <label for="Memantine">Memantine</label>
                        <input type="checkbox" id="rivastigmine" name="rivastigmine">
                        <label for="rivastigmine">rivastigmine</label>
                        <input type="checkbox" id="galantamine" name="galantamine">
                        <label for="galantamine">galantamine</label>
                        <input type="checkbox" id="risperidone" name="risperidone">
                        <label for="risperidone">risperidone</label>
                        <input type="checkbox" id="haloperidol" name="haloperidol">
                        <label for="haloperidol">haloperidol</label>
                    </div>
                </div>

                <input class="add-input" type = "text"  name = "medication" placeholder="Other Medication">
                <span class="invalid-feedback"><?php echo $medication_error; ?></span>

            </div>
            <div>
                <p class="add-header">How was your day?</p>
                <input class="add-input" type = "text"  name = "day_comment" placeholder="How was your day?">
                <span class="invalid-feedback"><?php echo $day_comment_error; ?></span>
            </div>
            <input class="add-button" type="submit" value="Submit"">
        </div>
    </form>
</main>
</body>
</html>
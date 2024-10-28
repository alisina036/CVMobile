<?php
require("connection.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $AppName = mysqli_real_escape_string($conn, $_POST['appName']);
    $AppPictureUrl = mysqli_real_escape_string($conn, $_POST['appPicture']);

    // Voeg gegevens in de database toe
    $sql = "INSERT INTO Apps (`AppName`, `AppUrl`) VALUES ('$AppName', '$AppPictureUrl')";
    $result = mysqli_query($conn, $sql);

    // Redirect na succesvolle invoer
    if ($result) {
        header("Location: app store.php"); // Redirect naar de app_store.php pagina
        exit(); // Stop verdere uitvoering van de script
    }
}
?>
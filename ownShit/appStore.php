<?php
// Zorg ervoor dat de databaseverbinding wordt opgenomen
require("connection.php");

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Grid</title>
    <link rel="stylesheet" href="style_mobile.css">
</head>
<body>
    <form action="upload.php" method="POST">
        <input type="text" placeholder="App Name" name="appName" required />
        <input type="text" placeholder="App Image URL" name="appPicture" required />
        <input type="submit" value="Upload Image">
    </form>

    <div class="app-grid">
        <?php
        // Selecteer alle apps en hun URL's uit de database
        $query = "SELECT * FROM Apps";
        $result = mysqli_query($conn, $query);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='app-item'>";
                echo "<img src='{$row['AppUrl']}' alt='app picture'>";
                echo "<h2>{$row['AppName']}</h2>";
                echo "</div>";
            }
        } else {
            echo "<p>Geen afbeeldingen gevonden.</p>";
        }
        ?>
    </div>
</body>
</html>

<style>
    /* Grid-styling */
    .app-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* Creëert 4 kolommen */
        gap: 10px;
    }
    .app-item {
        text-align: center;
        margin: 10px;
    }
    img {
        border-radius: 25px;
        height: 90px; 
        width: 90px;
    }
</style>

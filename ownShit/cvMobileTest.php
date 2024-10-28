<?php
require("connection.php");

// Aantal apps per pagina instellen
$appsPerPage = 24;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $appsPerPage;

$totalQuery = "SELECT COUNT(*) as total FROM Apps";
$totalResult = mysqli_query($conn, $totalQuery);
$totalApps = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalApps / $appsPerPage);

$query = "SELECT * FROM Apps LIMIT $appsPerPage OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Borel&display=swap">
    <title>App Gallery</title>
    <style>
        * { color: white; }
        .wallpaper { width: 100%; height: auto; top: 0; left: 0; position: absolute; border-radius: 40px; }
        .outer-grid { display: grid; grid-template-columns: repeat(4, 1fr); grid-auto-rows: 120px; }
        .dynamic-island {
            margin-top: 10px; height: 50px; width: 75%; background-color: black; position: relative;
            grid-column: 2 / 4; grid-row: 1; margin-left: auto; margin-right: auto;
            justify-content: right; align-items: center; text-align: center; border-radius: 25px; display: flex;
        }
        .lens { border-radius: 50%; height: 40px; margin-right: 3%; opacity: 0.1; }
        .app-grid { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            grid-auto-rows: 120px; 
            margin-top: -30px; 
            transition: transform 0.5s ease-in-out; /* Smooth transition */
            position: relative; /* Needed for absolute positioning of child */
        }
        .app-item { text-align: center; margin: 0px; position: relative; }
        img { border-radius: 25px; height: auto; width: 70%; }
        h2 { margin-top: -5px; }
    </style>
</head>
<body>
    <img class="wallpaper" src="images/iphone wallpaper.png" alt="wallpaper">
    <section class="outer-grid">
        <div class="dynamic-island"><img class="lens" src="images/lens.png" alt=""></div>
    </section>
    <div class="app-grid" id="appGrid">
        <?php
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

    <script>
        const appGrid = document.getElementById('appGrid');
        let startX, currentX;
        let isDragging = false;

        // Pijltoetsnavigatie
        document.addEventListener('keydown', function(event) {
            // Rechterpijl: ga naar de volgende pagina
            if (event.key === 'ArrowRight' && <?php echo $page; ?> < <?php echo $totalPages; ?>) {
                navigatePage(1);
            }
            // Linkerpijl: ga naar de vorige pagina
            else if (event.key === 'ArrowLeft' && <?php echo $page; ?> > 1) {
                navigatePage(-1);
            }
        });

        // Functie om pagina te navigeren
        function navigatePage(direction) {
            appGrid.style.transition = 'transform 0.5s ease-in-out'; // Zorg voor een soepele overgang
            appGrid.style.transform = direction === 1 ? 'translateX(-100%)' : 'translateX(100%)'; // Beweeg naar links of rechts

            setTimeout(() => {
                window.location.href = "?page=" + (<?php echo $page; ?> + direction);
            }, 500); // Wacht op de fade-out animatie
        }

        // Touch events voor mobiele swipe-navigatie
        appGrid.addEventListener('touchstart', function(event) {
            startX = event.touches[0].clientX; // Beginpositie
            isDragging = true;
        });

        appGrid.addEventListener('touchmove', function(event) {
            if (isDragging) {
                currentX = event.touches[0].clientX; // Huidige positie
                const deltaX = currentX - startX; // Beweging
                appGrid.style.transform = deltaX < 0 ? `translateX(${deltaX}px)` : `translateX(0)`; // Beweeg naar links
            }
        });

        appGrid.addEventListener('touchend', function(event) {
            if (isDragging) {
                const deltaX = currentX - startX;
                appGrid.style.transition = 'transform 0.5s ease-in-out'; // Zorg voor een soepele overgang
                if (deltaX < -50 && <?php echo $page; ?> < <?php echo $totalPages; ?>) {
                    navigatePage(1); // Volgende pagina
                } else if (deltaX > 50 && <?php echo $page; ?> > 1) {
                    navigatePage(-1); // Vorige pagina
                } else {
                    appGrid.style.transform = 'translateX(0)'; // Terug naar de originele positie
                }
                isDragging = false;
            }
        });

        // Voeg de fade-in klasse toe bij pagina laden
        window.addEventListener('load', function() {
            appGrid.style.transform = 'translateX(0)'; // Zorg ervoor dat het terugkeert naar de standaardpositie
        });
    </script>
</body>
</html>

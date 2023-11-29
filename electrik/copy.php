<!DOCTYPE html>
<html lang="en">
<head>
    <title>Page Title</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include('navbar.php'); ?>

<!-- Bootstrap Carousel -->
<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner" id="carouselInner">
    <?php
    // Check if data is received from the client
    if (isset($_POST['jsonData'])) {
        $jsonData = json_decode($_POST['jsonData'], true);
        $apps = $jsonData['response']['apps'];

        // Output carousel items dynamically based on API data
        foreach ($apps as $index => $app) {
            $appId = $app['appid'];
            $imageUrl = "https://steamcdn-a.akamaihd.net/steam/apps/{$appId}/header.jpg";
            
            // Set active class for the first item
            $activeClass = ($index === 0) ? 'active' : '';

            echo '<div class="carousel-item ' . $activeClass . '">';
            echo '<img src="' . $imageUrl . '" class="d-block w-100" alt="Card ' . $appId . '" style="height: 25rem;">';
            echo '</div>';
        }
    }
    ?>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<!-- Cards below the carousel -->
<div class="container mt-5" id="cardContainer">
    <?php
    // Output initial set of cards
    for ($i = 1; $i <= 3; $i++) {
        echo '<div class="card">';
        echo '<img src="https://steamcdn-a.akamaihd.net/steam/apps/10/header.jpg" class="card-img-top" alt="Card ' . $i . '" style="height: 18rem;">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">Card ' . $i . '</h5>';
        echo '<p class="card-text">Some text for Card ' . $i . '.</p>';
        echo '</div>';
        echo '</div>';
    }
    ?>
</div>

<div class="footer">
   &copy; 2023 Electrik.com. All rights reserved. <a class="terms-link" href="terms.php">Terms of Service</a>
</div>

<!-- Bootstrap JS (optional, but required for some features) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Your JavaScript file -->
<script src="upcomingGames.js"></script>

<script>
// Infinite scrolling
window.onscroll = function() {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
        // Fetch and append more data (e.g., additional cards) here
        // For simplicity, let's add a few more cards with the same content
        for (let i = 4; i <= 6; i++) {
            let cardContainer = document.getElementById('cardContainer');
            let newCard = document.createElement('div');
            newCard.className = 'card';
            newCard.innerHTML = '<img src="https://steamcdn-a.akamaihd.net/steam/apps/10/header.jpg" class="card-img-top" alt="Card ' + i + '" style="height: 18rem;">' +
                '<div class="card-body">' +
                '<h5 class="card-title">Card ' + i + '</h5>' +
                '<p class="card-text">Some text for Card ' + i + '.</p>' +
                '</div>';
            cardContainer.appendChild(newCard);
        }
    }
};
</script>

</body>
</html>

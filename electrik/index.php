<!DOCTYPE html>
<html lang="en">
<head>
    <title>Page Title</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS from CDN for faster loading -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Inline critical CSS */
        /* Add essential styles here */
    </style>
    <link rel="stylesheet" href="style.css"> <!-- Non-critical CSS -->
</head>
<body>

<?php include('navbar.php'); ?>

<!-- Form to Submit or Clear SteamID -->
<form action="index.php" method="post" class="mt-3 mb-3">
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Enter SteamID" name="steamID">
        <button class="btn btn-outline-secondary" type="submit" name="submitSteamID">Submit</button>
        <button class="btn btn-outline-danger" type="submit" name="clearSteamID">Clear</button>
    </div>
</form>

<!-- Form to Search Game -->
<form action="index.php" method="get" class="mt-3 mb-3">
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Enter Game Name" name="gameSearch">
        <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
</form>

<!-- PHP Code for Pagination -->
<?php
    // Define default page and limit
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 5; // Reduced the limit for faster loading
    $offset = ($page - 1) * $limit;

    // Total items (assuming you know it's 10,000)
    $totalItems = 100; 
    $totalPages = ceil($totalItems / $limit);

    // Generate the API request with pagination
    $request = array();
    $request['type'] = "GetAppList";
    $request['offset'] = $offset; // Add offset to the request
    $request['limit'] = $limit;   // Add limit to the request

    // Sending the API request and handling the response
    $client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
    $response = $client->send_request($request);
?>

<!-- Bootstrap Carousel -->
<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner" id="carouselInner">
        <?php
        if (is_string($response)) {
            $jsonResponse = json_decode($response);
            if (json_last_error() === JSON_ERROR_NONE && isset($jsonResponse->response->apps)) {
                $firstItem = true;
                foreach ($jsonResponse->response->apps as $app) {
                    $appId = $app->appid;
                    $gameName = $app->name;
                    $imageUrl = "https://steamcdn-a.akamaihd.net/steam/apps/{$appId}/header.jpg";
                    $activeClass = $firstItem ? 'active' : '';
                    $firstItem = false;

                    echo '<div class="carousel-item ' . $activeClass . '">';
                    echo '<a href="review.php?appid=' . $appId . '&name=' . urlencode($gameName) . '">';
                    echo '<img src="' . $imageUrl . '" class="d-block w-100 lazy" alt="' . $gameName . '" style="height: 25rem;">';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo 'Response is not a valid JSON string.';
            }
        } else {
            echo '<script>';
            echo 'console.error("Response is not a string: ", ' . json_encode($response) . ');';
            echo '</script>';
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
<div class="container mt-5">
    <div class="card-group">
        <?php 
        $gamesToShow = !empty($ownedGames) ? $ownedGames : $randomGames;
        foreach ($gamesToShow as $game): ?>
            <div class="card">
                <a href="review.php?appid=<?php echo $game['appid']; ?>&name=<?php echo urlencode($game['name']); ?>">
                    <img src="https://steamcdn-a.akamaihd.net/steam/apps/<?php echo $game['appid']; ?>/header.jpg" class="card-img-top lazy" alt="<?php echo $game['name']; ?>" style="height: 18rem;">
                </a>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $game['name']; ?></h5>
                    <?php if (!empty($ownedGames)): ?>
                        <p class="card-text">Time Played: <?php echo round($game['playtime_forever'] / 60, 1); ?> hours</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Pagination Controls -->
<nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination justify-content-center">
        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= max(1, $page - 1) ?>">Previous</a>
        </li>
        <!-- Example: Add a few page number links here if needed -->
        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= min($totalPages, $page + 1) ?>">Next</a>
        </li>
    </ul>
</nav>

<!-- Table of Owned Games -->
<div class="container mt-5">
    <h3>Your Owned Games</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name of Game</th>
                <th>Hours Played</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($ownedGames)): ?>
                <?php foreach ($ownedGames as $game): ?>
                    <tr>
                        <td>
                            <a href="review.php?appid=<?php echo $game['appid']; ?>&name=<?php echo urlencode($game['name']); ?>">
                                <img src="https://steamcdn-a.akamaihd.net/steam/apps/<?php echo $game['appid']; ?>/header.jpg" alt="Game Image" style="height: 50px;">
                            </a>
                        </td>
                        <td><?php echo $game['name']; ?></td>
                        <td><?php echo round($game['playtime_forever'] / 60, 1); ?> hours</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No games to display. Please submit your SteamID.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="footer">
   &copy; 2023 Electrik.com. All rights reserved. <a class="terms-link" href="terms.php">Terms of Service</a>
</div>

<!-- Bootstrap JS (optional, but required for some features) -->
<!-- Load JS asynchronously for performance -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" async></script>
<script>
    // Lazy loading for images
    document.addEventListener("DOMContentLoaded", function() {
        var lazyImages = [].slice.call(document.querySelectorAll("img.lazy"));

        if ("IntersectionObserver" in window) {
            let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        let lazyImage = entry.target;
                        lazyImage.src = lazyImage.dataset.src;
                        lazyImage.classList.remove("lazy");
                        lazyImageObserver.unobserve(lazyImage);
                    }
                });
            });

            lazyImages.forEach(function(lazyImage) {
                lazyImageObserver.observe(lazyImage);
            });
        } else {
            // Fallback for browsers without IntersectionObserver support
            lazyImages.forEach(function(lazyImage) {
                lazyImage.src = lazyImage.dataset.src;
            });
        }
    });
</script>
</body>
</html>
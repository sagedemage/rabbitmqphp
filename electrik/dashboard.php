<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Dashboard</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
   <link rel="stylesheet" href="style.css"> <!-- Link to your CSS stylesheet for additional styling -->
</head>
<body>

<!-- Include the common header and navbar -->
<?php include('dashnav.php'); ?>

<div class="container mt-5">
   <h1 class="text-center display-4 fw-bold text-primary">Game Recommendation Portal</h1>
</div>

<!-- Cards below the carousel -->
<div class="container mt-5">
   <div class="row row-cols-1 row-cols-md-4 g-4">
       <!-- Cards from the original code -->
       <div class="col">
           <div class="card">
               <img src="images/image5.jpg" class="card-img-top" alt="Card 1">
               <div class="card-body">
                   <h5 class="card-title">Card 1</h5>
                   <p class="card-text">Some text for Card 1.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image7.png" class="card-img-top" alt="Card 2">
               <div class="card-body">
                   <h5 class="card-title">Card 2</h5>
                   <p class="card-text">Some text for Card 2.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image9.jpg" class="card-img-top" alt="Card 3">
               <div class="card-body">
                   <h5 class="card-title">Card 3</h5>
                   <p class="card-text">Some text for Card 3.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image10.jpg" class="card-img-top" alt="Card 4">
               <div class="card-body">
                   <h5 class="card-title">Card 4</h5>
                   <p class="card-text">Some text for Card 4.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image11.jpg" class="card-img-top" alt="Card 5">
               <div class="card-body">
                   <h5 class="card-title">Card 5</h5>
                   <p class="card-text">Some text for Card 5.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image12.jpg" class="card-img-top" alt="Card 6">
               <div class="card-body">
                   <h5 class="card-title">Card 6</h5>
                   <p class="card-text">Some text for Card 6.</p>
               </div>
           </div>
       </div>

       <!-- New cards -->
       <div class="col">
           <div class="card">
               <img src="images/image13.jpg" class="card-img-top" alt="Card 7">
               <div class="card-body">
                   <h5 class="card-title">Card 7</h5>
                   <p class="card-text">Some text for Card 7.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image14.jpg" class="card-img-top" alt="Card 8">
               <div class="card-body">
                   <h5 class="card-title">Card 8</h5>
                   <p class="card-text">Some text for Card 8.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image15.jpg" class="card-img-top" alt="Card 9">
               <div class="card-body">
                   <h5 class="card-title">Card 9</h5>
                   <p class="card-text">Some text for Card 9.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image16.jpg" class="card-img-top" alt="Card 10">
               <div class="card-body">
                   <h5 class="card-title">Card 10</h5>
                   <p class="card-text">Some text for Card 10.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image17.jpg" class="card-img-top" alt="Card 11">
               <div class="card-body">
                   <h5 class="card-title">Card 11</h5>
                   <p class="card-text">Some text for Card 11.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image18.jpg" class="card-img-top" alt="Card 12">
               <div class="card-body">
                   <h5 class="card-title">Card 12</h5>
                   <p class="card-text">Some text for Card 12.</p>
               </div>
           </div>
       </div>
   </div>
</div>

<!-- Include the common footer -->
<div class="footer">
   &copy; 2023 Electrik.com. All rights reserved. <a class="terms-link" href="terms.php">Terms of Service</a>
</div>

<!-- Bootstrap JS (optional, but required for some features) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Your existing script for user session verification -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
   function get_cookie_value(cookie_name) {
       let decodedCookie = decodeURIComponent(document.cookie);
       const cookieValue = decodedCookie
           .split("; ")
           .find((row) => row.startsWith(cookie_name + "="))
           ?.split("=")[1];
       return cookieValue;
   }
   let user_id = get_cookie_value("user_id");
   axios.post('/verify_user_session.php', {
       user_id: user_id,
   })
       .then(function(response) {
           if (response.data !== true) {
               location.href = "/login.php";
           }
       })
       .catch(function(error) {
           console.log(error);
       });
</script>

</body>
</html>



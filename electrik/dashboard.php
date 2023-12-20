<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Dashboard</title>
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
               <img src="images/image5.jpg" class="card-img-top" alt="Card 1" style="height: 18rem;">
               <div class="card-body">
                   <h5 class="card-title">Card 1</h5>
                   <p class="card-text">Some text for Card 1.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image7.png" class="card-img-top" alt="Card 2" style="height: 18rem;">
               <div class="card-body">
                   <h5 class="card-title">Card 2</h5>
                   <p class="card-text">Some text for Card 2.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image9.jpg" class="card-img-top" alt="Card 3" style="height: 18rem;">
               <div class="card-body">
                   <h5 class="card-title">Card 3</h5>
                   <p class="card-text">Some text for Card 3.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image10.jpg" class="card-img-top" alt="Card 4" style="height: 18rem;">
               <div class="card-body">
                   <h5 class="card-title">Card 4</h5>
                   <p class="card-text">Some text for Card 4.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image11.jpg" class="card-img-top" alt="Card 5" style="height: 18rem;">
               <div class="card-body">
                   <h5 class="card-title">Card 5</h5>
                   <p class="card-text">Some text for Card 5.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image12.jpg" class="card-img-top" alt="Card 6" style="height: 18rem;">
               <div class="card-body">
                   <h5 class="card-title">Card 6</h5>
                   <p class="card-text">Some text for Card 6.</p>
               </div>
           </div>
       </div>

       <!-- New cards -->
       <div class="col">
           <div class="card">
               <img src="images/image13.jpg" class="card-img-top" alt="Card 7" style="height: 18rem;">
               <div class="card-body">
                   <h5 class="card-title">Card 7</h5>
                   <p class="card-text">Some text for Card 7.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image14.jpg" class="card-img-top" alt="Card 8" style="height: 18rem;">
               <div class="card-body">
                   <h5 class="card-title">Card 8</h5>
                   <p class="card-text">Some text for Card 8.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image15.jpg" class="card-img-top" alt="Card 9" style="height: 18rem;">
               <div class="card-body">
                   <h5 class="card-title">Card 9</h5>
                   <p class="card-text">Some text for Card 9.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image16.jpg" class="card-img-top" alt="Card 10" style="height: 18rem;">
               <div class="card-body">
                   <h5 class="card-title">Card 10</h5>
                   <p class="card-text">Some text for Card 10.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image17.jpg" class="card-img-top" alt="Card 11" style="height: 18rem;">
               <div class="card-body">
                   <h5 class="card-title">Card 11</h5>
                   <p class="card-text">Some text for Card 11.</p>
               </div>
           </div>
       </div>
       <div class="col">
           <div class="card">
               <img src="images/image18.jpg" class="card-img-top" alt="Card 12" style="height: 18rem;">
               <div class="card-body">
                   <h5 class="card-title">Card 12</h5>
                   <p class="card-text">Some text for Card 12.</p>
               </div>
           </div>
       </div>
   </div>
</div>

<!-- Include the common footer -->
<?php include('footer.php'); ?>

<!-- Your existing script for user session verification -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="verify_user_session.js"></script>

</body>
</html>



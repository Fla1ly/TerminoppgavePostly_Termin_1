<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

include 'components/like_post.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>kategori</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <?php include 'components/user_header.php'; ?>
   <section class="categories">
      <h1 class="heading">kategorier</h1>
      <div class="box-container">
         <div class="box"><a href="category.php?category=nature">natur</a></div>
         <div class="box"><a href="category.php?category=eduction">utdanning</a></div>
         <div class="box"><a href="category.php?category=pets and animals">dyr</a></div>
         <div class="box"><a href="category.php?category=technology">teknologi</a></div>
         <div class="box"><a href="category.php?category=fashion">fashion</a></div>
         <div class="box"><a href="category.php?category=entertainment">underholdning</a></div>
         <div class="box"><a href="category.php?category=movies">filmer</a></div>
         <div class="box"><a href="category.php?category=gaming">spill</a></div>
         <div class="box"><a href="category.php?category=music">musikk</a></div>
         <div class="box"><a href="category.php?category=sports">sport</a></div>
         <div class="box"><a href="category.php?category=news">nyheter</a></div>
         <div class="box"><a href="category.php?category=travel">reise</a></div>
         <div class="box"><a href="category.php?category=comedy">komedie</a></div>
         <div class="box"><a href="category.php?category=design and development">design og utvikling</a></div>
         <div class="box"><a href="category.php?category=food and drinks">mat og drikke</a></div>
         <div class="box"><a href="category.php?category=lifestyle">livsstil</a></div>
         <div class="box"><a href="category.php?category=health and fitness">helse og trening</a></div>
         <div class="box"><a href="category.php?category=business">business</a></div>
         <div class="box"><a href="category.php?category=shopping">shopping</a></div>
         <div class="box"><a href="category.php?category=animations">animasjoner</a></div>
      </div>
   </section>
   <script src="js/script.js"></script>
<body>
   
</html>
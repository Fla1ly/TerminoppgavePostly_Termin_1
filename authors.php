<?php

// Inkluderer databasekoblingsfilen
include 'components/connect.php';

// Starter økten for å kunne bruke $_SESSION-variabler
session_start();

// Sjekker om bruker er logget inn, henter bruker-ID hvis det er tilfelle
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

// Inkluderer filen for å håndtere "like" på innlegg
include 'components/like_post.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>forfattere</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <!-- Inkluderer brukerhodet for konsistens -->
   <?php include 'components/user_header.php'; ?>

   <!-- Seksjon for forfattere -->
   <section class="authors">
      <!-- Overskrift for forfatterseksjonen -->
      <h1 class="heading">Forfattere</h1>

      <!-- Bokscontainer for forfatterinformasjon -->
      <div class="box-container">
         <?php
         // Forbereder og utfører SQL-spørring for å hente alle forfattere
         $select_author = $conn->prepare("SELECT * FROM `admin`");
         $select_author->execute();

         // Sjekker om det finnes forfattere
         if ($select_author->rowCount() > 0) {
            while ($fetch_authors = $select_author->fetch(PDO::FETCH_ASSOC)) {

               // Henter antall innlegg, likes og kommentarer for hver forfatter
               $count_admin_posts = $conn->prepare("SELECT * FROM `posts` WHERE admin_id = ? AND status = ?");
               $count_admin_posts->execute([$fetch_authors['id'], 'active']);
               $total_admin_posts = $count_admin_posts->rowCount();

               $count_admin_likes = $conn->prepare("SELECT * FROM `likes` WHERE admin_id = ?");
               $count_admin_likes->execute([$fetch_authors['id']]);
               $total_admin_likes = $count_admin_likes->rowCount();

               $count_admin_comments = $conn->prepare("SELECT * FROM `comments` WHERE admin_id = ?");
               $count_admin_comments->execute([$fetch_authors['id']]);
               $total_admin_comments = $count_admin_comments->rowCount();

         ?>
               <!-- Boks med forfatterinformasjon -->
               <div class="box">
                  <!-- Forfatterens navn og statistikk -->
                  <p>forfatter : <span><?= $fetch_authors['name']; ?></span></p>
                  <p>antall innlegg : <span><?= $total_admin_posts; ?></span></p>
                  <p>innlegg likes : <span><?= $total_admin_likes; ?></span></p>
                  <p>innlegg kommentarer : <span><?= $total_admin_comments; ?></span></p>
                  <!-- Lenke til forfatterens innlegg -->
                  <a href="author_posts.php?author=<?= $fetch_authors['name']; ?>" class="btn">Vis innlegg</a>
               </div>
         <?php
            }
         } else {
            // Melding hvis ingen forfattere ble funnet
            echo '<p class="empty">Ingen forfattere ble funnet!</p>';
         }
         ?>
      </div>
   </section>
   <!-- Inkluderer skriptfil for interaktivitet -->
   <script src="js/script.js"></script>
</body>

</html>
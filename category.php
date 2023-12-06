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

// Henter kategori fra URL-parameteren hvis den er satt, ellers settes den til tom streng
if (isset($_GET['category'])) {
   $category = $_GET['category'];
} else {
   $category = '';
}

// Inkluderer filen for å håndtere "like" på innlegg
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
   <!-- Inkluderer brukerhodet for konsistens -->
   <?php include 'components/user_header.php'; ?>

   <!-- Seksjon for innlegg i en bestemt kategori -->
   <section class="posts-container">
      <!-- Overskrift for kategoriinnleggene -->
      <h1 class="heading">Forrige kategorier</h1>

      <!-- Bokscontainer for innlegg i kategorien -->
      <div class="box-container">
         <?php
         // Forbereder og utfører SQL-spørring for å hente innlegg fra en bestemt kategori
         $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE category = ? and status = ?");
         $select_posts->execute([$category, 'active']);

         // Sjekker om det finnes innlegg i kategorien
         if ($select_posts->rowCount() > 0) {
            while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {

               // Henter nødvendig informasjon om innlegget
               $post_id = $fetch_posts['id'];

               // Henter antall kommentarer, likes og sjekker om brukeren har likt innlegget
               $count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
               $count_post_comments->execute([$post_id]);
               $total_post_comments = $count_post_comments->rowCount();

               $count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");
               $count_post_likes->execute([$post_id]);
               $total_post_likes = $count_post_likes->rowCount();

               $confirm_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND post_id = ?");
               $confirm_likes->execute([$user_id, $post_id]);
         ?>
               <!-- Skjema for hvert innlegg i kategorien -->
               <form class="box" method="post">
                  <input type="hidden" name="post_id" value="<?= $post_id; ?>">
                  <input type="hidden" name="admin_id" value="<?= $fetch_posts['admin_id']; ?>">
                  <!-- Forfatterinformasjon -->
                  <div class="post-admin">
                     <i class="fas fa-user"></i>
                     <div>
                        <a href="author_posts.php?author=<?= $fetch_posts['name']; ?>"><?= $fetch_posts['name']; ?></a>
                        <div><?= $fetch_posts['date']; ?></div>
                     </div>
                  </div>

                  <!-- Viser bilde hvis det finnes -->
                  <?php
                  if ($fetch_posts['image'] != '') {
                  ?>
                     <img src="uploaded_img/<?= $fetch_posts['image']; ?>" class="post-image" alt="">
                  <?php
                  }
                  ?>
                  <!-- Tittel, innhold og lenke til fullt innlegg -->
                  <div class="post-title"><?= $fetch_posts['title']; ?></div>
                  <div class="post-content content-150"><?= $fetch_posts['content']; ?></div>
                  <a href="view_post.php?post_id=<?= $post_id; ?>" class="inline-btn">Les mer</a>
                  <!-- Ikoner for kommentarer og "likes" -->
                  <div class="icons">
                     <a href="view_post.php?post_id=<?= $post_id; ?>"><i class="fas fa-comment"></i><span><?= $total_post_comments; ?></span></a>
                     <button type="submit" name="like_post"><i class="fas fa-heart" style="<?php if ($confirm_likes->rowCount() > 0) {
                                                                                                echo 'color:var(--red);';
                                                                                             } ?>  "></i><span><?= $total_post_likes; ?></span></button>
                  </div>
               </form>
         <?php
            }
         } else {
            // Melding hvis ingen innlegg ble funnet i kategorien
            echo '<p class="empty">Ingen innlegg ble funnet i denne kategorien!</p>';
         }
         ?>
      </div>
   </section>
   <!-- Inkluderer skriptfil for interaktivitet -->
   <script src="js/script.js"></script>
</body>

</html>
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

// Henter forfatter fra URL-parameteren hvis den er satt, ellers settes den til tom streng
if (isset($_GET['author'])) {
   $author = $_GET['author'];
} else {
   $author = '';
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
   <title>Forfattere Innlegg</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <!-- Inkluderer brukerhodet for konsistens -->
   <?php include 'components/user_header.php'; ?>

   <!-- Seksjon for innlegg -->
   <section class="posts-container">
      <!-- Bokscontainer for innlegg -->
      <div class="box-container">
         <?php
         // Forbereder og utfører SQL-spørring for å hente innlegg fra en bestemt forfatter
         $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE name = ? and status = ?");
         $select_posts->execute([$author, 'active']);

         // Sjekker om det finnes innlegg for den angitte forfatteren
         if ($select_posts->rowCount() > 0) {
            while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {

               // Henter inn nødvendig informasjon om innlegget
               $post_id = $fetch_posts['id'];

               $count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
               $count_post_comments->execute([$post_id]);
               $total_post_comments = $count_post_comments->rowCount();

               $count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");
               $count_post_likes->execute([$post_id]);
               $total_post_likes = $count_post_likes->rowCount();

               $confirm_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND post_id = ?");
               $confirm_likes->execute([$user_id, $post_id]);
         ?>
               <!-- Skjema for hvert innlegg -->
               <form class="box" method="post">
                  <input type="hidden" name="post_id" value="<?= $post_id; ?>">
                  <input type="hidden" name="admin_id" value="<?= $fetch_posts['admin_id']; ?>">
                  <div class="post-admin">
                     <!-- Ikon og forfatterinformasjon -->
                     <i class="fas fa-user"></i>
                     <div>
                        <!-- Lenke til forfatterens innlegg -->
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
                  <a href="view_post.php?post_id=<?= $post_id; ?>" class="inline-btn">les mer</a>
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
            // Melding hvis ingen innlegg ble funnet for den angitte forfatteren
            echo '<p class="empty">ingen innlegg funnet for denne forfatteren!</p>';
         }
         ?>
      </div>
   </section>
   <!-- Inkluderer skriptfil for interaktivitet -->
   <script src="js/script.js"></script>
</body>

</html>
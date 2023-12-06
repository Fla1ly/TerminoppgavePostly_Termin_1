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
   <title>hjemme side</title>
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body class="home-body">
   <!-- Inkluderer brukerhodet for konsistens -->
   <?php include 'components/user_header.php'; ?>

   <!-- Seksjon for nylige innlegg -->
   <section class="home-section">
      <section class="posts-container">
         <!-- Overskrift for nylige innlegg -->
         <h1 class="heading">Nylige innlegg</h1>

         <!-- Bokscontainer for nylige innlegg -->
         <div class="box-container">
            <?php
            // Forbereder og utfører SQL-spørring for å hente nylige innlegg
            $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE status = ? LIMIT 6 ");
            $select_posts->execute(['active']);

            // Sjekker om det finnes nylige innlegg
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
                  <!-- Skjema for hvert nylige innlegg -->
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
                     <a href="category.php?category=<?= $fetch_posts['category']; ?>" class="post-cat"> <i class="fas fa-tag"></i> <span><?= $fetch_posts['category']; ?></span></a>
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
               // Melding hvis ingen nylige innlegg ble funnet
               echo '<p class="empty">Ingen innlegg ble lagt ut ennå!</p>';
            }
            ?>
         </div>

         <!-- Knapp for å vise flere innlegg -->
         <div class="more-btn" style="text-align: center; margin-top:1rem;">
            <a href="posts.php" class="inline-btn">Vis mer </a>
         </div>
      </section>

      <!-- Seksjon for rutenett med brukerprofil og kategorier -->
      <section class="home-grid">
         <div class="box-container">
            <!-- Boks for brukerprofil -->
            <div class="box">
               <?php
               // Henter brukerprofilinformasjon hvis brukeren er logget inn
               $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
               $select_profile->execute([$user_id]);

               // Sjekker om brukerprofilen ble funnet
               if ($select_profile->rowCount() > 0) {
                  $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

                  // Henter antall brukerens kommentarer og likes
                  $count_user_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
                  $count_user_comments->execute([$user_id]);
                  $total_user_comments = $count_user_comments->rowCount();

                  $count_user_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
                  $count_user_likes->execute([$user_id]);
                  $total_user_likes = $count_user_likes->rowCount();
               ?>
                  <!-- Viser brukerprofilinformasjon -->
                  <p> Velkommen <span><?= $fetch_profile['name']; ?></span></p>
                  <p> Kommentarer: <span><?= $total_user_comments; ?></span></p>
                  <p> Liker innlegg: <span><?= $total_user_likes; ?></span></p>
                  <a href="update.php" class="btn">Oppdater profil</a>
                  <div class="flex-btn">
                     <a href="user_likes.php" class="option-btn">Liker</a>
                     <a href="user_comments.php" class="option-btn">Kommentarer</a>
                  </div>
               <?php
               } else {
               ?>
                  <!-- Melding hvis brukeren ikke er logget inn -->
                  <p class="name">Logg inn eller registrer konto!</p>
                  <div class="flex-btn">
                     <a href="login.php" class="option-btn">Logg inn</a>
                     <a href="register.php" class="option-btn">Registrer</a>
                  </div>
               <?php
               }
               ?>
            </div>

            <!-- Boks for kategorier -->
            <div class="box">
               <p> Kategorier</p>
               <div class="flex-box">
                  <!-- Lenker til ulike kategorier -->
                  <a href="category.php?category=nature" class="links">Natur</a>
                  <a href="category.php?category=education" class="links">Utdanning</a>
                  <a href="category.php?category=business" class="links">Business</a>
                  <a href="category.php?category=travel" class="links">Reise</a>
                  <a href="category.php?category=news" class="links">Nyheter</a>
                  <a href="category.php?category=gaming" class="links">Spill</a>
                  <a href="category.php?category=sports" class="links">Sport</a>
                  <a href="category.php?category=design" class="links">Design</a>
                  <a href="category.php?category=fashion" class="links">Fashion</a>
                  <a href="category.php?category=personal" class="links">Personlig</a>
                  <!-- Knapp for å vise alle kategorier -->
                  <a href="all_category.php" class="btn">Vis alle</a>
               </div>
            </div>

            <!-- Boks for forfattere -->
            <div class="box">
               <p> Forfattere</p>
               <div class="flex-box">
                  <?php
                  // Henter unike forfatternavn fra databasen
                  $select_authors = $conn->prepare("SELECT DISTINCT name FROM `admin` LIMIT 10");
                  $select_authors->execute();

                  // Sjekker om det finnes forfattere
                  if ($select_authors->rowCount() > 0) {
                     while ($fetch_authors = $select_authors->fetch(PDO::FETCH_ASSOC)) {
                  ?>
                        <!-- Lenker til forfatterens innlegg -->
                        <a href="author_posts.php?author=<?= $fetch_authors['name']; ?>" class="links"><?= $fetch_authors['name']; ?></a>
                  <?php
                     }
                  } else {
                     // Melding hvis ingen forfattere ble funnet
                     echo '<p class="empty">Ingen innlegg ble lagt ut ennå!</p>';
                  }
                  ?>
                  <!-- Knapp for å vise alle forfattere -->
                  <a href="authors.php" class="btn">Vis alle</a>
               </div>
            </div>
         </div>
      </section>
   </section>
   <!-- Inkluderer skriptfil for interaktivitet -->
   <script src="js/script.js"></script>
</body>

</html>
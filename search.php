<?php

// Inkluderer tilkoblingsfilen for å koble til databasen
include 'components/connect.php';

// Starter sesjonen for å kunne lagre brukerinformasjon
session_start();

// Sjekker om brukeren allerede er logget inn ved å se etter bruker-ID i sesjonen
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   // Hvis ikke, setter bruker-ID til tom streng
   $user_id = '';
}

// Inkluderer filen for å behandle "lik post"-funksjonaliteten
include 'components/like_post.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>søk</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <?php include 'components/user_header.php'; ?>
   <?php
   // Sjekker om søkeskjemaet er sendt inn
   if (isset($_POST['search_box']) or isset($_POST['search_btn'])) {
   ?>
      <!-- Seksjon for å vise søkeresultater -->
      <section class="posts-container">
         <div class="box-container">
            <?php
            // Henter søkeordet fra skjemaet
            $search_box = $_POST['search_box'];
            // Forbereder og utfører spørring for å hente relevante innlegg basert på tittel eller kategori
            $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE title LIKE '%{$search_box}%' OR category LIKE '%{$search_box}%' AND status = ?");
            $select_posts->execute(['active']);
            if ($select_posts->rowCount() > 0) {
               while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {

                  $post_id = $fetch_posts['id'];

                  // Henter antall kommentarer for hvert innlegg
                  $count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
                  $count_post_comments->execute([$post_id]);
                  $total_post_comments = $count_post_comments->rowCount();

                  // Henter antall "lik" for hvert innlegg
                  $count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");
                  $count_post_likes->execute([$post_id]);
                  $total_post_likes = $count_post_likes->rowCount();

                  // Sjekker om brukeren allerede har likt dette innlegget
                  $confirm_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND post_id = ?");
                  $confirm_likes->execute([$user_id, $post_id]);
            ?>
                  <!-- Skjema for hvert innlegg i søkeresultatet -->
                  <form class="box" method="post">
                     <input type="hidden" name="post_id" value="<?= $post_id; ?>">
                     <input type="hidden" name="admin_id" value="<?= $fetch_posts['admin_id']; ?>">
                     <div class="post-admin">
                        <i class="fas fa-user"></i>
                        <div>
                           <!-- Lenke til forfatterens innlegg basert på forfatternavnet -->
                           <a href="author_posts.php?author=<?= $fetch_posts['name']; ?>"><?= $fetch_posts['name']; ?></a>
                           <div><?= $fetch_posts['date']; ?></div>
                        </div>
                     </div>
                     <?php
                     // Viser bilde hvis det finnes
                     if ($fetch_posts['image'] != '') {
                     ?>
                        <img src="uploaded_img/<?= $fetch_posts['image']; ?>" class="post-image" alt="">
                     <?php
                     }
                     ?>
                     <div class="post-title"><?= $fetch_posts['title']; ?></div>
                     <div class="post-content content-150"><?= $fetch_posts['content']; ?></div>
                     <!-- Lenke til å se hele innlegget -->
                     <a href="view_post.php?post_id=<?= $post_id; ?>" class="inline-btn">Les mer</a>
                     <!-- Lenke til å se innlegg basert på kategori -->
                     <a href="category.php?category=<?= $fetch_posts['category']; ?>" class="post-cat">
                        <i class="fas fa-tag"></i>
                        <span><?= $fetch_posts['category']; ?></span>
                     </a>
                     <div class="icons">
                        <!-- Lenke til å se kommentarer for innlegget -->
                        <a href="view_post.php?post_id=<?= $post_id; ?>">
                           <i class="fas fa-comment"></i>
                           <span><?= $total_post_comments; ?></span>
                        </a>
                        <!-- Knapp for å "like" innlegget -->
                        <button type="submit" name="like_post">
                           <i class="fas fa-heart" style="<?php if ($confirm_likes->rowCount() > 0) {
                                                               echo 'color:var(--red);';
                                                            } ?>  "></i>
                           <span><?= $total_post_likes; ?></span>
                        </button>
                     </div>
                  </form>
            <?php
               }
            } else {
               // Melding hvis ingen resultater ble funnet
               echo '<p class="empty">Ingen resultater ble funnet!</p>';
            }
            ?>
         </div>
      </section>
   <?php
   } else {
      // Melding hvis skjemaet ikke er sendt inn
      echo '<section><p class="empty">Søk etter noe!</p></section>';
   }
   ?>
   <!-- Inkluderer JavaScript-filen -->
   <script src="js/script.js"></script>
</body>

</html>
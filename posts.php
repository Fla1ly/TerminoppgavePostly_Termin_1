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

// Inkluderer filen for å behandle likerklikk på innlegg
include 'components/like_post.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Innlegg</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <?php include 'components/user_header.php'; ?>
   <!-- Seksjon for å vise nyeste innlegg -->
   <section class="posts-container">
      <h1 class="heading">Nylige innlegg</h1>
      <div class="box-container">
         <?php
         // Forbereder og utfører spørringen for å hente alle aktive innlegg
         $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE status = ?");
         $select_posts->execute(['active']);

         // Sjekker om det er minst ett innlegg
         if ($select_posts->rowCount() > 0) {
            while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {

               // Henter innlegg-ID
               $post_id = $fetch_posts['id'];

               // Forbereder og utfører spørring for å telle antall kommentarer til innlegget
               $count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
               $count_post_comments->execute([$post_id]);
               $total_post_comments = $count_post_comments->rowCount();

               // Forbereder og utfører spørring for å telle antall likerklikk på innlegget
               $count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");
               $count_post_likes->execute([$post_id]);
               $total_post_likes = $count_post_likes->rowCount();

               // Forbereder og utfører spørring for å bekrefte om brukeren har likt innlegget
               $confirm_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND post_id = ?");
               $confirm_likes->execute([$user_id, $post_id]);
         ?>
               <!-- Skjema for hvert innlegg med skjulte inndatafelt -->
               <form class="box" method="post">
                  <input type="hidden" name="post_id" value="<?= $post_id; ?>">
                  <input type="hidden" name="admin_id" value="<?= $fetch_posts['admin_id']; ?>">
                  <!-- Informasjon om innleggets forfatter og dato -->
                  <div class="post-admin">
                     <i class="fas fa-user"></i>
                     <div>
                        <!-- Lenke til forfatterens innlegg basert på forfatternavnet -->
                        <a href="author_posts.php?author=<?= $fetch_posts['name']; ?>"><?= $fetch_posts['name']; ?></a>
                        <div><?= $fetch_posts['date']; ?></div>
                     </div>
                  </div>

                  <?php
                  // Sjekker om innlegget har et bilde
                  if ($fetch_posts['image'] != '') {
                  ?>
                     <!-- Viser bildet hvis tilgjengelig -->
                     <img src="uploaded_img/<?= $fetch_posts['image']; ?>" class="post-image" alt="">
                  <?php
                  }
                  ?>
                  <!-- Viser innleggets tittel og innhold -->
                  <div class="post-title"><?= $fetch_posts['title']; ?></div>
                  <div class="post-content content-150"><?= $fetch_posts['content']; ?></div>
                  <!-- Lenke for å lese mer om innlegget -->
                  <a href="view_post.php?post_id=<?= $post_id; ?>" class="inline-btn">Les mer</a>
                  <!-- Viser kategorien til innlegget -->
                  <a href="category.php?category=<?= $fetch_posts['category']; ?>" class="post-cat"> <i class="fas fa-tag"></i> <span><?= $fetch_posts['category']; ?></span></a>
                  <!-- Ikoner for antall kommentarer og likerklikk -->
                  <div class="icons">
                     <a href="view_post.php?post_id=<?= $post_id; ?>"><i class="fas fa-comment"></i><span><?= $total_post_comments; ?></span></a>
                     <!-- Knapp for å sende inn likerklikk på innlegget -->
                     <button type="submit" name="like_post"><i class="fas fa-heart" style="<?php if ($confirm_likes->rowCount() > 0) {
                                                                                                echo 'color:var(--red);';
                                                                                             } ?>  "></i><span><?= $total_post_likes; ?></span></button>
                  </div>
               </form>
         <?php
            }
         } else {
            // Melding hvis ingen innlegg er tilgjengelige
            echo '<p class="empty">Ingen innlegg ble lagd ennå!</p>';
         }
         ?>
      </div>
   </section>
   <!-- Inkluderer JavaScript-filen -->
   <script src="js/script.js"></script>

</body>

</html>
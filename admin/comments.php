<?php

// Inkluderer tilkoblingsfilen for å koble til databasen
include '../components/connect.php';

// Starter sesjonen for å kunne lagre admininformasjon
session_start();

// Henter admin-ID fra sesjonen
$admin_id = $_SESSION['admin_id'];

// Sjekker om admin-ID er satt i sesjonen, ellers omdirigerer til innloggingssiden
if (!isset($admin_id)) {
   header('location:admin_login.php');
}

// Håndterer sletting av kommentar når skjemaet blir sendt
if (isset($_POST['delete_comment'])) {

   // Henter og filtrerer kommentar-ID fra skjemaet
   $comment_id = $_POST['comment_id'];
   $comment_id = filter_var($comment_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   // Forbereder og utfører SQL-spørring for å slette kommentaren basert på kommentar-ID
   $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
   $delete_comment->execute([$comment_id]);

   // Legger til en melding om at kommentaren er slettet
   $message[] = 'Kommentar slettet!';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>user kontoer</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>
   <?php include '../components/admin_header.php' ?>
   <section class="comments">
      <h1 class="heading">Kommentarer</h1>
      <p class="comment-title">Kommentarer på innlegg</p>
      <div class="box-container">
         <?php
         // Henter kommentarer fra databasen basert på admin-ID
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE admin_id = ?");
         $select_comments->execute([$admin_id]);

         // Sjekker om det er noen kommentarer
         if ($select_comments->rowCount() > 0) {
            while ($fetch_comments = $select_comments->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <?php
               // Henter tilhørende innlegg basert på post-ID
               $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE id = ?");
               $select_posts->execute([$fetch_comments['post_id']]);
               while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {
               ?>
                  <div class="post-title"> From: <span><?= $fetch_posts['title']; ?></span> <a href="read_post.php?post_id=<?= $fetch_posts['id']; ?>">Vis innlegg</a></div>
               <?php
               }
               ?>
               <div class="box">
                  <div class="user">
                     <i class="fas fa-user"></i>
                     <div class="user-info">
                        <span><?= $fetch_comments['user_name']; ?></span>
                        <div><?= $fetch_comments['date']; ?></div>
                     </div>
                  </div>
                  <div class="text"><?= $fetch_comments['comment']; ?></div>
                  <!-- Skjema for å slette kommentaren -->
                  <form action="" method="POST">
                     <input type="hidden" name="comment_id" value="<?= $fetch_comments['id']; ?>">
                     <button type="submit" class="inline-delete-btn" name="delete_comment" onclick="return confirm('Slett kommentaren?');">Slett kommentar</button>
                  </form>
               </div>
         <?php
            }
         } else {
            // Melding vises hvis det ikke er noen kommentarer
            echo '<p class="empty">Ingen kommentarer ble lagt til!</p>';
         }
         ?>
      </div>
   </section>
   <script src="../js/admin_script.js"></script>
</body>

</html>
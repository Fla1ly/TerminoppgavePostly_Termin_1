<?php

// Inkluderer tilkoblingsfilen for å koble til databasen
include 'components/connect.php';

// Starter sesjonen for å kunne lagre brukerinformasjon
session_start();

// Sjekker om brukeren allerede er logget inn ved å se etter bruker-ID i sesjonen
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   // Hvis ikke, setter bruker-ID til tom streng og sender brukeren til hjemmesiden
   $user_id = '';
   header('location:home.php');
}

// Sjekker om skjemaet for redigering av kommentarer er sendt inn
if (isset($_POST['edit_comment'])) {

   // Henter og filtrerer kommentar-ID og kommentartekst fra skjemaet
   $edit_comment_id = $_POST['edit_comment_id'];
   $edit_comment_id = filter_var($edit_comment_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   $comment_edit_box = $_POST['comment_edit_box'];
   $comment_edit_box = filter_var($comment_edit_box, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   // Verifiserer at den redigerte kommentaren ikke allerede eksisterer
   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE comment = ? AND id = ?");
   $verify_comment->execute([$comment_edit_box, $edit_comment_id]);

   // Utfører redigering av kommentaren hvis den ikke allerede eksisterer
   if ($verify_comment->rowCount() > 0) {
      $message[] = 'Kommentaren eksisterer allerede!';
   } else {
      $update_comment = $conn->prepare("UPDATE `comments` SET comment = ? WHERE id = ?");
      $update_comment->execute([$comment_edit_box, $edit_comment_id]);
      $message[] = 'Kommentaren din ble redigert!';
   }
}

// Sjekker om skjemaet for sletting av kommentarer er sendt inn
if (isset($_POST['delete_comment'])) {
   $delete_comment_id = $_POST['comment_id'];
   $delete_comment_id = filter_var($delete_comment_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
   $delete_comment->execute([$delete_comment_id]);
   $message[] = 'Kommentaren ble slettet!';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dine kommentarer</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <?php include 'components/user_header.php'; ?>
   <?php
   // Sjekker om skjemaet for å åpne redigeringsboksen er sendt inn
   if (isset($_POST['open_edit_box'])) {
      $comment_id = $_POST['comment_id'];
      $comment_id = filter_var($comment_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   ?>
      <section class="comment-edit-form">
         <p>Rediger din kommentar</p>
         <?php
         // Henter kommentaren som skal redigeres basert på kommentar-ID
         $select_edit_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
         $select_edit_comment->execute([$comment_id]);
         $fetch_edit_comment = $select_edit_comment->fetch(PDO::FETCH_ASSOC);
         ?>
         <form action="" method="POST">
            <input type="hidden" name="edit_comment_id" value="<?= $comment_id; ?>">
            <textarea name="comment_edit_box" required cols="30" rows="10" placeholder="Skriv inn din kommentar"><?= $fetch_edit_comment['comment']; ?></textarea>
            <button type="submit" class="inline-btn" name="edit_comment">Rediger kommentar</button>
            <div class="inline-option-btn" onclick="window.location.href = 'user_comments.php';">Avbryt redigering</div>
         </form>
      </section>
   <?php
   }
   ?>
   <section class="comments-container">
      <h1 class="heading">Dine kommentarer</h1>
      <p class="comment-title">Dine kommentarer på forskjellige innlegg</p>
      <div class="user-comments-container">
         <?php
         // Henter brukerens kommentarer fra databasen
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
         $select_comments->execute([$user_id]);
         if ($select_comments->rowCount() > 0) {
            while ($fetch_comments = $select_comments->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <div class="show-comments">
                  <?php
                  // Henter informasjon om innlegget som kommentaren tilhører
                  $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE id = ?");
                  $select_posts->execute([$fetch_comments['post_id']]);
                  while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {
                  ?>
                     <div class="post-title"> Fra: <span><?= $fetch_posts['title']; ?></span> <a href="view_post.php?post_id=<?= $fetch_posts['id']; ?>">Vis innlegg</a></div>
                  <?php
                  }
                  ?>
                  <div class="comment-box"><?= $fetch_comments['comment']; ?></div>
                  <form action="" method="POST">
                     <input type="hidden" name="comment_id" value="<?= $fetch_comments['id']; ?>">
                     <button type="submit" class="inline-option-btn" name="open_edit_box">Rediger kommentar</button>
                     <button type="submit" class="inline-delete-btn" name="delete_comment" onclick="return confirm('Slett denne kommentaren?');">Slett kommentar</button>
                  </form>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">Ingen kommentarer ble laget ennå!</p>';
         }
         ?>
      </div>
   </section>
   <script src="js/script.js"></script>
</body>

</html>
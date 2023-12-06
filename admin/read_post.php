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

// Henter innleggets ID fra URL-parametere
$get_id = $_GET['post_id'];

// Sjekker om skjemainnsendingen er for sletting av innlegg
if (isset($_POST['delete'])) {

   // Henter innleggets ID fra skjemainndata
   $p_id = $_POST['post_id'];
   $p_id = filter_var($p_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   // Forbereder og utfører SQL-spørringer for å slette innlegget og tilhørende kommentarer
   $delete_image = $conn->prepare("SELECT * FROM `posts` WHERE id = ?");
   $delete_image->execute([$p_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   // Sjekker om det er et bilde knyttet til innlegget og sletter det
   if ($fetch_delete_image['image'] != '') {
      unlink('../uploaded_img/' . $fetch_delete_image['image']);
   }
   $delete_post = $conn->prepare("DELETE FROM `posts` WHERE id = ?");
   $delete_post->execute([$p_id]);
   $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE post_id = ?");
   $delete_comments->execute([$p_id]);
   // Omdirigerer til visningsiden for innlegg
   header('location:view_posts.php');
}

// Sjekker om skjemainnsendingen er for sletting av kommentar
if (isset($_POST['delete_comment'])) {

   // Henter kommentarens ID fra skjemainndata
   $comment_id = $_POST['comment_id'];
   $comment_id = filter_var($comment_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   // Forbereder og utfører SQL-spørringer for å slette kommentaren
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
   <title>innlegg</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>
   <?php include '../components/admin_header.php' ?>
   <section class="read-post">
      <?php
      // Henter innlegget basert på admin-ID og innleggets ID
      $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE admin_id = ? AND id = ?");
      $select_posts->execute([$admin_id, $get_id]);
      if ($select_posts->rowCount() > 0) {
         while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {
            $post_id = $fetch_posts['id'];

            // Henter antall kommentarer og likerklikk for innlegget
            $count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
            $count_post_comments->execute([$post_id]);
            $total_post_comments = $count_post_comments->rowCount();

            $count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");
            $count_post_likes->execute([$post_id]);
            $total_post_likes = $count_post_likes->rowCount();

      ?>
            <form method="post">
               <input type="hidden" name="post_id" value="<?= $post_id; ?>">
               <div class="status" style="background-color:<?php if ($fetch_posts['status'] == 'active') {
                                                               echo 'limegreen';
                                                            } else {
                                                               echo 'coral';
                                                            }; ?>;"><?= $fetch_posts['status']; ?></div>
               <?php if ($fetch_posts['image'] != '') { ?>
                  <img src="../uploaded_img/<?= $fetch_posts['image']; ?>" class="image" alt="">
               <?php } ?>
               <div class="title"><?= $fetch_posts['title']; ?></div>
               <div class="content"><?= $fetch_posts['content']; ?></div>
               <div class="icons">
                  <div class="likes"><i class="fas fa-heart"></i><span><?= $total_post_likes; ?></span></div>
                  <div class="comments"><i class="fas fa-comment"></i><span><?= $total_post_comments; ?></span></div>
               </div>
               <div class="flex-btn">
                  <a href="edit_post.php?id=<?= $post_id; ?>" class="inline-option-btn">Rediger</a>
                  <button type="submit" name="delete" class="inline-delete-btn" onclick="return confirm('Slett dette innlegget?');">Slett</button>
                  <a href="view_posts.php" class="inline-option-btn">Gå tilbake</a>
               </div>
            </form>
      <?php
         }
      } else {
         echo '<p class="empty">Ingen innlegg ble lagt til! <a href="add_posts.php" class="btn" style="margin-top:1.5rem;">Legg til innlegg</a></p>';
      }
      ?>
   </section>
   <section class="comments" style="padding-top: 0;">
      <p class="comment-title">Kommentarer</p>
      <div class="box-container">
         <?php
         // Henter kommentarer knyttet til innlegget
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
         $select_comments->execute([$get_id]);
         if ($select_comments->rowCount() > 0) {
            while ($fetch_comments = $select_comments->fetch(PDO::FETCH_ASSOC)) {
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
                  <form action="" method="POST">
                     <input type="hidden" name="comment_id" value="<?= $fetch_comments['id']; ?>">
                     <button type="submit" class="inline-delete-btn" name="slett kommentar" onclick="return confirm('Slett denne kommentaren?');">Slett kommentar</button>
                  </form>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">Ingen kommentarer lagt til!</p>';
         }
         ?>
      </div>
   </section>
   <script src="../js/admin_script.js"></script>
</body>

</html>
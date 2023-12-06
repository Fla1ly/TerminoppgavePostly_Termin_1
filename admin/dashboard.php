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

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashbord</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>
   <?php include '../components/admin_header.php' ?>
   <section class="dashboard">
      <h1 class="heading">Dashbord</h1>
      <div class="box-container">
         <!-- Box for velkomstmelding og profiloppdatering -->
         <div class="box">
            <h3>Velkommen!</h3>
            <p><?= $fetch_profile['name']; ?></p>
            <a href="update_profile.php" class="btn">Oppdater profil</a>
         </div>

         <!-- Box for antall innlegg -->
         <div class="box">
            <?php
            // Henter antall innlegg fra databasen basert på admin-ID
            $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE admin_id = ?");
            $select_posts->execute([$admin_id]);
            $numbers_of_posts = $select_posts->rowCount();
            ?>
            <h3><?= $numbers_of_posts; ?></h3>
            <p>Innlegg lagt til</p>
            <a href="add_posts.php" class="btn">Legg til nytt innlegg</a>
         </div>

         <!-- Box for antall aktive innlegg -->
         <div class="box">
            <?php
            // Henter antall aktive innlegg fra databasen basert på admin-ID og status
            $select_active_posts = $conn->prepare("SELECT * FROM `posts` WHERE admin_id = ? AND status = ?");
            $select_active_posts->execute([$admin_id, 'active']);
            $numbers_of_active_posts = $select_active_posts->rowCount();
            ?>
            <h3><?= $numbers_of_active_posts; ?></h3>
            <p>Aktive innlegg</p>
            <a href="view_posts.php" class="btn">Se innlegg</a>
         </div>

         <!-- Box for antall deaktive innlegg -->
         <div class="box">
            <?php
            // Henter antall deaktive innlegg fra databasen basert på admin-ID og status
            $select_deactive_posts = $conn->prepare("SELECT * FROM `posts` WHERE admin_id = ? AND status = ?");
            $select_deactive_posts->execute([$admin_id, 'deactive']);
            $numbers_of_deactive_posts = $select_deactive_posts->rowCount();
            ?>
            <h3><?= $numbers_of_deactive_posts; ?></h3>
            <p>Deaktive innlegg</p>
            <a href="view_posts.php" class="btn">Se innlegg</a>
         </div>

         <!-- Box for antall vanlige brukere -->
         <div class="box">
            <?php
            // Henter antall vanlige brukere fra databasen
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $numbers_of_users = $select_users->rowCount();
            ?>
            <h3><?= $numbers_of_users; ?></h3>
            <p>Vanlige brukere</p>
            <a href="users_accounts.php" class="btn">Se brukere</a>
         </div>

         <!-- Box for antall creator kontoer -->
         <div class="box">
            <?php
            // Henter antall creator kontoer fra databasen
            $select_admins = $conn->prepare("SELECT * FROM `admin`");
            $select_admins->execute();
            $numbers_of_admins = $select_admins->rowCount();
            ?>
            <h3><?= $numbers_of_admins; ?></h3>
            <p>Creator kontoer</p>
            <a href="admin_accounts.php" class="btn">Se creators</a>
         </div>

         <!-- Box for antall kommentarer på egne innlegg -->
         <div class="box">
            <?php
            // Henter antall kommentarer på egne innlegg fra databasen basert på admin-ID
            $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE admin_id = ?");
            $select_comments->execute([$admin_id]);
            $numbers_of_comments = $select_comments->rowCount();
            ?>
            <h3><?= $numbers_of_comments; ?></h3>
            <p>Kommentarer på dine innlegg</p>
            <a href="comments.php" class="btn">Se kommentarer</a>
         </div>

         <!-- Box for antall likes på egne innlegg -->
         <div class="box">
            <?php
            // Henter antall likes på egne innlegg fra databasen basert på admin-ID
            $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE admin_id = ?");
            $select_likes->execute([$admin_id]);
            $numbers_of_likes = $select_likes->rowCount();
            ?>
            <h3><?= $numbers_of_likes; ?></h3>
            <p>Likes på dine innlegg</p>
            <a href="view_posts.php" class="btn">Se innlegg</a>
         </div>
      </div>
   </section>
   <script src="../js/admin_script.js"></script>
</body>

</html>
<?php

// Inkluderer tilkoblingsfilen for å koble til databasen
include '../components/connect.php';

// Starter sesjonen for å kunne lagre admininformasjon
session_start();

// Henter admin-ID fra sesjonen
$admin_id = $_SESSION['admin_id'];

// Sjekker om admin-ID er satt i sesjonen, ellers omdirigerer til innloggingssiden for administrator
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
   <title>brukere</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>
   <?php include '../components/admin_header.php' ?>
   <section class="accounts">
      <h1 class="heading">Vanlige brukere</h1>
      <div class="box-container">
         <?php
         // Henter informasjon om alle brukere fra databasen
         $select_account = $conn->prepare("SELECT * FROM `users`");
         $select_account->execute();

         // Sjekker om det er brukere tilgjengelige
         if ($select_account->rowCount() > 0) {
            while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
               // Henter brukerens ID og teller antall kommentarer og likerklikk
               $user_id = $fetch_accounts['id'];
               $count_user_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
               $count_user_comments->execute([$user_id]);
               $total_user_comments = $count_user_comments->rowCount();
               $count_user_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
               $count_user_likes->execute([$user_id]);
               $total_user_likes = $count_user_likes->rowCount();
         ?>
               <div class="box">
                  <p>Bruker ID: <span><?= $user_id; ?></span></p>
                  <p>Brukernavn: <span><?= $fetch_accounts['name']; ?></span></p>
                  <p>Totalt kommentarer: <span><?= $total_user_comments; ?></span></p>
                  <p>Totalt likerklikk: <span><?= $total_user_likes; ?></span></p>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">Ingen brukere tilgjengelige.</p>';
         }
         ?>
      </div>
   </section>
   <script src="../js/admin_script.js"></script>
</body>

</html>
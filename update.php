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

// Sjekker om skjemaet for oppdatering er sendt inn
if (isset($_POST['submit'])) {

   // Henter og filtrerer navn fra skjemaet
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   // Henter og filtrerer e-post fra skjemaet
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   // Oppdaterer navn hvis det ikke er tomt
   if (!empty($name)) {
      $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $user_id]);
   }

   // Sjekker e-post og oppdaterer hvis den ikke er tom og ikke allerede i bruk
   if (!empty($email)) {
      $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_email->execute([$email]);
      if ($select_email->rowCount() > 0) {
         $message[] = 'E-postadressen er allerede i bruk!';
      } else {
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $user_id]);
      }
   }

   // Variabler for håndtering av passordoppdatering
   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_prev_pass = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
   $select_prev_pass->execute([$user_id]);
   $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   // Sjekker forrige passord og utfører passordoppdatering hvis alt er gyldig
   if ($old_pass != $empty_pass) {
      if ($old_pass != $prev_pass) {
         $message[] = 'Forrige passord er ikke korrekt!';
      } elseif ($new_pass != $confirm_pass) {
         $message[] = 'Bekreft passordet stemmer ikke!';
      } else {
         if ($new_pass != $empty_pass) {
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $user_id]);
            $message[] = 'Passordet er oppdatert!';
         } else {
            $message[] = 'Det nye passordet kan ikke være tomt!';
         }
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>oppdater profil</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <?php include 'components/user_header.php'; ?>
   <section class="form-container">
      <form action="" method="post">
         <h3>oppdater profil</h3>
         <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" class="box" maxlength="50">
         <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="old_pass" placeholder="ditt gamle passord" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="new_pass" placeholder="ditt nye passord" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="confirm_pass" placeholder="bekreft ditt nye passord" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="oppdater nå" name="submit" class="btn">
      </form>
   </section>
   <script src="js/script.js"></script>
</body>

</html>
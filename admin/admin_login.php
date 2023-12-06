<?php

// Inkluderer tilkoblingsfilen for å koble til databasen
include '../components/connect.php';

// Starter sesjonen for å kunne lagre admininformasjon
session_start();

// Håndterer innloggingsskjemaet når det blir sendt
if (isset($_POST['submit'])) {

   // Henter og filtrerer brukernavn og passord fra skjemaet
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   // Henter admininformasjon fra databasen basert på brukernavn og passord
   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);

   // Sjekker om det er en match i databasen
   if ($select_admin->rowCount() > 0) {
      // Henter admin-ID og lagrer den i sesjonen
      $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
      $_SESSION['admin_id'] = $fetch_admin_id['id'];

      // Omdirigerer til admin-dashboard etter vellykket innlogging
      header('location:dashboard.php');
   } else {
      // Melding vises hvis innloggingen mislykkes
      $message[] = 'Feil brukernavn eller passord!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>logg inn</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body style="padding-left: 0 !important;">
   <?php
   // Viser meldinger hvis det er noen
   if (isset($message)) {
      foreach ($message as $message) {
         echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
      }
   }
   ?>
   <!-- HTML-seksjon for innloggingsskjemaet -->
   <section class="form-container">
      <form action="" method="POST">
         <p>Standard brukernavn = <span>creator</span> & passord = <span>pass</span></p>
         <h3>Logg inn</h3>
         <input type="text" name="name" maxlength="20" required placeholder="Brukernavn" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="pass" maxlength="20" required placeholder="Passord" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="Logg inn" name="submit" class="btn">
      </form>
   </section>
</body>

</html>
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

// Sjekker om skjemaet er sendt inn (når brukeren trykker på "Logg inn"-knappen)
if (isset($_POST['submit'])) {

   // Henter og sanerer e-postadresse fra skjemaet
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   // Henter og hasher passordet ved hjelp av SHA-1 (Merk: anbefales å bruke sikrere hash-algoritmer som bcrypt)
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   // Forbereder og utfører spørringen for å hente brukerinformasjon basert på e-post og passord
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);

   // Henter raden med brukerinformasjon fra resultatsettet
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   // Sjekker om brukeren ble funnet og passordet er korrekt
   if ($select_user->rowCount() > 0) {
      // Lagrer bruker-ID i sesjonen og sender brukeren til hjemmesiden
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
   } else {
      // Hvis brukeren ikke ble funnet, legg til feilmelding i meldingsarrayet
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
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <?php include 'components/user_header.php'; ?>
   <section class="form-container">
      <!-- Skjema for innlogging -->
      <form action="" method="post">
         <h3>Logg inn</h3>
         <!-- Inntastingsfelt for e-post -->
         <input type="email" name="email" required placeholder="E-post" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <!-- Inntastingsfelt for passord -->
         <input type="password" name="pass" required placeholder="Passord" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <!-- Innsending av skjema -->
         <input type="submit" value="Logg inn" name="submit" class="btn">
         <!-- Lenke for registrering hvis brukeren ikke har konto -->
         <p>Har du ikke konto? <a href="register.php">Registrer deg</a></p>
      </form>
   </section>
   <!-- Inkluderer JavaScript-filen -->
   <script src="js/script.js"></script>
</body>

</html>
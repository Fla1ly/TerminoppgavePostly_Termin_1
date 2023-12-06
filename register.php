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

// Sjekker om skjemaet for registrering er sendt inn
if (isset($_POST['submit'])) {

   // Henter og filtrerer navn, e-post og passord fra skjemaet
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   // Forbereder og utfører spørring for å sjekke om e-posten allerede eksisterer i databasen
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   // Sjekker om e-posten allerede eksisterer
   if ($select_user->rowCount() > 0) {
      $message[] = 'E-posten eksisterer allerede!';
   } else {
      // Sjekker om passordene matcher
      if ($pass != $cpass) {
         $message[] = 'Bekreft passordet stemmer ikke!';
      } else {
         // Setter inn ny bruker i databasen og logger inn brukeren
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
         $select_user->execute([$email, $pass]);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);

         // Sjekker om brukeren ble lagt til og logget inn
         if ($select_user->rowCount() > 0) {
            $_SESSION['user_id'] = $row['id'];
            header('location:home.php');
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
   <title>registrer</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <?php include 'components/user_header.php'; ?>
   <!-- Seksjon for skjemaet for registrering -->
   <section class="form-container">
      <form action="" method="post">
         <h3>Registrer nå</h3>
         <!-- Inndatafelt for navn, e-post og passord -->
         <input type="text" name="name" required placeholder="Navn" class="box" maxlength="50">
         <input type="email" name="email" required placeholder="E-post" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="pass" required placeholder="Passord" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="cpass" required placeholder="Bekreft passord" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <!-- Knapp for å sende inn skjemaet -->
         <input type="submit" value="Registrer deg" name="submit" class="btn">
         <!-- Lenke for å logge inn hvis brukeren allerede har en konto -->
         <p>Har du allerede en konto? <a href="login.php">Logg inn</a></p>
      </form>
   </section>
   <!-- Inkluderer JavaScript-filen -->
   <script src="js/script.js"></script>
</body>

</html>
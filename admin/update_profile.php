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

// Sjekker om skjemainnsendingen er for å oppdatere profilen
if (isset($_POST['submit'])) {

   // Henter og filtrerer brukernavnet fra skjemainndata
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   // Sjekker om brukernavnet er tomt
   if (!empty($name)) {
      // Forbereder en spørring for å sjekke om brukernavnet allerede eksisterer
      $select_name = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
      $select_name->execute([$name]);

      // Sjekker om brukernavnet allerede eksisterer
      if ($select_name->rowCount() > 0) {
         $message[] = 'Brukernavnet er allerede i bruk!';
      } else {
         // Forbereder og utfører SQL-spørringen for å oppdatere brukernavnet i databasen
         $update_name = $conn->prepare("UPDATE `admin` SET name = ? WHERE id = ?");
         $update_name->execute([$name, $admin_id]);
      }
   }

   // Definerer en konstant for tom passordstreng
   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';

   // Henter det gamle passordet fra databasen
   $select_old_pass = $conn->prepare("SELECT password FROM `admin` WHERE id = ?");
   $select_old_pass->execute([$admin_id]);
   $fetch_prev_pass = $select_old_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];

   // Henter og filtrerer gammelt, nytt og bekreft passord fra skjemainndata
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   // Sjekker om det gamle passordet er tomt
   if ($old_pass != $empty_pass) {
      // Sjekker om det gamle passordet er likt det lagrede passordet i databasen
      if ($old_pass != $prev_pass) {
         $message[] = 'Det gamle passordet er ikke riktig!';
      } elseif ($new_pass != $confirm_pass) {
         $message[] = 'Bekreftelsen av passordet stemmer ikke!';
      } else {
         // Sjekker om det nye passordet er tomt
         if ($new_pass != $empty_pass) {
            // Forbereder og utfører SQL-spørringen for å oppdatere passordet i databasen
            $update_pass = $conn->prepare("UPDATE `admin` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $admin_id]);
            $message[] = 'Passordet er oppdatert!';
         } else {
            $message[] = 'Passordet kan ikke være tomt!';
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
   <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>
   <?php include '../components/admin_header.php' ?>
   <section class="form-container">
      <form action="" method="POST">
         <h3>oppdater profil</h3>
         <input type="text" name="name" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" placeholder="<?= $fetch_profile['name']; ?>">
         <input type="password" name="old_pass" maxlength="20" placeholder="ditt gamle passord" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="new_pass" maxlength="20" placeholder="ditt nye passord" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="confirm_pass" maxlength="20" placeholder="bekreft ditt nye passord" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="oppdater" name="submit" class="btn">
      </form>
   </section>
   <script src="../js/admin_script.js"></script>
</body>

</html>
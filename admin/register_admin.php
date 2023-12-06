<?php

// Inkluderer tilkoblingsfilen for å koble til databasen
include '../components/connect.php';

// Starter sesjonen for å kunne lagre admininformasjon
session_start();

// Henter admin-ID fra sesjonen
$admin_id = $_SESSION['admin_id'];

// Sjekker om admin-ID er satt i sesjonen, ellers omdirigerer til registreringssiden for administrator
if (!isset($admin_id)) {
   header('location:register_admin.php');
};

// Sjekker om skjemainnsendingen er for å registrere en ny administrator
if (isset($_POST['submit'])) {

   // Henter og filtrerer brukernavn, passord og bekreft passord fra skjemainndata
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   // Forbereder en spørring for å sjekke om brukernavnet allerede eksisterer
   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
   $select_admin->execute([$name]);

   // Sjekker om brukernavnet allerede eksisterer
   if ($select_admin->rowCount() > 0) {
      $message[] = 'Brukernavnet eksisterer allerede!';
   } else {
      // Sjekker om passordene er like
      if ($pass != $cpass) {
         $message[] = 'Passordene er ikke like!';
      } else {
         // Forbereder og utfører SQL-spørringen for å legge til en ny administrator i databasen
         $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?,?)");
         $insert_admin->execute([$name, $cpass]);
         $message[] = 'Administrator ble registrert!';
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
   <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>
   <?php include '../components/admin_header.php' ?>
   <section class="form-container">
      <form action="" method="POST">
         <h3>Registrer ny</h3>
         <input type="text" name="name" maxlength="20" required placeholder="Brukernavn" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="pass" maxlength="20" required placeholder="Passord" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="cpass" maxlength="20" required placeholder="Bekreft passord" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="Registrer" name="submit" class="btn">
      </form>
   </section>
   <script src="../js/admin_script.js"></script>

</body>

</html>
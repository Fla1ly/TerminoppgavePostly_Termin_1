<?php

// Inkluderer tilkoblingsfilen for å koble til databasen
include '../components/connect.php';

// Starter sesjonen for å kunne lagre admininformasjon
session_start();

// Henter admin-ID fra sesjonen
$admin_id = $_SESSION['admin_id'];

// Sjekker om admin-ID er satt, ellers omdirigerer til admin-login
if (!isset($admin_id)) {
   header('location:admin_login.php');
}

// Håndterer publisering av innlegg hvis skjemaet er sendt
if (isset($_POST['publish'])) {

   // Henter og filtrerer data fra skjemaet
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $content = $_POST['content'];
   $content = filter_var($content, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $status = 'active';

   // Håndterer opplasting av bilde
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/' . $image;

   // Sjekker om bildet allerede eksisterer og om det er for stort
   $select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ? AND admin_id = ?");
   $select_image->execute([$image, $admin_id]);

   if (isset($image)) {
      if ($select_image->rowCount() > 0 and $image != '') {
         $message[] = 'Navnet til bildet gjentar seg!';
      } elseif ($image_size > 2000000) {
         $message[] = 'Bildet er for stort!';
      } else {
         move_uploaded_file($image_tmp_name, $image_folder);
      }
   } else {
      $image = '';
   }

   // Sjekker om bildet allerede eksisterer og om det er tomt
   if ($select_image->rowCount() > 0 and $image != '') {
      $message[] = 'Lag et nytt navn for bildet!';
   } else {
      // Legger til nytt innlegg i databasen
      $insert_post = $conn->prepare("INSERT INTO `posts`(admin_id, name, title, content, category, image, status) VALUES(?,?,?,?,?,?,?)");
      $insert_post->execute([$admin_id, $name, $title, $content, $category, $image, $status]);
      $message[] = 'Innlegg ble publisert!';
   }
}

// Håndterer lagring av utkast hvis skjemaet er sendt
if (isset($_POST['draft'])) {

   // Henter og filtrerer data fra skjemaet
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $content = $_POST['content'];
   $content = filter_var($content, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $status = 'inactive';

   // Håndterer opplasting av bilde
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/' . $image;

   // Sjekker om bildet allerede eksisterer og om det er for stort
   $select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ? AND admin_id = ?");
   $select_image->execute([$image, $admin_id]);

   if (isset($image)) {
      if ($select_image->rowCount() > 0 and $image != '') {
         $message[] = 'Navnet til bildet gjentar seg!';
      } elseif ($image_size > 2000000) {
         $message[] = 'Bildet er for stort!';
      } else {
         move_uploaded_file($image_tmp_name, $image_folder);
      }
   } else {
      $image = '';
   }

   // Sjekker om bildet allerede eksisterer og om det er tomt
   if ($select_image->rowCount() > 0 and $image != '') {
      $message[] = 'Lag et nytt navn for bildet!';
   } else {
      // Legger til nytt innlegg i databasen
      $insert_post = $conn->prepare("INSERT INTO `posts`(admin_id, name, title, content, category, image, status) VALUES(?,?,?,?,?,?,?)");
      $insert_post->execute([$admin_id, $name, $title, $content, $category, $image, $status]);
      $message[] = 'Utkast ble lagret!';
   }
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
   <section class="post-editor">
      <h1 class="heading">Legg til nytt innlegg</h1>
      <form action="" method="post" enctype="multipart/form-data">
         <input type="hidden" name="name" value="<?= $fetch_profile['name']; ?>">
         <p>Innleggstittel<span>*</span></p>
         <input type="text" name="title" maxlength="100" required placeholder="Legg til tittel" class="box">
         <p>Innholdsinnlegg<span>*</span></p>
         <textarea name="content" class="box" required maxlength="10000" placeholder="Skriv ditt innhold..." cols="30" rows="10"></textarea>
         <p>Innleggskategori<span>*</span></p>
         <select name="category" class="box" required>
            <option value="" selected disabled>-- Velg kategori* </option>
            <option value="nature">Natur</option>
            <option value="education">Utdanning</option>
            <option value="pets and animals">Dyr</option>
            <option value="technology">Teknologi</option>
            <option value="fashion">Fashion</option>
            <option value="entertainment">Underholdning</option>
            <option value="movies and animations">Filmer</option>
            <option value="gaming">Spill</option>
            <option value="music">Musikk</option>
            <option value="sports">Sport</option>
            <option value="news">Nyheter</option>
            <option value="travel">Reise</option>
            <option value="comedy">Komedie</option>
            <option value="design and development">Design og utvikling</option>
            <option value="food and drinks">Mat og drikke</option>
            <option value="lifestyle">Livsstil</option>
            <option value="personal">Personlig</option>
            <option value="health and fitness">Helse og trening</option>
            <option value="business">Buisness</option>
            <option value="shopping">Shopping</option>
            <option value="animations">Animasjoner</option>
         </select>
         <p>Bannerbilde til innlegget</p>
         <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
         <div class="flex-btn">
            <input type="submit" value="Publiser innlegg" name="publish" class="btn">
            <input type="submit" value="Lagre utkast" name="draft" class="option-btn">
         </div>
      </form>
   </section>
   <script src="../js/admin_script.js"></script>
</body>

</html>
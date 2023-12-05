<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['publish'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $content = $_POST['content'];
   $content = filter_var($content, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $status = 'active';

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/' . $image;

   $select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ? AND admin_id = ?");
   $select_image->execute([$image, $admin_id]);

   if (isset($image)) {
      if ($select_image->rowCount() > 0 and $image != '') {
         $message[] = 'navnet til bildet repeterer seg!';
      } elseif ($image_size > 2000000) {
         $message[] = 'bildet er for stort!';
      } else {
         move_uploaded_file($image_tmp_name, $image_folder);
      }
   } else {
      $image = '';
   }

   if ($select_image->rowCount() > 0 and $image != '') {
      $message[] = 'lag et nytt navn for bildet!';
   } else {
      $insert_post = $conn->prepare("INSERT INTO `posts`(admin_id, name, title, content, category, image, status) VALUES(?,?,?,?,?,?,?)");
      $insert_post->execute([$admin_id, $name, $title, $content, $category, $image, $status]);
      $message[] = 'innlegg ble publisert!';
   }
}

if (isset($_POST['draft'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $content = $_POST['content'];
   $content = filter_var($content, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $status = 'deactive';

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/' . $image;

   $select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ? AND admin_id = ?");
   $select_image->execute([$image, $admin_id]);

   if (isset($image)) {
      if ($select_image->rowCount() > 0 and $image != '') {
         $message[] = 'navnet til bildet repeterer seg!';
      } elseif ($image_size > 2000000) {
         $message[] = 'bildet er for stort!';
      } else {
         move_uploaded_file($image_tmp_name, $image_folder);
      }
   } else {
      $image = '';
   }

   if ($select_image->rowCount() > 0 and $image != '') {
      $message[] = 'lag et nytt navn for bildet!';
   } else {
      $insert_post = $conn->prepare("INSERT INTO `posts`(admin_id, name, title, content, category, image, status) VALUES(?,?,?,?,?,?,?)");
      $insert_post->execute([$admin_id, $name, $title, $content, $category, $image, $status]);
      $message[] = 'utkast ble lagret!';
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
      <h1 class="heading">legg til ny innlegg</h1>
      <form action="" method="post" enctype="multipart/form-data">
         <input type="hidden" name="name" value="<?= $fetch_profile['name']; ?>">
         <p>innlegg tittel<span>*</span></p>
         <input type="text" name="title" maxlength="100" required placeholder="legg til tittel" class="box">
         <p>innlegg innhold<span>*</span></p>
         <textarea name="content" class="box" required maxlength="10000" placeholder="skriv ditt innhold..." cols="30" rows="10"></textarea>
         <p>innlegg kategori <span>*</span></p>
         <select name="category" class="box" required>
            <option value="" selected disabled>-- velg kategori* </option>
            <option value="nature">natur</option>
            <option value="education">utdanning</option>
            <option value="pets and animals">dyr</option>
            <option value="technology">teknologi</option>
            <option value="fashion">fashion</option>
            <option value="entertainment">underholdning</option>
            <option value="movies and animations">filmer</option>
            <option value="gaming">spill</option>
            <option value="music">musikk</option>
            <option value="sports">sport</option>
            <option value="news">nyheter</option>
            <option value="travel">reise</option>
            <option value="comedy">komedie</option>
            <option value="design and development">design og utvikling</option>
            <option value="food and drinks">mat og drikke</option>
            <option value="lifestyle">livsstil</option>
            <option value="personal">personlig</option>
            <option value="health and fitness">helse og trening</option>
            <option value="business">buisness</option>
            <option value="shopping">shopping</option>
            <option value="animations">animasjoner</option>
         </select>
         <p>banner bilde til innlegget</p>
         <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
         <div class="flex-btn">
            <input type="submit" value="publiser innlegg" name="publish" class="btn">
            <input type="submit" value="lagre utkast" name="draft" class="option-btn">
         </div>
      </form>
   </section>
   <script src="../js/admin_script.js"></script>
</body>

</html>
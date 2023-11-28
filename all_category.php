<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/like_post.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>category</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->




<section class="categories">

   <h1 class="heading">postkategorier</h1>

   <div class="box-container">
      <div class="box"><span>01</span><a href="category.php?category=nature">natur</a></div>
      <div class="box"><span>02</span><a href="category.php?category=eduction">utdanning</a></div>
      <div class="box"><span>03</span><a href="category.php?category=pets and animals">dyr og dyr</a></div>
      <div class="box"><span>04</span><a href="category.php?category=technology">teknologi</a></div>
      <div class="box"><span>05</span><a href="category.php?category=fashion">mote</a></div>
      <div class="box"><span>06</span><a href="category.php?category=entertainment">underholdning</a></div>
      <div class="box"><span>07</span><a href="category.php?category=movies">filmer</a></div>
      <div class="box"><span>08</span><a href="category.php?category=gaming">spill</a></div>
      <div class="box"><span>09</span><a href="category.php?category=music">musikk</a></div>
      <div class="box"><span>10</span><a href="category.php?category=sports">sport</a></div>
      <div class="box"><span>11</span><a href="category.php?category=news">nyheter</a></div>
      <div class="box"><span>12</span><a href="category.php?category=travel">reise</a></div>
      <div class="box"><span>13</span><a href="category.php?category=comedy">komedie</a></div>
      <div class="box"><span>14</span><a href="category.php?category=design and development">design og utvikling</a></div>
      <div class="box"><span>15</span><a href="category.php?category=food and drinks">mat og drikke</a></div>
      <div class="box"><span>16</span><a href="category.php?category=lifestyle">livsstil</a></div>
      <div class="box"><span>17</span><a href="category.php?category=health and fitness">helse og trening</a></div>
      <div class="box"><span>18</span><a href="category.php?category=business">forretning</a></div>
      <div class="box"><span>19</span><a href="category.php?category=shopping">shopping</a></div>
      <div class="box"><span>20</span><a href="category.php?category=animations">animasjoner</a></div>
   </div>

</section>


















<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
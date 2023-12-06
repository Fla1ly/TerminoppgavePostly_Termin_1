<?php
// Viser meldinger hvis de er satt
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

<header class="header">
   <a href="dashboard.php" class="logo">Creator<span>Panel</span></a>
   <div class="profile">
      <?php
      // Henter profilinformasjon om den påloggede admin-brukeren
      $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
      $select_profile->execute([$admin_id]);
      $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <p><?= $fetch_profile['name']; ?></p>
      <a href="update_profile.php" class="btn">Oppdater profil</a>
   </div>
   <nav class="navbar">
      <a href="../home.php"><i class="fas fa-home"></i> <span>Hjem</span></a>
      <a href="dashboard.php"><i class="fas fa-dashboard"></i> <span>Dashboard</span></a>
      <a href="add_posts.php"><i class="fas fa-pen"></i> <span>Lag innlegg</span></a>
      <a href="view_posts.php"><i class="fas fa-eye"></i> <span>Vis innlegg</span></a>
      <a href="admin_accounts.php"><i class="fas fa-user"></i> <span>Kontoer</span></a>
      <a href="../components/admin_logout.php" style="color:var(--red);" onclick="return confirm('Logg ut?');"><i class="fas fa-right-from-bracket"></i><span>Logg ut</span></a>
   </nav>
   <div class="flex-btn">
      <a href="admin_login.php" class="option-btn">Logg inn</a>
      <a href="register_admin.php" class="option-btn">Registrer</a>
   </div>
</header>
<div id="menu-btn" class="fas fa-bars"></div>
<?php

// Sjekker om skjemaet for å like et innlegg er sendt
if (isset($_POST['like_post'])) {

   // Sjekker om bruker er logget inn
   if ($user_id != '') {

      // Henter innleggets ID og admin ID fra skjemaet
      $post_id = $_POST['post_id'];
      $post_id = filter_var($post_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $admin_id = $_POST['admin_id'];
      $admin_id = filter_var($admin_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      // Sjekker om brukeren allerede har likt innlegget
      $select_post_like = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ? AND user_id = ?");
      $select_post_like->execute([$post_id, $user_id]);

      // Hvis brukeren allerede har likt innlegget, fjern liket
      if ($select_post_like->rowCount() > 0) {
         $remove_like = $conn->prepare("DELETE FROM `likes` WHERE post_id = ?");
         $remove_like->execute([$post_id]);
         $message[] = 'slettet fra likte';
      } else {
         // Hvis brukeren ikke har likt innlegget, legg til liket i databasen
         $add_like = $conn->prepare("INSERT INTO `likes`(user_id, post_id, admin_id) VALUES(?,?,?)");
         $add_like->execute([$user_id, $post_id, $admin_id]);
         $message[] = 'lagt til i likte';
      }
   } else {
      // Hvis brukeren ikke er logget inn, gi beskjed om å logge inn først
      $message[] = 'logg inn først!';
   }
}

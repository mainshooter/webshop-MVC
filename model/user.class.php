<?php

 class user {
   public function regiserUser() {
     
   }

   private function checkLoginToken() {
     // Checks if the user has the same login token
     // Returns true or false
     if (ISSET($_SESSION['logintoken'])) {
       if ($_SESSION['logintoken'] === 'h79vr29hu3pqhf-249p;gae') {
         return(true);
       }
       else {
         return(false);
       }
     }
     else {
       return(false);
     }
   }

   public function isLogedIn() {
     return($this->checkLoginToken());
   }
 }


?>

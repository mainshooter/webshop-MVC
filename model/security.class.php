<?php

  class security {

    public function checkInput($data) {
      // This funcion checks the input
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      $data = htmlentities($data);
      return ($data);
    }
  }


?>

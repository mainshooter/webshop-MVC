<?php

  class security {

    /**
     * Removes any HTML and javascript
     * @param  [string] $data [The data that needs the be checked]
     * @return [string]       [With the removed HTML and Javascript]
     */
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

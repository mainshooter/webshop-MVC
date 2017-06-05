<?php

  class Translate {
    public function translateEngToNL($word) {
      $translation;
      switch ($word) {
        case 'paid':
          $translation = 'betaald';
          break;
      }
      return($translation);
    }
  }


?>

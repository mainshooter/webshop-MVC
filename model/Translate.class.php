<?php

  class Translate {

    /**
     * Translate a word
     * @param  [string] $word [The word we want to translate]
     * @return [string]       [The translated word]
     */
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

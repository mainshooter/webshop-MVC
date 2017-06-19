<?php

  class ShoppingcardView {

    public function generateOptionNumbers($productID, $amount) {
      // Generates a select input field.
      // When the option is the same to the number we got for the option input
      // We set that as selected
      if ($amount > 10) {
        $maxNumbers = intval($amount + 5);
      }
      else {
          $maxNumbers = 10;
      }

      $selectField = '<select onchange="shoppingcard.update(\'' . $productID . '\', this.value);">';
      for ($i=1; $i < $maxNumbers; $i++) {
        if ($amount == $i) {
          $selectField .= '<option value="' . $i .'" selected>' . $i . '</option>';
        }
        else {
          $selectField .= '<option value="' . $i .'">' . $i . '</option>';
        }
      }
      $selectField .= '</select>';
      return($selectField);
    }
  }

?>

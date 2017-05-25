<?php

  class ShoppingcardView {
    public function displayShoppingCard($result, $amount, $productTotal) {
      $view = '';
      foreach ($result as $key) {
        $view .= '
          <div class="col-2">&nbsp;</div>
          <div class="col-8 product winkelmandje-height-center">
            <img class="col-1" src="' . $key['pad'] . $key['filenaam'] . '">
            <h2 class="col-5 left-text"><a href="?op=details&productID=' . $key['idProduct'] . '">' . $key['naam'] . '</a></h2>
            <p class="col-2 left-text">&euro;' . $key['prijs'] . '</p>';
            $view .= $this->generateOptionNumbers($key['idProduct'], $amount);
            $view .= '<i class="fa fa-trash-o col-1" aria-hidden="true" style="margin-top: 0.5em;" onclick="shoppingcard.remove(' . $key['idProduct'] . ')"></i>
            <p class="col-2">Totaal: &euro;' . $productTotal . '</p>
          </div>
          <div class="col-2">&nbsp;</div>
          <div class="col-12"></div>
        ';
      }
      return($view);
    }

    public function generateOptionNumbers($productID, $amount) {
      // Generates a select input field.
      // When the option is the same to the number we got for the option input
      // We set that as selected
      $selectField = '<select onchange="shoppingcard.update(\'' . $productID . '\', this.value);" class="col-1">';
      for ($i=0; $i < 10; $i++) {
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

<?php

  class ShoppingcardView {
    public function displayShoppingCard($result, $amount, $productTotal) {
      $view = '';
      foreach ($result as $key) {
        $view .= '
          <div class="col-2 col-m-0">&nbsp;</div>
          <div class="col-8 col-m-12 product winkelmandje-height-center">
              <div class="col-1 col-m-1 webshop-img"><img class="col-12 webshop-img" src="' . $key['pad'] . $key['filenaam'] . '"></div>
              <div class="col-5 col-m-5"><h2 class="left-text"><a href="?op=details&productID=' . $key['idProduct'] . '">' . $key['naam'] . '</a></h2></div>
              <div class="col-1 col-m-1" style="margin-top: 0.5em;">&euro;' . str_replace('.', ',', $key['prijs']) . '</div>
              <div class="col-1 col-m-1 right-text" style="margin-top: 0.5em;">Aantal: </div>
              <div class="col-1 col-m-2" style="margin-top: 0.5em;">';
              $view .= $this->generateOptionNumbers($key['idProduct'], $amount);
              $view .='</div>
              <div class="col-1 col-m-1" style="margin-top: 0.5em;"><i class="fa fa-trash-o" aria-hidden="true" onclick="shoppingcard.remove(' . $key['idProduct'] . ')"></i></div>
              <div class="col-2 col-m-1" style="margin-top: 0.5em;">Totaal: &euro;' . $productTotal . '</div>
          </div>
          <div class="col-2 col-m-0">&nbsp;</div>
          <div class="col-12"></div>
        ';
      }
      return($view);
    }

    public function generateOptionNumbers($productID, $amount) {
      // Generates a select input field.
      // When the option is the same to the number we got for the option input
      // We set that as selected
      $selectField = '<select onchange="shoppingcard.update(\'' . $productID . '\', this.value);">';
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

<?php

  class Productview {

    public function createProductDetails($result) {
      $detail = '';
      foreach ($result as $key) {
        $detail .= '
          <div class="col-1"></div>
          <div class="col-10 product_details">
            <h2 class="col-12">' . $key['naam'] . '</h2>
            <img class="col-3" src="' . $key['pad'] . $key['filenaam'] . '" />
            <div class="col-9">' . $key['beschrijving'] . '</div>
            <p class="col-1">&euro;' . str_replace('.', ',', $key['prijs']) . '</p>
            <button type="button" onclick="shoppingcard.add(' . $key['idProduct'] . ')"><i class="fa fa-cart-plus" aria-hidden="true"></i> Bestel</button>
          </div>
          <div class="col-1"></div>
        ';
    }
    return($detail);
  }
}

?>

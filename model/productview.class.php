<?php

  class Productview {

    public function createNewProductsView($result) {
      $products = '';
      $products .= '<div class="col-2 col-m-1"></div>';
      foreach ($result as $key) {
        $products .= '
        <div class="col-4 col-m-4 product">
          <a href="?op=details&productID=' . $key['idProduct'] . '">
            <div class="col-12 col-m-12">
              <img class="product_img" src="' . $key['pad'] . $key['filenaam'] . '" />
            </div>
            <h2>' . $key['naam'] . '</h2>
          </a>
          <p>&euro;' . str_replace('.', ',', $key['prijs']) . '</p>
          <i class="fa fa-cart-arrow-down" aria-hidden="true" onclick="shoppingcard.add(' . $key['idProduct'] . ');shoppingcard.goTo();"></i>
        </div>
        ';
      }
      $products .= '<div class="col-2 col-m-1"></div>';
      return($products);
    }

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
            <i class="fa fa-cart-plus col-5" aria-hidden="true" onclick="shoppingcard.add(' . $key['idProduct'] . ')"></i>
          </div>
          <div class="col-1"></div>
        ';
    }
    return($detail);
  }
}

?>

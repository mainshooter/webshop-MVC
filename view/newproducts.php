<?php
    echo '<div class="col-2 col-m-1"></div>';
  foreach ($newestProducts as $key) {
      echo '
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
    echo '<div class="col-2 col-m-1"></div>';

?>

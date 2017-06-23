<?php

    foreach ($productDetails as $key) {
       echo '
        <div class="col-1"></div>
        <div class="col-10 product_details">
          <h2 class="col-12">' . $key['naam'] . '</h2>
          <div class="col-3">
            <img class="col-12" src="' . $key['pad'] . $key['filenaam'] . '" />
            <button type="button" class="col-12" onclick="shoppingcard.add(' . $key['idProduct'] . ')"><i class="fa fa-cart-plus" aria-hidden="true"></i> Bestel</button>
            <p class="col-1">&euro;' . str_replace('.', ',', $key['prijs']) . '</p>
          </div>
          <div class="col-9">' . html_entity_decode(html_entity_decode($key['beschrijving'])) . '</div>
        </div>
        <div class="col-1"></div>
      ';
  }
?>

<div class="new-products">
<h2 class="col-12">Onze nieuwste VR-brillen</h2>
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

        <button type="button" onclick="shoppingcard.add(' . $key['idProduct'] . ');shoppingcard.goTo();">Bestellen</button>
        <a href="?op=details&productID=' . $key['idProduct'] . '"><button type="button">Details</button></a>
      </div>
      ';
      // <i class="fa fa-cart-arrow-down" aria-hidden="true" onclick="shoppingcard.add(' . $key['idProduct'] . ');shoppingcard.goTo();"></i>
    }
    echo '<div class="col-2 col-m-1"></div>';

?>
</div>

<?php
  // Displays all products
    // echo $productview;
    $teller = 0;

    foreach ($products as $key) {
      if ($teller == 0) {
        echo "<div class='row'>";
        echo '<div class="col-1 col-m-1">&nbsp;</div>';
        echo "<div class='col-10'>";
      }
        echo '
        <div class="col-3 col-m-3 product">
          <a href="?op=details&productID=' . $key['idProduct'] . '">
            <div class="col-12 col-m-12">
              <img class="product_img" src="' . $key['pad'] . $key['filenaam'] . '" />
            </div>
            <h2>' . $key['naam'] . '</h2>
          </a>
          <p>&euro;' . str_replace('.', ',', $key['prijs']) . '</p>
          <button type="button" onclick="shoppingcard.add(' . $key['idProduct'] . ');shoppingcard.goTo();">Bestellen</button>
          <button type="button"><a href="?op=details&productID=' . $key['idProduct'] . '">Details</button>
        </div>
        ';
        if ($teller == 3) {
          echo "</div>";
          echo '<div class="col-1 col-m-1">&nbsp;</div>';
          echo "</div>";
          $teller = 0;
        }
        else {
        $teller++;
        }
      }
      if ($teller < 4) {
        // To fix when we don't and at 3
        // We end the row and the colls the fix the grid view
        echo "</div>";
        echo '<div class="col-1 col-m-1">&nbsp;</div>';
        echo "</div>";
      }
?>

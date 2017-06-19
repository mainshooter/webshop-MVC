<?php

$teller = 0;
$tellerForTimesPlaced = 0;
    foreach ($product_details as $row) {
      foreach ($row as $key) {
        if ($tellerForTimesPlaced == 0) {
          echo "<div class='row'>";
        }
        echo  '<div class="col-4 shoppingcard_content">
          <img class="webshop-img" src="' . $key['pad'] . $key['filenaam'] . '">
          <h2><a href="?op=details&productID=' . $key['idProduct'] . '">' . $key['naam'] . '</a> <i class="fa fa-trash-o" aria-hidden="true" onclick="shoppingcard.remove(' . $key['idProduct'] . ')"></i></h2>
          <p>&euro;' . str_replace('.', ',', $key['prijs']) . '</p>
          <div>
            Aantal: ' . $product_details_aantal[$teller]['aantal'] . '
          </div>
          <div></div>
          <div>Totaal: &euro;' . str_replace('.', ',', $product_details_price[$teller]['productTotal']) . '</div>
        </div>
      ';
      $teller++;
      if ($tellerForTimesPlaced == 2) {
          echo "</div>";
          $tellerForTimesPlaced = 0;
        }
        else {
          $tellerForTimesPlaced++;
        }
      }
    }
    echo "<div class='col-12'><h2>BTW: &euro;" . $BTWPrice . "</h2>";
    echo "<h2>Exclusief BTW: &euro;" . $priceWithoutBTW . "</h2>";
    echo "<h2>Totaal: &euro;" . $totalPrice . "</h2></div>";
    echo "<div class='col-12'></div><div class='col-12'><a href='?op=createOrder'><button id='order' type='button'>Bestellen!</button></a></div>";
?>

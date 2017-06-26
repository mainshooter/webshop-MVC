<?php
// To place 3 items next to each other
echo "<div class='col-1'></div>";
echo "<table class='col-10'>";
    foreach ($product_details as $row) {
      foreach ($row as $key) {
        echo "<tr class='col-12 col-m-12'>
              <td class='col-1 col-m-1'><img class='col-12' src='" . $key['pad'] . $key['filenaam'] . "'></td>
              <td class='col-5 col-m-5'><h2><a href='?op=details&productID=" . $key['idProduct'] . "'>" . $key['naam'] . "</a></h2></td>
              <td class='col-1 col-m-1'><i class='fa fa-trash-o' aria-hidden='true' onclick='shoppingcard.remove(" . $key['idProduct'] . ")'></i></td>
              <td class='col-1 col-m-1'>&euro;" . str_replace('.', ',', $key['prijs']) . "</td>
              <td class='col-2 col-m-2'>Aantal: " . $product_details_aantal[$teller]['aantal'] . "</td>
              <td class='col-2 col-m-2'>Totaal: &euro;" . str_replace('.', ',', $product_details_price[$teller]['productTotal']) . "</td>
              </tr>
              ";
      }
    }
    echo "</table>";
    echo "<div class='col-1'></div>";
    echo "<h2 class='col-12'>Exclusief BTW: &euro;" . $priceWithoutBTW . "</h2>";
    echo "<div class='col-12'><h2>BTW: &euro;" . $BTWPrice . "</h2>";
    echo "<h2 class='col-12'>Totaal: &euro;" . $totalPrice . "</h2></div>";
    echo "<div class='col-12'></div><div class='col-12'><a href='?op=createOrder'><button id='order' type='button'><i class='fa fa-money' aria-hidden='true'></i> Bestellen!</button></a></div>";
?>

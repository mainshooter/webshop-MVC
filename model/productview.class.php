<?php

  class Productview {
    public function createProductsView($result) {
      $products = '<table>';
      foreach ($result as $key) {
        $products .= '
        <tr class="col-3 col-m-4 product">
          <a href="?op=details&productID=' . $key['idProduct'] . '">
            <td class="col-12 col-m-12">
              <img class="product_img" src="' . $key['pad'] . $key['filenaam'] . '" />
            </td>
            <td class="col-12 col-m-12"><h2>' . $key['naam'] . '</h2></td>
          </a>
          <td class="col-12 col-m-12"><p>&euro;' . str_replace('.', ',', $key['prijs']) . '</p>
          <i class="fa fa-cart-arrow-down" aria-hidden="true" onclick="shoppingcard.add(' . $key['idProduct'] . ');shoppingcard.goTo();"></i></td>
        </tr>
        ';
      }
      $products .= '</table>';
      return($products);
    }

    public function createProductDetails($result) {
      $detail = '';
      foreach ($result as $key) {
        $detail .= '
          <div class="col-10 product_details">
            <h2 class="col-12">' . $key['naam'] . '</h2>
            <img class="col-3" src="' . $key['pad'] . $key['filenaam'] . '" />
            <div class="col-9">' . $key['beschrijving'] . '</div>
            <p class="col-1">&euro;' . str_replace('.', ',', $key['prijs']) . '</p>
            <i class="fa fa-cart-plus col-5" aria-hidden="true" onclick="shoppingcard.add(' . $key['idProduct'] . ')"></i>
          </div>
        ';
    }
    return($detail);
  }

  public function createPagenering($pages) {
    // Creates the pagenering
    $list = '';
    $list .= '<ul>';
    for ($i=0; $i < $pages; $i++) {
      $list .= '<li><a href="products.php?page=' . $i . '">' . $i . '</a></li>';
    }
    $list .= '</ul>';
    return($list);
  }
}

?>

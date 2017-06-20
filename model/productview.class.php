<?php

  class Productview {
    /**
     * Creates the view for the all products page
     * @param  [assoc arr] $result [The result from the productDB]
     * @return [string / HTML]         [The view generates]
     */
    public function createProductsView($result) {
      $counter = 0;
      // Counts how many times we displayed a product
      // Reset it self when it reach 4

      $products = '';
      foreach ($result as $key) {
        if ($counter == 0) {
          $products .= '<div class="col-12"><div class="col-1"></div><div class="col-10">';
        }
        $products .= '
        <div class="col-3 col-m-4 product">
          <a href="?op=details&productID=' . $key['idProduct'] . '">
            <div class="col-12 col-m-12">
              <img class="product_img" src="' . $key['pad'] . $key['filenaam'] . '" />
            </div>
            <div class="col-12 product-title">
              <h2>' . $key['naam'] . '</h2>
            </div>
          </a>
          <p>&euro;' . str_replace('.', ',', $key['prijs']) . '</p>
          <i class="fa fa-cart-arrow-down" aria-hidden="true" onclick="shoppingcard.add(' . $key['idProduct'] . ');shoppingcard.goTo();"></i>
        </div>
        ';
        if ($counter == 3) {
          $products .= '</div><div class="col-1 height"></div></div>';
          $counter = 0;
        }
        else {
          $counter++;
        }
      }
      if ($counter != 3) {
        // To fix that we forgot 1 div at the end
        $products .= '</div><div class="col-1 height"></div></div>';
      }
      return($products);
    }
    
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
    public function createRecentProductsView($result) {
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

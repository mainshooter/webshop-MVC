<?php
require_once 'security.class.php';
  class Shoppingcard {
    // Classe met hoofdletter
    // constructor public private or protected
    // Enters tussen method's
    public function add($productID, $amount) {
      // Ads product to shoppingcard
      $s = new Security();
      $_SESSION['shoppingcard'][$s->checkInput($productID)] = array('amount' => $s->checkInput($amount), 'productID' => $productID);
    }

    public function delete($productID) {
      // Delete a shoppingcard product
      $s = new Security();
      // unset($_SESSION['shoppingcard'][$s->checkInput($productID])]);
      unset($_SESSION['shoppingcard'][$s->checkInput($productID)]);
    }

    public function update($productID, $amount) {
      $s = new Security();
      $_SESSION['shoppingcard'][$s->checkInput($productID)]['amount'] = $s->checkInput($amount);
    }

    public function getProductAmount($productID) {
      // Get the amount of a product in the shoppingcard
      // And returns it
      $s = new Security();
      return($_SESSION['shoppingcard'][$s->checkInput($productID)]['amount']);
    }

    public function get() {
      if (ISSET($_SESSION['shoppingcard'])) {
        return($_SESSION['shoppingcard']);
      }
      else {
        return(0);
      }
    }

    public function checkIfIdExists($productID) {
      // This function checks if the product already exits in the shoppingcard
      // If it is return true
      // else false
      $s = new Security();
      if ($_SESSION['shoppingcard'][$s->checkInput($productID)]['amount'] != 0) {
        return(true);
      }
      else {
        return(false);
      }
    }

    public function count() {
      // counts all product in the shoppingcard and returns it
      $counts = 0;
      if (!empty($_SESSION["shoppingcard"])) {
        foreach ($_SESSION['shoppingcard'] as $row) {
              // If the id has more than 1 amounts
              $counts = $counts +  $row['amount'];
        }
      }
      return($counts);
    }

    private function getProductIDs() {
      // Get all product id's from the shoppingcard
      // And returns it
      $shoppingcard = $this->get();

      $productIDArray;
      if (!empty($shoppingcard)) {
        foreach ($shoppingcard as $key) {
          $productIDArray[] = $key['productID'];
        }
        return($productIDArray);
      }
    }


    public function calculateBTW() {
      // Calculates the BTW of the total shoppingcard
      // Returns it as a number
      $totalPrice = $this->calculateTotalPriceShoppingcard();
      $BTWPercentage = 21;
      $BTWPrice = $totalPrice / 100 * $BTWPercentage;

      return($BTWPrice);
    }

    public function calculatePriceWithoutBTW() {
      // Calculates the price without BTW
      // Returns it as a number
      $totalPrice = $this->calculateTotalPriceShoppingcard();
      $totalBTWPrice = $this->calculateBTW();
      $priceWithoutBTW = $totalPrice - $totalBTWPrice;

      return($priceWithoutBTW);
    }

    public function calculateTotalPriceShoppingcard() {
      // Calculates the total price of the shoppingcard
      // Returns totalPrice as a number
      $productIDArray = $this->getProductIDs();
      // Get all productIDs from the shoppingcard

      $totalPrice = 0;
      if (!empty($productIDArray)) {
        foreach ($productIDArray as $key => $value) {
          $totalPrice = $totalPrice + $this->productTotalPriceInShoppingCard($value);
        }
      }
      return($totalPrice);

    }

    public function productTotalPriceInShoppingCard($productID) {
      // Gets a total of a product price by
      $card = $this->get();
      $product = new product();
      $productPrice = $product->productPrice($productID);

      $total = 0;
      foreach ($card as $key) {
        if ($key['productID'] == $productID) {
          // Only calculate a total product price when it is eacual to the productID
            $total = $total + (intval($key['amount']) * $productPrice);
        }
      }
      return($total);
    }
  }
?>

<?php
require_once 'security.class.php';
  class Shoppingcard {
    // Classe met hoofdletter
    // constructor public private or protected
    // Enters tussen method's

    /**
     * Add a product to the shoppingcard
     * @param [INT] $productID [The ID of the product]
     * @param [number] $amount    [The amount of one product we set in the shoppingcard]
     */
    public function add($productID, $amount) {
      // Ads product to shoppingcard
      $s = new Security();
      $_SESSION['shoppingcard'][$s->checkInput($productID)] = array('amount' => $s->checkInput($amount), 'productID' => $productID);
    }

    /**
     * Deletes a product from the shoppingcard
     * @param  [INT] $productID [The ID of the product]
     */
    public function delete($productID) {
      // Delete a shoppingcard product
      $s = new Security();
      // unset($_SESSION['shoppingcard'][$s->checkInput($productID])]);
      unset($_SESSION['shoppingcard'][$s->checkInput($productID)]);
    }

    /**
     * Clears the shoppingcard
     */
    public function clearShoppingcard() {
      unset($_SESSION['shoppingcard']);
    }

    /**
     * Updates a product in the shoppingcard
     * @param  [INT] $productID [The productID from the product in the shoppingcard we need to update]
     * @param  [number] $amount    [How many is the new amount of one product]
     */
    public function update($productID, $amount) {
      $s = new Security();
      $_SESSION['shoppingcard'][$s->checkInput($productID)]['amount'] = $s->checkInput($amount);
    }

    /**
     * Gets the amount of one product from by the productID
     * @param  [type] $productID [description]
     * @return [type]            [description]
     */
    public function getProductAmount($productID) {
      // Get the amount of a product in the shoppingcard
      // And returns it
      $s = new Security();
      return($_SESSION['shoppingcard'][$s->checkInput($productID)]['amount']);
    }

    /**
     * Gets the content of the shoppingcard
     * @return [array] [From the shoppingcard]
     */
    public function get() {
      if (ISSET($_SESSION['shoppingcard'])) {
        return($_SESSION['shoppingcard']);
      }
      else {
        return(0);
      }
    }

    /**
     * Checks if a product exists in the shoppingcard
     * @param  [INT] $productID [The ID of the product]
     * @return [boolean]            [If it exists]
     */
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

    /**
     * Counts all products in the database
     * @return [number] [How many products there are in the shoppingcard]
     */
    public function count() {
      // counts all product in the shoppingcard and returns it
      $shoppingcard = $this->get();
      $counts = 0;
      if (!empty($shoppingcard)) {
        foreach ($shoppingcard as $row) {
              // If the id has more than 1 amounts
              $counts = $counts +  $row['amount'];
        }
      }
      return($counts);
    }

    /**
     * Get all the ID's from the products in the shoppingcard
     * @return [array] [With all productIDs]
     */
    public function getProductIDs() {
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

    /**
     * Calculates the BTW
     * @return [number] [The price of the BTW]
     */
    public function calculateBTW() {
      // Calculates the BTW of the total shoppingcard
      // Returns it as a number
      $totalPrice = $this->calculateTotalPriceShoppingcard();
      $BTWPercentage = 21;
      $BTWPrice = $totalPrice / 100 * $BTWPercentage;

      return($BTWPrice);
    }

    /**
     * Calculates the price without BTW
     * @return [number] [price without btw]
     */
    public function calculatePriceWithoutBTW() {
      // Calculates the price without BTW
      // Returns it as a number
      $totalPrice = $this->calculateTotalPriceShoppingcard();
      $totalBTWPrice = $this->calculateBTW();
      $priceWithoutBTW = $totalPrice - $totalBTWPrice;

      return($priceWithoutBTW);
    }

    /**
     * Calcuates the total price of the content of the shoppingcard
     * @return [decimal] [The total price of the shoppingcard]
     */
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

    /**
     * Calculates the totalprice of one product in the shoppingcard and returns it
     * @param  [INT] $productID [The ID of the product in th shoppingcard]
     * @return [decimal]            [The total price of one product]
     */
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

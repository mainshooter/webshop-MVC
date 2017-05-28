<?php
  require_once 'model/shoppingcard.class.php';
  require_once 'model/product.class.php';
  require_once 'model/databasehandler.class.php';

  class Order {
    var $shoppingcard;
    var $oderNumer;

    function __construct() {
      // Runs when class is created
    }

    public function createOrder($orderID) {
      // This function creates the order
      // Parameter is a string or number from the orderID
      // This function is used to save every product item a customer has ordered

      try {
        $shoppingcard = new shoppingcard();
        $product = new product();

        $shoppingcardProductIDs = $shoppingcard->getProductIDs();

        foreach ($shoppingcardProductIDs as $productID) {
          $productPrice = $product->productPrice($productID);
          $productAmountInShoppingcard = $shoppingcard->getProductAmount($productID);

          $db = new db();

          $sql = "INSERT INTO order_item (Order_idOrder, prijs, Product_idProduct, aantal) VALUES (
            :orderID,
            :price,
            :productID,
            :amount
          )";
          $input = array(
            "orderID" => $orderID,
            "price" => $productPrice,
            "productID" => $productID,
            "amount" => $productAmountInShoppingcard
          );

          $db->CreateData($sql , $input);
          return(1);
        }
      } catch (Exception $e) {
        return($e->getMessage());
      }
    }

    public function getOrderItems($orderID) {
      $db = new db();
      $s = new Security();

      $sql = "SELECT * FROM order_item WHERE Order_idOrder=:orderID";
      $input = array(
        "orderID" => $orderID
      );

      return($db->readData($sql, $input));
    }



  }



?>

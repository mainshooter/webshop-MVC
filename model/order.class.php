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

    /**
     * Removes a order by the paymentID
     * We do that when they order has not been paid
     * @param  [INT] $paymentID [The ID from a payment]
     */
    public function removeOrder($paymentID) {
      $db = new db();
      $s = new Security();

      $sql = "DELETE FROM `Order` WHERE paymentID=:paymentID";
      $input = array(
        "paymentID" => $s->checkInput($paymentID)
      );
      $db->deleteData($sql, $input);
    }

    public function getNameOfThePersonWhoOrder($orderID) {
      // Gets the first and lastname of the person who ordered and returns it as a string
      $db = new db();
      $s = new Security();

      $sql = "SELECT klant_voornaam, klant_achternaam FROM `Order` WHERE idOrder=:orderID";
      $input = array(
        "orderID" => $s->checkInput($orderID)
      );
      $result = $db->readData($sql, $input);

      foreach ($result as $key) {
        return($key['klant_voornaam'] . ' ' . $key['klant_achternaam']);
      }
    }

    public function getEmailOfThePersonWhoOrder($orderID) {
      // Gets the email adress from a order by orderID
      // Returns it as a string
      $db = new db();
      $s = new Security();

      $sql = "SELECT klant_email FROM `Order` WHERE idOrder=:orderID";
      $input = array(
        "orderID" => $orderID
      );
      $result = $db->readData($sql, $input);
      foreach ($result as $key) {
        return($key['klant_email']);
      }
    }

    /**
     * Gets the content of a order By orderID
     * @param  [INT] $orderID [The ID of the order]
     * @return [array]          [Containing the result from the db]
     */
    public function getOrder($orderID) {
      $db = new db();
      $s = new Security();

      $sql = "SELECT * FROM `Order` WHERE idOrder=:orderID";
      $input = array(
        "orderID" => $s->checkInput($orderID)
      );
      $order = $db->readData($sql, $input);
      return($order);
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

    public function getOrderItemsForHtmlGenerator($orderID) {
      $db = new db();
      $s = new Security();

      $sql = "SELECT naam, aantal, order_item.prijs FROM order_item JOIN Product ON idProduct=Product_idProduct WHERE Order_idOrder=:orderID";
      $input = array(
        "orderID" => $s->checkInput($orderID)
      );

      return($db->readData($sql, $input));
    }

    public function getHeadersForOrderItemsForHtmlGenerator($orderID) {
      $db = new db();
      $s = new Security();

      $sql = "SELECT naam, aantal, order_item.prijs FROM order_item JOIN Product ON idProduct=Product_idProduct WHERE Order_idOrder=:orderID LIMIT 1";
      $input = array(
        "orderID" => $s->checkInput($orderID)
      );

      return($db->readData($sql, $input));
    }

    /**
     * Gets the orderID by using the paymentID given by mollie
     * @param  [INT] $paymentID [The paymentID geven by mollie]
     * @return [INT] $orderID   [The ID of the order]
     */
    protected function getOrderID($paymentID) {
      $db = new db();
      $s = new Security();

      $sql = "SELECT idOrder FROM `Order` WHERE paymentID=:paymentID LIMIT 1";
      $input = array(
        "paymentID" => $paymentID
      );

      $result = $db->readData($sql, $input);

      foreach ($result as $key) {
        return($key['idOrder']);
      }
    }



  }



?>

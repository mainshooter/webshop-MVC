<?php
  require_once 'Order.php';

  class Payment {
    private $price;
    private $discription;

    private function calculatePrice($orderID) {
      // This function Caluclates the price that is needed to be payed
      // Returns the totalprice as a number
      $order = new order();

      $orderItems = $order->getOrderItems();
      $totalPrice = 0;

      foreach ($orderItems as $key) {
        $totalPrice = $totalPrice + $key['prijs'];
      }
      return($totalPrice);
    }
  }


?>

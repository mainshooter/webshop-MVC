<?php
  require_once 'Order.php';
  require_once 'Mollie//API/Autoloader.php';
  require_once 'security.class.php';
  require_once 'databasehandler.class.php';

  class Payment {
    private $mollie;

    private $price;
    private $discription;

    function __construct() {
      $this->mollie = new Mollie_API_Client();
      $this->mollie->setApiKey('test_2aH3RczeMMTU776NWJjJzMrPEH57pG');
    }

    /**
     * Start a transaction
     * It gets the totalprice of a order by the OrderID
     * Then it sets the payment
     *
     * When the payment is done we get the PaymentID
     * We save the paymentID in the DB on a order
     * @param  INT $orderID The INT for the orderID
     * @return [int]          [PaymentID for mollie]
     */
    public function startPayment($orderID) {
      // This function creates the payment
      $s = new Security();
      $orderID = $s->checkInput($orderID);

      try {
        $payment = $this->mollie->payments->create(array(
          "amount"       => $this->calculatePrice($orderID),
          "method"       => Mollie_API_Object_Method::IDEAL,
          "description"  => "Order: " . $orderID,
          "redirectUrl"  => "https://dev.samebestserver.nl/leerjaar2/webshop-MVC/",
          "webhookUrl"   => "https://dev.samebestserver.nl/leerjaar2/webshop-MVC/",
        ));
        $payment = $this->mollie->payments->get($payment->id);
        $paymentID = $payment->id;
        if ($payment->isPaid()) {
          echo "REAL PAYMENT!";
        }
        $this->savePaymentID($orderID, $paymentID);

        return($paymentID);
        exit;
      }
      catch (Mollie_API_Exception $e) {
          // Error handleling Mollie
          echo "API call failed: " . htmlspecialchars($e->getMessage());
          echo " on field " . htmlspecialchars($e->getField());
      }
    }

    /**
     * Saves the paymentID to the database on a order
     * It can later be used to refund a payment
     * @param  [INT] $orderID   [ID of the order]
     * @param  [INT] $paymentID [The paymentID that has been returned by mollie]
     */
    private function savePaymentID($orderID, $paymentID) {
      // PaymentID is the ID that has been given by the mollie API when there is paid
      // We save it when there is needed to refund someone
      $db = new db();

      $sql = "UPDATE `Order` SET paymentID=:paymentID WHERE idOrder=:orderID";
      $input = array(
        "orderID" => $orderID,
        "paymentID" => $paymentID
      );
      $db->UpdateData($sql, $input);
    }

    /**
     * Starts a refund for a order
     * @param  [INT] $orderID      [the orderID it is used to get the paymentID]
     * @param  [number] $refundAmount [the amount that the person who gets for refund]
     */
    public function refundPayment($orderID, $refundAmount) {
      // Refunds a payment by the orderID
      $s = new Security();

      $refundAmount = $s->checkInput($refundAmount);
      $paymentID = $this->getPaymentID($orderID);

      $refund = $this->mollie->payments->refund($paymentID, $refundAmount);
    }

    /**
     * Gets the paymentID and returns it
     * @param  [INT] $orderID [The ID for the order it will be used to find the paymentID]
     * @return [INT] paymentID         [The ID for the payment that has been given by mollie]
     */
    private function getPaymentID($orderID) {
      // Gets the PaymentID from the array and returns it
      $db = new db();
      $s = new security();

      $sql = "SELECT paymentID FROM Order WHERE idOrder=:orderID LIMIT 1";
      $input = array(
        "orderID" => $s->checkInput($orderID)
      );
      $result = $db->readData($sql, $input);
      foreach ($result as $key) {
        return($key['paymentID']);
      }
    }

    /**
     * Calculates the totalPrice of a order
     * Gets all order items and counts them together
     * @param  [INT] $orderID [The ID of a order]
     * @return [number] $totalPrice [The totalprice of a order]
     */
    private function calculatePrice($orderID) {
      // This function Caluclates the price that is needed to be payed
      // Returns the totalprice as a number
      $order = new order();

      $orderItems = $order->getOrderItems($orderID);
      $totalPrice = 0;

      foreach ($orderItems as $key) {
        $totalPrice = $totalPrice + $key['prijs'];
      }
      return($totalPrice);
    }
  }


?>

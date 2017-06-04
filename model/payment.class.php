<?php
  require_once 'order.class.php';
  require_once 'Mollie/API/Autoloader.php';
  require_once 'security.class.php';
  require_once 'databasehandler.class.php';
  require_once 'mail.class.php';
  require_once 'HtmlGenerator.class.php';
  require_once 'config-webshop.php';

  class Payment extends order {
    private $mollie;

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
          "redirectUrl"  => siteLocation . "?op=displayOrder&orderID=" . $orderID . "",
          "webhookUrl"   => siteLocation . "?op=paymentResponse",
          'metadata'    => array(
            'order_id' => $orderID
          )
        ));
        $payment = $this->mollie->payments->get($payment->id);

        $paymentID = $payment->id;
        $this->savePaymentID($orderID, $paymentID);

        header("Location: " . $payment->getPaymentUrl());
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
     * This function handels the payment result from mollie
     * @param [INT] $paymentID [The PaymentID given by mollie API!]
     */
    public function handelsPaymentResult($paymentID) {
      $s = new Security();
      $mail = new Mail();
      $HtmlGenerator = new HtmlGenerator();

      $paymentID = $s->checkInput($paymentID);
      $orderID = $this->getOrderID($s->checkInput($paymentID));

      $payment = $this->mollie->payments->get($paymentID);
      $paymentStatus = $s->checkInput($payment->status);

      $this->setPaymentStatus($paymentID, $paymentStatus);

      if ($payment->isPaid()) {
        // Payment is done
        $this->sendOwnerMailToReadAOrder($orderID);
        $this->sendMailToCustomerAboutPayment($orderID);
      }
      else if (!$payment->isOpen()) {
        // payment is closed and has'nt been completed
        // We remove it

      }
    }

    /**
     * Sets in the database that someone has payed
     * @param  [INT] $paymentID [paymentID of a payment]
     */
    private function setPaymentStatus($paymentID, $paymentStatus) {
      $db = new db();
      $s = new Security();

      $sql = "UPDATE `Order` SET betaal_status=:betaal_status WHERE paymentID=:paymentID";
      $input = array(
        "betaal_status" => $s->checkInput($paymentStatus),
        "paymentID" => $s->checkInput($paymentID)
      );

      $db->UpdateData($sql, $input);
    }

    /**
     * Saves the paymentID to the database on a order
     * It can later be used to refund a payment
     * @param  [INT] $orderID   [ID of the order]
     * @param  [INT] $paymentID [The paymentID that has been returned by mollie]
     */
    private function savePaymentID($orderID, $paymentID) {
      // PaymentID is the ID that has been given by the mollie API
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

      $sql = "SELECT paymentID FROM `Order` WHERE idOrder=:orderID LIMIT 1";
      $input = array(
        "orderID" => $s->checkInput($orderID)
      );
      $result = $db->readData($sql, $input);
      foreach ($result as $key) {
        return($key['paymentID']);
      }
    }

    /**
     * gets the payment status by orderID
     * @param  [INT] $orderID [The ID of a order]
     * @return [string] [With the payment status]
     */
    public function getPaymentStatus($orderID) {
      $Db = new db();
      $S = new Security();

      $sql = "SELECT betaal_status FROM `Order` WHERE idOrder=:orderID";
      $input = array(
        "orderID" => $S->checkInput($orderID)
      );

      $result = $Db->readData($sql, $input);

      foreach ($result as $key) {
        return($key['betaal_status']);
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
      $orderItems = $this->getOrderItems($orderID);
      $totalPrice = 0;

      foreach ($orderItems as $key) {
        $totalPrice = $totalPrice + $key['prijs'];
      }
      return($totalPrice);
    }
  }


?>

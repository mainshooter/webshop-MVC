<?php
  require_once 'PHPMailer/PHPMailerAutoload.php';

  class Mail {
    // To send email to clients
    var $adress;
    var $adressName;

    var $subject;
    var $messageInHTML;
    var $message;

    var $mail;

    function __construct() {
      // Set the default settings for phpmailer
      $this->mail = new PHPMailer;

      $this->mail->SMTPDebug = 3;                               // Enable verbose debug output

      $this->mail->isSMTP();                                      // Set mailer to use SMTP
      $this->mail->Host = 'smtp.transip.email';  // Specify main and backup SMTP servers
      $this->mail->SMTPAuth = true;                               // Enable SMTP authentication
      $this->mail->Username = 'webshop@samebestdevelopment.nl';                 // SMTP username
      $this->mail->Password = 'Webshop1234567890!';                           // SMTP password
      $this->mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $this->mail->Port = 465;                                    // TCP port to connect to

      $this->mail->setFrom('webshop@samebestdevelopment.nl', 'Multiversum');
      $this->mail->isHTML(true);                                  // Set email format to HTML
    }

    public function sendMail() {
      // Sends the mail
      $this->mail->addAddress($this->adress, $this->adressName);

      $this->mail->Subject = $this->subject;
      $this->mail->Body    = $this->messageInHTML;
      $this->mail->AltBody = $this->message;
      // Sets the mail content

      if(!$this->mail->send()) {
          return("Failed " . $this->mail->ErrorInfo);
      } else {
          return('Succes');
      }
    }
  }
?>

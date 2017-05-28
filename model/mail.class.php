<?php
  require_once 'PHPMailer/PHPMailerAutoload.php';

  class Mail {
    // To send email to clients
    var $adress;

    var $subject;
    var $messageInHTML;
    var $message;

    function __construct() {
      // Set the default settings for phpmailer
      $mail = new PHPMailer;

      $mail->SMTPDebug = 3;                               // Enable verbose debug output

      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.transip.email';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'webshop@samebestdevelopment.nl';                 // SMTP username
      $mail->Password = 'Webshop1234567890!';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 465;                                    // TCP port to connect to

      $mail->setFrom('webshop@samebestdevelopment.nl', 'Multiversum');
      $mail->isHTML(true);                                  // Set email format to HTML
    }

    public function sendMail() {
      // Sends the mail

      $mail->Subject = $this->subject;
      $mail->Body    = $this->messageInHTML;
      $mail->AltBody = $this->message;
      // Sets the mail content

      if(!$mail->send()) {
          return("Failed " . $mail->ErrorInfo);
      } else {
          return('Succes');
      }
    }
  }

  $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
$mail->addAddress('ellen@example.com');               // Name is optional
$mail->addReplyTo('info@example.com', 'Information');
$mail->addCC('cc@example.com');
$mail->addBCC('bcc@example.com');

$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name






?>

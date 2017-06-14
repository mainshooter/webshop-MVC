<?php
  // Used for the input the customer data
  include 'header.php';
?>
  <div class="col-1"></div>
  <div class="col-10">
    <form method="post">
      <h2>Voornaam</h2>
      <input type="text" name="customer_firstname" required="true">

      <h2>Achternaam</h2>
      <input type="text" name="customer_lastname" required="true">

      <h2>tussenvoegsel</h2>
      <input type="text" name="customer_tussenvoegsel">

      <h2>Straat</h2>
      <input type="text" name="customer_street" required="true">

      <h2>Huisnummer</h2>
      <input type="number" name="customer_houseNumber" required="true">

      <h2>Toevoeging</h2>
      <input type="text" name="customer_addon">

      <h2>Postcode</h2>
      <input type="text" name="customer_zipCode" required="true">

      <h2>E-mail</h2>
      <input type="mail" name="customer_email" required="true">

      <input type="submit" name="op" value="betalen">
    </form>

  </div>
  <div class="col-1"></div>
<?php

  include 'footer.php';

?>

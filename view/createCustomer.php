<?php
  // Used for the input the customer data
  include 'header.php';
?>
  <div class="col-1"></div>
  <div class="col-10">
    <h2>Voordat we uw bestelling kunnen verwerken, hebben we wat gegevens van u nodig</h2>
    <br>
    <form method="post">
      <h3>Voornaam</h3>
      <input type="text" name="customer_firstname" required="true">

      <h3>Achternaam</h3>
      <input type="text" name="customer_lastname" required="true">

      <h3>tussenvoegsel</h3>
      <input type="text" name="customer_tussenvoegsel">

      <h3>Straat</h3>
      <input type="text" name="customer_street" required="true">

      <h3>Huisnummer</h3>
      <input type="number" name="customer_houseNumber" required="true">

      <h3>Toevoeging</h3>
      <input type="text" name="customer_addon">

      <h3>Postcode</h3>
      <input type="text" name="customer_zipCode" required="true">

      <h3>E-mail</h3>
      <input type="mail" name="customer_email" required="true">
      <br>
      <br>
      <input type="submit" name="op" value="betalen">
    </form>

  </div>
  <div class="col-1"></div>
<?php

  include 'footer.php';

?>

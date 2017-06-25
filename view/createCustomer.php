  <div class="col-1"></div>
  <div class="col-10">
    <h2>Voordat we uw bestelling kunnen verwerken, hebben we wat gegevens van u nodig</h2>
    <br>
    <form method="post" action="?op=Betalen" class="createCustomer">
      <div class="col-6">
        <label>Voornaam</label>
        <input type="text" name="customer_firstname" required="true">

        <label>Achternaam</label>
        <input type="text" name="customer_lastname" required="true">

        <label>Tussenvoegsel</label>
        <input type="text" name="customer_tussenvoegsel">

        <label>E-mail</label>
        <input type="mail" name="customer_email" required="true">
      </div>

      <div class="col-6">
        <label>Straat</label>
        <input type="text" name="customer_street" required="true">

        <label>Huisnummer</label>
        <input type="number" name="customer_houseNumber" required="true">

        <label>Toevoeging</label>
        <input type="text" name="customer_addon">

        <label>Postcode</label>
        <input type="text" name="customer_zipCode" required="true">

      </div>
      <br>
      <br>
      <button type="button" id="order" onclick="createingOrder();">Betalen</button>
    </form>

  </div>
  <div class="col-1"></div>

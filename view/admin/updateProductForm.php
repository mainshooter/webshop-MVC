<?php include 'header.html'; ?>
  <body>
    <div class="row">
      <?php

      foreach ($productDetails as $key) {
        echo '
          <form method="post" class="col-12">
            <div>Product naam</div>
            <input type="text" name="product_name" value="' . $key['naam'] . '">

            <div>Prijs</div>
            <input type="number" name="product_price" value="' . $key['prijs'] . '">

            <div>Product beschrijving</div>
            <textarea name="product_description">' . $key['beschrijving'] . '</textarea>

            <div>EAN-code</div>
            <input type="number" name="EAN" value="' . $key['ean'] . '">

            <div>Product foto</div>
            <input type="file" name="file_upload">

            <div></div>
            <input type="submit" name="op" value="updateProduct">
          </form>
        ';
      }

      ?>
    </div>
  </body>
</html>

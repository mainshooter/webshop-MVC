<?php include 'header.html'; ?>
  <body>
    <a class="back_link" href="?op=dashboard"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
    <div class="row">
      <?php
      foreach ($productDetails as $key) {
        echo '
        <img class="col-3" src="' . $key['pad'] . $key['filenaam'] .  '">
          <form method="post" enctype="multipart/form-data" class="col-9">
            <label>Product naam</label>
            <input type="text" name="product_name" value="' . $key['naam'] . '">

            <label>Prijs</label>
            <input type="number" step="0.01" name="product_price" value="' . $key['prijs'] . '">

            <label>Product beschrijving</label>
            <textarea name="product_description">' . $key['beschrijving'] . '</textarea>

            <label>EAN-code</label>
            <input type="number" name="EAN" value="' . $key['EAN'] . '">

            <label>Product foto</label>
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

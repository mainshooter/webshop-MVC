<?php include 'header.html' ?>
  <body>
    <div class="row">
      <a class="col-11" href="?op=addProductForm">Product toevoegen</a>
      <a class="col-1" href="?op=logout">Logout</a>
      <?php
        echo "<table class='col-12'>";
        foreach ($AllProducts as $key) {
          echo '
            <tr class="col-6">
              <td class="col-4"><img class="col-12" src="' . $key['pad'] . $key['filenaam'] . '"/></td>
              <td class="col-4"><h2>' . $key['naam'] . '</h2></td>
              <td class="col-2">
                <a href="?op=updateProductForm&productID=' . $key['idProduct'] . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                <i onclick="product.delete(' . $key['idProduct'] . ');" class="fa fa-trash-o" aria-hidden="true"></i>
              </td>
            </tr>
          ';
        }
      ?>

    </div>
  </body>
</html>

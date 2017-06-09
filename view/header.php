 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width,initial-scale=1">

   <title>Multiversum - VR-brillen</title>

   <link rel="stylesheet" href="style/grid.css" type="text/css">
   <link rel="stylesheet" href="style/style.css" type="text/css">

   <link href="https://fonts.googleapis.com/css?family=Quicksand:300" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

   </head>
   <body onload="shoppingcard.count();">
     <div class="wrapper">
       <div class="row">
        <nav>
      <a href="index.php">
          <img class="col-3 col-m-3" src="file/site/logo_mt.png">
      </a>
        <div id="nav" class="col-8 col-m-12">
            <ul id="width-ul">
                <li><a href="index.php">Home</a></li>
                <li><a href="?op=page">Producten</a></li>
                <li><a href="?op=contact">Contact</a></li>
            </ul>
        </div>
        <a href="?op=shoppingcardShow">
          <i class="fa fa-shopping-basket col-1 col-m-1 shopping_card" aria-hidden="true"><span id="shoppingcardCount">0</span></i>
        </a>
    </nav>

    <img class="col-12 image_header" src="file/site/header.png"/>

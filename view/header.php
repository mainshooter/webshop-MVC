 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width,initial-scale=1">

   <title>Multiversum - VR-brillen</title>

   <link rel="stylesheet" href="style/grid.css" type="text/css">
   <link rel="stylesheet" href="style/style.css" type="text/css">

   <!-- <link href="https://fonts.googleapis.com/css?family=Quicksand:300" rel="stylesheet"> -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="apple-touch-icon" sizes="57x57" href="file/icon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="file/icon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="file/icon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="file/icon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="file/icon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="file/icon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="file/icon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="file/icon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="file/icon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="file/icon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="file/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="file/icon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="file/icon/favicon-16x16.png">
    <link rel="manifest" href="file/icon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="file/icon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

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

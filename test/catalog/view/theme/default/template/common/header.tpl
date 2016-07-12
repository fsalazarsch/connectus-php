<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
<!--//Link de estilos para tema creative <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

      <link rel='stylesheet' href='catalog/view/theme/default/stylesheet/revslider/rs-plugin/css/settings.css' type='text/css' media='all' />
      <link rel='stylesheet' href='catalog/view/theme/default/stylesheet/mmenu.css' type='text/css' media='all' />
      <link rel='stylesheet' href='catalog/view/theme/default/stylesheet/bootstrap.min.css' type='text/css' media='all' />
      <link rel='stylesheet' href='catalog/view/theme/default/stylesheet/prettyPhoto.css' type='text/css' media='all' />
      <link rel='stylesheet' href='catalog/view/theme/default/stylesheet/animate.css' type='text/css' media='all' />
      <link rel='stylesheet' href='catalog/view/theme/default/stylesheet/font-awesome.min.css' type='text/css' media='all' />
      <link rel='stylesheet' href='catalog/view/theme/default/stylesheet/style.css' type='text/css' media='all' />
      <link rel='stylesheet' href='catalog/view/theme/default/stylesheet/responsive.css' type='text/css' media='all' />
      <link rel='stylesheet' href='catalog/view/theme/default/stylesheet/tw_woocommerce.css' type='text/css' media='all' />
      <link rel='stylesheet' href='catalog/view/theme/default/stylesheet/twentytwenty.css' type='text/css' media='all' />
      <link rel='stylesheet' href='catalog/view/theme/default/stylesheet/blue_skin.css' type='text/css' media='all' />

      <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

    <!--
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:100,200,300,700,800,900' rel='stylesheet' type='text/css'>
    <!--fin estilo creative-->

<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php echo $google_analytics; ?>
</head>
<body class="<?php echo $class; ?>">
<nav id="top">
  <div class="container">
    <?php echo $currency; ?>
    <?php echo $language; ?>
    <div id="top-links" class="nav pull-right">
      <ul class="list-inline">
        <li><a href="<?php echo $contact; ?>"><i class="fa fa-phone"></i></a> <span class="hidden-xs hidden-sm hidden-md"><?php echo $telephone; ?></span></li>
        <li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span class="caret"></span></a>
          <ul class="dropdown-menu dropdown-menu-right">
             <?php if ($logged) { ?>
            <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
            <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
            <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
            <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
            <li><a href="<?php echo $goBackend; ?>"><?php echo $text_goBackend; ?></a></li>
            <?php } else { ?>
            <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
            <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
            <li><a href="<?php echo $goBackend; ?>"><?php echo $text_goBackend; ?></a></li>
            <?php } ?>
          </ul>
        </li>
        <li><a href="<?php echo $wishlist; ?>" id="wishlist-total" title="<?php echo $text_wishlist; ?>"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_wishlist; ?></span></a></li>
        <li><a href="<?php echo $shopping_cart; ?>" title="<?php echo $text_shopping_cart; ?>"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_shopping_cart; ?></span></a></li>
        <li><a href="<?php echo $checkout; ?>" title="<?php echo $text_checkout; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_checkout; ?></span></a></li>
      </ul>
    </div>
  </div>
</nav>
<header>
  <div class="container">
    <div class="row">
      <div class="col-sm-4">
        <div >
          <?php if ($logo) { ?>
          <a href="<?php echo $home; ?>"><img id="logo" src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
          <?php } else { ?>
          <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
          <?php } ?>
        </div>
      </div>
      <div class="col-sm-5"><?php echo $search; ?>
      </div>
      <div class="col-sm-3"><?php echo $cart; ?></div>
    </div>
  </div>
</header>

<!--//
<?php if ($categories) { ?>
  <div class="header-container"> 
  <!--Barra sobre nav-ba de navegacion
    <div class="tw-top-bar">
      <div class="container">
        <div class="top-right-widget">
        <!--Login
            <div class="tw-top-widget right" id="wysija-2">
              <span class="top-widget-title"><i class="fa fa-envelope-o"></i> sign up</span>
              <div class="widget_wysija_cont">
                <div id="msg-form-wysija-2" class="wysija-msg ajax">
                </div>
                <form id="form-wysija-2" method="post" action="#wysija" class="widget_wysija">
                  <p class="wysija-paragraph">
                    <label>Email <span class="wysija-required">*</span></label>
                    <input type="text"  class="wysija-input" title="Email" value=""/>

                  </p>
                  <input class="wysija-submit wysija-submit-field" type="submit" value="Subscribe!"/>
      
                </form>
              </div>
            </div>
        <!--Crear Cuenta
        <div class="tw-top-widget right" id="search-3">
          <span class="top-widget-title"><i class="fa fa-search"></i></span>
          <form role="search" method="get" id="searchform" action="#">
            <div class="input">
              <input type="text" value="" name="s" id="s" placeholder="Search Here"/>
              <i class="button-search fa fa-search"></i>
            </div>
          </form>
        </div>
      </div>
    </div>
    </div>
  <header id="header" class="header-large">
    <div class="container">
      <div class="show-mobile-menu clearfix">
            <a href="#" class="mobile-menu-icon">
            <span></span><span></span><span></span><span></span>
            </a>
      </div>
      <div class="row header">
        <!--logo
        <div class="col-md-3">
          <div class="tw-logo">
          <a href="<?php echo $home; ?>"><img id="logo" src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
          </div>
        </div>
        <!--barra 
        <div class="col-md-9">
          <nav class="menu-container clearfix">
            <div class="tw-menu-container">
              <a id="tw-nav-toggle" href="#"><span></span> </a>
              <ul id="menu" class="sf-menu">
                <li id="menu-item-571" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-571 tw-menu-active"><a href="#one-page-home"><?php echo $inicio; ?></a></li>
                <li id="menu-item-572" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-572"><a href="#about"><?php echo $servicios; ?></a></li>
                <li id="menu-item-573" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-573"><a href="#service"><?php echo $precios; ?></a></li>
                <li id="menu-item-574" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-574"><a href="#features"><?php echo $plataforma; ?></a></li>
                <li id="menu-item-577" class="btn btn-flat btn-s menu-item menu-item-type-custom menu-item-object-custom menu-item-home menu-item-577"><a href="home-creative.html"><?php echo $acceso; ?></a></li>
              </ul>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </header>
  </div>
<?php } ?>
//-->


<?php if ($categories) { ?>
<div class="container">
  <nav id="menu" class="navbar">
    <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
      <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <ul class="nav navbar-nav">
        <?php foreach ($categories as $category) { ?>
        <?php if ($category['children']) { ?>
        <li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?></a>
          <div class="dropdown-menu">
            <div class="dropdown-inner">
              <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
              <ul class="list-unstyled">
                <?php foreach ($children as $child) { ?>
                <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
                <?php } ?>
              </ul>
              <?php } ?>
            </div>
            <a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $text_all; ?> <?php echo $category['name']; ?></a> </div>
        </li>
        <?php } else { ?>
        <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
        <?php } ?>
        <?php } ?>
      </ul>
    </div>
  </nav>
</div>
<?php } ?>

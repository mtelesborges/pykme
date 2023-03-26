<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#17c600">
<meta name="apple-mobile-web-app-title" content="pykme">
<meta name="application-name" content="pykme">
<meta name="msapplication-TileColor" content="#17c600">
<meta name="theme-color" content="#ffffff">	
<meta name="keywords" content="<?php foreach($view["Keywords"] as $keyword){echo $keyword.", ";}?>">
<meta name="description" content="<?php echo $view["PageDescription"];?>">
<meta name="author" content="White Rose">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $view["PageTitle"];?> - pykme</title>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-E1VQB9VQNR"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-E1VQB9VQNR');
</script>
	
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	
<script src="/View/js/materialize.js"></script>

<script src="/View/js/main.js" type="application/javascript"></script>
	
<?php
if(!empty($view["JS"])){
	foreach($view["JS"] as $js){
		echo '<script src="/View/js/'.$js.'" type="application/javascript"></script>';
	}
}
?>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="/View/css/materialize.css">

<link rel="stylesheet" type="text/css" href="/View/css/main.css">
	
<?php

if(!empty($view["CSS"])){
	foreach($view["CSS"] as $css){
		echo '<link rel="stylesheet" type="text/css" href="/View/css/'.$css.'">';
	}
}
?>
	
<link rel="stylesheet" type="text/css" href="/View/css/overwrite.css">
</head>
<body>
<div id="picker"></div>
<div id="modalMessage" class="modal">
</div>
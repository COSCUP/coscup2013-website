<?php
include_once "i18n.php";
$theme_assets_uri = "/2013-theme/assets/";
$lc = new i18n;
switch($this->current[0])
{
  case "en":
    $lc->lang = "en";
    break;
  case "zh-tw":
    $lc->lang = "zh-TW";
    break;
  case "zh-cn":
    $lc->lang = "zh-CN";
    break;
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lc->lang ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php echo $title; ?> | 2013 COSCUP-Open x [Web | Mobile | Data]</title>

<meta name="keywords" content="">
<meta name="description" content="">
<meta name="COMPANY" content="">

<!--fb shareing-->
<meta property="og:title" content="2013 COSCUP-Open x [Web | Mobile | Data]" />
<meta name="og:description" content="" />
<meta property="og:type" content="website" />
<meta property="og:url" content="" />
<meta property="og:site_name" content="2013 COSCUP-Open x [Web | Mobile | Data]" />
<meta property="og:image" content="" />

<!--RWD Revise-->
<!--[if lt IE 7]>
<style type="text/css">
  body { overflow: hidden; }
  #wrapper { height: 100%; overflow: auto; }
  #fixed { position: absolute; right: 17px; }
</style>
<![endif]-->

<!--[if lt IE 8]>
<style type="text/css">
  body { overflow: hidden; }
  #wrapper { height: 100%; overflow: auto; }
  #fixed { position: absolute; right: 17px; }
</style>
<![endif]-->


<!--phone-->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<link rel="apple-touch-icon" href="<?php echo $theme_assets_uri;?>ios-fav.jpg" />
<link media="only screen and (max-width:768px)" href="<?php echo $theme_assets_uri;?>mobile.css" type= "text/css" rel="stylesheet" />
<link media="screen and (min-width:769px)" href="<?php echo $theme_assets_uri;?>style.css" type="text/css" rel="stylesheet" />
<?php
if (isset($styles)) {
  foreach ( $styles as $file ) {
?>
<link href="<?php echo $file;?>" type="text/css" rel="stylesheet" />
<?php
  }
}
?>
<!--favicon-->
<link type="image/x-icon" href="<?php echo $theme_assets_uri;?>favicon.ico" rel="shortcut icon">

<!--隱藏網址列-->
<script>
	window.addEventListener("load",function() {  
	setTimeout(function(){
	window.scrollTo(0, 1); }, 10);
});
 
</script>

<!-- GA -->
<script>
</script>
</head>
<body>
<div id="header">
	<div class="blue"></div>
    <div class="m_kv"><img src="<?php echo $theme_assets_uri;?>mobile/kv.png" width="100%" /></div>
    <div class="wrap">
   	  <div class="logo"><a href="<?php echo $home_path.$this->current[0]."/index.html"?>">coscup 2013</a></div>
      	<ul id="lan">
          <li><a href="#">正體</a></li>
        </ul>
	    <ul id="social">
        <li><a href="https://www.facebook.com/coscup" title="facebook"><img src="<?php echo $theme_assets_uri;?>icon_fb.png"/></a></li>
        <li><a href="http://www.plurk.com/coscup" title="plurk"><img src="<?php echo $theme_assets_uri;?>icon_plurk.png" /></a></li>
        <li><a href="https://twitter.com/coscup" title="twitter"><img src="<?php echo $theme_assets_uri;?>icon_twitter.png" /></a></li>
        <li><a href="http://blog.coscup.org" title="blog"><img src="<?php echo $theme_assets_uri;?>icon_blog.png" /></a></li>
        <li><a href="http://www.flickr.com/people/coscup/" title="flickr"><img src="<?php echo $theme_assets_uri;?>icon_flickr.png"  /></a></li>
        <li><a href="http://www.youtube.com/user/thecoscup?feature=watch" title="youtube"><img src="<?php echo $theme_assets_uri;?>icon_utube.png" /></a></li>
	    </ul>
      <nav id="nav-wrap">
        <ul id="mainNav">
          <?php echo $this->menu(1); ?>
          <li class="open"><a href="#" title="OPEN">We (heart) Open.</a></li>
        </ul>
      </nav> 
    </div>
</div>
<!--Main-->
<div id="main">
<div class="wrap">
  <div id="content">
  <?php echo $transformed; ?>
  </div>
  <!--Sponsor-->
  <div id="sponsor">
<?php
switch($this->current[0])
{
  /* case "zh-tw": */
  /*   echo $this->block['sponsors-zh-tw']; */
  /*   break; */
  /* case "zh-cn": */
  /*   echo $this->block['sponsors-zh-cn']; */
  /*   break; */
  default:
    echo $this->block['sponsors'];
    break;
}
?>
  </div>
</div>
</div>
<!--social Mobile-->
<ul class="sharing">
  <li class="title">Follow Us!!<hr></li>
  <li><a href="https://www.facebook.com/coscup"><img src="<?php echo $theme_assets_uri;?>icon_fb.png" align="absmiddle" /><span>facebook</span></a></li>
  <li><a href="http://www.plurk.com/coscup"><img src="<?php echo $theme_assets_uri;?>icon_plurk.png" align="absmiddle" /><span>Plurk</span></a></li>
  <li><a href="https://twitter.com/coscup"><img src="<?php echo $theme_assets_uri;?>icon_twitter.png" align="absmiddle" /><span>twitter</span></a></li>
  <li><a href="http://blog.coscup.org"><img src="<?php echo $theme_assets_uri;?>icon_blog.png"  align="absmiddle" /><span>Blog</span></a></li>
  <li><a href="http://www.flickr.com/people/coscup/"><img src="<?php echo $theme_assets_uri;?>icon_flickr.png"  align="absmiddle" /><span>flickr</span></a></li>
  <li><a href="http://www.youtube.com/user/thecoscup?feature=watch"><img src="<?php echo $theme_assets_uri;?>icon_utube.png"  align="absmiddle" /><span>Youtube</span></a></li>
</ul><!--social Mobile end-->
<!--底-->
<div id="footer">
	<ul>
    	<li>© 2013 COSCUP |<a href="#"> 聯絡我們</a> | </li>
        <li><a href="http://coscup.org/2006/" target="_blank">2006</a>|</li>
        <li><a href="http://coscup.org/2007/" target="_blank">2007</a>|</li>
        <li><a href="http://coscup.org/2008/" target="_blank">2008</a>|</li>
        <li><a href="http://coscup.org/2009/zh-tw/" target="_blank">2009</a>|</li>
        <li><a href="http://coscup.org/2010/" target="_blank">2010</a>|</li>
        <li><a href="http://coscup.org/2011/zh-tw/" target="_blank">2011</a>|</li>
        <li><a href="http://coscup.org/2012/zh-tw/" target="_blank" >2012</a>|</li>
        <div class="design">Design by <a href="http://www.lichenple.com" target="_blank">LICHENple</a></div>
    </ul>
    
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_assets_uri;?>script.js"></script></body>
<script type="text/javascript" src="<?php echo $theme_assets_uri;?>respond.min.js"></script>
<script type="text/javascript">
  jQuery(document).ready(function($){
    /* prepend menu icon */
    $('#nav-wrap').prepend('<div id="menu-icon"></div>');

    /* toggle nav */
    $("#menu-icon").on("click", function(){
      $("#mainNav").slideToggle();
      $(this).toggleClass("active");
    });
  });
</script>
<script type="text/javascript" src="<?php echo $theme_assets_uri;?>swipe.js"></script>
<script>
  // pure JS
  var elem = document.getElementById('mySwipe');
  window.mySwipe = Swipe(elem, {
    // startSlide: 4,
       auto: 3000,
    // continuous: true,
    // disableScroll: true,
    // stopPropagation: true,
    // callback: function(index, element) {},
    // transitionEnd: function(index, element) {}
  });

  // with jQuery
  // window.mySwipe = $('#mySwipe').Swipe().data('Swipe');
</script>
</html>

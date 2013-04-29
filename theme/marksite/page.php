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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $lc->lang ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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

<!--phone-->
<meta name="viewport" content="width=320" />
<meta name="viewport" content="width=480" />
<meta name="viewport" content="width=600" />
<meta name="viewport" content="width=685" />
<meta name="viewport" content="width=device-width" />
<link rel="apple-touch-icon" href="<?php echo $theme_assets_uri;?>ios-fav.jpg" />
<link media="only screen and (max-width:685px)" href="<?php echo $theme_assets_uri;?>mobile.css" type= "text/css" rel="stylesheet" />
<link media="screen and (min-width:686px)" href="<?php echo $theme_assets_uri;?>style.css" type="text/css" rel="stylesheet" />
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
	    	<li><a href="#">EN</a></li>                                                
	        <li><a href="#">簡中</a></li>
	        <li><a href="#">正體</a></li>
	        <li><a href="#">日文</a></li>
	    </ul>
	    <ul id="social">
	    	<li><a href="#" title="facebook"><img src="<?php echo $theme_assets_uri;?>icon_fb.png"/></a></li>                                                
	        <li><a href="#" title="plurk"><img src="<?php echo $theme_assets_uri;?>icon_plurk.png" /></a></li>
	        <li><a href="#" title="twitter"><img src="<?php echo $theme_assets_uri;?>icon_twitter.png" /></a></li>
	        <li><a href="#" title="blog"><img src="<?php echo $theme_assets_uri;?>icon_blog.png" /></a></li>
	        <li><a href="#" title="flickr"><img src="<?php echo $theme_assets_uri;?>icon_flickr.png"  /></a></li>
	        <li><a href="#" title="youtube"><img src="<?php echo $theme_assets_uri;?>icon_utube.png" /></a></li>
	    </ul>
        <ul id="mainNav">
          <?php echo $this->menu(1,2); ?>
          <li class="open"><a href="<?php echo $home_path.$this->current[0]."/openwall/";?>" title="OPEN">We (heart) Open.</a></li>
   	  </ul>
        
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
<!--底-->
<div id="footer">
	<ul>
    	<li>© 2013 COSCUP |<a href="#"> 聯絡我們</a> | </li>
        <li><a href="#">2006</a>|</li>
        <li><a href="#">2007</a>|</li>
        <li><a href="#">2008</a>|</li>
        <li><a href="#">2009</a>|</li>
        <li><a href="#">2010</a>|</li>
        <li><a href="#">2011</a>|</li>
        <li><a href="#">2012</a>|</li>
        <div class="design">Design by <a href="http://www.lichenple.com">LICHENple</a></div>
    </ul>
    
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_assets_uri;?>script.js"></script></body>
</html>

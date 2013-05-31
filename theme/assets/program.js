jQuery(document).ready(function($){
  var navHeight = $('#navTab').get(0).offsetTop;
  /* Program fixed Nav */
  $(window).scroll(function () {
    if ($(this).scrollTop() > navHeight) {
      $('#navTab').addClass("floatTab");
    } else {
      $('#navTab').removeClass("floatTab");
    }
  });
});

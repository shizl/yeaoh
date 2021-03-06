<?php
// $Id: maintenance-page.tpl.php,v 1.1.2.2 2011/01/06 07:45:39 danprobo Exp $
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>"
      lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">
<head>
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <?php print $styles; ?>
  <?php print $scripts; ?>
  <!--google analytic js start-->
  <script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-34926348-1']);
    _gaq.push(['_setDomainName', 'yeaoh.com']);
    _gaq.push(['_setAllowLinker', true]);
    _gaq.push(['_trackPageview']);

    (function () {
      var ga = document.createElement('script');
      ga.type = 'text/javascript';
      ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(ga, s);
    })();

  </script>
  <!--google analytic js end -->
</head>
<body class="<?php print $classes; ?>" <?php print $attributes; ?>>
<!-- Facebook Social Plugin Javascript start -->
<div id="fb-root"></div>
<script>(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=231262563644329";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>
<!-- Facebook Social Plugin Javascript end-->


<?php print $page_top; ?>
<?php print $page; ?>
<?php print $page_bottom; ?>

</body>
</html>

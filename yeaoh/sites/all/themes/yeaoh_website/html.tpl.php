<?php
// $Id: maintenance-page.tpl.php,v 1.1.2.2 2011/01/06 07:45:39 danprobo Exp $
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">
<head>
<link  href="http://fonts.googleapis.com/css?family=Didact+Gothic:regular" rel="stylesheet" type="text/css" >
<link href='http://fonts.googleapis.com/css?family=Fjalla+One' rel='stylesheet' type='text/css'>
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <?php print $styles; ?>
  <?php print $scripts; ?>
  
  	<script>
	jQuery(document).ready(function(){
	
	   jQuery('.fancybox').fancybox(); 
	     
	});
	</script>
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
<?php print $page_top ;?>
<?php print $page ;?>
<?php print $page_bottom ;?>
</body>
</html>
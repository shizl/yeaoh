<?php

/**
 * @file
 * Implementation to display a single Drupal page while offline.
 *
 * All the available variables are mirrored in page.tpl.php.
 *
 * @see template_preprocess()
 * @see template_preprocess_maintenance_page()
 * @see bartik_process_maintenance_page()
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">
<head>
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>
  <div id="center_m"><!-- start cneter -->
	  
	   <div id="page_top1"><!-- start page_top -->
			<div>
                          <img  src="sites/all/themes/yeaoh_website/logo.png" />
			</div>
    <div id="main-wrapper-m"><div id="main" class="clearfix">
      <div id="content" class="column"><div class="section">
        <a id="main-content"></a>
        <?php if ($title): ?><h1 class="title" id="page-title">Under Reconstruction</h1><?php endif; ?>
        <?php print $content; ?>
      </div></div> <!-- /.section, /#content -->
    </div></div> <!-- /#main, /#main-wrapper -->
		
   </div><!--center end -->
</div><!-- end page_wrapper-->

</body>
</html>
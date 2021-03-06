<div id="page_wrapper"><!-- start page_wrapper -->
  <div id="center"><!-- start cneter -->
    <div id="page_top"><!-- start page_top -->
      <div class="header">
        <div id="toplink"><!-- stat toplink-->
          <?php if ($page['top_link']): ?>
            <?php print render($page['top_link']); ?>
          <?php endif; ?>
        </div>
        <!-- end toplink-->
        <?php if ($logo): ?>
          <!-- start logo wrapper -->
          <div id="logo-wrapper">
            <div class="logo">
              <a href="<?php print $base_path ?>" title="<?php print t('Home') ?>"><img src="<?php print $logo ?>"
                                                                                        alt="<?php print t('Home') ?>"/></a>
            </div>
          </div><!-- end logo wrapper -->
        <?php endif; ?>
      </div>
      <div id="main-menu-box">
        <div id="main-menu" class="navigation">
          <?php $menu = menu_tree('main-menu');
          print render($menu);?>
        </div>
        <!-- /#main-menu -->
        <div id="main-menu-list-box">
          <div id="main-menu-list" class="clearfix"></div>
        </div>
      </div>
   </div>
      <!-- start centermain -->

      <div id="main_content">
        <div class="main_content">

          <?php if ($title): ?>
            <h1 class="title" id="page-title">
              <?php print $title; ?>
            </h1>
          <?php endif; ?>

          <?php if ($tabs): ?>
            <div class="tabs"><?php print render($tabs); ?></div>
          <?php endif; ?>
          <div class="s-cotnent"> <?php print render($page['content']); ?></div>
          <?php if ($page['sidebar_second']): ?>
            <?php print render($page['sidebar_second']); ?>
          <?php endif; ?>
        </div>
        <div class="protfolio">
          <?php if ($page['home_column3']): ?>
            <div class="pro_content clearfix"><?php print render($page['home_column3']); ?></div>
          <?php endif; ?>
        </div>
      </div>

      <div class="footer_main">
        <?php if ($page['foot_main']): ?>
          <div class="foot_content">  <?php print render($page['foot_main']); ?> </div>
        <?php endif; ?>
      </div>

    </div>
    <!-- end contermain -->

    <!-- start footer -->
    <div id="footer">
      <div class="footer-bottom">
        <?php if ($page['footer']): ?>
          <?php print render($page['footer']); ?>
        <?php endif; ?></div>
    </div>
  </div>
  <!--footer end -->

</div><!--center end -->
</div><!-- end page_wrapper-->


<?php if ($page['float_qq']): ?>
  <div class="float_qq"><?php print render($page['float_qq']); ?></div>
<?php endif; ?>

<script text="text/javascript">
  jQuery("#main-menu .root_menu li:first a").click(function () {
    jQuery(this).attr("href", "/");
  });
  jQuery("#main-menu .root_menu li:last a").click(function () {
    jQuery(this).attr("href", "/contact-us");
  });
</script>

<script language="javascript">
  jQuery('.root_menu li').click(function () {
    jQuery('.root_menu li').removeClass('active');
    jQuery(this).addClass('active');

  });
  function setLeaveMouse() {
    jQuery("#page_top .menu_des").slideUp(500);
    jQuery('.root_menu li').removeClass('active');
  }
  jQuery('#page_top').mouseleave(function () {
    setTimeout(setLeaveMouse, 1000);

  });


  jQuery("#edit-submitted-you-full-name").val("Your full name");
  jQuery("#edit-submitted-you-email").val("name@example.com");
  jQuery("#edit-submitted-body").val("Your question");
  jQuery(".webform-client-form .form-text").css({"padding-left": "5px", "color": "#999"});
  jQuery(".webform-client-form textarea").css({"padding-left": "5px", "color": "#999"});
  jQuery("#edit-submitted-you-full-name").focus(function () {
    jQuery(this).css("color", "#000");
    if (jQuery(this).val() == "Your full name") jQuery(this).val("");
  });
  jQuery("#edit-submitted-you-email").focus(function () {
    jQuery(this).css("color", "#000");
    if (jQuery(this).val() == "name@example.com") jQuery(this).val("");
  });
  jQuery("#edit-submitted-body").focus(function () {
    jQuery(this).css("color", "#000");
    if (jQuery(this).val() == "Your question") jQuery(this).val("");
  });
  jQuery("#edit-submitted-you-full-name").blur(function () {
    if (jQuery(this).val() == "") {
      jQuery(this).val("Your full name");
      jQuery(this).css("color", "#999");
    }
  });
  jQuery("#edit-submitted-you-email").blur(function () {
    if (jQuery(this).val() == "") {
      jQuery(this).val("name@example.com");
      jQuery(this).css("color", "#999");
    }
  });
  jQuery("#edit-submitted-body").blur(function () {
    if (jQuery(this).val() == "") {
      jQuery(this).val("Your question");
      jQuery(this).css("color", "#999");
    }
  });

  jQuery(".webform-client-form .form-submit").click(function () {
    var chmail = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    var chvarchar = /[a-zA-Z0-9]+$/;

    var fname = jQuery("#edit-submitted-you-full-name").val();
    var femail = jQuery("#edit-submitted-you-email").val();
    var fbody = jQuery("#edit-submitted-body").val();
    if (!chvarchar.test(fname) || fname == "Your full name") {
      alert("Please input your full name.");
      return false;
    }

    if (!chvarchar.test(fbody) || fbody == "Your question") {
      alert("Please input your question.");
      return false;
    }

  });
  jQuery("#block-locale-language .content").append('<div id="language_arrow"><a href="####"><img src="http://dev.yeaoh.com/sites/all/themes/yeaoh_website/images/flag_english.png"/><span>English</span></a></div>');
  jQuery("#language_arrow").click(function () {
    jQuery(".language-switcher-locale-session").animate({height: "0"}, 500);
  });
  jQuery(document).click(function () {
    if (jQuery(".language-switcher-locale-session").css("height") == "0px") {
      jQuery(".language-switcher-locale-session").animate({height: "0"}, 500);
    }
  })
  var str_url = window.location.href;
  var start_num = str_url.indexOf("?language=zh-hans");
  if (start_num > 0) {
    jQuery("#language_arrow img").attr("src", "http://dev.yeaoh.com/sites/all/themes/yeaoh_website/images/zh-hans.png");
    jQuery("#language_arrow span").html("中文简体");
  }

  -- >
</script>

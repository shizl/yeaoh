<div id="page_wrapper"><!-- start page_wrapper -->
  <div id="center"><!-- start cneter -->
	  
	   <div id="page_top"><!-- start page_top -->
			<div class="header">
				<div id="toplink"><!-- stat toplink-->
				   <?php if($page['top_link']): ?>
					  <?php  print render ($page['top_link']) ;?>
				   <?php endif ; ?>
				 </div><!-- end toplink-->
				<?php if ($logo): ?> 
				<!-- start logo wrapper -->
				<div id="logo-wrapper">
					<div class="logo">
						<a href="<?php print $base_path ?>" title="<?php print t('Home') ?>"><img src="<?php print $logo ?>" alt="<?php print t('Home') ?>" /></a>
					</div>
				</div><!-- end logo wrapper -->
				<?php endif; ?>

				<div id="main-menu"><!-- start main_menu -->
                                  	<?php if ($main_menu ): ?>
						<?php 
						 $value_menu_simple=3;
						function show_menu($mlid=0,$lid=0,$mname='root_menu'){
							$rootmenu=db_query("SELECT `mlid`,`link_path`,`link_title`,'options' FROM `menu_links` WHERE `menu_name`='main-menu' AND `plid` ='$mlid' AND `language`='".(!empty($_REQUEST['language'])?$_REQUEST['language']:'en')."' ORDER BY `weight` ASC");
							$menus='';
							++$lid;
							$aggmentq= $mname=="child_menu" ? '':$_GET['q'];
							$root_menu="";
							$m=1;
							foreach($rootmenu as $menu){ 
								
									if($lid>=2){
										$menus.='<li><a href="'.($menu->link_path=='<front>' ? '':drupal_get_path_alias($menu->link_path)).'"> '.($lid > 2 ? '>> ':'').$menu->link_title.'</a>'.($lid == 2 ? '<hr />':'').show_menu($menu->mlid,$lid,'child_menu').'</li>';
									}else{
										$root_menu.='<li class="'.($aggmentq == $menu->link_path || ($aggmentq=='node' && $menu->link_path=="<front>") ? 'active':'').'"><a href="javascript:show_box('.$m.')"> '.$menu->link_title.'</a></li>';
										$menus.=show_menu($menu->mlid,$lid,'child_menu');
										++$m;
									}
								
							}
							return ($lid==1 ? '<ul class="'.$mname.' menu_'.$lid.'">'.$root_menu.'</ul>':'').($menus<>'' ? ( $lid==1 ? '</div></div><div class="menu_des"><div class="menu_box"><div class="all_menu">'.$menus.'</div></div></div>':'<ul class="'.$mname.' menu_'.$lid.'">'.$menus.'</ul>'):'');
						}
						echo show_menu();
						
						?>
					 
				 <?php endif; ?>
				 </div><!-- main menu end -->
			</div>
			<div class="head_bottom_bg"></div> </div> <!-- page top end-->
	   <!-- start centermain -->
	   <div id="centermain">
			 <div id="big_bg_image"><!-- start big_bg_image -->
			    
		  		<div class="banner_content"><?php print render($page['home_banner']) ;?></div>
		  	 <div class="banner_nav"></div>	
				<div class="float_qq"><?php print render($page['float_qq']);?></div>
			 </div><!-- end big_bg_image -->
			  <div class="aboutus_developer">
                <?php  if($page['home_column1']):  ?>
		           <div class="col_cotnent"> <?php  print render($page['home_column1']) ;?></div>
	             <?php  endif ; ?>	
			</div>
			<div class="service">
			   <?php if($page['home_column2']): ?>
			     <div class="ser_content"><?php print render($page['home_column2']) ;?></div>
			   <?php endif ; ?> 
			</div>		
			<div class="protfolio">
			   <?php if($page['home_column3']): ?>
			     <div class="pro_content"><?php print render($page['home_column3']) ;?></div>
			   <?php endif ; ?> 	   
			</div>	
			<div class="footer_main">
			  <?php if($page['foot_main']): ?>
				<div class="foot_content">  <?php  print render ($page['foot_main']) ;?> </div>
			   <?php endif ; ?>	
			</div>
			
		</div><!-- end contermain -->

		<!-- start footer -->
		<div id="footer">
			<div class="footer-bottom">
			   <?php if($page['footer']): ?>
				  <?php  print render ($page['footer']) ;?>
			   <?php endif ; ?></div>
			</div>
		</div>
		<!--footer end -->
		
   </div><!--center end -->
</div><!-- end page_wrapper-->
<script text="text/javascript">
 jQuery("#main-menu .root_menu li:first a").click(function(){
     jQuery(this).attr("href","/");
});
 jQuery("#main-menu .root_menu li:last a").click(function(){
     jQuery(this).attr("href","/contact");
});
</script>

				<script language="javascript"><!--
				var m=0;
function show_box(sid){//manin menu show 
	if(jQuery('.menu_des').css('display')=='none'){
		jQuery('.all_menu').css('margin-left',(1-sid)*990);
	}else{
		jQuery('.all_menu').animate({marginLeft: (1-sid)*990}, 500);
	}
	if(m==sid){
		jQuery('.menu_des').slideUp();
		m=0;
	}else{
		m=sid;
		jQuery('.menu_des').slideDown();
	}
	
}
jQuery('.root_menu li').click(function(){
	jQuery('.root_menu li').removeClass('active');
	jQuery(this).addClass('active');

});
function setLeaveMouse(){
   jQuery("#page_top .menu_des").slideUp(500);
   jQuery('.root_menu li').removeClass('active');
}
jQuery('#page_top').mouseleave(function(){
   setTimeout(setLeaveMouse,1000);
   
});

jQuery("#edit-submitted-you-full-name").val("Your full name");
jQuery("#edit-submitted-you-email").val("name@example.com");
jQuery("#edit-submitted-body").val("Your question");
jQuery(".webform-client-form .form-text").css({"padding-left":"5px","color":"#999"});
jQuery(".webform-client-form textarea").css({"padding-left":"5px","color":"#999"});
jQuery("#edit-submitted-you-full-name").focus(function(){
jQuery(this).css("color","#000");
if(jQuery(this).val() == "Your full name") jQuery(this).val("");
});
jQuery("#edit-submitted-you-email").focus(function(){
jQuery(this).css("color","#000");
if(jQuery(this).val() == "name@example.com") jQuery(this).val("");
});
jQuery("#edit-submitted-body").focus(function(){
jQuery(this).css("color","#000");
if(jQuery(this).val() == "Your question") jQuery(this).val("");
});
jQuery("#edit-submitted-you-full-name").blur(function(){
if(jQuery(this).val() == "") {jQuery(this).val("Your full name");jQuery(this).css("color","#999");}
});
jQuery("#edit-submitted-you-email").blur(function(){
if(jQuery(this).val() == "") {jQuery(this).val("name@example.com");jQuery(this).css("color","#999");}
});
jQuery("#edit-submitted-body").blur(function(){
if(jQuery(this).val() == ""){ jQuery(this).val("Your question");jQuery(this).css("color","#999");}
});

jQuery(".webform-client-form .form-submit").click(function(){
var chmail=/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
var chvarchar=/[a-zA-Z0-9]+$/;

var fname=jQuery("#edit-submitted-you-full-name").val();
var femail=jQuery("#edit-submitted-you-email").val();
var fbody=jQuery("#edit-submitted-body").val();
if(!chvarchar.test(fname) || fname=="Your full name" ){
alert("Please input your full name.");
return false;
}

if(!chmail.test(femail) || femail=="name@example.com" ){
alert("Please input correctly email.");
return false;
}
if(!chvarchar.test(fbody) || fbody=="Your question" ){
alert("Please input your question.");
return false;
}

});
jQuery("#block-locale-language .content").append('<div id="language_arrow"><a><img src="sites/all/themes/yeaoh_website/images/flag_english.png"/><span>English</span></a></div>');
jQuery("#language_arrow").click(function(){
     jQuery(".language-switcher-locale-session").animate({height:"0"},500);
});
jQuery(document).click(function(){
    if(jQuery(".language-switcher-locale-session").css("height")=="0px")
     {
        jQuery(".language-switcher-locale-session").animate({height:"0"},500);
     }
})
/*var str_url=window.location.href;
var start_num=str_url.indexOf("?language=zh-hans");
if(start_num>0)
{
   jQuery("#language_arrow img").attr("src","sites/all/themes/yeaoh_website/images/zh-hans.png");
   jQuery("#language_arrow span").html("中文简体");
}
jQuery(".zh-hans a").attr("href","/?language=zh-hans");*/
-->
</script>

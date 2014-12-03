<?php

/**
 * @file
 * Main view template.
 *
 * Variables available:
 * - $classes_array: An array of classes determined in
 *   template_preprocess_views_view(). Default classes are:
 *     .view
 *     .view-[css_name]
 *     .view-id-[view_name]
 *     .view-display-id-[display_name]
 *     .view-dom-id-[dom_id]
 * - $classes: A string version of $classes_array for use in the class attribute
 * - $css_name: A css-safe version of the view name.
 * - $css_class: The user-specified classes names, if any
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposed: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 *
 * @ingroup views_templates
 */
?>
<div class="<?php print $classes; ?>">
  <?php print render($title_prefix); ?>
  <?php if ($title): ?>
    <?php print $title; ?>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
  <?php if ($header): ?>
    <div class="view-header">
      <?php print $header; ?>
    </div>
  <?php endif; ?>

  <?php if ($exposed): ?>
    <div class="view-filters">
      <?php print $exposed; ?>
    </div>
  <?php endif; ?>

  <?php if ($attachment_before): ?>
    <div class="attachment attachment-before">
      <?php print $attachment_before; ?>
    </div>
  <?php endif; ?>

  <?php if ($rows): ?>
    <div class="view-title">
	 <?php  print $rows; ?>
	</div>
    <div class="view-content">
      <?php print $rows; ?>
      <div class="service_arrow">
           <div id="arrow_pre"><a href="javascript:void(0)"><img src="/sites/all/themes/yeaoh_website/images/arrow_pre.jpg"></a></div>
           <div id="arrow_next"><a href="javascript:void(0)"><img src="/sites/all/themes/yeaoh_website/images/arrow_next.jpg"></a></div>
      </div>
    </div>
  <?php elseif ($empty): ?>
    <div class="view-empty">
      <?php print $empty; ?>
    </div>
  <?php endif; ?>

  <?php if ($pager): ?>
    <?php print $pager; ?>
  <?php endif; ?>

  <?php if ($attachment_after): ?>
    <div class="attachment attachment-after">
      <?php print $attachment_after; ?>
    </div>
  <?php endif; ?>

  <?php if ($more): ?>
    <?php print $more; ?>
  <?php endif; ?>

  <?php if ($footer): ?>
    <div class="view-footer">
      <?php print $footer; ?>
    </div>
  <?php endif; ?>

  <?php if ($feed_icon): ?>
    <div class="feed-icon">
      <?php print $feed_icon; ?>
    </div>
  <?php endif; ?>

</div><?php /* class view */ ?>
<script type="text/javascript">
jQuery(document).ready(function(){
      href_Of_Page=window.location.href;  

       queryString  = href_Of_Page.substring(href_Of_Page.indexOf('service'));
       
      
      if(queryString!="" || queryString !=null){
     
            if(queryString=="service/optimization-services/Google-SEO"){
              tnid=12;
            }else if(queryString=="service/web-development/Magento-Store"){
              tnid =13;
            }else if(queryString=="service/web-development/Drupal-Website"){
              tnid =32;
            }else if(queryString=="service/web-development/Website-Design"){
              tnid =22;
            }else if(queryString=="service/optimization-services/Facebook-SEM"){
              tnid =25;
            }else if(queryString=="service/optimization-services/sina-werbo-sem"){
              tnid =11;
            }else if(queryString=="service/mobile-solutions/Website-Mobile-Version"){
              tnid =37;
            }else if(queryString=="service/mobile-solutions/IOS-Development"){
              tnid =36;
            }else if(queryString=="service/mobile-solutions/Android-Application"){
              tnid=21;
            }else if(queryString=="service/other-services/Business-Consulting"){
              tnid =23;
            }else if(queryString=="service/other-services/Hosting-Solutions"){
              tnid =34;
            }else if(queryString=="service/other-services/Content-Authoring"){
              tnid =35;
            }
      
      jQuery('.view-taxonomy-term .view-content .views-field-nid').each(function(){
	  var  bnid = parseInt(jQuery(this).text());
	 	 if(tnid==bnid){
	     jQuery(this).parent().find('.views-field-body').css({"display":"block"});
		  
		    id = jQuery(this).find('.field-content').text();
			 jQuery('.view-taxonomy-term .view-title .views-field-nid').each(function(){
			  cid = parseInt(jQuery(this).text())
				 if(id == cid ){
				    jQuery(this).parent().find('.views-field-title').css({'background':'#fff'});
				 }else{
				    jQuery(this).parent().find('.views-field-title').css({'background':'none'});
				 }
			 });

	     }else{
		    jQuery(this).parent().find('.views-field-body').css({"display":"none"});
		 }
	});  
      }
   });
</script>

<script>
 jQuery('.view-taxonomy-term .view-title .views-field-title').click(function(){
  var  tnid = parseInt(jQuery(this).parent().find('.views-field-nid .field-content').text());
	jQuery('.view-taxonomy-term .view-content .views-field-nid').each(function(){
	  var  bnid = parseInt(jQuery(this).text());
	 	 if(tnid==bnid){
	     jQuery(this).parent().find('.views-field-body').css({"display":"block"});
		  
		    id = jQuery(this).find('.field-content').text();
			 jQuery('.view-taxonomy-term .view-title .views-field-nid').each(function(){
			  cid = parseInt(jQuery(this).text())
				 if(id == cid ){
				    jQuery(this).parent().find('.views-field-title').css({'background':'#fff'});
				 }else{
				    jQuery(this).parent().find('.views-field-title').css({'background':'none'});
				 }
			 });

	     }else{
		    jQuery(this).parent().find('.views-field-body').css({"display":"none"});
		 }
	});
	  
 });
 
 jQuery(".service_arrow #arrow_pre img").click(function(){
     var  lenth=jQuery(".view-taxonomy-term .view-content .views-row").length;
     for(var i=1;i<lenth+1;i++)
     {
         if(jQuery(".view-taxonomy-term .view-content .views-row-"+i+" .views-field-body").css("display")=="block")
         {
            jQuery(".view-taxonomy-term .view-content .views-row-"+i+" .views-field-body").hide();
            jQuery(".view-taxonomy-term .view-title  .views-row-"+i+" .views-field-title").css({'background':'none'});
            i--;
            if(0==i)
            {
               i=lenth;
               jQuery(".view-taxonomy-term .view-title  .views-row-"+i+".views-field-title").css({'background':'#fff'});
               jQuery(".view-taxonomy-term .view-content .views-row-"+i+" .views-field-body").show();
            }
            jQuery(".view-taxonomy-term .view-content .views-row-"+i+" .views-field-body").show();
            jQuery(".view-taxonomy-term .view-title  .views-row-"+i+"  .views-field-title").css({'background':'#fff'});
            break;
         }
     }
     
 });
 jQuery(".service_arrow #arrow_next img").click(function(){
     var  lenth=jQuery(".view-taxonomy-term .view-content .views-row").length;
     var  end=lenth+1;
     for(var i=1;i<end;i++)
     {
         if(jQuery(".view-taxonomy-term .view-content .views-row-"+i+" .views-field-body").css("display")=="block")
         {
            jQuery(".view-taxonomy-term .view-content .views-row-"+i+" .views-field-body").hide();
            jQuery(".view-taxonomy-term .view-title  .views-row-"+i+" .views-field-title").css({'background':'none'});
            i++;
            if(i==end){
              i=1;
            jQuery(".view-taxonomy-term .view-content .views-row-"+i+" .views-field-body").show();
            jQuery(".view-taxonomy-term .view-title  .views-row-"+i+"  .views-field-title").css({'background':'#fff'});     
            }
            jQuery(".view-taxonomy-term .view-content .views-row-"+i+" .views-field-body").show();
            jQuery(".view-taxonomy-term .view-title  .views-row-"+i+"  .views-field-title").css({'background':'#fff'});
            break;
         }
     }
     
 });
</script>

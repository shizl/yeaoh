jQuery(function(){
    var box = "#views_slideshow_cycle_main_banner-block";
    var int=self.setInterval(check,10);
    function check(){
      jQuery('.views-slideshow-cycle-main-frame-row').each(function(){
        if("block" == jQuery(this).css("display")){
          color = jQuery(this).find('.banner_background_color').css("background-color");
          jQuery(box).css({background:color});
        }
      });
    }    
});

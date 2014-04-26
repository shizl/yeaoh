jQuery(document).ready(function(){
    terimonials_blog();
    banner_background();
    quick_quote();
    });

function terimonials_blog(){
  var box = '#block-views-tertimonials-and-blog-block .view-content .views-row';
  var bbox = '#block-views-tertimonials-and-blog-block .view-content';
  var image = '.views-field-field-user-image img';
  var body = '.views-field-body';
  var name = '.views-field-field-company-name';
  var body_box = '.views-field-body .field-content';
  timeid = doop_show();
  function doop_show(){ 
    var timeid = window.setInterval(check,3000);
    function check(){
      var num = Math.random();
      var view_row = Math.round(num*10);
      if (view_row == 0) {
        view_row = 10;
      }
      var views_row = box+'-'+view_row;
      reset();
      show(views_row);
    }
    return timeid;
  }
  function reset(){
    jQuery(image,box).stop().css({opacity:0.5});
    jQuery(body_box,box).stop().css({opacity:0});
    jQuery(body,box).stop().css({display:'none'});
    jQuery(name,box).stop().css({display:'none'});
  }
  function show(var_box){
    jQuery(image,var_box).stop().animate({opacity:1},'3000');
    jQuery(body_box,var_box).stop().animate({opacity:1},'3000');
    jQuery(body,var_box).stop().css({display:'block'});
    jQuery(name,var_box).stop().css({display:'block'});
  }
  jQuery(box).mouseenter(
      function(){
      window.clearInterval(timeid);
      reset();
      show(this);
      }
      );
  jQuery(box).mouseleave(
      function(){
      //reset();
      window.clearInterval(timeid);
      timeid = doop_show();
      }
      );
};

function banner_background(){
  var box = "#views_slideshow_cycle_main_banner-block";
  var timeid = window.setInterval(check,1);
  function check(){
    jQuery('.views-slideshow-cycle-main-frame-row').each(function(){
        if("block" == jQuery(this).css("display")){
        color = jQuery(this).find('.banner_background_color').css("background-color");
        jQuery(box).css({background:color});
        }
        });
  }    
};

function quick_quote(){
  var box = ".float_qq";
  var button_open =  "#block-block-21 .quick_quote";
  var button_close = "#block-block-21 .quick_quote2";
  jQuery(box).css({'right':'-374px'});
  if(jQuery(".float_qq form input").hasClass("error")){
    jQuery(box).css({'right':'0'});
    jQuery(button_open).css({'margin-left':'0'});
  }
  jQuery(button_open).click(function(){
      jQuery(box).stop().animate({'right':'0'});
      jQuery(button_open).stop().animate({'margin-left':'0'});
      })
  jQuery(button_close).click(function(){
      jQuery(box).stop().animate({'right':'-374px'});
      jQuery(button_open).stop().animate({'margin-left':'-149px'});
      })
  jQuery(box).submit(function(){
      jQuery(box).stop().animate({'right':'0'});
      });
};


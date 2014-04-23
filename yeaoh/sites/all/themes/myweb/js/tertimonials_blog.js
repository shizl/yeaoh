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

jQuery(document).ready(function () {
    terimonials_blog();
    // banner_background();
    quick_quote();
    main_menu_content();
    banner_width();
    banner_height();
    service_choose();
});
function service_choose() {
    var nid = getUrlParam('nid');
    var nclass = '.nid' + '.' + nid;
    var pager = '.views-slideshow-pager-field-item';
    var npager = jQuery(nclass, pager).parents(pager);
    jQuery(npager).trigger("click");
}
function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]);
    return null; //返回参数值
}
function banner_width() {
    var width = jQuery(window).width();
    var slidebox = '.view-banner .views-slideshow-cycle-main-frame-row';
    var slideshowbox = '#views_slideshow_cycle_teaser_section_banner-block';
    jQuery('#views_slideshow_cycle_main_banner-block img').css({width: width});
    jQuery(window).resize(function () {
        var width = jQuery(window).width();
        if (width > 1200) {
            jQuery('#views_slideshow_cycle_main_banner-block img').width(width);
            jQuery(slidebox).width(width);
            jQuery(slideshowbox).width(width);
        } else {
            jQuery('#views_slideshow_cycle_main_banner-block img').width('1200px');
            jQuery(slidebox).width('1200px');
            jQuery(slideshowbox).width('1200px');
        }
    });
}

function banner_height() {
    var slideboxm = '.view-banner .views-slideshow-cycle-main-frame-row';
    var slidebox = '.view-banner .views-slideshow-cycle-main-frame';
    jQuery(window).resize(function () {
        imgh = jQuery('.views-slideshow-cycle-main-frame-row-item:visible', slidebox).css("height");
        jQuery(slideboxm).height(imgh);
        jQuery(slidebox).height(imgh);
    });
}
function terimonials_blog() {
    var box = '#block-views-tertimonials-and-blog-block .view-content .views-row';
    var bbox = '#block-views-tertimonials-and-blog-block .view-content';
    var image = '.views-field-field-user-image img';
    var body = '.views-field-body';
    var name = '.views-field-field-company-name';
    var body_box = '.views-field-body .field-content';
    timeid = doop_show();
    function doop_show() {
        var timeid = window.setInterval(check, 3000);

        function check() {
            var num = Math.random();
            var view_row = Math.round(num * 10);
            if (view_row == 0) {
                view_row = 10;
            }
            var views_row = box + '-' + view_row;
            reset();
            show(views_row);
        }

        return timeid;
    }

    function reset() {
        jQuery(image, box).stop().css({opacity: 0.5});
        jQuery(body_box, box).stop().css({opacity: 0});
        jQuery(body, box).stop().css({display: 'none'});
        jQuery(name, box).stop().css({display: 'none'});
    }

    function show(var_box) {
        jQuery(image, var_box).stop().animate({opacity: 1}, '3000');
        jQuery(body_box, var_box).stop().animate({opacity: 1}, '3000');
        jQuery(body, var_box).stop().css({display: 'block'});
        jQuery(name, var_box).stop().css({display: 'block'});
    }

    jQuery(box).mouseenter(
        function () {
            window.clearInterval(timeid);
            reset();
            show(this);
        }
    );
    jQuery(box).mouseleave(
        function () {
            //reset();
            window.clearInterval(timeid);
            timeid = doop_show();
        }
    );
};

function banner_background() {
    var box = "#views_slideshow_cycle_main_banner-block";
    var timeid = window.setInterval(check, 1);

    function check() {
        jQuery('.views-slideshow-cycle-main-frame-row').each(function () {
            if ("block" == jQuery(this).css("display")) {
                color = jQuery(this).find('.banner_background_color').css("background-color");
                jQuery(box).css({background: color});
            }
        });
    }
};

function quick_quote() {
    var box = ".float_qq";
    var button_open = "#block-block-21 .quick_quote";
    var button_close = "#block-block-21 .quick_quote2";
    jQuery(box).css({'right': '-374px'});
    if (jQuery(".float_qq form input").hasClass("error")) {
        jQuery(box).css({'right': '0'});
        jQuery(button_open).css({'margin-left': '0'});
    }
    jQuery(button_open).click(function () {
        jQuery(box).stop().animate({'right': '0'});
        jQuery(button_open).stop().animate({'margin-left': '0'});
    })
    jQuery(button_close).click(function () {
        jQuery(box).stop().animate({'right': '-374px'});
        jQuery(button_open).stop().animate({'margin-left': '-149px'});
    })
    jQuery(box).submit(function () {
        jQuery(box).stop().animate({'right': '0'});
    });
};

function main_menu_content() {
    var main_menu_box = 'div.main-menu-box';
    var main_box = '#main-menu ul:first';
    var main_item = '#main-menu ul li';
    var main_menu_list_box = '#main-menu-list';
    var main_list_box = '#main-menu-list';
    var main_list_box_ul = '#main-menu-list div';
    jQuery(main_item).click(function () {

        var content = jQuery('div.main-menu-content', main_list_box).hasClass('main-menu-content');
        var ok = jQuery(this).find('ul.menu:first').find('li').hasClass('expanded');
        if (ok) {
            var temp = jQuery(this).find('ul.menu').html();
            jQuery(main_list_box).html('<div class="main-menu-content"><ul>' + temp + '</ul></div>');
            jQuery(main_list_box).stop().animate({height: '175px'});
            jQuery(main_list_box).find('ul').css({display: 'block'});
            var width = jQuery('div.main-menu-content', main_list_box).width();
            var dw = jQuery(main_menu_list_box).width();
            var dww = dw - width;
            jQuery('div.main-menu-content', main_list_box).css({left: dww});

            jQuery(this).find('ul.menu').addClass('active');
            jQuery(this).find('span.nolink').addClass('active');

            var prev = jQuery(this).prevAll().find('ul.menu').hasClass('active');
            var next = jQuery(this).nextAll().find('ul.menu').hasClass('active');
            if (prev) {
                var prev_content = jQuery(this).prevAll().find('ul.menu').html();
                jQuery(this).prevAll().find('ul.menu').removeClass('active');
                jQuery(this).prevAll().find('span.nolink').removeClass('active');

                jQuery('<div class="main-menu-prev"><ul>' + prev_content + '</ul></div>').insertBefore(main_list_box_ul);

                jQuery('div.main-menu-prev', main_list_box).css({display: 'none'});
                jQuery('div.main-menu-content', main_list_box).css({left: '1000px'});
                //    jQuery('div.main-menu-prev',main_list_box).stop().animate({left:'-1376px'});
                var width = jQuery('div.main-menu-content', main_list_box).width();
                var dw = jQuery(main_menu_list_box).width();
                jQuery('div.main-menu-content', main_list_box).stop().animate({left: dw - width});

                jQuery(main_list_box).find('ul').css({display: 'block'});
            }
            if (next) {
                var next_content = jQuery(this).nextAll().find('ul.menu').html();
                jQuery(this).nextAll().find('ul.menu').removeClass('active');
                jQuery(this).nextAll().find('span.nolink').removeClass('active');

                jQuery('<div class="main-menu-next"><ul>' + next_content + '</ul></div>').insertAfter(main_list_box_ul);

                jQuery('div.main-menu-next', main_list_box).css({display: 'none'});
                jQuery('div.main-menu-content', main_list_box).css({left: '0px'});
                //      jQuery('div.main-menu-next',main_list_box).stop().animate({left:'0px'});
                var width = jQuery('div.main-menu-content', main_list_box).width();
                var dw = jQuery(main_menu_list_box).width();
                jQuery('div.main-menu-content', main_list_box).stop().animate({left: dw - width});

                jQuery(main_list_box).find('ul').css({display: 'block'});
            }
            var prev = jQuery('div.main-menu-prev', main_list_box).hasClass('main-menu-prev');
            var next = jQuery('div.main-menu-next', main_list_box).hasClass('main-menu-next');
            if (!(prev || next) && content) {
                jQuery(main_list_box).stop().animate({height: '0px'});
                jQuery(main_item).find('ul.menu').removeClass('active');
                jQuery(main_item).find('span.nolink').removeClass('active');
                jQuery(main_list_box).html('');
            }

            jQuery(this).siblings().find('ul.menu').css({display: 'none'});
            jQuery(this).siblings().find('ul.menu').removeClass('active');
            jQuery(this).siblings().find('span.nolink').removeClass('active');
        }
    })
    jQuery(main_list_box).mouseleave(function () {
        jQuery(main_list_box).stop().animate({height: '0px'});
        jQuery(main_item).find('ul.menu').removeClass('active');
        jQuery(main_item).siblings().find('span.nolink').removeClass('active');
        jQuery(main_list_box).html('');
    })
};

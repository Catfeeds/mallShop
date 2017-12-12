/**scrm5-common begin
 * john@renlaifeng.cn
 * 2016/06/15 12:00
 */
console.log("%cmobiwind.cn","background: rgba(252,234,187,1);background: -moz-linear-gradient(left, rgba(252,234,187,1) 0%, rgba(175,250,77,1) 12%, rgba(0,247,49,1) 28%, rgba(0,210,247,1) 39%,rgba(0,189,247,1) 51%, rgba(133,108,217,1) 64%, rgba(177,0,247,1) 78%, rgba(247,0,189,1) 87%, rgba(245,22,52,1) 100%);background: -webkit-gradient(left top, right top, color-stop(0%, rgba(252,234,187,1)), color-stop(12%, rgba(175,250,77,1)), color-stop(28%, rgba(0,247,49,1)), color-stop(39%, rgba(0,210,247,1)), color-stop(51%, rgba(0,189,247,1)), color-stop(64%, rgba(133,108,217,1)), color-stop(78%, rgba(177,0,247,1)), color-stop(87%, rgba(247,0,189,1)), color-stop(100%, rgba(245,22,52,1)));background: -webkit-linear-gradient(left, rgba(252,234,187,1) 0%, rgba(175,250,77,1) 12%, rgba(0,247,49,1) 28%, rgba(0,210,247,1) 39%, rgba(0,189,247,1) 51%, rgba(133,108,217,1) 64%, rgba(177,0,247,1) 78%, rgba(247,0,189,1) 87%, rgba(245,22,52,1) 100%);background: -o-linear-gradient(left, rgba(252,234,187,1) 0%, rgba(175,250,77,1) 12%, rgba(0,247,49,1) 28%, rgba(0,210,247,1) 39%, rgba(0,189,247,1) 51%, rgba(133,108,217,1) 64%, rgba(177,0,247,1) 78%, rgba(247,0,189,1) 87%, rgba(245,22,52,1) 100%);background: -ms-linear-gradient(left, rgba(252,234,187,1) 0%, rgba(175,250,77,1) 12%, rgba(0,247,49,1) 28%, rgba(0,210,247,1) 39%, rgba(0,189,247,1) 51%, rgba(133,108,217,1) 64%, rgba(177,0,247,1) 78%, rgba(247,0,189,1) 87%, rgba(245,22,52,1) 100%);background: linear-gradient(to right, rgba(252,234,187,1) 0%, rgba(175,250,77,1) 12%, rgba(0,247,49,1) 28%, rgba(0,210,247,1) 39%, rgba(0,189,247,1) 51%, rgba(133,108,217,1) 64%, rgba(177,0,247,1) 78%, rgba(247,0,189,1) 87%, rgba(245,22,52,1) 100%);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fceabb', endColorstr='#f51634', GradientType=1 );font-size:2em;font-family:helvetica");

/* 头部下拉菜单 */
$(function () {
    // 请选择常用快捷功能 打开和关闭
    $(".select-hd").click(function (e) {
        $(".select-bd").toggle();
        e.stopPropagation();
    });
    $(".select-bd li").click(function () {
        $(this).each(function () {
            $(".select-hd span").html($(this).html());
            $(".select-bd").hide();
        })
    });

    // 点击其他位置收起下拉菜单
    $(document).click(function () {
        $(".select-bd").hide();
    })
});


/* 左边栏菜单 */
$(function () {
    // 左边栏菜单 点击展开收起
    $(".sidebar-class-1").click(function () {

        $(this).addClass("item-active").children(".sidebar-class-2").slideDown(200);
        $(this).siblings(".sidebar-class-1").removeClass("item-active").children(".sidebar-class-2").slideUp(200);
    });

    // 二级菜单点击效果
    $(".sidebar-class-2 li").click(function () {
        $(".sidebar-class-2 li").siblings(".sidebar-class-2 li").removeClass("item-active");
        $(this).addClass("item-active");
    });

    // 二级菜单选中时二级列表展开
    if ($(".sidebar-class-2 li").hasClass("item-active")) {
        $(".sidebar-class-2").find(".item-active").parent().show();
    }
});
/* scrm5-common end !
 以上代码为公共头部和侧边栏请勿轻易修改 */


/* 模态框 scrm5-Popup.html */
/* Mini窗 成功啦！ */
$(function(){
    $(".js-mini-1").click(function () {
        $(".js-wrap-mini-1").fadeIn(120).delay(1200).fadeOut(120);
    });
})
/* Mini窗 失败啦！ */
$(function(){
    $(".js-mini-2").click(function () {
        $(".js-wrap-mini-2").fadeIn(120).delay(1200).fadeOut(120);
    });
})
/* Mini窗 警告哦！ */
$(function(){
    $(".js-mini-3").click(function () {
        $(".js-wrap-mini-3").fadeIn(120).delay(1200).fadeOut(120);
    });
})

/* 小弹窗 样式-1 */
$(function () {
    // 点击触发模态框
    $(".js-small-1").click(function () {
        $(".wrap-small-1").fadeIn(120);
    });

    // 点击关闭图标和灰色包裹层关闭模态框
    $(".js-icon-close").click(function () {
        $(".wrap-small-1").fadeOut(120);
    });
});


/* 小弹窗 样式-2 */
$(function () {
    // 点击触发模态框
    $(".js-small-2").click(function () {
        $(".wrap-small-2").fadeIn(120);
    });

    // 点击关闭图标和灰色包裹层关闭模态框
    $(".js-icon-close").click(function () {
        $(".wrap-small-2").fadeOut(120);
    });
});


/* 小弹窗 样式-3 */
$(function () {
    // 点击触发模态框
    $(".js-small-3").click(function () {
        $(".wrap-small-3").fadeIn(120).delay(600).fadeOut(120);
    });
});


/* 小弹窗 是否删除 */
$(function(){
    // 点击触发模态框
    $(".js-del-or-not").click(function(){
        $(".js-del-or-not-wrap").fadeIn(120);
    });
});



/* 一般弹窗 样式-1 */
$(function () {
    // 点击触发模态框
    $(".js-middle-1").click(function () {
        $(".wrap-middle-1").fadeIn(120);
    });

    // 点击关闭图标和灰色包裹层关闭模态框
    $(".js-icon-close,.js-ok").click(function () {
        $(".wrap-middle-1").fadeOut(120);
    });
});


/* 一般弹窗 样式-2 */
$(function () {
    // 点击触发模态框
    $(".js-middle-2").click(function () {
        $(".wrap-middle-2").fadeIn(120);
    });

    // 点击关闭图标和灰色包裹层关闭模态框
    $(".js-icon-close").click(function () {
        $(".wrap-middle-2").fadeOut(120);
    });
});


/* 一般弹窗 样式-3 */
$(function () {
    // 点击触发模态框
    $(".js-middle-3").click(function () {
        $(".wrap-middle-3").fadeIn(120);
    });

    // 点击关闭图标和灰色包裹层关闭模态框
    $(".js-icon-close,.js-ok").on("click", function () {
        $(".wrap-middle-3").fadeOut(120);
    });
});


/* 特殊弹窗 样式-1 */
$(function () {
    // 点击触发模态框
    $(".js-pro-1").click(function () {
        $(".wrap-pro-1").fadeIn(120);
    });

    // 点击关闭图标和灰色包裹层关闭模态框
    $(".js-icon-close,.js-ok").click(function () {
        $(".wrap-pro-1").fadeOut(120);
    });
});


/* 特殊弹窗 样式-2 */
$(function () {
    // 点击触发模态框
    $(".js-pro-2").click(function () {
        $(".wrap-pro-2").fadeIn(120);
    });

    // 选项卡切换
    $(".mod-tab-top li").on("click", function () {
        if ($(this).not(".item-active")[0]) {
            $(".mod-tab-top li").eq($(this).index()).addClass("item-active").siblings().removeClass("item-active");
            $(".mod-tab-row li").fadeOut(150).delay(150).eq($(this).index()).fadeIn(150);
        }
    });
});


/* 特殊弹窗 样式-3 */
$(function () {
    // 点击触发模态框
    $(".js-pro-3").click(function () {
        $(".wrap-pro-3").fadeIn(120);
    });

    /* 手风琴折叠切换 */
    $(".wrap-pro-3 .group").click(function () {
        $(".wrap-pro-3 .group").eq($(this).index()).addClass("item-active").children(".mod-body").slideDown(120);
        $(".wrap-pro-3 .group").eq($(this).index()).siblings().removeClass("item-active").children(".mod-body").slideUp(120);
    })
});


/* 标准的最大尺寸弹窗 */
$(function(){
    $(".js-bigger-1").click(function(){
        $(".js-wrap-bigger-1").fadeIn(120);
    })
});


/* 页签 scrm5-TAB.html */
$(function () {
    jQuery(".js-tab-box").slide({
        trigger: "click",
        delayTime: 300
    });
});


/* 滚动条调用 .js-scroll */
$(function () {
    $(".js-card-scroll").niceScroll({
        cursorcolor: "#ccc",//#CC0071 光标颜色
        cursoropacitymax: .7, //改变不透明度非常光标处于活动状态（scrollabar“可见”状态），范围从1到0
        touchbehavior: true, //使光标拖动滚动像在台式电脑触摸设备
        cursorwidth: "5px", //像素光标的宽度
        cursorborder: "0", // 游标边框css定义
        cursorborderradius: "5px",//以像素为光标边界半径
        autohidemode: true //是否隐藏滚动条
    });

    $(".js-scroll").niceScroll({
        cursorcolor: "#ccc",//#CC0071 光标颜色
        cursoropacitymax: .7, //改变不透明度非常光标处于活动状态（scrollabar“可见”状态），范围从1到0
        touchbehavior: false, //使光标拖动滚动像在台式电脑触摸设备
        cursorwidth: "5px", //像素光标的宽度
        cursorborder: "0", // 游标边框css定义
        cursorborderradius: "5px",//以像素为光标边界半径
        autohidemode: true//是否隐藏滚动条
    });
});


/**
 * ios风格的左右按钮开关
 * lc_switch.js
 * Version: 1.0
 * Author: LCweb - Luca Montanari
 * Download: http://www.mycodes.net
 * Licensed under the MIT license
 */
(function ($) {
    if (typeof($.fn.lc_switch) != 'undefined') {
        return false;
    } // prevent dmultiple scripts inits

    $.fn.lc_switch = function (on_text, off_text) {

        // destruct
        $.fn.lcs_destroy = function () {

            $(this).each(function () {
                var $wrap = $(this).parents('.lcs_wrap');

                $wrap.children().not('input').remove();
                $(this).unwrap();
            });

            return true;
        };


        // set to ON
        $.fn.lcs_on = function () {

            $(this).each(function () {
                var $wrap = $(this).parents('.lcs_wrap');
                var $input = $wrap.find('input');

                if (typeof($.fn.prop) == 'function') {
                    $wrap.find('input').prop('checked', true);
                } else {
                    $wrap.find('input').attr('checked', true);
                }

                $wrap.find('input').trigger('lcs-on');
                $wrap.find('input').trigger('lcs-statuschange');
                $wrap.find('.lcs_switch').removeClass('lcs_off').addClass('lcs_on');

                // if radio - disable other ones
                if ($wrap.find('.lcs_switch').hasClass('lcs_radio_switch')) {
                    var f_name = $input.attr('name');
                    $wrap.parents('form').find('input[name=' + f_name + ']').not($input).lcs_off();
                }
            });

            return true;
        };


        // set to OFF
        $.fn.lcs_off = function () {

            $(this).each(function () {
                var $wrap = $(this).parents('.lcs_wrap');

                if (typeof($.fn.prop) == 'function') {
                    $wrap.find('input').prop('checked', false);
                } else {
                    $wrap.find('input').attr('checked', false);
                }

                $wrap.find('input').trigger('lcs-off');
                $wrap.find('input').trigger('lcs-statuschange');
                $wrap.find('.lcs_switch').removeClass('lcs_on').addClass('lcs_off');
            });

            return true;
        };


        // construct
        return this.each(function () {

            // check against double init
            if (!$(this).parent().hasClass('lcs_wrap')) {

                // default texts
                var ckd_on_txt = (typeof(on_text) == 'undefined') ? 'ON' : on_text;
                var ckd_off_txt = (typeof(off_text) == 'undefined') ? 'OFF' : off_text;

                // labels structure
                var on_label = (ckd_on_txt) ? '<div class="lcs_label lcs_label_on">' + ckd_on_txt + '</div>' : '';
                var off_label = (ckd_off_txt) ? '<div class="lcs_label lcs_label_off">' + ckd_off_txt + '</div>' : '';


                // default states
                var disabled = ($(this).is(':disabled')) ? true : false;
                var active = ($(this).is(':checked')) ? true : false;

                var status_classes = '';
                status_classes += (active) ? ' lcs_on' : ' lcs_off';
                if (disabled) {
                    status_classes += ' lcs_disabled';
                }


                // wrap and append
                var structure =
                    '<div class="lcs_switch ' + status_classes + '">' +
                    '<div class="lcs_cursor"></div>' +
                    on_label + off_label +
                    '</div>';

                if ($(this).is(':input') && ($(this).attr('type') == 'checkbox' || $(this).attr('type') == 'radio')) {

                    $(this).wrap('<div class="lcs_wrap"></div>');
                    $(this).parent().append(structure);

                    $(this).parent().find('.lcs_switch').addClass('lcs_' + $(this).attr('type') + '_switch');
                }
            }
        });
    };


    // handlers
    $(document).ready(function () {

        // on click
        $(document).delegate('.lcs_switch:not(.lcs_disabled)', 'click tap', function (e) {

            if ($(this).hasClass('lcs_on')) {
                if (!$(this).hasClass('lcs_radio_switch')) { // not for radio
                    $(this).lcs_off();
                }
            } else {
                $(this).lcs_on();
            }
        });


        // on checkbox status change
        $(document).delegate('.lcs_wrap input', 'change', function () {

            if ($(this).is(':checked')) {
                $(this).lcs_on();
            } else {
                $(this).lcs_off();
            }
        });

    });
})(jQuery);
/* ios风格的左右按钮开关调用 */
$(document).ready(function (e) {
    $('input.ios-btn').lc_switch();
    // triggered each time a field changes status
    $('body').delegate('.lcs_check', 'lcs-statuschange', function () {
        var status = ($(this).is(':checked')) ? 'checked' : 'unchecked';
        console.log('field changed status: ' + status);
    });

    // triggered each time a field is checked
    $('body').delegate('.lcs_check', 'lcs-on', function () {
        console.log('field is checked');
    });

    // triggered each time a is unchecked
    $('body').delegate('.lcs_check', 'lcs-off', function () {
        console.log('field is unchecked');
    });
});


/**
 * @name emoji表情组件
 * @author john@renlaifeng.cn
 * @time 2016/07/07 12:00
 * @method 点击表情关闭表情盒子并向文本域追加表情 给表情和文本框的共同父级(最近父级)追加 .js-emoji-into
 */
/*$(function () {
    // 点击表情开关
    $(".js-emoji-change").on("click", function (e) {
        $(this).parents(".emoji-wrap").find(".js-emoji-box").fadeToggle(120);
        e.stopPropagation();
    });
    // 点击其他区域关闭表情盒子
    $(document).on("click", function () {
        $(".js-emoji-box").fadeOut(120);
    });

    // 点击表情关闭表情盒子并向文本域追加表情 给表情和文本框的共同父级(最近父级)追加 .js-emoji-into
    $(".emoji-box ul li").on("click", function () {
        $(this).parents(".emoji-wrap").find(".js-emoji-box").fadeOut(120);
        var dataTitle = $(this).find("i").attr("data-title");
        var emoval = $(this).parents(".js-emoji-into").find("textarea,input").val();
        $(this).parents(".js-emoji-into").find("textarea,input").val(emoval + dataTitle);
        $(this).parents(".js-emoji-into").find("textarea,input").focus();
    });
});*/


$(function () {
    // 点击表情开关
    $(".js-emoji-change").on("click", function (e) {
        $(this).parents(".emoji-wrap").find(".js-emoji-box").fadeToggle(120);
        e.stopPropagation();
    });
    // 点击其他区域关闭表情盒子
    $(document).on("click", function () {
        $(".js-emoji-box").fadeOut(120);
    });
    

    // 点击表情关闭表情盒子并向文本域追加表情 给表情和文本框的共同父级(最近父级)追加 .js-emoji-into
    $(".emoji-box ul li").on("click", function (e) {
        var $oTxt1 = $(this).parents(".js-emoji-into").find("textarea,input");
        var oTxt1 = $oTxt1[0];
        var cursurPosition=-1;
        if(oTxt1.selectionStart){//非IE浏览器
            cursurPosition= oTxt1.selectionStart;
        }else{//IE
            var range = document.selection.createRange();
            range.moveStart("character",-oTxt1.value.length);
            cursurPosition=range.text.length;
        }
        var num =cursurPosition;
        var emoval = $(this).parents(".js-emoji-into").find("textarea,input").val();
        var text1 = emoval.substr(0,num);
        var text2 = emoval.substr(num);
        $(this).parents(".emoji-wrap").find(".js-emoji-box").fadeOut(120);
        var dataTitle = $(this).find("i").attr("data-title");
        $(this).parents(".js-emoji-into").find("textarea,input").val(text1 + dataTitle + text2);
        $(this).parents(".js-emoji-into").find("textarea,input").focus();
    });
});

/**
 * @name 微信自定义菜单管理 显示区
 * @author john@renlaifeng.cn
 * @time 2016/07/08 23:00
 */
$(function () {
    // 点击添加一级菜单
    $(document).on("click", ".js-add-btn", function () {
        $(this).parents(".content").find(".js-no-menu-tips").hide();
        $(this).parents(".content").find(".js-menu-form-area").removeClass("hide").show();

        var ClLen = $(this).parents("ul.pre-menu-list").find("li.pre-menu-item").length;
        console.log(ClLen);
        if (ClLen == 1) {
            // 追加第一个一级菜单
            $(this).parents("li.pre-menu-item").before('<li class="pre-menu-item size1of1"><a href="javascript:void(0);" class="pre-menu-link js-add-btn-toggle item-active"><span>一级菜单</span></a><div class="child-pre-menu-list-wrap"><ul class="child-pre-menu-list"><li class="child-pre-menu-item"><a href="javascript:void(0);" class="child-pre-menu-link js-add-child-btn" title="最多添加5个二级菜单"><span class="child-pre-menu-inner"><i class="icon-menu-add"></i></span></a></li></ul><i class="arrow-out"></i><i class="arrow-in"></i></div></li>');
            $(this).parents("ul.pre-menu-list").find("li.pre-menu-item").attr("class", "pre-menu-item size1of2");
            $(this).removeClass("item-active").find("span").replaceWith('<i class="icon-menu-add"></i>');
        } else if (ClLen == 2) {
            // 追加第二个一级菜单
            $(this).parents("li.pre-menu-item").before('<li class="pre-menu-item size1of1"><a href="javascript:void(0);" class="pre-menu-link js-add-btn-toggle item-active"><span>一级菜单</span></a><div class="child-pre-menu-list-wrap"><ul class="child-pre-menu-list"><li class="child-pre-menu-item"><a href="javascript:void(0);" class="child-pre-menu-link js-add-child-btn" title="最多添加5个二级菜单"><span class="child-pre-menu-inner"><i class="icon-menu-add"></i></span></a></li></ul><i class="arrow-out"></i><i class="arrow-in"></i></div></li>');
            $(this).parents("ul.pre-menu-list").find("li.pre-menu-item").attr("class", "pre-menu-item size1of3");
            $(this).parents("ul.pre-menu-list").find("li.pre-menu-item:eq(0) .child-pre-menu-list-wrap").hide();
            $(this).parents("ul.pre-menu-list").find("li.pre-menu-item:eq(1)").siblings().find(".js-add-btn-toggle").removeClass("item-active");
        } else if (ClLen == 3) {
            // 追加第三个一级菜单
            $(this).parents("li.pre-menu-item").before('<li class="pre-menu-item size1of1"><a href="javascript:void(0);" class="pre-menu-link js-add-btn-toggle item-active"><span>一级菜单</span></a><div class="child-pre-menu-list-wrap"><ul class="child-pre-menu-list"><li class="child-pre-menu-item"><a href="javascript:void(0);" class="child-pre-menu-link js-add-child-btn" title="最多添加5个二级菜单"><span class="child-pre-menu-inner"><i class="icon-menu-add"></i></span></a></li></ul><i class="arrow-out"></i><i class="arrow-in"></i></div></li>');
            $(this).parents("ul.pre-menu-list").find("li.pre-menu-item").attr("class", "pre-menu-item size1of3");
            $(this).parents("ul.pre-menu-list").find("li.pre-menu-item:eq(0) .child-pre-menu-list-wrap").hide();
            $(this).parents("ul.pre-menu-list").find("li.pre-menu-item:eq(1) .child-pre-menu-list-wrap").hide();
            $(this).parents("ul.pre-menu-list").find("li.pre-menu-item:eq(2)").siblings().find(".js-add-btn-toggle").removeClass("item-active");
            $(this).parents(".pre-menu-item").hide();
        }
    });

    // 点击添加二级子菜单
    $(document).on("click", ".js-add-child-btn", function () {
        $(this).parents(".content").find(".js-no-menu-tips").hide();
        $(this).parents(".content").find(".js-menu-form-area").removeClass("hide").show();

        $(this).parents("ul.pre-menu-list").find("li.pre-menu-item").find(".js-add-btn-toggle").removeClass("item-active");
        var C2Len = $(this).parents("ul.child-pre-menu-list").find("li.child-pre-menu-item").length;
        console.log(C2Len);
        // 二级菜单最多5个
        if (C2Len < 5) {
            // $(this).parents("ul.child-pre-menu-list").prepend('<li class="child-pre-menu-item js-menu-alive"><a href="javascript:void(0);" class="child-pre-menu-link"><span class="child-pre-menu-inner"><span>子菜单名称</span></span></a> </li>')
            $(this).parents("li.child-pre-menu-item").before('<li class="child-pre-menu-item js-menu-alive"><a href="javascript:void(0);" class="child-pre-menu-link"><span class="child-pre-menu-inner"><span>子菜单名称</span></span></a></li>');
            $(this).parents("li.child-pre-menu-item").prev().find(".child-pre-menu-link").addClass("item-active");
            $(this).parents("li.child-pre-menu-item").prev().siblings().find(".child-pre-menu-link").removeClass("item-active");
        } else if (C2Len == 5) {
            // $(this).parents("ul.child-pre-menu-list").prepend('<li class="child-pre-menu-item js-menu-alive"><a href="javascript:void(0);" class="child-pre-menu-link"><span class="child-pre-menu-inner"><span>子菜单名称</span></span></a> </li>')
            $(this).parents("li.child-pre-menu-item").before('<li class="child-pre-menu-item js-menu-alive"><a href="javascript:void(0);" class="child-pre-menu-link"><span class="child-pre-menu-inner"><span>子菜单名称</span></span></a></li>');
            $(this).parents("li.child-pre-menu-item").prev().find(".child-pre-menu-link").addClass("item-active");
            $(this).parents("li.child-pre-menu-item").prev().siblings().find(".child-pre-menu-link").removeClass("item-active");
            $(this).parents("li.child-pre-menu-item").hide();
        }
    });

    // 点击当前一级菜单时其二级菜单自动展开其他闭合
    $(document).on("click", ".js-add-btn-toggle", function () {
        $(this).next(".child-pre-menu-list-wrap").show();
        $(this).parents(".pre-menu-item").siblings().find(".child-pre-menu-list-wrap").hide();
    });

    // 一级菜单选中效果
    $(document).on("click", ".js-add-btn-toggle", function () {
        $(this).parents(".content").find(".js-no-menu-tips").hide();
        $(this).parents(".content").find(".js-menu-form-area").removeClass("hide").show();

        // $(this).parents("li.pre-menu-item").siblings().find(".js-add-btn-toggle").removeClass("item-active");
        $(this).parents("ul.pre-menu-list").find("a.item-active").removeClass("item-active");
        $(this).addClass("item-active");

        /* 如果一级菜单 有子菜单 编辑区更改对应样式 */
        if ($(this).next(".child-pre-menu-list-wrap").find(".child-pre-menu-item").hasClass("js-menu-alive")) {
            $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-inner-none").removeClass("hide");
            $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-control-wrap,.js-radio-send-msg-wrap").addClass("hide");

            $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-menu-form-hd-tit").text("菜单名称");
            $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-emoji-into-tit").text("菜单名称");
            $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js_menuContent").text("菜单内容");
            $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js_titleNolTips").text("字数不超过4个汉字或8个字母");
            $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".jsDelBt").text("删除菜单");
        } else {
            /* 如果一级菜单 没有子菜单 编辑区更改对应样式 */
            $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-inner-none").addClass("hide");
            $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-control-wrap,.js-radio-send-msg-wrap").removeClass("hide");

            $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-menu-form-hd-tit").text("菜单名称");
            $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-emoji-into-tit").text("菜单名称");
            $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js_menuContent").text("菜单内容");
            $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js_titleNolTips").text("字数不超过4个汉字或8个字母");
            $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".jsDelBt").text("删除菜单");
        }
    });
    /* 如果一级菜单 添加按钮 编辑区更改对应样式 */
    $(document).on("click", "a.js-add-btn", function () {
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-inner-none").addClass("hide");
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-control-wrap,.js-radio-send-msg-wrap").removeClass("hide");

        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-menu-form-hd-tit").text("菜单名称");
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-emoji-into-tit").text("菜单名称");
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js_menuContent").text("菜单内容");
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js_titleNolTips").text("字数不超过4个汉字或8个字母");
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".jsDelBt").text("删除菜单");
    });

    // 二级菜单选中效果
    $(document).on("click", "li.js-menu-alive", function () {
        $(this).parents(".content").find(".js-no-menu-tips").hide();
        $(this).parents(".content").find(".js-menu-form-area").removeClass("hide").show();

        // $(this).siblings().find(".child-pre-menu-link").removeClass("item-active");
        $(this).parents("ul.pre-menu-list").find("a.item-active").removeClass("item-active");
        $(this).find(".child-pre-menu-link").addClass("item-active");

        /* 如果有二级菜单，那么点击二级菜单的时候 编辑区应该是这样的 */
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-inner-none").addClass("hide");
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-control-wrap,.js-radio-send-msg-wrap").removeClass("hide");

        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-menu-form-hd-tit").text("子菜单名称");
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-emoji-into-tit").text("子菜单名称");
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js_menuContent").text("子菜单内容");
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js_titleNolTips").text("字数不超过8个汉字或16个字母");
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".jsDelBt").text("删除子菜单");
    });
    // 二级菜单 添加按钮 选中效果
    $(document).on("click", ".js-add-child-btn", function () {
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-inner-none").addClass("hide");
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-control-wrap,.js-radio-send-msg-wrap").removeClass("hide");

        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-menu-form-hd-tit").text("子菜单名称");
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js-emoji-into-tit").text("子菜单名称");
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js_menuContent").text("子菜单内容");
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".js_titleNolTips").text("字数不超过8个汉字或16个字母");
        $(this).parents(".menu-setting-area").find(".js-menu-form-area").find(".jsDelBt").text("删除子菜单");
    });

    // 菜单名称 的输入框的值 要动态赋给 当前选中的菜单按钮
    // 二级菜单点击
    $(document).on("click", ".js-menu-alive", function () {
        var L2Val = $(this).find("span.child-pre-menu-inner span").text();
        $(this).parents(".menu-setting-area").find(".js_menu_name").focus().val(L2Val);
    });

    // 二级添加菜单点击
    $(document).on("click", ".js-add-child-btn", function () {
        var L2Val = $(this).parents(".child-pre-menu-item").prev().find("span.child-pre-menu-inner span").text();
        $(this).parents(".menu-setting-area").find(".js_menu_name").focus().val(L2Val);
    });

    // 一级菜单点击
    $(document).on("click", ".pre-menu-link", function () {
        var L1Val = $(this).find("span").text();
        $(this).parents(".menu-setting-area").find(".js_menu_name").focus().val(L1Val);
    });

    // 一级添加菜单点击
    $(document).on("click", ".js-add-btn", function () {
        var L1Val = $(this).parents(".pre-menu-item").prev().find("span").text();
        $(this).parents(".menu-setting-area").find(".js_menu_name").focus().val(L1Val);
    });

    // 文本框修改按钮文本
    $(document).on("change", ".js_menu_name", function () {
        var inputVal = $(this).val();
        if (inputVal != "") {
            $(this).parents(".menu-setting-area").find(".js-add-btn-toggle.item-active span").text(inputVal);
            $(this).parents(".menu-setting-area").find(".child-pre-menu-link.item-active .child-pre-menu-inner span").text(inputVal);
        } else if (inputVal == "") {
            $(this).parents(".menu-setting-area").find(".js-add-btn-toggle.item-active span").text("一级菜单");
            $(this).parents(".menu-setting-area").find(".child-pre-menu-link.item-active .child-pre-menu-inner span").text("二级菜单");
        }
    });
});


/**
 * @name 微信自定义菜单管理 编辑区
 * @author john@renlaifeng.cn
 * @time 2016/07/12 12:00
 */
$(function () {
    /* 切换 发送信息 还是 跳转网页 */
    $(document).on("click", ".js-radio-send-msg", function () {
        $(this).parents(".menu-form-body").find(".js-radio-url-wrap").hide();
        $(this).parents(".menu-form-body").find(".js-radio-send-msg-wrap").removeClass("hide").fadeIn(120);
    });

    $(document).on("click", ".js-radio-url", function () {
        $(this).parents(".menu-form-body").find(".js-radio-send-msg-wrap").hide();
        $(this).parents(".menu-form-body").find(".js-radio-url-wrap").removeClass("hide").fadeIn(120);
    });


    /* 添加图文 */
    $(document).on("click", ".js-del-cur-pic-msg-con", function () {
        $(this).parents(".js-Wechat-menu-pic-msg-inner").find(".js-Wechat-menu-no-con").removeClass("hide");
        $(this).parents(".js-Wechat-menu-pic-msg-box").remove();
    });

    /* 添加图片 */
    $(document).on("click", ".js-del-cur-img", function () {
        $(this).parents(".js-Wechat-menu-pic-inner").find(".js-Wechat-menu-no-con").removeClass("hide");
        $(this).parents(".Wechat-menu-pic-box").remove();
    });

    /* 添加音频 */
    $(document).on("click", ".js-icon-menu-audio", function () {
        $(this).toggleClass("on");
    });
    $(document).on("click", ".js-del-cur-audio", function () {
        $(this).parents(".js-Wechat-menu-audio-inner").find(".js-Wechat-menu-no-con").removeClass("hide");
        $(this).parents(".js-Wechat-menu-audio-box").remove();
    });

    /* 添加视频 */
    $(document).on("click", ".js-del-cur-video", function () {
        $(this).parents(".js-Wechat-menu-video-inner").find(".js-Wechat-menu-no-con").removeClass("hide");
        $(this).parents(".js-Wechat-menu-video-box").remove();
    });
});


/**
 * @name 删除按钮删除菜单交互 编辑区
 * @author john@renlaifeng.cn
 * @time 2016/07/13 12:00
 */
$(function () {
    $(document).on("click", ".jsDelBt", function () {
        // 点击删除菜单按钮即隐藏编辑区 显示提示区
        $(this).parents(".js-menu-form-area").hide();
        $(this).parents(".menu-setting-area").find(".js-no-menu-tips").show();


        // 删除一级菜单
        var L1Len = $(this).parents(".menu-setting-area").find(".pre-menu-item").length;
        console.log("CurrL1 " + L1Len);

        if (L1Len == 4 && $(this).parents(".menu-setting-area").find(".js-add-btn-toggle").hasClass("item-active")) {
            // 当前有三个一级菜单的时候
            $(this).parents(".menu-setting-area").find(".js-add-btn-toggle.item-active").parents(".pre-menu-item").remove();
            $(this).parents(".menu-setting-area").find(".pre-menu-item").attr("class", "pre-menu-item size1of3");
            $(this).parents(".menu-setting-area").find(".pre-menu-item:last-child").show();
        } else if (L1Len == 3 && $(this).parents(".menu-setting-area").find(".js-add-btn-toggle").hasClass("item-active")) {
            // 当前有三个一级菜单的时候
            $(this).parents(".menu-setting-area").find(".js-add-btn-toggle.item-active").parents(".pre-menu-item").remove();
            $(this).parents(".menu-setting-area").find(".pre-menu-item").attr("class", "pre-menu-item size1of2");

        } else if (L1Len == 2 && $(this).parents(".menu-setting-area").find(".js-add-btn-toggle").hasClass("item-active")) {
            // 当前有三个一级菜单的时候
            $(this).parents(".menu-setting-area").find(".js-add-btn-toggle.item-active").parents(".pre-menu-item").remove();
            $(this).parents(".menu-setting-area").find(".pre-menu-item").attr("class", "pre-menu-item size1of1");
            $(this).parents(".menu-setting-area").find(".pre-menu-item:last-child a").replaceWith('<a href="javascript:void(0);" class="pre-menu-link js-add-btn item-active" title="最多添加3个一级菜单"><span><i class="icon-menu-add no-menu"></i> 添加菜单</span></a>');
        }

        // 删除二级菜单
        $(this).parents(".menu-setting-area").find(".child-pre-menu-link.item-active").parents(".child-pre-menu-list").find("li:last-child").show();
        if ($(this).parents(".menu-setting-area").find(".child-pre-menu-link.item-active").hasClass("item-active")) {
            $(this).parents(".menu-setting-area").find(".child-pre-menu-link.item-active").parents(".child-pre-menu-item").remove();
        }
    });
});


/* 微信文件上传选择 模态框 */
$(function () {
    // 点击 关闭图标 确定按钮 取消按钮 和 灰色包裹层 关闭模态框
    $(".js-icon-close,.js-ok,.js-cancel").click(function () {
        $(".popup-wrap").fadeOut(120);
    });
    // 点击Esc 关闭模态框
    $(document).ready(function () {
        $(this).keydown(function (e) {
            var escCode = e.keyCode || e.which;
            if (escCode == 27) {
                $(".popup-wrap").fadeOut(120);
            }
        })
    });


    // 上传文件 选中、取消选中 效果
    $(document).on("click", ".js-add-file", function () {
        $(this).parents(".bd").find(".js-add-file-cover-wrap").fadeOut(120);
        $(this).next(".js-add-file-cover-wrap").fadeIn(120);
    });
    $(document).on("click", ".js-add-file-cover-wrap", function () {
        $(this).fadeOut(120);
    });


    // 打开上传图文模态框
    $(document).on("click", ".js-WeChat-file-add-pic-msg", function () {
        $(".js-WeChat-file-add-pic-msg-wrap").fadeIn(120);
    });

    // 打开上传图片模态框
    $(document).on("click", ".js-WeChat-file-add-pic", function () {
        $(".js-WeChat-file-add-pic-wrap").fadeIn(120);
    });

    // 打开上传视频模态框
    $(document).on("click", ".js-WeChat-file-add-video", function () {
        $(".js-WeChat-file-add-video-wrap").fadeIn(120);
    });

    // 打开上传视频模态框
    $(document).on("click", ".js-WeChat-file-add-audio", function () {
        $(".js-WeChat-file-add-audio-wrap").fadeIn(120);
    });

    // 打开植入链接模态框
    $(document).on("click", ".js-WeChat-into-url-btn", function () {
        $(".js-WeChat-into-url-wrap").fadeIn(120);
    });
});


/**
 * @name 瀑布流调用
 * @author john@renlaifeng.cn
 * @time 2016/07/015 14:00
 * @method 给瀑布流分层追加 .js-masonry  给内容块追加 .js-masonry-box
 */
$(function () {
    $(document).ready(function () {
        var $container = $(".js-masonry");
        $container.imagesLoaded(function () {
            $container.masonry({
                itemSelector: ".js-masonry-box",  // 瀑布流里的每个内容块容器上共同的类
                gutter: 20,  // 内容块之间的距离
                isAnimated: true  // 当改变窗口宽度的时候的动画效果
            });
        });
    })
});


/* 素材库交互 john@renlaifeng.cn 2016-07-21 12:06 */
$(function () {
    // 顶部的全选移动分组
    $(".js-hd-move-item").click(function () {
        $(this).parent().children(".js-hd-move-item-popup").fadeToggle(120);
        $(this).parent().children(".js-hd-remove-item-popup").fadeOut(120);
    });
    $(".js-hd-move-item-popup .js-sure").click(function () {
        $(this).parents(".js-hd-move-item-popup").fadeOut(120);
    });
    $(".js-hd-move-item-popup .js-cancel").click(function () {
        $(this).parents(".js-hd-move-item-popup").fadeOut(120);
    });
    // 顶部的全选删除
    $(".js-hd-remove-item").click(function () {
        $(this).parent().children(".js-hd-remove-item-popup").fadeToggle(120);
        $(this).parent().children(".js-hd-move-item-popup").fadeOut(120);
    });
    $(".js-hd-remove-item-popup .js-sure").click(function () {
        $(this).parents(".js-hd-remove-item-popup").fadeOut(120);
    });
    $(".js-hd-remove-item-popup .js-cancel").click(function () {
        $(this).parents(".js-hd-remove-item-popup").fadeOut(120);
    });


    // 新建分组命名
    $(document).on("click", ".js-add-new-group", function () {
        $(this).next(".js-add-new-group-popup").fadeIn(120).find(".js-add-new-group-popup-input").focus();
    });
    // 取消按钮
    $(document).on("click", ".add-new-group .js-cancel", function () {
        $(this).parents(".js-add-new-group-popup").fadeOut(120);
    });
    // 确认按钮
    $(document).on("click", ".add-new-group .js-sure", function () {
        var addNewGruopName = $(this).parents(".js-add-new-group-popup").find(".js-add-new-group-popup-input").val();
        if (addNewGruopName == "") {
            addNewGruopName = "新建分组";
        }
        $(this).parents(".add-new-group").before('<li><h5><span class="js-new-group-name">' + addNewGruopName + '</span> <span class="text-gray">(0)</span></h5></li>');
        $(this).parents(".add-pic-tab-wrap").find(".bd").append('<div class="inner" style="display: none;"><div class="inner-top clearfix"><div class="inner-top-group-name fl"><h4 class="inline"> 分组一 </h4> <h4 class="inline"><a class="tips js-rename-group-btn"> 重命名 </a></h4> <h4 class="inline"><a class="tips js-delete-group-btn"> 删除分组 </a></h4>  <div class="rename-group-popup js-rename-group"> <span class="triangle-up-b"></span> <span class="triangle-up-a"></span> <h3>编辑名称</h3> <div class="input-group"> <input class="w200" type="text"> </div> <div class="move-btn-group"> <button class="btn-middle btn-purple js-sure"> &nbsp;&nbsp;确认&nbsp;&nbsp;</button>&nbsp;<button class="btn-middle btn-white js-cancel"> &nbsp;&nbsp;取消&nbsp;&nbsp;</button></div></div> <div class="delete-group-popup js-delete-group-popup"> <span class="triangle-up-b"></span><span class="triangle-up-a"></span><h3>仅删除分组，不删除图片，确定删除此分组吗？</h3><div class="move-btn-group"><button class="btn-middle btn-purple js-sure">&nbsp;&nbsp;确认&nbsp;&nbsp;</button>&nbsp;<button class="btn-middle btn-white js-cancel">&nbsp;&nbsp;取消&nbsp;&nbsp;</button></div></div> </div><a href="javascript:void(0);" class="tips fr"><i class="icon-upload"></i>上传图片</a> </div><div class="inner-body"><div class="inner-body-hd"><label class="checkbox inline js-check-all"><input type="checkbox">全选</label> <a class="btn-middle btn-default">移动分组</a> <a class="btn-middle btn-default">删除</a></div><div class="add-pic-tab"></div></div></div>');
        $(this).parents(".js-add-new-group-popup").fadeOut(120).find(".js-add-new-group-popup-input").val("");
        // document.location.reload();
    });
    // 点击其他地方关闭添加分组弹窗
    $(document).on("click", ".js-add-new-group,.js-add-new-group-popup", function (e) {
        e.stopPropagation();
    });
    $(document).click(function () {
        $(".js-add-new-group-popup").fadeOut(120);
    });


    // 重命名分组
    $(document).on("click", ".js-rename-group-btn", function () {
        $(this).parents(".inner-top-group-name").find(".js-rename-group").fadeToggle(120);
    });
    // 重命名分组 取消 确定
    $(document).on("click", ".js-rename-group .js-cancel,.js-rename-group .js-sure", function () {
        $(this).parents(".js-rename-group").fadeOut(120);
    });


    // 删除分组
    $(document).on("click", ".js-delete-group-btn", function () {
        $(this).parents(".inner-top-group-name").find(".js-delete-group-popup").fadeToggle(120);
    });


    // 重命名图片
    $(document).on("click", ".js-rename-pic", function () {
        $(this).parents(".js-inner-box").find(".js-rename-group-popup").fadeToggle(120);
    });
    // 重命名图片 取消 确定
    $(document).on("click", ".js-rename-group-popup .js-cancel,.js-rename-group-popup .js-sure", function () {
        $(this).parents(".js-rename-group-popup").fadeOut(120);
    });


    // 移动分组按钮
    $(document).on("click", ".js-move-group-to", function () {
        $(this).parents(".js-inner-box").find(".js-move-group-popup").fadeToggle(120);
    });
    // 移动分组按钮 取消 确定
    $(document).on("click", ".js-move-group-popup .js-cancel,.js-move-group-popup .js-sure", function () {
        $(this).parents(".js-move-group-popup").fadeOut(120);
    });


    // 删除分组按钮
    $(document).on("click", ".js-del", function () {
        $(this).parents(".js-inner-box").find(".js-delete-group-popup").fadeToggle(120);
    });
    // 删除分组按钮 取消 确定
    $(document).on("click", ".js-delete-group-popup .js-cancel,.js-delete-group-popup .js-sure", function () {
        $(this).parents(".js-delete-group-popup").fadeOut(120);
    });
    // 删除当前素材按钮
    $(document).on("click", ".js-delete-group-popup .js-sure", function () {
        $(this).parents(".js-inner-box").remove();
        // document.location.reload();
    });

    // 删除当前素材按钮
    /*$(document).on("click", ".js-del", function () {
     $(this).parents(".js-inner-box").remove();
     // document.location.reload();
     });*/

    // 图片全选or全不选
    $(".js-check-all input").click(function () {
        if (this.checked) {
            $(this).parents(".inner-body").find(".js-inner-box input[type='checkbox']").each(function () {
                this.checked = true;
            });
        } else {
            $(this).parents(".inner-body").find(".js-inner-box input[type='checkbox']").each(function () {
                this.checked = false;
            });
        }
    });
});


/* 新建图文消息编辑菜单 john@renlaifeng.cn 2016-07-25 15:30 */
$(function () {
    // 点击选中当前取消选中其他
    $(".js-new-pic-msg .con-inner_L1-cover").removeClass("on");
    $(".js-new-pic-msg .con-inner-L2-cover").removeClass("on");
    // 一级图文点击
    $(document).on("click", ".js-new-pic-msg .con-inner-L1-cover", function () {
        $(this).addClass("on");
        $(this).parents(".js-new-pic-msg").find(".con-inner-L2-cover").removeClass("on");
    });
    // 二级图文点击
    $(document).on("click", ".js-new-pic-msg .con-inner-L2-cover", function () {
        $(this).parents(".js-new-pic-msg").find(".con-inner-L1-cover").removeClass("on");
        $(this).addClass("on").parents(".con-inner-L2").siblings().find(".con-inner-L2-cover").removeClass("on");
    });

    // 一级图文的编辑
    $(document).on("click", ".js-new-pic-msg .con-inner-L1-cover", function () {
        var valInput = $(this).parents(".con-inner-L1").find(".con-inner-tit").text();
        $(this).parents(".js-new-pic-msg").find(".js_menu_name").val(valInput).select().focus();
    });
    $(document).on("input", ".js-new-pic-msg .js_menu_name", function () {
        var valInput = $(this).val();
        // console.log(valInput);
        $(this).parents(".js-new-pic-msg").find(".con-inner-L1-cover.on").parents(".con-inner-L1").find(".con-inner-tit").text(valInput);
    });

    // 二级图文的编辑
    $(document).on("click", ".js-new-pic-msg .con-inner-L2-cover", function () {
        var valInput = $(this).parents(".con-inner-L2").find(".con-inner-tit").text();
        $(this).parents(".js-new-pic-msg").find(".js_menu_name").val(valInput).select().focus();
    });
    $(document).on("input", ".js-new-pic-msg .js_menu_name", function () {
        var valInput = $(this).val();
        // console.log(valInput);
        $(this).parents(".js-new-pic-msg").find(".con-inner-L2-cover.on").parents(".con-inner-L2").find(".con-inner-tit").text(valInput);
    });


    // 添加二级图文
    $(document).on("click", ".js-new-pic-msg .con-inner-L2-add", function () {
        var lenInnerL2 = $(this).parents(".con-box").find(".con-inner-L2").length;
        console.log(lenInnerL2);
        if (lenInnerL2 < 6) {
            $(this).before('<div class="con-inner-L2 clearfix"><h4 class="con-inner-tit fl">标题</h4><i class="con-inner-img fr"></i><div class="con-inner-L2-cover"><div class="edit js-edit"><span><i class="icon-edit"></i> 编辑</span></div><div class="del js-del"><span><i class="icon-del"></i> 删除</span></div></div></div>')
            $(this).show();
        } else if (lenInnerL2 = 6) {
            $(this).before('<div class="con-inner-L2 clearfix"><h4 class="con-inner-tit fl">标题</h4><i class="con-inner-img fr"></i><div class="con-inner-L2-cover"><div class="edit js-edit"><span><i class="icon-edit"></i> 编辑</span></div><div class="del js-del"><span><i class="icon-del"></i> 删除</span></div></div></div>')
            $(this).hide();
        }
    });


    // 删除二级图文
    $(document).on("click", ".js-new-pic-msg .js-del", function () {
        $(this).parents(".js-new-pic-msg").find(".js_menu_name").val("");
        $(this).parents(".js-new-pic-msg").find(".con-inner-L2-add").show();
        $(this).parents(".con-inner-L2").remove();
    });
});


/* 微信自动回复设置 john@renlaifeng.cn 2016-07-26 12:00 */
$(function () {
    // 模拟下拉收起
    $(document).on("click", ".js-item-selected", function (e) {
        $(this).next("ul").slideToggle(120);
        e.stopPropagation();
    });

    // 点击赋值

    $(document).on("click", ".js-selector li", function (e) {
        $(this).parents("ul").slideUp(120);
        var valCurrItem = $(this).text();
        // console.log(valCurrItem);
        $(this).parents(".js-selector").find(".js-item-selected span").text(valCurrItem);
        $(this).parents(".js-input-box").find("input").focus();
        e.stopPropagation();
    });
    $(document).click(function () {
        $(".js-selector").find("ul").slideUp(120);
    })
});


/* 微页面加密 john@renlaifeng.cn 2016-07-29 16:40 */
// 全选列
$(".js-check-all").click(function () {
    alert("123");
    if (this.checked) {
        $(this).parents("table").find("input[type='checkbox']").each(function () {
            this.checked = true;
        });
    } else {
        $(this).parents("table").find("input[type='checkbox']").each(function () {
            this.checked = false;
        });
    }
});


/* 新建微页面 弹窗部分 john@renlaifeng.cn 2016-08-03 21:00 */
$(function () {
    // 是否显示购物车图标
    $(".js-goods-toggle-on").click(function () {
        $(".js-goods-toggle").show();
    });
    $(".js-goods-toggle-off").click(function () {
        $(".js-goods-toggle").hide();
    });

    // 是否开启通底
    $(".footer-toggle-on").click(function () {
        $(".js-footer-nav-style").show();
    });
    $(".footer-toggle-off").click(function () {
        $(".js-footer-nav-style").hide();
    });

    // 是否开启加密
    $(document).on("click", ".js-has-password-no", function () {
        $(".js-has-password").attr('disabled', 'disabled');
    });
    $(document).on("click", ".js-has-password-yes", function () {
        $(".js-has-password").removeAttr('disabled');
    });

    // 搜索栏设置 开启关闭
    $('.js-search-toggle-on').click(function () {
        $(".js-search-inner").show();
    });
    $('.js-search-toggle-off').click(function () {
        $(".js-search-inner").hide();
    });

    // 添加图片组件 切换选中组件
    $(".js-display-style-wrap i.display-style").click(function () {
        $(this).addClass("on").siblings().removeClass("on");
    });

    // 添加图片组件 切换选中图片
    $(".js-uploading-wrap i.uploading").click(function () {
        $(this).addClass("on").siblings().removeClass("on");
    });


    // 选择陈列样式对应的图片数量
    $(".js-display-style-wrap").find(".display-style").click(function () {
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-1").addClass("on").siblings().removeClass("on");
    });
    $(".js-display-style-wrap").find(".display-style.type-1").click(function () {
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-1").show();
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-2").hide();
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-3").hide();
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-4").hide();
    });
    $(".js-display-style-wrap").find(".display-style.type-2").click(function () {
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-1").show();
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-2").show();
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-3").hide();
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-4").hide();
    });
    $(".js-display-style-wrap").find(".display-style.type-3").click(function () {
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-1").show();
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-2").show();
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-3").show();
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-4").hide();
    });
    $(".js-display-style-wrap").find(".display-style.type-4").click(function () {
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-1").show();
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-2").show();
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-3").show();
        $(this).parents(".inner-wrap").find(".js-uploading-wrap i.type-4").show();
    });

    // 点击添加图片
    $(".js-edit-group-wrap i.js-be-remove").click(function () {
        $(this).parents("i.uploading").remove();
    });


    // 幻灯片图片上传的选中
    $(document).delegate(".js-slide-img-group i.uploading", "click", function () {
        $(this).addClass("on").siblings().removeClass("on");
    });
    // 点击上传幻灯片图片
    $(document).delegate(".js-slide-img-group i.uploading.WeiPage-img-add", "click", function () {
        var imgLen = $(this).parents(".js-slide-img-group").find("i.uploading").length;
        if (imgLen < 5) {
            $(this).before('<i class="uploading"> <ul class="edit-group-wrap clearfix js-edit-group-wrap"> <li><i class="icon-edit to-left js-to-left"></i> </li> <li><i class="icon-edit to-right js-to-right"></i> </li> <li><i class="icon-edit be-remove js-be-remove"></i> </li> </ul> </i> ');
            $(this).prev().addClass("on").siblings().removeClass("on");
        } else if (imgLen == 5) {
            $(this).before('<i class="uploading"> <ul class="edit-group-wrap clearfix js-edit-group-wrap"> <li><i class="icon-edit to-left js-to-left"></i> </li> <li><i class="icon-edit to-right js-to-right"></i> </li> <li><i class="icon-edit be-remove js-be-remove"></i> </li> </ul> </i> ');
            $(this).prev().addClass("on").siblings().removeClass("on");
            $(this).hide();
        }
    });
    // 幻灯片删除图片
    $(document).on("click", ".js-be-remove", function () {
        $(this).parents(".uploading").remove();
        $(".js-slide-img-group i.uploading.WeiPage-img-add").show();
    });


    // 点击选择商品排列方式
    $(".js-goods-style-wrap .goods-style").click(function () {
        $(this).addClass("on").siblings().removeClass("on");
    });
    // 点击左边的商品组件商品名称会添加到右边去
    $(document).delegate(".js-goods-name-choose > li", "click", function () {
        var leftVal = $(this).text();
        console.log(leftVal);
        $(this).parents(".js-goods-choose-wrap").find(".goods-choose-right .goods-name-choose").append('<li> <span>' + leftVal + ' 第五集</span> <ul class="edit-group-wrap-2 clearfix"> <li><i class="edit-icon-group to-left js-to-left"></i></li> <li><i class="edit-icon-group to-right js-to-right"></i></li> <li><i class="edit-icon-group be-romove js-be-romove"></i></li> </ul> </li>');
        $(this).remove();
    });
    // 点击右边的商品名称的删除图标会还给左边的列表区
    $(document).delegate(".goods-choose-right i.js-be-romove", "click", function () {
        var rightVal = $(this).parent().parent().prev().text();
        $(this).parents(".js-goods-choose-wrap").find(".goods-choose-left .js-goods-name-choose").append('<li>' + rightVal + '</li>');
        $(this).parent().parent().parent().remove();
    });
});


/**
 * @name 前/后/上/下 移动一个单位
 * @method 要点击的前后上下按钮绑定 toUp() toDown() 方法，给移动谁，给谁追加 .js-to-move-cover
 * @time 2016-08-10 10:30
 * @author john@renlaifeng.cn
 */
$(".js-to-move-cover .js-up").click(toUp());
$(".js-to-move-cover .js-down").click(toDown());
function toUp() {
    $(document).delegate(".js-up", "click", function () {
        var onThis = $(this).parents(".js-to-move-cover");
        var toUp = onThis.prev();
        console.log("onThis : " + onThis);
        console.log("toUp : " + toUp);
        $(onThis).after(toUp);
    });
}
function toDown() {
    $(document).delegate(".js-down", "click", function () {
        var onThis = $(this).parents(".js-to-move-cover");
        var toDown = onThis.next();
        console.log("onThis : " + onThis);
        console.log("toDown : " + toDown);
        $(toDown).after(onThis);
    });
}


// 表单验证调用
$(function () {
    $("form").validationEngine({
        promptPosition: 'centerRight', /* 错误信息的位置 */
        autoPositionUpdate: 'true', /* 是否自动调整提示层的位置 */
        autoHidePrompt: 'true', /* 是否自动隐藏提示信息 */
        fadeDuration: '0.3', /* 隐藏提示信息淡出的时间 */
        maxErrorsPerField: 1, /* 单个元素显示错误提示的最大数量，值设为数值。默认 false 表示不限制 */
        showOneMessage: true, /* 是否只显示一个提示信息 */
        addPromptClass: 'formError-noArrow formError-text', /* 错误信息的样式 */

        custom_error_messages: {
            '#pwd': {
                'minSize': {
                    'message': '* 密码不得少于 6 位，建议使用英文与数字组合'
                }
            }
        }
    });
});


/* asa 的全局提示弹窗 */
$(document).ready(function () {
    $("input[type='reset']").click(function () {
        $("input[type='text']").attr('value', '');
        $("select option").attr('selected', false);
    });
});
function alertTan(text, time) {
    if (time == null) {
        time = 1000;
    }
    $("#infotext-asa").html(text);
    $(".alert-tan-asa").fadeIn();
    setTimeout("$('.alert-tan-asa').fadeOut();", time);
}


/* 入驻流程 john@renlaifeng.cn 2016-08-19 10:30 */
/*$(function () {
    var toppos = 0;
    $(window).scroll(function () {
        var sidebarTop = $("#js-sidebar-row").offset().top; // 获取div距离顶部的距离
        // console.log(sidebarTop);
        var winTop = $(window).scrollTop(); // 滚动条的高度
        console.log(winTop);
        var resultTop = sidebarTop - winTop; // 元素高度减去滚动条高度等于当前距离顶部高度
        // console.log(resultTop);
        if (winTop >= 45) {
            $("#js-sidebar-row").css("top", 15 + winTop - 45);
        } else {
            $("#js-sidebar-row").css("top", 15);
        }
        // 判断鼠标、滚动条滚动方向 这个我写着玩儿的，没啥用
        var scrollTop = $(window).scrollTop();
        if (scrollTop > toppos) {
            // console.log("向下滚动啦！");
        } else {
            // console.log("向上滚动啦！");
        }
        toppos = scrollTop;
    });
});*/
$(function () {
    // 左边栏菜单  点击选中效果
    $(".enter-platform-wrap .js-sidebar-list li").click(function () {
        $(this).addClass("on").siblings().removeClass("on");
    });
    
    // 添加子账号
    $(document).on("click", ".js-add-new-account", function () {
        $(this).parent(".tit-2").next(".new-account").append('<li class="info-group-cover clearfix"> <div class="child-sum-3 fl"> <div class="group pb-20 text-info"> <h6 class="inline w100">账号类型：</h6><h6 class="inline w100">超级管理员</h6> </div> <div class="inline pb-20"> <h6 class="inline w100">姓名：</h6><input type="text" class="w100 inline"> </div> <div class="inline pb-20"> <h6 class="inline w100">工作邮箱：</h6><input type="text" class="w100 inline"> </div> <div class="group pb-20 text-info"> <h6 class="inline w100">SCRM5 PC权限：</h6><h6 class="inline w100"><a href="javascript:void(0);" class="tips">权限配置</a></h6> </div> <div class="inline pb-20"> <h6 class="inline w100">拉卡拉POSkey：</h6><input type="text" class="w100 inline"> </div> </div> <div class="child-sum-3 fl"> <div class="inline pb-20"> <h6 class="inline w100">登录名：</h6><input type="text" class="w100 inline"> </div> <div class="inline pb-20"> <h6 class="inline w100">手机：</h6><input type="text" class="w100 inline"> </div> <div class="inline pb-20"> <h6 class="inline w100">门店权限：</h6><select class="inline w100"> <option value="">请选择门店权限</option> <option value="">门店权限1</option> <option value="">门店权限2</option> </select> </div> <div class="group pb-20 text-info"> <h6 class="inline w100">风助手权限：</h6><h6 class="inline w100"><a href="javascript:void(0);" class="tips">权限配置</a></h6> </div> <div class="group pb-20 text-info"> <h6 class="inline w100">拉卡拉POS权限：</h6><h6 class="inline w100"><a href="javascript:void(0);" class="tips">权限配置</a></h6> </div> </div> <div class="child-sum-3 fl"> <div class="inline pb-20"> <h6 class="inline w100">密码：</h6><input type="text" class="w100 inline"> </div> <div class="inline pb-20"> <h6 class="inline w100">工作座机：</h6><input type="text" class="w100 inline"> </div> </div> <div class="del-btn-group"><a class="btn-small btn-white js-del-account">删除</a></div> </li>');
    });
    // 删除子账号
    $(document).delegate(".js-del-account", "click", function () {
        $(this).parents(".info-group-cover").remove();
    });

    // 添加目标
    $(document).delegate(".target-fans .js-icon-EnterPlatform-add", "click", function () {
        $(this).parent(".inner-item").before('<li class="inner-item pb-20"><input class="laydate-input" onclick="laydate({istime: true, format: '+"'YYYY-MM-DD'"+'})" placeholder="YYYY-MM-DD">&nbsp;-&nbsp;<input class="laydate-input" onclick="laydate({istime: true, format: '+"'YYYY-MM-DD'"+'})" placeholder="YYYY-MM-DD">&nbsp;<h6 class="inline">&nbsp;增长&nbsp;&nbsp;</h6><input type="text" class="inline w70"><h6 class="inline">&nbsp;位粉丝&nbsp; </h6><i class="icon-EnterPlatform-del js-icon-EnterPlatform-del"></i></li>');
    });
    $(document).delegate(".target-user .js-icon-EnterPlatform-add", "click", function () {
        $(this).parent(".inner-item").before('<li class="inner-item pb-20"><input class="laydate-input" onclick="laydate({istime: true, format: '+"'YYYY-MM-DD'"+'})" placeholder="YYYY-MM-DD">&nbsp;-&nbsp;<input class="laydate-input" onclick="laydate({istime: true, format: '+"'YYYY-MM-DD'"+'})" placeholder="YYYY-MM-DD">&nbsp;<h6 class="inline">&nbsp;增长&nbsp;&nbsp;</h6><input type="text" class="inline w70"><h6 class="inline">&nbsp;位注册会员&nbsp; </h6><i class="icon-EnterPlatform-del js-icon-EnterPlatform-del"></i></li>');
    });
    $(document).delegate(".target-sell .js-icon-EnterPlatform-add", "click", function () {
        $(this).parent(".inner-item").before('<li class="inner-item pb-20"><input class="laydate-input" onclick="laydate({istime: true, format: '+"'YYYY-MM-DD'"+'})" placeholder="YYYY-MM-DD">&nbsp;-&nbsp;<input class="laydate-input" onclick="laydate({istime: true, format: '+"'YYYY-MM-DD'"+'})" placeholder="YYYY-MM-DD">&nbsp;<h6 class="inline">&nbsp;实现&nbsp;&nbsp;</h6><input type="text" class="inline w70"><h6 class="inline">&nbsp;万元&nbsp; </h6><i class="icon-EnterPlatform-del js-icon-EnterPlatform-del"></i></li>');
    });

    $(document).on("click",".js-icon-EnterPlatform-add",function(){
        var currVal_1 = $(this).parent("li").children("input:eq(0)").val();
        var currVal_2 = $(this).parent("li").children("input:eq(1)").val();
        var currVal_3 = $(this).parent("li").children("input:eq(2)").val();
        console.log(currVal_1);
        console.log(currVal_2);
        console.log(currVal_3);
        $(this).parent("li").prev("li").children("input:eq(0)").val(currVal_1);
        $(this).parent("li").children("input:eq(0)").val("");
        $(this).parent("li").prev("li").children("input:eq(1)").val(currVal_2);
        $(this).parent("li").children("input:eq(1)").val("");
        $(this).parent("li").prev("li").children("input:eq(2)").val(currVal_3);
        $(this).parent("li").children("input:eq(2)").val("");
    });
    // 弹框里面的拆解目标
    $(document).delegate(".target-store .js-icon-EnterPlatform-add", "click", function () {
        var _html =  '<li class="inner-item pb-15"><select class="inline w40"><option>00</option><option>01</option><option>02</option><option>03</option><option>04</option><option>05</option><option>06</option><option>07</option><option>08</option><option>09</option><option>10</option><option>11</option><option>12</option><option>13</option><option>14</option><option>15</option><option>16</option><option>17</option><option>18</option><option>19</option><option>20</option><option>21</option><option>22</option><option>23</option></select> : '
            _html += '<select class="inline w40"><option>00</option><option>01</option><option>02</option><option>03</option><option>04</option><option>05</option><option>06</option><option>07</option><option>08</option><option>09</option><option>10</option><option>11</option><option>12</option><option>13</option><option>14</option><option>15</option><option>16</option><option>17</option><option>18</option><option>19</option><option>20</option><option>21</option><option>22</option><option>23</option><option>24</option><option>25</option><option>26</option><option>27</option><option>28</option><option>29</option><option>30</option><option>31</option><option>32</option><option>33</option><option>34</option><option>35</option><option>36</option><option>37</option><option>38</option><option>39</option><option>40</option><option>41</option><option>42</option><option>43</option><option>44</option><option>45</option><option>46</option><option>47</option><option>48</option><option>49</option><option>50</option><option>51</option><option>52</option><option>53</option><option>54</option><option>55</option><option>56</option><option>57</option><option>58</option><option>59</option></select> - '                   
            _html += '<select class="inline w40"><option>00</option><option>01</option><option>02</option><option>03</option><option>04</option><option>05</option><option>06</option><option>07</option><option>08</option><option>09</option><option>10</option><option>11</option><option>12</option><option>13</option><option>14</option><option>15</option><option>16</option><option>17</option><option>18</option><option>19</option><option>20</option><option>21</option><option>22</option><option>23</option></select> : '
            _html += '<select class="inline w40"><option>00</option><option>01</option><option>02</option><option>03</option><option>04</option><option>05</option><option>06</option><option>07</option><option>08</option><option>09</option><option>10</option><option>11</option><option>12</option><option>13</option><option>14</option><option>15</option><option>16</option><option>17</option><option>18</option><option>19</option><option>20</option><option>21</option><option>22</option><option>23</option><option>24</option><option>25</option><option>26</option><option>27</option><option>28</option><option>29</option><option>30</option><option>31</option><option>32</option><option>33</option><option>34</option><option>35</option><option>36</option><option>37</option><option>38</option><option>39</option><option>40</option><option>41</option><option>42</option><option>43</option><option>44</option><option>45</option><option>46</option><option>47</option><option>48</option><option>49</option><option>50</option><option>51</option><option>52</option><option>53</option><option>54</option><option>55</option><option>56</option><option>57</option><option>58</option><option>59</option></select> '                    
            _html += '<i class="icon-EnterPlatform-del js-icon-EnterPlatform-del"></i></li>'                    
        $(this).parent(".inner-item").before(_html);
        var currVal_1 = $(this).parent("li").children("select:eq(0)").val();
        var currVal_2 = $(this).parent("li").children("select:eq(1)").val();
        var currVal_3 = $(this).parent("li").children("select:eq(2)").val();
        var currVal_4 = $(this).parent("li").children("select:eq(3)").val();
        console.log(currVal_1);
        console.log(currVal_2);
        console.log(currVal_3);
        console.log(currVal_4);
        $(this).parent("li").prev("li").children("select:eq(0)").val(currVal_1);
        $(this).parent("li").children("select:eq(0)").val("0");
        $(this).parent("li").prev("li").children("select:eq(1)").val(currVal_2);
        $(this).parent("li").children("select:eq(1)").val("0");
        $(this).parent("li").prev("li").children("select:eq(2)").val(currVal_3);
        $(this).parent("li").children("select:eq(2)").val("0");
        $(this).parent("li").prev("li").children("select:eq(3)").val(currVal_4);
        $(this).parent("li").children("select:eq(3)").val("0");
    });
    // 删除目标
    $(document).delegate(".js-icon-EnterPlatform-del", "click", function () {
        $(this).parent(".inner-item").remove();
    });

    // 普票增票
    $(".js-invoice-type-1").click(function(){
        $(".js-invoice-type-2-wrap").hide();
    });
    $(".js-invoice-type-2").click(function(){
        $(".js-invoice-type-2-wrap").show();
    });
});


/* 优惠列表 john@renlaifeng.cn 216-08-26 14:10*/
$(function(){
    // 点击显示当前优惠列表隐藏其他
    $(".js-sale-token li label input[type='radio']").click(function(){
        $(this).parents(".js-sale-token").find("li .group").hide();
        $(this).parent().parent().find(".group").show();
    });

    // 删除当前优惠
    $(document).delegate(".js-del-curr-sale","click",function(){
        $(this).parent(".group").remove();
    });

    // 添加一级优惠
    $(".js-add-new-sale-1").click(function(){
        var currGroupLength = $(this).parent(".group").siblings(".group").length;
        var newSaleType = '<div class="group pb-10"> <h6 class="inline w100 text-right">满：</h6> <input class="inline w60" type="text"> 元，减 <input class="inline w60" type="text"> 元 <a href="javascript:void(0);" class="tips js-del-curr-sale">删除</a> </div>';
        if(currGroupLength<3){
            $(this).parent(".group").before(newSaleType);
        };
        $(this).parent().parent().find(".group").show();
    });

    $(".js-add-new-sale-2").click(function(){
        var currGroupLength = $(this).parent(".group").siblings(".group").length;
        var newSaleType = '<div class="group pb-10"> <h6 class="inline w100 text-right">满：</h6> <input class="inline w60" type="text"> 元，折 <input class="inline w60" type="text"> % <a href="javascript:void(0);" class="tips js-del-curr-sale">删除</a> </div>';
        if(currGroupLength<3){
            $(this).parent(".group").before(newSaleType);
        };
        $(this).parent().parent().find(".group").show();
    });

    $(".js-add-new-sale-3").click(function(){
        var currGroupLength = $(this).parent(".group").siblings(".group").length;
        var newSaleType = '<div class="group pb-10"> <h6 class="inline w100 text-right">满：</h6> <input class="inline w60" type="text"> 件，减 <input class="inline w60" type="text"> 元 <a href="javascript:void(0);" class="tips js-del-curr-sale">删除</a> </div>';
        if(currGroupLength<3){
            $(this).parent(".group").before(newSaleType);
        };
        $(this).parent().parent().find(".group").show();
    });

    $(".js-add-new-sale-4").click(function(){
        var currGroupLength = $(this).parent(".group").siblings(".group").length;
        var newSaleType = '<div class="group pb-10"> <h6 class="inline w100 text-right">满：</h6> <input class="inline w60" type="text"> 件，折 <input class="inline w60" type="text"> % <a href="javascript:void(0);" class="tips js-del-curr-sale">删除</a> </div>';
        if(currGroupLength<3){
            $(this).parent(".group").before(newSaleType);
        };
        $(this).parent().parent().find(".group").show();
    });
});


/*!
 * 灯箱画廊插件
 * baguetteBox.js
 * @author  feimosi
 * @version 0.7.0
 * @url https://github.com/feimosi/baguetteBox.js
 */
var baguetteBox = (function() {
    // SVG shapes used in buttons
    var leftArrow = '<svg width="40" height="60" xmlns="http://www.w3.org/2000/svg" version="1.1">' +
            '<polyline points="30 10 10 30 30 50" stroke="rgba(255,255,255,0.5)" stroke-width="4"' +
              'stroke-linecap="butt" fill="none" stroke-linejoin="round">&lt;</polyline>' +
            '</svg>',
        rightArrow = '<svg width="40" height="60" xmlns="http://www.w3.org/2000/svg" version="1.1">' +
            '<polyline points="10 10 30 30 10 50" stroke="rgba(255,255,255,0.5)" stroke-width="4"' +
              'stroke-linecap="butt" fill="none" stroke-linejoin="round">&gt;</polyline>' +
            '</svg>',
        closeX = '<svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" version="1.1">' +
            '<g stroke="rgb(160, 160, 160)" stroke-width="4">' +
            '<line x1="5" y1="5" x2="25" y2="25"/>' +
            '<line x1="5" y1="25" x2="25" y2="5"/>' +
            'X</g></svg>';
    // Main ID names
    var overlayID = 'baguetteBox-overlay';
    var sliderID = 'baguetteBox-slider';
    // Global options and their defaults
    var options = {}, defaults = {
        captions: true,
        buttons: 'auto',
        async: false,
        preload: 2,
        animation: 'slideIn'
    };
    // DOM Elements references
    var overlay, slider, previousButton, nextButton, closeButton;
    // Current image index inside the slider and displayed gallery index
    var currentIndex = 0, currentGallery = -1;
    // Touch event start position (for slide gesture)
    var touchStartX;
    // If set to true ignore touch events because animation was already fired
    var touchFlag = false;
    // Array of all used galleries (DOM elements)
    var galleries = [];
    // 2D array of galleries and images inside them
    var imagesMap = [];
    // Array containing temporary images DOM elements
    var imagesArray = [];

    // forEach polyfill for IE8
    if(!Array.prototype.forEach) {
        Array.prototype.forEach = function(callback, thisArg) {
            var len = this.length;
            for(var i = 0; i < len; i++) {
                callback.call(thisArg, this[i], i, this);
            }
        };
    }

    // Script entry point
    function run(selector, userOptions) {
        buildOverlay();
        // For each gallery bind a click event to every image inside it
        galleries = document.querySelectorAll(selector);
        [].forEach.call(
            galleries,
            function (galleryElement, galleryIndex) {
                var galleryID = imagesMap.length;
                imagesMap.push(galleryElement.getElementsByTagName('a'));
                imagesMap[galleryID].options = userOptions;
                [].forEach.call(
                    imagesMap[galleryID],
                    function (imageElement, imageIndex) {
                        bind(imageElement, 'click', function(event) {
                            /*jshint -W030 */
                            event.preventDefault ? event.preventDefault() : event.returnValue = false;
                            prepareOverlay(galleryID);
                            showOverlay(imageIndex);
                        });
                    }
                );
            }
        );
        defaults.transforms = testTransformsSupport();
    }

    function buildOverlay() {
        overlay = document.getElementById(overlayID);
        // Check if the overlay already exists
        if(overlay) {
            slider = document.getElementById(sliderID);
            previousButton = document.getElementById('previous-button');
            nextButton = document.getElementById('next-button');
            closeButton = document.getElementById('close-button');
            return;
        }
        // Create overlay element
        overlay = document.createElement('div');
        overlay.id = overlayID;
        document.getElementsByTagName('body')[0].appendChild(overlay);
        // Create gallery slider element
        slider = document.createElement('div');
        slider.id = sliderID;
        overlay.appendChild(slider);
        // Create all necessary buttons
        previousButton = document.createElement('button');
        previousButton.id = 'previous-button';
        previousButton.innerHTML = leftArrow;
        overlay.appendChild(previousButton);

        nextButton = document.createElement('button');
        nextButton.id = 'next-button';
        nextButton.innerHTML = rightArrow;
        overlay.appendChild(nextButton);

        closeButton = document.createElement('button');
        closeButton.id = 'close-button';
        closeButton.innerHTML = closeX;
        overlay.appendChild(closeButton);

        previousButton.className = nextButton.className = closeButton.className = 'baguetteBox-button';

        bindEvents();
    }

    function bindEvents() {
        // When clicked on the overlay (outside displayed image) close it
        bind(overlay, 'click', function(event) {
            if(event.target && event.target.nodeName !== "IMG")
                hideOverlay();
        });
        // Add event listeners for buttons
        bind(document.getElementById('previous-button'), 'click', function(event) {
            /*jshint -W030 */
            event.stopPropagation ? event.stopPropagation() : event.cancelBubble = true;
            showPreviousImage();
        });
        bind(document.getElementById('next-button'), 'click', function(event) {
            /*jshint -W030 */
            event.stopPropagation ? event.stopPropagation() : event.cancelBubble = true;
            showNextImage();
        });
        bind(document.getElementById('close-button'), 'click', function(event) {
            /*jshint -W030 */
            event.stopPropagation ? event.stopPropagation() : event.cancelBubble = true;
            hideOverlay();
        });
        // Add touch events
        bind(overlay, 'touchstart', function(event) {
            // Save x axis position
            touchStartX = event.changedTouches[0].pageX;
        });
        bind(overlay, 'touchmove', function(event) {
            if(touchFlag)
                return;
            /*jshint -W030 */
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            touch = event.touches[0] || event.changedTouches[0];
            if(touch.pageX - touchStartX > 40) {
                touchFlag = true;
                showPreviousImage();
            } else if (touch.pageX - touchStartX < -40) {
                touchFlag = true;
                showNextImage();
            }
        });
        bind(overlay, 'touchend', function(event) {
            touchFlag = false;
        });
        // Activate keyboard shortcuts
        bind(document, 'keydown', function(event) {
            switch(event.keyCode) {
                case 37: // Left arrow
                    showPreviousImage();
                    break;
                case 39: // Right arrow
                    showNextImage();
                    break;
                case 27: // Esc
                    hideOverlay();
                    break;
            }
        });
    }

    function prepareOverlay(galleryIndex) {
        // If the same gallery is being opened prevent from loading it once again
        if(currentGallery === galleryIndex)
            return;
        currentGallery = galleryIndex;
        // Update gallery specific options
        setOptions(imagesMap[galleryIndex].options);
        // Empty slider of previous contents (more effective than .innerHTML = "")
        while(slider.firstChild)
            slider.removeChild(slider.firstChild);
        imagesArray.length = 0;
        // Prepare and append images containers
        for(var i = 0; i < imagesMap[galleryIndex].length; i++) {
            imagesArray.push(returnImageContainer());
            slider.appendChild(imagesArray[i]);
        }
    }

    function setOptions(newOptions) {
        if(!newOptions)
            newOptions = {};
        for(var item in defaults) {
            options[item] = defaults[item];
            if(typeof newOptions[item] !== 'undefined')
                options[item] = newOptions[item];
        }
        /* Apply new options */
        // Change transition for proper animation
        slider.style.transition = slider.style.webkitTransition = options.animation === 'fadeIn' ? 'opacity .4s ease' : '';
        // Hide buttons if necessary
        if(options.buttons === 'auto' && ('ontouchstart' in window || imagesMap[currentGallery].length === 1))
            options.buttons = false;
        // Set buttons style to hide or display them
        previousButton.style.display = nextButton.style.display = options.buttons ? '' : 'none';
    }

    // Return DOM element for image container <div class="full-image">...</div>
    function returnImageContainer() {
        var fullImage = document.createElement('div');
        fullImage.className = 'full-image';
        return fullImage;
    }

    function showOverlay(index) {
        // Return if overlay is already visible
        if(overlay.style.display === 'block')
            return;
        // Set current index to a new value and show proper image
        currentIndex = index;
        loadImage(currentIndex, function() {
            preloadNext(currentIndex);
            preloadPrev(currentIndex);
        });
        updateOffset();
        overlay.style.display = 'block';
        // Fade in overlay
        setTimeout(function() {
            overlay.className = 'visible';
        }, 50);
    }

    function hideOverlay() {
        // Return if overlay is already hidden
        if(overlay.style.display === 'none')
            return;
        // Fade out and hide the overlay
        overlay.className = '';
        setTimeout(function() {
            overlay.style.display = 'none';
        }, 500);
    }

    function loadImage(index, callback) {
        var imageContainer = imagesArray[index];
        // If index is invalid return
        if(typeof imageContainer === 'undefined')
            return;
        // If image is already loaded run callback and return
        if(imageContainer.getElementsByTagName('img')[0]) {
            if(callback)
                callback();
            return;
        }
        // Get element reference, optional caption and source path
        imageElement = imagesMap[currentGallery][index];
        imageCaption = imageElement.getAttribute('data-caption') || imageElement.title;
        imageSrc = getImageSrc(imageElement);
        // Prepare image container elements
        var figure = document.createElement('figure');
        var image = document.createElement('img');
        var figcaption = document.createElement('figcaption');
        imageContainer.appendChild(figure);
        // Add loader element
        figure.innerHTML = '<div class="spinner">' +
            '<div class="double-bounce1"></div>' +
            '<div class="double-bounce2"></div>' +
            '</div>';
        // Set callback function when image loads
        image.onload = function() {
            // Remove loader element
            var spinner = this.parentNode.querySelector('.spinner');
            this.parentNode.removeChild(spinner);
            if(!options.async && callback)
                callback();
        };
        image.setAttribute('src', imageSrc);
        figure.appendChild(image);
        // Insert caption if available
        if(options.captions && imageCaption) {
            figcaption.innerHTML = imageCaption;
            figure.appendChild(figcaption);
        }
        // Run callback
        if(options.async && callback)
            callback();
    }

    function getImageSrc(image) {
        // Set dafult image path from href
        var result = imageElement.getAttribute('href');
        // If dataset is supported find the most suitable image
        if(image.dataset) {
            var srcs = [];
            // Get all possible image versions depending on the resolution 
            for(var item in image.dataset) {
                if(item.substring(0, 3) === 'at-' && !isNaN(item.substring(3)))
                    srcs[item.replace('at-', '')] = image.dataset[item];
            }
            // Sort resolutions ascending
            keys = Object.keys(srcs).sort(function(a, b) {
                return parseInt(a) < parseInt(b) ? -1 : 1;
            });
            // Get real screen resolution 
            var width = window.innerWidth * window.devicePixelRatio;
            // Find first image bigger than or equal to the current width
            for(var i = 0; i < keys.length; i++) {
                if(keys[i] >= width) {
                    result = srcs[keys[i]];    
                    break;
                }
                result = srcs[keys[i]];
            }
        }
        return result;
    }

    function showNextImage() {
        if(currentIndex <= imagesArray.length - 2) {
            currentIndex++;
            updateOffset();
            preloadNext(currentIndex);
        } else {
            slider.className = 'bounce-from-right';
            setTimeout(function() {
                slider.className = '';
            }, 400);
        }
    }

    function showPreviousImage() {
        if(currentIndex >= 1) {
            currentIndex--;
            updateOffset();
            preloadPrev(currentIndex);
        } else {
            slider.className = 'bounce-from-left';
            setTimeout(function() {
                slider.className = '';
            }, 400);
        }
    }

    function updateOffset() {
        var offset = -currentIndex * 100 + '%';
        if(options.animation === 'fadeIn') {
            slider.style.opacity = 0;
            setTimeout(function() {
                /*jshint -W030 */
                options.transforms ?
                    slider.style.transform = slider.style.webkitTransform = 'translate3d(' + offset + ',0,0)'
                    : slider.style.left = offset;
                slider.style.opacity = 1;
            }, 400);
        } else {
            /*jshint -W030 */
            options.transforms ?
                slider.style.transform = slider.style.webkitTransform = 'translate3d(' + offset + ',0,0)'
                : slider.style.left = offset;
        }
    }

    function testTransformsSupport() {
        var div = document.createElement('div'),
            support = false;
        support = typeof div.style.perspective !== 'undefined' || typeof div.style.webkitPerspective !== 'undefined';
        return support;
    }

    function preloadNext(index) {
        if(index - currentIndex >= options.preload)
            return;
        loadImage(index + 1, function() { preloadNext(index + 1); });
    }

    function preloadPrev(index) {
        if(currentIndex - index >= options.preload)
            return;
        loadImage(index - 1, function() { preloadPrev(index - 1); });
    }

    function bind(element, event, callback) {
        if(element.addEventListener)
            element.addEventListener(event, callback, false);
        else
            element.attachEvent('on' + event, callback);
    }

    return {
        run: run
    };
})();


/* 顶部幻灯片设置 john@renlaifeng.cn 2016-08-31 09:50 */
$(function(){
    $(document).on("click",".js-slide-img-list .slide-img",function(){
        $(this).addClass("on").parent("li").siblings().find(".slide-img").removeClass("on");
    })
});


/* 复制插件 http://9iphp.com/web/javascript/js-copy-library-clipboard-js.html 文档中还有多种调用方法  请大家自行参考 */
$(function(){
    var clipboard = new Clipboard('.js-clip-btn', {
        text: function(trigger) {
            return trigger.getAttribute('data-url');     //复制的内容   相当于$(this).attr('data-url')
        }
    });
    //成功事件
    clipboard.on('success', function(e) {    
        alertTan("复制成功");
    });
    //失败事件
    clipboard.on('error', function(e) {
        alertTan("复制失败");
    });
})


/* 新建线上调研活动 john@renlaifeng.cn 2016-09-01 20:50 */
$(function(){
    // 问题选项的option自动列标
    function optionAutoList(){
        var listNo = $(".js-option-numb").parent().parent().parent();
        var b = 1;
        listNo.each(function(){
            var a = 1;
            $(this).parent().prev().find(".js-tit-numb").html(b);
            $(this).find("li").each(function(){
                $(this).find(".js-option-numb").text(a++);
            });
            b++;
        });
    };
    optionAutoList();


    // 选择单选复选还是文本
    $(document).delegate(".js-option-text","click",function(){
        $(this).parents(".set-inner").find(".js-option-list").slideUp(120);
        $(this).parents(".set-inner").find(".js-option-img-warp").slideUp(120);
        $(this).parents(".set-inner").find(".js-must-ornot").slideDown(120);
    });
    $(document).delegate(".js-option-radio,.js-option-checkbox","click",function(){
        $(this).parents(".set-inner").find(".js-option-list").slideDown(120);
        $(this).parents(".set-inner").find(".js-must-ornot").slideUp(120);
        $(this).parents(".set-inner").find(".js-option-img-warp").slideUp(120);
    });

    // 放下和收起问题
    $(document).on("click",".js-question-toggle",function(){
        var titText = $(this).parents(".set-tit").next(".set-inner").find(".js-set-tit").val();
        $(this).parent().next(".set-inner").slideToggle(120,function(){
            $(this).parent().find(".js-set-tit-top").toggle();
            if($(this).is(":visible")){
                $(this).prev(".set-tit").find(".js-question-toggle h5").text("收起");
                $(this).prev(".set-tit").find(".js-set-tit-top").hide().text(titText);
            }else{
                $(this).prev(".set-tit").find(".js-question-toggle h5").text("编辑");
                $(this).prev(".set-tit").find(".js-set-tit-top").show().text(titText);
            };
        });
    });

    // 输入问题的文本同步到标题栏
    $(document).on("input",".js-set-tit",function(){
        $(this).parent().parent().parent().find(".js-set-tit-top").text($(this).val());
    })


    // 放下和收起图片描述
    $(document).on("click",".js-img-describe",function(){
        var currTextarea = $(this).parent().next();
        currTextarea.slideToggle(120,function(){
            if(currTextarea.is(":visible")){
                $(this).prev().children().text("收起图片描述 (100字以内)");
            }else if(currTextarea.is(":hidden")){
                $(this).prev().children().text("添加图片描述 (100字以内)");
            }
        });
    });

    // 上传图片展开收起
    $(document).on("click",".js-option-img",function(){
        $(this).parents(".set-inner").find(".js-option-img-warp").slideDown(120);
        $(this).parents(".set-inner").find(".js-must-ornot").slideDown(120);
        $(this).parents(".set-inner").find(".js-option-list").slideUp(120);
    });

    //删除图片和描述
    $(document).on("click",".js-del-img-cover",function(){
        $(this).parent().parent().slideUp(120,function(){
            $(this).remove();
        })
    });

    // 删除当前选项
    $(document).on("click",".js-del-option",function(){
        $(this).parent().parent().slideUp(120,function(){
            $(this).remove();
            optionAutoList();
        });
    });

    // 添加新的选项
    $(document).on("click",".js-add-option",function(){
        var newOption = '<li class="group pb-20"> <h6 class="inline w100">选项<span class="js-option-numb"></span>：</h6><input class="inline w300" type="text" name=""> <a class="btn-small btn-white ">上传图片</a> <h6 class="inline"><a class="tips js-del-option">删除选项</a></h6> <h6 class="text-gray ml-100 pt-5">限制15字以内</h6> </li>'
        $(this).parent().parent().before(newOption);
        optionAutoList();
    });


    // 添加问题
    $(document).on("click",".js-add-question-btn",function(){
        var addQuestion = '<li class="mb-20"> <div class="set-tit clearfix"> <h5 class="fl mr-20">问题 <span class="js-tit-numb"></span></h5> <h5 class="fl js-set-tit-top" style="display:none;"></h5> <a class="tips fr js-del-question-btn"><h5>删除</h5></a> <a class="tips mr-20 fr js-question-toggle"><h5>收起</h5></a></div>';
        addQuestion += '<div class="set-inner"> <div class="group pb-20"> <h6 class="inline w100">题目：</h6><input class="inline w400 js-set-tit" type="text" name=""> <h6 class="text-gray ml-100 pt-5">限制35字以内</h6> </div>';
        addQuestion += '<div class="group pb-20"> <h6 class="inline w100">题目类型：</h6> <label class="radio inline mr-20"><input class="js-option-radio" type="radio" name="setting-toggle" checked="">单选</label> <label class="radio inline mr-20"><input class="js-option-checkbox" type="radio" name="setting-toggle">多选</label> <label class="radio inline mr-20"><input class="js-option-text" type="radio" name="setting-toggle">单行文本</label> <label class="radio inline mr-20"><input class="js-option-text" type="radio" name="setting-toggle">多行文本</label> <label class="radio inline"><input class="js-option-img" type="radio" name="setting-toggle">上传图片</label></div>';
        addQuestion += '<ul class="js-option-list"> <li class="group pb-20"> <h6 class="inline w100">选项<span class="js-option-numb">1</span>：</h6><input class="inline w300" type="text" name=""> <a class="btn-small btn-white ">上传图片</a> <h6 class="text-gray ml-100 pt-5">限制15字以内</h6> </li> <li class="group pb-20"> <h6 class="inline w100">选项<span class="js-option-numb">2</span>：</h6><input class="inline w300" type="text" name=""> <a class="btn-small btn-white ">上传图片</a> <h6 class="text-gray ml-100 pt-5">限制15字以内</h6> </li> <li><h6 class="inline"><a class="tips js-add-option">添加选项</a></h6></li> </ul> </div> </li>';
        $(this).parent().before(addQuestion);
        optionAutoList();
    });

    // 删除问题
    $(document).on("click",".js-del-question-btn",function(){
        $(this).parents(".set-tit").parent().slideUp(120,function(){
            $(this).remove();
            optionAutoList();
        });
    });


});


/* 新建预订项目 选择时段 john@renlaifeng.cn 2016-09-09 14:00 */
$(function(){
    // 想选谁就选谁啦
    $(document).on("click",".js-time-interval>li>a",function(){
        $(this).toggleClass("on");
    });

    // 无限制？整周一致？还是单日不同？
    $(".js-by-anyway").click(function(){
        $(".js-by-day-wrap, .js-by-week-wrap").slideUp(250);
    });
    $(".js-by-day").click(function(){
        $(".js-by-week-wrap").slideUp(250);
        $(".js-by-day-wrap").slideDown(250);
    });
    $(".js-by-week").click(function(){
        $(".js-by-day-wrap").slideUp(250);
        $(".js-by-week-wrap").slideDown(250);
    });
});


/* check 子用户账号 新增子账号 john@renlaifeng.cn 2016-09-13 11:10 */
$(function(){
    // 新增子账号
    $(".js-check-add-new-user").click(function(){
        var _html ='<li> <div class="inner-header"><h5>子用户账号 <a href="javascript:void(0);" class="tips js-check-del-new-user">删除子账号</a></h5></div> <div class="content"> <dt class="clearfix pb-15"> <dl class="child-sum-3 fl"> <h6 class="inline w100">账号类型：</h6> <h6 class="inline w150">子用户账号</h6> </dl> <dl class="child-sum-3 fl"> <h6 class="inline w100">登录名：</h6> <input class="inline w150" type="text" name=""> </dl> <dl class="child-sum-3 fl"> <h6 class="inline w100">密码：</h6> <input class="inline w150" type="text" name=""> </dl> </dt> <dt class="clearfix pb-15"> <dl class="child-sum-3 fl"> <h6 class="inline w100">姓名：</h6> <input class="inline w150" type="text" name=""> </dl> <dl class="child-sum-3 fl"> <h6 class="inline w100">手机：</h6> <input class="inline w150" type="text" name=""> </dl> <dl class="child-sum-3 fl"> <h6 class="inline w100">工作座机：</h6> <input class="inline w150" type="text" name=""> </dl> </dt> <dt class="clearfix pb-15"> <dl class="child-sum-3 fl"> <h6 class="inline w100">工作邮箱：</h6> <input class="inline w150" type="text" name=""> </dl> <dl class="child-sum-3 fl"> <h6 class="inline w100">门店权限：</h6> <select class="inline w150"> <option>请选择门店权限</option> <option>全部门店</option> <option>门店名称1</option> <option>门店名称2</option> </select> </dl> </dt> <dt class="clearfix pb-15"> <dl class="child-sum-3 fl"> <h6 class="inline w100">SCRM5PC权限：</h6> <h6 class="inline"><a class="tips">权限配置</a></h6> </dl> <dl class="child-sum-3 fl"> <h6 class="inline w100">风助手权限：</h6> <h6 class="inline"><a class="tips">权限配置</a></h6> </dl> </dt> <dt class="clearfix pb-15"> <dl class="child-sum-3 fl"> <h6 class="inline w100">拉卡拉POSkey：</h6> <input class="inline w150" type="text" name=""> </dl> <dl class="child-sum-3 fl"> <h6 class="inline w100">拉卡拉POS权限：</h6> <h6 class="inline"><a class="tips">权限配置</a></h6> </dl> </dt> </div> </li>';
        $(this).parents(".js-check-user-list").append(_html);
    });

    // 删除子账号
    $(document).on("click",".js-check-del-new-user",function(){
        $(this).parents("li").slideUp(120,function(){
            $(this).remove();
        });
    });
});


/* 闪惠设置 john@renlaifeng.cn 2016-09-20 17:50 */
$(function(){
    // 优惠信息点击显示隐藏
    $(document).on("click",".js-group-c1 label",function(){
        console.log("hey John!");
        $(this).parents(".js-group-c1").siblings().find(".js-view-cover").slideUp(200);
        $(this).next(".js-view-cover").slideDown(200);
    });

    // 新增优惠项目 二级
    $(document).on("click",".js-add-new-privilege.type-1",function(){
        var itemLen = $(this).parent().parent().parent().find(".js-group-c2").length;
        var _html = '<dt class="inner-group pb-5 js-group-c2"> <h6 class="inline w100">满：</h6> <input class="inline w70" type="text" name=""> <h6 class="inline">元，减</h6> <input class="inline w70" type="text" name=""> <h6 class="inline mr-20">元</h6> <h5 class="inline mr-10"><a class="tips js-del-curr-privilege">删除该级优惠</a></h5> </dt>'
        if(itemLen<3){
            $(this).parent().parent().before(_html);
        }
    });
    $(document).on("click",".js-add-new-privilege.type-2",function(){
        var itemLen = $(this).parent().parent().parent().find(".js-group-c2").length;
        var _html = '<dt class="inner-group pb-5 js-group-c2"> <h6 class="inline w100">满：</h6> <input class="inline w70" type="text" name=""> <h6 class="inline">元，折</h6> <input class="inline w70" type="text" name=""> <h6 class="inline mr-20">%</h6> <h5 class="inline mr-10"><a class="tips js-del-curr-privilege">删除该级优惠</a></h5> </dt>'
        if(itemLen<3){
            $(this).parent().parent().before(_html);
        }
    });

    // 删除当前项目 二级
    $(document).on("click",".js-del-curr-privilege",function(){
        $(this).parents(".js-group-c2").slideUp(150,function(){
            $(this).remove();
        })
    });

    // 删除该优惠时段 一级
    $(document).on("click",".js-del-curr-period",function(){
        $(this).parent().parent().parent().slideUp(150,function(){
            $(this).remove();
        })
    })

    // 新增该优惠时段 一级
    var periodHtml = '<dt class="inner-group pb-5"> <h6 class="inline w100">优惠时段限制：</h6> <select class="inline w70"><option>周日</option><option>周一</option><option>周二</option><option>周三</option><option>周四</option><option>周五</option><option>周六</option></select> - <select class="inline w70 mr-20"><option>周日</option><option>周一</option><option>周二</option><option>周三</option><option>周四</option><option>周五</option><option>周六</option></select> <select class="inline w60"><option>00:00</option><option>01:00</option><option>02:00</option><option>03:00</option><option>04:00</option><option>05:00</option><option>06:00</option><option>07:00</option><option>08:00</option><option>09:00</option><option>10:00</option><option>11:00</option><option>12:00</option><option>13:00</option><option>14:00</option><option>15:00</option><option>16:00</option><option>17:00</option><option>18:00</option><option>19:00</option><option>20:00</option><option>21:00</option><option>22:00</option><option>23:00</option></select> - <select class="inline w60 mr-20"><option>00:00</option><option>01:00</option><option>02:00</option><option>03:00</option><option>04:00</option><option>05:00</option><option>06:00</option><option>07:00</option><option>08:00</option><option>09:00</option><option>10:00</option><option>11:00</option><option>12:00</option><option>13:00</option><option>14:00</option><option>15:00</option><option>16:00</option><option>17:00</option><option>18:00</option><option>19:00</option><option>20:00</option><option>21:00</option><option>22:00</option><option>23:00</option></select> <h5 class="inline"><a class="tips js-del-curr-period">删除该优惠时段</a></h5> </dt>'
    $(document).on("click",".js-add-new-period.type-1",function(){
        var _html = '<dl>'+ periodHtml +'<dt class="inner-group pb-5"><h6 class="inline w100">立减：</h6> <input class="inline w70" type="text" name=""> <h6 class="inline">元</h6></dt></dl>'
        $(this).parent().before(_html);
    });
    $(document).on("click",".js-add-new-period.type-2",function(){
        var _html = '<dl>'+ periodHtml +'<dt class="inner-group pb-5"><h6 class="inline w100">立折：</h6> <input class="inline w70" type="text" name=""> <h6 class="inline">%</h6></dt></dl>'
        $(this).parent().before(_html);
    });
    $(document).on("click",".js-add-new-period.type-3",function(){
        var _html = '<dl>'+ periodHtml +'<dt class="inner-group pb-5 js-group-c2"><h6 class="inline w100">满：</h6> <input class="inline w70" type="text" name=""> <h6 class="inline">元，减</h6> <input class="inline w70" type="text" name=""> <h6 class="inline mr-20">元</h6></dt><dt class="ml-100 pb-5"><h5 class="inline"><a class="tips js-add-new-privilege type-1">&nbsp;新增一级优惠</a></h5> <h6 class="inline text-gray">*每级优惠不能累计，最多设置三级优惠</h6></dt></dl>'
        $(this).parent().before(_html);
    });
    $(document).on("click",".js-add-new-period.type-4",function(){
        var _html = '<dl>'+ periodHtml +'<dt class="inner-group pb-5 js-group-c2"><h6 class="inline w100">满：</h6> <input class="inline w70" type="text" name=""> <h6 class="inline">元，折</h6> <input class="inline w70" type="text" name=""> <h6 class="inline mr-20">%</h6></dt><dt class="ml-100 pb-5"><h5 class="inline"><a class="tips js-add-new-privilege type-2">&nbsp;新增一级优惠</a></h5> <h6 class="inline text-gray">*每级优惠不能累计，最多设置三级优惠</h6></dt></dl>'
        $(this).parent().before(_html);
    });
    $(document).on("click",".js-add-new-period.type-5",function(){
        var _html = '<dl>'+ periodHtml +'<dt class="inner-group pb-5"><h6 class="inline w100">每满：</h6> <input class="inline w70" type="text" name=""> <h6 class="inline">元，减</h6> <input class="inline w70" type="text" name=""> <h6 class="inline mr-20">元</h6></dt></dl>'
        $(this).parent().before(_html);
    });
});
$(".QR-code-cover").mouseover(function(){
    $(this).find(".QR-code-box-down").remove();
});
$(".QR-code-cover").mouseout(function(){
    setTimeout(function(){$(this).children(".QR-code-box-down").show('slow')},1000);
})


/* 会员卡页面设计 john@renlaifeng.cn 2016-11-10 11:30 */
$(function(){
    $(document).on("input",".js-vipcard-tit1-set",function(){
        var setVal = $(this).val();
        $(".js-vipcard-tit1").text(setVal);
    });
    $(document).on("input",".js-vipcard-tit2-set",function(){
        var setVal = $(this).val();
        $(".js-vipcard-tit2").text(setVal);
    });
    $(document).on("blur",".js-vipcard-bgc-set",function(){
        var setVal = $(this).css("backgroundColor");
        // console.log(setVal);
        $(".js-vipcard-bgc").css("backgroundColor",setVal);
    });
    $(document).on("blur",".js-vipcard-color-set",function(){
        var setVal = $(this).css("backgroundColor");
        // console.log(setVal);
        $(".js-vipcard-bgc").css("color",setVal);
    });
    $(document).on("blur",".js-mod-con-color-set",function(){
        var setVal = $(this).css("backgroundColor");
        // console.log(setVal);
        $(".js-mod-con-color").css("color",setVal);
    });
})


/* 卡券中心 john@renlaifeng.cn 2016-10-31 16:10 */
$(function(){
    /* 优惠类型折叠 */
    $(".js-sale-toggle label").click(function(){
        $(this).parent("li").siblings("li").children(".group").slideUp(120);
        $(this).next(".group").slideDown(120);
    });

    /* 卡券名称 动态获取更新 */
    $(document).on("input",".js-tit-name-set",function(){
        $(".js-tit-name").text($(this).val());
    });

    /* 立减优惠 */
    $(document).on("input",".js-lijian + div input",function(){
        $(".js-tit-how-to-use").text("立减"+$(this).val()+"元");
    });
    $(document).on("click",".js-lijian",function(){
        $(".js-tit-how-to-use").text("立减"+$(this).next(".group").children("input").val()+"元");
    })

    /* 立折优惠 */
    $(document).on("input",".js-lizhe + div input",function(){
        $(".js-tit-how-to-use").text("立折"+$(this).val()+"%");
    });
    $(document).on("click",".js-lizhe",function(){
        $(".js-tit-how-to-use").text("立折"+$(this).next(".group").children("input").val()+"%");
    });

    /* 满减优惠 */
    $(document).on("input",".js-manjian + div input",function(){
        $(".js-tit-how-to-use").text("满"+$(".js-manjian + div input:eq(0)").val()+"元，减"+$(".js-manjian + div input:eq(1)").val()+"元");
    });
    $(document).on("click",".js-manjian",function(){
        $(".js-tit-how-to-use").text("满"+$(".js-manjian + div input:eq(0)").val()+"元，减"+$(".js-manjian + div input:eq(1)").val()+"元");
    });

    /* 满折优惠 */
    $(document).on("input",".js-manzhe + div input",function(){
        $(".js-tit-how-to-use").text("满"+$(".js-manzhe + div input:eq(0)").val()+"元，折"+$(".js-manzhe + div input:eq(1)").val()+"%");
    });
    $(document).on("click",".js-manzhe",function(){
        $(".js-tit-how-to-use").text("满"+$(".js-manzhe + div input:eq(0)").val()+"元，折"+$(".js-manzhe + div input:eq(1)").val()+"%");
    });

    /* 每满减优惠 */
    $(document).on("input",".js-meimanjian + div input",function(){
        $(".js-tit-how-to-use").text("每满"+$(".js-meimanjian + div input:eq(0)").val()+"元，减"+$(".js-meimanjian + div input:eq(1)").val()+"元");
    });
    $(document).on("click",".js-meimanjian",function(){
        $(".js-tit-how-to-use").text("每满"+$(".js-meimanjian + div input:eq(0)").val()+"元，减"+$(".js-meimanjian + div input:eq(1)").val()+"元");
    });
})
/* 卡券中心 第二版 时间DOM john@renlaifeng.cn 2016-11-08 14:10 */
$(function(){
    /*var afterDay = 0;
    var date1 = new Date();
    var date1Text = date1.getFullYear()+"."+(date1.getMonth()+1)+"."+date1.getDate(); 
    console.log(date1Text);
    var date2 = new Date(date1);
    date2.setDate(date1.getDate()+afterDay);
    var times = date2.getFullYear()+"."+(date2.getMonth()+1)+"."+date2.getDate();
    console.log(times); */


    /*$(".js-currdate-after").parent("label").click(function(){
        var afterDay = 0;
        var date1 = new Date();
        var date1Text = date1.getFullYear()+"."+(date1.getMonth()+1)+"."+date1.getDate(); 
        var date2 = new Date(date1);
        date2.setDate(date1.getDate()+afterDay);
        var times = date2.getFullYear()+"."+(date2.getMonth()+1)+"."+date2.getDate();

        $(".js-date-1").text(date1Text);
        $(".js-date-2").text(times);
    });
    $(document).on("input",".js-currdate-after",function(){
        var afterDay = $(this).val()-0;
        console.log(afterDay);
        var date1 = new Date();
        var date1Text = date1.getFullYear()+"."+(date1.getMonth()+1)+"."+date1.getDate(); 
        var date2 = new Date(date1);

        date2.setDate(date1.getDate()+afterDay);
        var times = date2.getFullYear()+"."+(date2.getMonth()+1)+"."+date2.getDate();

        $(".js-date-1").text(date1Text);
        $(".js-date-2").text(times);
    });
    $(".js-currdate-after").click(function(){
        $(this).parent("label").click();
        $(this).focus();
    });*/


    /*$(document).on("blur",".js-someday",function(){
        setTimeout(function(){
            var $tempStr = $(".js-someday").val();
            $tempStr = $tempStr.replace(/-/g,".");
            $(".js-date-1").text($tempStr);
        },200);
        $(".js-date-2").text("当天有效");
        $(this).parent("label").click();
    });*/


    /*$(document).on("blur",".js-anyday-1",function(){
        setTimeout(function(){
            var $tempStr1 = $(".js-anyday-1").val();
            $tempStr1 = $tempStr1.replace(/-/g,".");
            $(".js-date-1").text($tempStr1);
        },200);
        var $tempStr2 = $(".js-anyday-2").val();
        $tempStr2 = $tempStr2.replace(/-/g,".");
        $(".js-date-2").text($tempStr2);
        $(this).parent("label").click();
    });
    $(document).on("blur",".js-anyday-2",function(){
        setTimeout(function(){
            var $tempStr2 = $(".js-anyday-2").val();
            $tempStr2 = $tempStr2.replace(/-/g,".");
            $(".js-date-2").text($tempStr2);
        },200);
        var $tempStr1 = $(".js-anyday-1").val();
        $tempStr1 = $tempStr1.replace(/-/g,".");
        $(".js-date-1").text($tempStr1);
        $(this).parent("label").click();
    });*/
})
/* 卡券中心 第三版了 上帝保佑不要再改了阿门 john@renlaifeng.cn 2016-11-10 13:30 */
$(function(){
    /* eshop优惠券类型 获得多少日后有效 */
    function currdateAfter(){
        var finalHtml = new String;
        var dateVal = $(".js-currdate-after").val();
        finalHtml = '在收到券后 '+dateVal+' 日内有效';
        // console.log(finalHtml);
        $(".js-tit-date").text(finalHtml);
    };
    $(document).on("input",".js-currdate-after",function(){
        currdateAfter();
    });
    $(document).on("focus",".js-currdate-after",function(){
        $(this).prev("input[type=radio]").click();
    });
    $(".js-currdate-after").prev("input[type=radio]").click(function(){
        currdateAfter();
    });

    /* eshop优惠券类型 某日当天有效 */
    function someDay(){
        setTimeout(function(){
            var finalHtml = new String;
            var dateVal = $(".js-someday").val();
            finalHtml = '本券在 '+dateVal+' 当日内有效';
            // console.log(finalHtml);
            $(".js-tit-date").text(finalHtml);
        },200);
    };
    $(document).on("blur",".js-someday",function(){
        someDay();
    });
    $(document).on("focus",".js-someday",function(){
        $(this).prev("input[type=radio]").click();
    });
    $(".js-someday").prev("input[type=radio]").click(function(){
        someDay();
    });

    /* eshop优惠券类型 某天到某天有效 */
    function anyDay(){
        setTimeout(function(){
            var finalHtml = new String;
            var dateVal1 = $(".js-anyday-1").val();
            var dateVal2 = $(".js-anyday-2").val();
            finalHtml = dateVal1+" 至 "+dateVal2+" 有效"; 
            // console.log(finalHtml);
            $(".js-tit-date").text(finalHtml);
        },200);
    };
    $(document).on("blur",".js-anyday-1,.js-anyday-2",function(){
        anyDay();
    });
    $(document).on("focus",".js-anyday-1,.js-anyday-2",function(){
        $(".js-anyday-1").prev("input[type=radio]").click();
    });
    $(".js-anyday-1").prev("input[type=radio]").click(function(){
        anyDay();
    });
})


/* 文本长度计算 2016-11-11 20:40 */
$(function(){
    $(document).on("input",".js-textarea-length",function(){
        var strLen = $(this).val().length;
        // console.log(strLen);
        var msgNumb = Math.ceil(strLen/70);
        // console.log(msgNumb);
        $(this).parents(".textarea-length").find(".js-text-len").text('统计条数：'+msgNumb+'条');
        if(strLen>350){
            $(this).parents(".textarea-length").find(".js-text-len").text('已超出最大字符数');
        }
    })
})


/**全选反选
 * john@renlaifeng.cn
 * 2016-12-15 10:40
 * .js-check-cover
 * .js-check-all
 * .js-check-item
 */
$(function(){
    $(".js-check-all").on("click",function(){
        (this.checked) ? ($(this).parents(".js-check-cover").find(".js-check-item").prop("checked",true)) : ($(this).parents(".js-check-cover").find(".js-check-item").prop("checked",false));
    });
    $(".js-check-item").on("click",function(){
        var checkedNum = $(this).parents(".js-check-cover").find(".js-check-item:checked").size();
        var checkNum = $(this).parents(".js-check-cover").find(".js-check-item").size();
        (checkedNum==checkNum) ? ($(this).parents(".js-check-cover").find(".js-check-all").prop("checked",true)) : ($(this).parents(".js-check-cover").find(".js-check-all").prop("checked",false));
    });
})















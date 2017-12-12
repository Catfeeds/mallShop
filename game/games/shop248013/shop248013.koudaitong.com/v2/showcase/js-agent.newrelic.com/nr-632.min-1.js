<!DOCTYPE html>
<!--[if IEMobile 7 ]>    <html class="no-js iem7"> <![endif]-->
<!--[if (gt IEMobile 7)|!(IEMobile)]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="keywords" content="有赞,微信商城,粉丝营销,微信商城运营" />
    <meta name="description" content="有赞是帮助商家在微信上搭建微信商城的平台，提供店铺、商品、订单、物流、消息和客户的管理模块，同时还提供丰富的营销应用和活动插件。" />
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="cleartype" content="on">

    <link rel="icon" href="http://b.yzcdn.cn/v2/image/yz_fc.ico" />

	<title>对不起，您访问的页面已被删除或不存在 - 有赞</title>

    <!-- ▼Page CSS -->
    <link rel="stylesheet" href="http://b.yzcdn.cn/v2/build_css/wap_404_4846247fe7.css" onerror="_cdnFallback(this)">    <!-- ▲Page CSS -->

</head>

<body>

<!-- ▼.container -->
<div class="container">
    <div class="wap-404">
        <div class="mascot-obama" id="obama">
        </div>
        <h1>对不起，您访问的页面已被删除或不存在。</h1>
    </div>
</div>
<!-- ▲.container -->

<script src="http://b.yzcdn.cn/v2/vendor/zepto-1.1.3.js" onerror="_cdnFallback(this)"></script><script>
    function Dragon(){
        this.settings = {
            top:0,
            width:40,
            height:43
        }
    }
    Dragon.prototype.init = function(opts){
        var _this = this;
        opts = $.extend(this.settings, opts);
        this.create(opts);
    }
    Dragon.prototype.create = function(opts){
        var _this = this;
        this.elem = $('<div class="dragon_icon"/>');
        this.elem.css({
            'width': opts.width,
            'height': opts.height,
            'top':opts.top,
            'left':($(window).width()-opts.width)/2
        });
        $('body').append(this.elem)
        this.start(opts);
    }
    Dragon.prototype.start = function(opts){
        var self = this, 
            startTime = null,
            left_right = [1,-1],
            point = Math.round(Math.random()),
            ratio = parseInt(4*Math.random()),
            side = left_right[point],
            side_x = Math.random()*150+50,
            side_y = Math.random()*100+350,
            side_cir = Math.random()*100+160,
            height = $(window).height();
            
        function step(now){
            if(startTime === null){  startTime = now};
            var progress = now - startTime;
            var change_height = self.update(progress/1000,side,side_x,side_y,side_cir);
            self.rid = requestAnimationFrame(step,side,side_x,side_y,side_cir);
            if(change_height>height){
                self.clear(self.rid);
                self.destroy()
            }
        };
        self.rid = requestAnimationFrame(step,side,side_x,side_y,side_cir,height);   
    }
    Dragon.prototype.clear = function(){
        cancelAnimationFrame(this.rid);
    }
    Dragon.prototype.destroy = function(){
        this.elem.remove()
    }
    Dragon.prototype.update = function(t,side,side_x,side_y,side_cir){
        var x = side * side_x * t,
            y = -240 * t + side_y * t * t,
            cir = side * side_cir * t * t;
        var transformValue = 'translate3d('+ x +'px,'+ y +'px,0) rotate('+ cir +'deg)';
        this.elem.css({
            'transform': transformValue,
            '-webkit-transform':transformValue
        });
        return y;
    }
    window.requestAnimationFrame = window.requestAnimationFrame 
                            || window.mozRequestAnimationFrame 
                            || window.webkitRequestAnimationFrame 
                            || window.msRequestAnimationFrame;
    window.cancelAnimationFrame = window.cancelAnimationFrame 
                            || window.mozCancelAnimationFrame
                            || window.webkitCancelAnimationFrame
                            || window.msCancelAnimationFrame;
    var startTime = window.mozAnimationStartTime; 
    var startCoords = {}, endCoords = {};
    function page(event){
        var touch = event.touches[0]||event.changedTouches[0];
        var page = {
                'x':touch.pageX,
                'y':touch.pageY
            }
        return page
    }
    $(window).on('touchstart',function(ev){
           start_point = page(ev)['y'];
    });
    $(window).on('touchend', function(ev) {
        end_point = page(ev)['y'];

        if(start_point > end_point){
            setTimeout(function(){
                var obj = new Dragon();
                obj.init();
            }, 100);
               
        }else{
            return;
        }
    });
</script>
</body>
</html>
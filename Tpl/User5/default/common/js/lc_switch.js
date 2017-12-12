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
        autohidemode: true,//是否隐藏滚动条
    });
});
/* ios风格的左右按钮开关调用 */
$(document).ready(function (e) {
    $('input[type="checkbox"]').lc_switch();
    $('.lcs_switch').click(function(){
    	var $this = $(this);
    	var isstart = '';
    	if($(this).prev().is(':checked')) {
    		//如果打开进行关闭
    		isstart = '2';
    	}else{
    		//如果关闭进行打开
    		isstart = '1';
    	}
    	var action = $(this).parents('.js-toggle-group').attr('data-action');
    	if(action == 'other'){
    		if($('.lcs_switchs').is(':checked')) {
    			$('.lcs_switchs').prop("checked",""); 
        	}else{
        		$('.lcs_switchs').prop("checked","checked");
        	}
    		return true;
    		/*$.post("http://new.lightpen.cn/index.php?g=User5&m=Other&a=ajaxisopen&time="+Math.random(),
    				{isopen:isstart},
    				function(data){	    			 
    					if(data.code=='200'){
    		        		if($('.lcs_switchs').is(':checked')) {
    		        			$('.lcs_switchs').prop("checked",""); 
    		            	}else{
    		            		$('.lcs_switchs').prop("checked","checked");
    		            	}
    		        	}else{
    		        		alert(data.msg);
    		        	}
    			},"json");*/
    	}else{
    		host = window.location.host;
    		var id = $(this).parents('.isopen').attr('data-id');
        	var type = $(this).parents('.isopen').attr('data-type');
    		$.post("http://"+host+"/index.php?g=User5&m=TemplateMessage&a=ajaxisopen&time="+Math.random(),
    				{id:id,isopen:isstart,type:type},
    				function(data){	    			 
    					if(data.code=='200'){
    		        		if(isstart == '2') {
    		        			$this.prev().removeAttr("checked",""); 
    		            	}else{
    		            		$this.prev().attr("checked","checked");
    		            	}
    		        	}else{
    		        		alertTan(data.msg,'warn');
    		        	}
    			},"json");
    	}
    });
    	
});

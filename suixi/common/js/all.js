// JavaScript Document

$(function(){
	//¶þ¼¶²Ëµ¥
$('.nav_ct li:last').find('.second_menu').css({'left':'auto','right':'0'});	
$('.nav_ct li:last').prev().find('.second_menu').css({'left':'auto','right':'0'});	
$('.nav_ct li').hover(function(){
	
	var Alength=$(this).find('.second_menu').find('a').size();
	var Awidth=$(this).find('.second_menu').find('a').outerWidth();
	$('.second_p').delay(800).show();
	$(this).find('.second_menu').width(Awidth*Alength);
	$(this).find('a:first').css('color','#dc1313').children('i').css('color','#dc1313');
	$(this).find('.second_menu').show();
	},function(){
	$(this).find('a:first').css('color','#1a1a1a').children('i').css('color','#1a1a1a');
	$(this).find('.second_menu').hide();
	$('.second_p').hide();
	
	
	})
})


$(function(){
	//about usÇÐ»»
	$('.gw_ct3 dl').eq(0).show();
	$('.timeP span').hover(function(){
		$(this).addClass('activeSP').siblings('span').removeClass('activeSP');	
		$('.gw_ct3 dl').eq($('.timeP span').index(this)).show().siblings('dl').hide();	
	})

	
})

/*$(function(){
	//ÆÀ¼ÛÇÐ»»
	
	$('.py_box').each(function() {
        $(this).find('dl').eq(1).show();
    });
	$('.title_pj a').hover(function(){
		$(this).addClass('aon').siblings('a').removeClass('aon');	
		$('.py_box dl').eq($('.title_pj a').index(this)).show().siblings('dl').hide();	
	})

	
})*/


/*----------*/

$(function(){
	//µ¯´°
     var winHeight = $(window).height();
	var popHeight= $('.show_div').height();	
	$('.klw_ct2_cont li a').click(function(){
		$('.show_div').animate({ opacity: "show", top:(winHeight-popHeight)/2}, 300); 
		var imgsrc=$(this).find('img').attr('src')	;
		var showname=$(this).siblings('.nameP').text();
		$('.show_ct').find('img').attr("src",imgsrc);
		$('.show_ct').find('.show_name').text(showname);
		
		if($.browser.msie && parseInt($.browser.version) == 6){
			var sdf=$(document).scrollTop();
			var df=document.getElementById('show_div');
			df.style.marginTop=sdf+'px';
			}
		
	})
	
	$('.close').click(function(){
		$('.show_div').hide(200);	
	})

})











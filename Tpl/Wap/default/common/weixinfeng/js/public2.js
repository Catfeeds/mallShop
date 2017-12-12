$(document).ready(function() {
	// 全局页面进入loading
	function pageLoading() {

		setTimeout(function() {
			var index = layer.load(2, {shade: [0.1,'#fff']});
			setTimeout(function() {
				layer.close(index);
				$('.page').css({'opacity': '1'});
			},1000);
		},100);

	}pageLoading();

	// 点击关闭-新人见面礼
	$('.meeting-etiquette-box').on('click', '.close', function() {

		$('.meeting-etiquette-wrap').fadeOut();
		$('.meeting-etiquette-box').animate({'top': '-14rem'});

	});
	$('.address-inf').on('click', function() {

		$(this).find('b').addClass('selected');
		$(this).siblings().find('b').removeClass('selected');
		
	});


	$('.more-address').click(function() {

		$('.address-inf-box').css({
			'height': 'auto'
		})		
		$('.change-address').css({
			'margin-bottom': '2rem'
		})
		$('.add-address, .other-inf').fadeOut(0);
		$('.return-hide, .address-inf').fadeIn(0);
	})

	$('.return-hide').click(function() {

		$('.address-inf-box').css({
			'height': '2.35rem'
		})
		$('.change-address').css({
			'margin-bottom': '0'
		})
		$('.return-hide, .address-inf').fadeOut(0);
		$('.add-address, .other-inf').fadeIn(0);
		$('.selected').parent('.address-inf').fadeIn(0);

	})

	// 逐一选中按钮
	var changeN = 0;
	$('.payment-set-box .order-set').click(function() {

		if ($(this).find('b').hasClass('selected')) {

			changeN --
			$(this).find('b').removeClass('selected');

			if (changeN == 0) {
				
				$('.public-set').animate({
					'bottom': '-2.75rem'
				});

			};

		} else {

			changeN ++
			$(this).find('b').addClass('selected');
			$(this).parent('.payment-set-box').siblings();

			if (changeN > 0) {

				$('.public-set').animate({
					'bottom': '0'
				});

			};				
			
		}

	})

	// 下放的取消按钮
	$('.public-set .cancel-btn').click(function() {

		$('.payment-set-box .order-set b').removeClass('selected');
		$('.fr-set').find('b').removeClass('pitch-on');
		$('.public-set').animate({
			'bottom': '-2.75rem'
		});
		$('.payment-big-box').animate({
			'padding-bottom': '0rem'
		});

	})
	
	$('.choice-wrap .cancel-btn').click(function() {

		$('.payment-set-box .order-set b').removeClass('selected');
		$('.fr-set').find('b').removeClass('pitch-on');
		$('.public-set').animate({
			'bottom': '-2.75rem'
		});
		$('.payment-big-box').animate({
			'padding-bottom': '0rem'
		});

	})



	// 全选删除设置
	$('.fr-set').click(function() {

		$(this).find('b').addClass('pitch-on');
		$('.payment-set-box .order-set b').addClass('selected');
		$('.public-set').animate({
			'bottom': '0rem'
		});

	})

	// 点击存储保存设置
	/*$('.preservation-set').on('click','.preservation-btn',function() {

		var addressee = $('.addressee-input').val();
		var telephone = $('.telephone-input').val();
		var detailed  = $('.detailed-input').val();
		var address   = $('.address-input').val();
		var mobileReg  = /^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9]|17[0|1|2|3|5|6|7|8|9])\d{8}$/;

		var province = $('#province').find("option:selected").html();
		var city = $('#city').find("option:selected").html();
		var district = $('#district').find("option:selected").html();
		var town = $('#town').find("option:selected").html();

		if (addressee == "") {
			layer.msg('请填写收件人！', {time:1000});
			return
		}
		if (telephone == "") {
			layer.msg('您的手机不能为空！', {time:1000});
			return
		}
		if (!mobileReg.test(telephone)) {
			layer.msg('您的手机格式错误！<br/>请输入11位数移动电话号码', {time:1000});
			return
		}
		if (province == " - 请选择 - ") {
			layer.msg('请填所在省份', {time:1000});
			return
		}
		if (city == " - 请选择 - ") {
			layer.msg('请填所在城市', {time:1000});
			return
		}
		if (district == " - 请选择 - ") {
			layer.msg('请填所在市区或者街道', {time:1000});
			return
		}
		if (detailed == "") {
			layer.msg('请填写详细地址！', {time:1000});
			return
		}
		if (addressee !== "" && telephone !== "" && detailed !== "" && province !== " - 请选择 - "&& city !== " - 请选择 - "&& district !== " - 请选择 - ") {

			var index = layer.load(2, {shade: [0.1,'#fff']});
			setTimeout(function() {
				layer.close(index);
				layer.msg('提交成功！', {time:1000});
			},1000);


			// ajax后台
			// $.ajax({
			// 	url:'xxxxx&openid=' + openid,
			// 	type:'POST',
			// 	dataType: "json",
			// 	data: 'addressee=' + addressee + '&telephone=' + telephone + '&detailed=' + detailed + '&address=' + address + '&province=' + province + '&city=' + city + '&district=' + district,
			// 	success: function(json){
			// 		if(json.ret==1){

			// 		}else{

			// 		}
			// 	}
			// });



		};

	})*/

	// 点击取消则清空
	$('.preservation-set').on('click','.cancel-btn',function() {

		$('.addressee-input').val('');
		$('.telephone-input').val('');
		$('.detailed-input').val('');
		$('.address-input').val('');

	});

	var hideRule = true;
	$('.apply-rule').on('click','h2',function() {

		if (hideRule == true) {
			$('.apply-rule b').removeClass('ani-rotate');
			$('.apply-rule p').fadeOut();
			hideRule = false;
		} else {
			$('.apply-rule b').addClass('ani-rotate');
			$('.apply-rule p').fadeIn();
			hideRule = true;
		}
	});

	var hideCitys = true;
	$('.regional-choice').on('click','h2',function() {

		if (hideCitys == true) {
			$('.regional-choice b').removeClass('ani-rotate');
			$('.regional-choice .citys').fadeOut();
			hideCitys = false;
		} else {
			$('.regional-choice b').addClass('ani-rotate');
			$('.regional-choice .citys').fadeIn();
			hideCitys = true;
		}

	});


	// 点击勾起订单
	$('.all-order').on('click','.order-box',function() {
		var _this = $(this);
		var state = _this.find('.order-time b');

		// 判断开当前订单选项
		if (state.hasClass('selected')) {
			state.removeClass('selected');
		}else {
			state.addClass('selected');
		}

		// 确定是否开启体现功能
		if ($('.all-order .order-box .order-time b').hasClass('selected')) {
			$('.return-statistics .title-h1').find('a').addClass('reflect');
		} else {
			$('.return-statistics .title-h1').find('a').removeClass('reflect');
			$('.return-statistics .title-h1').find('b').removeClass('selected');
		}

	});

	// 点击全选订单
	var changeCheck = true;
	$('.return-statistics .title-h1').on('click','strong',function() {

		if (changeCheck == true) {
			$('.return-statistics .title-h1').find('a').addClass('reflect');
			$('.return-statistics .title-h1').find('b').addClass('selected');
			$('.all-order .order-time b').addClass('selected');
			changeCheck = false;
		} else {
			$('.return-statistics .title-h1').find('a').removeClass('reflect');
			$('.return-statistics .title-h1').find('b').removeClass('selected');
			$('.all-order .order-time b').removeClass('selected');
			changeCheck = true;
		}

	});

	


	$('.invoice-box').on('click','.personal',function() {

		$('.personal').find('b').addClass('choice');
		$('.company').find('b').removeClass('choice');

	});

	$('.invoice-box').on('click','.company',function() {

		$('.company').find('b').addClass('choice');
		$('.personal').find('b').removeClass('choice');

	});

	$('.invoice-information').on('click','.apply-set .preservation-btn',function() {

		var name = $('.choice').siblings('span').html();
		var company = $('.company-name').val();
		var identification = $('.identification-number').val();
		if (company == '') {
			layer.msg('公司抬头不能为空！', {time:1000});
			return
		};
		if (identification == '') {
			layer.msg('纳税人识别号不能为空！', {time:1000});
			return
		};
		if (company !== '' && identification !== '') {
			console.log(name);
			console.log(company);
			console.log(identification);
			var index = layer.load(2, {shade: [0.1,'#fff']});
			setTimeout(function() {
				layer.close(index);
				layer.msg('保存中...', {time:1000});
			},1000);
			// ajax后台
			// $.ajax({
			// 	url:'xxxxx&openid=' + openid,
			// 	type:'POST',
			// 	dataType: "json",
			// 	data: 'company=' + company + '&identification=' + identification + '&name=' + name,
			// 	success: function(json){
			// 		if(json.ret==1){
			// 		
			// 		}else{
			// 		
			// 		}
			// 	}
			// });
		}

	});

	$('.invoice-information').on('click','.apply-set .cancel-btn',function() {

		$('.invoice-box input').val('');

	});



});
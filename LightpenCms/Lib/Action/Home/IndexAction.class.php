<?php
class IndexAction extends HomeBaseAction{
	
	private $usersModel;
	
	private $companyModel;
	
	private $companyGroupModel;
	
	public function __construct(){
		parent::__construct();
		$this->usersModel = M('users');
		$this->companyModel = M('company');
	}
	
	//首页
	public function index(){
	    //dump($_SESSION);exit;
		$pageInfoAsa = array(
			'title'=>'人来风SCRM-社群生意，无限价值！',
			'keywords'=>'人来风，微信第三方，会员营销，会员制，微商城，微信商城，微信会员卡',
			'description'=>'人来风SCRM是国内领先的SCRM系统，为商户价值而生，为商户三大运营目标而生，一个有态度的温情品牌。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	
	public function missionComplete(){
		$pageInfoAsa = array(
				'title'=>'为价值而生-了解人来风SCRM5',
				'keywords'=>'人来风，粉丝数，会员数，销售额，运营绩效，移动营销',
				'description'=>'人来风SCRM是国内领先的SCRM系统，为商户价值而生，为商户三大运营目标而服务，管理商户的会员数据，帮助落地品牌个性化会员制一个有态度的温情品牌。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	public function CRM(){
		$pageInfoAsa = array(
				'title'=>'会员制CRM-了解人来风SCRM5',
				'keywords'=>'人来风，会员营销，会员管理，CRM活动',
				'description'=>'拉新成本越来越高，开发一个新客户，不如用人来风SCRM留住一个老客户，留下会员数据，利用CRM活动落地品牌个性化会员制。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	public function eshop(){
		$pageInfoAsa = array(
				'title'=>'Eshop微商城系统-了解人来风SCRM5',
				'keywords'=>'人来风，微信商城，微商城营销',
				'description'=>'人来风Eshop微商城系统是一线大牌电商背后的移动端商城系统，注重视觉细节，每秒稳定处理万笔订单，会员消费积分直通，内含多样独立活动插件，给你顾客都喜欢玩的微商城系统。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	public function O2O(){
		$pageInfoAsa = array(
				'title'=>'O2O业务场景-了解人来风SCRM5',
				'keywords'=>'微信会员，会员注册，会员营销，粉丝互动，微信抽奖',
				'description'=>'人来风帮助商户细分O2O业务场景，帮助商户抓住每一个极佳的引导会员注册口，数据直通SCRM，提供行业场景深度解决方案，留下会员数据，玩转会员制CRM。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	public function Session(){
		$pageInfoAsa = array(
				'title'=>'专场活动-了解人来风SCRM5',
				'keywords'=>'人来风，比佛利大道',
				'description'=>'比佛利大道是轻奢美食乐活电商平台，目前已入驻合作商户为北京、上海、广州、深圳及成都等8个城市的高端餐饮、玩乐、生活课程品牌。 人来风每月与比佛利大道开展专场合作活动，精选SCRM5优质入驻商户，为入驻商户引流。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	public function S5activity(){
		$pageInfoAsa = array(
				'title'=>'开放平台接入-了解人来风SCRM5',
				'keywords'=>'人来风，微信公众平台，微信小程序，今日头条，比佛利大道，支付宝服务窗',
				'description'=>'人来风打通第三方平台，将商户的品牌同移动平台受众直接相连，一站式管理你的移动平台，保证品牌的曝光展现。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	public function deploy(){
		$pageInfoAsa = array(
				'title'=>'部署服务-了解人来风SCRM5',
				'keywords'=>'人来风，微信部署，会员制，CRM活动',
				'description'=>'人来风SCRM完全为商户价值而生，携手商户完成0-1部署，落地O2O场景及会员制并监督效果，同时给出优化建议，一对一AE售后响应，帮助商户利用人来风SCRM真正做好生意。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	public function PurpleFriday(){
		$pageInfoAsa = array(
				'title'=>'紫色星期五-人来风',
				'keywords'=>'人来风，人来风SCRM，新品发布，新品预告，功能优化发布，功能优化预告',
				'description'=>'人来风紫色星期五每周发布，新品发布、新品预告、功能优化发布、功能优化预告，每一次更新都为了给商户真正做好生意的功能产品，杜绝给商户添麻烦的功能。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	public function joincase(){
		$pageInfoAsa = array(
				'title'=>'入驻案例-人来风',
				'keywords'=>'人来风，行业升级，消费升级',
				'description'=>'5个国家，23个城市，超过12000家优质入驻商户正在使用人来风SCRM实现实体业务转型适应移动互联网环境的消费产业升级。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	public function AboutUs(){
		$pageInfoAsa = array(
				'title'=>'关于人来风-人来风',
				'keywords'=>'人来风，微信CRM，人来风SCRM，移动营销',
				'description'=>'人来风SCRM面向餐饮、零售、电商、商业地产、公共事业等行业的一线品牌企业提供SCRM客户关系管理平台软件、移动营销增值服务等全方位的互联网+O2O行业解决方案，现已帮助企业管理超过8000万会员，大大改善了消费环境，帮助广大品牌以最低成本，成熟的方案，实现实体业务转型适应移动互联网环境的产业升级。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	public function cooperation(){
		$pageInfoAsa = array(
				'title'=>'合作联系',
				'keywords'=>'人来风，人来风SCRM',
				'description'=>'立刻联系人来风，拥有国内最好的SCRM'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//比弗利大道
	public function beverly(){
		$pageInfoAsa = array(
				'title'=>'比佛利大道-轻奢美食乐活电商',
				'keywords'=>'比佛利大道，品质生活，高端餐饮，网红美食',
				'description'=>'比佛利大道的核心用户为对生活品质有极高追求的白领人群及较高消费能力的高端人群。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//比弗利入驻申请
	public function Settledapplication(){
		$pageInfoAsa = array(
				'title'=>'品牌入驻申请-比佛利大道-高端人群的电商平台',
				'keywords'=>'比佛利大道，高端品牌，高端消费者',
				'description'=>'申请入驻比佛利大道，比佛利大道目前已入驻商户为北京、上海、广州、深圳及成都等8个城市的高端餐饮、玩乐、生活课程品牌。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//续费购买
	public function renew(){
		$pageInfoAsa = array(
				'title'=>'续费购买-人来风',
				'keywords'=>'人来风，会员营销，微信活动，增值业务',
				'description'=>'人来风SCRM配套服务、配套硬件，更好帮助商户落地O2O场景、部署会员制 ，提升销售业绩的利器都在这里了。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//立即购买人来风
	public function SCRM5Buy(){
		session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);
		$pageInfoAsa = array(
				'title'=>'立即购买-人来风',
				'keywords'=>'人来风，人来风SCRM，会员营销',
				'description'=>'人来风SCRM购买及续费，立刻获得专属AE一对一全程部署服务及O2O行业解决方案贴身指导落地'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	
	//拉卡拉POS机
	public function lakala(){
		$pageInfoAsa = array(
				'title'=>'人来风拉卡拉POS定制机-人来风',
				'keywords'=>'人来风，移动pos机，拉卡拉移动pos',
				'description'=>'人来风拉卡拉POS定制机，门店刷卡消费积分直达顾客账户，'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//拉卡拉POS机
	public function lakalaBuy(){
		$pageInfoAsa = array(
				'title'=>'立即购买-人来风拉卡拉POS定制机-人来风',
				'keywords'=>'人来风，移动pos机，拉卡拉移动pos',
				'description'=>'立即购买人来风拉卡拉POS定制机，'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//微信WiFi路由器
	public function wechatwifi(){
		$pageInfoAsa = array(
				'title'=>'微信WIFI路由器-人来风',
				'keywords'=>'人来风，微信连wifi，一键上网',
				'description'=>'人来风微信连Wi-Fi路由器能够让顾客选择商户的Wi-Fi上网即可关注微信公众号，是商户的门店吸粉神器，适用于大型商业综合体、中小型商户、网吧、旅游业等行业积累粉丝。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//微信WiFi路由器购买
	public function wechatwifiBuy(){
		session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);
		$pageInfoAsa = array(
				'title'=>'立即购买-微信WIFI路由器-人来风',
				'keywords'=>'人来风，微信连wifi，一键上网',
				'description'=>'立即购买人来风微信连Wi-Fi路由器，顾客一键点击即可上网并关注微信公众号，适用于中小型商户、大型综合商业体、网吧、旅游等行业。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//摇摇魔石介绍页---未SEO
	public function spellStone(){
		$pageInfoAsa = array(
			'title'=>'摇摇魔石-人来风',
			'keywords'=>'人来风，微信摇一摇，摇一摇周边，ibeacon设备',
			'description'=>'摇摇魔石是由人来风基于微信摇一摇周边独立研发并生产的ibeacon硬件设备，商户部署摇摇魔石后可以引导顾客用微信摇一摇摇出红包、优惠、关注二维码、H5活动页面等预设内容，增强互动性。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//摇摇魔石购买页---未SEO
	public function spellStoneBuy(){
		session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);
		$pageInfoAsa = array(
				'title'=>'立即购买-摇摇魔石-人来风',
				'keywords'=>'人来风，微信摇一摇，摇一摇周边，ibeacon设备',
				'description'=>'立即购买人来风摇摇魔石，基于微信摇一摇周边功能拉动微信粉丝至线下门店消费，快速聚集人气，引导客流消费。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//小票打印机购买页
	public function invoicePrinterBuy(){
		session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);
		$pageInfoAsa = array(
				'title'=>'立即购买-无线小票打印机-人来风',
				'keywords'=>'人来风，小票打印机',
				'description'=>'立即购买人来风无线小票打印机，快速打印SCRM平台交易单据，提升门店工作效率。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//SMS短信购买
	public function SMSBuy(){
		session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);
		$pageInfoAsa = array(
				'title'=>'立即充值-SMS短信通道-人来风',
				'keywords'=>'人来风，营销短信，通知短信',
				'description'=>'人来风SMS短信自助充值，助力商户发送营销及通知短信给会员。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//720全景
	public function view720(){
		$pageInfoAsa = array(
				'title'=>'720°全景-人来风',
				'keywords'=>'人来风，全景拍摄，全景购物',
				'description'=>'人来风720°全景拍摄服务，包含上门拍摄及后期制作服务，移动时代的品牌形象展示全新方式，能够实现线上全景购物。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//720全景购买
	public function Buy720(){
		session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);
		$pageInfoAsa = array(
				'title'=>'立即购买-720°全景-人来风',
				'keywords'=>'人来风，全景拍摄',
				'description'=>'购买人来风720°全景拍摄服务，包含上门拍摄及后期制作全部服务，给自己全新的品牌展示形象。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//现场互动墙
	public function interactiveWall(){
		$pageInfoAsa = array(
				'title'=>'现场互动墙-人来风',
				'keywords'=>'人来风，现场互动墙，现场互动',
				'description'=>'人来风现场互动墙，参与者可以通过个人微信参与互动，整个环节都将显示在现场大屏幕上，可参与留言墙、对对碰速配、摇一摇赛跑、抽奖及投票五大环节'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//现场互动墙购买
	public function interactiveWallBuy(){
		session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);
		$pageInfoAsa = array(
				'title'=>'立即购买-现场互动墙-人来风',
				'keywords'=>'人来风，现场互动墙',
				'description'=>'购买人来风现场互动墙，AE免费上门培训，'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//商户登陆
	public function login(){
		if(session('uid')){
			$this->redirect(U('Index/index'));
		}
		$pageInfoAsa = array(
			'title'=>'商户登录-人来风',
			'keywords'=>'人来风，SCRM，人来风SCRM，微信CRM',
			'description'=>'登录人来风SCRM，快去看看今天业绩增加了多少。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//立即开通－国内领先的SCRM系统
	public function register(){
		$pageInfoAsa = array(
				'title'=>'立即开通-人来风',
				'keywords'=>'人来风，微信第三方，会员营销',
				'description'=>'立即开通人来风SCRM，社群生意，无限价值。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//商户积分商城
	public function integral(){
		$pageInfoAsa = array(
				'title'=>'人来风SCRM - 商户积分商城',
				'keywords'=>'人来风，mobiwind,mobiwind.cn,SCRM,SCRM5，业绩奖励，KPI奖励，业绩目标',
				'description'=>'人来风SCRM商户积分商城，赶紧用获得的积分来兑换心仪的奖品吧～'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//微信代运营服务
	public function activity(){
		$pageInfoAsa = array(
				'title'=>'微信代运营服务-人来风',
				'keywords'=>'人来风，微信代运营，微信粉丝增长，会员制部署',
				'description'=>'人来风微信代运营服务，为商户价值而生，以微信粉丝、会员数、销售额的增长为目标，帮助商户完成账号、会员制及CRM活动的搭建，全程带领商户'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//H5微信活动定制
	public function H5WechatActivity(){
		$pageInfoAsa = array(
				'title'=>'H5微信活动定制-人来风',
				'keywords'=>'人来风，H5微信活动，H5活动定制',
				'description'=>'人来风H5微信活动定制，定制火爆朋友圈的大流量活动，包含活动策划、开发、前期预热、活动上线及活动总结，一大波粉丝涌向你'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//系统开发定制
	public function systemCustomization(){
		$pageInfoAsa = array(
				'title'=>'系统开发定制-人来风',
				'keywords'=>'人来风，CRM系统定制开发，电商系统定制，H5页面定制，O2O场景定制',
				'description'=>'人来风系统开发定制，专业一线互联网产品狗为您服务，配置一线大型互联网产品开发工作流程。'
		);
		$this->setPageSeo($pageInfoAsa);
		$this->display();
	}
	//合伙人申请
	public function CooperationAgent(){
		if(IS_POST){
			$data = $_POST;
			$data['id'] = guidNow();
			$data['createtime'] = $data['updatetime'] = time();
			$res = M("check_copartner")->add($data);
			if($res){
				$ajax['code'] = 200;
				$ajax['msg'] = "提交成功";
				//FG yep
				//$sendReturn = $this->sendSms('13918086001,15026482623', '有一条新的合伙人提交信息，请尽快前往check后台查看,公司'.$data['companyname'].'手机号'.$data['mobile'],'1186','【人来风】','','181818');
				$sendReturn = $this->sendSms('13918086001,15026482623', '有新的合伙人申请,赶紧联系吧,姓名：'.$data['name'].'；公司名：'.$data['companyname'].'；手机号：'.$data['mobile'],'1186','【人来风】','','181818');
			}else{
				$ajax['code'] = 200;
				$ajax['msg'] = "系统繁忙";
			}
			echo json_encode($ajax);
		}else{
			$pageInfoAsa = array(
					'title'=>'合伙人简介-人来风',
					'keywords'=>'人来风，CRM系统定制开发，电商系统定制，H5页面定制，O2O场景定制',
					'description'=>'人来风合伙人，共同为帮助人来风入驻商户创造更大价值'
			);
			$this->setPageSeo($pageInfoAsa);
			$this->display();
		}
		
	}
	//开票管理
	public function requestInvoice(){
		if(IS_POST){
			$_POST['createtime'] = $_POST['updatetime'] = time();
			$_POST['id'] = guidNow();
			$_POST['companyid'] = session("cid");
			$_POST['username'] = session("uname");
			$res = M("check_invoice")->add($_POST);
			if($res){
				//dump($_POST);exit;
				if(preg_match("/^1[34578]{1}\d{9}$/",$_POST['stel'])){
					$sendReturn = $this->sendSms('13818652568,13564012907,18616250318,13918086001', session("cname")." 已申请开具发票，请登录CHECK后台查看。",'1186','【人来风】','','181818');
					if($sendReturn['code'] =='300'){
						$sendReturn = $this->sendSms('13564012907', "申请开票有条短信发送失败".session("cname"),'1186','【人来风】','','181818');
					}
				}
				//dump($sendReturn);
				$ajax['code']='200';
				$ajax['msg']='开票申请提交成功<br />我们将尽快核对并处理<br />如需查询进度请联系您的AE';
				
			}else{
				$ajax['code']='300';
				$ajax['msg']='系统繁忙，请稍后重试';
			}
			echo json_encode($ajax);
		}else{
			$pageInfoAsa = array(
					'title'=>'开票申请',
					'keywords'=>'人来风，微信第三方，人来风SCRM',
					'description'=>'人来风SCRM开票申请'
			);
			$this->setPageSeo($pageInfoAsa);
			session("historyUrl",U("Index/requestInvoice"));
			$this->display();
		}
	}
	//登录
	public function login_scrm5(){
		if(IS_POST){
			$usersInfoWhere['username'] = $this->_post('username');
			$userInfo = $this->usersModel->where($usersInfoWhere)->find();
			if($userInfo){
				$password=get_md5_password($this->_post('password'));
				if($password===$userInfo['password']){
						session('cid',$userInfo['companyid']);
						session('uid',$userInfo['id']);
						session('uname',$userInfo['username']);
						session('truename',$userInfo['truename']);
						//记住密码
						$username = $this->_post('username');
						$password = $this->_post('password');
						$rememberPassword = $this->_post('rememberPassword') ? $this->_post('rememberPassword') : 0 ;
						if($rememberPassword == 1){
							cookie('username',$username,time()+360000000);
							cookie('password',$password,time()+360000000);
							cookie('rememberPassword',$rememberPassword,time()+360000000);
						}
						if(!is_dir('./Uploads/'.$userInfo['companyid'].'/image')){
							check_dir('./Uploads/'.$userInfo['companyid'].'/image');//创建文件夹
						}
						session(null);
						session('uid',$userInfo['id']);
						$userInfo['shopsid'] = $userInfo['shopsid'] > 0 ? $userInfo['shopsid'] : '0';
						session('shopsid',$userInfo['shopsid']);
						session('uname',$userInfo['username']);
						session('truename',$userInfo['truename']);
						session('phone',$userInfo['phone']);
						session('cid',$userInfo['companyid']);
						$saveCompanyDate['lasttime'] = time();
						$saveCompanyDate['lastip'] = get_client_ip(0);
						if(format_time(time(),'d') == '01'){
							$saveCompanyDate['nowrequestsnum'] = 0;
						}
						$resuser = M("users")->where(array('id'=>$userInfo['id']))->setInc('loginnum');
						$ajax['code']=200;
						$ajax['msg']="登录成功，正在跳转。。。";
				}else{
					$ajax['code']=300;
					$ajax['msg']="账号密码错误";
				}
			}else{
				$ajax['code']=300;
				$ajax['msg']="账号不存在";
			}
			
			echo json_encode($ajax);
		}else{
			if(session('uid')){
				$this->redirect(U('Index/index'));
			}
			$this->display();
		}
	}
	/**
	 * SCRM5的注册
	 * @author Asa<asa@renlaifeng.cn>
	 * @since  2016-8-24
	 */
	public function register_scrm5(){
		if(IS_POST){
			$count = M("users")->where(array("username"=>$this->_post('loginname')))->count();
			if($this->_post('invitecode') != ''){
				$where['invitecode'] = $this->_post('invitecode');
				$invitecode = M("agent_invitation")->where($where)->find();
				if($invitecode){
					$a = 2;
				}else{
					$a = 1;
				}
			}else{
				$a = 2;
			}
			if($a == 1){
				$ajaxReturn['code'] = '600';
				$ajaxReturn['tips'] = '请填写正确的邀请码';
			}else if($count){
				$ajaxReturn['code'] = '100';
				$ajaxReturn['tips'] = '注册的登陆用户名重复';
			}else{
				$data['brandname']=$_POST['companyname'];
				$data['companyname']=$_POST['companyname'];
				$data['trade'] = $this->_post('industry');
				$data['updatetime'] = $data['createtime'] = time();
				$res1 = M("check_customer_info")->add($data);
				
				//$viptime = time()+604800;//注册一周试用期
				//$data2['viptime'] = $viptime;
				$data2['companyid'] = $res1;
				$data2['name']=$_POST['companyname'];
				$data2['tel']=$_POST['phone'];
				$data2['status']=4;
				$data2['gid']=8;
				$data2['updatetime'] = $data2['createtime'] = time();
				$res2 = M("company")->add($data2);
				
				//风助手储值设置
				$helper['id'] = guidNow();
				$helper['companyid'] = $res2;
				$helper['updatetime'] = $helper['createtime'] = time();
				M('storedvalue_helper_set')->add($helper);
				
				$data3['companyid'] = $res1;
				$data3['cid'] = $res2;
				$data3['loginname']=$_POST['loginname'];
				$data3['loginpwd']=$_POST['password'];
				$data3['tel']=$_POST['phone'];
				$data3['updatetime'] = $data3['createtime'] = time();
				$res3 = M("check_number_config")->add($data3);
				
				$data4['companyid'] = $res2;
				$data4['numid'] = $res3;
				$data4['isboss'] = 1;
				$data4['username'] = $this->_request("loginname");
				$data4['truename'] = $this->_request("name");
				$data4['truePassword'] = $this->_request("password");
				$data4['password'] = md5($this->_request("password"));
				$data4['phone'] = $this->_request("phone");
				$res4 = M("users")->add($data4);
				
	
				$data5['id'] = guidNow();
				$data5['companyid'] = $res1;
				$data5['name'] = $this->_post('name');
				$data5['phone'] = $this->_post('phone');
				$data5['companyname'] = $this->_post('companyname');
				$data5['industry'] = $this->_post('industry');
				$data5['loginname'] = $this->_post('loginname');
				$data5['password'] = $this->_post('password');
				if($this->_post('invitecode') != ''){
					$data5['invitecode'] = $this->_post('invitecode');
				}
				$data5['status'] = 1;
				$data5['agenttype'] = '1';
				$data5['updatetime'] = $data5['createtime'] = time();
				$agent = M('agent')->add($data5);
				if($agent&&$res4&&$res3&&$res2&&$res1){
					
					session('cid',$res2);
					session('uid',$res4);
					session('uname',$data4['username']);
					session('gid',8);
					session('truename',$data4['truename']);
					session('cname',$data2['name']);
					session('logourl','');
					session('maximgspace',100);
					
					$ajaxReturn['code'] = '200';
					$ajaxReturn['tips'] = '注册申请提交成功';
					
					$sendFailNum = 0;
					if($data5['phone']){ //FG,cry,ryann,lan,stella,Yep
						$sendReturn = $this->sendSms('13918086001,18571729950,18616250318,13564012907,13818652568,15026482623', '新线索:'.$data5['name'].','.$data5['companyname'].','.$data5['phone'].','.$data5['loginname'].','.$data5['invitecode'],'1186','【人来风】','','181818');
						if($sendReturn['code'] =='200'){
							$agentSave = M('agent')->where(array('id'=>$data5['id']))->save(array('isnoticed'=>'2','noticedtime'=>time()));
							if(!$agentSave){
								//$log .= '任务失败  SQL：'.M()->getLastSql()."\n";
							}
						}else{
							$sendLog =json_encode($sendReturn);
							$sendFailNum++;
						}
						if($sendFailNum>0){
							$this->sendSms('13564012907', '共有'.$sendFailNum.'条新线索通知没能成功发送,请尽快核查'.$sendLog,'1186','【人来风】','','181818');
						}
					}
					//通论坛的地方
					require './LightpenCms/Lib/ORG/UcApi.Class.php';
					$_POST['email'] = strtolower(guidNow()).'@mail.net';// 这里的邮箱信息是必填项Ucenter无法设置非必填，目前规则是通过username来设置邮箱信息保证唯一性
					$reg = UcApi::reg($_POST['loginname'], $_POST['password'], $_POST['email']);
					if ($reg <= 0) {
						$data6['id'] = guidNow();
						$data6['username'] = $this->_post("loginname");
						$data6['type'] = 3;
						$data6['data'] = "子账号添加通论坛失败,返回值".$reg.'账号：'.$_POST['loginname'].'邮箱：'.$_POST['email'];
						$data6['time'] = date("Y-m-d H:i:s",time());
						$data6['createtime'] = time();
						$res = M("users_log")->add($data6);
					}else{
						$ajaxReturn['ok'] = '账号通成功';
					}
				}else{
					$ajaxReturn['code'] = '300';
					$ajaxReturn['tips'] = '提交失败';
				}
			}
			echo json_encode($ajaxReturn);
		}
	}
	//退出
	public function logOut(){
		session(null);
		// 同步退出
		require './LightpenCms/Lib/ORG/UcApi.Class.php';
		$ucsynlogout = UcApi::logout();
		echo $ucsynlogout;
		echo "<script type='text/javascript'>";
		echo "function logOut() {";
		echo "window.location.href='".U('Index/index')."'";//要跳转的页面
		echo "}";
		echo "window.setTimeout('logOut()',1)";
		echo "</script>";
	}
	/**
	 * 论坛通账号的方法
	 */
	public function userInterlink(){
		$data = json_decode(file_get_contents('php://input'),TRUE);
		if($data){
			$where['username'] = $data['username'];
			$userInfo = M("users")->where($where)->find();
			$companyInfo = M("company")->where(array('id'=>$userInfo['companyid']))->find();
			$companyGroupInfo = M("company_group")->where(array('id'=>$companyInfo['gid']))->field('name')->find();
			if($companyInfo['status']==0){
				$ajax['msg'] = "您的试用申请正在审核中，请耐心等待。";
			}elseif ($companyInfo['status']==2){
				$ajax['msg'] = "抱歉！您的试用申请未被通过，如有疑问请联系您的客户经理。";
			}elseif ($companyInfo['status']==4){
				$ajax['code'] = 500;
				session(null);
				$_SESSION['uid'] = $userInfo['id'];
				$_SESSION['uname'] = $userInfo['username'];
				$_SESSION['gid'] = $companyInfo['gid'];
				$_SESSION['truename'] = $userInfo['truename'];
				$_SESSION['cname'] = $companyInfo['name'];
				$_SESSION['logourl'] = $companyInfo['logourl'];
				$_SESSION['maximgspace'] = $companyInfo['maximgspace'];
				$ajax['msg'] = "请先填写入住流程。";
				$ajax['companyid'] = session('cid');
			}else{
				if ($companyInfo['isclose']==1){
					$ajax['msg'] = "您的账号已被冻结，请联系您的客户经理。";
				}else{
					if($companyInfo['viptime']< time() && $companyInfo['gid'] != 5){
						$ajax['code']=300;
						$ajax['msg'] = "抱歉！您的账号已到期。续费请联系您的客户经理，系统将于一周内自动删除您的历史数据。";
					}else{
						//记住密码
						$username = $this->_post('username');
						$password = $this->_post('password');
						$rememberPassword = $this->_post('rememberPassword') ? $this->_post('rememberPassword') : 0 ;
						if($rememberPassword == 1){
							cookie('username',$username,time()+360000000);
							cookie('password',$password,time()+360000000);
							cookie('rememberPassword',$rememberPassword,time()+360000000);
						}
						if(!is_dir('./Uploads/'.$userInfo['companyid'].'/image')){
							check_dir('./Uploads/'.$userInfo['companyid'].'/image');//创建文件夹
						}
						$companyGroupInfo = $this->companyGroupModel->where(array('id'=>$companyInfo['gid']))->field('name')->find();
						if (empty($companyGroupInfo)){
							//$this->error(L('ServerBusyPrompt'));
						}
						session(null);
						$_SESSION['uid'] = $userInfo['id'];
						$userInfo['shopsid'] = $userInfo['shopsid'] > 0 ? $userInfo['shopsid'] : '0';
						$_SESSION['shopsid'] = $userInfo['shopsid'];
						$_SESSION['uname'] = $userInfo['username'];
						$_SESSION['truename'] = $userInfo['truename'];
						$_SESSION['phone'] = $userInfo['phone'];
						$_SESSION['cid'] = $userInfo['companyid'];
						$_SESSION['cname'] = $companyInfo['name'];
						$_SESSION['viptime'] = $companyInfo['viptime'];
						$_SESSION['logourl'] = $companyInfo['logourl'];
						$_SESSION['companyPermissions'] = explode(',', $companyInfo['permissions']);
						$_SESSION['companyS5Permissions'] = explode(',', $companyInfo['scrm5permissions']);
						if($userInfo['isboss'] == 1){
							$_SESSION['permissions'] = explode(',', $companyInfo['permissions']);
							$_SESSION['S5permissions'] = explode(',', $companyInfo['scrm5permissions']);
						}else{
							$_SESSION['permissions'] = explode(',', $userInfo['permissions']);
							$_SESSION['S5permissions'] = explode(',', $userInfo['scrm5permissions']);
						}
						$_SESSION['maximgspace'] = $companyInfo['maximgspace'];
						$_SESSION['gid'] = $companyInfo['gid'];
						$_SESSION['gname'] = $companyGroupInfo['name'];
						$_SESSION['wechatfollowlink'] = $companyInfo['wechatfollowlink'];
						$saveCompanyDate['lasttime'] = time();
						$saveCompanyDate['lastip'] = get_client_ip(0);
						if(format_time(time(),'d') == '01'){
							$saveCompanyDate['nowrequestsnum'] = 0;
						}
						$this->usersModel->where(array('id'=>$userInfo['id']))->save();
						//$this->success(L('LoginSuccessful'),U('User/System/systemInfo'));
						$ajax['code']=200;
						$ajax['msg']="登录成功，正在跳转。。。";
						//这是积分商城需要的session
						$_SESSION['MBWapUserId'] = $userInfo['id'];
						$_SESSION['MBWapUserName'] = $userInfo['username'];
					}
				}
			}
		}else{
		    $ajax['msg'] = "不存在DARA";
		}
		echo json_encode($_SESSION);
	}
	/**
	 * 通账号的SQL
	 *
	 */
	public function userUpdatePW(){
	    $data = json_decode(file_get_contents('php://input'),TRUE);
	    $data6['id'] = guidNow();
	    $data6['username'] = $data['username']?$data['username']:'aa';
	    $data6['type'] = 3;
	    $data6['data'] = json_encode($data);
	    $data6['time'] = date("Y-m-d H:i:s",time());
	    $data6['createtime'] = time();
	    $res = M("users_log")->add($data6);
	}
	/**
	 * 通账号的SQL
	 * 
	 */
	public function userInterlinkSql(){
		//通论坛的SQL
		require './LightpenCms/Lib/ORG/UcApi.Class.php';
		$a = 0;$b = ''; $c = 0;
		$userInfo = M("users")->where(array('username'=>array('neq','')))->select();
		foreach( $userInfo as $key => $val){
			
			$data = '';
			$data['username'] = $val['username'];
			$data['password'] = $val['truePassword'];
			$data['email'] = strtolower($data['username']).'@mail.net';
			$reg = UcApi::reg($data['username'], $data['password'], $data['email']);
			if($reg=='-1'||$reg=='-4'){
				$b .='UserName: '.$data['username']+' ID: '.$val['id'].'<br />';
			    $data6['id'] = guidNow();
			    $data6['username'] = $data['username'];
			    $data6['type'] = 3;
			    $data6['data'] = 'MID：'.$val['id'].';账号：'.$data['username'].';错误代码：'.$reg;
			    $data6['time'] = date("Y-m-d H:i:s",time());
			    $data6['createtime'] = time();
			    $res = M("users_log")->add($data6);
			}else{
				$c ++;
			}
			$a++;
		}
		echo '共'.$a.'条，<br />成功：'.$c.'条';
		echo '失败详情：<br />'.$b;
	}
	/**
	 * 预约业务
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-9-27
	 */
	public function bookBusiness(){
		if(IS_POST){
			$name = $this->_post('name');
			$mobile = $this->_post('mobile');
			$companyname = $this->_post('companyname');
			$industry = $this->_post('industry');
			$time = time();
			$serialNumber = M('check_book_business')->where(array('createtime'=>array('between',array($time,$time+1))))->count();
			$bookData['orderid'] = orderID(2,'N', 1, $serialNumber+1);
			$bookData['businesstype'] = $this->_post('businesstype');
			$bookData['companyid'] = 1;
			$bookData['name'] = $name;
			$bookData['mobile'] = $mobile;
			$bookData['companyname'] = $companyname;
			$bookData['industry'] = $industry;
			$bookData['orderstatus'] = 1;
			$bookData['createtime'] = $bookData['updatetime'] = time();
			$result = M('check_book_business')->add($bookData);
			if($result){
				//$sendReturn = $this->sendSms('13918086001,15026482623,13564012907', '新线索:'.$data5['name'].','.$data5['companyname'].','.$data5['phone'].','.$data5['loginname'].','.$data5['invitecode'],'1186','【人来风】','','181818');
				$content = '新增值业务预约：'.$name.'，'.$mobile.'，公司名称：'.$companyname.'，所属行业：'.$industry;
				$this->sendSms('13918086001,18571729950,18616250318,13564012907', $content,'1186','【人来风】','','181818');
				$ajaxReturn['code'] = 200;
			}else{
				$ajaxReturn['code'] = 300;
			}
			echo json_encode($ajaxReturn);
		}
	}
}
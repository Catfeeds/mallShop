<?php
class IndexAction extends HomeBaseAction{
	
	private $usersModel;
	
	private $companyModel;
	
	private $companyGroupModel;
	
	private $logModel;
	
	public function __construct(){ 
		parent::__construct();
		$this->usersModel = M('users');
		$this->companyModel = M('company');
		$this->companyGroupModel = M('company_group');
		$this->logModel = M('login_log');
		$this->companyid = $this->_get('companyid');
	}
	//人来风首页
	public function index(){
		$newNotice =M()->table('tp_hcms_article as ha')->join('left join tp_hcms_class as hc on ha.classid = hc.id')->order('ha.sort')->limit(6)->where(array('hc.type'=>'2'))->field('ha.id,ha.title,ha.updatetime')->select();
		$this->assign('newNotice',$newNotice);
		$this->assign('title','首页');
		$this->display();
	}
	//人来风首页(静态)
	public function index_quiet(){
		$newNotice =M()->table('tp_hcms_article as ha')->join('left join tp_hcms_class as hc on ha.classid = hc.id')->order('ha.sort')->limit(6)->where(array('hc.type'=>'2'))->field('ha.id,ha.title,ha.updatetime')->select();
		$this->assign('newNotice',$newNotice);
		$this->assign('title','首页');
		$this->display();
	}
	//登录
	public function sign(){
		if(IS_POST){  // 如果点击登录提交
			$num = $_SESSION['lognum'];
			$data['loginip'] = get_real_ip();
			$data['createtime'] = time();
			$usersInfoWhere['username'] = $this->_post('username');
			$pass = $this->_post('password');
			if(empty($usersInfoWhere['username'])){
				$ajaxReturn['tips'] = $data['errortype'] = '100';  //未输入密码
				$num+=1;
				session('lognum',$num);
				$errorLog = $this->logModel->add($data);
			}else{
				if(empty($pass)){
					$ajaxReturn['tips'] = $data['errortype'] = '3';  //未输入密码
					$num+=1;
					session('lognum',$num);
					$errorLog = $this->logModel->add($data);
				}else{
					if(strstr($usersInfoWhere['username'],'@') == false){
						$userInfo = $this->usersModel->where($usersInfoWhere)->find();
						$username = $userInfo['username'];
					}else{
						$userInfo = $this->usersModel->where(array('email'=>$usersInfoWhere['username']))->find();
						$username = $userInfo['email'];
					}
					if(empty($userInfo)){
						$ajaxReturn['tips'] = $data['errortype'] = '1';  //用户不存在
						$num+=1;
						session('lognum',$num);
						$errorLog = $this->logModel->add($data);
					}else{
						$password = get_md5_password($this->_post('password'));
						$userInfo['password'];
						if($password != $userInfo['password']){
							$ajaxReturn['tips'] = $data['errortype'] = '2';  //密码不正确
							$num+=1;
							session('lognum',$num);
							$errorLog = $this->logModel->add($data);
						}
						if($password===$userInfo['password']){
							if($num > 2){
								$logVerify = strtolower($this->_post('verifys'));
								$logVerifys = session('loginCode');
								if(empty($logVerify)){
									$ajaxReturn['tips'] = $data['errortype'] = '4';  //未输入验证码
									$num+=1;
									session('lognum',$num);
									$errorLog = $this->logModel->add($data);
								}else if($logVerify != $logVerifys){
									$ajaxReturn['tips'] = $data['errortype'] = '5';  //验证码不正确
									$num+=1;
									session('lognum',$num);
									$errorLog = $this->logModel->add($data);
								}else{
									$companyInfo = $this->companyModel->where(array('id'=>$userInfo['companyid']))->find();
									if (empty($companyInfo)){
										$this->error(L('ServerBusyPrompt'));
									}
									if($companyInfo['viptime']< time() && $companyInfo['gid'] != 5){
										$this->error('抱歉！您的账号已到期。续费请联系您的客户经理，系统将于一周内自动删除您的历史数据。');
									}
									if($companyInfo['status']==0){
										$this->error('您的试用申请正在审核中，请耐心等待。');
									}elseif ($companyInfo['status']==2){
										$this->error('抱歉！您的试用申请未被通过，如有疑问请联系您的客户经理。');
									}
									if ($companyInfo['isclose']==1){
										$this->error('您的账号已被冻结，请联系您的客户经理。');
									}
									if(!is_dir('./Uploads/'.$userInfo['companyid'].'/image')){
										check_dir('./Uploads/'.$userInfo['companyid'].'/image'); //创建文件夹
									}
									$companyGroupInfo = $this->companyGroupModel->where(array('id'=>$companyInfo['gid']))->field('name')->find();
									if (empty($companyGroupInfo)){
										$this->error(L('ServerBusyPrompt'));
									}
									session(null);
									session('uid',$userInfo['id']);
									$userInfo['shopsid'] = $userInfo['shopsid'] > 0 ? $userInfo['shopsid'] : '0';
									session('shopsid',$userInfo['shopsid']);
									session('uname',$username);
									session('truename',$userInfo['truename']);
									session('email',$userInfo['email']);
									session('cid',$userInfo['companyid']);
									session('cname',$companyInfo['name']);
									session('viptime',$companyInfo['viptime']);
									session('logourl',$companyInfo['logourl']);
									session('companyPermissions',explode(',', $companyInfo['permissions']));
									if($userInfo['isboss'] == 1){
										session('permissions',explode(',', $companyInfo['permissions']));
									}else{
										session('permissions',explode(',', $userInfo['permissions']));
									}
									session('maximgspace',$companyInfo['maximgspace']);
									session('gid',$companyInfo['gid']);
									session('gname',$companyGroupInfo['name']);
									session('wechatfollowlink',$companyInfo['wechatfollowlink']);
									$saveCompanyDate['lasttime'] = time();
									$saveCompanyDate['lastip'] = get_client_ip(0);
									if(format_time(time(),'d') == '01'){
										$saveCompanyDate['nowrequestsnum'] = 0;
									}
									$ajaxReturn['tips'] = $data['errortype'] = '0';  //登录成功
									$errorLog = $this->logModel->add($data);
								}
	
							}else{
								$companyInfo = $this->companyModel->where(array('id'=>$userInfo['companyid']))->find();
								if (empty($companyInfo)){
									$this->error(L('ServerBusyPrompt'));
								}
								if($companyInfo['viptime']< time() && $companyInfo['gid'] != 5){
									$this->error('抱歉！您的账号已到期。续费请联系您的客户经理，系统将于一周内自动删除您的历史数据。');
								}
								if($companyInfo['status']==0){
									$this->error('您的试用申请正在审核中，请耐心等待。');
								}elseif ($companyInfo['status']==2){
									$this->error('抱歉！您的试用申请未被通过，如有疑问请联系您的客户经理。');
								}
								if ($companyInfo['isclose']==1){
									$this->error('您的账号已被冻结，请联系您的客户经理。');
								}
								if(!is_dir('./Uploads/'.$userInfo['companyid'].'/image')){
									check_dir('./Uploads/'.$userInfo['companyid'].'/image'); //创建文件夹
								}
								$companyGroupInfo = $this->companyGroupModel->where(array('id'=>$companyInfo['gid']))->field('name')->find();
								if (empty($companyGroupInfo)){
									$this->error(L('ServerBusyPrompt'));
								}
								session(null);
								session('uid',$userInfo['id']);
								$userInfo['shopsid'] = $userInfo['shopsid'] > 0 ? $userInfo['shopsid'] : '0';
								session('shopsid',$userInfo['shopsid']);
								session('uname',$username);
								session('truename',$userInfo['truename']);
								session('email',$userInfo['email']);
								session('cid',$userInfo['companyid']);
								session('cname',$companyInfo['name']);
								session('viptime',$companyInfo['viptime']);
								session('logourl',$companyInfo['logourl']);
								session('companyPermissions',explode(',', $companyInfo['permissions']));
								if($userInfo['isboss'] == 1){
									session('permissions',explode(',', $companyInfo['permissions']));
								}else{
									session('permissions',explode(',', $userInfo['permissions']));
								}
								session('maximgspace',$companyInfo['maximgspace']);
								session('gid',$companyInfo['gid']);
								session('gname',$companyGroupInfo['name']);
								session('wechatfollowlink',$companyInfo['wechatfollowlink']);
								$saveCompanyDate['lasttime'] = time();
								$saveCompanyDate['lastip'] = get_client_ip(0);
								if(format_time(time(),'d') == '01'){
									$saveCompanyDate['nowrequestsnum'] = 0;
								}
								$ajaxReturn['tips'] = $data['errortype'] = '0';  //登录成功
								$errorLog = $this->logModel->add($data);
								//$this->usersModel->where(array('id'=>$userInfo['id']))->save();
							}
						}
					}
				}
			}
			$ajaxReturn['status'] = $num;
			echo json_encode($ajaxReturn);
		}else{
			//1:加二维码扫描;2检测是否生成验证码
			$data['loginip'] = get_real_ip();
			$loginNum = $this->logModel->where(array('loginip'=>$data['loginip'],'errortype'=>array('neq','0'),'createtime'=>array('gt',strtotime('-1 day'))))->count();
			session('login',null);
			session('lognum',$loginNum);
			$this->assign('status',$loginNum);
			$this->assign('title','登录');
			$this->display();
		}
	}
	//验证码
	public function verify(){
		require 'LightpenCms/Lib/ORG/ValidateCode.class.php';
		$_vc = new ValidateCode();      //实例化一个对象
		$_vc->doimg();
		session('loginCode',$_vc->getCode());
	}
	/**
	 * 
	 * 微信扫码登录或注册获取openid
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2015-10-10
	 */
	public function wechatLogin(){
		$type = $_GET['type'];
		$typeUrl = '&type='.$type;
	    $code = $_GET['code'];
	    $wechatI = array('appid'=>'wx238ab97d12b1ff3c','appsecret'=>'adda52ad48417d1c99e3a721634e9a4e');
	    $authname = 'login_wechat_access_token'.$wechatI['appid'];
	    	if($code){
		    	$REDIRECT_URI = urlencode(C('site_url').'/index.php?m=Index&a=wechatLogin'.$typeUrl);
		        $STATE = md5(time());
		        $wechatH1 = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$wechatI['appid'].'&secret='.$wechatI['appsecret'].'&code='.$code.'&grant_type=authorization_code';
		        $result = http_get($wechatH1);
		        $json = json_decode($result,true);
		        if ($json['openid'] && !isset($json['errcode'])) {
		            $openid = $json['openid'];
		            $userInfo = M('users')->where(array('loginopenid'=>$openid))->find();
		            if($userInfo){
		            	if(empty($userInfo['username'])){
		            		$username = $userInfo['email'];
		            	}else{
		            		$username = $userInfo['username'];
		            	}
		              	$companyInfo = M('company')->where(array('id'=>$userInfo['companyid']))->find();
		              	if (empty($companyInfo)){
		                	$this->error(L('ServerBusyPrompt'));
		              	}
		              	if($companyInfo['viptime']< time() && $companyInfo['gid'] != 5){
		                	$this->error('抱歉！您的账号已到期。续费请联系您的客户经理，系统将于一周内自动删除您的历史数据。');
		              	}
		              	if($companyInfo['status']==0){
		                	$this->error('您的试用申请正在审核中，请耐心等待。');
		              	}elseif ($companyInfo['status']==2){
		                	$this->error('抱歉！您的试用申请未被通过，如有疑问请联系您的客户经理。');
		              	}
		              	if ($companyInfo['isclose']==1){
		                	$this->error('您的账号已被冻结，请联系您的客户经理。');
		              	}
		              	if(!is_dir('./Uploads/'.$userInfo['companyid'].'/image')){
		                	check_dir('./Uploads/'.$userInfo['companyid'].'/image');//创建文件夹
		              	}
		              	$companyGroupInfo = M('company_group')->where(array('id'=>$companyInfo['gid']))->field('name')->find();
		              	if (empty($companyGroupInfo)){
		                	$this->error(L('ServerBusyPrompt'));
		              	}
		              	session(null);
		              	session('uid',$userInfo['id']);
		              	$userInfo['shopsid'] = $userInfo['shopsid'] > 0 ? $userInfo['shopsid'] : '0';
		              	session('shopsid',$userInfo['shopsid']);
		              	session('uname',$username);
		              	session('truename',$userInfo['truename']);
		              	session('email',$userInfo['email']);
		              	session('cid',$userInfo['companyid']);
			            session('cname',$companyInfo['name']);
			            session('viptime',$companyInfo['viptime']);
		              	session('logourl',$companyInfo['logourl']);
		              	session('companyPermissions',explode(',', $companyInfo['permissions']));
		              	if($userInfo['isboss'] == 1){
		                	session('permissions',explode(',', $companyInfo['permissions']));
		              	}else{
		                	session('permissions',explode(',', $userInfo['permissions']));
		              	}
		              	session('maximgspace',$companyInfo['maximgspace']);
		              	session('gid',$companyInfo['gid']);
		              	session('gname',$companyGroupInfo['name']);
		              	session('wechatfollowlink',$companyInfo['wechatfollowlink']);
		              	$saveCompanyDate['lasttime'] = time();
		              	$saveCompanyDate['lastip'] = get_client_ip(0);
		              	if(format_time(time(),'d') == '01'){
		                	$saveCompanyDate['nowrequestsnum'] = 0;
		              	}
		              	$companyR = M('company')->where(array('id'=>$userInfo['companyid']))->save($saveCompanyDate);
		              	if($companyR){
		                	$this->success(L('LoginSuccessful'),U('User/System/systemInfo'));
		              	}else{
			                //跳往注册页面
			                $openid = base64_encode($openid);//加密openid
			                if($type == 'login'){
			                    $this->redirect(U('Index/sign'));
			                }else{
			                    $this->redirect(U('Index/register',array('oid'=>base64_encode($openid),'access'=>base64_encode($json['access_token']),'type'=>'register')));
			                }
			            }
	            	}else{
	            		if($type == 'login'){
	            			$this->redirect(U('Index/register',array('oid'=>base64_encode($openid),'access'=>base64_encode($json['access_token']),'type'=>'login')));
	            		}else{
	            			$this->redirect(U('Index/register',array('oid'=>base64_encode($openid),'access'=>base64_encode($json['access_token']),'type'=>'register')));
	            		}
	            	}
				}else{
			    	$this->redirect(U('Index/sign'));
				}
	      	}else{
	      		if($wechatI) {
			        $REDIRECT_URI = urlencode(C('site_url').'/index.php?m=Index&a=wechatLogin'.$typeUrl);
			        $STATE = md5(time());
			        $wechatH = 'https://open.weixin.qq.com/connect/qrconnect?appid='.$wechatI['appid'].'&redirect_uri='.$REDIRECT_URI.'&response_type=code&scope=snsapi_login&state='.$STATE.'#wechat_redirect';
			        redirect($wechatH);        
	       		}
	      	} 
    	}

	/**
	 *
	 * 注册
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2015-10-12
	 */
	public function register(){
		if($this->_get('oid')){
			if($this->_get('type') == 'login'){
				$status = '0';   //弹框
			}else{
				$this->redirect(U('Index/emailRegister',array('access'=>$this->_get('access'),'oid'=>$this->_get('oid'))));
			}
			$this->assign('status',$status);
			$this->assign('oid',$this->_get('oid'));
			$this->assign('access',$this->_get('access'));
			$this->display();
		}else{
			$this->display();
		}
	}
	public function emailRegister(){
		if(IS_POST){
			$to = $this->_post('email');
			$oid = $this->_post('openid');
			$nickname = $this->_post('nickname');
			$headimg = $this->_post('headimg');
			$users = $this->usersModel->where(array('email'=>$to))->find();
			if($users){
				$ajaxReturn['status'] = '0';   //ajax该邮箱已注册
			}else{
				$data['code']= get_order_id();
				$data['createtime'] = time();
				$data['email'] = $to;
				$addemail = M('send_email_code_log')->add($data);
				$var = array("to" => array($to),"sub" => array("%register_url%" => Array(C('site_url').'/index.php?m=Index&a=registerOk&code='.base64_encode($data['code']).'&email='.base64_encode($to).'&oid='.$oid.'&nickname='.base64_encode($nickname).'&headimg='.base64_encode($headimg))));   //模板替换变量
				$template = 'renlaifeng_register';  //注册激活模板
				$send = $this->sendEmail($var,$template);
				if (strpos($data['email'],'@') > 0){    //检测是否存在@字符
					$emailArr = explode('@', $data['email']);
					if(count($emailArr) > 2){    //检测是否存在多个@字符
						$ajaxReturn['email_url'] = '';
					}else{
						$mail_suffix = array(
										'@hotmail.com' => 'https://login.live.com/login.srf',
										'@msn.com' => 'https://login.live.com/login.srf',
										'@yahoo.com' => 'https://login.yahoo.com/config/mail',
										'@gmail.com' => 'https://mail.google.com',
										'@aim.com' => 'https://my.screenname.aol.com/_cqr/login/login.psp',
										'@aol.com' => 'https://my.screenname.aol.com/_cqr/login/login.psp',
										'@mail.com' => 'http://www.mail.com/premiumlogin/',
										'@inbox.com' => 'https://www.inbox.com/',
										'@126.com' => 'http://126.com/',
										'@163.com' => 'http://mail.163.com/',
										'@sina.com' => 'https://login.sina.com.cn/signup/signin.php',
										'@21cn.com' => 'http://mail.21cn.com/w2/',
										'@sohu.com' => 'https://passport.sohu.com/user/tologin',
										'@yahoo.com.cn' => 'https://login.yahoo.com/config/mail',
										'@tom.com' => 'http://pass.tom.com/login.php',
										'@qq.com' => 'https://mail.qq.com/cgi-bin/loginpage',
										'@eyou.com' => 'http://eyou.com/',
										'@56.com' => 'http://www.56.com/',
										'@chinaren.com' => 'https://passport.sohu.com/user/tologin',
										'@sogou.com' => 'http://mail.sogou.com/',
								);
						$emailkey = '@'.$emailArr[1];
						$ajaxReturn['email_url'] = $mail_suffix[$emailkey];
						if($ajaxReturn['email_url'] == ''){
							$ajaxReturn['email_url'] = '';
						}
					}
				}else{
					$ajaxReturn['email_url'] = '';
				}
				$ajaxReturn['status'] = '1';   //ajax显示邮件查收页面
				$ajaxReturn['e_name'] = $data['email'];
			}
			echo json_encode($ajaxReturn);
		}else{
			if($this->_get('oid')){
				$wechat = new Wechat();
				$wechatInfo = $wechat->getOauthUserinfo(base64_decode($this->_get('access')),base64_decode($this->_get('oid')));
				if($wechatInfo){
					$this->assign('oid',$this->_get('oid'));
					$this->assign('access',$this->_get('access'));
					$this->assign('wechatInfo',$wechatInfo);
					$this->display();
				}
			}else{
				$this->redirect(U('Index/register'));
			}
		}
	}

//判断手机重复
	public function tellJudge(){
		if($this->_post('v_code')){
			$v_code = $this->_post('v_code');
			$tell = $this->_post('tell');
			$registerCount = M('member_mobile_code')->where(array('mobile'=>$tell,'isused'=>2))->field('code,useendtime')->find();  //验证存在
			if($registerCount){
				if(time() > $registerCount['useendtime']){
					$ajaxReturn['codes'] = '300';   //失败 重新获取
				}else{
					if($v_code == $registerCount['code']){
						$ajaxReturn['codes'] = '100';   //成功
					}else{
						$ajaxReturn['codes'] = '200';   //失败
					}
				}
			}else{
				$ajaxReturn['codes'] = '300';   //失败 重新获取
			}
		}
		if($this->_post('tell')){
			$tell = $this->_post('tell');
			$userTell = $this->usersModel->where(array('phone'=>$tell))->find();
			if(empty($userTell)){
				$ajaxReturn['count'] = '1';    //手机号可以使用
			}else{
				$ajaxReturn['count'] = '0';   //手机号重复
			}
		}else{
			$ajaxReturn['count'] = '2';
		}
		echo json_encode($ajaxReturn);
	}
	//手机验证码
	public function tellCode(){
		$tell = $this->_post('tell');
        if($tell){
        	$registerCount = M('member_mobile_code')->where(array('mobile'=>$tell, 'isused'=>2))->count();
        	if($registerCount){  // 有未使用的手机号
        		$registerCount1 = M('member_mobile_code')->where(array('mobile'=>$tell, 'isused'=>2))->find();
        		if($registerCount1['useendtime'] >= time()){  // 未过期
        			$sendData = $this->sendSms($tell,$registerCount1['code'].'（注册验证码，请完成验证），如非本人操作，请忽略本短信。',$this->companyid);
        			if($sendData['code'] == 200){
        				$data['code'] = '200';
        				$data['tips'] ='验证码短信已发送到你的手机上，有效时间为30分钟。';
        			}else{
        				$data['code'] = '300';
        				$data['tips'] = '验证码发送失败，请重新发送';
        			}
        		}else{ // 已过期
        			$da1['isused'] = '1';
        			$list = M('member_mobile_code')->where(array('moblie'=>$tell))->save($da1);
        			if($list) {
        				$code['mobile'] = $tell;
        				$code['code'] = substr(time(),-6);  // 用时间生成验证码（当前时间戳的前4位）
        				$code['useendtime'] = time()+1800;  // 过期时间
        				$code['isused'] = '2';
        				$code['createtime'] = time();
        				$insterData1 = M('member_mobile_code')->add($code);
        				if ($insterData1){
        					$sendData = $this->sendSms($tell,$code['code'].'（注册验证码，请完成验证），如非本人操作，请忽略本短信。',$this->companyid);
        					if($sendData['code'] == 200){
        						$data['code'] = '200';
        						$data['tips'] ='验证码短信已发送到你的手机上，有效时间为30分钟。';
        					}
        				}
        			}
        		}
        	}else{ // 没有未使用的验证码
        		$code['mobile'] = $tell;
        		$code['code'] = substr(time(),-6);  // 用时间生成验证码（当前时间戳的前4位）
        		$code['useendtime'] = time()+1800;  // 过期时间
        		$code['isused'] = '2';
        		$code['createtime'] = time();
        		$insterData2 = M('member_mobile_code')->add($code);
        		$data['code'] = '200';
        		if($insterData2){
        			//发送
        			$sendData = $this->sendSms($tell,$code['code'].'（注册验证码，请完成验证），如非本人操作，请忽略本短信。',$this->companyid);
        			if($sendData['code'] == 200){
        				$data['code'] = '200';
        				$data['tips'] ='验证码短信已发送到你的手机上，有效时间为30分钟。';
        			}
        		}
        	}
        }else{
            $data['code'] = '300';
            $data['tips'] = '验证码发送错误，请重新发送';
        }
        echo json_encode($data);
	}
	
	//注册成功
	public function registerOk(){
		if(IS_POST){
			$model = new Model();
			$model->startTrans();//开启事务
			$companyGroupInfo = $this->companyGroupModel->where(array('id'=>1))->field('id,permissions,maximgspace,maxrequestsnum')->find();
			if (empty($companyGroupInfo)){
				$this->error(L('ServerBusyPrompt'),U('Index/register'));
			}
			$viptime = time()+604800;//注册一周试用期
			$companyInfoData['viptime'] = $viptime;
			$companyInfoData['name'] = $this->_post('com_name');
			$companyInfoData['permissions'] = $companyGroupInfo['permissions'];
			$companyInfoData['gid'] = $companyGroupInfo['id'];
			$companyInfoData['maximgspace'] = $companyGroupInfo['maximgspace'];
			$companyInfoData['maxrequestsnum'] = $companyGroupInfo['maxrequestsnum'];
			$companyInfoData['wechatnum'] = 1;
			$companyInfoData['workernum'] = 0;
			$companyInfoData['shopsnum'] = 1;
			$companyInfoData['status'] = 1;
			$companyInfoData['isclose'] = 0;
			$companyInfoData['updatetime'] = $companyInfoData['createtime'] = time();
			$companyInfoInsterReturn = $this->companyModel->add($companyInfoData);
			$_POST['companyid'] = $companyInfoInsterReturn;
			$_POST['email'] = $this->_post('email');
			$_POST['loginopenid'] = base64_decode($this->_post('oid'));
			$_POST['loginnickname'] = base64_decode($this->_post('nickname'));
			$_POST['loginheadimg'] = base64_decode($this->_post('headimg'));
			$_POST['password'] = get_md5_password($this->_post('password'));
			$_POST['truePassword'] = $this->_post('truepassword','trim');
			$_POST['truename'] = $this->_post('truename');
			$_POST['phone'] = $this->_post('phone');
			$_POST['companyName'] = $this->_post('com_name');
			$_POST['companyBusiness'] = $this->_post('com_bus');
			$_POST['updatetime'] = $_POST['createtime'] = time();
			$_POST['createip'] = get_client_ip(0);
			$_POST['isboss'] = 1;
			$usersInsterReturn = $this->usersModel->add($_POST);
			$data['time'] = format_time(time(),'ymd');
			$mallHomeData['companyid'] = $companyInfoInsterReturn;
			$mallHomeData['tplid'] = 1;
			$mallHomeData['updatetime'] = $mallHomeData['createtime'] = time();
			$mallHomeReturn = M('mall_home')->add($mallHomeData);
			
			if($companyInfoInsterReturn && $usersInsterReturn && $mallHomeReturn){
				$model->commit();//事务提交
				check_dir('./Uploads/'.$companyInfoInsterReturn.'/image');//创建文件夹
				$userInfo = M('users')->where(array('email'=>$_POST['email']))->find();
				$companyInfo = $this->companyModel->where(array('id'=>$userInfo['companyid']))->find();
				if (empty($companyInfo)){
					$this->error(L('ServerBusyPrompt'));
				}
	
				if($companyInfo['viptime']< time() && $companyInfo['gid'] != 5){
					$this->error('抱歉！您的账号已到期。续费请联系您的客户经理，系统将于一周内自动删除您的历史数据。');
				}
				if($companyInfo['status']==0){
					$this->error('您的试用申请正在审核中，请耐心等待。');
				}elseif ($companyInfo['status']==2){
					$this->error('抱歉！您的试用申请未被通过，如有疑问请联系您的客户经理。');
				}
				if ($companyInfo['isclose']==1){
					$this->error('您的账号已被冻结，请联系您的客户经理。');
				}
				if(!is_dir('./Uploads/'.$userInfo['companyid'].'/image')){
					check_dir('./Uploads/'.$userInfo['companyid'].'/image'); //创建文件夹
				}
				$companyGroupInfo = $this->companyGroupModel->where(array('id'=>$companyInfo['gid']))->field('name')->find();
				if (empty($companyGroupInfo)){
					$this->error(L('ServerBusyPrompt'));
				}
				session(null);
				session('uid',$userInfo['id']);
				$userInfo['shopsid'] = $userInfo['shopsid'] > 0 ? $userInfo['shopsid'] : '0';
				session('shopsid',$userInfo['shopsid']);
				session('uname',$userInfo['email']);
				session('truename',$userInfo['truename']);
				session('email',$userInfo['email']);
				session('cid',$userInfo['companyid']);
				session('cname',$companyInfo['name']);
				session('viptime',$companyInfo['viptime']);
				session('logourl',$companyInfo['logourl']);
				session('companyPermissions',explode(',', $companyInfo['permissions']));
				if($userInfo['isboss'] == 1){
					session('permissions',explode(',', $companyInfo['permissions']));
				}else{
					session('permissions',explode(',', $userInfo['permissions']));
				} 
				session('maximgspace',$companyInfo['maximgspace']);
				session('gid',$companyInfo['gid']);
				session('gname',$companyGroupInfo['name']);
				session('wechatfollowlink',$companyInfo['wechatfollowlink']);
				$saveCompanyDate['lasttime'] = time();
				$saveCompanyDate['lastip'] = get_client_ip(0);
				if(format_time(time(),'d') == '01'){
					$saveCompanyDate['nowrequestsnum'] = 0;
				}
				$this->usersModel->where(array('id'=>$userInfo['id']))->save();
				$companyR = M('company')->where(array('id'=>$userInfo['companyid']))->save($saveCompanyDate);
				$data['status'] = '1';
			}else{
				$model->rollback();
				$data['status'] = '0';   //失败
			}
			echo json_encode($data);
		}else{
			if(IS_GET){
				$email = base64_decode($this->_get('email'));
				$emailInfo = $this->usersModel->where(array('email'=>$email))->find();
				if($emailInfo){
					$this->error('该邮箱已经注册请登录',U('Index/sign'));
				}else{
					$code = base64_decode($this->_get('code'));
					$emailVer = M('send_email_code_log')->where(array('code'=>$code))->field('createtime')->find();
					if($emailVer){
						if($emailVer['createtime'] < strtotime('-1 day')){
							$this->error('验证已过期，请重新注册',U('Index/register'));
						}else{
							$this->assign('oid',$this->_get('oid'));
							$this->assign('nickname',$this->_get('nickname'));
							$this->assign('headimg',$this->_get('headimg'));
							$this->assign('email',$email);
							$this->display();
						}
					}else{
						$this->error('验证已过期，请重新注册',U('Index/register'));
					}
				}
			}else{
				$this->error('验证已过期，请重新注册',U('Index/register'));
			}
			
		}
	}


	//找回密码
	public function findPassword(){
		if(IS_POST){
			$tell = $this->_post('tell');
			$tellInfo = M('users')->where(array('phone'=>$tell))->field('truePassword,username,email')->find();
			if($tellInfo){
				$data['status'] = '1';   //正确
				if($tellInfo['username'] == ''){
					$user = $tellInfo['email'];
				}else{
					$user = $tellInfo['username'];
				}
				$sendData = $this->sendSms($tell,'尊敬的'.$user.',您的登录密码为：'.$tellInfo['truePassword'].'请勿泄露，如非本人操作，请忽略本短信。',$this->companyid);
			}else{
				$data['status'] = '0';   //未注册
			}
		echo json_encode($data);
		}else{
			$this->display();
		}
	}
	public function logOut(){
		session(null);
		$this->success('退出成功',U('Index/index'));
	}

	//案例
	public function cases(){
		if($this->_get('type')){
			$id = M('hcms_class')->where(array('parentid'=>$this->_get('type'),'type'=>'3'))->getField('id',true);
			$idAll = implode(',', $id).','.$this->_get('type');
			$noticePage = M()->table('tp_hcms_article as ha')->join('left join tp_hcms_class as hc on ha.classid = hc.id')->order('ha.sort')->where(array('hc.type'=>'3','ha.classid'=>array('in',$idAll)))->count();
			$Page=new Page($noticePage,12);
			$newCase = M()->table('tp_hcms_article as ha')->join('left join tp_hcms_class as hc on ha.classid = hc.id')->order('ha.sort')->limit($Page->firstRow.','.$Page->listRows)->where(array('hc.type'=>'3','ha.classid'=>array('in',$idAll)))->field('ha.id,ha.title,ha.img1,ha.updatetime')->select();
			$this->assign('type',$this->_get('type'));
		}else{
			$noticePage = M()->table('tp_hcms_article as ha')->join('left join tp_hcms_class as hc on ha.classid = hc.id')->order('ha.sort')->where(array('hc.type'=>'3'))->count();
			$Page=new Page($noticePage,12);
			$newCase = M()->table('tp_hcms_article as ha')->join('left join tp_hcms_class as hc on ha.classid = hc.id')->order('ha.sort')->limit($Page->firstRow.','.$Page->listRows)->where(array('hc.type'=>'3'))->field('ha.id,ha.title,ha.img1,ha.updatetime')->select();
		}
		$caseClass = M('hcms_class')->order('classsort')->where(array('type'=>'3','parentid'=>'0'))->field('id,name')->select();
		$this->assign('i','0');
		$this->assign('caseClass',$caseClass);
		$this->assign('newCase',$newCase);
		$this->assign('page',$Page->show());
		$this->assign('title','案例');
		$this->display();
	}
	//公告
	public function notice(){
		$data = $this->makeTopUrl(array(array('name'=>'公告','url'=>U('Index/notice'),'rel'=>'','target'=>'')));
		if($this->_get('type') || $this->_get('stick')){
			if($this->_get('type')){
				$id = M('hcms_class')->where(array('parentid'=>$this->_get('type'),'type'=>'2'))->getField('id',true);
				$idAll = implode(',', $id).','.$this->_get('type');
				$where['ha.classid'] = array('in',$idAll);
			}
			if($this->_get('stick')){
				$where['ha.isstick'] = $this->_get('stick');
			}
			$this->assign('stick',$this->_get('stick'));
			$where['hc.type'] = '2';
			$noticePage = M()->table('tp_hcms_article as ha')->join('left join tp_hcms_class as hc on ha.classid = hc.id')->order('ha.sort')->where($where)->field('ha.id,ha.title,ha.isstick,ha.updatetime')->count();
			$Page=new Page($noticePage,15);
			$noticeCase = M()->table('tp_hcms_article as ha')->join('left join tp_hcms_class as hc on ha.classid = hc.id')->order('ha.sort')->limit($Page->firstRow.','.$Page->listRows)->where($where)->field('ha.id,ha.title,ha.isstick,ha.updatetime')->select();
			$this->assign('type',$this->_get('type'));
		}else{
			$noticePage = M()->table('tp_hcms_article as ha')->join('left join tp_hcms_class as hc on ha.classid = hc.id')->order('ha.sort')->where(array('hc.type'=>'2'))->field('ha.id,ha.title,ha.isstick,ha.updatetime')->count();
			$Page=new Page($noticePage,15);
			$noticeCase = M()->table('tp_hcms_article as ha')->join('left join tp_hcms_class as hc on ha.classid = hc.id')->order('ha.sort')->limit($Page->firstRow.','.$Page->listRows)->where(array('hc.type'=>'2'))->field('ha.id,ha.title,ha.isstick,ha.updatetime')->select();
		}
		$noticeClass = M('hcms_class')->order('classsort')->where(array('type'=>'2','parentid'=>'0'))->field('id,name')->select();
		$this->assign('i','0');
		$this->assign('page',$Page->show());
		$this->assign('data',$data);
		$this->assign('noticeCase',$noticeCase);
		$this->assign('noticeClass',$noticeClass);
		$this->assign('title','公告');
		$this->display();
	}
	//案例详情
	public function casedetails(){
		if($this->_get('id')){
			$newCase = M()->table('tp_hcms_article as ha')->join('left join tp_hcms_class as hc on ha.classid = hc.id')->order('ha.sort')->where(array('hc.type'=>'3'))->field('ha.id,ha.img1')->select();
			$caseInfo = M('hcms_article')->where(array('id'=>$this->_get('id')))->field('id,classid,title,img2,img3,content')->find();
			$classid = M('hcms_class')->where(array('id'=>$caseInfo['classid']))->field('id,name,parentid')->find();
			if($classid['parentid'] != '0'){
				$classid = M('hcms_class')->where(array('id'=>$classid['parentid']))->field('id,name,parentid')->find();
			}
			$caseInfo['classname'] = $classid['name'];
			$caseInfo['cid'] = $classid['id'];
			if($caseInfo && $newCase){
				$data = $this->makeTopUrl(array(array('name'=>'案例','url'=>U('Index/cases'),'rel'=>'','target'=>''),array('name'=>$caseInfo['title'],'url'=>U('Index/case'),'rel'=>'','target'=>'')));
				$this->assign('data',$data);
				$this->assign('caseInfo',$caseInfo);
				$this->assign('newCase',$newCase);
				$this->assign('title','案例详情');
				$this->display();
			}else{
				$this->error(L('链接已失效'));
			}
		}else{
			$this->error(L('链接已失效'));
		}
	}
	//公告详情
	public function noticedetails(){
		if($this->_get('id')){
			$noticeInfo = M('hcms_article')->where(array('id'=>$this->_get('id')))->field('id,title,author,isstick,content,updatetime')->find();
			//上一篇
			$front = M('hcms_article')->where(array('id'=>array('lt',$this->_get('id'))))->order('id desc')->limit('1')->getField('id');
			$this->assign('front',$front);
			//下一篇
			$after = M('hcms_article')->where(array('id'=>array('gt',$this->_get('id'))))->order('id desc')->limit('1')->getField('id');
			$data = $this->makeTopUrl(array(array('name'=>'公告','url'=>U('Index/notice'),'rel'=>'','target'=>''),array('name'=>$noticeInfo['title'],'url'=>U('Index/notice'),'rel'=>'','target'=>'')));
			$this->assign('data',$data);
			$this->assign('after',$after);
			$this->assign('front',$front);
			$this->assign('noticeInfo',$noticeInfo);
			$this->assign('title','公告详情');
			$this->display();
		}else{
			$this->error(L('链接已失效'));
		}
	}
	//关于人来风
	public function aboutus(){
		$this->assign('title','关于人来风');
		$this->display();
	}
	//法律声明
	public function lawstatement(){
		$data = $this->makeTopUrl(array(array('name'=>'法律声明','url'=>U('Index/lawstatement'),'rel'=>'','target'=>'')));
		$this->assign('data',$data);
		$this->assign('title','法律声明');
		$this->display();
	}
	//摇摇魔石
	public function yymoshi(){
		$this->assign('title','摇摇魔石');
		$this->display();
	}
	//DMS
	public function dms(){
		if(IS_POST){
			$agent = M('agent')->add($_POST);
			if($agent){
				$ajax['code'] = '200';
			}else{
				$ajax['code'] = '300';
			}
			echo json_encode($ajax);
		}else{
			$this->assign('title','DMS分销之星');
			$this->display();
		}
	}
	//现场互动墙
	public function hdq(){
		$this->assign('title','现场互动墙');
		$this->display();
	}
	//手机收款
	public function phonesk(){
		$this->assign('title','手机收款');
		$this->display();
	}
	//联系我们
	public function callus(){
		$this->assign('title','联系我们');
		$this->display();
	}
	//紧急扩军
	public function mlakj(){
		$this->assign('title','紧急扩军');
		$this->display();
	}
	//帮助中心
	public function helpcenter(){
		if($this->_get('id')){
			$newCase = M('hcms_article')->where(array('type'=>'1','id'=>$this->_get('id')))->field('id,title,content')->find();
			$this->assign('type',$this->_get('type'));
		}
		$helpClass = M('hcms_class')->order('classsort')->where(array('type'=>'1','parentid'=>'0'))->field('id,name')->select();
		foreach($helpClass as $key=>$val){
			$helpTitle = M('hcms_article')->order('sort')->where(array('type'=>'1','classid'=>$val['id']))->field('id,title')->select();
			$helpClass[$key]['son'] = $helpTitle;
		}
		$this->assign('newCase',$newCase);
		$this->assign('helpClass',$helpClass);
		$this->assign('title','帮助中心');
		$this->display();
	}
	//支付方式
	public function payment(){
		$helpClass = M('hcms_class')->order('classsort')->where(array('type'=>'1','parentid'=>'0'))->field('id,name')->select();
		foreach($helpClass as $key=>$val){
			$helpTitle = M('hcms_article')->order('sort')->where(array('type'=>'1','classid'=>$val['id']))->field('id,title')->select();
			$helpClass[$key]['son'] = $helpTitle;
		}
		$this->assign('type','pay');
		$this->assign('helpClass',$helpClass);
		$this->assign('title','支付方式');
		$this->display();
	}
	/**
	 * 生成面包屑Url
	 *
	 * @param array  $now_path 生成面包屑URL
	 * @return null
	 */
	public function makeTopUrl($now_path = array()){
		$return_url = '<a href="'.U('Index/index').'" title="人来风首页">首页</a><em>&gt;</em>';
		if (isset($now_path)){
			foreach ($now_path as $npKey=>$npVal){
				if ($npKey==count($now_path)-1){
					$return_url .= "<span>".$npVal['name']."</span>";
				}else{
					$return_url .= "<a target='".$npVal['target']."'  href='".$npVal['url']."' title='".$npVal['name']."'>".$npVal['name']."</a><em>&gt;</em>";
				}
			}
		}
		return $return_url;
	}
}
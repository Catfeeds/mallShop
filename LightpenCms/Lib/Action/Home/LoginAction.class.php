<?php
/**
 * 
 * 新版登录
 * 
 * @author    Lando<806728685@qq.com>
 * @since     2015-8-6
 * @version   1.0
 */
class LoginAction extends BaseAction{
    public function index(){
        $this->display();
    }
    /**
     * 
     * 登录页面
     * 
     * @author Lando<806728685@qq.com>
     * @since  2015-8-13
     */
    public function login(){
      if (IS_POST) {
            $returnData['msg'] = '';
            $returnData['code'] = '300';
            $usersInfoWhere['username'] = $this->_post('username');
            $openid = $this->_post('openid');
			$userInfo = $this->usersModel->where($usersInfoWhere)->find();
			if ($userInfo){
    			$password=get_md5_password($this->_post('password'));
    			if($password===$userInfo['password']){
    				$companyInfo = $this->companyModel->where(array('id'=>$userInfo['companyid']))->find();
    				if (!$companyInfo){
    					$returnData['msg'] = '您输入的账号有误，请重新输入';//公司信息不存在
    					echo json_encode($returnData);
    					exit();
    				}
    				if($companyInfo['viptime']< time() && $companyInfo['gid'] != 5){
    					$returnData['msg'] = '抱歉！您的账号已到期。续费请联系您的客户经理，系统将于一周内自动删除您的历史数据。';
    					echo json_encode($returnData);
    					exit();
    				}
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
    					$this->error(L('ServerBusyPrompt'));
    				}
    				session(null);
    				session('uid',$userInfo['id']);
    				$userInfo['shopsid'] = $userInfo['shopsid'] > 0 ? $userInfo['shopsid'] : '0';
    				session('shopsid',$userInfo['shopsid']);
    				session('uname',$userInfo['username']);
    				session('truename',$userInfo['truename']);
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
    				if($openid){
        				//有账号，绑定微信openid
    				    $this->usersModel->where(array('id'=>$userInfo['id']))->save(array('openid1'=>$openid));
    				}
    				$companyR = M('company')->where(array('id'=>$userInfo['companyid']))->save($saveCompanyDate);
    				$returnData['code'] = 200;
    				$returnData['msg'] = '登陆成功，正在跳转，请稍等';
    				$returnData['loc'] = U('User/System/systemInfo');
    			}else{
    			    $returnData['code'] = 302;//密码错误
    				$returnData['msg'] = '您输入的密码有误，请重新输入';
    			}
			}else{
			    $returnData['msg'] = '您输入的用户名不存在，请注册';
			}
			echo json_encode($returnData);
      } else {
        $oid = $_GET['oid'];
        if($oid){
            echo $openid = encrypt($oid,'D','Lando');exit();
            $this->assign('openid',$openid);//映射到页面
        }
        $this->display();
      }
    }
    
    public function register(){
        if (IS_POST) {
            $model = new Model();
            $model->startTrans();//开启事务
            $companyGroupInfo = $this->companyGroupModel->where(array('id'=>1))->field('id,permissions,maximgspace,maxrequestsnum')->find();
            if (empty($companyGroupInfo)){
                $this->error(L('ServerBusyPrompt'),U('Index/register'));
            }
            $viptime = time()+604800;//注册一周试用期
            $companyInfoData['viptime'] = $viptime;
            $companyInfoData['permissions'] = $companyGroupInfo['permissions'];
            $companyInfoData['gid'] = $companyGroupInfo['id'];
            $companyInfoData['maximgspace'] = $companyGroupInfo['maximgspace'];
            $companyInfoData['maxrequestsnum'] = $companyGroupInfo['maxrequestsnum'];
            $companyInfoData['wechatnum'] = 1;
            $companyInfoData['workernum'] = 0;
            $companyInfoData['shopsnum'] = 1;
            $companyInfoData['status'] = 0;
            $companyInfoData['isclose'] = 0;
            $companyInfoData['updatetime'] = $companyInfoData['createtime'] = time();
            $companyInfoInsterReturn = $this->companyModel->add($companyInfoData);
            	
            $_POST['companyid'] = $companyInfoInsterReturn;
            //$_POST['password'] = get_md5_password($this->_post('repeat_password'));
            //$_POST['truePassword'] = $this->_post('repeat_password','trim');
            $_POST['updatetime'] = $_POST['createtime'] = time();
            $_POST['createip'] = get_client_ip(0);
            $_POST['applyname'] = $this->_post('truename');
            $_POST['applymobile'] = $this->_post('phone');
            //$_POST['applyemail'] = $this->_post('email');
            $_POST['isboss'] = 1;
            $usersInsterReturn = $this->usersModel->add($_POST);
            	
            $mallHomeData['companyid'] = $companyInfoInsterReturn;
            $mallHomeData['tplid'] = 1;
            $mallHomeData['updatetime'] = $mallHomeData['createtime'] = time();
            $mallHomeReturn = M('mall_home')->add($mallHomeData);
            	
            if($companyInfoInsterReturn && $usersInsterReturn && $mallHomeReturn){
                $model->commit();//事务提交
                check_dir('./Uploads/'.$companyInfoInsterReturn.'/image');//创建文件夹
                $this->redirect(U('Index/registerOk'));//提示 审核
                //$this->redirect(U('Index/index'));//提示 审核
            }else{
                $model->rollback();//事务回滚
                $this->error(L('ServerBusyPrompt'),U('Index/register'));
            }
        }else{
            $oid = $_GET['oid'];
            if($oid){
                echo $openid = encrypt($oid,'D','Lando');exit();
                $this->assign('openid',$openid);//映射到页面
            }
            $this->display();
        }
    }
    
    /**
     * 
     * 微信登陆
     * 
     * @author Lando<806728685@qq.com>
     * @since  2015-8-6
     */
    public function wechatQ(){
      $type = $_GET['type'];
      $type = $type=='login' ? $type : 'login' ;
      $typeUrl = '&type='.$type;
      $code = $_GET['code'];
      $wechatI = array('appid'=>'wx374b745167cafb4e','appsecret'=>'0f3f7a5e4138c965eb4a8ab0f4cfabf1');
      $authname = 'login_wechat_access_token'.$wechatI['appid'];
      if ($code) {
        $REDIRECT_URI = urlencode(C('site_url').'/index.php?g=Home&m=Login&a=wechatQ'.$typeUrl);
        $STATE = md5(time());
        $wechatH1 = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$wechatI['appid'].'&secret='.$wechatI['appsecret'].'&code='.$code.'&grant_type=authorization_code';
        $result = http_get($wechatH1);
        $json = json_decode($result,true);
        if ($json['openid'] && !isset($json['errcode'])) {
            echo $openid = $json['openid'];exit();
            $userInfo = M('users')->where(array('openid1'=>$openid))->field('id,companyid,shopsid,username,truename')->find();
            if($userInfo){
              $companyInfo = M('company')->where(array('id'=>$userInfo['companyid']))->field('name,viptime,logourl,permissions,maximgspace,gid,wechatfollowlink')->find();
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
              session('uname',$userInfo['username']);
              session('truename',$userInfo['truename']);
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
              if ($companyR) {
                $this->success(L('LoginSuccessful'),U('User/System/systemInfo'));
              } else {
                //跳往注册页面
                $openid = encrypt($openid,'E','Lando');//加密openid
                if($type == 'login'){
                    $this->redirect(U('Login/login',array('oid'=>$openid)));
                }else {
                    $this->redirect(U('Login/register',array('oid'=>$openid)));
                }
              }
            }else{
                $this->redirect(U('Login/register'));
            }
			/* $data['expire_time'] = time() + $json['expires_in'];
    		$data['access_token'] = $json['access_token'];
    		$fp = fopen('./LightpenData/logs/Cache/access_token/'.$authname.'.json', 'w');
    		fwrite($fp, json_encode($data));
    		fclose($fp); */
		}else{
		    $this->redirect(U('Login/register'));
		}
      } else {
        if ($wechatI) {
          $REDIRECT_URI = urlencode(C('site_url').'/index.php?g=Home&m=Login&a=wechatQ'.$typeUrl);
          $STATE = md5(time());
          $wechatH = 'https://open.weixin.qq.com/connect/qrconnect?appid='.$wechatI['appid'].'&redirect_uri='.$REDIRECT_URI.'&response_type=code&scope=snsapi_login&state='.$STATE.'#wechat_redirect';
          redirect($wechatH);        
        }
      }
    }
}
?>
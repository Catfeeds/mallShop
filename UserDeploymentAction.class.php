<?php
/**
 * 
 * 
 * @author    Asa<asa@renlaifeng.cn>
 * @since     2016-10-10
 * @version   1.0
 */
class UserDeploymentAction extends UserAction{
	public function _initialize(){
		parent::_initialize();
		$this->companyid = session('cid');
	}
	/**
	 * 账户信息
	 */
	public function index(){
		//echo $this->companyid;
		$this->companyInfo = $companyInfo = M("company")->where(array("id"=>$this->companyid))->find();
		$this->memberNum = M("member_register_info")->where(array("companyid"=>$this->companyid,'isregister'=>1))->count();
		$this->fansNum = M()->table("tp_member_wechat_info mwi")->join("tp_member_register_info mri on mri.id = mwi.mid")
		->where(array("subscribetype"=>1,'mwi.companyid'=>$this->companyid))->count();
		//$checkCompanyid = M("check_customer_info")->where(array("id"=>$companyInfo['companyid']))->getField("id");
		//$this->vipInfo = M("check_customer_viptime")->where(array("companyid"=>$checkCompanyid))->order("createtime desc")->select();
		//$this->vipInfoOne = M("check_customer_viptime")->where(array("companyid"=>$checkCompanyid,'type'=>1))->getField("starttime");
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'账号部署','url'=>U('UserDeployment/index')),array('name'=>'账户信息')));
		//$this->consoleInfo = M('report_scrm_company_console_info')->where(array('companyid'=>$this->companyid))->field('nowwechatfansnum,membernum,nowsalesnum,updatetimech')->find();
		$this->display();
	}
	/**
	 * 公司及门店部署
	 */
	public function enterOne(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'账号部署','url'=>U('UserDeployment/index')),array('name'=>'公司门店部署')));
		if(IS_POST){
			//公司
			$where['id']=$this->companyid;
			$data['companyname']=$_POST['companyname'];
			$data['name']=$_POST['brandname'];
			$data['logourl']=$_POST['logourl'];
			$data['updatetime'] = time();
			$res1 = M("company")->where($where)->save($data);
			$resInfo = M("company")->where($where)->find();
			//客户档案
			$where2['id']=$resInfo['companyid'];
			$data3['updatetime'] = time();
			$data3['brandname']=$_POST['brandname'];
			$data3['companyname']=$_POST['companyname'];
			$res2 = M("check_customer_info")->where($where2)->save($data3);
			$data2['name'] = $_POST['agentname'];
			$data2['phone'] = $_POST['agentphone'];
			$data2['updatetime'] = time();
			$res3 = M("agent")->where(array("id"=>$_POST['agentid']))->save($data2);
			if($res1&&$res2){
				$ajax['code'] = 200;
				session("logourl",$_POST['logourl']);
				$ajax['msg'] = '保存成功';
			}else{
				$ajax['code'] = 300;
				$ajax['msg'] = '系统繁忙请稍后重试';
			}
			echo json_encode($ajax);
		}else{
			$this->checkCompanyScrm5Permissions(21,true);
			$where['c.id'] = $this->companyid;
			//公司信息
			$this->companyInfo = $cidInfoAsa = M()->table("tp_company c")
			->join("tp_check_customer_info cci on c.companyid = cci.id")
			->where($where)->field("cci.brandname,cci.companyname,c.logourl,c.companyid ccompanyid")->find();
			//门店信息
			$count = M("company_shops")->where(array('companyid'=>$this->companyid))->count();
			$page = new NewPage($count,15);
			$this->shopsInfo = M()->table("tp_company_shops cs")
			->join("tp_area a1 on cs.country = a1.id")
			->join("tp_area a2 on cs.province = a2.id")
			->join("tp_area a3 on cs.city = a3.id")
			->join("tp_area a4 on cs.district = a4.id")
			->where(array('companyid'=>$this->companyid))
			->limit($page->firstRow.','.$page->listRows)
			->field("cs.id,shopname,cs.title,cs.viewnum,cs.updatetime,tel,longitude,latitude,shophours,address,a1.name name1,a2.name name2,a3.name name3,a4.name name4,country,province,city,district")->select();
			$this->assign('page',$page->diyshow());
			//获取全部门店的信息
			$this->mallShops = M("company_shops_page_set")->where(array('companyid'=>$this->companyid))->find();
			
			//获取国家的
			$countryList = M('area')->where(array('isshow'=>'1','parentid'=>'0'))->order('sort ASC , id ASC')->field('id,name')->select();
			$this->assign('country',$countryList);
			$this -> agentInfo = M("agent")->where(array("companyid"=>$cidInfoAsa["ccompanyid"]))->find();
			$this->urlType = "enterOne";
			$this->display();
		}
	}
	/**
	 * 账号部署
	 */
	public function enterTwo(){
		if(IS_POST){
			require './LightpenCms/Lib/ORG/UcApi.Class.php';
			$userarr = json_decode($_REQUEST['userarr'],TRUE);
			$bossID = M("users")->where(array("companyid"=>$this->companyid,"isboss"=>1))->getField('id');
			foreach ($userarr as $key => $val){
				$data = $val;
				$data['updatetime'] = time();
				if($val['id']==''){
					$data['parentid'] = $bossID;
					$data['createtime'] = time();
					$data['password'] = md5($val['truePassword']);
					$data['companyid'] = $this->companyid;
					$userid = M("users")->add($data);
				}else{
					$data['password'] = md5($val['truePassword']);
					$info = M('users')->where(array('companyid'=>$this->companyid,'id'=>$val['id']))->field('username,password,truePassword')->find();
					//dump($data);
					$res = M("users")->where(array("id"=>$val['id']))->save($data);
				}
			}
			if($res){
				$ajax['code'] = 200;
				$ajax['msg'] = '保存成功';
			}else{
				$ajax['code'] = 300;
				$ajax['msg'] = '系统繁忙请稍后重试';
			}
			echo json_encode($ajax);
		}else{
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'账号部署','url'=>U('UserDeployment/index')),array('name'=>'账号部署')));
			$this->check_url = 'UserDeploymententerTwo';
			$where['companyid'] = $this->companyid;
			$where['status'] = 1;
			$where['isboss'] = 1;
			$this -> bossInfo = M("users")->where($where)->find();
			$where['isboss'] = array("neq",1);
			$this -> userInfo2 = M("users")->where($where)->select();
			$this->display();
		}
	}
	/**
	 * 支付j接口配置
	 */
	public function enterFive(){
		if(IS_POST){
			$data = $_POST;
			$data['updatetime'] = time();
			$data['companyid'] = $this->companyid;
			$where['companyid'] = $this->companyid;
			//dump($data);exit;
			$a = M('company_pay_wechat')->where($where)->find();
			if($a){
				$res1 = M('company_pay_wechat')->where($where)->save($data);
			}else{
				$data['createtime'] = time();
				$res1 = M('company_pay_wechat')->add($data);
			}
			$b = M('company_pay_alipay')->where($where)->find();
			if($b){
				$res2 = M('company_pay_alipay')->where($where)->save($data);
			}else{
				$data['createtime'] = time();
				$res2 = M('company_pay_alipay')->add($data);
			}
			if($res1&&$res2){
				$ajax['code'] = 200;
				$ajax['msg'] = '保存成功';
			}else{
				$ajax['code'] = 300;
				$ajax['error'] = $res1.'  '.$res2;
				$ajax['msg'] = '系统繁忙请稍后重试';
			}
			echo json_encode($ajax);
		}else{
			$this->checkCompanyScrm5Permissions(23,true);
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'账号部署','url'=>U('UserDeployment/index')),array('name'=>'支付接口配置')));
			$where['companyid'] = $this->companyid;
			$this->info = M('company_pay_wechat')->where($where)->find();
			$this->info2 = M('company_pay_alipay')->where($where)->find();
			$this->ImagesAll($this->companyid);
			$this->enterUrl = 'five';
			$this->display();
		}
	}
	/**
	 * 编辑门店详情信息
	 */
	public function shops(){
		if(IS_POST){
			$data = $_POST;
			$data['updatetime'] = time();
			if($this->_get("type")==1){
				if($this->_post("id")){
					$res = M("company_shops_page_set")->where(array('companyid'=>$this->companyid))->save($data);
				}else{
					$data['id'] = guidNow();
					$data['companyid'] = $this->companyid;
					$data['createtime'] = time();
					$res = M("company_shops_page_set")->add($data);
				}
			}else{
				$res = M("company_shops")->where(array("id"=>$this->_post("id")))->save($data);
			}
			if($res){
				$ajax['code'] = 200;
				$ajax['msg'] = '保存成功';
			}else{
				$ajax['code'] = 300;
				$ajax['msg'] = '系统繁忙请稍后重试';
			}
			echo json_encode($ajax);
		}else{
			$this->checkCompanyScrm5Permissions(21,true);
			$this->ImagesAll($this->companyid);
			if($_GET['type']==1){
				$this->info = M("company_shops_page_set")->where(array('companyid'=>$this->companyid))->find();
				$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'账号部署','url'=>U('UserDeployment/index')),array('name'=>'公司及门店设置','url'=>U('UserDeployment/enterOne')),array("name"=>"编辑门店-全部门店")));
			}else{
				$this->info = M("company_shops")->where(array('companyid'=>$this->companyid,'id'=>$this->_get("id")))->find();
				$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'账号部署','url'=>U('UserDeployment/index')),array('name'=>'公司及门店设置','url'=>U('UserDeployment/enterOne')),array("name"=>"编辑门店-门店详情")));
			}
			$this->display();
		}
		
	}
	/**
	 * 获取下一级省市信息
	 */
	public function ajaxGetCityInfo(){
		$returnData['code'] = 300;
		$returnData['msg'] = '抱歉，服务器繁忙，请稍后重试';
		$parentid = $this->_post('parentid');
		if($parentid){
			$addressList = M('area')->where(array('isshow'=>'1','parentid'=>$parentid))->order('sort ASC , id ASC')->field('id,name')->select();
			if($addressList){
				$string = '<option value="">请选择</option>';
				foreach ($addressList as $key=>$val){
					$string .= '<option value="'.$val['id'].'">'.$val['name'].'</option>';
				}
				$returnData['code'] = 200;
				$returnData['msg'] = $string;
			}
		}
		echo json_encode($returnData);
	}
	/**
	 * getTimeAsa
	 */
	public function getTimeAsa(){
		$returnData['msg'] = date("Y-m-d H:i:s",time());
		echo json_encode($returnData);
	}
	/**
	 * 设置门店信息
	 */
	public function ajaxShop(){
		if(IS_POST){
			$data = $_POST;
			$data['companyid'] = $this->companyid;
			$data['info'] = htmlspecialchars_decode(htmlspecialchars_decode($this->_post("info")));
			$data['isshow'] = 1;
			$data['updatetime'] = time();
			if($this->_post("id")){
				$res = M("company_shops")->where(array("id"=>$this->_post("id")))->save($data);
			}else{
				$res = M("company_shops")->add($data);
			}
			if($res){
				$ajax['code'] = 200;
				$ajax['msg'] = '保存成功';
			}else{
				$ajax['code'] = 300;
				$ajax['msg'] = '系统繁忙请稍后重试';
			}
			echo json_encode($ajax);
		}
	}
	/**
	 * 删除门店信息
	 */
	public function delShop(){
		if(IS_POST){
			$res = M("company_shops")->where(array("id"=>$this->_post("id")))->delete();
			if($res){
				$ajax['code'] = 200;
				$ajax['msg'] = '门店信息删除成功';
			}else{
				$ajax['code'] = 300;
				$ajax['msg'] = '系统繁忙请稍后重试';
			}
			echo json_encode($ajax);
		}
	}
	/**
	 * 获取门店内容
	 */
	public function infoShops(){
		if(IS_POST){
			$res = M("company_shops")->where(array("id"=>$this->_post("id")))->find();
			$res['shophours'] = htmlspecialchars_decode($res['shophours']);
			echo json_encode($res);
		}
	}
	/**
	 * 判断用户名是否重复
	 */
	public function ajaxUser(){
		$id = $this->_post("id");
		$username = $this->_post("username");
		$where['id'] = array("neq",$id);
		$where['username'] = $username;
		$res = M("users")->where($where)->count();
		if($res){
			$ajax['code'] = 300;
			$ajax['msg'] = '用户名重复';
		}else{
			$ajax['code'] = 200;
			$ajax['msg'] = '用户名可以使用';
		}
		echo json_encode($ajax);
	}
	/**
	 * 删除子账号
	 */
	public function ajaxDelUser(){
		$id = $this->_post("id");
		$parentid = $this->_post("parentid");
		$where['id'] = $id;
		$where['parentid'] = $parentid;
		$username = M("users")->where($where)->getField("username");
		$res = M("users")->where($where)->delete();
		if($res){
			$ajax['code'] = 200;
			$ajax['msg'] = '子账号信息删除成功';
		}else{
			$ajax['code'] = 300;
			$ajax['msg'] = '系统繁忙请稍后重试';
		}
		echo json_encode($ajax);
	}
	public function asatest(){
		require './LightpenCms/Lib/ORG/UcApi.Class.php';
		$username = 'imatest5';
		$reg = UcApi::delUser($username);
		if(!$reg){
			$data6['id'] = guidNow();
			$data6['username'] = $username;
			$data6['type'] = 3;
			$data6['data'] = "子账号删除通论坛失败,返回值".$reg.'账号：'.$username;
			$data6['time'] = date("Y-m-d H:i:s",time());
			$data6['createtime'] = time();
			$resLog = M("users_log")->add($data6);
		}
		dump($reg);
	}
	/**
	 * 门店SQL
	 */
	public function shopsSql(){
		$a=0;
		$info = M("company_shops")->where()->select();
		foreach ($info as $val){
			$data['banner'] = $val['logourl'];
			$arr['shophours1']['time1']=$val['hoursstarth'];
			$arr['shophours1']['time2']=$val['hoursstarti'];
			$arr['shophours1']['time3']=$val['hoursendh'];
			$arr['shophours1']['time4']=$val['hoursendi'];
			$data['shophours']= json_encode($arr);
			echo $data['shophours'].'1<br />';
			$arr='';
			M("company_shops")->where(array("id"=>$val['id']))->save($data);
			$a++;
		}
	}
	/**
	 * 公司和品牌的数据通
	 */
	public function companySql(){
		$a = 0;
		$info = M("company")->select();
		foreach ($info as $val){
			$data = '';
			//$data["name"] = $val['name'];
			$data['companyname'] = M("check_customer_info")->where(array("id"=>$val["companyid"]))->getField("companyname");
			$data['companyname']?$data['companyname']:$data['companyname']=$val['name'];
			M("check_customer_info")->where(array("id"=>$val["companyid"]))->save(array("brandname"=>$val['name']));
			$res = M("company")->where(array("id"=>$val["id"]))->save($data);
			$data['id'] = $val['id'];
			$success[] = $data;
			if($res){
				$a++;
			}else{
				$error = $data;
			}
		}
		echo "成功".$a."条<br />";
		echo "失败详情：<br />";
		dump($error);
		dump($success);
	}
	/**
	 * 老数据的迁移工作
	 */
	 public function viptimeSql(){
	 	$a=0;
	 	$info = M("company")->where(array("status"=>3))->select();
	 	foreach($info as $key => $val){
	 		$checkCompanyid = M("check_customer_info")->where(array("id"=>$val["companyid"]))->getField("id");
	 		$fansInfo = M("check_enter_info")->where(array("companyid"=>$val["companyid"]))->getField("nowwechatfansnum,salesvolumenow");
	 		$fansNum = M()->table("tp_member_wechat_info mwi")->join("tp_member_register_info mri on mri.id = mwi.mid")
	 					->where(array("subscribetype"=>1,'mwi.companyid'=>$val['id']))->count();
	 		$memberNum = M("member_register_info")->where(array("companyid"=>$val['id'],'isregister'=>1))->count();
	 		//计算总销售额
	 		//$scrmWhere['_string'] = "`status`=1 or paystatus=3";
	 		//$scrmWhere['companyid'] = $val['id'];
	 		//$scrmWhere['moblie'] = array("not in",'7,8');
	 		//$scrmNum = M('member_spending')->where($scrmWhere)->sum("spendingamount");
	 		
	 		//写续约日志的SQL  ,'starttime'=>array("egt",time()),'endtime'=>array('elt',time()
	 		$viptime = M("check_customer_viptime")->where(array("companyid"=>$checkCompanyid))->field("id,createtime")->order("createtime desc")->find();
	 		$vipdata['updatetime'] = time();
	 		if($viptime){
	 			$vipdate['allfansnum'] = $fansNum;
	 			$vipdate['allmembernum'] = $memberNum;
	 			//$vipdate['allscrmsell'] = $scrmNum?$scrmNum:0;
	 			$vipdate['allscrmsell'] = 0;
	 			$vipdate['fansnum'] = $fansNum?$fansNum:0-$fansInfo['nowwechatfansnum']?floor($fansInfo['nowwechatfansnum']):0;
	 			$vipdate['membernum'] = $memberNum?$memberNum:0;
	 			//$vipdate['scrmsell'] = $scrmNum?$scrmNum:0;
	 			$vipdate['scrmsell'] = 0;
	 			$vipres = M("check_customer_viptime")->where(array("companyid"=>$val["id"],'id'=>$viptime['id']))->save($vipdata);
	 		}else{
	 			$vipdata['fansnum'] = $vipdate['allfansnum'] = $fansNum;
	 			$vipdata['membernum'] = $vipdate['allmembernum'] = $memberNum?$memberNum:0;
	 			//$vipdata['scrmsell'] = $vipdate['allscrmsell'] = $scrmNum?$scrmNum:0;
	 			$vipdata['scrmsell'] = $vipdate['allscrmsell'] = 0;
	 			$vipdata['id'] = guidNow();
	 			$vipdata['companyid'] = $checkCompanyid?$checkCompanyid:0;
	 			$vipdata['starttime'] = $val['createtime'];
	 			$vipdata['endtime'] = $val['viptime'];
	 			$vipdata['type'] = 1;
	 			$vipdata['createtime'] = time();
	 			$vipres = M("check_customer_viptime")->where(array("companyid"=>$checkCompanyid,'type'=>1))->add($vipdata);
	 		}
	 		if(!$vipres) $viparr['error'][]=$val['id'];
	 		
	 		//获取时长的
	 		$vipInfo = M("check_customer_viptime")->where(array("companyid"=>$checkCompanyid))->select();
	 		$timelength = 0;
	 		foreach ($vipInfo as $vipval){
	 			if($vipval['endtime']>=time()&&$vipval['starttime']<=time()){
	 				$timelength += (time()-$vipval['starttime'])/(3600*24);
	 			}elseif($vipval['starttime']<time()){
	 				$timelength += ($vipval['endtime']-$vipval['starttime'])/(3600*24);
	 			}
	 			//echo time().'<br />';
	 			//echo $vipval['starttime'].'<br />';
	 			//echo $timelength.'<br />';
	 		}
	 		//写入公司表的
	 		$comdata['updatetime'] = time();
	 		$comdata['wechatfansnum'] = $fansNum;
	 		$comdata['registermembernum'] = $memberNum;
	 		//$comdata['cumulativesales'] = $scrmNum?$scrmNum:0;
	 		$comdata['cumulativesales'] = 0;
	 		$comdata['entertimelength'] = floor($timelength);
	 		M("company")->where(array("id"=>$val['id']))->save($comdata);
	 		$arr[$key]['companyid']=$val['id'];
	 		$arr[$key]['fansNum']=$fansNum;
	 		$arr[$key]['membernum']=$memberNum;
	 		$a++;
	 	}
	 	echo "共".$a.'条消息，<br />';
	 	dump($viparr['error']);
	 	dump($arr);
	 }
	 
	 /**
	  * 拉粉码跑的数据
	  */
	 public function lafenSql(){
	 	$info = M("company")->where(array("status"=>3))->field("id,companyid")->select();
	 	foreach ( $info as $key => $val ){
	 		$userInfo = M("users")->where(array("companyid"=>$val['id'],'status'=>1))->field("id,username,truename,isboss")->select();
	 		foreach ($userInfo as $key2 => $val2){
	 			if($val2)
	 				$coderes = $this->createLafenCode($val['id'],$val2['id'],$val2['truename']?$val2['truename']:$val2['username'],$val2['isboss']);
	 			$coderes['companyid'] = $val['id'];
	 			$coderes['userid'] = $val2['id'];
	 			dump($coderes);
	 		}
	 	}
	 }
	 /**
	  * 删除拉粉码的数据
	  */
	 public function lafenDelSql(){
	 	$info = M("company")->where(array("status"=>3,"id"=>array("neq",1088)))->field("id,companyid")->select();
	 	foreach ( $info as $key => $val ){
	 		$userInfo = M('quick_response_code')->where(array('companyid'=>$val['id'],"userid"=>array("neq","")))->select();
	 		foreach ($userInfo as $key2 => $val2){
	 			$userInfo2 = M("users")->where(array("id"=>$val2['userid']))->field("id,companyid,username,truename,isboss")->find();
	 			if(!$userInfo2){
	 				M('quick_response_code')->where(array('id'=>$val2['id']))->delete();
	 				$userInfo2['test'] = $val2;
	 				M("quick_response_code_old_info")->add($val2);
	 				dump($userInfo2);
	 			}
	 		}
	 	}
	 }
	 /**
	  *
	  * 设置权限
	  *
	  * @author Mark<1311013341@qq.com>
	  * @since  2016-12-19
	  */
	 public function Juris(){
	 	$id = $this->_get("id");
	 	$where['id'] = $id;
	 	$this->type = $this->_get('type');
	 	if($this->type == '2'){
	 		$db = M( 'system_permissions_list');
	 		$fields = 'truename,username,permissions';
	 	}elseif($this->type == '3'){
	 		$db = M( 'system_helper_permissions_list');
	 		$fields = 'truename,username,helperpermissions as permissions';
	 	}else{
	 		$db = M( 'system_scrm5_permissions_list_new');
	 		$fields = 'truename,username,scrm5permissions as permissions';
	 	}
	 	$field = 'id,name';
	 	$userInfo = M("users")->where($where)->field($fields)->find();
	 	$systemPermissionsList = $db->where(array('parentid'=>0,'isshow'=>'1'))->field($field)->order('sort ASC')->select();
	 	if($systemPermissionsList){
	 		foreach($systemPermissionsList as $splKey=>$splVal){
	 			$systemPermissionsList[$splKey]['list'] = $db->where(array('parentid'=>$splVal['id'],'isshow'=>'1'))->field($field)->order('sort ASC')->select();
	 			if($systemPermissionsList[$splKey]['list']){
	 				foreach($systemPermissionsList[$splKey]['list'] as $splKeys=>$splVals){
	 					$systemPermissionsList[$splKey]['list'][$splKeys]['info'] = $db->where(array('parentid'=>$splVals['id'],'isshow'=>'1'))->field($field)->order('sort ASC')->select();
	 				}
	 			}
	 		}
	 	}
	 	$this->assign('list',$systemPermissionsList);
	 	$this->assign('userInfo',$userInfo);
	 	$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'账号部署','url'=>U('UserDeployment/index')),array('name'=>'账号部署','url'=>U('UserDeployment/enterTwo')),array('name'=>$userInfo['truename']?$userInfo['truename'].'的权限设置':$userInfo['username'].'的权限设置')));
	 	$this->assign('id',$id);
	 	$this->display();
	 }
	 /**
	  *
	  * ajax保存权限
	  *
	  * @author Mark<1311013341@qq.com>
	  * @since  2016-12-19
	  */
	 public function ajaxJuris(){
	 	$ajax['type'] = 'error';
	 	$ajax['msg'] = '网络繁忙，请稍后重试';
	 	if(IS_POST){
	 		$id = $this->_get("id");
	 		$type = $this->_get('type');
	 		if($type == '2'){
	 			$data['permissions'] = $this->_post('quanxian');
	 		}elseif($type == '3'){
	 			$data['helperpermissions'] = $this->_post('quanxian');
	 		}else{
	 			$data['scrm5permissions'] = $this->_post('quanxian');
	 		}
	 		$data['updatetime'] = time();
	 		$result = M('users')->where(array('id'=>$id))->save($data);
	 		if($result){
	 			$ajax['type'] = 'success';
	 			$ajax['msg'] = '保存成功';
	 		}else{
	 			$ajax['type'] = 'error';
	 			$ajax['msg'] = '保存失败';
	 		}
	 	}
	 	echo json_encode($ajax);
	 }
}
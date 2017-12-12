<?php
/**
 * 这是专门开通账号用的
 *
 * @author    Asa<asa@renlaifeng.cn>
 * @since     2016-09-13
 * @version   1.0
 */
class OpenUsersAction extends HCmsBaseAction{
	public function __construct(){
		parent::__construct();
	}
	/**
	 * 开通账号
	 */
	public function openUsers(){
		if($this->_get("role")==1){
			$username = $this ->_get("username");
			$userInfo = M("users")->where(array("username"=>$username))->field("id,companyid,username,truePassword")->find();
			//dump($userInfo);
			$companyInfo = M("company")->where(array("id"=>$userInfo['companyid']))->field("id,companyid,name,logourl")->find();
			
			$checkInfo = M("check_customer_info")->where(array("id"=>$companyInfo['companyid']))->save(array("status"=>1));
			$data['status'] = 3;
			$data['viptime'] = 1514736000;
			$group = M("company_group") -> where(array("id"=>8))->find();
			$data['permissions'] = $group['permissions'];
			$data['scrm5permissions'] = ",1,2,3,5,6,7,8,9,11,12,14,15,16,17,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,";
			$comInfo = M("company")->where(array("id"=>$userInfo['companyid']))->save($data);
			if($comInfo){
				echo "公司信息修改成功<br />";
			}else{
				echo "公司信息修改失败<br />";
			}
			//dump($companyInfo);   operationstatus
			$shopsInfo = M("company_shops")->where(array("companyid"=>$companyInfo['id']))->find();
			if(!$shopsInfo){
				$shops['companyid'] = $companyInfo['id'];
				$shops['shopname'] = "人来风MoBiWind";
				$shops['tel'] = C('servicenumber');
				$shops['country'] = "1017";
				$shops['province'] = "10185";
				$shops['city'] = "100395";
				$shops['address'] = "广灵四路116号";
				$shops['longitude'] = "121.480180";
				$shops['latitude'] = "31.286440";
				$res1 = M("company_shops")->add($shops);
				if($res1){
					echo "门店新增成功<br />";
				}else{
					echo "门店新增失败<br />";
				}
			}else{
				echo "已经配置过门店了，请查看<br />";
			}
			$apiInfo = M("wechats")->where(array("companyid"=>$companyInfo['id']))->find();
			if(!$apiInfo){
				$api["companyid"] = $companyInfo['id'];
				$api["appid"] = "wxe11bf6091163ef64";
				$api["appsecret"] = "aa4000184ab77a7672f7202d77bafd97";
				$api["encodingaeskey"] = "CW473uKrWVWFtGRXKsheRE97kBflpDnZ73BCArJU6cw";
				$api['token']="vyeqea1402554707";
				$api['waplang'] = 'zh-cn';
				$api["wxid"] = "gh_541dbb404a47";
				$api["weixin"] = "renlaifeng";
				$api["wxname"] = "人来风";
				$api["wechattype"] = 4;
				$api['headerpic'] = "http://www.mobiwind.cn/Uploads/1/image/20160823/20160823182949_77614.jpeg";
				$api['updatetime'] = $api['createtime'] = time();
				$res2 = M("wechats")->add($api);
				if($res2){
					echo "配置API成功<br />";
				}else{
					echo "配置API失败<br />";
				}
			}else{
				echo "已经配置过API了，请查看<br />";
			}
			
			
		}else{
			$this->redirect(U("Home/Index/index"));
		}
	}
}
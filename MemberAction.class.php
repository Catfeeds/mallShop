<?php
/**
 * 我的会员中心
 * Enter description here ...
 * @author yaochengkai
 */
class MemberAction extends WapBaseAction{
	
	private $mid;
	
	private $companyid;
	
	private $memberRegisterInfoModel;
	
	private $limit;
	
	public function __construct(){
		parent::__construct();
		$this->mid = session('mid'.session('wapcid'));
		$this->companyid = session('wapcid');
		$this->memberRegisterInfoModel = M('member_register_info');
		$this->limit = 8;
	}
	/**
	 * 会员中心
	 */
	public function center(){
		$this->setPageTitle(array('title'=>'我的会员中心'));
        $this->checkMemberLoginBox(); // 检测是否登录弹框
		if($this->mid){
		    $memberInfo = M()->table("tp_member_wechat_info mw")
                ->join("tp_member_register_info mr on mr.id = mw.mid")
                ->where(array("openid"=>session("openid".$this->companyid)))->field("mw.id,mid,mobile,nickname,headimgurl")->find();
            $count['unPay'] = M("mall_order_info")->where(array("mid"=>$this->mid,'orderstatus'=>1))->count();
            $count['usePay'] = M("mall_order_info")->where(array("mid"=>$this->mid,'orderstatus'=>array("in","2,3")))->count();
            $count['payOk'] = M("mall_order_info")->where(array("mid"=>$this->mid,'orderstatus'=>4))->count();
		}else{
		    session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);// 用于登陆后跳回本页面
		    $this->checkMemberLoginBox(); // 检测是否登录弹框
		    $memberInfo = $memberCardInfoSetInfo = array();
		}
        $this->remindCount = M("member_remind")->where(array("mid"=>$this->mid,'state'=>0))->count();
        $this->remindCount2 = M("member_remind")->where(array("mid"=>$this->mid,'state'=>array("in","0,1")))->count();
		//$this->assign('zyisshow',$zyisshow);
        $this->assign('memberInfo',$memberInfo);
        $this->assign('count',$count);
		$this->assign('memberCardInfoSetInfo',$memberCardInfoSetInfo);
		$this->display();
	}
	public function clear(){
	    dump($_SESSION);
	    $_SESSION = '';
        $_SESSION=array();
    }
	/**
	 * 账户设置
	 * @author Tomas
	 */
	public function accountSet(){
		$this->setPageTitle(array('title'=>'账户设置'));
		//个人信息表
		$memberInfo = M('member_register_info')
		->where(array('companyid'=>$this->companyid,'id'=>$this->mid))
		->field('mobile,totalintegration,totalexperiencevalue,accountbalance')->find();
		$this->assign('memberInfo',$memberInfo);
		$this->display();
	}
	/**
	 * 修改手机号码
	 * @author Tomas
	 */
	public function updateMobile(){
		if(IS_POST){
			$where['companyid'] = $this->companyid;
			$where['id'] = $this->mid;
			
			$_POST['truepassword'] = $this->_post('password','trim');
			$_POST['password'] = $this->_post('password','get_md5_password,trim');
			$_POST['updatetime'] = time();
			$registerInfo = M('Member_register_info')->where($where)->save($_POST);
			if($registerInfo){
				$data['code'] = 200;
				$data['tips'] = '恭喜您，修改成功！';
			}else{
				$data['code'] = 300;
				$data['tips'] = '抱歉，您的操作有误！请重新操作。';
			}
			echo json_encode($data);
		}else{
			$this->setPageTitle(array('title'=>'修改手机号码'));
			//个人信息表
			$memberInfo = M('member_register_info')->where(array('companyid'=>$this->companyid,'id'=>$this->mid))->field('mobile')->find();
			$this->assign('memberInfo',$memberInfo);
			$this->display();
		}
	}
	/**
	 * 获取验证码
	 * @author Tomas
	 */
	public function ajaxGetCode(){
		$time = time();
		$mobile = $this->_post('phoneVal');  // 接收手机号
		$where['companyid'] = $this->companyid;
		$where['id'] = $this->mid;
		if($mobile){
			//查询是否有使用的验证码
			$info = M('member_mobile_code')->where(array('companyid'=>$this->companyid,'mobile'=>$mobile,'isused'=>2))->find();
			if($info){  // 有
				if($info['useendtime'] >= $time){  // 未过期发送短信
					$content = '【微新风】您好，您正在更换您的登录手机号，验证码是：'.$info['code'].' ，如果非本人操作，请忽略';
					$sendData = $this->sendSms($content,$mobile);
					if($sendData['code'] == 200){
						$ajax['code'] = '200';
						$ajax['tips'] ='验证码已下发,请耐心等待';
					}else{
						$ajax['code'] = '306';
						$ajax['tips'] = '验证码发送失败,请重新发送';
					}
				}else{ // 已过期
					$date['isused'] = '1';
					$result = M('member_mobile_code')->where(array('mobile'=>$mobile, 'companyid'=>$this->companyid))->save($date);
					if($result) {
						$code['companyid'] = $this->companyid;
						$code['mobile'] = $mobile;
						$code['code'] = mt_rand(1000, 9999);	//验证码
						$code['useendtime'] = $time+1800;  // 过期时间
						$code['isused'] = '2';
						$code['createtime'] = $time;
						$insterCode = M('member_mobile_code')->add($code);
						if ($insterCode){
							$content = '【微新风】您好，您正在更换您的登录手机号，验证码是：'.$code.' ，如果非本人操作，请忽略';
							$sendData = $this->sendSms($content,$mobile);
							if($sendData['code'] == 200){
								$ajax['code'] = '200';
							}
						}else{
							$ajax['code'] = '305';
							$ajax['tips'] = '验证码发送失败,请重新发送';
						}
					}
				}
			}else{ // 没有未使用的验证码
				$code['companyid'] = $this->companyid;
				$code['mobile'] = $mobile;
				$code['code'] = mt_rand(1000, 9999);	//验证码
				$code['useendtime'] = $time+1800;  // 过期时间
				$code['isused'] = '2';
				$code['createtime'] = $time;
				$insterCode = M('member_mobile_code')->add($code);
				if($insterCode){
					//发送
					$content = '【微新风】您好，您正在更换您的登录手机号，验证码是：'.$code.' ，如果非本人操作，请忽略';
					$sendData = $this->sendSms($content,$mobile);
					if($sendData['code'] == 200){
						$ajax['code'] = '200';
					}else{
						$ajax['code'] = '304';
						$ajax['tips'] = '验证码发送失败,请重新发送';
					}
				}else{
					$ajax['code'] = '301';
					$ajax['tips'] = '验证码发送失败,请重新发送';
				}
			}
		}else{
			$ajax['code'] = '302';
			$ajax['tips'] = '验证码发送失败,请重新发送';
		}
		echo json_encode($ajax);
	}
	/**
	 * 二维码
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-10-25
	 */
	public function erweima(){
		$url = base64_decode($this->_get('link'));
		$this->getQRcode($url);
		$this->display();
	}
	/**
	 * 订单列表
	 * @since  2017-3-20
	 */
	public function orderList(){
		$this->setPageTitle(array('title'=>'交易订单'));
		if($this->mid){
		    $status = $this->_get("status");
		    if($status){
				if($status==2)
					$where['oi.orderstatus'] = array("in","2,3");
				else
				$where['oi.orderstatus'] = $status;
			}
		    else{
				$where['oi.orderstatus'] = array("neq",5);
				$status = 0;
			}
		    $where['oi.mid'] = $this->mid;
            $lists = M("")->table("tp_mall_order_info oi")
                ->join("tp_mall_order_goods og on oi.orderid=og.orderid")
                ->join("tp_mall_goods_sku gs on gs.id=og.goodskuid")
                ->where($where)->field("oi.id,oi.orderid,oi.orderstatus,og.goodname,og.goodpic,og.goodid,og.goodprice,og.goodnum,gs.name,gs.saleprice,oi.consigneename,oi.consigneephone,oi.consigneeaddress")->select();
            $this->assign("status",$status);
            $this->assign("lists",$lists);
            $this->assign("goods_type",$this->orderStatus());
		}else{
			session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);// 用于登陆后跳回本页面
			$this->checkMemberLoginBox(); // 检测是否登录弹框
		}
		$this->display();
	}

    /**
     * 删除订单
     */
	public function delOrder(){
        $id = $this->_post("id");
        $id = mb_substr ($id,0,-1);
        $where['id'] = array("in",$id);
        M("mall_order_info")->where($where)->save(array("orderstatus"=>5));
        echo json_encode(array("code"=>200,"msg"=>'success'));
    }
	public function orderStatus(){
	    return array(
            '0'=>'全部',
            '1'=>'待付款',
            '2'=>'待配送',
			'3'=>'待安装',
			'4'=>'已安装',
        );
    }

	/**
	 * 我的优惠券
	 */
	public function myVouchers()
	{
		$where['mid'] = $this->mid;
		$where['status'] = 2;
		//未使用
		$vouchers = M('member_vouchers')->where($where)->order('end_time DESC')->select();
		$this->assign('vouchers', $vouchers);
		$this->display();
	}
	/**
	 * 退出
	 */
	public function loginOut()
	{
		$_SESSION = array();
		$_COOKIE = array();
		redirect(U("Login/login"));
	}
}
?>
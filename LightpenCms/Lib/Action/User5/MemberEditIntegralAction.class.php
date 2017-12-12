<?php
/**
 * 
 * 积分修改
 * 
 * @author    Leo<1251868177@qq.com>
 * @since     2016-11-3
 * @version   1.0
 */
class MemberEditIntegralAction extends UserAction{

	private $uid;
	
	private $companyid;
	
	private $shopsid;
	
	public function __construct(){
		parent::__construct();
		$this->uid = session('uid');
		$this->companyid = session('cid');
		$this->shopsid = session('shopsid');
		$this->checkCompanyScrm5Permissions(33,TRUE);
	}
	/**
	 * 
	 * 积分修改
	 * 
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-11-3
	 */
	public function index(){
	    $this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'后台积分修改','url'=>'','rel'=>'','target'=>'')));
	    $moblie = $this->_request('moblie');
	    if($moblie){
	        $where['companyid'] = $this->companyid;
	        $where['moblie'] = array('LIKE', '%'.$moblie.'%');
	        $list = M('member_register_info')->where($where)->where($where)->field('id,name,moblie,totalintegration')->order('updatetime desc')->select();
	    }
	    $this->assign('list', $list);
	    $this->assign('moblie', $moblie);
	    $this->display();
	}
	/**
	 * 
	 * 异步修改积分【添加/减少】
	 * 
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-11-8
	 */
	public function ajaxEditIntegral(){
	    $returnData['code'] = 300;
	    $returnData['tips'] = '抱歉，服务器繁忙，请稍后重试';
        $integralnum = $this->_post('integralnum');
        $note = $this->_post('note');
        $mid = $this->_post('mid');
        $type = $this->_post('type');
        if($integralnum && $mid && ($type=='19'||$type=='20')){
            if($type=='20'){   // 减积分
                $registerInfo = M('member_register_info')->where(array('companyid'=>$this->companyid, 'id'=>$mid))->field('id, totalintegration')->find();
                if($registerInfo){
                    $totalintegration = $registerInfo['totalintegration']?$registerInfo['totalintegration']:'0';
                    if($integralnum <= $totalintegration){
                        $integralReturn = '1';
                    }else{
                        $returnData['tips'] = '抱歉，你的可用积分小于需要减去的积分';
                    }   
                }
            }else{   // 加积分
                $integralReturn = '1';
            }
            if($integralReturn){
            	if($type == '20'){
	                $option['type']  = '201';//交易类型
            	}else{
            		$option['type']  = '104';//交易类型
            	}
                $option['cid']  = $this->companyid;//会员id
                $option['uid']  = $this->uid;//会员id
                $option['mid']  = $mid;//会员id
                $option['num']  = $integralnum;//数值
                $option['note']  = $note;// 备注
                $return = $this->changeMemberBusinessSCRM5($option);
                if($return){
                    $returnData['code'] = 200;
                    $returnData['tips'] = '积分修改成功';    
                }
            }
        }
	    echo json_encode($returnData);
	}
	/**
	 * 
	 * 积分修改记录
	 * 
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-11-3
	 */
	public function lists(){
	    $this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'后台积分修改记录','url'=>'','rel'=>'','target'=>'')));
	    $where['integral.companyid'] = $this->companyid;
	    $where['integral.type'] = array("IN", '201,104');
	    $where['integral.status'] = '1';
	    $count = M()->table('tp_member_integral AS integral')->join('tp_member_register_info AS registerInfo ON integral.mid=registerInfo.id')->where($where)->count();
	    $page = new NewPage($count,15);
	    $list = M()->table('tp_member_integral AS integral')->join('tp_member_register_info AS registerInfo ON integral.mid=registerInfo.id')->where($where)->field('integral.id,integral.mid,integral.orderid,integral.type,integral.integralnum,integral.note,integral.username,integral.shopname,integral.createtime, registerInfo.name,registerInfo.moblie')->order('createtime DESC')->select();
	    $this->assign('page',$page->show());
	    $this->assign('list',$list);
	    $this->display();
	}
}
?>
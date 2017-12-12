<?php
/**
 * 
 * 人来风SCRM API
 * 
 * @author    Lando<806728685@qq.com>
 * @since     2016-10-29
 * @version   1.0
 */
class ScrmApiAction extends BaseAction{
	
    private $companyid = 1;
    
	public function __construct(){
		parent::__construct();
		ignore_user_abort();
		set_time_limit(0);
		
	}
	/**
	 * 
	 * API  入口
	 * 
	 * @author Lando<806728685@qq.com>
	 * @since  2016-10-29
	 */
	public function index(){
	    $error['code'] = '';
	    $postData = json_decode(file_get_contents('php://input'),true);
	    $mothed = $postData['mothed'];
	    if($mothed == 'mobiwind.crm.member.mids.get'){
	        $nextMid = $postData['next_mid'] ? $postData['next_mid'] : 0;
	        $returnData = $this->getCrmMemberMids($nextMid);
	    }elseif($mothed == 'mobiwind.crm.member.info.get'){
	        $nextMid = $postData['next_mid'] ? $postData['next_mid'] : 0;
	        $returnData = $this->getCrmMemberInfo($nextMid);
	    }else{
	        $error['code'] = 201;
	    }
	    echo  json_encode($error);
	}
	
	/**
	 * 
	 * 获取注册会员Mids
	 * 
	 * @param string $nextMid 下一页数据获取的开始Mid
	 * @param number $pageSize 每页返回数据，默认5000，暂时不做成变量
	 * @author Lando<806728685@qq.com>
	 * @since  2016-10-29
	 */
	public function getCrmMemberMids($nextMid = '0',$pageSize = 5000){
	    
	    $memberMidWhere = array('companyid'=>$this->companyid,'isregister'=>1);
	    if($nextMid){
	        $memberMidWhere['id'] = array('gt',$nextMid);
	    }
	    $memberMidList = M('member_register_info')->where($memberMidWhere)->order('id ASC')->limit($pageSize)->field('id as mid')->select();
	    
	    $returnData['mids'] = $memberMidList;
	    $nextMidKey = count($memberMidList)-1;
	    $returnData['next_mid'] = $memberMidList[$nextMidKey]['mid'];
	    return $returnData; 
	}
	
	public function getCrmMemberInfo($mid = '0'){
	     
	    $memberMidWhere = array('register.companyid'=>$this->companyid,'register.isregister'=>1);
	    if($mid){
	        $memberMidWhere['register.id'] = $mid;
	    }
	    $memberMidList = M()->table('tp_member_register_info as register')->join(array('tp_member_wechat_info as wechat on wechat.mid=register.id','tp_member_card_info as card on card.mid=register.id'))->where($memberMidWhere)->field('register.id as mid,')->find();
	     
	    $returnData['mids'] = $memberMidList;
	    $nextMidKey = count($memberMidList)-1;
	    $returnData['next_mid'] = $memberMidList[$nextMidKey]['mid'];
	    echo json_encode($returnData);
	}
	
}
?>
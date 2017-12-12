<?php
/**
 * 
 * 会员画像  - 会员数据报表
 * 
 * @author    Lando<806728685@qq.com>
 * @since     2017-1-18
 * @version   1.0
 */
class MemberPortraitAction extends UserAction{

	private $uid;
	
	private $companyid;
	
	private $shopsid;
	
	public function __construct(){
		parent::__construct();
		$this->uid = session('uid');
		$this->companyid = session('cid');
		$this->shopsid = session('shopsid');
		$this->checkCompanyScrm5Permissions(97,TRUE,2);
	}
	/**
	 * 
	 * 会员画像
	 * 
	 * @author Lando<806728685@qq.com>
	 * @since  2017-1-18
	 */
	public function index(){
	    $this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'会员画像','url'=>'','rel'=>'','target'=>'')));
	    $consoleInfo = M('report_scrm_company_console_info')->where(array('companyid'=>$this->companyid))->find();
	    $this->consoleInfo = $consoleInfo;
	    $memberRanks = M('member_card_rank')->where(array('companyid'=>$this->companyid,'name'=>array('neq','')))->field('name,reportnumber')->order('number ASC')->select();
	    if($memberRanks){
	        /*   Y轴数据颜色 {y:8890,color:"#e2c9ff"},
	        {y:5560,color:"#c38eff"},
	        {y:3280,color:"#a85aff"},
	        {y:2200,color:"#6f1dcc"}, */
	        $memberRanksReport = array();
	        foreach ($memberRanks as $mKey=>$mVal){
	            $mVal['reportnumber'] = $mVal['reportnumber'] ? $mVal['reportnumber'] : 0;
	            $memberRanksReport['xdata'] .= "'".$mVal['name']."（".$mVal['reportnumber']."）',";
	            if($mKey == 0){
    	            $memberRanksReport['ydata'] .= '{y:'.$mVal['reportnumber'].',color:"#e2c9ff"},';
	            }elseif ($mKey == 1){
	                $memberRanksReport['ydata'] .= '{y:'.$mVal['reportnumber'].',color:"#c38eff"},';
	            }elseif ($mKey == 2){
	                $memberRanksReport['ydata'] .= '{y:'.$mVal['reportnumber'].',color:"#a85aff"},';
	            }elseif ($mKey == 3){
	                $memberRanksReport['ydata'] .= '{y:'.$mVal['reportnumber'].',color:"#6f1dcc"},';
	            }
	        }
	        if($memberRanksReport){
    	        $memberRanksReport['xdata'] = substr($memberRanksReport['xdata'], 0,-1);
    	        $memberRanksReport['ydata'] = substr($memberRanksReport['ydata'], 0,-1);
    	        $this->assign('memberRanksReport',$memberRanksReport);
	        }
	    }
	    $this->display();
	}
	
}
?>
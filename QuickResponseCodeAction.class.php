<?php
/**
 * 微信二维码管理
 * Enter description here ...
 * @author yaochengkai
 */
class QuickResponseCodeAction extends WapBaseAction{
	
	private $companyid;
	
	public function __construct(){
		parent::__construct();
		$this->companyid = session('wapcid');
	}
	/**
	 * 通过扫描二维码，获得PV值
	 */
	public function index(){
		$data['companyid'] = $where['companyid'] = $this->companyid;
		$data['qid'] = $where['id'] = $this->_get('id');
		$info = M('quick_response_webpage_code')->where($where)->field('id,scannum,content')->find();
		if($info){
			M()->startTrans();
			$return = M('quick_response_webpage_code')->where($where)->setInc('scannum');
			$data['id'] = guidNow();
			$data['createtime'] = time();
			$daytime = strtotime(format_time(time(),'ymd'));
			$quickResponseCodelogcount = M('quick_response_webpage_code_daylog')->where(array('companyid'=>$this->companyid,'qid'=>$where['id'],'day'=>$daytime))->count();
			if($quickResponseCodelogcount>0){
				$daylog = M('quick_response_webpage_code_daylog')->where(array('companyid'=>$this->companyid,'qid'=>$where['id'],'day'=>$daytime))->setInc('scannum');
			}else{
				$qlc['id'] = guidNow();
				$qlc['companyid'] = $this->companyid;
				$qlc['qid'] = $where['id'];
				$qlc['day'] = $daytime;
				$qlc['scannum'] = '1';
				$qlc['updatetime'] = $qlc['createtime'] = time();
				$daylog = M('quick_response_webpage_code_daylog')->add($qlc);
			}
			if($return && $daylog){
				M()->commit();
				redirect(htmlspecialchars_decode($info['content']));
			}else{
				M()->rollback();
				$this->error(L('ServerBusyPrompt'));
			}
		}else{
			$this->error(L('ServerBusyPrompt'));
		}
	}
	/**
	 * 个人中心二维码
	 * @author   Tomas<416369046@qq.com>
	 * @since  2015-11-12
	 */
	public function getq(){
		$this->getQRcode(C('site_url').U('Login/login',array('mid'=>session('mid'.$this->companyid),'companyid'=>$this->companyid)));
	}
}
?>
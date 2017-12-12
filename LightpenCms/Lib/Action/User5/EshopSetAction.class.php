<?php
/**
 * 支付设置
 * 
 * @author    Mark<1311013341@qq.com>
 * @since     2017-1-12
 * @version   1.0
 */
class EshopSetAction extends UserAction{
	
	private $companyid;
	
	private $shopsid;
	
	private $uid;
	
	public function _initialize(){
		parent::_initialize();
		$this->checkCompanyScrm5Permissions(94,TRUE);
		$this->companyid = session('cid');
		$this->shopsid = session('shopsid');
		$this->uid = session('uid');
	}
	/**
	 * 商品品类管理
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-8-7
	 */
	public function index(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'eshop','url'=>'','rel'=>'','target'=>''),array('name'=>'eshop基础设置','url'=>'','rel'=>'','target'=>'')));
		$this->Info = M('company')->where(array('id'=>$this->companyid))->field('id,mallorderautoset,isinvoice,eshopheadlogo')->find();
		$this->display();
	}
	/**
	 * ajax--设置标签
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-8-7
	 */
	public function ajaxSet(){
		$return['code'] = '300';
		$return['tips'] = '网络繁忙，请稍后重试';
		if(IS_POST){
			$where['id'] = $this->companyid;
			$data['mallorderautoset'] = $this->_post('mallorderautoset');
			$data['isinvoice'] = $this->_post('isinvoice');
			$data['eshopheadlogo'] = $this->_post('eshopheadlogo');
			$data['updatetime'] = time();
			$sucEshop = M('company')->where($where)->save($data);
			if($sucEshop){
				$return['code'] = '200';
				$return['tips'] = '保存成功';
			}else{
				$return['code'] = '300';
				$return['tips'] = '保存失败';
			}
		}
		echo json_encode($return);
	}
}
?>
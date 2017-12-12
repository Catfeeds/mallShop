<?php
class SuixiAction extends HomeBaseAction{
	private $usersModel;
	
	private $companyModel;
	
	private $companyGroupModel;
	
	public function __construct(){
		parent::__construct();
		$this->usersModel = M('users');
		$this->companyModel = M('company');
		$this->companyGroupModel = M('company_group');
	}
	/**
	 * 获得 活动 状态
	 */
	public function getInfo(){
		$data['amout'] = M('suixi_activity_amout')->find();
		$data['photo'] = M('suixi_photo_list')->where(array('pid'=>1))->order('sort ASC,id DESC')->find();
		echo json_encode($data);
	}
	/**
	 * 获得 捐款列表
	 */
	public function getDonationList(){
		$callback = $this->_get('callback');
		$data['code'] = 300;
		$data['html'] = '';
		$page = $this->_get('page') ? $this->_get('page') : 1;
		$data['page'] = $page;
		$data['showpage'] = '';
		if($page < 1){
			exit();
		}
		$count = M('suixi_activity_donation')->count();
		$donation = M('suixi_activity_donation')->order('id DESC')->limit(10)->page($page)->select();
		if($donation){
			$data['code'] = 200;
			$data['prevpage'] = $page-1;
			$data['nextpage'] = $page+1;
			$data['showpage'] = $page.'/'.ceil($count/10);
			foreach ($donation as $dKey=>$info){
				$data['html'] .='<ul class="donate donate-list cf"><li>';
				if(strlen($info['name']) > 3){ 
				$data['html'] .=mb_substr($info['name'],0,-3).'*';
				}else{
				$data['html'] .=mb_substr($info['name'],0,-1).'*';
				}
				$data['html'] .='</li><li><span>'.intval($info['amout']).'</span>元</li><li>'.mb_substr($info['mobile'],0,3).'****'.mb_substr($info['mobile'],6).'</li></ul>';
			}
		}
		echo $callback.'('.json_encode($data).')';
	}
	/**
	 * 获得 捐款列表
	 */
	public function getPhotoInfo(){
		$callback = $this->_get('callback');
		$data['code'] = 300;
		$data['src'] = '';
		$day = $this->_get('day') ? $this->_get('day') : 1;
		if($day < 1){
			exit();
		}
		$photo = M('suixi_photo_list')->where(array('pid'=>1))->order('sort ASC,id DESC')->limit(1)->page($day)->select();
		if($photo){
			$data['code'] = 200;
			$data['prevday'] = $day-1;
			$data['nextday'] = $day+1;
			$data['src'] = $photo[0]['picurl'];
			$data['title'] = $photo[0]['title'];
		}
		echo $callback.'('.json_encode($data).')';
	}
	/**
	 * 感恩图片
	 */
	public function getPicture(){
		$callback = $this->_get('callback');
		$data['code'] = 300;
		$data['src'] = '';
		$page = $this->_get('page') ? $this->_get('page') : 1;
		if($page > 0){
			$photo = M('suixi_photo_list')->where(array('pid'=>2))->order('sort ASC,id DESC')->limit(1)->page($page)->select();
			if($photo){
				$data['code'] = 200;
				$data['prevPage'] = $page-1 == 0  ? 1 : $page-1;
				$data['nextPage'] = $page+1;
				$data['title'] = $photo[0]['title'];
				$data['src'] = $photo[0]['picurl'];
			}
		}
		echo $callback.'('.json_encode($data).')';
	}
}
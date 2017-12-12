<?php
/**
 * Home 基本操作
 * Enter description here ...
 * @author yongfei.zhao
 *
 */
class HomeBaseAction extends BaseAction{
	
	protected function _initialize(){
		parent::_initialize();
		//最新官方通告
		//$this->authorityCircular();
		//$this->sat_dunn();
	}
	/**
	 * 映射 页面seo
	 * @param unknown $pageSeo
	 */
	public function setPageSeo($pageSeo){
		if($pageSeo['title']){
			$this->assign('title',$pageSeo['title']);
		}
		if($pageSeo['keywords']){
			$this->assign('keywords',$pageSeo['keywords']);
		}
		if($pageSeo['description']){
			$this->assign('description',$pageSeo['description']);
		}
	}
	//最新官方通告
	public function authorityCircular(){
		$listLink = M('home_list_link')->where(array('companyid'=>'1','listid'=>56))->field('url')->select();
		if($listLink){
			foreach($listLink as $llKey=>$llVal){
				$txt= $llVal['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$infoId=$matches[1][0];
				}
				$info = M('home_info')->where(array('companyid'=>'1','id'=>$infoId))->field('id,title,updatetime')->find();
				$listLink[$llKey]['id'] = $info['id'];
				$listLink[$llKey]['title'] = $info['title'];
				$listLink[$llKey]['updatetime'] = $info['updatetime'];
			}
		}
		if($listLink){
			$listLink = arraySort($listLink,'updatetime');
		}
		$this->assign('link',$listLink);
	}
	public function sat_dunn(){
		//serve弹框
		$cid = M()->table('tp_company')->where(array('id'=>session('cid')))->getField('companyid');
		$list = M()->table('tp_check_customer_info')->where(array('id'=>$cid))->field('aeuser,amuser')->find();
		$aemobile = M()->table('tp_sell_staffs')->where(array('name'=>$list['aeuser']))->field('mobile')->find();
		$ammobile = M()->table('tp_sell_staffs')->where(array('name'=>$list['amuser']))->field('mobile')->find();
		$this->assign('list_dunn',$list);
		$this->assign('aemobile',$aemobile['mobile']);
		$this->assign('ammobile',$ammobile['mobile']);
		
	}
}
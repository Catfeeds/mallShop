<?php
class History_page_browsingModel extends Model{
	
	public function getuid(){
		return session('uid');
	}
	public function getcompanyid(){
		return session('cid');
	}
	public function getshopsid(){
		return session('shopsid');
	}
	/**
	 * 获得 公司 时间段内的浏览量
	 * @param unknown $companyDiningBookWhere
	 */
	public function getComapnyInfoViewNum($companyInfoViewNumWhere){
		$count = M('history_page_browsing')->where($companyInfoViewNumWhere)->count();
		return $count > 0 ? $count : 0 ;
	}
}
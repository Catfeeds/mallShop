<?php
class System_book_timeModel extends Model{
	/**
	 * 获得时间列表
	 * @param unknown $where
	 */
	public function getSystemBookTimeList($where){
		return M('system_book_time')->where($where)->field('id,name,sort,isshow')->order('id ASC')->select();
	}
	/**
	 * 获得 时间详情
	 * @param unknown $where
	 */
	public function getSystemBookTimeInfo($where){
		return M('system_book_time')->where($where)->field('id,name,sort,isshow')->find();
	}
}
<?php
class System_home_template_infoModel extends Model{
	/**
	 * 获得 频道列表列表
	 * @param unknown $where
	 */
	public function getSystemHomeTemplateInfoList($where){
		return M('system_home_template_info')->where($where)->field('id,name,picurl,tplid,desc')->order('sort ASC,id DESC')->select();
	}
	/**
	 * 获得 频道详情
	 * @param unknown $where
	 */
	public function getSystemHomeTemplateInfo($where){
		return M('system_home_template_info')->where($where)->field('id,name,picurl,tplid,desc')->find();
	}
}
<?php
class System_home_template_classModel extends Model{
	/**
	 * 获得 频道列表列表
	 * @param unknown $where
	 */
	public function getSystemHomeTemplateClassList($where){
		return M('system_home_template_class')->where($where)->field('id,name')->order('sort ASC,id DESC')->select();
	}
	/**
	 * 获得 频道详情
	 * @param unknown $where
	 */
	public function getSystemHomeTemplateClassInfo($where){
		return M('system_home_template_class')->where($where)->field('id,name')->find();
	}
}
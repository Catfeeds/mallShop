<?php
class AreaModel extends Model{
	/**
	 * 获得 地区详情
	 * @param unknown $areaid
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getAreaInfo($areaid){
		return M('area')->where(array('id'=>$areaid,'isshow'=>1))->find();
	}
	/**
	 * 获得条件下的 地区列表
	 * @param unknown $where
	 */
	public function getAreaList($where){
		return M('area')->where($where)->field('id,name,parentid,chirldennum,isshow')->order('sort ASC,CONVERT(name USING gbk) ASC')->select();
	}
	
}
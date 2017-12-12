<?php
class Report_member_card_infoModel extends Model{
	/**
	 * 获得公司的注册会员统计
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getReportMemberCardInfo($companyid){
		if($companyid){
			return M('report_member_card_info')->where(array('companyid'=>$companyid))->find();
		}
	}
	
}
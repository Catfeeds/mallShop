<?php
class Report_member_register_infoModel extends Model{
	/**
	 * 获得公司的  会员统计
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getReportMemberRegisterInfo($companyid){
		if($companyid){
			return M('report_member_register_info')->where(array('companyid'=>$companyid))->find();
		}
	}
}
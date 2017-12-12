<?php
class Report_member_wechat_infoModel extends Model{
	/**
	 * 获得公司的粉丝会员统计
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getReportMemberWechatrInfo($companyid){
		if($companyid){
			return M('report_member_wechat_info')->where(array('companyid'=>$companyid))->find();
		}
		
	}
	
}
<?php
class Report_wechat_info_daysModel extends Model{

	/**
	 * 获得 条件公众号的按天的报表信息
	 * @param unknown $companyDiningBookWhere
	 */
	public function getReportWechatDaysInfo($where){
		return M('report_wechat_info_days')->where($where)->find();
	}
}
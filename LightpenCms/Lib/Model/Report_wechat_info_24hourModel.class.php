<?php
class Report_wechat_info_24hourModel extends Model{

	/**
	 * 获得 条件公众号的24小时报表信息
	 * @param unknown $companyDiningBookWhere
	 */
	public function getReportWechatInfoInfo($where){
		return M('report_wechat_info_24hour')->where($where)->find();
	}
}
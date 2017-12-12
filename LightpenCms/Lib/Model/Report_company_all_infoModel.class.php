<?php
class Report_company_all_infoModel extends Model{

	/**
	 * 获得 公司 所有报表信息
	 * @param unknown $companyDiningBookWhere
	 */
	public function getReportCompanyAllInfo($companyid){
		return M('report_company_all_info')->where(array('companyid'=>$companyid))->find();	
	}
}
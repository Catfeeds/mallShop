<?php
class History_wechat_requestModel extends Model{
	/**
	 * 获得指定条件的 数据查询
	 * @param unknown $where
	 */
	public function getWechatRequestNum($where){
		$count =  M('history_wechat_request')->where($where)->count();
		return $count > 0 ? $count : 0 ;
	}
	/**
	 * 判读是否 可二十四小时微信发送
	 * @param string $companyid
	 * @param string $openid
	 */
	public function getMemberWehatRegquestNum($companyid = '',$openid = ''){
		$returnData = '';
		if($companyid && $openid){
			$wechatsList = D('Wechats')->getCompanyWechatss(array('companyid'=>$companyid,'wechattype'=>4));
			if($wechatsList){
				$historyNum = 0;
				foreach ($wechatsList as $wlKey=>$wlVal){
					$historyNum = D('History_wechat_request')->getWechatRequestNum(array('companyid'=>$companyid,'token'=>$wlVal['token'],'FromUserName'=>$openid));
					if($historyNum){
						$returnData[]=array('wxname'=>$wlVal['wxname'],'token'=>$wlVal['token'],'num'=>$historyNum);
					}
				}
			}
		}
		return $returnData;
	}
	/**
	 * 判读是否 可二十四小时微信文本发送
	 * @param string $companyid
	 * @param string $openid
	 */
	public function getMemberWehatRegquestTextNum($companyid = '',$openid = ''){
		$returnData = '';
		if($companyid && $openid){
			$wechatsList = D('Wechats')->getCompanyWechatss(array('companyid'=>$companyid,'wechattype'=>4));
			if($wechatsList){
				$historyNum = 0;
				foreach ($wechatsList as $wlKey=>$wlVal){
					$historyNum = D('History_wechat_request')->getWechatRequestNum(array('companyid'=>$companyid,'token'=>$wlVal['token'],'FromUserName'=>$openid,'MsgType'=>'text','CreateTime'=>array('between',array(strtotime('-1 day'),time()))));
					if($historyNum){
						$returnData[]=array('wxname'=>$wlVal['wxname'],'token'=>$wlVal['token'],'num'=>$historyNum);
					}
				}
			}
		}
		return $returnData;
	}
	
}
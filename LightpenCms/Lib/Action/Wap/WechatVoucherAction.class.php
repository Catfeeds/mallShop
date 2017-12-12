<?php
class WechatVoucherAction extends WapBaseAction{
    private $mid;
	
	private $companyid;
	
	public function __construct(){
		parent::__construct();
		$this->mid = session('mid'.session('wapcid'));
		$this->companyid = session('wapcid');
	}
	/**
	 * 
	 * 微信卡券发放
	 * 
	 * @author Lando<806728685@qq.com>
	 * @since  2015-10-26
	 */
	public function getVoucher(){
	    $agent = $_SERVER['HTTP_USER_AGENT'];
		if(strpos($agent,"MicroMessenger")) {
    	    $wechatsInfo = D('Wechats')->getCompanyWechatsInfo(array('companyid'=>$this->companyid,'wechattype'=>4));
    	    $cardid = $this->_get('cardid');
    	    if($wechatsInfo&&$cardid){
    	        $wechatOptions = array('token'=>$wechatsInfo['token'],'appid'=>$wechatsInfo['appid'],'appsecret'=>$wechatsInfo['appsecret']);
    	        $wechat  = new Wechat($wechatOptions);
    	        $signPackage = $wechat->getJsSign($wechatsInfo['appid']);
    	        if($signPackage){
        	        $apiTicket = $wechat->getJsCardTicket($wechatsInfo['appid']);
        	        if($apiTicket){
        	            $ticketData['timestamp'] = time();
        	            $ticketData['card_id'] = $cardid;
        	            $ticketData['api_ticket'] = $apiTicket;
        	            $ticketData['nonce_str'] = get_order_id();
        	            $jsCardSignature = $wechat->getTicketSignature($ticketData);
        	            if($jsCardSignature){
        	                $this->assign('signPackage',$signPackage);
        	                $chooseCardInfo['code'] = '';
        	                $chooseCardInfo['openid'] = '';
        	                $chooseCardInfo['timestamp'] = $ticketData['timestamp'];
        	                $chooseCardInfo['nonce_str'] = $ticketData['nonce_str'];
        	                $chooseCardInfo['signature'] = $jsCardSignature;
        	                $cardExt = json_encode($chooseCardInfo);
        	                $this->assign('cardExt',$cardExt);
        	                $this->assign('cardId',$cardid);
        	                $this->display();
        	            }else{
            	            $this->redirect(U('System/notFound'));
            	        }
        	        }else{
        	            $this->redirect(U('System/notFound'));
        	        }
    	        }else{
    	            $this->redirect(U('System/notFound'));
    	        }
    	    }else{
    	        $this->redirect(U('System/notFound'));
    	    }
	    }
	}
}

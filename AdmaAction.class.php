<?php
/**
 * 自定义宣传页
 * Enter description here ...
 * @author yongfei.zhao
 *
 */
class AdmaAction extends BaseAction{
	
	/**
	 * 自定义宣传页
	 * Enter description here ...
	 */
	public function index(){
		$token = $this->_get('token');
		if($token){
			$admaInfo=M('Adma')->where(array('token'=>$token))->find();
			if($admaInfo){
				$this->assign('adma',$admaInfo);
				$this->display();
			}else{
				$this->error(L('ServerBusyPrompt'),U('Home/Index/index'));
			}
		}else{
			$this->error(L('ServerBusyPrompt'),U('Home/Index/index'));
		}
		
	}

}
?>
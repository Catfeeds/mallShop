<?php
class testQRCodeAction extends WapBaseAction{
	
	private $mid;
	
	private $companyid;
	
	public function __construct(){
		parent::__construct();
		$this->mid = session('mid'.session('wapcid'));
		$this->companyid = session('wapcid');
	}
	public function index(){
	    $this->display();
	}
	public function getScanQRcode(){
	    $url = urldecode($this->_request('url'));
	    $this->getQRcode($url);
	}
	
	
	/* public function getScanQRcode1(){
	    $url = urldecode($this->_post('url'));
	    
	    echo $url; die();
	    
	    // $urlImg = $this->getQRcode('http://baidu.com');
	    $string = '<img src="';
	    $string .= $url;
	    $string .= '">';
	    $returnData['code'] = 200;
	    $returnData['img'] = $string;
	    
	    echo '<pre/>';  print($returnData); die();
	    
	    echo json_encode($returnData);
	} */
}
?>
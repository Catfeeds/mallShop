<?php
/**
 * 
 * UE框架
 * 
 * @author    Lando<806728685@qq.com>
 * @since     2016-6-14
 * @version   1.0
 */
class StyleAction extends BaseAction{
	
	public function __construct(){
		parent::__construct();
		//$this->checkUserLogin();
		$this->check_url = ACTION_NAME;
	}
	/**
	 * 
	 * UE  框架主页
	 * 
	 * @see UserAction::index()
	 */
	public function index(){
		$this->display();
	}
	public function Typography(){
		$this->display();
	}
	public function Buttons(){
		$this->display();
	}
	public function Hint(){
		$this->display();
	}
	public function Icons(){
		$this->display();
	}
	public function Check(){
		$this->display();
	}
	public function Input(){
		$this->display();
	}
	public function Chart(){
		$this->display();
	}
	public function Popup(){
		$this->display();
	}
	public function Table(){
		$this->display();
	}
	public function TAB(){
		$this->display();
	}
	public function Validation(){
		$this->display();
	}
	public function Common(){
		$this->display();
	}
}
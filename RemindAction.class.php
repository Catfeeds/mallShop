<?php
/**
 * 系统文件
 * Enter description here ...
 * @author yaochengkai
 */
class RemindAction extends BaseAction{
	

	public function __construct(){
		parent::__construct();
		$this->mid = session('mid'.session('wapcid'));
		$this->companyid = session('wapcid');
	}
    public function index()
    {
        $Rinfo = M("member_remind")->where(array("mid"=>$this->mid,"state"=>array("in","0,1")))->find();
        M("member_remind")->where(array("mid"=>$this->mid,"state"=>array("in","0,1")))->save(array("state"=>1,"update_time"=>time()));
        $this->info = M("remind")->where(array("id"=>$Rinfo['remind_id']))->find();
        if($this->info){
            $this->display();
        }else{
            redirect(U("Member/center"));
        }

    }
}
?>
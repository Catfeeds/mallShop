<?php
/**
 * 系统文件
 * Enter description here ...
 * @author yaochengkai
 */
class RemindAction extends UserAction{
	

	public function __construct(){
        parent::_initialize();
        $this->companyid = 1;//session('cid');
	}

    /**
     * 产品使用提醒设置
     */
	public function index()
    {
        if(IS_POST)
        {
            $_POST['update_time'] = time();
            if($id = $this->_post("id")){
                $res = M("remind")->where(array("id"=>$id))->save($_POST);
            }else{
                $_POST['create_time'] = time();
                $res = M("remind")->add($_POST);
            }
            if($res){
                $return['code'] = '200';
                $return['msg'] = '操作成功';
            }else{
                $return['code'] = '300';
                $return['msg'] = 'error';
            }
            echo json_encode($return);
        }else{
            $this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'产品使用提醒')));
            $this->info = M("remind")->find();
            $this->display();
        }
    }

}
?>
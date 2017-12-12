<?php
/**
 * 卡券中心
 * @author    Tomas<416369046@qq.com>
 * @since     2016-11-14
 * @version   1.0
 */
class VouchersAction extends UserAction{
	
	private $uid;
	
	private $companyid;
	
	private $shopsid;
	
	public function __construct(){
		parent::__construct();
		$this->check_url = 'voucherindex';
	}
	/**
	 * 卡券列表
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-11-14
	 */
	public function lists(){
        $this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'优惠券','url'=>U('Vouchers/lists'),'rel'=>'','target'=>''),array('name'=>'优惠券列表','url'=>'','rel'=>'','target'=>'')));
        $where = '';
        if($title = $this->_post("title")){
            $where['title'] = array("like","%".$title."%");
        }
        if($sn = $this->_post("sn")){
            $where['sn'] = $sn;
        }
        if($type = $this->_post("type")){
            $where['type'] = $type;
        }
        $count = M('vouchers')->where($where)->count();
        $page = new NewPage($count,15);
        $list = M("vouchers")->where($where)->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('page',$page->show());
        $this->assign("list",$list);
        $where['title'] = $title;
        $this->assign('where',$where);
        $this->display();
	}

	public function set(){
	    if(IS_POST){
            if($_POST['id']){
                $_POST['update_time'] = time();
                $res = M("vouchers")->where(array("id"=>$_POST['id']))->save($_POST);
            }else{
                if(!$_POST['sn']){
                    $_POST['sn'] = $this->newGetSNCode();
                }
                $_POST['create_time'] = $_POST['update_time'] = time();
                $res = M("vouchers")->add($_POST);
            }
            if($res){
                $ajax['code'] = 200;
                $ajax['msg'] = 'success';
            }
            echo json_encode($ajax);
        }else{
            $this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'优惠券','url'=>U('Vouchers/lists'),'rel'=>'','target'=>''),array('name'=>'创建优惠券','url'=>'','rel'=>'','target'=>'')));
            $id = $this->_get("id");
            if($id){
                $info = M("vouchers")->where(array("id"=>$id))->find();
            }else{
                $info = '';
            }
            $this->assign("info",$info);
            $this->display();
        }
    }

    public function ajaxSetIndex(){
        $_POST['update_time'] = time();
        M("vouchers")->where("1=1")->save(array("is_index"=>0));
        $res = M("vouchers")->where(array("id"=>$_POST['id']))->save($_POST);
        if($res){
            $ajax['code'] = 200;
            $ajax['msg'] = 'success';
        }
        echo json_encode($ajax);
    }

    public function ajaxDel(){
        $res = M("vouchers")->where(array("id"=>$_POST['id']))->delete();
        if($res){
            $ajax['code'] = 200;
            $ajax['msg'] = 'success';
        }
        echo json_encode($ajax);
    }


	/**
	 * 后台卡券核销
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-11-14
	 */
	public function useVouchers(){
		$this->checkCompanyScrm5Permissions(32,TRUE);
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'后台卡券核销','url'=>'','rel'=>'','target'=>'')));
		$this->display();
	}

}
?>
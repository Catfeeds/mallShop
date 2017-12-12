<?php
/**
 * eshop商品标签管理
 * @author    姚成凯<kevin@renlaifeng.cn>
 * @since     2016-8-7
 * @version   1.0
 */
class EshopTagAction extends UserAction{
	
	private $companyid;
	
	private $shopsid;
	
	private $uid;
	
	public function _initialize(){
		parent::_initialize();
		//$this->checkCompanyScrm5Permissions(67,TRUE);
		$this->companyid = session('cid');
		$this->shopsid = session('shopsid');
		$this->uid = session('uid');
	}
	/**
	 * 商品品类管理
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-8-7
	 */
	public function index(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'eshop','url'=>'','rel'=>'','target'=>''),array('name'=>'eshop商品标签管理','url'=>'','rel'=>'','target'=>'')));
		$sorttype = $this->_request('sorttype');
		$sortclass = $this->_request('sortclass');
		if($sorttype == 1){
			if($sortclass=='' || $sortclass==1){
				$sort = 'SORT_ASC';
				$this->assign('sortclass1','2');
			}elseif($sortclass == 2){
				$sort = 'SORT_DESC';
				$this->assign('sortclass1','1');
			}
		}else{
			$order = 'createtime desc';
		}
		$this->assign('sorttype',$sorttype);
		$count = M('eshop_tag')->where(array('companyid'=>$this->companyid))->count();
		if($count){
			$page = new NewPage($count,15);
			$list = M('eshop_tag')->where(array('companyid'=>$this->companyid))->field('id,title')->limit($page->firstRow.','.$page->listRows)->order($order)->select();
			foreach($list as $key=>$val){
				$list[$key]['num'] = M('mall_goods')->where(array('companyid'=>$this->companyid,'tags'=>array('like','%,'.$val['id'].',%')))->count();
			}
			$list = arraySort($list,'num',$sort);
			$this->assign('list',$list);
			$this->assign('page',$page->show());
		}
		$this->display();
	}
	/**
	 * ajax--设置标签
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-8-7
	 */
	public function ajaxSetTag(){
		$return['code'] = 'error';
		$return['tips'] = 'error:500';
		
		$id = $this->_post('id');
		$where['companyid'] = $this->companyid;
		$where['title'] = $this->_post('title');
		if($id){
			$where['id'] = array('neq',$id);
		}
		$titleCou = M('eshop_tag')->where($where)->count();
		if($titleCou){
			$return['code'] = 'warn';
			$return['tips'] = '该标签已存在';
			echo json_encode($return);
			exit();
		}
		if($id){
			$_POST['updatetime'] = time();
			$sucEshop = M('eshop_tag')->where(array('companyid'=>$this->companyid,'id'=>$id))->save($_POST);
		}else{
			$_POST['id'] = guidNow();
			$_POST['companyid'] = $this->companyid;
			$_POST['updatetime'] = $_POST['createtime'] = time();
			$sucEshop = M('eshop_tag')->add($_POST);
		}
		if($sucEshop){
			$return['code'] = 'success';
			$return['tips'] = '操作成功';
		}
		echo json_encode($return);
	}
	/**
	 * ajax--删除标签
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-8-7
	 */
	public function ajaxDelTag(){
		$return['code'] = 'error';
		$return['tips'] = 'error:500';
	
		$id = $this->_post('id');
		$sucAss = M('eshop_tag')->where(array('companyid'=>$this->companyid,'id'=>$id))->delete();
		if($sucAss){
			$return['code'] = 'success';
			$return['tips'] = '操作成功';
		}
		echo json_encode($return);
	}
}
?>
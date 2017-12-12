<?php
/**
 * 我的导出任务
 * @author    姚成凯<kevin@renlaifeng.cn>
 * @since     2015-12-21
 * @version   1.0
 */
class ExportTaskAction extends UserAction{
	
	private $uid;
	
	private $companyid;
	
	private $shopsid;
	
	public function _initialize(){
		parent::_initialize();
		$this->checkCompanyScrm5Permissions(24,TRUE);
		$this->uid = session('uid');
		$this->shopsid = session('shopsid');
		$this->companyid = session('cid');
	}
	/**
	 * 商品管理
	 */
	public function index(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'我的导出任务','url'=>'','rel'=>'','target'=>''),));
		$count = M('export_task')->where(array('companyid'=>$this->companyid))->count();
		$page = new NewPage($count,15);
		$list = M('export_task')->where(array('companyid'=>$this->companyid))->field('id,name,state,downloadpath,createtime,remarkname')->order('createtime DESC')->limit($page->firstRow.','.$page->listRows)->select();
		if($list){  
			foreach($list as $key=>$val){
				if($val['state'] == 1){
					$list[$key]['state'] = '进行中';
				}elseif($val['state'] == 2){
					$list[$key]['state'] = '任务失败，请减少本次下载数据量至1000以内，分批导出';
				}elseif($val['state'] == 3){
					$list[$key]['state'] = '已完成';
				}
			}
		}
		$this->assign('list',$list);
		$this->assign('page',$page->diyshow());
		$this->display();
	}
	/**
	 * 删除任务
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2015-12-21
	 */
	public function del(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$deleteReturn = M('export_task')->where(array('companyid'=>$this->companyid,'id'=>$this->_post('id')))->delete();
			if($deleteReturn){
				$ajax['code'] = '200';
				$ajax['msg'] = '删除成功';
			}else{
				$ajax['msg'] = '删除失败';
			}
		}
		echo json_encode($ajax);
	}
}
?>
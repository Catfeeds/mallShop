<?php
/**
 * 群发
 * Enter description here ...
 * @author GaoWen
 */
class MessageWechatsAction extends WapBaseAction{
	
	private $mid;
	
	private $companyid;
	
	public function __construct(){
		parent::__construct();
		$this->mid = session('mid'.session('wapcid'));
		$this->companyid = session('wapcid');
	}
	/**
	 * 图文详情
	 */
	public function newsInfo(){//http://weixin.lightpen.cn/index.php?g=Wap&m=MessageWechats&a=newsInfo&companyid=1169&id=92
		if($this->_get('id')){
			$where['id'] = $this->_get('id');
			$where['companyid'] = $this->_get('companyid');
			$newsInfo = M('message_wechats_news')->where($where)->field('title,author,thumb_media,digest,show_cover_pic,content,updatetime')->find();
			$this->assign('newsInfo',$newsInfo);
			$this->display();
		}else{
			$this->redirect(U('System/notFound'));
		}
	}
	public function newsInfo2(){//http://weixin.lightpen.cn/index.php?g=Wap&m=MessageWechats&a=newsInfo&companyid=1169&id=92
		if($this->_get('id')){
			$where['id'] = $this->_get('id');
			$where['companyid'] = $this->_get('companyid');
			$newsInfo = M('message_wechats_auto_news')->where($where)->field('title,author,thumb_media,digest,show_cover_pic,content,updatetime')->find();
			$this->assign('newsInfo',$newsInfo);
			$this->display('newsInfo');
		}else{
			$this->redirect(U('System/notFound'));
		}
	}
}
?>
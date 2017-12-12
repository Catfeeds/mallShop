<?php
/**
 * 微信群发管理
 * Enter description here ...
 * @author yongfei.zhao
 *
 */
class MaterialAction extends UserAction{
	private $uid;
	private $companyid;
	private $shopsid;
	public function __construct(){
		parent::__construct();
		$this->uid = session('uid');
		$this->companyid = session('cid');
		$this->shopsid = session('shopsid');
	}
	/**
	 *
	 * 图片
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-27
	 */
	public function images(){
		$this->checkCompanyScrm5Permissions(15,TRUE);
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'素材库','url'=>U('Material/images'),'rel'=>'','target'=>''),array('name'=>'图片','url'=>'','rel'=>'','target'=>'')));
		$where['companyid'] = $groupwhere['companyid'] = $countwhere['companyid'] = $this->companyid;
		/* 分组 */
		if($this->_request('gid')){
			$this->gid = $where['gid'] = $this->_request('gid');
			$this->grouptitle = M('message_locality_images_group')->where(array('companyid'=>$this->companyid,'id'=>$where['gid']))->field('id,title')->find();
		}
		$this->counts = M('message_locality_images')->where($groupwhere)->count();
		$count = M('message_locality_images')->where($where)->count();
		$page = new NewPage($count,15);
		$this->lists = M('message_locality_images')->where($where)->field('id,title,imageurl,gid')->order('updatetime desc')->limit($page->firstRow.','.$page->listRows)->select();
		/* 获取分组 */
		$group = M('message_locality_images_group')->where($groupwhere)->field('id,title')->order('createtime asc')->select();
		foreach ($group as $key=>$val){
			$countwhere['gid'] = $val['id'];
			$group[$key]['count'] = M('message_locality_images')->where($countwhere)->count();
		}
		$this->assign('group',$group);
		$this->assign('page',$page->diyshow());
		$this->display();
	}
	/**
	 *
	 * 移动分组
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-27
	 */
	public function editgroupid(){
		if(IS_POST){
			$where['id'] = array('in',$this->_post('id'));
			$where['companyid'] = $this->companyid;
			$data['gid'] = $this->_post('gid');
			$data['updatetime'] = time();
			$result = M('message_locality_images')->where($where)->save($data);
			if($result){
				$getCont = $this->memcManager('get','Imagelocality'.$this->companyid);
				if($getCont){
					$this->memcManager('delete','Imagelocality'.$this->companyid);
					$this->ImagesAll($this->companyid,'locality');
				}
				$search = 'Imagelocality'.$this->companyid.'search';
				$key = $this->memcManager('get',$search);
				if($key){
					$array = explode('↑↓',$key);
					foreach ($array as $akey=>$aval){
						$this->memcManager('delete',$aval);
					}
					$this->memcManager('delete',$search);
				}
				$ajax['code'] = '200';
			}else{
				$ajax['code'] = '300';
			}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * 修改名字（图片/分组公用）
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-27
	 */
	public function editname(){
		if(IS_POST){
			$where['id'] = $this->_post('id');
			$where['companyid'] = $this->companyid;
			$data['title'] = $this->_post('name');
			$data['updatetime'] = time();
			$type = $this->_post('type');
			if($type == 'group'){
				$dbname = 'message_locality_images_group';
			}elseif($type == 'voice'){
				$dbname = 'message_locality_voices';
			}else{
				$dbname = 'message_locality_images';
			}
			$result = M($dbname)->where($where)->save($data);
			if($result){
				if($type == 'image' || $type == 'group'){
					$getCont = $this->memcManager('get','Imagelocality'.$this->companyid);
					if($getCont){
						$this->memcManager('delete','Imagelocality'.$this->companyid);
						$this->ImagesAll($this->companyid,'wechat');
					}
					$search = 'Imagelocality'.$this->companyid.'search';
					$key = $this->memcManager('get',$search);
					if($key){
						$array = explode('↑↓',$key);
						foreach ($array as $akey=>$aval){
							$this->memcManager('delete',$aval);
						}
						$this->memcManager('delete',$search);
					}
				}
				if($type == 'voice'){
					$getCont = $this->memcManager('get','Voicelocality'.$this->companyid);
					if($getCont){
						$this->memcManager('delete','Voicelocality'.$this->companyid);
						$this->VoiceAll($this->companyid,'locality');
					}
					$search = 'Voicelocality'.$this->companyid.'search';
					$key = $this->memcManager('get',$search);
					if($key){
						$array = explode('↑↓',$key);
						foreach ($array as $akey=>$aval){
							$this->memcManager('delete',$aval);
						}
						$this->memcManager('delete',$search);
					}
				}
				$ajax['code'] = '200';
			}else{
				$ajax['code'] = '400';
			}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * 删除分组
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-27
	 */
	public function delgroup(){
		if(IS_POST){
			M()->startTrans();
			$where['id'] = $cwhere['gid'] = $this->_post('gid');
			$where['companyid'] = $cwhere['companyid'] = $this->companyid;
			$result = M('message_locality_images_group')->where($where)->delete();
			if($result){
				$count = M('message_locality_images')->where($cwhere)->count();
				if($count>0){
					$save = M('message_locality_images')->where($cwhere)->save(array('gid'=>'0','updatetime'=>time()));
					if($save){
						M()->commit();
						$getCont = $this->memcManager('get','Imagelocality'.$this->companyid);
						if($getCont){
							$this->memcManager('delete','Imagelocality'.$this->companyid);
							$this->ImagesAll($this->companyid,'locality');
						}
						$search = 'Imagelocality'.$this->companyid.'search';
						$key = $this->memcManager('get',$search);
						if($key){
							$array = explode('↑↓',$key);
							foreach ($array as $akey=>$aval){
								$this->memcManager('delete',$aval);
							}
							$this->memcManager('delete',$search);
						}
						$ajax['code'] = '200';
					}else{
						M()->rollback();
						$ajax['code'] = '300';
					}
				}else{
					M()->commit();
					$ajax['code'] = '200';
				}
			}else{
				M()->rollback();
				$ajax['code'] = '300';
			}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * 新建分组
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-27
	 */
	public function addgroup(){
		if(IS_POST){
			$data['id'] = guidNow();
			$data['companyid'] = $this->companyid;
			$data['title'] = $this->_post('name');
			$data['updatetime'] = $data['createtime'] = time();
			$result = M('message_locality_images_group')->add($data);
			if($result){
				$getCont = $this->memcManager('get','Imagelocality'.$this->companyid);
				if($getCont){
					$this->memcManager('delete','Imagelocality'.$this->companyid);
					$this->ImagesAll($this->companyid,'locality');
				}
				$search = 'Imagelocality'.$this->companyid.'search';
				$key = $this->memcManager('get',$search);
				if($key){
					$array = explode('↑↓',$key);
					foreach ($array as $akey=>$aval){
						$this->memcManager('delete',$aval);
					}
					$this->memcManager('delete',$search);
				}
				$ajax['code'] = '200';
			}else{
				$ajax['code'] = '300';
			}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * 音频
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-27
	 */
	public function voices(){
		$this->checkCompanyScrm5Permissions(15,TRUE);
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'素材库','url'=>U('Material/images'),'rel'=>'','target'=>''),array('name'=>'音频','url'=>'','rel'=>'','target'=>'')));
		$where['companyid'] = $this->companyid;
		if($this->_request('title')){
			$where['title'] = array('like','%'.$this->_request('title').'%');
			$this->title = $this->_request('title');
		}
		$count = M('message_locality_voices')->where($where)->count();
		$page = new NewPage($count,15);
		$this->lists = M('message_locality_voices')->where($where)->field('id,title,voicesurl,time,size')->order('updatetime desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->diyshow());
		$this->display();
	}
	/**
	 * 素材存库
	 * @author 徐建鹏
	 * @since  2017-10-31
	 */
	public function imgUrl(){
		if(IS_POST){
			$type = $this->_post('type');
			$url = $this->_post('url');
			$data['title'] = $this->_post('title');
			$ajax['id'] = $data['id'] = guidNow();
			$data['companyid'] = $this->companyid;
			if($type == 'image'){
				$data['gid'] = $this->_post('gid')?$this->_post('gid'):'0';
				$data['imageurl'] = $url;
				$dbname = 'message_locality_images';
			}
			$data['updatetime'] = $data['createtime'] = time();
			$ajax['updatetime'] = format_time($data['updatetime'],'ymd');
			$result = M($dbname)->add($data);
			if($result){
				if($type == 'image'){
					$this->ImagesAll($this->companyid,'locality');
				}
				$ajax['code'] = '200';
			}else{
				$ajax['code'] = '300';
			}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * 素材删除公用
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-27
	 */
	public function delimg(){
		if(IS_POST){
			$type = $this->_post('type');
			$where['id'] = array('in',$this->_post('id'));
			$where['companyid'] = $wheres['companyid'] = $this->companyid;
			if($type == 'image'){
				$dbname = 'message_locality_images';
			}elseif($type == 'voice'){
				$dbname = 'message_locality_voices';
			}
			$result = M($dbname)->where($where)->delete();
			if($result){
				if($type == 'image'){
					$getCont = $this->memcManager('get','Imagelocality'.$this->companyid);
					if($getCont){
						$this->memcManager('delete','Imagelocality'.$this->companyid);
						$this->ImagesAll($this->companyid,'locality');
					}
					$search = 'Imagelocality'.$this->companyid.'search';
					$key = $this->memcManager('get',$search);
					if($key){
						$array = explode('↑↓',$key);
						foreach ($array as $akey=>$aval){
							$this->memcManager('delete',$aval);
						}
						$this->memcManager('delete',$search);
					}
				}
				if($type == 'voice'){
					$getCont = $this->memcManager('get','Voicelocality'.$this->companyid);
					if($getCont){
						$this->memcManager('delete','Voicelocality'.$this->companyid);
						$this->VoiceAll($this->companyid,'locality');
					}
					$search = 'Voicelocality'.$this->companyid.'search';
					$key = $this->memcManager('get',$search);
					if($key){
						$array = explode('↑↓',$key);
						foreach ($array as $akey=>$aval){
							$this->memcManager('delete',$aval);
						}
						$this->memcManager('delete',$search);
					}
				}
				$ajax['code'] = '200';
			}else{
				$ajax['code'] = '300';
			}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
}
?>
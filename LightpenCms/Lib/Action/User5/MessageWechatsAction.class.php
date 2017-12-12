<?php
/**
 * 微信群发管理
 * Enter description here ...
 * @author yongfei.zhao
 *
 */
class MessageWechatsAction extends UserAction{
	private $uid;
	private $companyid;
	private $shopsid;
	public  $wechatsModel;
	private $messageWechatsModel;
	private $messageWechatsNewsModel;
	private $messageWechatsTextModel;
	private $memberGroupModel;
	private $memberRegisterInfoModel;
	public function __construct(){
		parent::__construct();
		$this->uid = session('uid');
		$this->companyid = session('cid');
		$this->shopsid = session('shopsid');
		$this->wechatsModel = D('Wechats');
		$this->messageWechatsModel = D('Message_wechats');
		$this->messageWechatsNewsModel = D('Message_wechats_news');
		$this->messageWechatsTextModel = D('Message_wechats_text');
		$this->memberGroupModel = D('Member_group');
		$this->memberRegisterInfoModel = D('Member_register_info');
	}
	/**
	 *
	 * 音频筛选（公用方法都可调用）
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-3
	 */
	public function videosearch(){
		if(IS_POST){
			if($this->_post('title')){
				$where['title'] = $memc['title'] = array('like','%'.$this->_post('title').'%');
			}
			$where['companyid'] = $this->companyid;
			$memcname = 'Video'.$this->companyid.json_encode($memc);
			$getCont = $this->memcManager('get',$memcname);
			if($getCont){
				$ajax['code'] = '200';
				$Lists = $getCont;
			}else{
				$where['token'] = M('wechats')->where(array('companyid'=>$this->companyid,'wechattype'=>'4'))->getField('token');
				$Lists = M('message_wechats_videos')->where($where)->field('id,title,videosurl,createtime,updatetime')->order('updatetime desc')->select();
				if($Lists){
					$this->memcManager('set',$memcname,$Lists,86400);
					$search = 'Video'.$this->companyid.'search';
					$key = $this->memcManager('get',$search);
					if($key){
						if(!in_array($memcname,$key)){
							$this->memcManager('append',$search,'↑↓'.$memcname);
						}
					}else{
						$this->memcManager('set',$search,'↑↓'.$memcname,86400);
					}
				}
				$ajax['code'] = '200';
			}
			if($Lists){
				$ajax['html'] = '';
				foreach($Lists as $lkey=>$lval){
					$ajax['html'] .= '<div class="inner-box inline add-file-wrap js-add-file-wrap">';
					$ajax['html'] .= '<div class="js-add-file clickvideo" data-videoid="'.$lval['id'].'">';
					$ajax['html'] .= '<div class="add-video">';
					$ajax['html'] .= '<video src="'.$lval['videosurl'].'" height="116" width="230">您的浏览器不支持视频播放。</video>';
					$ajax['html'] .= '</div>';
					$ajax['html'] .= '<h5 class="add-tit">'.$lval['title'].'</h5>';
					$ajax['html'] .= '<h5 class="add-tit-date">'.format_time($lval['updatetime'],'ymd').'</h5>';
					$ajax['html'] .= '</div><div class="add-file-cover-wrap js-add-file-cover-wrap"><img class="add-img-cover" src="./Tpl/User5/default/common/img/add-pic-img-cover.png"></div></div>';
				}
			}else{
				$ajax['html'] = '';
			}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * 音频筛选（公用方法都可调用）
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-3
	 */
	public function voicesearch(){
		if(IS_POST){
			if($this->_post('title')){
				$where['title'] = $memc['title'] = array('like','%'.$this->_post('title').'%');
			}
			$where['companyid'] = $this->companyid;
			$type = $this->_post('type');
			$memcname = 'Voice'.$type.$this->companyid.json_encode($memc);
			$getCont = $this->memcManager('get',$memcname);
			if($getCont){
				$ajax['code'] = '200';
				$Lists = $getCont;
			}else{
				if($type == 'wechat'){
					$where['token'] = M('wechats')->where(array('companyid'=>$this->companyid,'wechattype'=>'4'))->getField('token');
					$dbimgname = M('message_wechats_voices');
				}else{
					$dbimgname = M('message_locality_voices');    //暂无数据库
				}
				$Lists = $dbimgname->where($where)->field('id,title,token,voicesurl,time,size,createtime,updatetime')->order('updatetime desc')->select();
				if($Lists){
					$this->memcManager('set',$memcname,$Lists,86400);
					$search = 'Voice'.$type.$this->companyid.'search';
					$key = $this->memcManager('get',$search);
					if($key){
						if(!in_array($memcname,$key)){
							$this->memcManager('append',$search,'↑↓'.$memcname);
						}
					}else{
						$this->memcManager('set',$search,'↑↓'.$memcname,86400);
					}
				}
				$ajax['code'] = '200';
			}
			if($Lists){
				$ajax['html'] = '';
				foreach($Lists as $lkey=>$lval){
					$ajax['html'] .='<label class="audio-item radio inline">';
					$ajax['html'] .='<input type="radio" name="audio-item" value="'.$lval['id'].'"> ';
					$ajax['html'] .='<span>';
					$ajax['html'] .='<span class="audio-meta audio-title w350">'.$lval['title'].'</span> ';
					$ajax['html'] .='<span class="audio-meta audio-date w120">'.format_time($lval['updatetime'],'ymd').'</span> ';
					$ajax['html'] .='<span class="audio-meta audio-length w120">'.$lval['time'].'</span> ';
					$ajax['html'] .='<span class="audio-meta"><i class="icon-menu-audio js-icon-menu-audios"></i><audio class="aa" src="'.$lval['voicesurl'].'">您的浏览器不支持播放音频</audio></span> ';
					$ajax['html'] .='</span> ';
					$ajax['html'] .='</label>';
				}
			}else{
				$ajax['html'] = '';
			}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
	/**
	 * 
	 * 图文筛选（公用方法都可调用）
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-3
	 */
	public function textsearch(){
		if(IS_POST){
			$content = $this->_post('title');
			if($content){
				$map['newsdigest'] = array('like','%'.$content.'%');
				$map['newstitle']  = array('like','%'.$content.'%');
				$map['_logic'] = 'or';
				$where['_complex'] = $memc['_complex'] = $map;
			}
			$where['companyid'] = $this->companyid;
    		$Lists = M('message_wechats_manynews')->where($where)->field('id,newsid,newsnum')->order("updatetime DESC")->select();
			foreach($Lists as $key=>$val){
				if($val['newsid'] != ''){
					$data['id'] = array('in',$val['newsid']);
					$Lists[$key]['news'] = M('message_wechats_news')->where($data)->field('id,title,thumb_media,digest,updatetime')->order('sort asc')->select();
				}
			}
	    	if($Lists){
				$ajax['code'] = '200';
	    		$ajax['html'] = '';
	    		foreach($Lists as $lkey=>$lval){
	    			$ajax['html'] .= '<div class="inner-box inline fl add-file-wrap js-add-file-wrap clickimgtext" data-itextid="'.$lval['id'].'">';
	    			$ajax['html'] .= '<div class="js-add-file">';
	    			$ajax['html'] .= '<h5 class="add-tit-date">'.format_time($lval['news'][0]['updatetime'],'ymdhis').'</h5>';
	    			$ajax['html'] .= '<div class="add-pic">';
	    			$ajax['html'] .= '<i class="add-pic-img" style="background-image:url('.$lval['news'][0]['thumb_media'].')"></i>';
	    			$ajax['html'] .= '<h3><span>'.emoji_decode($lval['news'][0]['title']).'</span></h3>';
	    			$ajax['html'] .= '</div>';
	    			if($lval['newsnum'] > 1){
	    				foreach($lval['news'] as $nKey=>$nVal){
	    					if($nKey > 0){
	    						$ajax['html'] .= '<div class="L2-pic-msg">';
	    						$ajax['html'] .= '<h4><span>'.emoji_decode($nVal['title']).'</span></h4>';
	    						$ajax['html'] .= '<img src="'.$nVal['thumb_media'].'">';
	    						$ajax['html'] .= '</div>';
	    					}
	    				}
	    			}
	    			$ajax['html'] .= '</div>';
	    			$ajax['html'] .= '<div class="add-file-cover-wrap js-add-file-cover-wrap">';
	    			$ajax['html'] .= '<img class="add-img-cover" src="./Tpl/User5/default/common/img/add-pic-img-cover.png">';
	    			$ajax['html'] .= '</div></div>';
	    		}
	    	}else{
	    		$ajax['code'] = '200';
	    		$ajax['html'] = '';
	    	}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * 图片筛选（公用方法都可调用）
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-3
	 */
	public function imgsearch(){
		if(IS_POST){
			if($this->_post('id')){
				$where['gid'] = $memc['gid'] = $this->_post('id');
			}
			if($this->_post('title')){
				$where['title'] = $memc['title'] = array('like','%'.$this->_post('title').'%');
			}
			$where['companyid'] = $this->companyid;
			$type = $this->_post('type');
			$memcname = 'Image'.$type.$this->companyid.json_encode($memc);
			$getCont = $this->memcManager('get',$memcname);
			if($getCont){
				$ajax['code'] = '200';
				$Lists = $getCont;
			}else{
				if($type == 'wechat'){
					$where['token'] = M('wechats')->where(array('companyid'=>$this->companyid,'wechattype'=>'4'))->getField('token');
					$dbimgname = M('message_wechats_images');
				}else{
					$dbimgname = M('message_locality_images');    //暂无数据库
				}
				$Lists = $dbimgname->where($where)->field('id,title,media_id,token,gid,imageurl,createtime,updatetime')->order('updatetime desc')->select();
				if($Lists){
					$this->memcManager('set',$memcname,$Lists,86400);
					$search = 'Image'.$type.$this->companyid.'search';
					$key = $this->memcManager('get',$search);
					if($key){
						if(!in_array($memcname,$key)){
							$this->memcManager('append',$search,'↑↓'.$memcname);
						}
					}else{
						$this->memcManager('set',$search,'↑↓'.$memcname,86400);
					}
				}
				$ajax['code'] = '200';
			}
			if($Lists){
				$ajax['html'] = '';
				foreach($Lists as $lkey=>$lval){
					$ajax['html'] .= '<div class="inner-box inline add-file-wrap js-add-file-wrap">';
					$ajax['html'] .= '<div class="js-add-file clickimg" data-imgid="'.$lval['id'].'">';
					$ajax['html'] .= '<img class="add-img" src="'.$lval['imageurl'].'">';
					$ajax['html'] .= '<h5 class="add-tit">'.$lval['title'].'</h5>';
					$ajax['html'] .= '</div><div class="add-file-cover-wrap js-add-file-cover-wrap">';
					$ajax['html'] .= '<img class="add-img-cover" src="./Tpl/User5/default/common/img/add-pic-img-cover.png">';
					$ajax['html'] .= '</div></div>';
				}
			}else{
				$ajax['html'] = '';
			}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
	/**
	 * 
	 * 图文
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-27
	 */
	public function imageText(){
		$this->checkCompanyScrm5Permissions(11,TRUE);
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'我的API接入','url'=>U('Wechat/lists'),'rel'=>'','target'=>''),array('name'=>'微信素材库','url'=>U('MessageWechats/imageText'),'rel'=>'','target'=>''),array('name'=>'图文消息','url'=>'','rel'=>'','target'=>'')));
		$wechatInfo = $this->wechatsModel->where(array('companyid'=>$this->companyid,'wechattype'=>'4'))->getField('token');
		$data['companyid'] = $where['companyid'] = $this->companyid;
		$content = $this->_request('title');
		if($content){
			$map['newsdigest'] = array('like','%'.$content.'%');
			$map['newstitle']  = array('like','%'.$content.'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
		}
		$this->assign('content',$content);
		$count = M('message_wechats_manynews')->where($where)->count();
		$page = new NewPage($count,15);
		$manylist = M('message_wechats_manynews')->where($where)->field('id,newsid,newsnum')->order("updatetime DESC")->limit($page->firstRow.','.$page->listRows)->select();
		foreach($manylist as $key=>$val){
			if($val['newsid'] != ''){
				$data['id'] = array('in',$val['newsid']);
				$manylist[$key]['news'] = M('message_wechats_news')->where($data)->field('id,title,thumb_media,digest,updatetime')->order('sort asc')->select();
				if($manylist[$key]['news']){
					$this->assign('page',$page->diyshow());
					$this->assign('manylist',$manylist);
				}else{
					$this->assign('page','');
					$this->assign('manylist','');
				}
			}
		}
		$this->display();
	}
	/**
	 *
	 * 编辑页面删除图文
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-3
	 */
	public function delnews(){
		if(IS_POST){
			$where['id'] = $this->_post('newsid');
			$data['id'] = $this->_post('m_id');
			$data['companyid'] = $where['companyid'] = $this->companyid;
			M()->startTrans();
			$delete = M('message_wechats_news')->where($where)->delete();
			if($delete){
				$manyInfo = M('message_wechats_manynews')->where($data)->field('id,newsid,newsnum')->find();
				if(str_replace($this->_post('newsid').',', '', $manyInfo['newsid'])){
					$saveid['newsid'] = str_replace($this->_post('newsid').',', '', $manyInfo['newsid']);
				}elseif (str_replace(','.$this->_post('newsid'), '', $manyInfo['newsid'])){
					$saveid['newsid'] = str_replace(','.$this->_post('newsid'), '', $manyInfo['newsid']);
				}
				$saveid['newsnum'] = $manyInfo['newsnum']-1;
				$save = M('message_wechats_manynews')->where(array('companyid'=>$this->companyid,'id'=>$manyInfo['id']))->save($saveid);
				if($delete && $manyInfo && $save){
					M()->commit();
					$ajaxReturn['code'] = '200';
				}else{
					M()->rollback();
				}
			}else{
				M()->rollback();
			}
		}
		echo json_encode($ajaxReturn);
	}
	/**
	 *
	 * 删除图文
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-3
	 */
	public function manyNewsDel(){
		$id = $this->_post('id');
		if($id){
			$manynewsInfo = M('message_wechats_manynews')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('id,newsid')->find();
			M()->startTrans();
			if($manynewsInfo){
				$newsInfo = M('message_wechats_news')->where(array('companyid'=>$this->companyid,'id'=>array('in',$manynewsInfo['newsid'])))->delete();
				$newsReturn = M('message_wechats_manynews')->where(array('companyid'=>$this->companyid,'id'=>$id))->delete();
				if($newsReturn && $newsInfo){
					M()->commit();
					$ajax['code'] = '200';
				}else{
					M()->rollback();
					$ajax['code'] = '300';
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
	 *	创建图文
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-2
	 */
	public function manyNewsSet(){
		$wechatInfo = $this->wechatsModel->where(array('companyid'=>$this->companyid,'wechattype'=>'4'))->getField('token');
		if (IS_POST){
			$newsAll = json_decode(htmlspecialchars_decode($_POST['jsons']), true);
			$newsNum = count($newsAll);
			M()->startTrans();
			$many['companyid'] =  $data['companyid'] = $this->companyid;
			$addNew = '';
			$insterNum = 0;
			$newstitle = '';
			$newsdigest = '';
			foreach($newsAll as $key=>$vo){
				$insterNum++;
				//添加
				$saves['token'] = $data['token'] = $wechatInfo;
				$saves['title'] = $data['title'] = emoji_encode(htmlspecialchars($vo['title']));
				$saves['content'] = $data['content'] = htmlspecialchars($vo['content']);
				$saves['digest'] = $data['digest'] = htmlspecialchars($vo['digest']);
				$saves['thumb_media'] = $data['thumb_media'] = $vo['src'];
				$saves['sort'] = $data['sort'] = $insterNum;
				$saves['updatetime'] = $saves['createtime'] = $data['updatetime'] = $data['createtime'] = time();
				if($vo['id'] == ''){
					$addNew = $data['id'] = guidNow();
					$result = M('message_wechats_news')->add($data);
				}else{
					$result = M('message_wechats_news')->where(array('companyid'=>$this->companyid,'id'=>$vo['id']))->save($saves);
					$addNew = $vo['id'];
				}
				if($result){
					$ids = $addNew.','.$ids;
					$newstitle = $vo['title'].';'.$newstitle;
					$newsdigest = $vo['digest'].';'.$newsdigest;
					$addNew = '';
				}
			}
			if($newsNum != $insterNum){
				M()->rollback();
				$ajaxReturn['msg'] = '保存失败';
			}else{
				$many['token'] = $wechatInfo;
				$many['newstitle'] = $newstitle;
				$many['newsdigest'] = $newsdigest;
				$many['newsid'] = substr($ids,0,-1);
				$many['newsnum'] = $newsNum;
				$many['createtime'] = time();
				if($this->_post('type') == 'add'){
					$many['updatetime'] = $many['createtime'];
					$many['id'] = guidNow();
					$addManyre = M('message_wechats_manynews')->add($many);
					if($addManyre){
						$Many = $many['id'];
					}
				}elseif ($this->_post('type') == 'save'){
					$Many = M('message_wechats_manynews')->where(array('companyid'=>$this->companyid,'id'=>$this->_post('manyid')))->save($many);
				}
				if($ids && $Many){
					$ajaxReturn['code'] = '200';
					$ajaxReturn['msg'] = '保存成功';
					M()->commit();
				}else{
					$ajaxReturn['msg'] = '保存失败';
					M()->rollback();
				}
			}
			echo json_encode($ajaxReturn);
		}else{
			$this->checkCompanyScrm5Permissions(11,TRUE);
			$wechat = $this->wechatsModel->getCompanyWechatsInfo(array('companyid'=>$this->companyid,'token'=>$wechatInfo));
			$this->assign('info',$wechat);
			$this->ImagesAll($this->companyid,'wechat');
			if($this->_get('id')){
				$where['id'] = $this->_get('id');
				$data['companyid'] = $where['companyid'] = $this->companyid;
				$manyslist = M('message_wechats_manynews')->where($where)->field('id,newsid')->find();
				$this->assign('manyid',$manyslist['id']);
				$data['id'] = array('in',$manyslist['newsid']);
				$list = M('message_wechats_news')->where($data)->field('id,title,thumb_media,content,digest,updatetime')->order('sort asc')->select();
				if($list){
					$this->assign('list',$list);
				}else{
					$this->assign('list','');
				}
				$title = '修改图文消息';
				$this->assign('type','save');
			}else{
				$title = '新建图文消息';
				$this->assign('type','add');
				$this->assign('manyid','');
			}
			$this->assign('title',$title);
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'我的API接入','url'=>U('Wechat/lists'),'rel'=>'','target'=>''),array('name'=>'微信素材库','url'=>U('MessageWechats/imageText'),'rel'=>'','target'=>''),array('name'=>'图文消息','url'=>'','rel'=>'','target'=>'')));
			$this->display();
		}
	}
	/**
	 *
	 * 图片
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-27
	 */
	public function images(){
		$this->checkCompanyScrm5Permissions(11,TRUE);
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'我的API接入','url'=>U('Wechat/lists'),'rel'=>'','target'=>''),array('name'=>'微信素材库','url'=>U('MessageWechats/imageText'),'rel'=>'','target'=>''),array('name'=>'图片','url'=>'','rel'=>'','target'=>'')));
		$where['token'] = $groupwhere['token'] = $countwhere['token'] = $this->wechatsModel->where(array('companyid'=>$this->companyid,'wechattype'=>'4'))->getField('token');
		$where['companyid'] = $groupwhere['companyid'] = $countwhere['companyid'] = $this->companyid;
		/* 分组 */
		if($this->_request('gid')){
			$this->gid = $where['gid'] = $this->_request('gid');
			$this->grouptitle = M('message_wechats_images_group')->where(array('companyid'=>$this->companyid,'token'=>$where['token'],'id'=>$where['gid']))->field('id,title')->find();
		}
		$this->counts = M('message_wechats_images')->where($groupwhere)->count();
		$count = M('message_wechats_images')->where($where)->count();
		$page = new NewPage($count,15);
		$this->lists = M('message_wechats_images')->where($where)->field('id,title,imageurl,gid')->order('updatetime desc')->limit($page->firstRow.','.$page->listRows)->select();
		/* 获取分组 */
		$group = M('message_wechats_images_group')->where($groupwhere)->field('id,title')->order('createtime asc')->select();
		foreach ($group as $key=>$val){
			$countwhere['gid'] = $val['id'];
			$group[$key]['count'] = M('message_wechats_images')->where($countwhere)->count();
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
			$result = M('message_wechats_images')->where($where)->save($data);
			if($result){
				$getCont = $this->memcManager('get','Imagewechat'.$this->companyid);
				if($getCont){
					$this->memcManager('delete','Imagewechat'.$this->companyid);
					$this->ImagesAll($this->companyid,'wechat');
				}
				$search = 'Imagewechat'.$this->companyid.'search';
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
				$dbname = 'message_wechats_images_group';
			}elseif($type == 'voice'){
				$dbname = 'message_wechats_voices';
			}elseif($type == 'video'){
				$dbname = 'message_wechats_videos';
			}else{
				$dbname = 'message_wechats_images';
			}
			$result = M($dbname)->where($where)->save($data);
			if($result){
				if($type == 'image' || $type == 'group'){
					$getCont = $this->memcManager('get','Imagewechat'.$this->companyid);
					if($getCont){
						$this->memcManager('delete','Imagewechat'.$this->companyid);
						$this->ImagesAll($this->companyid,'wechat');
					}
					$search = 'Imagewechat'.$this->companyid.'search';
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
					$getCont = $this->memcManager('get','Voicewechat'.$this->companyid);
					if($getCont){
						$this->memcManager('delete','Voicewechat'.$this->companyid);
						$this->VoiceAll($this->companyid,'wechat');
					}
					$search = 'Voicewechat'.$this->companyid.'search';
					$key = $this->memcManager('get',$search);
					if($key){
						$array = explode('↑↓',$key);
						foreach ($array as $akey=>$aval){
							$this->memcManager('delete',$aval);
						}
						$this->memcManager('delete',$search);
					}
				}
				if($type == 'video'){
					$getCont = $this->memcManager('get','Videowechat'.$this->companyid);
					if($getCont){
						$this->memcManager('delete','Videowechat'.$this->companyid);
						$this->VideoAll($this->companyid,'wechat');
					}
					$search = 'Videowechat'.$this->companyid.'search';
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
			$result = M('message_wechats_images_group')->where($where)->delete();
			if($result){
				$count = M('message_wechats_images')->where($cwhere)->count();
				if($count>0){
					$save = M('message_wechats_images')->where($cwhere)->save(array('gid'=>'0','updatetime'=>time()));
					if($save){
						M()->commit();
						$getCont = $this->memcManager('get','Imagewechat'.$this->companyid);
						if($getCont){
							$this->memcManager('delete','Imagewechat'.$this->companyid);
							$this->ImagesAll($this->companyid,'wechat');
						}
						$search = 'Imagewechat'.$this->companyid.'search';
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
			$result = M('message_wechats_images_group')->add($data);
			if($result){
				$getCont = $this->memcManager('get','Imagewechat'.$this->companyid);
				if($getCont){
					$this->memcManager('delete','Imagewechat'.$this->companyid);
					$this->ImagesAll($this->companyid,'wechat');
				}
				$search = 'Imagewechat'.$this->companyid.'search';
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
		$this->checkCompanyScrm5Permissions(11,TRUE);
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'我的API接入','url'=>U('Wechat/lists'),'rel'=>'','target'=>''),array('name'=>'微信素材库','url'=>U('MessageWechats/imageText'),'rel'=>'','target'=>''),array('name'=>'音频','url'=>'','rel'=>'','target'=>'')));
		$where['token'] = $this->wechatsModel->where(array('companyid'=>$this->companyid,'wechattype'=>'4'))->getField('token');
		$where['companyid'] = $this->companyid;
		if($this->_request('title')){
			$where['title'] = array('like','%'.$this->_request('title').'%');
			$this->title = $this->_request('title');
		}
		$count = M('message_wechats_voices')->where($where)->count();
		$page = new NewPage($count,15);
		$this->lists = M('message_wechats_voices')->where($where)->field('id,title,voicesurl,time,size')->order('updatetime desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->diyshow());
		$this->display();
	}
	/**
	 *
	 * 视频
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-27
	 */
	public function videos(){
		$this->checkCompanyScrm5Permissions(11,TRUE);
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'我的API接入','url'=>U('Wechat/lists'),'rel'=>'','target'=>''),array('name'=>'微信素材库','url'=>U('MessageWechats/imageText'),'rel'=>'','target'=>''),array('name'=>'视频','url'=>'','rel'=>'','target'=>'')));
		$where['token'] = $this->wechatsModel->where(array('companyid'=>$this->companyid,'wechattype'=>'4'))->getField('token');
		$where['companyid'] = $this->companyid;
		if($this->_request('title')){
			$where['title'] = array('like','%'.$this->_request('title').'%');
			$this->title = $this->_request('title');
		}
		$count = M('message_wechats_videos')->where($where)->count();
		$page = new NewPage($count,15);
		$this->lists = M('message_wechats_videos')->where($where)->field('id,title,videosurl,size,createtime')->order('updatetime desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->diyshow());
		$this->display();
	}
	/**
	 *
	 * 素材存库
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-27
	 */
	public function imgUrl(){
		if(IS_POST){
			$wechatInfo = $this->wechatsModel->where(array('companyid'=>$this->companyid,'wechattype'=>'4'))->field('token,appid,appsecret')->find();
			if($wechatInfo){
				$wechatOptions = array('token'=>$wechatInfo['token'],'appid'=>$wechatInfo['appid'],'appsecret'=>$wechatInfo['appsecret']);
				$wechat  = new Wechat($wechatOptions);
				$type = $this->_post('type');
				$url = $this->_post('url');
				$filePaht = Project_Dir.$url;
				$data['title'] = $this->_post('title');
				if($type == 'video'){
					$mediaData = $wechat->uploadForeverMedia($filePaht,$type,true,array('title'=>$data['title']));
				}else{
					$mediaData = $wechat->uploadForeverMedia($filePaht,$type);
				}
				if($type == 'voice' && $mediaData['errcode'] == '45007'){
					$ajax['code'] = '300';
					$ajax['msg'] = '音频长度不能超过60秒';
				}else{
					if($mediaData['media_id']){
						$data['media_id'] = $mediaData['media_id'];
						$ajax['id'] = $data['id'] = guidNow();
						$data['companyid'] = $this->companyid;
						$data['token'] = $wechatInfo['token'];
						if($type == 'image'){
							$data['gid'] = $this->_post('gid')?$this->_post('gid'):'0';
							$data['imageurl'] = $url;
							$dbname = 'message_wechats_images';
						}elseif($type == 'voice'){
							$data['voicesurl'] = $url;
							$dbname = 'message_wechats_voices';
							include "./LightpenCms/Lib/ORG/getid/getid3.php";
							$getID3 = new getID3;
							$radio = realpath('.'.$url);
							$ThisFileInfo = $getID3->analyze($radio);
							$ajax['time'] = $data['time'] = $ThisFileInfo['playtime_string'];
							$ajax['size'] = $data['size'] = getRealSize($ThisFileInfo['filesize']);
						}elseif($type == 'video'){
							$data['videosurl'] = $url;
							$dbname = 'message_wechats_videos';
							include "./LightpenCms/Lib/ORG/getid/getid3.php";
							$getID3 = new getID3;
							$radio = realpath('.'.$url);
							$ThisFileInfo = $getID3->analyze($radio);
							$data['size'] = getRealSize($ThisFileInfo['filesize']);
						}
						$data['updatetime'] = $data['createtime'] = time();
						$ajax['updatetime'] = format_time($data['updatetime'],'ymd');
						$result = M($dbname)->add($data);
						if($result){
							if($type == 'image'){
								$getCont = $this->memcManager('get','Imagewechat'.$this->companyid);
								if($getCont){
									$this->memcManager('delete','Imagewechat'.$this->companyid);
									$this->ImagesAll($this->companyid,'wechat');
								}
								$search = 'Imagewechat'.$this->companyid.'search';
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
								$getCont = $this->memcManager('get','Voicewechat'.$this->companyid);
								if($getCont){
									$this->memcManager('delete','Voicewechat'.$this->companyid);
									$this->VoiceAll($this->companyid,'wechat');
								}
								$search = 'Voicewechat'.$this->companyid.'search';
								$key = $this->memcManager('get',$search);
								if($key){
									$array = explode('↑↓',$key);
									foreach ($array as $akey=>$aval){
										$this->memcManager('delete',$aval);
									}
									$this->memcManager('delete',$search);
								}
							}
							if($type == 'video'){
								$getCont = $this->memcManager('get','Videowechat'.$this->companyid);
								if($getCont){
									$this->memcManager('delete','Videowechat'.$this->companyid);
									$this->VideoAll($this->companyid,'wechat');
								}
								$search = 'Videowechat'.$this->companyid.'search';
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
						$ajax['msg'] = '错误代码：'.$mediaData['errcode'];
					}
				}
			}else{
				$ajax['code'] = '300';
				$ajax['msg'] = '该公众号不支持此功能';
			}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
	/**
	 * 添加文字模板(non-PHPdoc)
	 * @see UserAction::add()
	 */
	public function addText(){
		if (IS_POST){
			$this->insertback('Message_wechats_text',U('MessageWechats/textList'));
		}else{
			$this->display();
		}
	}
	/**
	 * ajax 获得 可发送 客服消息的 微信公众号
	 */
	public function ajaxGetSendMessage24hourWechats(){
		$openid = $this->_post('openid');
		$wecahtsList = D('History_wechat_request')->getMemberWehatRegquestNum($this->companyid,$openid);
		$return['code'] = 300;
		$return['html'] = '';
		if($wecahtsList){
			$return['code'] = 200;
			foreach ($wecahtsList as $wlKey=>$wlVal){
				if($wlKey == 0){
					$return['html'] .= '<label class="radio"><input type="radio" data-rule-required="true" name="token" checked="checked" value="'.$wlVal['token'].'">'.$wlVal['wxname'].'</label>';
				}else{
					$return['html'] .= '<label class="radio"><input type="radio" data-rule-required="true" name="token" value="'.$wlVal['token'].'">'.$wlVal['wxname'].'</label>';
				}
			}
		}
		echo json_encode($return);
	}
	/**
	 * 发送24小时微信信息(non-PHPdoc)
	 * @see UserAction::sendMessage24hour()
	 */
	public function sendMessage24hour(){
		C('TOKEN_ON',false);
		$wechatWhere['token'] = $this->_post('token');
		$wechatWhere['companyid'] = $this->companyid;
		$wechatInfo = $this->wechatsModel->getCompanyWechatsInfo($wechatWhere);
		if($wechatInfo){
			$wechatOptions = array('token'=>$wechatInfo['token'],'appid'=>$wechatInfo['appid'],'appsecret'=>$wechatInfo['appsecret']);
			$wechat  = new Wechat($wechatOptions);
			$sendData['touser'] = $this->_post('openid');
			$sendData['msgtype'] = 'text';
			$sendData['text'] = array('content'=>$_POST['info']);
			$data = $wechat->sendCustomMessage($sendData);
			if($data['errcode'] == 0){
				$_POST['companyid'] = $this->companyid;
				$_POST['mid'] = $this->_post('mid');
				$_POST['option'] = $this->_post('token');
				$_POST['info'] = $this->_post('info');
				$_POST['msgtype'] = '1';
				$_POST['createtime'] = time();
				$hourmessageReturn = M('member_wechat_24hourmessage')->add($_POST);
				if($hourmessageReturn){
					$this->success('信息发送成功');
				}else{
					$this->error(L('ServerBusyPrompt'));
				}
			}else{
				$this->error(L('ServerBusyPrompt'));
			}
		}else{
			$this->error(L('ServerBusyPrompt'));
		}
	}
	/**
	 * ajax 获得 交互消息
	 */
	public function ajaxGetWechatSendMessage24hour(){
		$where['companyid'] = $this->companyid;
		$where['mid'] = $this->_post('mid');
		$openid = $this->_post('openid');
		$token = $this->_post('token');
		$hourmessageCount = M('member_wechat_24hourmessage')->where($where)->count();
		$page = $this->_post('page')>0 ? $this->_post('page') : 1;
		$return['code'] = 300;
		$return['html'] = '';
		if($hourmessageCount && $page >0){
			$return['code'] = 200;
			$return['allCount'] = $hourmessageCount;
			$return['openid'] = $openid;
			$return['token'] = $token;
			$return['allPage'] = ceil($hourmessageCount/2);
			$return['nextPage'] = $page+1;
			$return['prevPage'] = $page-1;
			$hourmessageList = M('member_wechat_24hourmessage')->where($where)->field('id,option,info,msgtype,createtime')->order('id DESC,isread ESC')->limit('2')->page($page)->select();
			if($hourmessageList){
				foreach($hourmessageList as $hmKey=>$hmVal){
					if($hmVal['msgtype'] == '1'){
						$hourmessageList[$hmKey]['wechats'] = M('wechats')->where(array('token'=>$hmVal['option']))->field('id,wxname,headerpic')->find();
						if(empty($hourmessageList[$hmKey]['wechats']['headerpic'])){ $hourmessageList[$hmKey]['wechats']['headerpic'] = './Tpl/User/default/common/img/user_n.jpg';}
						$return['html'] .= '<li><img width="50" height="50" src="'.$hourmessageList[$hmKey]['wechats']['headerpic'].'"><h3 class="">'.$hourmessageList[$hmKey]['wechats']['wxname'].'</h3><p class="txt">'.$hmVal['info'].'</p><p class="tim">'.format_time($hmVal['createtime'],'ymdhi').'</p></li>';
					}elseif($hmVal['msgtype'] == '2'){
						$hourmessageList[$hmKey]['wechat'] = M('member_wechat_info')->where(array('openid'=>$hmVal['option']))->field('id,nickname,headimgurl')->find();
						if(empty($hourmessageList[$hmKey]['wechat']['headimgurl'])){ $hourmessageList[$hmKey]['wechat']['headimgurl'] = './Tpl/User/default/common/img/user_n.jpg';}
						$return['html'] .= '<li><img width="50" height="50" src="'.$hourmessageList[$hmKey]['wechat']['headimgurl'].'"><h3 class="">'.$hourmessageList[$hmKey]['wechat']['nickname'].'</h3><p class="txt">'.$hmVal['info'].'</p><p class="tim">'.format_time($hmVal['createtime'],'ymdhi').'</p></li>';
					}
				}
			}
			M('member_wechat_info')->where(array('companyid'=>$this->companyid,'openid'=>$openid))->save(array('wechatmessageisread'=>1));
		}
		echo json_encode($return);
	}
	/**
	 * 微信对话（列表）
	 */
	public function getWechatTalkList(){
		$where['companyid'] = $this->companyid;
		$where['mid'] = $mid = $this->_post('mid');
		$hourmessageCount = M('member_wechat_24hourmessage')->where($where)->count();
		$page = $this->_post('page')>0 ? $this->_post('page') : 1;
		$return['code'] = 300;
		$return['html'] = '';
		if($hourmessageCount && $page>0){
			$return['code'] = 200;
			$return['allCount'] = $hourmessageCount;
			$return['allPage'] = ceil($hourmessageCount/5);
			$return['nextPage'] = $page+1;
			$return['prevPage'] = $page-1;
			$hourmessageList = M('member_wechat_24hourmessage')->where($where)->field('info,msgtype,isread,createtime')->order('createtime desc,isread ASC,id DESC')->limit('5')->page($page)->select();
			if($hourmessageList){
				$hourmessageList = arraySort($hourmessageList,'createtime','SORT_ASC');
				foreach($hourmessageList as $hmKey=>$hmVal){
					
					$return['html'] .= '<li>';
					
					if($hmVal['msgtype'] == '1'){
						$hourmessageList[$hmKey]['wechats'] = M('wechats')->where(array('companyid'=>$this->companyid))->field('id,wxname,headerpic')->find();
	
						if(empty($hourmessageList[$hmKey]['wechats']['headerpic'])){ $hourmessageList[$hmKey]['wechats']['headerpic'] = './Tpl/User5/default/common/img/demo-user-avatar.png';}
	
						$return['html'] .= '<img class="user-avatar" src="'.$hourmessageList[$hmKey]['wechats']['headerpic'].'">';

						$return['html'] .= '<div class="msg-con">';
	
						$return['html'] .= '<h5 class="user-name">'.$hourmessageList[$hmKey]['wechats']['wxname'].'</h5>';
	
						$return['html'] .= '<h5 class="user-msg">'.emoji_decode($hmVal['info']).'</h5><h6 class="user-address text-gray"><span>'.format_time($hmVal['createtime'],'ymdhis').'</span></h6></div>';
					}elseif($hmVal['msgtype'] == '2'){
						$hourmessageList[$hmKey]['wechat'] = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'mid'=>$mid))->field('nickname,city,country,headimgurl')->find();
						$country = M('area')->where(array('id'=>$hourmessageList[$hmKey]['wechat']['country']))->getField('name');
						$city = M('area')->where(array('id'=>$hourmessageList[$hmKey]['wechat']['city']))->getField('name');
						$hourmessageList[$hmKey]['wechat']['address'] = $country.$city;
	
						if(empty($hourmessageList[$hmKey]['wechat']['headimgurl'])){ $hourmessageList[$hmKey]['wechat']['headimgurl'] = './Tpl/User5/default/common/img/demo-user-avatar.png';}
	
						$return['html'] .= '<img class="user-avatar" src="'.$hourmessageList[$hmKey]['wechat']['headimgurl'].'">';
						
						$return['html'] .= '<div class="msg-con">';
						
						$return['html'] .= '<h5 class="user-name">'.$hourmessageList[$hmKey]['wechat']['nickname'].'</h5>';
						
						$return['html'] .= '<h5 class="user-msg">'.emoji_decode($hmVal['info']).'</h5><h6 class="user-address text-gray">'.$hourmessageList[$hmKey]['wechat']['address'].'&nbsp; &nbsp; &nbsp; &nbsp;<span>'.format_time($hmVal['createtime'],'ymdhis').'</span></h6></div>';
					}
					$return['html'] .= '</li>';
				}
			}
			M('member_wechat_24hourmessage')->where(array('companyid'=>$this->companyid,'mid'=>$mid))->save(array('isread'=>2));
			M('member_wechat_info')->where(array('companyid'=>$this->companyid,'mid'=>$mid))->save(array('wechatmessageisread'=>1));
		}
		echo json_encode($return);
	}
	/**
	 * 微信对话（列表）
	 */
/* 	public function getWechatTalkList(){
		$where['companyid'] = $this->companyid;
		$where['mid'] = $mid = $this->_post('mid');
		$hourmessageCount = M('member_wechat_24hourmessage')->where($where)->count();
		$page = $this->_post('page')>0 ? $this->_post('page') : 1;
		$return['code'] = 300;
		$return['html'] = '';
		if($hourmessageCount && $page>0){
			$return['code'] = 200;
			$return['allCount'] = $hourmessageCount;
			$return['allPage'] = ceil($hourmessageCount/5);
			$return['nextPage'] = $page+1;
			$return['prevPage'] = $page-1;
			$hourmessageList = M('member_wechat_24hourmessage')->where($where)->field('info,msgtype,isread,createtime')->order('isread ASC,id DESC')->limit('5')->page($page)->select();
			if($hourmessageList){
				foreach($hourmessageList as $hmKey=>$hmVal){
						
					$return['html'] .= '<li>';
						
					if($hmVal['msgtype'] == '1'){
						$hourmessageList[$hmKey]['wechats'] = M('wechats')->where(array('companyid'=>$this->companyid))->field('id,wxname,headerpic')->find();
	
						if(empty($hourmessageList[$hmKey]['wechats']['headerpic'])){ $hourmessageList[$hmKey]['wechats']['headerpic'] = './Tpl/User5/default/common/img/demo-user-avatar.png';}
	
						$return['html'] .= '<img class="user-avatar" src="'.$hourmessageList[$hmKey]['wechats']['headerpic'].'">';
	
						$return['html'] .= '<div class="msg-con">';
	
						$return['html'] .= '<h5 class="user-name">'.$hourmessageList[$hmKey]['wechats']['wxname'].'</h5>';
	
						$return['html'] .= '<h5 class="user-msg">'.emoji_decode($hmVal['info']).'</h5><h6 class="user-address text-gray"><span>'.format_time($hmVal['createtime'],'ymdhi').'</span></h6></div>';
					}elseif($hmVal['msgtype'] == '2'){
						$hourmessageList[$hmKey]['wechat'] = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'mid'=>$mid))->field('nickname,city,country,headimgurl')->find();
						$country = M('area')->where(array('id'=>$hourmessageList[$hmKey]['wechat']['country']))->getField('name');
						$city = M('area')->where(array('id'=>$hourmessageList[$hmKey]['wechat']['city']))->getField('name');
						$hourmessageList[$hmKey]['wechat']['address'] = $country.$city;
	
						if(empty($hourmessageList[$hmKey]['wechat']['headimgurl'])){ $hourmessageList[$hmKey]['wechat']['headimgurl'] = './Tpl/User5/default/common/img/demo-user-avatar.png';}
	
						$return['html'] .= '<img class="user-avatar" src="'.$hourmessageList[$hmKey]['wechat']['headimgurl'].'">';
	
						$return['html'] .= '<div class="msg-con">';
	
						$return['html'] .= '<h5 class="user-name">'.$hourmessageList[$hmKey]['wechat']['nickname'].'</h5>';
	
						$return['html'] .= '<h5 class="user-msg">'.emoji_decode($hmVal['info']).'</h5><h6 class="user-address text-gray">'.$hourmessageList[$hmKey]['wechat']['address'].'&nbsp; &nbsp; &nbsp; &nbsp;<span>'.format_time($hmVal['createtime'],'ymdhi').'</span></h6></div>';
					}
					$return['html'] .= '</li>';
				}
			}
			M('member_wechat_24hourmessage')->where(array('companyid'=>$this->companyid,'mid'=>$mid))->save(array('isread'=>2));
			M('member_wechat_info')->where(array('companyid'=>$this->companyid,'mid'=>$mid))->save(array('wechatmessageisread'=>1));
		}
		echo json_encode($return);
	} */
	/**
	 * 微信对话
	 */
	public function wechatTalk(){
		if(IS_POST){
			$where['companyid'] = $this->companyid;
			$wechatInfo = $this->wechatsModel->where($where)->field('token,appid,appsecret')->find();
			if($wechatInfo){
				$wechatOptions = array('token'=>$wechatInfo['token'],'appid'=>$wechatInfo['appid'],'appsecret'=>$wechatInfo['appsecret']);
				$wechat  = new Wechat($wechatOptions);
				$sendData['touser'] = $this->_post('openid');
				$sendData['msgtype'] = 'text';
				$sendData['text'] = array('content'=>htmlspecialchars_decode($_POST['info']));
				$data = $wechat->sendCustomMessage($sendData);
				$mid = $this->_post('mid');
				M('member_wechat_24hourmessage')->where(array('companyid'=>$this->companyid,'mid'=>$mid))->save(array('isreply'=>1));
				M('member_wechat_info')->where(array('companyid'=>$this->companyid,'mid'=>$mid))->save(array('wechatmessageisreply'=>1));
				if($data['errcode'] == 0){
					$_POST['id'] = guid();
					$_POST['companyid'] = $this->companyid;
					$_POST['mid'] = $mid;
					$_POST['option'] = $wechatInfo['token'];
					$_POST['info'] = emoji_encode(htmlspecialchars_decode($this->_post('info')));
					$_POST['msgtype'] = '1';
					$_POST['createtime'] = time();
					$hourmessageReturn = M('member_wechat_24hourmessage')->add($_POST);
					if($hourmessageReturn){
						$ajax['code'] = '200';
					}else{
						$ajax['code'] = '300';
					}
				}else{
					$ajax['code'] = '300';
				}
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
			$wechatInfo = $this->wechatsModel->where(array('companyid'=>$this->companyid,'wechattype'=>'4'))->field('token,appid,appsecret')->find();
			if($wechatInfo){
				$wechatOptions = array('token'=>$wechatInfo['token'],'appid'=>$wechatInfo['appid'],'appsecret'=>$wechatInfo['appsecret']);
				$wechat  = new Wechat($wechatOptions);
				$type = $this->_post('type');
				$where['id'] = array('in',$this->_post('id'));
				$where['companyid'] = $wheres['companyid'] = $this->companyid;
				if($type == 'image'){
					$dbname = 'message_wechats_images';
				}elseif($type == 'voice'){
					$dbname = 'message_wechats_voices';
				}elseif($type == 'video'){
					$dbname = 'message_wechats_videos';
				}
				$num = '0';
				$media_id = M($dbname)->where($where)->getField('media_id',true);
				foreach ($media_id as $key=>$val){
					$wheres['media_id'] = $val;
					$result = $wechat->delForeverMedia($val);
					M($dbname)->where($wheres)->delete();
					$num++;
				}
				if($num){
					if($type == 'image'){
						$getCont = $this->memcManager('get','Imagewechat'.$this->companyid);
						if($getCont){
							$this->memcManager('delete','Imagewechat'.$this->companyid);
							$this->ImagesAll($this->companyid,'wechat');
						}
						$search = 'Imagewechat'.$this->companyid.'search';
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
						$getCont = $this->memcManager('get','Voicewechat'.$this->companyid);
						if($getCont){
							$this->memcManager('delete','Voicewechat'.$this->companyid);
							$this->VoiceAll($this->companyid,'wechat');
						}
						$search = 'Voicewechat'.$this->companyid.'search';
						$key = $this->memcManager('get',$search);
						if($key){
							$array = explode('↑↓',$key);
							foreach ($array as $akey=>$aval){
								$this->memcManager('delete',$aval);
							}
							$this->memcManager('delete',$search);
						}
					}
					if($type == 'video'){
						$getCont = $this->memcManager('get','Videowechat'.$this->companyid);
						if($getCont){
							$this->memcManager('delete','Videowechat'.$this->companyid);
							$this->VideoAll($this->companyid,'wechat');
						}
						$search = 'Videowechat'.$this->companyid.'search';
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
				$ajax['msg'] = '该公众号不支持此功能';
			}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
	/**
	 * 
	 * 图片上传(+压缩)
	 * 
	 * @author Leo<1251868177@qq.com>
	 * @since  2017-3-4
	 */
	public function imgUpload(){
	    $returnData['code'] = 300;
	    $returnData['tips'] = '抱歉';
	    $base64_image_content = $_POST['img'];
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
            $type = $result[2]; //jpeg
            $img = base64_decode(str_replace($result[1], '', $base64_image_content)); //返回文件流
            
            $time = time();
            $tmp_file = 'Uploads/'.$this->companyid.'/'.date("Ymd", $time).'/';
            check_dir($tmp_file);
            file_put_contents($tmp_file.$time.'.'.$type, $img); //可以直接将文件流保存为本地图片
            $returnData['code'] = 200;
            $returnData['tips'] = '恭喜';
        }
        
	    echo json_encode($returnData);    
	}
	
}
?>
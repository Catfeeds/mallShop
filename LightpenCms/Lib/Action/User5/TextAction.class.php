<?php
/**
 * 文本回复
 * Enter description here ...
 * @author yongfei.zhao
 *
 */
class TextAction extends UserAction{
	private $token;
	
	private $companyid;
	
	private $shopsid;
	
	private $uid;
	
	private $keywordModel;
	
	public function __construct(){
		parent::__construct();
		$this->checkCompanyScrm5Permissions(8,TRUE);
		$this->wechatsModel = D('wechats');
		$this->keywordModel 	= D('Keyword');
		$this->uid = session('uid');
		$this->companyid = session('cid');
		$this->shopsid = session('shopsid');
		$this->token = M('wechats')->where(array('companyid'=>$this->companyid))->getField('token');
		if($this->token){
			$this->assign('token',$this->token);
		}else{
			$this->error(L('ServerBusyPrompt'));
		}
	}
	/**
	 * 首页(non-PHPdoc)
	 * @see UserAction::index()
	 */
	public function index(){
		$where['companyid']=$this->companyid;
		$where['token']=$this->token;
		$where['module']='Text';
		$count=$this->keywordModel->where($where)->count();
		$page=new NewPage($count,15);
		$textList=$this->keywordModel->where($where)->limit($page->firstRow.','.$page->listRows)->order('id DESC')->select();
		$this->assign('page',$page->show());
		$this->assign('list',$textList);
		$this->display();
	}
	/**
	 * 关键词回复的添加
	 * (non-PHPdoc)
	 * @see UserAction::add()
	 */
	public function add(){
		$this->wechatManage();
		if (IS_POST){
			if($this->_get('type') == 1){
				$_POST['updatetime'] = $_POST['createtime'] = time();
				$_POST['token'] = $this->token;
				$_POST['module'] = 'Text';
				$_POST['content'] = htmlspecialchars_decode($this->_post('content'));
				$_POST['keyword'] = $this->_post('keyword');
				$_POST['companyid'] = $this->companyid;
				$result = M('keyword')->where(array('companyid'=>$this->companyid))->add($_POST);
				if($result){
					$info['code'] = 200;
				}
			}elseif($this->_get('type') == 2){
				$_POST['updatetime'] = $_POST['createtime'] = time();
				$_POST['token'] = $this->token;
				$_POST['module'] = 'News';
				$_POST['pictxtid'] = $this->_post('pictxtid');
				$_POST['keyword'] = $this->_post('keyword');
				$_POST['companyid'] = $this->companyid;
				$result = M('keyword')->where(array('companyid'=>$this->companyid))->add($_POST);
				if($result){
					$info['code'] = 200;
				}
			}
			echo json_encode($info);
		}else{
			//图文模板
			$manylist = M('message_wechats_manynews')->where(array('companyid'=>$this->companyid))->field('id,newsid,newsnum,media_id')->order("id DESC")->select();
			foreach($manylist as $key=>$val){
				if($val['newsid'] != ''){
					$data['id'] = array('in',$val['newsid']);
					$manylist[$key]['news'] = M('message_wechats_news')->where($data)->field('id,title,thumb_media,digest,updatetime')->order('id asc')->select();
					if($manylist[$key]['news']){
						$this->assign('manylist',$manylist);
					}else{
						$this->assign('manylist','');
					}
				}
			}
			$this->display();
		}
	}
	/**
	 * 关键词回复的编辑
	 * 编辑(non-PHPdoc)
	 * @see UserAction::edit()
	 */
	public function edit(){
		$this->wechatManage();
		if (IS_POST){
			$module = $this->_post('module');
			$textid = $this->_get('Textid');
			if($module == 'Text'){
				$data['updatetime'] = time();
				$data['module'] = $module;
				$data['content'] = $this->_post('content');
				$data['keyword'] = $this->_post('keyword');
				$result = M('keyword')->where(array('companyid'=>$this->companyid,'id'=>$textid))->save($data);
				if($result){
					$info['code'] = 200;
				}
			}elseif($module == 'News'){
				$value = $this->_post('value');
				if($value == '0'){
					$data['pictxtid'] = 0;
					$data['module'] = 'News';
					$data['updatetime'] = time();
					$data['keyword'] = $this->_post('keyword');
					$data['companyid'] = $this->companyid;
					$result = M('keyword')->where(array('companyid'=>$this->companyid,'id'=>$textid))->save($data);
					if($result){
						$info['code'] = 200;
					}
				}else{
					$data['module'] = $module;
					$data['updatetime'] = time();
					$data['pictxtid'] = $this->_post('pictxtid');
					$data['keyword'] = $this->_post('keyword');
					$data['companyid'] = $this->companyid;
					$result = M('keyword')->where(array('companyid'=>$this->companyid,'id'=>$textid))->save($data);
					if($result){
						$info['code'] = 200;
					}
				}
			}
			echo json_encode($info);
		}else{
			$module = $this->_get('module');
			$Textid = $this->_get('Textid');
			$textWhere['id'] = $Textid;
			$textWhere['companyid'] = $this->companyid;
			$textWhere['token'] = $this->token;
			$textInfo = $this->keywordModel->where($textWhere)->field('id,keyword,content,pictxtid,module')->find();
			$textInfo['tuwen'] = M('message_wechats_manynews')->where(array('companyid'=>$this->companyid,'id'=>$textInfo['pictxtid']))->field('id,newsid,newsnum,media_id')->order("id DESC")->select();
			foreach($textInfo['tuwen'] as $key=>$val){
				if($val['newsid'] != ''){
					$data['id'] = array('in',$val['newsid']);
					$textInfo['tuwen'][$key]['news'] = M('message_wechats_news')->where($data)->field('id,title,thumb_media,digest,updatetime')->order('id asc')->select();
					if($textInfo['tuwen'][$key]['news']){
						$this->assign('keywordtuwenList',$textInfo['tuwen']);
					}else{
						$this->assign('keywordtuwenList','');
					}
				}
			}
			//图文模板
			$manylist = M('message_wechats_manynews')->where(array('companyid'=>$this->companyid))->field('id,newsid,newsnum,media_id')->order("id DESC")->select();
			foreach($manylist as $key=>$val){
				if($val['newsid'] != ''){
					$data['id'] = array('in',$val['newsid']);
					$manylist[$key]['news'] = M('message_wechats_news')->where($data)->field('id,title,thumb_media,digest,updatetime')->order('id asc')->select();
					if($manylist[$key]['news']){
						$this->assign('manylist',$manylist);
					}else{
						$this->assign('manylist','');
					}
				}
			}
			$this->assign('module',$module);
			$this->assign('Textid',$Textid);
			$this->assign('info',$textInfo);
			$this->display();
		}
	}/**
	 * 关键词回复的编辑
	 * 编辑(non-PHPdoc)
	 * @see UserAction::edit()
	 */
	public function set(){
		$this->wechatManage();
		if (IS_POST){
			$result = json_decode(htmlspecialchars_decode($_POST['myarray']),true);
			$type = $this->_post('type');
			if($type == '2'){
				$datakey['module'] = 'News';
				$datakey['pictxtid'] = $this->_post('scvalue');
			}elseif($type == '3'){
				$datakey['module'] = 'Image';
				$datakey['pictxtid'] = $this->_post('scvalue');
			}elseif($type == '4'){
				$datakey['module'] = 'Voice';
				$datakey['pictxtid'] = $this->_post('scvalue');
			}elseif($type == '5'){
				$datakey['module'] = 'Video';
				$datakey['pictxtid'] = $this->_post('scvalue');
			}else{
				$datakey['module'] = 'Text';
				$datakey['text'] = emoji_encode(htmlspecialchars_decode($this->_post('scvalue')));
			}
			$data['companyid'] = $datakey['companyid'] = $where['companyid'] = $this->companyid;
			$data['token'] = $datakey['token']= $where['token'] = $this->token;
			$datakey['updatetime'] = $data['updatetime'] = time();
			$id = '';
			$keyword = '';
			foreach ($result as $rkey=>$rval){
				$data['keyword'] = emoji_encode($rval['keyword']);
				$data['ismate'] = $rval['ismate'];
				if($rval['id'] == ''){
					$data['createtime'] = $data['updatetime'];
					$data['id'] = guid();
					$add = M('keyword')->add($data);
					if($add){
						if($data['id'] == ''){
							$id .= $add.',';
							$jsonid = strval($add);
						}else{
							$id .= $data['id'].',';
							$jsonid = $data['id'];
						}
					}
				}else{
					$data['id'] = $where['id'] = $rval['id'];
					$save = M('keyword')->where($where)->save($data);
					if($save){
						$id .= $data['id'].',';
						$jsonid = $data['id'];
					}
				}
				$array[] = array('id'=>$jsonid,'keyword'=>$data['keyword'],'ismate'=>$rval['ismate']);
				$keyword .= $data['keyword'].',';
			}
			$datakey['mate'] = json_encode($array);
			$datakey['keyword'] = mb_substr($keyword,0,-1);
			$textid = $this->_request('id');
			if($textid){
				$results =  M('keyword_list')->where(array('companyid'=>$this->companyid,'id'=>$textid,'token'=>$this->token))->save($datakey);
			}else{
				$datakey['id'] = guid();
				$datakey['createtime'] = $data['createtime'];
				$results = M('keyword_list')->add($datakey);
			}
			if($results){
				if($this->_post('delid')){
					M('keyword')->where(array('companyid'=>$this->companyid,'token'=>$this->token,'id'=>array('in',$this->_post('delid'))))->delete();
				}
				$info['code'] = '200';
				$info['msg'] = '保存成功';
			}else{
				$info['code'] = '300';
				$info['msg'] = '保存失败';
			}
			echo json_encode($info);
		}else{
			$this->commonheader = 'API';
			$this->check_url = 'Reply';
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'我的API接入','url'=>U('Wechat/lists'),'rel'=>'','target'=>''),array('name'=>'微信自动回复设置','url'=>U('Other/set',array('wechatid'=>$this->_request('wechatid'))),'rel'=>'','target'=>''),array('name'=>'微信关键词回复','url'=>'','rel'=>'','target'=>'')));
			$this->ImagesAll($this->companyid,'wechat');
			$this->VoiceAll($this->companyid,'wechat');
			$this->VideoAll($this->companyid,'wechat');
			$module = $this->_get('module');
			$id = $this->_get('id');
			if($id){
				$textWhere['id'] = $id;
				$textWhere['companyid'] = $this->companyid;
				$textWhere['token'] = $this->token;
				$textInfo = M('keyword_list')->where($textWhere)->find();
				if($module == 'News'){
					//微信默认的回复图文
					$defaultPictxtlist = M('message_wechats_manynews')->where(array('companyid'=>$this->companyid,'id'=>$textInfo['pictxtid']))->field('id,newsid,newsnum')->find();
					if($defaultPictxtlist){
						$data['id'] = array('in',$defaultPictxtlist['newsid']);
						$data['companyid'] = $this->companyid;
						$defaultPictxtlist['news'] = M('message_wechats_news')->where($data)->field('id,title,thumb_media,updatetime')->order('sort asc')->select();
						if($defaultPictxtlist['news']){
							$this->assign('news',$defaultPictxtlist);
						}else{
							$this->assign('news','');
						}
					}
				}elseif($module == 'Image'){
					$image = M('message_wechats_images')->where(array('companyid'=>$this->companyid,'id'=>$textInfo['pictxtid']))->field('id,imageurl')->find();
					$this->assign('image',$image);
				}elseif($module == 'Voice'){
					$voice = M('message_wechats_voices')->where(array('companyid'=>$this->companyid,'id'=>$textInfo['pictxtid']))->field('id,title,voicesurl,time,size')->find();
					$this->assign('voice',$voice);
				}elseif($module == 'Video'){
					$video = M('message_wechats_videos')->where(array('companyid'=>$this->companyid,'id'=>$textInfo['pictxtid']))->field('id,title,videosurl,updatetime')->find();
					$this->assign('video',$video);
				}else{
					$this->assign('text',$textInfo['text']);
				}
				$this->keyword=json_decode($textInfo['mate'],true);
				$this->assign('module',$module);
				$this->assign('id',$id);
				$this->assign('info',$textInfo);
			}
			$this->display();
		}
	}
	/**
	 * 删除(non-PHPdoc)
	 * @see UserAction::del()
	 */
	public function del(){
		$this->deleteInfo('Keyword',array('id'=>$this->_get('id'),'companyid'=>$this->companyid,'token'=>$this->token),'/index?token='.$this->token);
	}

	/**
	 * 删除(non-PHPdoc)
	 * @see UserAction::del()
	 */
public function delkeyword(){
		$Textid = $this->_get('Textid');
		$id = $this->_post('id');
		$textWhere['id'] = $Textid;
		$textWhere['companyid'] = $this->companyid;
		$textWhere['token'] = $this->token;
		$textInfo = M('keyword_list')->where($textWhere)->find();
		$keyword=explode(',', $textInfo['keyword']);
		$info = json_decode($textInfo['mate'],true);
		foreach ($info as $key => $val){
			if($val['id']==$id){
				M("keyword")->where(array('id'=>$id))->delete();
			}else{
				$keyword1[$key]['id']=$val['id'];
				$keyword1[$key]['keyword']=$val['keyword'];
				$keyword1[$key]['ismate']=$val['ismate'];
				$keyword2[]=$val;
			}
		}
		$num = count($keyword2);
		if($num == 1){
			$keywordzu=$keyword2['0'];
		}else{
			$keywordzu=implode(',', $keyword2);
		}
		$data2['keyword']=$keywordzu;
		$keyword=json_encode($keyword1);
		$data2['mate']=$keyword;
		$data2['updatetime']=time();
		$result = M('keyword_list')->where($textWhere)->save($data2);
		if($result){
			$info['code'] = 200;
			$info['msg'] = '删除成功';
		}else{
			$info['msg'] = '删除失败';
		}
		echo json_encode($info);
	}
	public function ismate(){
		$Textid = $this->_get('Textid');
		$id = $this->_post('id');
		$ismate = $this->_post('ismate');
		if($ismate==1){
			$ismate=2;
		}else{
			$ismate=1;
		}
		$textWhere['id'] = $Textid;
		$textWhere['companyid'] = $this->companyid;
		$textWhere['token'] = $this->token;
		$textInfo = M('keyword_list')->where($textWhere)->find();
		$info = json_decode($textInfo['mate'],true);
		foreach ($info as $key => $val){
			if($val['id']==$id){
				$data['ismate']=$ismate;
				M("keyword")->where(array('id'=>$id))->save($data);
				$keyword1[$key]['ismate']=$ismate;
			}else{
				$keyword1[$key]['ismate']=$val['ismate'];
			}
			$keyword1[$key]['id']=$val['id'];
			$keyword1[$key]['keyword']=$val['keyword'];
		}
		$keyword=json_encode($keyword1);
		$data2['mate']=$keyword;
		$data2['updatetime']=time();
		$result = M('keyword_list')->where($textWhere)->save($data2);
		if($result){
			$info['code'] = 200;
			$info['ismate']=$ismate;
			$info['msg'] = '更新成功';
		}else{
			$info['msg'] = '更新失败';
		}
		echo json_encode($info);
	}
}
?>
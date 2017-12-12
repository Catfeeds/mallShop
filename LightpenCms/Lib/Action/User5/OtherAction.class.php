<?php
/**
 * 无回复 回复
 * Enter description here ...
 * @author yongfei.zhao
 *
 */
class OtherAction extends UserAction{
	
	private $token;
	
	private $uid;
	
	private $companyid;
	
	private $shopsid;
	
	private $otherModel;
	
	private $areplyModel;
	
	public function __construct(){
		parent::__construct();
		$this->checkCompanyScrm5Permissions(8,TRUE);
		$this->wechatsModel 	= D('wechats');
		$this->wechatManage();
		$this->otherModel 	= D('other');
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
	 * 
	 * 关键词关注时回复
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-5
	 */
	public function set(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'我的API接入','url'=>U('Wechat/lists'),'rel'=>'','target'=>''),array('name'=>'微信自动回复设置','url'=>U('Other/set',array('wechatid'=>$this->_request('wechatid'))),'rel'=>'','target'=>''),array('name'=>'微信无匹配回复','url'=>'','rel'=>'','target'=>'')));
		$this->ImagesAll($this->companyid,'wechat');
		$this->VoiceAll($this->companyid,'wechat');
		$this->VideoAll($this->companyid,'wechat');
		$otherWhere['companyid'] = $this->companyid;
		$otherWhere['token'] = $this->token;
		$otherInfo = $this->otherModel->where($otherWhere)->find();
		if(!$otherInfo){
			$otherWhere['id'] = guid();
			$otherWhere['updatetime'] = $otherWhere['createtime'] =time();
			$otherInfo = $this->otherModel->find($this->otherModel->add($otherWhere));
		}
		if(strstr($otherInfo['pictxtid'],'news')){
			//微信默认的回复图文
			$defaultPictxtid = ltrim($otherInfo['pictxtid'],'news');
			$defaultPictxtlist = M('message_wechats_manynews')->where(array('companyid'=>$this->companyid,'id'=>$defaultPictxtid))->field('id,newsid,newsnum')->find();
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
		}elseif(strstr($otherInfo['pictxtid'],'image')){
			$defaultPictxtid = ltrim($otherInfo['pictxtid'],'image');
			$image = M('message_wechats_images')->where(array('companyid'=>$this->companyid,'id'=>$defaultPictxtid))->field('id,imageurl')->find();
			$this->assign('image',$image);
		}elseif(strstr($otherInfo['pictxtid'],'voice')){
			$defaultPictxtid = ltrim($otherInfo['pictxtid'],'voice');
			$voice = M('message_wechats_voices')->where(array('companyid'=>$this->companyid,'id'=>$defaultPictxtid))->field('id,title,voicesurl,time,size')->find();
			$this->assign('voice',$voice);
		}elseif(strstr($otherInfo['pictxtid'],'video')){
			$defaultPictxtid = ltrim($otherInfo['pictxtid'],'video');
			$video = M('message_wechats_videos')->where(array('companyid'=>$this->companyid,'id'=>$defaultPictxtid))->field('id,title,videosurl,updatetime')->find();
			$this->assign('video',$video);
		}else{
			$this->assign('text',$otherInfo['info']);
		}
		$this->assign('otherInfo',$otherInfo);
		$this->display();
	}
	/**
	 * 
	 * 回复设置
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-5
	 */
	public function subset(){
		if(IS_POST){
			$otherWhere['companyid'] = $this->companyid;
			$otherWhere['token'] = $this->token;
			$type = $this->_post('type');
			$scvalue = $this->_post('scvalue');
			if($type == '2'){
				$data['pictxtid'] = 'news'.$scvalue;
			}elseif($type == '3'){
				$data['pictxtid'] = 'image'.$scvalue;
			}elseif($type == '4'){
				$data['pictxtid'] = 'voice'.$scvalue;
			}elseif($type == '5'){
				$data['pictxtid'] = 'video'.$scvalue;
			}else{
				$data['pictxtid'] = 'text';
				$data['info'] = emoji_encode(htmlspecialchars_decode($scvalue));
			}
			$data['isshow'] = $this->_post('isstart');
			$data['updatetime'] = time();
			$result = M('Other')->where($otherWhere)->save($data);
			if($result){
				$info['code'] = '200';
				$info['msg'] = '设置成功';
			}else{
				$info['code'] == '300';
				$info['msg'] = '设置失败';
			}
		}else{
			$info['code'] == '300';
			$info['msg'] = '设置失败';
		}
		echo json_encode($info);
	}
}
?>
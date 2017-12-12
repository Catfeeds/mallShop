<?php
/**
 * 自定义菜单
 * Enter description here ...
 * @author yongfei.zhao
 *
 */
class DiymenAction extends UserAction{
	private $token;
	private $companyid;
	public $wechatsModel;
	private $diymenClassModel;
	public function __construct(){
		parent::__construct();
		//检查公司配置
		$this->checkCompanyScrm5Permissions(7,TRUE);
		$this->companyid = session('cid');
		$this->token = M('wechats')->where(array('companyid'=>$this->companyid,'wechattype'=>array('in','2,3,4')))->getField('token');
		if($this->token){
			$this->assign('token',$this->token);
		}else{
			$this->error(L('ServerBusyPrompt'));
		}
		$this->wechatsModel 	= D('Wechats');
		$this->diymenClassModel = D('Diymen_class');
	}
	/**
	 * 
	 * 创建自定义菜单
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-19
	 */
	public function createDiymen(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请刷新重试';
		$result = json_decode(htmlspecialchars_decode($_POST['data']),true);
		if($result){
			$sort = 1;
			foreach($result as $rkey=>$rval){
				$sort+=1;
				if($rval['replytype'] == '1'){
					if($rval['contenttype'] == '2'){
						$keyword = 'news'.$rval['sucaicoutent'];
					}elseif($rval['contenttype'] == '3'){
						$keyword = 'image'.$rval['sucaicoutent'];
					}elseif($rval['contenttype'] == '4'){
						$keyword = 'voice'.$rval['sucaicoutent'];
					}elseif($rval['contenttype'] == '5'){
						$keyword = 'video'.$rval['sucaicoutent'];
					}else{
						$keyword = M('diymen_class')->where(array('companyid'=>$this->companyid,'token'=>$this->token,'num'=>$rkey))->getField('title');
					}
				}else{
					$keyword = M('diymen_class')->where(array('companyid'=>$this->companyid,'token'=>$this->token,'num'=>$rkey))->getField('title');
				}
				M('diymen_class')->where(array('companyid'=>$this->companyid,'token'=>$this->token,'num'=>$rkey))->save(array('sort'=>$sort,'updatetime'=>time(),'contenttype'=>$rval['contenttype'],'keyword'=>$keyword));
				$sort2 = 1;
				if($rval['chil']){
					foreach($rval['chil'] as $ckey=>$cval){
						$sort2+=1;
						if($cval['replytype'] == '1'){
							if($cval['contenttype'] == '2'){
								$ckeyword = 'news'.$cval['sucaicoutent'];
							}elseif($cval['contenttype'] == '3'){
								$ckeyword = 'image'.$cval['sucaicoutent'];
							}elseif($cval['contenttype'] == '4'){
								$ckeyword = 'voice'.$cval['sucaicoutent'];
							}elseif($cval['contenttype'] == '5'){
								$ckeyword = 'video'.$cval['sucaicoutent'];
							}else{
								$ckeyword = M('diymen_class')->where(array('companyid'=>$this->companyid,'token'=>$this->token,'num'=>$ckey))->getField('title');
							}
						}else{
							$ckeyword = M('diymen_class')->where(array('companyid'=>$this->companyid,'token'=>$this->token,'num'=>$ckey))->getField('title');
						}
						M('diymen_class')->where(array('companyid'=>$this->companyid,'token'=>$this->token,'num'=>$ckey))->save(array('sort'=>$sort2,'updatetime'=>time(),'contenttype'=>$cval['contenttype'],'keyword'=>$ckeyword));
						unset($cval);
					}
				}
				unset($rval);
			}
		}
		$wechatList = $this->wechatsModel->getCompanyWechatss(array('companyid'=>$this->companyid,'wechattype'=>array('in','2,3,4')));
		if (empty($wechatList)){
			$ajax['code'] = '300';
			$ajax['msg'] = '您的公众号类型无法使用本模块';
			die;
		}
		$wechatid = $this->_get('id');
		$api=$this->wechatsModel->getCompanyWechatsInfo(array('token'=>$this->token,'companyid'=>$this->companyid));
		if($api['appid']==false||$api['appsecret']==false){
			$this->error(L('MustFirstFill').'【AppId】【 AppSecret】',U('Diymen/index',array('token'=>$this->token,'id'=>$wechatid)));exit;
		}
		M('diymen_class')->where(array('token'=>$this->token,'companyid'=>$this->companyid,'title'=>array('eq','')))->delete();
		$class=$this->diymenClassModel->where(array('token'=>$this->token,'companyid'=>$this->companyid,'pid'=>0,'is_show'=>1,'title'=>array('neq','')))->limit(3)->order('sort ASC')->select();
		$kcount=count($class);
		$k=1;
		$data = '{"button":[';
		foreach($class as $key=>$vo){
			$vo['url']=str_replace(array('&amp;'),array('&'),$vo['url']);
			$vo['title']=emoji_decode(str_replace(array('&amp;'),array('&'),$vo['title']));
			//主菜单
			$data.='{"name":"'.$vo['title'].'",';
			$c=$this->diymenClassModel->where(array('token'=>$this->token,'companyid'=>$this->companyid,'pid'=>$vo['id'],'is_show'=>1,'title'=>array('neq','')))->limit(5)->order('sort ASC')->select();
			$count= count($c);
			//子菜单
			if($c){
				$data.='"sub_button":[';
			}else{
				if ($vo['replytype'] == '2') {
					$data.='"type":"view","url":"'.$vo['url'].'"';
				}else{
					$data.='"type":"click","key":"'.$vo['id'].'"';
				}
			}
			$i=1;
			foreach($c as $voo){
				$voo['url']=str_replace(array('&amp;'),array('&'),$voo['url']);
				$voo['title']=emoji_decode(str_replace(array('&amp;'),array('&'),$voo['title']));
				if($i==$count){
					if($voo['replytype'] == '2'){
						$data.='{"type":"view","name":"'.$voo['title'].'","url":"'.$voo['url'].'"}';
					}else{
						$data.='{"type":"click","name":"'.$voo['title'].'","key":"'.$voo['id'].'"}';
					}
				}else{
					if($voo['replytype'] == '2'){
						$data.='{"type":"view","name":"'.$voo['title'].'","url":"'.$voo['url'].'"},';
					}else{
						$data.='{"type":"click","name":"'.$voo['title'].'","key":"'.$voo['id'].'"},';
					}
				}
				$i++;
				unset($voo);
			}
			if($c!=false){
				$data.=']';
			}
			if($k==$kcount){
				$data.='}';
			}else{
				$data.='},';
			}
			$k++;
			unset($vo);
		}
		$data.=']}';
		/* if($this->companyid == '1183'){
			echo $data;
		} */
		$wechatOptions = array('appid'=>$api['appid'],'appsecret'=>$api['appsecret']);
		$wechat  = new Wechat($wechatOptions);
		$wechatInfo = $wechat->createMenu($data);
		if($wechatInfo['errmsg'] == 'ok'){
			$ajax['code'] = '200';
			$ajax['msg'] = '发布成功';
		}else{
			$ajax['code'] = '300';
			$ajax['msg'] = $wechatInfo['errcode']?$wechatInfo['errcode']:'发布失败';
		}
		echo json_encode($ajax);
	}
	/**
	 * 查询素材
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-3
	 */
	public function sucaiCoutent(){
		$contenttype = $this->_post('contenttype');
		$where['companyid'] = $data['companyid'] = $this->companyid;
		$where['id'] = $this->_post('sucaicoutent');
		$where['token'] = $data['token'] = $this->token;
		if($contenttype == '3'){
			$result = M('message_wechats_images')->where($where)->field('id,title,imageurl')->find();
			$info['html'] .= '<div class="Wechat-menu-pic-box">';
			$info['html'] .= '<i class="pic-img" style="background-image:url('.$result['imageurl'].');"></i>';
			$info['html'] .= '<a class="tips del-cur-img js-del-cur-img" href="javascript:void(0);">删除</a>';
			$info['html'] .= '</div>';
		}elseif($contenttype == '4'){
			$result = M('message_wechats_voices')->where($where)->field('id,title,voicesurl,time,size')->find();
			$info['html'] .= '<div class="Wechat-menu-audio-box js-Wechat-menu-audio-box">';
			$info['html'] .= '<div class="Wechat-menu-audio-con clearfix">';
			$info['html'] .= '<i class="icon-menu-audio js-icon-menu-audio"></i>';
			$info['html'] .= '<audio class="aa" src="'.$result['voicesurl'].'">您的浏览器不支持播放音频</audio>';
			$info['html'] .= '<div class="con-cover">';
			$info['html'] .= '<h5>'.$result['title'].'</h5>';
			$info['html'] .= '<h5 class="text-gray">时长：<span>'.$result['time'].'</span></h5>';
			$info['html'] .= '<h5 class="text-gray">大小：<span>'.$result['size'].'</span></h5>';
			$info['html'] .= '</div></div><a class="tips del-cur-audio js-del-cur-audio" href="javascript:void(0);">删除</a></div>';
		}elseif($contenttype == '5'){
			$result = M('message_wechats_videos')->where($where)->field('id,title,videosurl,updatetime')->find();
			$info['html'] .= '<div class="Wechat-menu-video-box js-Wechat-menu-video-box">';
			$info['html'] .= '<div class="Wechat-menu-video-con">';
			$info['html'] .= '<div class="video-con-tit">';
			$info['html'] .= '<video src="'.$result['videosurl'].'" height="114" width="206">您的浏览器不支持视频播放。</video>';
			$info['html'] .= '</div>';
			$info['html'] .= '<h5 class="con-tit">'.$result['title'].'</h5>';
			$info['html'] .= '<h5 class="con-date">'.format_time($result['updatetime'],'ymd').'</h5>';
			$info['html'] .= '</div><a class="tips del-cur-video js-del-cur-video" href="javascript:void(0);">删除</a></div>';
		}else{
			$newsid = M('message_wechats_manynews')->where($where)->getField('newsid');
			if($newsid){
				$data['id'] = array('in',$newsid);
				$result = M('message_wechats_news')->where($data)->field('id,title,thumb_media,content,digest,updatetime')->order('sort asc')->select();
				$info['html'] .= '<div class="Wechat-menu-pic-msg-box js-Wechat-menu-pic-msg-box">';
				$info['html'] .= '<div class="Wechat-menu-pic-msg-con">';
				$info['html'] .= '<h5 class="add-tit-date">'.format_time($result[0]['updatetime'],'ymdhis').'</h5>';
				$info['html'] .= '<div class="pic-msg-con-tit">';
				$info['html'] .= '<i class="pic-msg-con-tit-img" style="background-image:url('.$result[0]['thumb_media'].')"></i>';
				$info['html'] .= '<h3><span>'.emoji_decode($result[0]['title']).'</span></h3></div>';
				if(count($result)>1){
					foreach ($result as $rkey=>$rval){
						if($rkey>0){
							$info['html'] .= '<div class="L2-pic-msg">';
							$info['html'] .= '<h4><span>'.emoji_decode($rval['title']).'</span></h4>';
							$info['html'] .= '<img src="'.$rval['thumb_media'].'">';
							$info['html'] .= '</div>';
						}
					}
				}
				$info['html'] .= '</div>';
				$info['html'] .= '<a class="tips del-cur-pic-msg-con js-del-cur-pic-msg-con" href="javascript:void(0);">删除</a>';
				$info['html'] .= '</div>';
			}
		}
		if($result){
			$info['code'] = 200;
		}else{
			$info['html'] = '';
		}
		echo json_encode($info);
	}
	/**
	 *
	 * 公用修改
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-18
	 */
	public function commonSave(){
		$edittype = $this->_post('edittype');
		$content = $this->_post('content');
		$where['companyid'] = $this->companyid;
		$where['num'] = $this->_post('datanum');
		$where['token']  = $this->token;
		if($edittype == 'title'){
			//$data['keyword'] = emoji_encode($content);
			$data['title'] = emoji_encode($content);
		}elseif($edittype == 'content'){
			$data['content'] = emoji_encode($content);
		}elseif($edittype == 'url'){
			$data['url'] = str_replace(array('&amp;'),array('&'),$content);
		}else{
			$data[''.$edittype.''] = $content;
		}
		$data['updatetime'] = time();
		$result = M('diymen_class')->where($where)->save($data);
		if($result){
			$info['code'] = 200;
		}
		echo json_encode($info);
	}
	/**
	 *
	 * 添加菜单
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-18
	 */
	public function addMenu(){
		$num = $this->_post('datanum');
		$zi_num = $this->_post('datanum_zi');
		$data['id'] = guid();
		$data['token']  = $this->token;
		if($this->_post('type') == '1'){
			$data['num'] = $num;
			$data['pid'] = 0;
			$data['title'] = '一级菜单';
			$data['keyword'] = '一级菜单';
			$data['content'] = '一级菜单默认文本回复内容';
		}else{
			$data['num'] = $zi_num;
			$pid = M('diymen_class')->where(array('companyid'=>$this->companyid,'num'=>$num))->getField('id');
			$data['pid'] = $pid;
			$data['title'] = '二级菜单';
			$data['keyword'] = '二级菜单';
			$data['content'] = '二级菜单默认文本回复内容';
		}
		$data['is_show'] = '1';
		$data['companyid'] = $this->companyid;
		$data['createtime'] = $data['updatetime'] = time();
		$result = M('Diymen_class')->where(array('companyid'=>$this->companyid))->add($data);
		if($result){
			$info['code'] = 200;
		}
		echo json_encode($info);
	}
	/**
	 *
	 * 删除菜单
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-18
	 */
	public function delMain(){
		$wechatList = $this->wechatsModel->getCompanyWechatss(array('companyid'=>$this->companyid,'wechattype'=>array('in','2,3,4')));
		if (empty($wechatList)){
			$this->error('您的公众号类型无法使用本模块');
		}
		$num = $this->_post('datanum');
		$id = M('diymen_class')->where(array('companyid'=>$this->companyid,'num'=>$num))->getField('id');
		$count = M('diymen_class')->where(array('companyid'=>$this->companyid,'pid'=>$id))->count();
		if($count){
			$result2 = M('diymen_class')->where(array('companyid'=>$this->companyid,'pid'=>$id))->delete();
		}
		$result = M('diymen_class')->where(array('companyid'=>$this->companyid,'num'=>$num))->delete();
		if($result){
			$info['code'] = 200;
		}
		echo json_encode($info);
	}
	/**
	 * 自定义菜单配置(non-PHPdoc)
	 * @see UserAction::index()
	 */
	public function index(){
		$this->wechatManage();
		$this->ImagesAll($this->companyid,'wechat');
		$this->VoiceAll($this->companyid,'wechat');
		$this->VideoAll($this->companyid,'wechat');
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'我的API接入','url'=>U('Wechat/lists'),'rel'=>'','target'=>''),array('name'=>'微信自定义菜单管理','url'=>'','rel'=>'','target'=>'')));
		$wechat = $this->wechatsModel->getCompanyWechatsInfo(array('companyid'=>$this->companyid,'token'=>$this->token));
		$this->assign('info',$wechat);
		$diymenList['parent']=$this->diymenClassModel->getWechatDiymenList(array('pid'=>0,'is_show'=>1,'companyid'=>$this->companyid,'token'=>$this->token,'title'=>array('neq','')));
		foreach($diymenList['parent'] as $dlKey=>$dlVal){
			if(strstr($dlVal['keyword'],'news')){
				$diymenList['parent'][$dlKey]['sucaicoutent'] = ltrim($dlVal['keyword'],'news');
			}elseif(strstr($dlVal['keyword'],'image')){
				$diymenList['parent'][$dlKey]['sucaicoutent'] = ltrim($dlVal['keyword'],'image');
			}elseif(strstr($dlVal['keyword'],'voice')){
				$diymenList['parent'][$dlKey]['sucaicoutent'] = ltrim($dlVal['keyword'],'voice');
			}elseif(strstr($dlVal['keyword'],'video')){
				$diymenList['parent'][$dlKey]['sucaicoutent'] = ltrim($dlVal['keyword'],'video');
			}else{
				$diymenList['parent'][$dlKey]['sucaicoutent'] = '';
			}
		}
		$Count = M('diymen_class')->where(array('is_show'=>1,'pid'=>0,'companyid'=>$this->companyid,'token'=>$this->token))->count();
		foreach($diymenList['parent'] as $dlKey=>$dlVal){
			$diymenList['parent'][$dlKey]['child']=$this->diymenClassModel->getWechatDiymenList(array('is_show'=>1,'pid'=>$dlVal['id'],'companyid'=>$this->companyid,'token'=>$this->token,'title'=>array('neq','')));
			 foreach($diymenList['parent'][$dlKey]['child'] as $cKey=>$cVal){
				if(strstr($cVal['keyword'],'news')){
					$diymenList['parent'][$dlKey]['child'][$cKey]['sucaicoutent'] = ltrim($cVal['keyword'],'news');
				}elseif(strstr($cVal['keyword'],'image')){
					$diymenList['parent'][$dlKey]['child'][$cKey]['sucaicoutent'] = ltrim($cVal['keyword'],'image');
				}elseif(strstr($cVal['keyword'],'voice')){
					$diymenList['parent'][$dlKey]['child'][$cKey]['sucaicoutent'] = ltrim($cVal['keyword'],'voice');
				}elseif(strstr($cVal['keyword'],'video')){
					$diymenList['parent'][$dlKey]['child'][$cKey]['sucaicoutent'] = ltrim($cVal['keyword'],'video');
				}else{
					$diymenList['parent'][$dlKey]['child'][$cKey]['sucaicoutent'] = '';
				}
			}  
			$diymenList['parent'][$dlKey]['count'] = M('diymen_class')->where(array('pid'=>$dlVal['id'],'companyid'=>$this->companyid,'token'=>$this->token,'is_show'=>1,))->count();
		} 
		$this->assign('Count',$Count);
		$this->assign('list',$diymenList);
		$this->display();
	}
	/**
	 * 文本+分页
	 */
	public function text(){
		$keywordWhere= array('companyid'=>$this->companyid,'token'=>$this->_get('token'),'module'=>'Text');
		$textCount = M('keyword')->where($keywordWhere)->count();
		$page = $this->_post('page') > 0 ? $this->_post('page') : 1;
		$return['code'] = 300;
		$return['html'] = '';
		if($textCount && $page > 0){
			$return['code'] = 200;
			$return['allCount'] = $textCount;
			$return['allPage'] = ceil($textCount/15);
			$return['nextPage'] = $page+1;
			$return['prevPage'] = $page-1;
			$text = M('keyword')->where($keywordWhere)->field('id,keyword,content')->limit('15')->page($page)->select();
			foreach ($text as $tKey=>$tVal){
				$return['html'] .= '<tr><td>'.$tVal['keyword'].'</td><td class="f-pre">'.$tVal['content'].'</td><td><a href="javascript:void(0);" data-word="'.$tVal['keyword'].'" class="btn btn-mini btn-success useKeyword">使用</a></td></tr>';
			}
		}else{
			$return['html'] .= '<tr><td colspan="3"><p class="seek"><i class="seek-icon"></i>抱歉，当前无数据！</p></td></tr>';
		}
		echo json_encode($return);
	}
	/**
	 * 图文+分页
	 */
	public function keyword(){
		$keywordWhere= array('companyid'=>$this->companyid,'token'=>$this->_get('token'),'module'=>'News');
		$keywordCount = M('keyword')->where($keywordWhere)->count();
		$page = $this->_post('page') > 0 ? $this->_post('page') : 1;
		$return['code'] = 300;
		$return['html'] = '';
		if($keywordCount && $page > 0){
			$return['code'] = 200;
			$return['allCount'] = $keywordCount;
			$return['allPage'] = ceil($keywordCount/15);
			$return['nextPage'] = $page+1;
			$return['prevPage'] = $page-1;
			$keyword = M('keyword')->where($keywordWhere)->field('id,keyword,title')->limit('15')->page($page)->select();
			foreach ($keyword as $kKey=>$kVal){
				$return['html'] .= '<tr><td>'.$kVal['keyword'].'</td><td class="f-pre">'.$kVal['title'].'</td><td><a href="javascript:void(0);" data-word="'.$kVal['keyword'].'" class="btn btn-mini btn-success useKeyword">使用</a></td></tr>';
			}
		}else{
			$return['html'] .= '<tr><td colspan="3"><p class="seek"><i class="seek-icon"></i>抱歉，当前无数据！</p></td></tr>';
		}
		echo json_encode($return);
	}
	


	/**
	 * 添加回复文字
	 * @author   Tomas<416369046@qq.com>
	 * @since  2016-1-19
	 */
	public function addWenzi(){
		$wechatList = $this->wechatsModel->getCompanyWechatss(array('companyid'=>$this->companyid,'wechattype'=>array('in','2,3,4')));
		if (empty($wechatList)){
			$this->error('您的公众号类型无法使用本模块');
		}
		$num = $this->_post("num");
		$content = $this->_post('content');
		$data['content'] = $content;
		$data['updatetime'] = time();
		$result = M('diymen_class')->where(array('companyid'=>$this->companyid,'num'=>$num))->save($data);
		if($result){
			$info['code'] = 200;
		}
		echo json_encode($info);
	}
	/**
	 * 添加回复图文
	 * @author   Tomas<416369046@qq.com>
	 * @since  2016-1-21
	 */
	public function addTuwen(){
		$wechatList = $this->wechatsModel->getCompanyWechatss(array('companyid'=>$this->companyid,'wechattype'=>array('in','2,3,4')));
		if (empty($wechatList)){
			$this->error('您的公众号类型无法使用本模块');
		}
		$num = $this->_post("num");
		$tuwen = $this->_post('tuwen');
		$data['keyword'] = 'news'.$tuwen;
		$data['updatetime'] = time();
		$result = M('diymen_class')->where(array('companyid'=>$this->companyid,'num'=>$num))->save($data);
		if($result){
			$info['code'] = 200;
		}
		echo json_encode($info);
	}
	/**
	 * 添加链接
	 * @author   Tomas<416369046@qq.com>
	 * @since  2016-1-20
	 */
	public function addLink(){
		$wechatList = $this->wechatsModel->getCompanyWechatss(array('companyid'=>$this->companyid,'wechattype'=>array('in','2,3,4')));
		if (empty($wechatList)){
			$this->error('您的公众号类型无法使用本模块');
		}
		$num = $_REQUEST["num"];
		$url = $_REQUEST["url"];
		$data['url'] = $url;
		$data['updatetime'] = time();
		$result = M('diymen_class')->where(array('companyid'=>$this->companyid,'num'=>$num))->save($data);
		if($result){
			$info['code'] = 200;
		}
		echo json_encode($info);
	}

 	/**
 	 * 添加子菜单p
 	 * @author   Tomas<416369046@qq.com>
 	 * @since  2016-1-30
 	 */
 	public function addZip(){
 		
 	}
	/**
	 * 添加子菜单
	 * @author   Tomas<416369046@qq.com>
	 * @since  2016-1-19
	 */
	public function addZi(){
		$wechatList = $this->wechatsModel->getCompanyWechatss(array('companyid'=>$this->companyid,'wechattype'=>array('in','2,3,4')));
		if (empty($wechatList)){
			$this->error('您的公众号类型无法使用本模块');
		}
		$Mainnum = $this->_post('Mainnum');
		$MainList = M('diymen_class')->where(array('companyid'=>$this->companyid,'num'=>$Mainnum))->field('id')->find();
		$num = $this->_post('num');
		$title = $this->_post('title');
		if($MainList){
			$Count = M('diymen_class')->where(array('companyid'=>$this->companyid,'num'=>$num))->count();
			if($Count){
				$keyword = M('diymen_class')->where(array('companyid'=>$this->companyid,'num'=>$num))->field('keyword')->find();
				if(strstr($keyword['keyword'],'news')){
					$data['title'] = $title;
					$data['updatetime'] = time();
					$result = M('diymen_class')->where(array('token'=>$this->token,'companyid'=>$this->companyid,'num'=>$num))->save($data);
				}else{
					$data['title'] = $title;
					$data['keyword'] = $title;
					$data['updatetime'] = time();
					$result = M('diymen_class')->where(array('token'=>$this->token,'companyid'=>$this->companyid,'num'=>$num))->save($data);
				}
				if($result){
					$info['code'] = 200;
				}
			}else{
				$data['token']  = $this->token;
				$data['pid'] = $MainList['id'];
				$data['title'] = $title;
				$data['keyword'] = $title;
				$data['num'] = $num;
				$data['is_show'] = '1';
				$data['companyid'] = $this->companyid;
				$data['createtime'] = $data['updatetime'] = time();
				$result = M('diymen_class')->add($data);
				if($result){
					$info['code'] = 200;
				}
			}
		}
		echo json_encode($info);
	}
	/**
	 * 如若添加了子菜单，将清空主菜单内所有回复内容
	 * @author   Tomas<416369046@qq.com>
	 * @since  2016-1-20
	 */
	public function emptyContent(){
		$wechatList = $this->wechatsModel->getCompanyWechatss(array('companyid'=>$this->companyid,'wechattype'=>array('in','2,3,4')));
		if (empty($wechatList)){
			$this->error('您的公众号类型无法使用本模块');
		}
		$num = $this->_post('num');
		$data['url'] = $data['content'] = '';
		$data['updatetime'] = time();
		$result = M('diymen_class')->where(array('companyid'=>$this->companyid,'num'=>$num))->save($data);
		if($result){
			$info['code'] = 200;
		}
		echo json_encode($info);
	}
	/**
	 * 获取图文
	 * @author   Tomas<416369046@qq.com>
	 * @since  2016-1-21
	 */
	public function getTuwen(){
		$tuwen = $this->_post('tuwen');
		$num = $this->_post('num');
		if($tuwen){
			$dlVal['keyword'] =$tuwen;
			$pictxtlist = M('message_wechats_manynews')->where(array('companyid'=>$this->companyid,'id'=>$dlVal['keyword']))->field('id,newsid,newsnum,media_id')->order("id DESC")->select();
			foreach($pictxtlist as $key=>$val){
				if($val['newsid'] != ''){
					$data['id'] = array('in',$val['newsid']);
					$pictxtlist[$key]['news'] = M('message_wechats_news')->where($data)->field('id,title,thumb_media,digest,updatetime')->order('id asc')->select();
				}
			}
			$string ='';
			if($pictxtlist){
				$string .='<div style=" height: 345px;overflow-y:scroll; padding-left: 20px;">';
				foreach($pictxtlist as $key=>$val){
					if($val['newsnum'] > 1){
						$string .='<div class="canchose" ><div  class="sucai-ku"><div class="sucai-ku2" ><div class = "dit-areawai">';
						$string .='<div class="text-header data-id" data-id="'.$val['id'].'" data-msgid="'.$val['media_id'].'">';
						$string .='<p class="header-p">'.format_time($val['news'][0]['updatetime'],'zhymdhi').'</p>';
						$string .='<div class="header-img"><img src="'.$val['news'][0]['thumb_media'].'" style="width:280px;height:136px"><p>'.$val['news'][0]['title'].'</p></div></div>';
						foreach($val['news'] as $nKey=>$nVal){
							if($nKey > 0){
								$string .='<div class="minimg-text"><p class="minimg-p">'.$nVal['title'].'</p><img src="'.$nVal['thumb_media'].'" style="width:90px;height:90px"></div>';
							}
						}
						$string .='</div></div></div></div>';
					}else{
						$string .='<div class="canchose" ><div class="sucai-ku"><div class="sucai-ku2"><div class="dit-areawai" ><div class="dit-area data-id" data-id="'.$val['id'].'" data-msgid="'.$val['media_id'].'">';
						$string .='<p>'.$val['news'][0]['title'].'</p><span>'.format_time($val['news'][0]['updatetime'],'zhymdhi').'</span>';
						$string .='<img class="area-img" src="'.$val['news'][0]['thumb_media'].'" style="width:280px;height:136px">';
						$string .='<p>'.$val['news'][0]['digest'].'</p></div></div></div></div></div>';
					}
				}
				$string .='<div class=""><a class="dele_subscribe" id="dele_sub" data-num="'.$num.'" style="float: left;color: #0000ff;cursor:pointer;">删除</a></div></div>';
			}
			echo json_encode($string);
		}
	}
	/**
	 * 删除已有的图文
	 * @author   Tomas<416369046@qq.com>
	 * @since  2016-1-21
	 */
	public function dele(){
		$num = $this->_post('num');
		$data['keyword'] = '菜单名称';
		$data['updatetime'] = time();
		$result = M('Diymen_class')->where(array('companyid'=>$this->companyid,'num'=>$num))->save($data);
		if($result){
			$info['code'] = 200;
		}
		echo json_encode($info);
	}
	/**
	 * 获取图文2
	 * @author   Tomas<416369046@qq.com>
	 * @since  2016-1-21
	 */
	public function getTuwen2(){
		$tuwen = $this->_post('tuwen');
		$num = $this->_post('num');
		if($tuwen){ 
			$dlVal['keyword'] =$tuwen;
			$pictxtlist = M('message_wechats_manynews')->where(array('companyid'=>$this->companyid,'id'=>$dlVal['keyword']))->field('id,newsid,newsnum,media_id')->order("id DESC")->select();
			foreach($pictxtlist as $key=>$val){
				if($val['newsid'] != ''){
					$data['id'] = array('in',$val['newsid']);
					$pictxtlist[$key]['news'] = M('message_wechats_news')->where($data)->field('id,title,thumb_media,digest,updatetime')->order('id asc')->select();
				}
			}
			$string ='';
			if($pictxtlist){
				$string .='<div style=" height: 345px;overflow-y:scroll; padding-left: 20px;">';
				foreach($pictxtlist as $key=>$val){
					if($val['newsnum'] > 1){
						$string .='<div class="canchose" ><div  class="sucai-ku"><div class="sucai-ku2" ><div class = "dit-areawai">';
						$string .='<div class="text-header data-id" data-id="'.$val['id'].'" data-msgid="'.$val['media_id'].'">';
						$string .='<p class="header-p">'.format_time($val['news'][0]['updatetime'],'zhymdhi').'</p>';
						$string .='<div class="header-img"><img src="'.$val['news'][0]['thumb_media'].'" style="width:280px;height:136px"><p>'.$val['news'][0]['title'].'</p></div></div>';
						foreach($val['news'] as $nKey=>$nVal){
							if($nKey > 0){
								$string .='<div class="minimg-text"><p class="minimg-p">'.$nVal['title'].'</p><img src="'.$nVal['thumb_media'].'" style="width:90px;height:90px"></div>';
							}
						}
						$string .='</div></div></div></div>';
					}else{
						$string .='<div class="canchose" ><div class="sucai-ku"><div class="sucai-ku2"><div class="dit-areawai" ><div class="dit-area data-id" data-id="'.$val['id'].'" data-msgid="'.$val['media_id'].'">';
						$string .='<p>'.$val['news'][0]['title'].'</p><span>'.format_time($val['news'][0]['updatetime'],'zhymdhi').'</span>';
						$string .='<img class="area-img" src="'.$val['news'][0]['thumb_media'].'" style="width:280px;height:136px">';
						$string .='<p>'.$val['news'][0]['digest'].'</p></div></div></div></div></div>';
					}
				}
				$string .='<div class=""><a class="dele_subscribe2" id="dele_sub" data-num="'.$num.'" style="float: left;color: #0000ff;cursor:pointer;">删除</a></div></div>';
			}
			echo json_encode($string);
		}
	}
	/**
	 * 删除已有的图文2
	 * @author   Tomas<416369046@qq.com>
	 * @since  2016-1-21
	 */
	public function dele2(){
		$num = $this->_post('num');
		$data['keyword'] = '子菜单名称';
		$data['updatetime'] = time();
		$result = M('Diymen_class')->where(array('companyid'=>$this->companyid,'num'=>$num))->save($data);
		if($result){
			$info['code'] = 200;
		}
		echo json_encode($info);
	}
	/**
	 * 
	 * 修改状态
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-5-27
	 */
	public function checkedtype(){
		$num = $this->_post('num');
		$data['type'] = $this->_post('type');
		$data['updatetime'] = time();
		$result = M('Diymen_class')->where(array('companyid'=>$this->companyid,'num'=>$num))->save($data);
		if($result){
			$info['code'] = 200;
		}
		echo json_encode($info);
	}
	/**
	 *
	 * 刷新图文
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-5-27
	 */
	public function newtuwen(){
		//图文库
		$ajax['html'] = '';
		$manylist = M('message_wechats_manynews')->where(array('companyid'=>$this->companyid))->field('id,newsid,newsnum,media_id')->order("id DESC")->select();
		if($manylist){
			foreach($manylist as $key=>$val){
				$data['id'] = array('in',$val['newsid']);
				$news = M('message_wechats_news')->where($data)->field('id,title,thumb_media,digest,updatetime')->order('sort asc')->select();
				if($val['newsnum'] > 1){
					$ajax['html'] .= '<div class="canchose" ><div  class="sucai-ku"><div class="sucai-ku2" ><div class="sucai-ku31" style="height: 100%; display:none" ></div><div class="sucai-ku32"  style="height: 100%; display:none"><img src = "./Tpl/User/default/common/img/duihao.png"></div><div class = "dit-areawai">';
					$ajax['html'] .= '<div class="text-header data-id" data-id="'.$val['id'].'">';
					$ajax['html'] .= '<p class="header-p">'.format_time($news[0]['updatetime'],'zhymdhi').'</p>';
					$ajax['html'] .= '<div class="header-img">';
					$ajax['html'] .= '<img src="'.$news[0]['thumb_media'].'" style="width:280px;height:136px">';
					$ajax['html'] .= '<p>'.$news[0]['title'].'</p>';
					$ajax['html'] .= '</div></div>';
					foreach($news as $nKey=>$nVal){
						if($nKey > 0){
							$ajax['html'] .= '<div class="minimg-text">';
							$ajax['html'] .= '<p class="minimg-p">'.$nVal['title'].'</p>';
							$ajax['html'] .= '<img src="'.$nVal['thumb_media'].'" style="width:90px;height:90px">';
							$ajax['html'] .= '</div>';
							
						}
					}
					$ajax['html'] .= '</div></div></div></div>';
				}else{
					$ajax['html'] .= '<div class="canchose" ><div class="sucai-ku"><div class="sucai-ku2"><div class="sucai-ku31"  style="display:none"></div><div class="sucai-ku32"  style="display:none"><img src = "./Tpl/User/default/common/img/duihao.png"></div><div class="dit-areawai" >';
					$ajax['html'] .= '<div class="dit-area data-id" data-id="'.$val['id'].'">';
					$ajax['html'] .= '<p>'.$news[0]['title'].'</p>';
					$ajax['html'] .= '<span>'.format_time($news[0]['updatetime'],'zhymdhi').'</span>';
					$ajax['html'] .= '<img class="area-img" src="'.$news[0]['thumb_media'].'" style="width:280px;height:136px">';
					$ajax['html'] .= '<p>'.$news[0]['digest'].'</p>';
					$ajax['html'] .= '</div></div></div></div></div>';
				}
			}
		}else{
			$ajax['html'] .='<div class="noContent"><p class="seek"><i class="seek-icon"></i>暂无</p></div>';
		}
		echo json_encode($ajax);
	}
}
?>
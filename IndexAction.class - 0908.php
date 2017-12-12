<?php
class IndexAction extends HomeBaseAction{
	
	private $usersModel;
	
	private $companyModel;
	
	private $companyGroupModel;
	
	public function __construct(){
		parent::__construct();
		$this->usersModel = M('users');
		$this->companyModel = M('company');
		$this->companyGroupModel = M('company_group');
	}
	//首页
	public function index(){
		//人来风 - MobiWind
		$this->setPageSeo(array('title'=>'人来风S-CRM-v4.0上线-人来风MobiWind官网','keywords'=>'人来风MobiWind,S-CRM,社会化客户关系管理系统,微信营销,微信第三方平台,微信代运营,微电商,O2O,www.mobiwind.cn','description'=>'人来风MobiWind官方网站在线提供S-CRM,社会化客户关系管理系统,微信第三方平台,微信代运营,渠道代理,O2O咨询等服务'));
		$articleList = M('article')->field('fArticleId,fArticleTitle,fArticleCreateDate,fArticleIntrod')->where(array('fArticleCategoryId'=>1,'fArticleIsShow'=>1))->order('fArticleSort ASC,fArticleCreateDate DESC')->limit('2')->select();
		//O2O最新推荐
		$articleList = M()->table('tp_home_list_link as link')->join(array('LEFT JOIN tp_home_list as list on list.id=link.listid'))->where(array('link.companyid'=>'1','link.listid'=>array('in','223,232')))->field('list.title,link.listid,link.pic,link.url')->limit(0,10)->select();
		if($articleList){
			foreach($articleList as $lhKey=>$lhVal){
				$txt= $lhVal['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$nid=$matches[1][0];
				}
				$info = M('home_info')->where(array('id'=>$nid,'companyid'=>'1'))->field('id,title,praise')->find();
				$articleList[$lhKey]['title'] = $lhVal['title'];
				$articleList[$lhKey]['listid'] = $lhVal['listid'];
				$articleList[$lhKey]['pic'] = $lhVal['pic'];
				$articleList[$lhKey]['infoid'] = $info['id'];
				$articleList[$lhKey]['infotitle'] = $info['title'];
				$articleList[$lhKey]['praise'] = $info['praise'];
			}
		}
		/* $k = 0;
		foreach($articleList as $key => $val){
			$articleList[$k]['title'] = $val['title'];
			$articleList[$k]['listid'] = $val['listid'];
			$articleList[$k]['pic'] = $val['pic'];
			$articleList[$k]['infoid'] = $val['info']['id'];
			$articleList[$k]['infotitle'] = $val['info']['title'];
			$articleList[$k]['praise'] = $val['info']['praise'];
			$k++;
		}
		if($articleList){
			$articleList = arraySort($articleList,'infoid');
		} */
		$this->assign('list',$articleList);
		$this->display();
	}
	//产品简介
	public function intro(){
		$this->setPageSeo(array('title'=>'人来风S-CRM产品简介-v4.0上线,免费开通','keywords'=>'人来风MobiWind,S-CRM,产品简介','description'=>'人来风S-CRM产品简介-V4.0上线,全新升级,全新体验,立即免费开通'));
		$this->display();
	}
	//O2O风人院
	public function hearsay(){
		$listLink['id'] = $id = $this->_get('id');
		if($id == 232){
			$this->setPageSeo(array('title'=>'行业O2O观察-人来风O2O风人院','keywords'=>'人来风,mobiwind,O2O资讯,行业O2O观察,O2O风人院,O2O动态资讯：电商O2O,零售O2O,品牌O2O,婚庆O2O,餐饮O2O,汽车O2O','description'=>'行业O2O观察-O2O风人院为您提供第一手的行业O2O动态资讯：电商O2O,零售O2O,品牌O2O,婚庆O2O,餐饮O2O,汽车O2O等'));
		}elseif($id == 223){
			$this->setPageSeo(array('title'=>'探索移动营销-人来风O2O风人院','keywords'=>'人来风,mobiwind,移动营销,移动红利,微信营销','description'=>'探索移动营销-O2O风人院为您一同探索最新的品牌移动营销方法与实践经验'));
		}
		//每月新增、总计
		$nowMonth = strtotime(format_time(time(),'ym'));
		$nextMonth = strtotime(format_time(strtotime('+1 month'),'ym'));
		$listLink['monthlyAddCount'] = M('home_list_link')->where(array('listid'=>$id,'companyid'=>'1','createtime'=>array('between',array($nowMonth,$nextMonth))))->count();
		$listLink['allCount'] = M('home_list_link')->where(array('listid'=>$id,'companyid'=>'1'))->count();
		//热点
		$listLink['hotSpot'] = M('home_list_link')->where(array('listid'=>$id,'companyid'=>'1'))->field('listid,title,url,createtime')->select();
		if($listLink['hotSpot']){
			foreach($listLink['hotSpot'] as $lhKey=>$lhVal){
				$listLink['hotSpot'][$lhKey]['readcount'] = M('history_page_browsing')->where(array('pagelink'=>$lhVal['url'],'companyid'=>'1'))->count();
				$txt= $lhVal['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$listLink['hotSpot'][$lhKey]['id']=$matches[1][0];
				}
			}
		}		
		if($listLink['hotSpot']){
			$listLink['topHotSpot'] = arraySort($listLink['hotSpot'],$listLink['hotSpot'][$lhKey]['readcount'],'SORT_ASC');
		}
		//热门
		$listLink['hotDoor'] = M()->table('tp_home_list_link as link')->join(array('LEFT JOIN tp_home_list as list on list.id=link.listid'))->where(array('link.companyid'=>'1','link.listid'=>array('in','223,232')))->field('list.title,link.listid,link.title as lTitle,link.pic,link.url')->select();
		if($listLink['hotDoor']){
			foreach($listLink['hotDoor'] as $lhKey=>$lhVal){
				$listLink['hotDoor'][$lhKey]['readcount'] = M('history_page_browsing')->where(array('pagelink'=>$lhVal['url'],'companyid'=>'1'))->count();
				$txt= $lhVal['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$listLink['hotDoor'][$lhKey]['id']=$matches[1][0];
				}
			}
		}
		if($listLink['hotDoor']){
			$listLink['hotDoor'] = arraySort($listLink['hotDoor'],$listLink['hotDoor'][$lhKey]['readcount'],'SORT_ASC');
		}
		//列表
		$count  = M('home_list_link')->where(array('listid'=>$id,'companyid'=>'1'))->count();
		$pages = ceil($count/6);
		$page = $this->_get('page')>0?$this->_get('page'):'1';
		$beginNum = ($page-1)*6;   
		$endNum = $page*6;
		$prevpage = $page-1;
		$nextpage = $page+1;
		$listLink['title'] = M('home_list')->where(array('id'=>$id,'companyid'=>'1'))->field('title')->find();
		$listLink['listLink'] = M('home_list_link')->where(array('listid'=>$id,'companyid'=>'1'))->field('listid,pic,url')->order('sort ASC,updatetime DESC')->limit($beginNum,$endNum)->select();
		if($listLink['listLink']){
			foreach($listLink['listLink'] as $llKey=>$llVal){
				$txt= $llVal['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$infoId=$matches[1][0];
				}
				$info = M('home_info')->where(array('id'=>$infoId,'companyid'=>'1'))->field('id,title,author,desc,praise,sharenum,updatetime,readcount')->find();
				$listLink['listLink'][$llKey]['id'] = $info['id'];
				$listLink['listLink'][$llKey]['title'] = $info['title'];
				$listLink['listLink'][$llKey]['author'] = $info['author'];
				$listLink['listLink'][$llKey]['desc'] = $info['desc'];
				$listLink['listLink'][$llKey]['click'] = $info['readcount'];
				//$listLink['listLink'][$llKey]['click'] = M('history_page_browsing')->where(array('companyid'=>1,'pagelink'=>array('like',C('site_url').U('Index/hearsayInfo',array('listid'=>$id,'id'=>$info['id'])).'%')))->count();
				$listLink['listLink'][$llKey]['praise'] = $info['praise'];
				$listLink['listLink'][$llKey]['sharenum'] = $info['sharenum'];
				$listLink['listLink'][$llKey]['updatetime'] = $info['updatetime'];
			}
		}
		/* $k = 0;
		foreach($listLink['listLink'] as $key => $val){
			$listLink['listLink'][$k]['id'] = $val['info']['id'];
			$listLink['listLink'][$k]['title'] = $val['info']['title'];
			$listLink['listLink'][$k]['author'] = $val['info']['author'];
			$listLink['listLink'][$k]['desc'] = $val['info']['desc'];
			$listLink['listLink'][$k]['click'] = $val['info']['click'];
			$listLink['listLink'][$k]['praise'] = $val['info']['praise'];
			$listLink['listLink'][$k]['sharenum'] = $val['info']['sharenum'];
			$listLink['listLink'][$k]['updatetime'] = $val['info']['updatetime'];
			$k++;
		}
		if($listLink['listLink']){
			$listLink['listLink'] = arraySort($listLink['listLink'],'updatetime');
		} */
		$this->assign('list',$listLink);
		$this->assign('beginNum',$beginNum);
		$this->assign('endNum',$endNum);
		$this->assign('prevpage',$prevpage);
		$this->assign('nextpage',$nextpage);
		$this->assign('pages',$pages);
		$this->assign('page',$page);
		$this->display();
	}
	//O2O风人院详情
	public function hearsayInfo(){
		if(IS_POST){
			$listid = $this->_post('listid');
			$infoid = $this->_post('infoid');
			$_POST['info'] = $this->_post('info');
			if(empty($_POST['info'])){
				$this->redirect(U('Index/hearsayInfo',array('listid'=>$listid,'id'=>$infoid)));
			}
			$_POST['createtime'] = $_POST['updatetime'] = time();
			$comment = M('home_info_comment')->add($_POST);
			if($comment){
				$this->redirect(U('Index/hearsayInfo',array('listid'=>$listid,'id'=>$infoid)));
			}
		}else{
			$listid = $this->_get('listid');
			$id = $this->_get('id');
			$listLink['link'] = M()->table('tp_home_list_link as link')->join(array('LEFT JOIN tp_home_list as list on list.id=link.listid'))->where(array('link.companyid'=>'1','link.listid'=>$listid))->field('list.title,link.listid,link.pic')->find();
			$listLink['info'] = M('home_info')->where(array('id'=>$id,'companyid'=>'1'))->field('id,title,author,desc,info,praise,sharenum,updatetime,readcount')->find();
			$listLink['info']['click']=$listLink['info']['readcount'];
			//$listLink['info']['click'] = M('history_page_browsing')->where(array('companyid'=>1,'pagelink'=>array('like',C('site_url').U('Index/hearsayInfo',array('listid'=>$listid,'id'=>$id)).'%')))->count();
			$this->setPageSeo(array('title'=>$listLink['info']['title'].'by 人来风微信营销','keywords'=>$listLink['info']['title'].$listLink['info']['desc'],'description'=>$listLink['info']['desc'].'by 人来风微信营销'));
			//热门
			$listLink['hotDoor'] = M()->table('tp_home_list_link as link')->join(array('LEFT JOIN tp_home_list as list on list.id=link.listid'))->where(array('link.companyid'=>'1','link.listid'=>array('in','223,232')))->field('list.title,link.listid,link.title as lTitle,link.pic,link.url')->select();
			if($listLink['hotDoor']){
				foreach($listLink['hotDoor'] as $lhKey=>$lhVal){
					$listLink['hotDoor'][$lhKey]['readcount'] = M('history_page_browsing')->where(array('pagelink'=>$lhVal['url'],'companyid'=>'1'))->count();
					$txt= $lhVal['url'];
					$re1='.*?';
					$re2='(\\d+)';
					if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
						$listLink['hotDoor'][$lhKey]['id']=$matches[1][0];
					}
				}
			}
			if($listLink['hotDoor']){
				$listLink['hotDoor'] = arraySort($listLink['hotDoor'],$listLink['hotDoor'][$lhKey]['readcount'],'SORT_ASC');
			}
			//回复内容
			$listLink['comment'] = M('home_info_comment')->where(array('companyid'=>'1','listid'=>$listid,'infoid'=>$id))->field('name,info')->order('id DESC')->limit('3')->select();
			$this->assign('list',$listLink);
			//PV
			M('home_info')->where('id='.$id)->setInc('readcount');
			
			/*
			$data['companyid'] = '1';
			$data['pagelink'] = C('site_url').U('Index/hearsayInfo',array('listid'=>$listid,'id'=>$id,'companyid'=>1));;
			$data['createtime'] = time();
			M('history_page_browsing')->where(array('companyid'=>'1'))->add($data);
		    */
			$this->display();
		}
	}
	//案例库
	public function cases(){
		$this->setPageSeo(array('title'=>'人来风案例库','keywords'=>'人来风,mobiwind,案例库,生活服务案例,移动电商案例,营销活动案例,微信案例,微信营销案例,营销案例','description'=>'人来风案例库为您实时分享第一手品牌客户移动营销案例：生活服务案例,移动电商案例,营销活动案例'));
		$listLink['life'] = M('home_list_link')->where(array('listid'=>'57','companyid'=>1))->field('listid,title,pic,url')->order('sort ASC,updatetime DESC')->select();
		if($listLink['life']){
			foreach($listLink['life'] as $Key=>$Val){
				$txt= $Val['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$listLink['life'][$Key]['id']=$matches[1][0];
				}
			}
		}
		$listLink['market'] = M('home_list_link')->where(array('listid'=>'58','companyid'=>'1'))->field('listid,title,pic,url')->order('sort ASC,updatetime DESC')->select();
		if($listLink['market']){
			foreach($listLink['market'] as $Key=>$Val){
				$txt= $Val['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$listLink['market'][$Key]['id']=$matches[1][0];
				}
			}
		}
		$listLink['society'] = M('home_list_link')->where(array('listid'=>'59','companyid'=>'1'))->field('listid,title,pic,url')->order('sort ASC,updatetime DESC')->select();
		if($listLink['society']){
			foreach($listLink['society'] as $Key=>$Val){
				$txt= $Val['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$listLink['society'][$Key]['id']=$matches[1][0];
				}
			}
		}
		$this->assign('list',$listLink);
		$this->display();
	}
	//案例详情
	public function caseInfo(){
		$listid = $this->_get('listid');
		$id = $this->_get('id');
		$info = M('home_info')->where(array('companyid'=>'1','id'=>$id))->field('title,desc,info,shareimg')->find();
		$this->setPageSeo(array('title'=>$info['title'].'by 人来风微信营销','keywords'=>$info['title'].$info['desc'],'description'=>$info['desc'].'by 人来风微信营销'));
		if($listid=='57'){
			$info['listTitle'] = '生活服务业';
		}elseif($listid=='58'){
			$info['listTitle'] = '营销活动';
		}elseif($listid=='59'){
			$info['listTitle'] = '移动电商';
		}
		//O2O最新推荐
		$info['new'] = M()->table('tp_home_list_link as link')->join(array('LEFT JOIN tp_home_list as list on list.id=link.listid'))->where(array('link.companyid'=>'1','link.listid'=>array('in','223,232')))->field('list.title,link.listid,link.pic,link.url')->order('link.id DESC')->limit('8')->select();
		if($info['new']){
			foreach($info['new'] as $lhKey=>$lhVal){
				$txt= $lhVal['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$nid=$matches[1][0];
				}
				$info['new'][$lhKey]['info'] = M('home_info')->where(array('id'=>$nid,'companyid'=>'1'))->field('id,title,praise')->find();
			}
		}
		//O2O最新推荐
		$info['new'] = M()->table('tp_home_list_link as link')->join(array('LEFT JOIN tp_home_list as list on list.id=link.listid'))->where(array('link.companyid'=>'1','link.listid'=>array('in','223,232')))->field('list.title,link.listid,link.pic,link.url')->limit(0,10)->select();
		if($info['new']){
			foreach($info['new'] as $lhKey=>$lhVal){
				$txt= $lhVal['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$nid=$matches[1][0];
				}
				$newinfo = M('home_info')->where(array('id'=>$nid,'companyid'=>'1'))->field('id,title,praise')->find();
				$info['new'][$lhKey]['title'] = $lhVal['title'];
				$info['new'][$lhKey]['listid'] = $lhVal['listid'];
				$info['new'][$lhKey]['pic'] = $lhVal['pic'];
				$info['new'][$lhKey]['infoid'] = $newinfo['id'];
				$info['new'][$lhKey]['infotitle'] = $newinfo['title'];
				$info['new'][$lhKey]['praise'] = $newinfo['praise'];
			}
		}
		$this->assign('info',$info);
		$this->display();
	}
	//客服中心
	public function serviceList(){
		$id = $this->_get('id');
		if($id == 56){
			$this->setPageSeo(array('title'=>'官方通告-人来风客服中心','keywords'=>'官方通告,人来风,mobiwind,S-CRM,新产品，新功能,发布信息,产品信息,公司动态信息,微信政策,微信公众平台','description'=>'人来风官方通告提供人来风S-CRM新产品新功能发布信息，产品信息，公司动态信息，微信政策信息等'));
		}elseif($id == 142){
			$this->setPageSeo(array('title'=>'新手入门-人来风客服中心','keywords'=>'新手入门,人来风,mobiwind,S-CRM,新品发布,使用帮助','description'=>'人来风新手入门提供人来风S-CRM产品使用说明,新手入门'));
		}
		$count = M('home_list_link')->where(array('listid'=>$id,'companyid'=>'1'))->count();
		$pages = ceil($count/6);
		$page = $this->_get('page')>0?$this->_get('page'):'1';
		$beginNum = ($page-1)*6;
		$endNum = $page*6;
		$prevpage = $page-1;
		$nextpage = $page+1;
		$listLink = M('home_list')->where(array('id'=>$id,'companyid'=>'1'))->field('id,title')->find();
		$listLink['listLink'] = M('home_list_link')->where(array('listid'=>$id,'companyid'=>'1'))->field('url')->order('sort ASC,updatetime DESC')->limit($beginNum,$endNum)->select();
		if($listLink['listLink']){
			foreach($listLink['listLink'] as $llKey=>$llVal){
				$txt= $llVal['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$infoId=$matches[1][0];
				}
				$info = M('home_info')->where(array('id'=>$infoId,'companyid'=>'1'))->field('id,title,author,praise,updatetime,createtime')->find();
				$listLink['listLink'][$llKey]['id'] = $info['id'];
				$listLink['listLink'][$llKey]['title'] = $info['title'];
				$listLink['listLink'][$llKey]['author'] = $info['author'];
				$listLink['listLink'][$llKey]['praise'] = $info['praise'];
				$listLink['listLink'][$llKey]['updatetime'] = $info['updatetime'];
				$listLink['listLink'][$llKey]['createtime'] = $info['createtime'];
			}
		}
		/* $k = 0;
		foreach($listLink['listLink'] as $key => $val){
			$listLink['listLink'][$k]['id'] = $val['info']['id'];
			$listLink['listLink'][$k]['title'] = $val['info']['title'];
			$listLink['listLink'][$k]['author'] = $val['info']['author'];
			$listLink['listLink'][$k]['praise'] = $val['info']['praise'];
			$listLink['listLink'][$k]['updatetime'] = $val['info']['updatetime'];
			$listLink['listLink'][$k]['createtime'] = $val['info']['createtime'];
			$k++;
		}
		if($listLink['listLink']){
			$listLink['listLink'] = arraySort($listLink['listLink'],'updatetime');
		} */
		//最多浏览
		$listLink['more'] = M('home_list_link')->where(array('listid'=>$id,'companyid'=>'1'))->field('listid,url')->select();
		if($listLink['more']){
			foreach($listLink['more'] as $mKey=>$mVal){
				$listLink['more'][$mKey]['readcount'] = M('history_page_browsing')->where(array('pagelink'=>$mVal['url'],'companyid'=>'1'))->count();
				$txt= $mVal['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$infoId=$matches[1][0];
				}
				$listLink['more'][$mKey]['info'] = M('home_info')->where(array('id'=>$infoId,'companyid'=>'1'))->field('id,title')->find();
			}
		}
		if($listLink['more']){
			$listLink['more'] = arraySort($listLink['more'],$listLink['more'][$mKey]['readcount'],'SORT_ASC');
		}
		//PV
		$data['companyid'] = '1';
		$data['pagelink'] = C('site_url').U('Index/serviceList',array('id'=>$id,'companyid'=>1));
		$data['createtime'] = time();
		M('history_page_browsing')->where(array('companyid'=>'1'))->add($data);
		$this->assign('list',$listLink);
		$this->assign('beginNum',$beginNum);
		$this->assign('endNum',$endNum);
		$this->assign('prevpage',$prevpage);
		$this->assign('nextpage',$nextpage);
		$this->assign('pages',$pages);
		$this->assign('page',$page);
		$this->display();
	}
	//客服中心详情
	public function serviceInfo(){
		if(IS_POST){
			$listid = $this->_post('listid');
			$infoid = $this->_post('infoid');
			$_POST['info'] = $this->_post('info');
			if(empty($_POST['info'])){
				$this->redirect(U('Index/serviceInfo',array('listid'=>$listid,'id'=>$infoid)));
			}
			$_POST['createtime'] = $_POST['updatetime'] = time();
			$comment = M('home_info_comment')->add($_POST);
			if($comment){
				$this->redirect(U('Index/serviceInfo',array('listid'=>$listid,'id'=>$infoid)));
			}
		}else{
			$listid = $this->_get('listid');
			$id = $this->_get('id');
			$listLink = M('home_list')->where(array('id'=>$listid,'companyid'=>'1'))->field('id,title')->find();
			$listLink['info'] = M('home_info')->where(array('id'=>$id,'companyid'=>'1'))->field('id,title,author,desc,info,praise,updatetime,createtime')->find();
			$this->setPageSeo(array('title'=>$listLink['info']['title'].'by 人来风微信营销','keywords'=>$listLink['info']['title'].$listLink['info']['desc'],'description'=>$listLink['info']['desc'].'by 人来风微信营销'));
			//回复内容
			$listLink['comment'] = M('home_info_comment')->where(array('listid'=>$listid,'infoid'=>$id))->field('name,info')->order('id DESC')->limit('3')->select();
			//最多浏览
			$listLink['more'] = M('home_list_link')->where(array('listid'=>$listid,'companyid'=>'1'))->field('listid,url')->select();
			if($listLink['more']){
				foreach($listLink['more'] as $mKey=>$mVal){
					$listLink['more'][$mKey]['readcount'] = M('history_page_browsing')->where(array('pagelink'=>$mVal['url'],'companyid'=>'1'))->count();
					$txt= $mVal['url'];
					$re1='.*?';
					$re2='(\\d+)';
					if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
						$infoId=$matches[1][0];
					}
					$listLink['more'][$mKey]['info'] = M('home_info')->where(array('id'=>$infoId,'companyid'=>'1'))->field('id,title')->find();
				}
			}
			if($listLink['more']){
				$listLink['more'] = arraySort($listLink['more'],$listLink['more'][$mKey]['readcount'],'SORT_ASC');
			}
			$this->assign('info',$listLink);
			//PV
			$data['companyid'] = '1';
			$data['pagelink'] = C('site_url').U('Index/serviceInfo',array('listid'=>$listid,'id'=>$id,'companyid'=>1));
			$data['createtime'] = time();
			M('history_page_browsing')->where(array('companyid'=>'1'))->add($data);
			$this->display();
		}
	}
	//渠道代理
	public function agent(){
		$this->setPageSeo(array('title'=>'渠道代理-人来风Mobiwind-SCRM','keywords'=>'人来风,mobiwind,S-CRM,微信合作,微信渠道代理,移动红利','description'=>'人来风S-CRM渠道代理申请，共享移动红利'));
		if(IS_POST){
			$_POST['createtime'] = $_POST['updatetime'] = time();
			$agentlist = M('agent')->add($_POST);
			if($agentlist){
				$this->redirect(U('Index/agent'));
			}
		}else{
			$areaAll = M('area')->where(array('parentid'=>'1017'))->field('id,name')->select();
			$this->assign('list',$areaAll);
			$this->display();
		}
	}
	//合作伙伴
	public function partners(){
		$this->setPageSeo(array('title'=>'合作伙伴-人来风Mobiwind','keywords'=>'人来风,Mobiwind,合作伙伴、品牌合作、小米、腾讯、微信、中国电信、中国移动、广告公司、公关公司','description'=>'人来风的合作伙伴：小米、腾讯、微信、人来风自媒体'));
		$this->display();
	}
	//招聘信息
	public function joinus(){
		$this->setPageSeo(array('title'=>'招聘信息-人来风Mobiwind','keywords'=>'人来风,Mobiwind,招聘,前端工程师,网页设计师,JS,销售顾问,产品经理,执行,市场专员','description'=>'火热招聘中:前端工程师,网页设计师,JS,销售顾问,产品经理,市场专员'));
		$this->display();
	}
	//联系我们
	public function contactUs(){
		$this->setPageSeo(array('title'=>'联系我们-人来风Mobiwind','keywords'=>'联系我们,人来风,Mobiwind','description'=>'联系我们-人来风Mobiwind'));
		$this->display();
	}
	//推广合作
	public function extendCooperation(){
		$this->setPageSeo(array('title'=>'CPA推广合作-人来风Mobiwind-SCRM','keywords'=>'人来风,mobiwind,S-CRM,推广合作,微信推广,二维码推广,CPA合作','description'=>'人来风S-CRM推广合作-通过账号分享链接、二维码分享等CPA方式推广人来风微信平台，赢取免费正式版账号的机会'));
		$this->display();
	}
	//登录
	public function login(){
		if(IS_POST){
			$usersInfoWhere['username'] = $this->_post('username');
			$userInfo = $this->usersModel->where($usersInfoWhere)->find();
			if (empty($userInfo)){
				$this->error(L('ServerBusyPrompt'));
			}
			$password=get_md5_password($this->_post('password'));
			if($password===$userInfo['password']){
				$companyInfo = $this->companyModel->where(array('id'=>$userInfo['companyid']))->find();
				if (empty($companyInfo)){
					$this->error(L('ServerBusyPrompt'));
				}
	
				if($companyInfo['viptime']< time() && $companyInfo['gid'] != 5){
					$this->error('抱歉！您的账号已到期。续费请联系您的客户经理，系统将于一周内自动删除您的历史数据。');
				}
				if($companyInfo['status']==0){
					$this->error('您的试用申请正在审核中，请耐心等待。');
				}elseif ($companyInfo['status']==2){
					$this->error('抱歉！您的试用申请未被通过，如有疑问请联系您的客户经理。');
				}
				if ($companyInfo['isclose']==1){
					$this->error('您的账号已被冻结，请联系您的客户经理。');
				}
				//记住密码
				$username = $this->_post('username');
				$password = $this->_post('password');
				$rememberPassword = $this->_post('rememberPassword') ? $this->_post('rememberPassword') : 0 ;
				if($rememberPassword == 1){
					cookie('username',$username,time()+360000000);
					cookie('password',$password,time()+360000000);
					cookie('rememberPassword',$rememberPassword,time()+360000000);
				}
				if(!is_dir('./Uploads/'.$userInfo['companyid'].'/image')){
					check_dir('./Uploads/'.$userInfo['companyid'].'/image');//创建文件夹
				}
				$companyGroupInfo = $this->companyGroupModel->where(array('id'=>$companyInfo['gid']))->field('name')->find();
				if (empty($companyGroupInfo)){
					$this->error(L('ServerBusyPrompt'));
				}
				session(null);
				session('uid',$userInfo['id']);
				$userInfo['shopsid'] = $userInfo['shopsid'] > 0 ? $userInfo['shopsid'] : '0';
				session('shopsid',$userInfo['shopsid']);
				session('uname',$userInfo['username']);
				session('truename',$userInfo['truename']);
				session('phone',$userInfo['phone']);
				session('cid',$userInfo['companyid']);
				session('cname',$companyInfo['name']);
				session('viptime',$companyInfo['viptime']);
				session('logourl',$companyInfo['logourl']);
				session('companyPermissions',explode(',', $companyInfo['permissions']));
				if($userInfo['isboss'] == 1){
					session('permissions',explode(',', $companyInfo['permissions']));
				}else{
					session('permissions',explode(',', $userInfo['permissions']));
				}
				session('maximgspace',$companyInfo['maximgspace']);
				session('gid',$companyInfo['gid']);
				session('gname',$companyGroupInfo['name']);
				session('wechatfollowlink',$companyInfo['wechatfollowlink']);
				$saveCompanyDate['lasttime'] = time();
				$saveCompanyDate['lastip'] = get_client_ip(0);
				if(format_time(time(),'d') == '01'){
					$saveCompanyDate['nowrequestsnum'] = 0;
				}
				$this->usersModel->where(array('id'=>$userInfo['id']))->save();
				$this->success(L('LoginSuccessful'),U('User/System/systemInfo'));
			}else{
				$this->error(L('WrongPW'));
			}
		}else{
			if(session('uid')){
				$this->redirect(U('Index/index'));
			}
			$this->setPageSeo(array('title'=>'企业登录-人来风-MobiWind','keywords'=>'企业登录人来风，企业登录MobiWind，人来风，MobiWind，人来风-MobiWind','description'=>'企业登录-人来风-MobiWind'));
			$this->display();
		}
	}
	//登录
	public function login_scrm5(){
		if(IS_POST){
			$usersInfoWhere['username'] = $this->_post('username');
			$userInfo = $this->usersModel->where($usersInfoWhere)->find();
			if($userInfo){
				$password=get_md5_password($this->_post('password'));
				if($password===$userInfo['password']){
					$companyInfo = $this->companyModel->where(array('id'=>$userInfo['companyid']))->find();
					if($companyInfo['status']==0){
						$ajax['msg'] = "您的试用申请正在审核中，请耐心等待。";
					}elseif ($companyInfo['status']==2){
						$ajax['msg'] = "抱歉！您的试用申请未被通过，如有疑问请联系您的客户经理。";
					}elseif ($companyInfo['status']==4){
						$ajax['code'] = 500;
						session('cid',$userInfo['companyid']);
						session('uid',$userInfo['id']);
						session('uname',$userInfo['username']);
						session('gid',$companyInfo['gid']);
						session('truename',$userInfo['truename']);
						session('cname',$companyInfo['name']);
						session('maximgspace',$companyInfo['maximgspace']);
						$ajax['msg'] = "请先填写入住流程。";
						$ajax['companyid'] = session('cid');
					}else{
						if ($companyInfo['isclose']==1){
							$ajax['msg'] = "您的账号已被冻结，请联系您的客户经理。";
						}else{
							if($companyInfo['viptime']< time() && $companyInfo['gid'] != 5){
								$ajax['msg'] = "抱歉！您的账号已到期。续费请联系您的客户经理，系统将于一周内自动删除您的历史数据。";
							}
							//记住密码
							$username = $this->_post('username');
							$password = $this->_post('password');
							$rememberPassword = $this->_post('rememberPassword') ? $this->_post('rememberPassword') : 0 ;
							if($rememberPassword == 1){
								cookie('username',$username,time()+360000000);
								cookie('password',$password,time()+360000000);
								cookie('rememberPassword',$rememberPassword,time()+360000000);
							}
							if(!is_dir('./Uploads/'.$userInfo['companyid'].'/image')){
								check_dir('./Uploads/'.$userInfo['companyid'].'/image');//创建文件夹
							}
							$companyGroupInfo = $this->companyGroupModel->where(array('id'=>$companyInfo['gid']))->field('name')->find();
							if (empty($companyGroupInfo)){
								//$this->error(L('ServerBusyPrompt'));
							}
							session(null);
							session('uid',$userInfo['id']);
							$userInfo['shopsid'] = $userInfo['shopsid'] > 0 ? $userInfo['shopsid'] : '0';
							session('shopsid',$userInfo['shopsid']);
							session('uname',$userInfo['username']);
							session('truename',$userInfo['truename']);
							session('phone',$userInfo['phone']);
							session('cid',$userInfo['companyid']);
							session('cname',$companyInfo['name']);
							session('viptime',$companyInfo['viptime']);
							session('logourl',$companyInfo['logourl']);
							session('companyPermissions',explode(',', $companyInfo['permissions']));
							if($userInfo['isboss'] == 1){
								session('permissions',explode(',', $companyInfo['permissions']));
							}else{
								session('permissions',explode(',', $userInfo['permissions']));
							}
							session('maximgspace',$companyInfo['maximgspace']);
							session('gid',$companyInfo['gid']);
							session('gname',$companyGroupInfo['name']);
							session('wechatfollowlink',$companyInfo['wechatfollowlink']);
							$saveCompanyDate['lasttime'] = time();
							$saveCompanyDate['lastip'] = get_client_ip(0);
							if(format_time(time(),'d') == '01'){
								$saveCompanyDate['nowrequestsnum'] = 0;
							}
							$this->usersModel->where(array('id'=>$userInfo['id']))->save();
							//$this->success(L('LoginSuccessful'),U('User/System/systemInfo'));
							$ajax['code']=200;
							$ajax['msg']="登录成功，正在跳转。。。";
						}
					}
				}else{
					//$this->error(L('WrongPW'));
					$ajax['code']=300;
					$ajax['msg']="账号密码错误";
				}
			}else{
				$ajax['code']=300;
				$ajax['msg']="账号不存在";
			}
			
			echo json_encode($ajax);
		}else{
			if(session('uid')){
				$this->redirect(U('Index/index'));
			}
			$this->setPageSeo(array('title'=>'企业登录-人来风-MobiWind','keywords'=>'企业登录人来风，企业登录MobiWind，人来风，MobiWind，人来风-MobiWind','description'=>'企业登录-人来风-MobiWind'));
			$this->display();
		}
	}
	//注册
	public function register(){
		if(IS_POST){
			$model = new Model();
			$model->startTrans();//开启事务
			$companyGroupInfo = $this->companyGroupModel->where(array('id'=>1))->field('id,permissions,maximgspace,maxrequestsnum')->find();
			if (empty($companyGroupInfo)){
				$this->error(L('ServerBusyPrompt'),U('Index/register'));
			}
			$viptime = time()+604800;//注册一周试用期
			$companyInfoData['viptime'] = $viptime;
			$companyInfoData['permissions'] = $companyGroupInfo['permissions'];
			$companyInfoData['gid'] = $companyGroupInfo['id'];
			$companyInfoData['maximgspace'] = $companyGroupInfo['maximgspace'];
			$companyInfoData['maxrequestsnum'] = $companyGroupInfo['maxrequestsnum'];
			$companyInfoData['wechatnum'] = 1;
			$companyInfoData['workernum'] = 0;
			$companyInfoData['shopsnum'] = 1;
			$companyInfoData['status'] = 0;
			$companyInfoData['isclose'] = 0;
			$companyInfoData['updatetime'] = $companyInfoData['createtime'] = time();
			$companyInfoInsterReturn = $this->companyModel->add($companyInfoData);
			
			$_POST['companyid'] = $companyInfoInsterReturn;
			//$_POST['password'] = get_md5_password($this->_post('repeat_password'));
			//$_POST['truePassword'] = $this->_post('repeat_password','trim');
			$_POST['updatetime'] = $_POST['createtime'] = time();
			$_POST['createip'] = get_client_ip(0);
			$_POST['applyname'] = $this->_post('truename');
			$_POST['applymobile'] = $this->_post('phone');
			//$_POST['applyemail'] = $this->_post('email');
			$_POST['isboss'] = 1;
			$usersInsterReturn = $this->usersModel->add($_POST);
			
			$mallHomeData['companyid'] = $companyInfoInsterReturn;
			$mallHomeData['tplid'] = 1;
			$mallHomeData['updatetime'] = $mallHomeData['createtime'] = time();
			$mallHomeReturn = M('mall_home')->add($mallHomeData);
			
			if($companyInfoInsterReturn && $usersInsterReturn && $mallHomeReturn){
				$model->commit();//事务提交
				check_dir('./Uploads/'.$companyInfoInsterReturn.'/image');//创建文件夹
				//$ajax['code']='200';
				//$ajax['msg']="您的申请已经提交，我们最顶尖的AM会在一个工作日内审核";
				
				$this->redirect(U('Index/registerOk'));//提示 审核
				$this->redirect(U('Index/index'));//提示 审核
			}else{
				$model->rollback();//事务回滚
				//$ajax['code']='300';
				//$ajax['msg']="信息填写不正确";
				$this->error(L('ServerBusyPrompt'),U('Index/register'));
			}
			//echo json_encode($ajax);
		}else{
			/* if(session('uid')){
				$this->redirect(U('Index/index'));
			} */
			$this->setPageSeo(array('title'=>'免费开通人来风账号','keywords'=>'免费开通,人来风,mobiwind','description'=>'立即注册人来风账号-免费开通，我们的AM将在24小时内与您确认注册信息'));
			$this->display();
		}
	}
	//注册新
	public function register_new(){
		if(IS_POST){
			$data['id'] = guidNow();
			$data['name']=$this->_request("truename");
			$data['phone']=$this->_request("phone");
			$data['companyname']=$this->_request("companyName");
			$data['updatetime']=$data['createtime']=time();
			$data['status']=1;
 			$agent = M('agent')->add($data);
			if($agent){
				$ajax['code']='200';
				$ajax['msg']="您的申请已经提交，我们最顶尖的AM会在一个工作日内审核";
	
			}else{
				$ajax['code']='300';
				$ajax['msg']="信息填写不正确";
			}
			echo json_encode($ajax);
		}
	}
	/**
	 * SCRM5的注册
	 * @author Asa<asa@renlaifeng.cn>
	 * @since  2016-8-24
	 */
	public function register_scrm5(){
		if(IS_POST){
			$count = M("users")->where(array("username"=>$this->_post('loginname')))->count();
			if($count){
				$ajaxReturn['code'] = '100';
				$ajaxReturn['tips'] = '登陆用户名重复';
			}else{
				$data['brandname']=$_POST['companyname'];
				$data['companyname']=$_POST['companyname'];
				$data['updatetime'] = $data['createtime'] = time();
				$res1 = M("check_customer_info")->add($data);
				
				$data2['companyid'] = $res1;
				$data2['name']=$_POST['companyname'];
				$data2['tel']=$_POST['phone'];
				$data2['status']=4;
				$data2['gid']=8;
				$data2['updatetime'] = $data2['createtime'] = time();
				$res2 = M("company")->add($data2);
				
				$data3['companyid'] = $res1;
				$data3['cid'] = $res2;
				$data3['loginname']=$_POST['loginname'];
				$data3['loginpwd']=$_POST['password'];
				$data3['tel']=$_POST['phone'];
				$data3['updatetime'] = $data3['createtime'] = time();
				$res3 = M("check_number_config")->add($data3);
				
				$data4['companyid'] = $res2;
				$data4['numid'] = $res3;
				$data4['isboss'] = 1;
				$data4['username'] = $this->_request("loginname");
				$data4['truename'] = $this->_request("name");
				$data4['truePassword'] = $this->_request("password");
				$data4['password'] = md5($this->_request("password"));
				$data4['phone'] = $this->_request("phone");
				$res4 = M("users")->add($data4);
				
	
				$data5['id'] = guidNow();
				$data5['name'] = $this->_post('name');
				$data5['phone'] = $this->_post('phone');
				$data5['companyname'] = $this->_post('companyname');
				$data5['industry'] = $this->_post('industry');
				$data5['loginname'] = $this->_post('loginname');
				$data5['password'] = $this->_post('password');
				if($this->_post('invitecode') != ''){
					$data5['invitecode'] = $this->_post('invitecode');
				}
				$data5['status'] = 1;
				$data5['agenttype'] = '1';
				$data5['updatetime'] = $data5['createtime'] = time();
				$agent = M('agent')->add($data5);
				if($agent&&$res4&&$res3&&$res2&&$res1){
					$ajaxReturn['code'] = '200';
					$ajaxReturn['id'] = $data['id'];
					$ajaxReturn['tips'] = '注册申请提交成功';
				}else{
					$ajaxReturn['code'] = '300';
					$ajaxReturn['tips'] = '提交失败';
				}
				//}
			}
		}
	}
	public function register_new2(){
		if(IS_POST){
			$data['name']=$this->_request("truename");
			$data['phone']=$this->_request("phone");
			$data['companyname']=$this->_request("companyName");
			$data['updatetime']=$data['createtime']=time();
			$data['status']=1;
			//dumo($data);exit;
			$agent = M('agent')->add($data);
			if($agent){
				$this->redirect(U('Index/registerOk'));//提示 审核
			}else{
				$this->error(L('ServerBusyPrompt'),U('Index/register'));
			}
			//echo json_encode($ajax);
		}
	}
	//注册成功
	public function registerOk(){
		$this->display();
	}
	//找回密码
	public function forgotPassword(){
		if(IS_POST){
			$usersWhere['email'] = $this->_post('email','trim');
			$usersCount = $this->usersModel->where($usersWhere)->field('id,truePassword')->find();
			if($usersCount){
				$mailer = new Mailer();
				$toList = array($usersWhere['email']);
				$subject = '人来风：您的登录密码';
				$content = '<div class="" id="qm_con_body"><div id="mailContentContainer" class="qmbox qm_con_body_content" style="">
	<div class="wrapper" style="margin: 20px auto 0; width: 500px; padding:15px auto 10px">
	<div class="header clearfix">
	<a href="'.C('site_url').'" class="logo" style="float:left" target="_blank">
	<img src="'.C('site_url').'/Tpl/Home/default/common/images/logo.png" width="150"></a>
	</div>
	<br style="clear:both; height:0"><div class="content" style="background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #E9E9E9; margin: 10px 0 0; padding: 30px;">
	<p> '.$usersWhere['email'].'，你好</p>
	<p>您的登录密码为：'.$usersCount['truePassword'].'</p>
	<p class="footer" style="border-top: 1px solid #DDDDDD; padding-top:6px; margin-top:25px; color:#838383;">© 2014 人来风&nbsp;&nbsp;|&nbsp;&nbsp;该邮件由系统发送，请勿回复</p>
	</div>
	</div>
	  </div></div>';
				$sendReturn = $mailer->sendMail($toList, $subject, $content);
				if ($sendReturn){
					$this->redirect(U('Index/forgotPasswordOk'));
				}else{
					$this->error(L('ServerBusyPrompt'));
				}
			}else{
				$this->error('邮箱不存在');
			}
		}else{
			$this->display();
		}
	}
	//找回密码
	public function forgotPasswordOk(){
		$this->display();
	}
	//退出
	public function logOut(){
		session(null);
		$this->success('退出成功',U('Index/index'));
	}
	//点赞
	public function praise(){
		$id = $this->_post('id');
		$praise = M('home_info')->where(array('id'=>$id))->setInc('praise');
		$newPraise = M('home_info')->where(array('id'=>$id))->getfield('praise');
		if($praise){
			$data['code'] = '200';
			$data['newPraise'] = $newPraise;
		}else{
			$data['code'] = '300';
			$data['newPraise'] = $newPraise;
		}
		echo json_encode($data);
	}
	//ajax 获得城市
	public function	getCity(){
		$provinceId = $this->_post('provinceId');
		$citys = M('area')->where(array('isshow'=>1,'parentid'=>$provinceId))->select();
		if($citys){
			$message['code'] = 200;
			$message['html'] = '';
			foreach($citys as $cKey=>$cVal){
				$message['html'].="<option value='".$cVal['id']."'>".$cVal['name']."</option>";
			}
		}else{
			$message['code'] = 300;
		}
		echo json_encode($message);
	}
	//jQuery数字滚动展示效果
	public function ajaxGetSystemData(){
		$ajaxData['code'] = 300;
		$ajaxData['num'] = 0;
		$oldNum = M('system_num')->where(array('companyid'=>1))->getField('num');
		$nowH = format_time(time(),'h');
		$addNum = 0;
		if( 7 <=$nowH && $nowH < 24 ){
			$addNum = rand(0, 3);
		}else if (0 <=$nowH && $nowH < 1) {
			$addNum = rand(0, 1);
		}else{
			$numArr = array(0,0,1);
			$numKey = rand(0, 7);
			$addNum = $numArr[$numKey];
		}
		$isfrist = $this->_post('isfrist');
		if($isfrist == '1'){
			$ajaxData['code'] = 200;
			$numlength = strlen($oldNum);
			$addZeroNum = 9-$numlength;
			$addZero = '';
			for($i=0;$i<$addZeroNum;$i++){
				$addZero .='0';
			}
			$ajaxData['num'] = $addZero.$oldNum;
			$ajaxData['isfrist'] = '0';
		}elseif($addNum && $isfrist == '0'){
			$ajaxData['isfrist'] = '0';
			$data['num'] = $oldNum+$addNum;
			$data['updatetime'] = time();
			$updateReturn  = M('system_num')->where(array('companyid'=>1))->save($data);
			if($updateReturn){
				$ajaxData['code'] = 200;
				$numlength = strlen($data['num']);
				$addZeroNum = 9-$numlength;
				$addZero = '';
				for($i=0;$i<$addZeroNum;$i++){
					$addZero .='0';
				}
				$ajaxData['num'] = $addZero.$data['num'];
			}
		}
		echo json_encode($ajaxData);
	}
	/**
	 * 公司注册后没有设置商城首页的添加数据
	 * mall_home添加数据
	 */
	public function addMallHomeList(){
		$conList = M('company')->field('id')->select();
		if($conList){
			foreach($conList as $key=>$val){
				$countHome = M('mall_home')->where(array('companyid'=>$val['id']))->count();
				if(!$countHome){
					$mallHomeData['companyid'] = $val['id'];
					$mallHomeData['tplid'] = 1;
					$mallHomeData['updatetime'] = $mallHomeData['createtime'] = time();
					M('mall_home')->add($mallHomeData);
				}
			}
		}
	}
/**************************************************************************************************/
	public function article(){
		$this->display();
	}
	//公告详情页
	public function articleInfo(){
		$articleInfo = M('article')->field('fArticleId,fArticleTitle,fArticleIntrod,fArticleContent')->where(array('fArticleId'=>$this->_get('id','intval'),'fArticleCategoryId'=>1,'fArticleIsShow'=>1))->find();
		$this->assign('info',$articleInfo);
		$this->display();
	}
	//产品与服务
	public function scheme(){
		$this->diningCount();
		$this->commonCount();
		$this->wechatMessageCount();
		$this->liuyanCount();
		$this->setPageSeo(array('title'=>'人来风产品与服务-人来风-MobiWind','keywords'=>'人来风报价、试用版，基础版，专业版，餐饮专业版，定制版，人来风，MobiWind，人来风-MobiWind','description'=>'立即申请试用，7天免费。人来风产品版本类型简介及详细报价。'));
		$this->display();
	}
	//关于我们
	public function aboutUs(){
		$this->diningCount();
		$this->commonCount();
		$this->wechatMessageCount();
		$this->liuyanCount();
		$this->setPageSeo(array('title'=>'关于我们-人来风-MobiWind','keywords'=>'关于人来风，SCRM，人来风SCRM，人来风，MobiWind，人来风-MobiWind，会员制移动营销，移动O2O','description'=>'MobiWind人来风是一个专注于SCRM的企业品牌。人来风SCRM不做入门级微信平台！我们不提供无视用户体验的功能堆砌类产品；我们不追求客户数量，只追求客户质量。人来风团队均由百万用户量级互联网产品团队倾力打造。低调、认真、务实、忠于原创、注重口碑！'));
		$this->display();
	}
	//修改数据
	public function saveData(){
		$users = M('users')->field('id,truename,email,phone')->where(array('isboss'=>1))->select();
		if($users){
			foreach($users as $key=>$val){
				$saveData['applyname'] = $val['truename'];
				$saveData['applyemail'] = $val['email'];
				$saveData['applymobile'] = $val['phone'];
				M('users')->where(array('id'=>$val['id']))->save($saveData);
			}
		}
	}
}
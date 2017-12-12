<?php
/**
 * 移动官网
 * Enter description here ...
 * @param unknown_type $haystack
 * @param unknown_type $needle
 */

class IndexAction extends WapBaseAction{
	private $homeInfo;	
	private $homeChannelList;
	public  $companyInfo;
	private $companyid;
	public function __construct(){
		parent::__construct();
		$this->companyid=session('wapcid');
		$this->companyInfo['name'] = session('cname');
		if($this->companyid == '1161'){
    		$shopsInfo = M('company_shops')->where(array('companyid'=>$this->companyid))->field('id,shopname,name,tel,longitude,latitude')->find();
    		$this->assign('shopsInfo',$shopsInfo);
		}
	}
	/**
	 *
	 * 欢迎页（贰千金定制）
	 *
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-1-7
	 */
	public function Welcome(){
	    $this->setPageTitle(array('title'=>'贰千金-移动官网'));
	    $info['sharefriendstitle'] = '贰千金-移动官网';
	    $info['shareurl'] = C('site_url').U('Index/Welcome',array('companyid'=>$this->companyid));
	    $info['shareimg'] = C('site_url').'/Tpl/Wap/default/common/img/ladybund_share.jpg';
	    $info['sharedes'] = '点击了解更多';
	    $this->assign('info',$info);
    	$this->display();
	}
	/**
	 * 首页
	 * Enter description here ...
	 */
	public function index(){
	    $homeInfo = D('Home')->getWhereHomeInfo(array('companyid'=>$this->companyid));
	    if(empty($homeInfo)){
	        $this->redirect(U('System/notFound',array('companyid'=>$this->companyid)));
	    }
		$where['homeid']=$homeInfo['id'];
		$where['isshow']=1;
		$where['companyid']=$this->companyid;
		$flash = D('Home_flash')->getWhereHomeFlashList($where);
		$this->assign('flash',$flash);
		$homeChannelList=D('Home_channel')->getWhereHomeChannelList(array('companyid'=>$this->companyid,'homeid'=>$homeInfo['id']));//获得频道信息
		/* if($homeChannelList){
			foreach ($homeChannelList as $hclKey=>$hclVal){
				$homeChannelList[$hclKey]['name'] = $hclVal['title'];
			}
		}
		*/
		$this->assign('info',$homeChannelList);//频道列表（首页频道）
		
		$this->assign('homeInfo',$homeInfo);
		$this->setPageTitle(array('title'=>$homeInfo['title']));
		$this->display('1'.$homeInfo['tplid'].'_index');
	}
	/**
	 * 列表
	 * Enter description here ...
	 */
	public function lists(){
		$where['companyid'] = $this->companyid;
		$where['listid'] = $this->_get('listid');
		
		$listInfo = D('Home_list')->getWhereHomeListInfo(array('id'=>$where['listid'],'companyid'=>$this->companyid));
		$count = M('home_list_link')->where($where)->count();   // 官网列表
		
		if($listInfo['tplid']==23 || $listInfo['tplid']==88 || $listInfo['tplid']==89 || $listInfo['tplid']==90 || $listInfo['tplid']==91 || $listInfo['tplid']==2 || $listInfo['tplid']==1 || $listInfo['tplid']==92 || $listInfo['tplid']==93 || $listInfo['tplid']==6){
			$limit = 10;
			$linkList = M('home_list_link')->where($where)->field('id,listid,title,info,pic,url')->order('sort,updatetime DESC')->limit($limit)->select();  // 官网列表
			$this->assign('count',$count);
		}else{
			if(!$listInfo){
				echo '无信息'; die();
			}
			if($_GET['p']==false){
				$page=1;
			}else{
				$page=$_GET['p'];
			}
			$pageSize=10;
			$pagecount=ceil($count/$pageSize);
			if($page > $count){$page=$pagecount;}
			if($page >=1){$p=($page-1)*$pageSize;}
			if($p==false){$p=0;}
			
			if($listInfo['tplid'] == '1161'){
				$linkList=M('home_list_link')->where($where)->order('sort ASC,updatetime DESC')->select();  // 官网列表
			}else{
				$linkList=M('home_list_link')->where($where)->order('sort ASC,updatetime DESC')->limit("{$p},".$pageSize)->select();  // 官网列表
			}
			
			
			$linkList=M('home_list_link')->where($where)->order('sort ASC,updatetime DESC')->limit("{$p},".$pageSize)->select();  // 官网列表
			$this->assign('page',$pagecount);
			$this->assign('p',$page);
		}
		
		$flash = D('Home_list_flash')->getWhereHomeListFlashList(array('homelistid'=>$where['listid'],'companyid'=>$this->companyid,'isshow'=>1));
		$this->assign('listInfo',$listInfo);
		$this->assign('flash',$flash);
		$this->assign('res',$linkList);
		$this->setPageTitle(array('title'=>$listInfo['title']));
		$this->display('1'.$listInfo['tplid'].'_list');
	}
	
	/**
	 * 详情
	 * Enter description here ...
	 * @param unknown_type $contentid
	 */
	public function info(){
		$where['companyid']=$this->companyid;
		$where['isshow'] = 1;
		$where['id'] = $this->_get('id');
		$info=D('Home_info')->getWhereHomeInfo($where);
		if($info){
 		    //新版上线需修改阅读数字段
			M('home_info')->where(array('id'=>$where['id'],'companyid'=>$this->companyid))->setInc('readcount');
 		    
			$info['info'] = htmlspecialchars_decode($info['info']);
			$wechatInfo = M('wechats')->field('wxname')->where(array('companyid'=>$this->companyid))->find();
			if($wechatInfo){
				$info['wxname'] = $wechatInfo['wxname'];
			}
			//M('history_page_view')->add(array('companyid'=>$this->companyid,'pid'=>$where['id'],'module'=>'home_info','createtime'=>time()));
			$this->assign('res',$info);			//内容详情;
			$this->setPageTitle(array('title'=>$info['title']));

		    $this->display('1'.$info['tplid'].'_content');
		}else{
			echo '该内容已被发布者删除';
		}
	}
	/**
	 * 显示更多（123模板）
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-1-22
	 */
	public function ajaxMoreList123(){
		$data['code'] = 300;
		$where['companyid'] = $this->companyid;
		$where['listid'] = $this->_post('listid');
		$limit = 10;
		$startNumber = $this->_post('startNumber'); // 本次查询的开始条数
		$startNumber = $startNumber ? $startNumber : 0;
		$linkList = M('home_list_link')->where($where)->field('id,title,url,pic,info')->order('sort,updatetime DESC')->limit($startNumber,$limit)->select();
			
		$string = '';
		if($linkList){
			foreach($linkList as $key=>$val){
				$string .= '<li><a href="'.$val['url'].'"><div class="menuimg"><img src="'.$val['pic'].'" /></div><p class="title">'.$val['title'].'</p><p class="text">'.$val['info'].'</p></a></li>';
			}
		}
		if($string){
			$data['code'] = 200;
			$data['tips'] = $string;
		}
		echo json_encode($data);
	}
	/**
	 * 显示更多（188模板）
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-1-22
	 */
	public function ajaxMoreList188(){
		$data['code'] = 300;
	
		$where['companyid'] = $this->companyid;
		$where['listid'] = $this->_post('listid');
		$limit = 10;
		$startNumber = $this->_post('startNumber'); // 本次查询的开始条数
		$startNumber = $startNumber ? $startNumber : 0;
		$linkList = M('home_list_link')->where($where)->field('id,title,url,pic')->order('sort,updatetime DESC')->limit($startNumber,$limit)->select();
			
		$string = '';
		if($linkList){
			foreach($linkList as $key=>$val){
				$string .= '<li><a href="'.$val['url'].'"><img src="'.$val['pic'].'" /><span>'.$val['title'].'</span></a></li>';
			}
		}
		if($string){
			$data['code'] = 200;
			$data['tips'] = $string;
		}
		echo json_encode($data);
	}
	/**
	 * 显示更多（189模板）
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-1-22
	 */
	public function ajaxMoreList189(){
		$data['code'] = 300;
	
		$where['companyid'] = $this->companyid;
		$where['listid'] = $this->_post('listid');
		$limit = 10;
		$startNumber = $this->_post('startNumber'); // 本次查询的开始条数
		$startNumber = $startNumber ? $startNumber : 0;
		$linkList = M('home_list_link')->where($where)->field('id,title,url,pic')->order('sort,updatetime DESC')->limit($startNumber,$limit)->select();
			
		$string = '';
		if($linkList){
			foreach($linkList as $key=>$val){
				$string .= '<li><a href="'.$val['url'].'"><img src="'.$val['pic'].'" /><span>'.$val['title'].'</span></a></li>';
			}
		}
		if($string){
			$data['code'] = 200;
			$data['tips'] = $string;
		}
		echo json_encode($data);
	}
	/**
	 * 显示更多（190模板）
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-1-22
	 */
	public function ajaxMoreList190(){
		$data['code'] = 300;
		
		$where['companyid'] = $this->companyid;
		$where['listid'] = $this->_post('listid');
		$limit = 10;
		$startNumber = $this->_post('startNumber'); // 本次查询的开始条数
		$startNumber = $startNumber ? $startNumber : 0;
		$linkList = M('home_list_link')->where($where)->field('id,title,url,pic')->order('sort,updatetime DESC')->limit($startNumber,$limit)->select();
		 
		$string = '';
		if($linkList){
			foreach($linkList as $key=>$val){
				$string .= '<div class="dingzhi_yh2_2 dingzhi_yh2_3"><a href="'.$val['url'].'"><img src="'.$val['pic'].'"><span>'.$val['title'].'</span></a></div>';
			}
		}
		if($string){
			$data['code'] = 200;
			$data['tips'] = $string;
		}
		echo json_encode($data);
	}
	/**
	 * 显示更多（191模板）
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-1-22
	 */
	public function ajaxMoreList191(){
		$data['code'] = 300;
	
		$where['companyid'] = $this->companyid;
		$where['listid'] = $this->_post('listid');
		$limit = 10;
		$startNumber = $this->_post('startNumber'); // 本次查询的开始条数
		$startNumber = $startNumber ? $startNumber : 0;
		$linkList = M('home_list_link')->where($where)->field('id,title,url,pic')->order('sort,updatetime DESC')->limit($startNumber,$limit)->select();
			
		$string = '';
		if($linkList){
			foreach($linkList as $key=>$val){
				$string .= '<div class="hotel-list"><a href="'.$val['url'].'"><img src="'.$val['pic'].'"><span>'.$val['title'].'</span></a></div>';
			}
		}
		if($string){
			$data['code'] = 200;
			$data['tips'] = $string;
		}
		echo json_encode($data);
	}
	/**
	 * 显示更多（12模板）
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-2-1
	 */
	public function ajaxMoreList12(){
		$data['code'] = 300;
		$where['companyid'] = $this->companyid;
		$where['listid'] = $this->_post('listid');
		$limit = 10;
		$startNumber = $this->_post('startNumber'); // 本次查询的开始条数
		$startNumber = $startNumber ? $startNumber : 0;
		$linkList = M('home_list_link')->where($where)->field('id,title,info,url,pic')->order('sort,updatetime DESC')->limit($startNumber,$limit)->select();
			
		$string = '';
		if($linkList){
			foreach($linkList as $key=>$val){
				$string .= '<dl><a href="'.$val['url'].'"><a href="javascript:void(0);"><dt class="listPic"><div><img src="'.$val['pic'].'"></div></dt><dd class="listInfo"><h2 class="listTitle">'.$val['title'].'</h2><p class="listTxt">'.$val['info'].'</p></dd></a></dl>';
			}
		}
		if($string){
			$data['code'] = 200;
			$data['tips'] = $string;
		}
		echo json_encode($data);
	}
	/**
	 * 显示更多（11模板）
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-2-1
	 */
	public function ajaxMoreList11(){
		$data['code'] = 300;
		$where['companyid'] = $this->companyid;
		$where['listid'] = $this->_post('listid');
		$limit = 10;
		$startNumber = $this->_post('startNumber'); // 本次查询的开始条数
		$startNumber = $startNumber ? $startNumber : 0;
		$linkList = M('home_list_link')->where($where)->field('id,title,info,url,pic')->order('sort,updatetime DESC')->limit($startNumber,$limit)->select();
		$string = '';
		if($linkList){
			foreach($linkList as $key=>$val){
				$string .= '<li><a href="'.$val['url'].'"><h2 class="title">'.$val['title'].'</h2><p class="onlyheight"><img src="'.$val['pic'].'">'.$val['info'].'</p></a></li>';
			}
		}
		if($string){
			$data['code'] = 200;
			$data['tips'] = $string;
		}
		echo json_encode($data);
	}
	/**
	 * 显示更多（192模板）
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-2-2
	 */
	public function ajaxMoreList192(){
		$data['code'] = 300;
		$where['companyid'] = $this->companyid;
		$where['listid'] = $this->_post('listid');
		$limit = 10;
		$startNumber = $this->_post('startNumber'); // 本次查询的开始条数
		$startNumber = $startNumber ? $startNumber : 0;
		$linkList = M('home_list_link')->where($where)->field('id,title,info,url,pic')->order('sort,updatetime DESC')->limit($startNumber,$limit)->select();
		$string = '';
		if($linkList){
			foreach($linkList as $key=>$val){
				$string .= '<div class="qingrenjie_dz_box"><a href="'.$val['url'].'"><img src="'.$val['pic'].'"><div class="qingrenjie_dz"><div class="qingrenjie_con_box"><p class="qingrenjie_con2">'.$val['info'].'</p></div></div></a></div>';
			}
		}
		if($string){
			$data['code'] = 200;
			$data['tips'] = $string;
		}
		echo json_encode($data);
	}
	/**
	 * 显示更多（193模板）
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-5-9
	 */
	public function ajaxMoreList193(){
	    $data['code'] = 300;
	    $where['companyid'] = $this->companyid;
	    $where['listid'] = $this->_post('listid');
	    $limit = 10;
	    $startNumber = $this->_post('startNumber'); // 本次查询的开始条数
	    $startNumber = $startNumber ? $startNumber : 0;
	    $linkList = M('home_list_link')->where($where)->field('id,title,info,url,pic')->order('sort,updatetime DESC')->limit($startNumber,$limit)->select();
	    $string = '';
	    if($linkList){
	        foreach($linkList as $key=>$val){
	            $string .= '<li><div class="mingh_img_div"><a href="'.$val['url'].'"><img class="mingh_bjt" src="'.$val['pic'].'" /></a></div></li>';
	        }
	    }
	    if($string){
	        $data['code'] = 200;
	        $data['tips'] = $string;
	    }
	    echo json_encode($data);
	}
	/**
	 * 显示更多（16模板）
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-6-22
	 */
	public function ajaxMoreList16(){
		$data['code'] = 300;
		$where['companyid'] = $this->companyid;
		$where['listid'] = $this->_post('listid');
		$limit = 10;
		$startNumber = $this->_post('startNumber'); // 本次查询的开始条数
		$startNumber = $startNumber ? $startNumber : 0;
		$linkList = M('home_list_link')->where($where)->field('id,title,url,pic,info')->order('sort,updatetime DESC')->limit($startNumber,$limit)->select();
		$string = '';
		if($linkList){
			foreach($linkList as $key=>$val){
				$string .= '<li class="listInfo"><a href="';
				if($val['url']){
					$string .= ''.$val['url'].'';
				}else{
					$string .= 'javascript:void(0);';
				}
				$string .= '"><div class="listPic"><img src="'.$val['pic'].'"></div><h2 class="listTitle"><p>'.$val['title'].'</p></h2></a></li>';
			}
		}
		if($string){
			$data['code'] = 200;
			$data['tips'] = $string;
		}
		echo json_encode($data);
	}
	/**
	 * 点赞
	 */
	public function addPraise(){
		$where['companyid']=$this->companyid;
		$where['id']=$this->_post('id');
		$data['code'] = 300;
		M()->startTrans();
		M('home_info')->where($where)->setInc('praise');
		M('history_page_praise')->add(array('companyid'=>$this->companyid,'pid'=>$where['id'],'module'=>'home_info','createtime'=>time()));
		$info=D('Home_info')->getWhereHomeInfo($where);
		if($info){
			$data['code'] = 200;
			$data['num'] = $info['praise'];
			M()->commit();
		}else{
			M()->rollback();
		}
		echo json_encode($data);
	}
	/**
	 * 分享
	 */
	public function addShare(){
		$where['companyid']=$this->companyid;
		$where['id']=$this->_post('id');
		$data['code'] = 300;
		M()->startTrans();
		M('home_info')->where($where)->setInc('sharenum');
		M('history_page_share')->add(array('companyid'=>$this->companyid,'pid'=>$where['id'],'module'=>'home_info','createtime'=>time()));
		$info=D('Home_info')->getWhereHomeInfo($where);
		if($info){
			$data['code'] = 200;
			$data['num'] = $info['sharenum'];
			M()->commit();
		}else{
			M()->rollback();
		}
		echo json_encode($data);
	}
	/********************************************************移动官网********************************************************/
	//官网首页
	public function wapIndex(){
		$this->setPageTitle(array('title'=>'人来风首页'));
		//O2O最新推荐
		$articleList = M()->table('tp_home_list_link as link')->join(array('LEFT JOIN tp_home_list as list on list.id=link.listid'))->where(array('link.companyid'=>'1','link.listid'=>array('in','223,232')))->field('list.title,link.listid,link.pic,link.url')->limit(0,4)->select();
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
		$this->assign('articleList',$articleList);
		//最近官方客服
		$listLink = M('home_list_link')->where(array('companyid'=>'1','listid'=>56))->limit(0,3)->field('url')->select();
		if($listLink){
			$info = '';
			foreach($listLink as $llKey=>$llVal){
				$txt= $llVal['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$infoId=$matches[1][0];
				}
				$info = M('home_info')->where(array('companyid'=>'1','id'=>$infoId))->field('id,title,createtime')->find();
				$link[$llKey]['id'] = $info['id'];
				$link[$llKey]['title'] = $info['title'];
				$link[$llKey]['createtime'] = $info['createtime'];
			}
		}
		$this->assign('link',$link);
		//微信分享
		$info['shareimg'] = '';
		$info['sharefriendstitle'] = '人来风首页';
		$info['sharedes'] = '详情请访问www.mobiwind.cn';
		$this->assign('info',$info);
		$this->display();
	}
//免费开通账号
	public function wapRegister(){
		$data['truename'] = $this->_post('truename');
		if($data['truename']){
			$mobileCount = M('users')->where(array('isboss'=>1,'applymobile'=>$this->_post('phone')))->find();
			if($mobileCount){
				$ajaxReturn['code'] = '300';
				$ajaxReturn['tips'] = '该手机号已被注册';
			}else{
				$companyGroupInfo = M('company_group')->where(array('id'=>1))->field('id,permissions,maximgspace,maxrequestsnum')->find();
				if (empty($companyGroupInfo)){
					$this->redirect(U('Index/wapRegister'));
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
				$companyInfoInsterReturn = M('company')->add($companyInfoData);
					
				$data['companyid'] = $companyInfoInsterReturn;
				$data['applyname'] = $this->_post('truename');
				//$data['password'] = get_md5_password($this->_post('password'));
				//$data['truePassword'] = $this->_post('password','trim');
				//$data['applyemail'] = $this->_post('email');
				$data['applymobile'] = $this->_post('phone');
				$data['updatetime'] = $data['createtime'] = time();
				$data['createip'] = get_client_ip(0);
				$data['companyName'] = $this->_post('companyName');
				$data['companyBusiness'] = $this->_post('companyBusiness');
				$data['companyRemark'] = $this->_post('companyRemark');
				$data['isboss'] = 1;
				$usersInsterReturn = M('users')->add($data);
					
				if($companyInfoInsterReturn && $usersInsterReturn){
					check_dir('./Uploads/'.$companyInfoInsterReturn.'/image');//创建文件夹
					//$this->redirect(U('Index/registerOk'));//提示 审核
					$ajaxReturn['code'] = '200';
					$ajaxReturn['tips'] = '注册成功';
				}else{
					$ajaxReturn['code'] = '300';
					$ajaxReturn['tips'] = '注册失败';
					//$this->error(L('ServerBusyPrompt'),U('Index/register'));
				}
			}
			echo json_encode($ajaxReturn);
		}else{
			$this->setPageTitle(array('title'=>'立即开通-人来风SCRM5'));
			$info['shareimg'] = C('site_url').'/Tpl/Wap/default/common/img/waprigister.jpg';
			$info['sharefriendstitle'] = '立即开通-人来风SCRM5';
			$info['sharedes'] = '社群生意，无限价值！';
			$this->assign('info',$info);
			$this->display();
		}
	}
	//功能模块
	public function wapFunction(){
		$this->display();
	}
	//免费开通账号  新
	public function wapRegister_new(){
		$count = M()->query("SELECT COUNT(*) AS tp_count FROM `tp_users` WHERE (binary `username` = '".$this->_post('loginname')."') LIMIT 1 ");
		if($count[0]['tp_count']){
			$ajaxReturn['code'] = '100';
			$ajaxReturn['tips'] = '名字重复';
		}else{
			/* $count2 = M()->query("SELECT COUNT(*) AS tp_count FROM `tp_agent` WHERE (binary `loginname` = '".$this->_post('loginname')."') LIMIT 1 ");
			if($count2[0]['tp_count']){
				$ajaxReturn['code'] = '400';
				$ajaxReturn['tips'] = '名字重复';
			}else{ */
				$data['id'] = guidNow();
				$data['phone'] = $this->_post('phone');
				$data['name'] = $this->_post('name');
				$data['companyname'] = $this->_post('companyname');
				$data['industry'] = $this->_post('industry');
				$data['loginname'] = $this->_post('loginname');
				$data['password'] = $this->_post('password');
				if($this->_post('invitecode') != ''){
					$data['invitecode'] = $this->_post('invitecode');
				}
				$data['status'] = 1;
				$data['agenttype'] = '1';
				$data['updatetime'] = $data['createtime'] = time();
				$agent = M('agent')->add($data);
				if($agent){
					$ajaxReturn['code'] = '200';
					$ajaxReturn['id'] = $data['id'];
					$ajaxReturn['tips'] = '提交成功';
				}else{
					$ajaxReturn['code'] = '300';
					$ajaxReturn['tips'] = '提交失败';
				}
			//}
		}
		echo json_encode($ajaxReturn);
	}

	/**
	 * 手机端的免费开通SCRM5
	 * @author Asa<asa@renlaifeng.cn>
	 * @since  2016-8-25
	 */
	public function wapRegister_scrm5(){
		$count = M("users")->where(array("username"=>$this->_post('loginname')))->count();
		if($this->_post('invitecode') != ''){
			$where['invitecode'] = $this->_post('invitecode');
			$invitecode = M("agent_invitation")->where($where)->find();
			if($invitecode){
				$a = 2;
			}else{
				$a = 1;
			}
		}else{
			$a = 2;
		}
		if($a == 1){
			$ajaxReturn['code'] = '600';
			$ajaxReturn['tips'] = '请填写正确的邀请码';
		}else if($count){
			$ajaxReturn['code'] = '100';
			$ajaxReturn['tips'] = '登录用户名重复';
		}else{
			$data['brandname']=$_POST['companyname'];
			$data['companyname']=$_POST['companyname'];
			$data['updatetime'] = $data['createtime'] = time();
			$res1 = M("check_customer_info")->add($data);
			
			$viptime = time()+604800;//注册一周试用期
			$data2['viptime'] = $viptime;
			$data2['companyid'] = $res1;
			$data2['name']=$_POST['companyname'];
			$data2['tel']=$_POST['phone'];
			$data2['status']=4;
			$data2['gid']=8;
			$data2['updatetime'] = $data2['createtime'] = time();
			$res2 = M("company")->add($data2);
			
			//风助手储值设置
			$helper['id'] = guidNow();
			$helper['companyid'] = $res2;
			$helper['updatetime'] = $helper['createtime'] = time();
			M('storedvalue_helper_set')->add($helper);
			
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
			$data5['companyid'] = $res1;
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
				$sendFailNum = 0;
				if($data5['phone']){ //FG,cry,ryann,lan,stella,Yep
					$sendReturn = $this->sendSms('13918086001,18571729950,18616250318,13564012907,13818652568,15026482623', '新线索:'.$data5['name'].','.$data5['companyname'].','.$data5['phone'].','.$data5['loginname'].','.$data5['invitecode'],'1186','【人来风】','','181818');
					if($sendReturn['code'] =='200'){
						$agentSave = M('agent')->where(array('id'=>$data5['id']))->save(array('isnoticed'=>'2','noticedtime'=>time()));
						if(!$agentSave){
							//$log .= '任务失败  SQL：'.M()->getLastSql()."\n";
						}
					}else{
						$sendLog =json_encode($sendReturn);
						$sendFailNum++;
					}
					if($sendFailNum>0){
						$this->sendSms('13564012907', '共有'.$sendFailNum.'条新线索通知没能成功发送,请尽快核查'.$sendLog,'1186','【人来风】','','181818');
					}
				}
				
				//通论坛的地方
				require './LightpenCms/Lib/ORG/UcApi.Class.php';
				$_POST['email'] = strtolower(guidNow()).'@mail.net';// 这里的邮箱信息是必填项Ucenter无法设置非必填，目前规则是通过username来设置邮箱信息保证唯一性
				$reg = UcApi::reg($_POST['loginname'], $_POST['password'], $_POST['email']);
				if ($reg <= 0) {
					$data6['id'] = guidNow();
					$data6['username'] = $this->_post("loginname");
					$data6['type'] = 4;
					$data6['data'] = "子账号添加通论坛失败,返回值".$reg.'账号：'.$_POST['loginname'].'邮箱：'.$_POST['email'];
					$data6['time'] = date("Y-m-d H:i:s",time());
					$data6['createtime'] = time();
					$res = M("users_log")->add($data6);
				}
				
			}else{
				$ajaxReturn['code'] = '300';
				$ajaxReturn['tips'] = '提交失败';
			}
			//}
		}
		echo json_encode($ajaxReturn);

	}
	//功能模块
	public function wapRegistersuccess(){
		$this->setPageTitle(array('title'=>'欢迎您选择人来风SCRM5'));
		$info['shareimg'] = C('site_url').'./Tpl/Wap/default/common/img/waprigister.jpg';
		$info['sharefriendstitle'] = '立即开通人来风SCRM5';
		$info['sharedes'] = '社群生意，无限价值！';
		$info['shareurl'] = C('site_url').'/index.php?g=Wap&m=Index&a=wapRegister&companyid=1';
		$info['loginname'] = M('agent')->where(array('id'=>$this->_get('id')))->getField('loginname');
		$this->assign('info',$info);
		$this->display();
	}
	//O2O风人院
	public function wapFengrenyuan(){
		$id = $this->_get('id');
		$count = M('home_list_link')->where(array('listid'=>$id,'companyid'=>'1'))->count();
		$pages = ceil($count/6);
		$listLink = M('home_list')->where(array('companyid'=>'1','id'=>$id))->field('id,title')->find();
		$listLink['listLink'] = M('home_list_link')->where(array('companyid'=>'1','listid'=>$id))->limit(0,6)->order('sort ASC,updatetime DESC')->field('url')->select();
		if($listLink['listLink']){
			foreach($listLink['listLink'] as $Key=>$Val){
				$txt=$Val['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$infoId=$matches[1][0];
				}
				$info = M('home_info')->where(array('companyid'=>'1','id'=>$infoId))->field('id,title,author,desc,praise,sharenum,updatetime,readcount')->find();
				$listLink['listLink'][$Key]['id'] = $info['id'];
				$listLink['listLink'][$Key]['title'] = $info['title'];
				$listLink['listLink'][$Key]['author'] = $info['author'];
				$listLink['listLink'][$Key]['desc'] = $info['desc'];
				$listLink['listLink'][$Key]['click'] = $info['readcount'];  //访问量
				//$listLink['listLink'][$Key]['click'] = M('history_page_browsing')->where(array('companyid'=>1,'pagelink'=>array('like',C('site_url').U('Wap/Index/info',array('id'=>$info['id'],'companyid'=>1)).'%')))->count();
				$listLink['listLink'][$Key]['praise'] = $info['praise'];
				$listLink['listLink'][$Key]['sharenum'] = $info['sharenum'];
				$listLink['listLink'][$Key]['updatetime'] = $info['updatetime'];
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
		$this->assign('pages',$pages);
		$info['shareimg'] = '';
		if($id == 232){
			$this->setPageTitle(array('title'=>'行业O2O观察-O2O风人院'));
			$info['sharefriendstitle'] = '行业O2O观察-人来风';
		}elseif($id == 223){
			$this->setPageTitle(array('title'=>'探索移动营销-O2O风人院'));
			$info['sharefriendstitle'] = '探索移动营销-人来风';
		}
		$info['sharedes'] = '详情请访问www.mobiwind.cn';
		$this->assign('info',$info);
		$this->display();
	}
	//更多（O2O风人院）
	public function moreFengrenyuanList(){
		$id = $this->_post('id','intval');
		$page = $this->_post('page','intval')+1;
		$beginNum = ($page-1)*6;
		$endNum = $page*6;
		$pages = $this->_get('pages','intval'); //总页数
		$ajaxReturn['code'] = 300;
		$ajaxReturn['html'] = '';
		$ajaxReturn['page'] = '1';
		$listLink = M('home_list')->where(array('companyid'=>'1','id'=>$id))->field('id,title')->find();
		$listLink['listLink'] = M('home_list_link')->where(array('companyid'=>'1','listid'=>$id))->limit($beginNum,$endNum)->order('sort ASC,updatetime DESC')->field('url')->select();
		if($listLink['listLink']){
			foreach($listLink['listLink'] as $Key=>$Val){
				$txt=$Val['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$infoId=$matches[1][0];
				}
				$info = M('home_info')->where(array('companyid'=>'1','id'=>$infoId))->field('id,title,author,desc,praise,sharenum,updatetime')->find();
				$info['click'] = M('history_page_browsing')->where(array('companyid'=>1,'pagelink'=>array('like',C('site_url').U('Wap/Index/info',array('id'=>$info['id'],'companyid'=>1)).'%')))->count();
				$html .= '<div class="item-post-list"><a href="'.U('Index/info',array('id'=>$info['id'],'companyid'=>1)).'"><div class="title">'.$info['title'].'</div></a><div class="meta-item"><span class="meta-title industry">'.$listLink['title'].'</span><span class="meta-title icon-calendar">'.format_time($info['updatetime'],'ymd').'</span><span class="meta-title icon-eye-open">'.$info['click'].'</span><span class="meta-title icon-thumbs-up">'.$info['praise'].'</span><span class="meta-title icon-share">'.$info['sharenum'].'</span></div><p class="intro">'.$info['desc'].'</p></div>';
			}
		}
		/* if($listLink['listLink']){
			foreach($listLink['listLink'] as $Key=>$Val){
				if($beginNum<=$Key && $Key<$endNum){
					$html .= '<div class="item-post-list">
					<a href="'.U('Index/info',array('id'=>$Val['id'],'companyid'=>1)).'"><div class="title">'.$Val['title'].'</div></a>
					<div class="meta-item">
					<span class="meta-title industry">'.$listLink['title'].'</span>
					<span class="meta-title icon-calendar">'.format_time($Val['updatetime'],'ymd').'</span>
					<span class="meta-title icon-eye-open">'.$Val['click'].'</span>
					<span class="meta-title icon-thumbs-up">'.$Val['praise'].'</span>
					<span class="meta-title icon-share">'.$Val['sharenum'].'</span>
					</div>
					<p class="intro">'.$Val['desc'].'</p>
					</div>';
				}
			}
		} */
		$ajaxReturn['code'] = 200;
		$ajaxReturn['html'] = $html;
		$ajaxReturn['page'] = $page;
		if($page == $pages){
			$ajaxReturn['pages'] = 'hide';
		}
		echo json_encode($ajaxReturn);
	}
	//案例库
	public function wapCase(){
		$id = $this->_get('id');
		$listLink = M('home_list')->where(array('companyid'=>'1','id'=>$id))->field('id,title')->find();
		$count = M('home_list_link')->where(array('listid'=>$id,'companyid'=>'1'))->count();
		$pages = ceil($count/5);
		$listLink['link'] = M('home_list_link')->where(array('companyid'=>'1','listid'=>$id))->field('title,info,pic,url')->order('sort,id DESC')->limit('5')->select();
		if($listLink['link']){
			foreach($listLink['link'] as $Key=>$Val){
				$txt=$Val['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$listLink['link'][$Key]['id']=$matches[1][0];
				}
			}
		}
		$this->assign('list',$listLink);
		$this->assign('pages',$pages);
		$info['shareimg'] = '';
		if($id == 57){
			$this->setPageTitle(array('title'=>'生活服务业案例-案例库'));
			$info['sharefriendstitle'] = '生活服务业案例-案例库';
		}elseif($id == 59){
			$this->setPageTitle(array('title'=>'移动电商案例-案例库'));
			$info['sharefriendstitle'] = '移动电商案例-案例库';
		}elseif($id == 58){
			$this->setPageTitle(array('title'=>'营销活动案例-案例库'));
			$info['sharefriendstitle'] = '营销活动案例-案例库';
		}
		$info['sharedes'] = '详情请访问www.mobiwind.cn';
		$this->assign('info',$info);
		$this->display();
	}
	//更多（案例库）
	public function moreCase(){
		$id = $this->_post('id','intval');
		$page = $this->_post('page','intval')+1;
		$pages = $this->_get('pages','intval'); //总页数
		$ajaxReturn['code'] = 300;
		$ajaxReturn['html'] = '';
		$ajaxReturn['page'] = '1';
		$listLink = M('home_list')->where(array('companyid'=>'1','id'=>$id))->field('id,title')->find();
		$listLink['link'] = M('home_list_link')->where(array('companyid'=>'1','listid'=>$id))->field('title,info,pic,url')->order('sort,id DESC')->page($page)->limit('5')->select();
		if($listLink['link']){
			$html = '';
			foreach($listLink['link'] as $Key=>$Val){
				$txt=$Val['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$listLink['link'][$Key]['id']=$matches[1][0];
				}
				$html .= '<div class="item-post-list"><a href="'.U('Index/info',array('id'=>$listLink['link'][$Key]['id'],'companyid'=>1)).'"><img class="" src="'.$Val['pic'].'"><div class="title">'.$Val['title'].'</div><p class="intro">'.$Val['info'].'</p></a></div>';
			}
		}
		$ajaxReturn['code'] = 200;
		$ajaxReturn['html'] = $html;
		$ajaxReturn['page'] = $page;
		if($page == $pages){
			$ajaxReturn['pages'] = 'hide';
		}
		echo json_encode($ajaxReturn);
	}
	//客服中心
	public function wapCustomService(){
		$id = $this->_get('id');
		$count = M('home_list_link')->where(array('listid'=>$id,'companyid'=>'1'))->count();
		$pages = ceil($count/6);
		$listLink = M('home_list')->where(array('companyid'=>'1','id'=>$id))->field('id,title')->find();
		$listLink['listLink'] = M('home_list_link')->where(array('companyid'=>'1','listid'=>$id))->limit(0,6)->order('sort ASC,updatetime DESC')->field('url')->select();
		if($listLink['listLink']){
			foreach($listLink['listLink'] as $llKey=>$llVal){
				$txt= $llVal['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$infoId=$matches[1][0];
				}
				$info = M('home_info')->where(array('companyid'=>'1','id'=>$infoId))->field('id,title,author,updatetime')->find();
				$listLink['listLink'][$llKey]['id'] = $info['id'];
				$listLink['listLink'][$llKey]['title'] = $info['title'];
				$listLink['listLink'][$llKey]['author'] = $info['author'];
				$listLink['listLink'][$llKey]['updatetime'] = $info['updatetime'];
			}
		}
		/* $k = 0;
		foreach($listLink['listLink'] as $key => $val){
			$listLink['listLink'][$k]['id'] = $val['info']['id'];
			$listLink['listLink'][$k]['title'] = $val['info']['title'];
			$listLink['listLink'][$k]['author'] = $val['info']['author'];
			$listLink['listLink'][$k]['updatetime'] = $val['info']['updatetime'];
			$k++;
		}
		if($listLink['listLink']){
			$listLink['listLink'] = arraySort($listLink['listLink'],'updatetime');
		} */
		$this->assign('list',$listLink);
		$this->assign('pages',$pages);
		$info['shareimg'] = '';
		if($id == 56){
			$this->setPageTitle(array('title'=>'官方通告-客服中心'));
			$info['sharefriendstitle'] = '官方通过-客服中心';
		}elseif($id == 142){
			$this->setPageTitle(array('title'=>'新手入门-客服中心'));
			$info['sharefriendstitle'] = '新手入门-客服中心';
		}
		$info['sharedes'] = '详情请访问www.mobiwind.cn';
		$this->assign('info',$info);
		$this->display();
	}
	//更多(客服中心)
	public function moreCustomService(){
		$id = $this->_post('id','intval');
		$page = $this->_post('page','intval')+1;
		$beginNum = ($page-1)*6;
		$endNum = $page*6;
		$pages = $this->_get('pages','intval'); //总页数
		$ajaxReturn['code'] = 300;
		$ajaxReturn['html'] = '';
		$ajaxReturn['page'] = '1';
		$listLink = M('home_list')->where(array('companyid'=>'1','id'=>$id))->field('id,title')->find();
		$listLink['listLink'] = M('home_list_link')->where(array('companyid'=>'1','listid'=>$id))->limit($beginNum,$endNum)->order('sort ASC,updatetime DESC')->field('url')->select();
		if($listLink['listLink']){
			foreach($listLink['listLink'] as $llKey=>$llVal){
				$txt= $llVal['url'];
				$re1='.*?';
				$re2='(\\d+)';
				if(preg_match_all ("/".$re1.$re2."/is", $txt, $matches)){
					$infoId=$matches[1][0];
				}
				$info = M('home_info')->where(array('companyid'=>'1','id'=>$infoId))->field('id,title,author,updatetime')->find();
				$html .= '<div class="item-post-list"><a href="'.U('Index/info',array('id'=>$info['id'],'companyid'=>1)).'"><div class="title">'.$info['title'].'</div></a><div class="meta-item"><span class="meta-title">'.$info['author'].'</span><span class="meta-title gray">最后修改于'.format_time($info['updatetime'],'zhymdhi').'</span></div></div>';
			}
		}
		/* if($listLink['listLink']){
			foreach($listLink['listLink'] as $Key=>$Val){
				if($beginNum<=$Key && $Key<$endNum){
					$html .= '<div class="item-post-list">
					<a href="'.U('Index/info',array('id'=>$Val['id'],'companyid'=>1)).'"><div class="title">'.$Val['title'].'</div></a>
					<div class="meta-item">
					<span class="meta-title">'.$Val['author'].'</span>
					<span class="meta-title gray">最后修改于'.format_time($Val['updatetime'],'zhymdhi').'</span>
					</div>
					</div>';
				}
			}
		} */
		$ajaxReturn['code'] = 200;
		$ajaxReturn['html'] = $html;
		$ajaxReturn['page'] = $page;
		if($page == $pages){
			$ajaxReturn['pages'] = 'hide';
		}
		echo json_encode($ajaxReturn);
	}
	//渠道代理
	public function wapAgent(){
		$this->setPageTitle(array('title'=>'渠道代理-人来风'));
		$info['shareimg'] = '';
		$info['sharefriendstitle'] = '渠道代理-人来风';
		$info['sharedes'] = '详情请访问www.mobiwind.cn';
		$this->assign('info',$info);
		$this->display();
	}
	//渠道代理申请
	public function wapAgentRegister(){
		$data['companyname'] = $this->_post('companyname');
		if($data['companyname']){
			$data['name'] = $this->_post('name');
			$data['companyPost'] = $this->_post('companyPost');
			$data['email'] = $this->_post('email');
			$data['phone'] = $this->_post('phone');
			$data['http'] = $this->_post('http');
			$data['scope'] = $this->_post('scope');
			$data['province'] = $this->_post('province');
			$data['city'] = $this->_post('city');
			$data['qq'] = $this->_post('qq');
			$data['createtime'] = $data['updatetime'] = time();
			$agentlist = M('agent')->add($data);
			if($agentlist){
				$message['code'] = '200';
				$message['tips'] = '申请成功';
			}else{
				$message['code'] = '300';
				$message['tips'] = '申请失败';
			}
			echo json_encode($message);
		}else{
			$this->setPageTitle(array('title'=>'代理申请-人来风'));
			$areaAll = M('area')->where(array('parentid'=>'1017'))->field('id,name')->select();
			$this->assign('list',$areaAll);
			$info['shareimg'] = '';
			$info['sharefriendstitle'] = '代理申请-人来风';
			$info['sharedes'] = '详情请访问www.mobiwind.cn';
			$this->assign('info',$info);
			$this->display();
		}
	}
	//推广合作
	public function wapPromotion(){
		$this->setPageTitle(array('title'=>'推广合作-人来风'));
		$info['shareimg'] = '';
		$info['sharefriendstitle'] = '推广合作-人来风';
		$info['sharedes'] = '详情请访问www.mobiwind.cn';
		$this->assign('info',$info);
		$this->display();
	}
	//关于人来风
	public function wapAboutUs(){
		$this->setPageTitle(array('title'=>'关于人来风'));
		$info['shareimg'] = '';
		$info['sharefriendstitle'] = '关于人来风';
		$info['sharedes'] = '详情请访问www.mobiwind.cn';
		$this->assign('info',$info);
		$this->display();
	}
	//联系我们
	public function wapContactUs(){
		$this->setPageTitle(array('title'=>'联系我们-人来风'));
		$info['shareimg'] = '';
		$info['sharefriendstitle'] = '联系我们-人来风';
		$info['sharedes'] = '详情请访问www.mobiwind.cn';
		$this->assign('info',$info);
		$this->display();
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
	/**
	 * 外滩十八号定制列表页
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-2-3
	 */
	public function eighteenList(){
		$this->setPageTitle(array('title'=>'外滩十八号Bund18'));
		$data = array(
				'1'=>array('id'=>1,'pic'=>'./Tpl/Wap/default/common/chaoren/lat.jpg','title'=>'./Tpl/Wap/default/common/chaoren/lat-logo.png'),
				'2'=>array('id'=>2,'pic'=>'./Tpl/Wap/default/common/chaoren/sal.jpg','title'=>'./Tpl/Wap/default/common/chaoren/sal-logo.png'),
				'3'=>array('id'=>3,'pic'=>'./Tpl/Wap/default/common/chaoren/ono.jpg','title'=>'./Tpl/Wap/default/common/chaoren/ono-logo.png'),
				'4'=>array('id'=>4,'pic'=>'./Tpl/Wap/default/common/chaoren/bound-01.jpg','title'=>'./Tpl/Wap/default/common/chaoren/hakkasan-logo.png'),
				'5'=>array('id'=>5,'pic'=>'./Tpl/Wap/default/common/chaoren/bound-02.jpg','title'=>'./Tpl/Wap/default/common/chaoren/bound-logo2.png'),
				'6'=>array('id'=>6,'pic'=>'./Tpl/Wap/default/common/chaoren/bound-03.jpg','title'=>'./Tpl/Wap/default/common/chaoren/bound-logo3.png'),
				'7'=>array('id'=>7,'pic'=>'./Tpl/Wap/default/common/chaoren/attos-01.jpg','title'=>'./Tpl/Wap/default/common/chaoren/attos-logo.png'),
				'8'=>array('id'=>8,'pic'=>'./Tpl/Wap/default/common/chaoren/hudie.jpg','title'=>'./Tpl/Wap/default/common/chaoren/hudie-logo.png'),
				//'9'=>array('id'=>9,'pic'=>'./Tpl/Wap/default/common/chaoren/swe.jpg','title'=>'./Tpl/Wap/default/common/chaoren/swe-logo.png')
		);
		$this->assign('data',$data);
		$this->display();
	}
	/**
	 * 外滩十八号定制餐厅主页
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-2-3
	 */
	public function eighteenIndex(){
		$this->setPageTitle(array('title'=>'外滩十八号Bund18'));
		$id = $this->_get('id');
		$data = array(
				'1'=>array('id'=>1,'url'=>'http://www.mobiwind.cn/index.php?g=Wap&m=MemberPhoto&a=photoList&companyid=1211&id=422','name'=>'外滩十八号 上海市中山东一路18号三楼','x'=>'121.489790','y'=>'31.238440','pic'=>array('1'=>'./Tpl/Wap/default/common/chaoren/lat-01.jpg','2'=>'./Tpl/Wap/default/common/chaoren/lat-02.jpg','3'=>'./Tpl/Wap/default/common/chaoren/lat-03.jpg','4'=>'./Tpl/Wap/default/common/chaoren/lat-04.jpg'),'title'=>'./Tpl/Wap/default/common/chaoren/lat-logo.png','phone'=>'(8621)60718888'),
				'2'=>array('id'=>2,'url'=>'http://www.mobiwind.cn/index.php?g=Wap&m=MemberPhoto&a=photoList&companyid=1211&id=412','name'=>'外滩十八号 上海市中山东一路18号一楼','x'=>'121.489790','y'=>'31.238440','pic'=>array('1'=>'./Tpl/Wap/default/common/chaoren/sal-01.jpg','2'=>'./Tpl/Wap/default/common/chaoren/sal-02.jpg','3'=>'./Tpl/Wap/default/common/chaoren/sal-03.jpg','4'=>'./Tpl/Wap/default/common/chaoren/sal-04.jpg'),'title'=>'./Tpl/Wap/default/common/chaoren/sal-logo.png','phone'=>'(8621)60708888'),
				'3'=>array('id'=>3,'url'=>'http://www.mobiwind.cn/index.php?g=Wap&m=MemberPhoto&a=photoList&companyid=1211&id=410','name'=>'外滩十八号 上海市中山东一路18号三楼','x'=>'121.489790','y'=>'31.238440','pic'=>array('1'=>'./Tpl/Wap/default/common/chaoren/ono-01.jpg','2'=>'./Tpl/Wap/default/common/chaoren/ono-02.jpg'),'title'=>'./Tpl/Wap/default/common/chaoren/ono-logo.png','phone'=>'(8621)63339818'),
				'4'=>array('id'=>4,'url'=>'http://www.mobiwind.cn/index.php?g=Wap&m=Index&a=lists&listid=1025&companyid=1211','name'=>'外滩十八号 上海市中山东一路18号五楼','x'=>'121.489790','y'=>'31.238440','pic'=>array('1'=>'./Tpl/Wap/default/common/chaoren/hkk-1.jpg','2'=>'./Tpl/Wap/default/common/chaoren/hkk-2.jpg','3'=>'./Tpl/Wap/default/common/chaoren/hkk-3.jpg'),'title'=>'./Tpl/Wap/default/common/chaoren/hakkasan-logo.png','phone'=>'(8621)63215888'),
				'5'=>array('id'=>5,'url'=>'http://www.mobiwind.cn/index.php?g=Wap&m=Wei&a=index&id=174PN8MGEGO5G&companyid=1211','name'=>'外滩十八号上海市中山东一路18号六楼','x'=>'121.489790','y'=>'31.238440','pic'=>array('1'=>'./Tpl/Wap/default/common/chaoren/mmb-1.jpg','2'=>'./Tpl/Wap/default/common/chaoren/mmb-2.jpg','3'=>'./Tpl/Wap/default/common/chaoren/mmb-3.jpg'),'title'=>'./Tpl/Wap/default/common/chaoren/bound-logo2.png','phone'=>'(8621)63239898'),
				'6'=>array('id'=>6,'url'=>'http://www.mobiwind.cn/index.php?g=Wap&m=Index&a=lists&listid=1024&companyid=1211','name'=>'外滩十八号上海市中山东一路18号六楼','x'=>'121.489790','y'=>'31.238440','pic'=>array('1'=>'./Tpl/Wap/default/common/chaoren/barrouge-1.jpg','2'=>'./Tpl/Wap/default/common/chaoren/barrouge-2.jpg','3'=>'./Tpl/Wap/default/common/chaoren/barrouge-3.jpg'),'title'=>'./Tpl/Wap/default/common/chaoren/bound-logo3.png','phone'=>'(8621)63391199'),
				'7'=>array('id'=>7,'url'=>'http://www.mobiwind.cn/index.php?g=Wap&m=MemberPhoto&a=photoList&companyid=1211&id=417','name'=>'外滩十八号 上海市中山东一路18号一楼','x'=>'121.489790','y'=>'31.238440','pic'=>array('1'=>'./Tpl/Wap/default/common/chaoren/attos-bj1.jpg','2'=>'./Tpl/Wap/default/common/chaoren/attos-bj2.jpg','3'=>'./Tpl/Wap/default/common/chaoren/attos-bj3.jpg'),'title'=>'./Tpl/Wap/default/common/chaoren/attos-logo.png','phone'=>'(8621)4000663770'),
				'8'=>array('id'=>8,'url'=>'','name'=>'外滩十八号 上海市中山东一路18号一楼','x'=>'121.489790','y'=>'31.238440','pic'=>array('1'=>'./Tpl/Wap/default/common/chaoren/hudie-01.jpg')),
				//'9'=>array('id'=>9,'url'=>'','name'=>'外滩十八号 上海市中山东一路18号一楼','x'=>'121.489790','y'=>'31.238440','pic'=>array('1'=>'./Tpl/Wap/default/common/chaoren/swe-01.jpg'))
		);
		$this->assign('id',$id);
		$this->assign('data',$data[$id]);
		$this->display();
	}
	/**
	 * 外滩十八号定制餐厅详情页
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-2-3
	 */
	public function eighteenDetail(){
		$this->setPageTitle(array('title'=>'外滩十八号Bund18'));
		$id = $this->_get('id');
		$data = array(
				'1'=>array('id'=>1,'introduce'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;上海乔尔 • 卢布松美食坊拥有亚洲最大的开放式厨房，共有32个紧邻开放式厨房的席位，以及16个围绕在周围的餐桌席位。食客们可以清楚观赏到一道道乔尔 • 卢布松经典美食在主厨Francky Semblat和他的团队手中诞生。主厨Francky Sembla跟随卢布松先生工作已有19年之久，深谙他烹饪之精妙。在上海的乔尔 • 卢布松美食坊用餐，透过互动的氛围，食客总会不自觉地沉浸于纯正美食之中，享受非一般的欢愉用餐体验。此外，餐厅还设有3个可容纳8位客人的包间，满足食客对私密空间的需要。<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;L’Atelier de Joël Robuchon in Shanghai has the largest open kitchen in Asia surrounded by 32 seats in the signature open kitchen, as well as 16 seats at the tables around, where customers can observe the preparation of Joël Robuchon’s signature dishes by Executive Chef Francky Semblat who has been working alongside Joël Robuchon for 19 years and his team. The interactive atmosphere immerses guests in the unusual dining experience where gastronomy rhymes with conviviality. Also, this set up is completed by 3 private rooms, each of them accommodating up to 8 guests.<br/><br/><br/>','phone'=>'(8621)63215888<br/><a href="http://www.joelrobuchon-china.com">www.joelrobuchon-china.com<a/>','businessTime'=>'<br/>午餐 Lunch：11:30–14:00 (周六 Saturday–周日 Sunday)<br/>晚餐 Dinner：17:30–22:30 (周日 Wednesday–周三 Sunday) <br>17:30–23:00 (周四 Thursday–周六 Saturday) <br/>','address'=>'<br/>外滩十八号 上海市中山东一路18号三楼<br/>3F Bund18, Zhongshan East Road (E1)','pic'=>array('1'=>'./Tpl/Wap/default/common/chaoren/lat-list01.jpg','2'=>'./Tpl/Wap/default/common/chaoren/lat-list02.jpg','3'=>'./Tpl/Wap/default/common/chaoren/lat-list03.jpg','4'=>'./Tpl/Wap/default/common/chaoren/lat-list04.jpg','5'=>'./Tpl/Wap/default/common/chaoren/lat-list05.jpg'),'title'=>'./Tpl/Wap/default/common/chaoren/lat-logo.png'),
				'2'=>array('id'=>2,'introduce'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;位于外滩十八号中庭，Salon de Thé（法语意为下午茶）呈献充满想象的各种蛋糕、点心、面包、下午茶与精致小食。本着Robuchon先生对美食尽善尽美的追求，每一款美食皆出自手工。同时，系列鲜榨果汁、多种好茶、精选咖啡也一样值得饕客们期待。现代精致的经典法式面包坊La Boutique，提供丰富多样的Robuchon先生特色蛋糕、点心及面包，满足行色匆匆的食客的外带需要。<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The Salon de Thé (Tea shop in French) is offering a wide selection of cakes, pastries, breads, afternoon teas as well as light savory dishes in the luxurious surroundings of the Atrium of Bund18. All items are daily homemade in the spirit of Joël Robuchon’s quality and perfectionism companied with a selection of freshly pressed and artisanal fruit juice as well as choices of fine teas and special blend coffees.Jointed with Salon de Thé, La Boutique presents a contemporary and chic French boulangerie (bakery shop) offering the full menu of Mr. Robuchon’s signature cakes, pastries and breads for guests to take away.<br/><br/><br/>','phone'=>'(8621)60708888<br/><a href="http://www.joelrobuchon-china.com">www.joelrobuchon-china.com<a/>','businessTime'=>'<br/>Salon de Thé：10:00–19:00<br/>La Boutique：10:00–23:00 ( Thursday 周四–Saturday 周六 )<br/>10:00 - 21:00 ( Sunday 周日–Wednesday 周三 )<br/>','address'=>'<br/>外滩十八号 上海市中山东一路18号一楼<br/>1F Bund18 18 Zhongshan East Road(E1)','pic'=>array('1'=>'./Tpl/Wap/default/common/chaoren/sal-list01.jpg','2'=>'./Tpl/Wap/default/common/chaoren/sal-list02.jpg','3'=>'./Tpl/Wap/default/common/chaoren/sal-list03.jpg','4'=>'./Tpl/Wap/default/common/chaoren/sal-list04.jpg'),'title'=>'./Tpl/Wap/default/common/chaoren/sal-logo.png'),
				'3'=>array('id'=>3,'introduce'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GINZA ONODERA是LEOC集团旗下的品牌。LEOC集团于1983年由小野寺裕司先生（Onodera Hiroshi）的父亲，小野寺真悟在日本北海道创立，以经营医院、养老院、公司团体餐饮服务为主并成为日本国内团餐领军品牌。2001年由小野寺裕司先生接手之后，十四年间LEOC公司集团加速壮大。2013年在银座以小野寺裕司先生名字开设了第一家GINZA ONODERA，以最能代表日本料理的寿司、铁板烧和天妇罗作为特色，将“从银座走向世界”作为目标，先后成立夏威夷、香港、巴黎等分店，把日本顶级的料理推荐给全球食客贵宾们，并受到广为追捧和褒奖。<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ginza Onodera belongs to the LEOC group’s. It was built in Hokkaido,  Japan by Mr. Onodera Hiroshi’s father in 1983. LEOC group mainly operates hospitals, rest home,  company group hospitality and become the leader brand of Japan domestic hospitality group.LEOC group accelerated growth in fourteen years after Mr. Onodera Hiroshi took over it in 2001.The first Ginza Onodera was opened in Ginza with the name of Onodera. It characters in the most representative of the Japanese food, which is sushi, Teppanyaki, tempura. And take “from Ginza to the world” as the target. Ginza Onodera has branches in Hawaii, Hong Kong, Paris ,etc. and brought top Japanese cuisine to the guests from all around world. They also widely receive pursue and admire.<br/><br/><br/>','phone'=>'(8621)63339818<br/><a href="https://onodera-group.com/cn/">https://onodera-group.com/cn/<a/>','businessTime'=>'<br/>午餐 Lunch：11:30–14:00 (周五 Friday–周日 Sunday)<br/>晚餐 Dinner：17:30–21:30 (周二 Tuesday–周日 Sunday) <br/>','address'=>'<br/>外滩十八号 上海市中山东一路18号三楼<br/>3F Bund18 18 Zhongshan East Road(E1)','pic'=>array('1'=>'./Tpl/Wap/default/common/chaoren/ono-list1.png','2'=>'./Tpl/Wap/default/common/chaoren/ono-list2.png','3'=>'./Tpl/Wap/default/common/chaoren/ono-list3.png'),'title'=>'./Tpl/Wap/default/common/chaoren/ono-logo.png'),
				'4'=>array('id'=>4,'introduce'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;知名伦敦餐饮与服务巨擘Hakkasan秉承伦敦当代烹饪艺术精髓，结合传统粤菜予以全新演绎。Hakkasan不仅是至臻美食的殿堂，更为食客带来丰富的多重感官体验：在精心营造的兼具高端中式特色和现代美感的环境中，品味世界级饕餮美馔，尊享卓越贴心的服务。Hakkasan注重细节的打造，从渲染气氛的光影投射、精心挑选的背景音乐、到标志性的茉莉花香氛，每一处都是感官的奢华礼遇，令人不禁沉醉于这轻松曼妙的感官飨宴之中，悦享独一无二的就餐体验。<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hakkasan, the renowned London-based hospitality organization, captures the spirit of London contemporary epicurean dining, and introduces a modern approach to Cantonese cuisine defined by the combination of world-class cuisine and outstanding, yet unobtrusive service, experienced in a modern ‘Chinoise Chic’ environment. The unique dining experience is known for its relaxed ambience and a sensual vibe, generated by the atmospheric lighting, eclectic sounds and a signature jasmine incense aroma. It is not just another fine dining restaurant – Hakkasan takes its guests through a multi-sensorial experience.<br/><br/><br/>','phone'=>'(8621)63215888<br/><a href="http://www.hakkasan.com">www.hakkasan.com<a/>','businessTime'=>'<br/>午餐 Lunch：11:00–15:00 (周五 Friday–周日 Sunday)<br/>下午茶 Afternoon Tea：14:30–17:00 (周六 Saturday–周日 Sunday )<br/>晚餐 Dinner：17:30–00:30 (周一 Monday–周四 Thursday) <br>17:30–02:00 (周五 Friday–周六 Saturday) <br/>17:30–23:30 (周日 Sunday)<br/>','address'=>'<br/>外滩十八号 上海市中山东一路18号五楼<br/>5F Bund18 18 Zhongshan East Road(E1)','pic'=>array('1'=>'./Tpl/Wap/default/common/chaoren/hkk-list1.jpg','2'=>'./Tpl/Wap/default/common/chaoren/hkk-list2.jpg','3'=>'./Tpl/Wap/default/common/chaoren/hkk-list3.jpg','4'=>'./Tpl/Wap/default/common/chaoren/hkk-list4.jpg','5'=>'./Tpl/Wap/default/common/chaoren/hkk-list5.jpg'),'title'=>'./Tpl/Wap/default/common/chaoren/hakkasan-logo.png'),
				'5'=>array('id'=>5,'introduce'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mr & Mrs Bund 是一家法式餐厅，风格如同名厨Paul Pairet 一样：生在法国，游历天下，身上带着国际化印记。当代气息浓厚，但绝不沉闷。轻松，但丝毫不失优雅。开设于2009年4月，这里提供大众流行的法国招牌菜式，但经由 Pairet 重新演绎。家庭式的服务风格与摩登的用餐环境相得益彰，同时提供32种可按不同分量单杯点用的葡萄酒。擅长经典料理，提供温馨菜式，Mr & Mrs Bund 上菜方式和您周日晚上的家庭聚餐如出一辙的惬意分享。这里跳脱传统的不只是美食和酒饮－它让高端餐饮成了欢乐享宴。国际获奖：2014年度亚洲最佳50餐厅排名第11；2013年度全球最佳50餐厅排名第43。<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mr & Mrs Bund is French, in the manner of Chef Paul Pairet: born, traveled, and globally stamped. Contemporary, but not stuffy. Relaxed, without sacrificing an ounce of chic.Opened in April 2009, this eatery serves populist French favorites, through Pairet’s looking glass. Service is family-style, recast for a modern table, and the wine list includes 32 wines by the glass.Riffing on classics, dishing up comfort cuisine, Mr & Mrs Bund serves food just like you would on Sunday night in sharing style. Its break with tradition extends beyond just food and drinks – It turns fine dining into fun dining.International accolades: No. 11 in Asia’s 50 Best Restaurants 2014; No. 43 in The World’s 50 Best Restaurants 2013.<br/><br/><br/>','phone'=>'(8621)63239898<br/><a href="http://www.mmbund.com">www.mmbund.com</a>','businessTime'=>'<br/>早午餐 Brunch：周六 Saturday–周日 Sunday 11:30–14:30（最后点单 last order）<br/>晚餐 Dinner：周一 Monday–周日 Sunday 17:30–22:30（最后点单 last order）<br/>夜宵 Late Night：周四 Thursday–周六 Saturday 23:00–2:00（最后点单 last order）','address'=>'<br/>外滩十八号 上海市中山东一路18号六楼<br/>6F Bund18 18 Zhongshan East Road(E1)','pic'=>array('1'=>'./Tpl/Wap/default/common/chaoren/mmb-list1.jpg','2'=>'./Tpl/Wap/default/common/chaoren/mmb-list2.jpg','3'=>'./Tpl/Wap/default/common/chaoren/mmb-list3.jpg','4'=>'./Tpl/Wap/default/common/chaoren/mmb-list4.jpg','5'=>'./Tpl/Wap/default/common/chaoren/mmb-list5.jpg'),'title'=>'./Tpl/Wap/default/common/chaoren/bound-logo2.png'),
				'6'=>array('id'=>6,'introduce'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;位于外滩十八号顶层的Bar Rouge 于2004年11月开业。Bar Rouge的开业，宣告了上海夜生活的一个新时代的到来。作为上海的顶尖时尚娱乐场所之一，它超越了人们的预期，迅速成为上海最热门的酒吧之一。来自法国的顶级调酒专家、引人注目的室内设计和上海最热门的DJ的加盟，使Bar Rouge成为在上海享受夜生活的必到之处。<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The opening of Bar Rouge in November 2004 a top Bund18 announced a new era in Shanghai nightlife. Conceived as a top-notch fashionable entertainment venue in Shanghai, it exceeded all expectations by rapidly becoming the undisputedly hottest nightspot in the city. With expert top bartenders brought in from France, striking interiors and the hottest DJs in town, Bar Rouge is the place for a night out in Shanghai.<br/><br/><br/>','phone'=>'(8621)63391199<br/><a href="http://www.bar-rouge-shanghai.com">www.bar-rouge-shanghai.com</a>','businessTime'=>'<br/>每周日 Sunday–周三 Wednesday  18:00–2:00<br/>每周四 Thursday–周六 Saturday 18:00 –深夜 till late','address'=>'<br/>外滩十八号 上海市中山东一路18号七楼<br/>7F Bund18 18 Zhongshan East Road(E1)','pic'=>array('1'=>'./Tpl/Wap/default/common/chaoren/barrouge_list1.jpg','2'=>'./Tpl/Wap/default/common/chaoren/barrouge_list2.jpg','3'=>'./Tpl/Wap/default/common/chaoren/barrouge_list3.jpg','4'=>'./Tpl/Wap/default/common/chaoren/barrouge_list4.jpg','5'=>'./Tpl/Wap/default/common/chaoren/barrouge_list5.jpg'),'title'=>'./Tpl/Wap/default/common/chaoren/bound-logo3.png'),
				'7'=>array('id'=>7,'introduce'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;作为全国规模最大的奢侈品官方授权免税概念店，ATTOS的产品线包括鞋、包、服饰、眼镜、手表、配饰等；旗下拥有Chloé、 Gucci、 Saint Laurent、 Bottega Veneta、 Balenciaga、 Versace、 Alexander McQueen、Alexander Wang、Sergio Rossi、Stella McCartney、ATTOS Milano等超过50个国际一线品牌及设计师品牌。<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;结合全国30多家线下体验店和线上会员平台, ATTOS致力于打造七感A2G营销 ,传递”信感 永恒”的品牌理念 ;为更多中高端消费者带来全球同步价格、同步发布的高品位，高质量的奢侈品体验。<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ATTOS Group is an authorized retailer for high end luxury fashion and designers brands, including women and men’s ready to wear, accessories, shoes, jewelry, fragrances and home gift ideas. Our knowledge of the local consumer markets has enabled us to become the top retail channel for over 40 brands with a total of 200,000 square feet retail space spanning across 30 boutiques worldwide over two years.ATTOS A2G Business Model is an integration of Fashion Luxury O2O Model, Network Marketing, Social Media, Traditional Media, Finance, IT, DT, Entertainment and Charity to share “Passion is Fashion! ” concept.<br/><br/><br/>','phone'=>'(8621)4000663770<br/><a href="http://www.attos.com">www.attos.com</a>','businessTime'=>'<br/>10:00–23:00 (周四 Thursday–周六 Saturday) <br/>10:00–22:00 (周日 Sunday–周三 Wednesday)','address'=>'<br/>外滩十八号 上海市中山东一路18号一楼<br/>1F Bund18 18 Zhongshan East Road(E1)','pic'=>array('1'=>'./Tpl/Wap/default/common/chaoren/attos-list1.jpg','2'=>'./Tpl/Wap/default/common/chaoren/attos-list2.jpg','3'=>'./Tpl/Wap/default/common/chaoren/attos-list3.jpg','4'=>'./Tpl/Wap/default/common/chaoren/attos-list4.jpg','5'=>'./Tpl/Wap/default/common/chaoren/attos-list5.jpg'),'title'=>'./Tpl/Wap/default/common/chaoren/attos-logo2.png')
				
		);
		$this->assign('data',$data[$id]);
		$this->display();
	}
	/**
	 * 统计外滩十八号历史浏览的Pv值
	 * @author   Tomas<416369046@qq.com>
	 * @since  2016-3-24
	 */
	public function eighteenCountPv(){
		$companyid = $this->_get('companyid');
		$id = array(
				'1'=>array('id'=>1,'name'=>'L’Atelier de Joël Robuchon','pv'=>'844'),
				'2'=>array('id'=>2,'name'=>'Salon De The','pv'=>'502'),
				'3'=>array('id'=>3,'name'=>'Onodera','pv'=>'441'),
				'4'=>array('id'=>4,'name'=>'Hakkasan','pv'=>'422'),
				'5'=>array('id'=>5,'name'=>'Mr&Mrs Bund','pv'=>'145'),
				'6'=>array('id'=>6,'name'=>'Bar Rouge','pv'=>'99'),
				'7'=>array('id'=>7,'name'=>'Attos','pv'=>'81'),
				'8'=>array('id'=>8,'name'=>'Hudie','pv'=>'29'),
				//'9'=>array('id'=>9,'name'=>'Sweet Princess','pv'=>'27')
		);
		$where1['companyid'] = $companyid;
		$where1['pagelink'] = C('site_url').U('Wap/Index/eighteenList',array('companyid'=>$companyid,'from'=>'singlemessage','isappinstalled'=>0));//&from=singlemessage&isappinstalled=0
		$totalCount = M('history_page_browsing')->where($where1)->count();
		echo '<pre />';print_r('“外滩十八号Bund18 ” 浏览总量值是： '.($totalCount+273));
		foreach($id as $key =>$val){
			$where['companyid'] = $companyid;
			$where['pagelink'] = C('site_url').U('Wap/Index/eighteenIndex',array('companyid'=>$companyid,'id'=>$val['id']));
			$count = M('history_page_browsing')->where($where)->count();
			echo '<pre />';print_r('“' .$val['name'].'”  店铺的浏览总量值是： '.($count+$val['pv']));
		}
	}
}
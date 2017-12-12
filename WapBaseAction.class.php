<?php
/**
 * Wap  基本操作
 * Enter description here ...
 * @author yongfei.zhao
 *
 */
class WapBaseAction extends BaseAction{
	
	public function __construct(){
		parent::_initialize();
        $companyid = 1;
		$company = array("id"=>1,
            'name'=>'新微风'
        );
//		dump($_SESSION);
//        session("mid".$companyid,'5963');
//        session("openid".$companyid,'o3e4C09hp1UE_ceBdf4pyRDWezGM');
//        session("wname".$companyid,'Asa-王恩宝');
//        session("whead".$companyid,'http://wx.qlogo.cn/mmopen/XJ7tyfiabicnzYZgkr2bI2JAENITIa6VE9VM1KrBR0xXBqib3KzeyriaCxtCdCmpiaZhCCIfotcSzFTLHibSqJe3CLZzUG6f6lTQUW/0');
        $wechatsInfo = D('Wechats')->getCompanyWechatsInfo(array('companyid'=>$companyid));
        if($wechatsInfo){
            $wechatOptions = array('token'=>$wechatsInfo['token'],'appid'=>$wechatsInfo['appid'],'appsecret'=>$wechatsInfo['appsecret']);
            $wechat  = new Wechat($wechatOptions);
            $signPackage = $wechat->getJsSign($wechatsInfo['appid']);
            $this->assign('signPackage',$signPackage);
        }
        session('wapcid',$company['id']);
        session('cname',$company['name']);
        $this->assign('companyid',$company['id']);
	}
	/**
	 * 
	 * 底部菜单
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-12-10
	 */
	public function bottomMenu($companyid){
		$bottomMenuInfo = M('wap_bottom_menu')->where(array('companyid'=>$companyid))->find();
		$time = time();
		if(!$bottomMenuInfo){
			$id = M("wei_list")->where(array('companyid'=>$companyid,'ishomepage'=>'2','type'=>'1'))->getField('id');
			if($id){
				$indexUrl = C('site_url').'/index.php?g=Wap&m=Wei&a=index&companyid='.$companyid.'&id='.$id;
			}else{
				$homeIndex = M("home")->where(array("companyid"=>$companyid))->find();
				if($homeIndex){
					$indexUrl = C('site_url').'/index.php?g=Wap&m=Index&a=index&companyid='.$companyid;
				}else{
					$indexUrl = '';
				}
			}
			$bottomMenuInfo = array('id'=>guidNow(),'companyid'=>$companyid,'isopen'=>'1',
					'name1'=>'首页','relevancetype1'=>'1','relevanceurl1'=>$indexUrl,'isopen1'=>'1',
					'name2'=>'关注我们','relevancetype2'=>'2','relevanceurl2'=>'','isopen2'=>'1',
					'name3'=>'全部门店','relevancetype3'=>'1','relevanceurl3'=>C('site_url').'/index.php?g=Wap&m=MemberDining&a=shops&companyid='.$companyid,'isopen3'=>'1',
					'name4'=>'会员中心','relevancetype4'=>'1','relevanceurl4'=>C('site_url').'/index.php?&g=Wap&m=Member&a=center&companyid='.$companyid,'isopen4'=>'1',
					'updatetime'=>$time,'createtime'=>$time
			);
			M('wap_bottom_menu')->add($bottomMenuInfo);
		}
		$this->assign('bottomMenuInfo',$bottomMenuInfo);
	}
	/**
	 * 映射 页面seo
	 * @param unknown $pageSeo
	 */
	public function setPageSeo($pageSeo){
		if($pageSeo['title']){
			$this->assign('title',$pageSeo['title']);
		}
		if($pageSeo['keywords']){
			$this->assign('keywords',$pageSeo['keywords']);
		}
		if($pageSeo['description']){
			$this->assign('description',$pageSeo['description']);
		}
	}

    public function checkMemberLoginBox(){
	    $this->checkMemberAutoLogin();
    }
	/**
	 * 
	 * 会员微信环境中自动登陆
	 * 
	 * @return boolean
	 * @author Lando<806728685@qq.com>
	 * @since  2017-3-13
	 */
	public function checkMemberAutoLogin(){
	    $companyid = 1;
        if(session('openid'.$companyid) && session('mid'.$companyid) ){
            // 如果已经生产Mid，直接退出
            return false;
        }
	    $agent = $_SERVER['HTTP_USER_AGENT'];
	    if(!strpos($agent,"MicroMessenger")) {
            $this->redirect(U('Login/login',array('companyid'=>$companyid)));
	        return false;
	    }
	    $wechats['appid'] = 'wx643dfa13dbd9e2ad';
	    $wechats['appsecret'] = '46a78dd6acf1e222123e3a06d386fc48';
	    $wechatOptions = array('appid'=>$wechats['appid'],'appsecret'=>$wechats['appsecret']);
	    $wechat  = new Wechat($wechatOptions);
	    if($_GET['code']){
	        $wechatInfo = $wechat->getOauthAccessToken();
	        if($wechatInfo['openid']){
	            if(session('mid'.$companyid)){
	                $where['wechat.mid'] = session('mid'.$companyid);
                }elseif(session('openid'.$companyid)){
                    $where['wechat.openid'] = session('openid'.$companyid);
                }else{
					$where['wechat.openid'] = $wechatInfo['openid'];
				}
                $info = M()->table('tp_member_wechat_info AS wechat')
                    ->join(array('LEFT JOIN tp_member_register_info AS register ON wechat.mid=register.id'))
                    ->where($where)
                    ->field('wechat.mid,wechat.nickname,wechat.language,wechat.headimgurl,register.isregister,register.name')->find();
	            if($info){
                    session('openid'.$companyid,$wechatInfo['openid']);
                    session('mid'.$companyid,$info['mid']);
                    session('mname'.$companyid,$info['name']);
                    session('wname'.$companyid,$info['nickname']);
                    session('whead'.$companyid,$info['headimgurl']);
	                if(!$info['nickname']){
                        $wechat2 =  $wechat->getOauthUserinfo($wechatInfo['access_token'],$wechatInfo['openid']);
                        if($wechat2){
                            session('wname'.$companyid,$wechat2['nickname']);
                            session('whead'.$companyid,$wechat2['headimgurl']);
                            $data = $wechat2;
                            $data['updatetime'] = time();
                            M("member_wechat_info")->where(array("mid"=>$info['mid']))->save($data);
                        }
                    }
                }else{
                    redirect(U('Login/login'));
	                $wechat2 =  $wechat->getOauthUserinfo($wechatInfo['access_token'],$wechatInfo['openid']);
	                if(!$wechat2) $wechat2['openid'] = $wechatInfo['openid'];
	                $data = $wechat2;
	                $data['companyid'] = 1;
	                $data['mid'] = M("member_register_info")->add(array('createtime'=>time()));
	                $data['createtime'] = time();
                    M("member_wechat_info")->add($data);
                    session('mid'.$companyid,$info['mid']);
                    session('mname'.$companyid,'');
                    session('wname'.$companyid,$wechat2['nickname']);
                    session('whead'.$companyid,$wechat2['headimgurl']);
                }
				$this->redirect(session('historyUrl'));
	        }else{
	            $this->redirect(U('System/notOauth',array('companyid'=>$companyid)));
	        }
	    }else{
			session('historyUrl','http://' . $_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]);
	        $info = $wechat->getOauthRedirect(session('historyUrl'),'renlaifeng','snsapi_userinfo');
	        redirect($info);
	    }
	}
	
	/**
	* 设置 页面title
	*/
	public function setPageTitle($data =array('title'=>'会员中心')){
		$this->assign('pageTitle',$data['title']);
	}
	/**
	 * 左上角菜单栏
	 */
	public function menuBar(){
		$memberWechatInfo = M('member_wechat_info')->table('tp_member_wechat_info AS wechat')->join('tp_member_register_info AS register ON wechat.mid=register.id')->field('wechat.nickname,wechat.headimgurl,register.name')->where(array('wechat.companyid'=>session('wapcid'),'wechat.mid'=>session('mid'.session('wapcid'))))->find();
		$this->assign('WechatInfo',$memberWechatInfo);
	}
	/**
	 * 系统通知
	 */
	public function noticeCount(){
		$noticeCount = M('member_notices')->where(array('companyid'=>session('wapcid'),'mid'=>session('mid'.session('wapcid')),'msgtype'=>1,'isread'=>2))->count();
		$this->assign('noticeCount',$noticeCount);
	}
	/**
	 * Eshop客服
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-11-11
	 */
	public function eshopServiceCount(){
		$eshopServiceCount = M('mall_notices')->where(array('companyid'=>session('wapcid'),'mid'=>session('mid'.session('wapcid')),'msgtype'=>1,'isread'=>2))->count();
		$this->assign('eshopServiceCount',$eshopServiceCount);
	}
	/**
	 * 记录pv
	 */
	public function historyPage(){
		$data['companyid'] = session('wapcid') ? session('wapcid') : 0 ;
		$data['mid'] = session('mid'.session('wapcid')) ? session('mid'.session('wapcid')) : 0 ;
		$data['pagelink'] = 'http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
		$data['createtime'] = time();
		M('history_page_browsing')->add($data);
	}
	/**
	 * 系统自定义菜单
	 */
	public function systemDiymenu($companyid){
		$list['parent'] = M('system_diymen')->where(array('companyid'=>$companyid,'pid'=>'0','is_show'=>1))->field('id,title,url')->order('sort ASC')->limit('0,3')->select();
		if($list){
			foreach($list['parent'] as $Key=>$Val){
				$list['parent'][$Key]['child'] = M('system_diymen')->where(array('companyid'=>$companyid,'pid'=>$Val['id'],'is_show'=>1))->field('id,title,url')->order('sort ASC')->select();
			}
		}
		$this->assign('systemDiymenu',$list);
	}
	/**
	 * Eshop通头
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-7-19
	 */
	public function eshophead($companyid){
		$eshopinfo = M('eshop')->where(array('companyid'=>$companyid))->field('id,title,logo')->find();
		$this->assign('eshopinfo',$eshopinfo);
	}
	/**
	 * Eshop商城菜单栏
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-7-18
	 */
	public function eshopMenuBar($companyid){
		$list = M('eshop_class')->where(array('companyid'=>$companyid,'ptid'=>''))->field('id,name,tags')->order('sort,updatetime desc')->select();
		if($list){
			foreach($list as $key=>$val){
				$list[$key]['child'] = M('eshop_class')->where(array('companyid'=>$companyid,'ptid'=>$val['id']))->field('id,name,tags')->order('sort,updatetime desc')->select();
			}
		}
		$this->assign('menuBar',$list);
	}
	/**
	 * 调用微信logo、微信公众号名称以及二维码
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-9
	 */
	public function wechatInfo($companyid){
		$wechatInfo = M('wechats')->where(array('companyid'=>$companyid))->field('id,weixin,wxname,headerpic,qrcodeurl')->find();
		$this->assign('wechatInfo',$wechatInfo);
	}
	/**
	 * 购物车商品数量
	 */
	public function shoppingcartgoodsnum(){
		$goodsnum = M()->table('tp_mall_shopping_cart as cart')->join(array('LEFT JOIN tp_mall_goods as goods ON goods.id = cart.goodid'))->where(array('cart.companyid'=>session('wapcid'),'cart.mid'=>session('mid'.session('wapcid'))))->sum('cart.goodnum');
		$this->assign('shoppingcartgoodsnum',$goodsnum);
	}
	/**
	 * 
	 * 代理信息的处理
	 * 
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-1-13
	 */
	public function editAgentData($peopleid){
	    if($peopleid){
	        $companyid = session('wapcid');
	        $mid = session('mid'.session('wapcid'));
    	    if ($companyid && $mid){
    			// 登录过
    			$time = time();
    			$info = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$mid))->field('id,peopleid,peopletime,shopptime')->find();
    			if($info){
    			    $disptime = $time-(5*24*60*60);
    			    if($info['peopleid']==$peopleid && $info['shopptime']>=$disptime){
    			        $data['shopptime'] = $time;
    			        M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$mid))->save($data);
    			    }
    			}
    		}
	    }    
	}
	/**
	 * 预定用无感知授权
	 * @author Mark<1311013341@qq.com>
	 * @since  2017-3-13
	 */
	public function MobileBookNoSenseAccredit(){
	    if(!session('openid'.session('wapcid'))){
    		$companyid = session('wapcid');
    		$wechats = M('wechats')->where(array('companyid'=>$companyid))->field('appid,appsecret')->find();
    		$wechatOptions = array('appid'=>$wechats['appid'],'appsecret'=>$wechats['appsecret']);
    		$wechat  = new Wechat($wechatOptions);
    		if($_GET['code']){
    			$wechatInfo = $wechat->getOauthAccessToken();
    			if($wechatInfo['openid']){
    				session('openid'.$companyid,$wechatInfo['openid']);
    			}else{
    				$this->redirect(U('System/notOauth',array('companyid'=>$companyid)));
    			}
    		}else{
    			$info = $wechat->getOauthRedirect('http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"],'renlaifeng','snsapi_base');
    			redirect($info);
    		}
	    }
	}
	/**
	 * 无感知授权
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-3-24
	 */
	public function NoSenseAccredit(){
		if(!session('openid'.session('wapcid'))){
			$companyid = session('wapcid');
			$wechats = M('wechats')->where(array('companyid'=>$companyid))->field('appid,appsecret')->find();
			$wechatOptions = array('appid'=>$wechats['appid'],'appsecret'=>$wechats['appsecret']);
			$wechat  = new Wechat($wechatOptions);
			if($_GET['code']){
				$wechatInfo = $wechat->getOauthAccessToken();
				if($wechatInfo['openid']){
					session('openid'.$companyid,$wechatInfo['openid']);
					/* $signPackage = $wechat->getJsSign($wechats['appid']);
					$this->assign('signPackage',$signPackage); */
				}else{
					$this->redirect(U('System/notOauth',array('companyid'=>$companyid)));
				}
			}else{
				$info = $wechat->getOauthRedirect('http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"],'renlaifeng','snsapi_base');
				redirect($info);
			}
		}
	}
	/**
	 * 全量授权
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-4-15
	 */
	public function SenseAccredit(){
			$companyid = session('wapcid');
			$wechats = M('wechats')->where(array('companyid'=>$companyid))->field('appid,appsecret')->find();
			$wechatOptions = array('appid'=>$wechats['appid'],'appsecret'=>$wechats['appsecret']);
			$wechat  = new Wechat($wechatOptions);
			if($_GET['code']){
				$wechatInfo = $wechat->getOauthAccessToken();
				if($wechatInfo['openid']){
					session('openid'.$companyid,$wechatInfo['openid']);
					$wInfo2 = $wechat->getOauthUserinfo($wechatInfo['access_token'],$wechatInfo['openid']);
					if($wInfo2){
						session('nickname',$wInfo2['nickname']);
						session('headimgurl',$wInfo2['headimgurl']);
					}else{
						$this->redirect(U('System/notOauth',array('companyid'=>$companyid)));
					}
					/* $signPackage = $wechat->getJsSign($wechats['appid']);
					$this->assign('signPackage',$signPackage); */
				}else{
					$this->redirect(U('System/notOauth',array('companyid'=>$companyid)));
				}
			}else{
				$info = $wechat->getOauthRedirect('http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"],'renlaifeng','snsapi_userinfo');
				redirect($info);
			}
	}
	/**
	 * 上传时，将图片进行压缩
	 * @param unknown $img
	 * @param unknown $save_path
	 * @param unknown $width
	 * @param unknown $height
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-4-19
	 */
	public function thumb_img($img,$save_path,$width,$height){
		require_cache('LightpenCms/_Core/Extend/Library/ORG/Util/Image/Driver/ImageGd.class.php');
		$image = new ImageGd();
		$imageImageSrc = $image->open($img);
		$image->thumb($width,$height,THINKIMAGE_THUMB_FILLED);
		$image->save($save_path);
	}
	/**
	 * 判断底部首页链接的
	 */
	public function homeIndexUrl(){
		if($this->companyid == 1){
			$homeIndex['url'] = U("Index/wapIndex",array("companyid"=>$this->companyid));
		}else{
			$homeIndex = M("wei_list")->where(array("companyid"=>$this->companyid,'ishomepage'=>'2'))->find();
			if($homeIndex){
				$homeIndex['url'] = U("Wei/index",array("companyid"=>$this->companyid,'id'=>$homeIndex['id']));
				$this->currentPage = 1; // 通底图标点亮
			}else{
				$homeIndex = M("home")->where(array("companyid"=>$this->companyid))->find();
				if($homeIndex){
					$homeIndex['url'] = U("Index/index",array("companyid"=>$this->companyid));
				}else{
					$homeIndex['url'] = 'javascript:void(0);';
				}
			}
		}
		$this->homeIndex = $homeIndex;
	}
	/**
	 * 
	 * 默认微信分享的方法
	 * 
	 * @param unknown $info   需要向页面映射的信息
	 * @param unknown $sharedes   分享描述
	 * @author Asa<asa@renlaifeng.cn>
	 * @since  2016-11-11
	 */
	public function defaultWechatShare($info,$sharedes){
		//这里写分享信息方法的
		//,shareimg,sharefriendstitle,sharedes,
		$comInfoA = M("company")->where(array('id'=>$this->companyid))->field("logourl,name")->find();
		$info['sharefriendstitle']?'':$info['sharefriendstitle']=$comInfoA['name'];
		$info['sharedes']?'':$info['sharedes']=$sharedes;
		$info['shareimg']?'':$info['shareimg']=$comInfoA['logourl'];
		$this->info = $info;
	}
}
?>
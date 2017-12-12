<?php
/**
 * 微页面
 * @author    asa<asa@renlaifeng.cn>
 * @since     2016-7-27
 * @version   1.0
 */
class WeiAction extends WapBaseAction{
	
	private $companyid;
	
	private $mid;
	
	public function __construct(){
		parent::__construct();
		$this->companyid = session('wapcid');
		$this->mid = session('mid'.session('wapcid'));
		$this->wlist = M('wei_list');
		$this->wass = M('wei_assembly');
	}
	/**
	 * 微页面
	 * @author asa<asa@renlaifeng.cn>
	 * @since  2016-7-27
	 */
	public function index(){
		if($this->_get('ishomepage')==2){
			$where3['companyid'] = $this->companyid;
			$where3['ishomepage'] = 2;
			$ishomepage = $this->wlist->where($where3)->find();
			if(!$ishomepage){
				$this->redirect(U('Index/index',array('companyid'=>$this->companyid)));
			}else{
				$where['id']=$ishomepage['id'];
				$where2['parentid']=$ishomepage['id'];
			}
		}else{
			$where['id']=$this->_request('id');
			$where2['parentid']=$this->_get('id');
		}
		$where['companyid'] = $this->companyid;
		$info = $this->wlist->where($where)->field('id,title,shareimg,sharefriendstitle,sharedes,iswechat,isencrypt,encryptinfo,bgcolor')->find();
		$this->wlist->where($where)->setInc('pv');
		$where2['companyid'] = $this->companyid;
		$info['assembly'] = $this->wass->where($where2)->order('sort asc,updatetime DESC')->select();
		if($info['assembly']){
			foreach($info['assembly'] as $key=>$val){
				if($val['type'] == 6 || $val['type'] == 14 ){
					$info['assembly'][$key]['goods'] = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>array('in',$val['goodid'])))->field('id,goodtype,title,stockamount,pricetype,saleprice,intprice,voucherimgurl,originalprice,grouponprice')->order('updatetime DESC')->select();
					if($info['assembly'][$key]['goods']){
						foreach($info['assembly'][$key]['goods'] as $gkey=>$gval){
							if($gval['goodtype'] == 1 || $gval['goodtype'] == 3 || $gval['goodtype'] == 4 || $gval['goodtype'] == 5 || $gval['goodtype'] == 6 || $gval['goodtype'] == 7){
								$info['assembly'][$key]['goods'][$gkey]['pic'] = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('sort')->getField('pic');
								if($gval['goodtype'] == 6 || $gval['goodtype'] == 7){
									$info['assembly'][$key]['goods'][$gkey]['grouponprice'] = $gval['grouponprice'];
									$info['assembly'][$key]['goods'][$gkey]['originalprice'] = $gval['originalprice'];
								}else{
									$info['assembly'][$key]['goods'][$gkey]['grouponprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('grouponprice')->getField('grouponprice');
									$info['assembly'][$key]['goods'][$gkey]['originalprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('originalprice DESC')->getField('originalprice');
								}
							}else{
								$info['assembly'][$key]['goods'][$gkey]['pic'] = $gval['voucherimgurl'];
								$info['assembly'][$key]['goods'][$gkey]['grouponprice'] = $gval['grouponprice'];
								$info['assembly'][$key]['goods'][$gkey]['originalprice'] = $gval['originalprice'];
							}
							$info['assembly'][$key]['goods'][$gkey]['groupnum'] = M('mall_groupon_activity')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->getField('groupnum');
						}
					}
				}
				if($val['type'] == -1){
					$whereass['parentid']=$val['assid'];
					$whereass['companyid'] = $this->companyid;
					$whereass['type'] = array("not in",'11,12');
					$info['assembly'][$key]['assinfo'] = M("wei_assembly")->where($whereass)->order("sort asc")->select();
					foreach($info['assembly'][$key]['assinfo'] as $key2=>$val2){
						$info['assembly'][$key]['assinfo'][$key2]['goods'] = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>array('in',$val2['goodid'])))->field('id,goodtype,title,stockamount,pricetype,saleprice,voucherimgurl,originalprice,grouponprice')->order('updatetime DESC')->select();
						if($info['assembly'][$key]['assinfo'][$key2]['goods']){
							foreach($info['assembly'][$key]['assinfo'][$key2]['goods'] as $gkey=>$gval){
								if($gval['goodtype'] == 1 || $gval['goodtype'] == 3 || $gval['goodtype'] == 4 || $gval['goodtype'] == 5 || $gval['goodtype'] == 6 || $gval['goodtype'] == 7){
									$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['pic'] = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('sort')->getField('pic');
									if($gval['goodtype'] == 6 || $gval['goodtype'] == 7){
										$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['grouponprice'] = $gval['grouponprice'];
										$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['originalprice'] = $gval['originalprice'];
									}else{
										$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['grouponprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('grouponprice')->getField('grouponprice');
										$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['originalprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('originalprice DESC')->getField('originalprice');
									}
								}else{
									$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['pic'] = $gval['voucherimgurl'];
									$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['grouponprice'] = $gval['grouponprice'];
									$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['originalprice'] = $gval['originalprice'];
								}
								$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['groupnum'] = M('mall_groupon_activity')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->getField('groupnum');
							}
						}
					}
				}
			}
		}
		//头和底
		$where3['parentid']=$this->_get('id');
		$where3['companyid'] = $this->companyid;
		$where3['type'] = 12;
		$footerinfo =  $this->wass->where($where3)->field('id,type,footisstatus,footclass')->find();
		if($footerinfo){
			$this->footerinfo=$footerinfo;
		}else{
			$wheref['type'] = -1;
			$wheref['parentid']=$this->_get('id');
			$info3 =  $this->wass->where($wheref)->field('id,assid,type,footisstatus,footclass')->select();
			foreach ($info3 as $val){
				$wheref1['parentid']=$val['assid'];
				$wheref1['companyid'] = $this->companyid;
				$wheref1['type'] = 12;
				$info4 = $this->wass->where($wheref1)->find();
				if($info4){
					$info4['id']=$val['id'];
					$this->footerinfo=$info4;
				}
			}
		}
		$where3['type'] = 11;
		$headerinfo =  $this->wass->where($where3)->find();
		if($headerinfo){
			$headerinfo2=$headerinfo;
		}else{
			$whereh['type'] = -1;
			$whereh['parentid']=$this->_get('id');
			$info4 =  $this->wass->where($whereh)->field('id,assid,type')->select();
			foreach ($info4 as $val){
				$whereh1['parentid']=$val['assid'];
				$whereh1['companyid'] = $this->companyid;
				$whereh1['type'] = 11;
				$info5 = $this->wass->where($whereh1)->find();
				if($info5){
					//$info5['id']=$val['id'];
					$headerinfo2=$info5;
				}
			}
		}
		if($headerinfo2){
			$where10['_string'] = "parentid='0' or parentid=''";
			$where10['assid'] = $headerinfo2['id'];
			$tags = M("wei_list_nav")->where($where10)->field('id,parentid,title,url,isstatus,sort')->order('sort asc')->select();
			if($tags){
				$headerinfo2['navinfo']=$tags;
				foreach($tags as $key=>$val){
					$tags2 = M("wei_list_nav")->where(array("companyid"=>$this->companyid,'parentid'=>$val['id']))->order('sort asc')->select();
					if($tags2){
						$headerinfo2['navinfo'][$key]['lists'] = $tags2;
					}
				}
			}else{
				$return['code'] = 400;
			}
		}
		$this->headerinfo = $headerinfo2;
		
		$this->assign('info',$info);
		$this->setPageTitle(array('title'=>$info['title']));
		if($_POST['title']){
			$this->searchtitle = $_POST['title'];
			$search['title'] = array('like','%'.$_POST['title'].'%');
			$search['companyid'] = $this->companyid;
			$search['type'] = 1;
			$limit = 15;
			$count = $this->wlist->where($search)->count();
			$maxpage = ceil($count/$limit);
			$lists = $this->wlist->where($search)->limit(0,$limit)->order('updatetime desc')->field('id,title,shareimg,sharedes')->select();
			if($lists){
				$this->assign('lists',$lists);
				$this->assign('count',$count);
				$this->assign('maxpage',$maxpage);
				$this->assign('limit',$limit);
			}
			$this->setPageTitle(array('title'=>$_POST['title']));
		}
		//判断底部导航是否需要显示
		$this -> checkIsShowFooter = 2;
		$wechatInfo = M('wechats')->where(array('companyid'=>$this->companyid))->field('id,weixin,wxname,headerpic,qrcodeurl')->find();
		$this->assign('wechatInfo',$wechatInfo);
		//这里写分享信息方法的
		$this->defaultWechatShare($info, $info['title']);
		$this->display();
	}
	/**
	 * 
	 * 加载更多
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-26
	 */
	public function ajaxMoreList(){
		$data['code'] = 300;
		$search['title'] = array('like','%'.$this->_get('title').'%');
		$search['companyid'] = $this->companyid;
		$search['type'] = 1;
		$maxpage = $this->_post('totalpage');
		$limit =  $this->_post('limit');
		$currentpage = $this->_post('currentpage','intval')+1; // 本次查询的开始条数\
		if($maxpage>=$currentpage){
			$linkList = $this->wlist->where($search)->limit($limit)->page($currentpage)->order('updatetime desc')->field('id,title,shareimg,sharedes')->select();
			$string = '';
			if($linkList){
				foreach($linkList as $key=>$Lval){
					$string .='<li><a href="'.C('site_url').'/index.php?g=Wap&m=Wei&a=index&id='.$Lval['id'].'&companyid='.$this->companyid.'"><div class="title_search_cont">';
					$string .='<div class="title_search_cleft"><img src="';
					if($Lval['shareimg']){
						$string .= $Lval['shareimg'];
					}else{
						$string .= './Tpl/User5/default/common/img/WeiPage-temp-img.png';
					}
					$string .='" /></div>';
					$string .='<div class="title_search_cright">';
					$string .='<h2>'.$Lval['title'].'</h2>';
					$string .='<p class="title_search_contp">'.$Lval['sharedes'].'</p>';
					$string .='</div></div></a></li>';
				}
			}
			if($string){
				$data['code'] = 200;
				$data['tips'] = $string;
				if($maxpage == $currentpage){
					$data['page'] = 100;
				}
			}
		}
		echo json_encode($data);
	}
	/**
	 * 这是写关注二维码的
	 */
	public function ajaxWechatCode(){
		$info = M("wechats")->where(array("companyid"=>$this->companyid))->field("qrcodeurl,wxname,weixin")->find();
		if($info){
			$info['code']==200;
		}else{
			$info['code']==300;
		}
		echo json_encode($info);
	}
}
?>
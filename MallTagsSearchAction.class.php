<?php
/**
 * 筛选
 * Enter description here ...
 * @author yaochengkai
 */
class MallTagsSearchAction extends WapBaseAction{
	
	private $companyid;
	
	private $mid;
	
	public function __construct(){
		parent::__construct();
		$this->companyid = session('wapcid');
		$this->mid = session('mid'.session('wapcid'));
		$this->limit = 8;
	}
	/**
	 * 筛选
	 */
	public function index(){
		$id=$this->_get('id');
		$tabName = $this->_get('tabName');
		if($tabName){
			$this->CountScannum($id,$tabName); //执行扫描次数+1
		}
		//每次进入这页面计算pv数；
		$group = GROUP_NAME;
		$method = MODULE_NAME;
		$action = ACTION_NAME;
		$this->countPv($group,$method,$action);
		$this->setPageTitle(array('title'=>'筛选'));
		$list = M('mall_tags')->where(array('companyid'=>$this->companyid))->field('id,name')->select();
		$this->assign('list',$list);
		$info['sharedes'] = $info['sharefriendstitle'] = '筛选';
		$info['shareimg'] = session('clogo');
		$this->assign('info',$info);
		$this->display();
	}
	/**
	 * 筛选列表页
	 */
	public function lists(){
		//where条件
		$where['companyid'] = $this->companyid;
		$where['isoffshelves'] = '2';
		$where['pricetype'] = '1';
		//商品名称
		$title = $this->_request('title');
		if($title){
			$where['title'] = array('like','%'.$title.'%');
			$this->assign('title',$title);
		}
		$id = $this->_request('id');
		$search = M('eshop_class')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('title,tags,ordertype,shareimg,sharefriendstitle,sharedes')->find();
		$tid = $search['tags'];
		//标签string
		if($tid){
			$tidSting = explode(',', $tid);
			foreach($tidSting as $key=>$val){
				if($val){
					$wheretags .=" (tags like '%,".$val.",%') AND";
				}
			}
			if($wheretags){
				$wheretags = substr($wheretags, 0,-3);
				$where['_string'] = $wheretags;
			}
		}
		//商品排序
		if($search['ordertype'] == 1){
			$order = 'updatetime DESC';
		}elseif($search['ordertype'] == 2){
			$order = 'viewnum DESC';
		}elseif($search['ordertype'] == 3){
			$order = 'salenum DESC';
		}else{
			$order = 'createtime DESC';
		}
		$this->assign('tags',$id);
		$this->count = M('mall_goods')->where($where)->count();
		$list = M('mall_goods')->where($where)->field('id,goodtype,title,pricetype,originalprice,saleprice,intprice,voucherimgurl')->order($order)->limit($this->limit)->select();
		if($list){                                   
			foreach($list as $key=>$val){
				$list[$key]['pic'] = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$val['id']))->order('sort')->getField('pic');
				if($val['goodtype'] == 1 || $val['goodtype'] == 3 || $val['goodtype'] == 4 || $val['goodtype'] == 5){
					$list[$key]['saleprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$val['id']))->min('saleprice');
					$list[$key]['intprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$val['id']))->min('intprice');
				}
			}
		}
		$search['tplid'] = $search['tplid'] ? $search['tplid'] : 1;
		$title = $search['title'] ? $search['title'] : '商品筛选列表'; 
		// 分享图片
		if($search['shareimg'] && strpos($search['shareimg'], C('site_url'))===false){
			$search['shareimg'] = C('site_url').$search['shareimg'];
		}
		
		$this->setPageTitle(array('title'=>$title));
		$this->assign('list',$list);
		$this->defaultWechatShare($search, $this->_request('title'));
		$this->display('1'.$search['tplid'].'_list');
	}
	/**
	 * 显示更多-->ajax
	 */
	public function getMoreList(){
		$ajaxReturn['code'] = 300;
		$ajaxReturn['tips'] = 'error:500';
		
		$ajaxReturn['html'] = '';
		$startNumber = $this->_post('startNumber'); // 本次查询的开始条数
		$startNumber = $startNumber ? $startNumber : 0;
		//where条件
		$where['companyid'] = $this->companyid;
		$where['isoffshelves'] = '2';
		$where['pricetype'] = '1';
		//商品名称
		$title = $this->_post('title');
		if($title){
			$where['title'] = array('like',$title.'%');
		}
		$id = $this->_post('id');
		$search = M('eshop_class')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('tags,ordertype')->find();
		//商品标签
		$tid = $search['tags'];
		if($tid){
			$tidSting = explode(',', $tid);
			foreach($tidSting as $key=>$val){
				if($val){
					$wheretags .=" (tags like '%,".$val.",%') AND";
				}
			}
			if($wheretags){
				$wheretags = substr($wheretags, 0,-3);
				$where['_string'] = $wheretags;
			}
		}
		//商品排序
		if($search['ordertype'] == 1){
			$order = 'updatetime DESC';
		}elseif($search['ordertype'] == 2){
			$order = 'viewnum DESC';
		}elseif($search['ordertype'] == 3){
			$order = 'salenum DESC';
		}else{
			$order = 'id DESC';
		}
		$list = M('mall_goods')->where($where)->field('id,goodtype,title,pricetype,originalprice,saleprice,intprice,voucherimgurl')->order($order)->limit($startNumber, $this->limit)->select();
		if($list){
			foreach($list as $key=>$val){
				$ajaxReturn['html'].='<li><a href="'.U('MallGoods/goodInfo',array('companyid'=>$this->companyid,'id'=>$val['id'])).'">';
      	  		if($val['goodtype'] == 1){
      	  			$val['saleprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$val['id']))->min('saleprice');
      	  			$val['intprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$val['id']))->min('intprice');
      	  			$pic = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$val['id']))->order('sort')->getField('pic');
      	  			$ajaxReturn['html'].='<img src="'.$pic.'" alt="" class="eshop_whtong"/>';
      	  		}else{
      	  			$pic = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$val['id']))->order('sort')->getField('pic');
      	  			//$pic = $val['voucherimgurl']?$val['voucherimgurl']:'./Tpl/User/default/common/images/xuni/moren.jpg';
      	  			$ajaxReturn['html'].='<img src="'.$pic.'" alt="" class="eshop_whtong" />';
      	  		}
                $ajaxReturn['html'].='<p class="eshopv_bigtwo_p1">'.$val['title'].'</p>';
                $ajaxReturn['html'].='<p class="eshopv_bigtwo_p2">';
		        if($val['pricetype'] == 1){
			    $ajaxReturn['html'].='￥<i>'.$val['saleprice'].'</i>';
			    }elseif($val['pricetype'] == 2){
			    $ajaxReturn['html'].='<i><span style="font-weight:bold;">积分:</span>'.$val['intprice'].'</i>';
			    }
			    if($val['goodtype'] == 1){
			     	$ajaxReturn['html'].='<i class="icon-buy-buy-buy"></i>';
			    }elseif($val['goodtype'] == 2){
			    	$ajaxReturn['html'].='<i class="icon-buy-buy-buy icon-quan"></i>';
			    }elseif($val['goodtype'] == 3){
			    	$ajaxReturn['html'].='<i class="icon-buy-buy-buy icon-jicika"></i>';
			    }elseif($val['goodtype'] == 4){
			    	$ajaxReturn['html'].='<i class="icon-buy-buy-buy icon-tuangouka"></i>';
			    }elseif($val['goodtype'] == 5){
			    	$ajaxReturn['html'].='<i class="icon-buy-buy-buy icon-menpiao"></i>';
			    }elseif($val['goodtype'] == 6){
			    	$ajaxReturn['html'].='<i class="icon-buy-buy-buy icon-power"></i>';
			    }elseif($val['goodtype'] == 7){
			    	$ajaxReturn['html'].='<i class="icon-buy-buy-buy icon-libao"></i>';
			  	}
		        $ajaxReturn['html'].='</p>';
          		$ajaxReturn['html'].='</a></li>';
			}
			$ajaxReturn['code'] = 200;
			$ajaxReturn['tips'] = 'success';
		}
		echo json_encode($ajaxReturn);
	}
}
?>
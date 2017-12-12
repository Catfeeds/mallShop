<?php
/**
 * eshop整单优惠活动
 * @author    姚成凯<kevin@renlaifeng.cn>
 * @since     2016-4-21
 * @version   1.0
 */
class EshopDiscountAction extends UserAction{
	
	private $companyid;
	
	private $shopsid;
	
	private $uid;
	
	public function __construct(){
		parent::__construct();
		//检查公司配置
		$this->checkCompanyScrm5Permissions(68,TRUE);
		$this->companyid = session('cid');
		$this->shopsid = session('shopsid');
		$this->uid = session('uid');
	}
	/**
	 * 整单优惠活动列表
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-8-3
	 */
	public function index(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'eshop','url'=>'','rel'=>'','target'=>''),array('name'=>'eshop整单优惠活动','url'=>'','rel'=>'','target'=>'')));
		
		$count = M('eshop_discount')->where(array('companyid'=>$this->companyid))->count();
		$page = new NewPage($count,15);
		$list = M('eshop_discount')->where(array('companyid'=>$this->companyid))->field('id,title,starttime,endtime,isoff,type')->order('createtime DESC')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$page->show());
		$this->display();
	}
	/**
	 * 创建（编辑）活动
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-8-3
	 */
	public function set(){
		if(IS_POST){
			$return['code'] = 'error';
			$return['tips'] = 'error:500';
			
			$time = time();
			$id = $this->_post('id');
			$_POST['starttime'] = strtotime($this->_post('starttime'));
			$_POST['endtime'] = strtotime($this->_post('endtime'));
			$fulljian = ''; //满减优惠
			for($a=1;$a<4;$a++){
				if($_POST['fulljianm'.$a] && $_POST['fulljianj'.$a]){
					$fulljian .= $_POST['fulljianm'.$a].','.$_POST['fulljianj'.$a].'|';
				}
			}
			$_POST['fulljian'] = substr($fulljian,0,-1);
			$fullzhe = ''; //满折优惠
			for($b=1;$b<4;$b++){
				if($_POST['fullzhem'.$b] && $_POST['fullzhez'.$b]){
					$fullzhe.= $_POST['fullzhem'.$b].','.$_POST['fullzhez'.$b].'|';
				}
			}
			$_POST['fullzhe'] = substr($fullzhe,0,-1);
			$fullnumjian = ''; //满件减
			for($c=1;$c<4;$c++){
				if($_POST['fullnumjianm'.$c] && $_POST['fullnumjianj'.$c]){
					$fullnumjian.= $_POST['fullnumjianm'.$c].','.$_POST['fullnumjianj'.$c].'|';
				}
			}
			$_POST['fullnumjian'] = substr($fullnumjian,0,-1);
			$fullnumzhe = ''; //满件折
			for($d=1;$d<4;$d++){
				if($_POST['fullnumzhem'.$d] && $_POST['fullnumzhez'.$d]){
					$fullnumzhe.= $_POST['fullnumzhem'.$d].','.$_POST['fullnumzhez'.$d].'|';
				}
			}
			$_POST['fullnumzhe'] = substr($fullnumzhe,0,-1);
			if($id){
				$where['companyid'] = $this->companyid;
				$where['id'] = $id;
				$_POST['updatetime'] = $time;
				$setSuc = M('eshop_discount')->where($where)->save($_POST);
			}else{
				$_POST['id'] = guidNow();
				$_POST['companyid'] = $this->companyid;
				$_POST['updatetime'] = $_POST['createtime'] = $time;
				$setSuc = M('eshop_discount')->add($_POST);
			}
			if($setSuc){
				$return['id'] = $_POST['id'];
				$return['code'] = 'success';
				$return['tips'] = '操作成功';
			}
			echo json_encode($return);
		}else{
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'eshop','url'=>'javascript:void(0);','rel'=>'','target'=>''),array('name'=>'活动管理','url'=>U('EshopDiscount/index'),'rel'=>'','target'=>''),array('name'=>'活动详情','url'=>'','rel'=>'','target'=>'')));
			
			$id = $this->_get('id');
			if($id){
				$this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'eshop','url'=>'','rel'=>'','target'=>''),array('name'=>'eshop整单优惠活动','url'=>U('EshopDiscount/index'),'rel'=>'','target'=>''),array('name'=>'编辑整单优惠活动','url'=>'','rel'=>'','target'=>'')));
			}else{
				$this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'eshop','url'=>'','rel'=>'','target'=>''),array('name'=>'eshop整单优惠活动','url'=>U('EshopDiscount/index'),'rel'=>'','target'=>''),array('name'=>'创建整单优惠活动','url'=>'','rel'=>'','target'=>'')));
			}
			$info = M('eshop_discount')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('id,title,starttime,endtime,isoff,memberclass,type,money,discount,fulljian,fullzhe,fullnumjian,fullnumzhe')->find();
			if(!$info){
				$info['isoff'] = 1;
				$info['memberclass'] = 1;
				$info['type'] = 1;
			}
			$this->assign('info',$info);
			$this->display();
		}
	}
	/**
	 * 拼团活动列表
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-12
	 */
	public function group(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'eshop','url'=>'javascript:void(0);','rel'=>'','target'=>''),array('name'=>'拼团活动','url'=>'','rel'=>'','target'=>'')));
		$count = M()->table('tp_mall_groupon_activity as act')->join(array("LEFT JOIN tp_mall_goods as goods ON act.goodid = goods.id"))->where(array('act.companyid'=>$this->companyid))->count();
		$page = new NewPage($count,15);
		$list = M()->table('tp_mall_groupon_activity as act')->join(array("LEFT JOIN tp_mall_goods as goods ON act.goodid = goods.id"))->where(array('act.companyid'=>$this->companyid))->field('act.id,act.goodid,act.limitbuy,act.limitnum,act.groupnum,act.qrcode,act.starttime,act.endtime,act.status,goods.title')->order('act.createtime DESC')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$page->show());
		$this->display();
	}
	/**
	 * 创建/编辑 拼团活动
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-12
	 */
	public function groupSet(){
		if(IS_POST){
			$time = time();
			$id = $this->_post('id');
			$priceArr = $this->_post('priceArr');
			$starttime = strtotime($this->_post('starttime'));
			$endtime = strtotime($this->_post('endtime'));
			$goodid = $this->_post('goodid');
			$goodtype = $this->_post('goodtype');
			$groupnum = $this->_post('groupnum');
			$limitbuy = $this->_post('limitbuy');
			$limitnum = $this->_post('limitnum');
			$qrcode = $this->_post('qrcode');
			$data['starttime'] = $starttime;
			$data['endtime'] = $endtime;
			$data['goodid'] = $goodid;
			$data['goodtype'] = $goodtype;
			$data['groupnum'] = $groupnum;
			$data['limitbuy'] = $limitbuy;
			$data['limitnum'] = $limitnum;
			$data['qrcode'] = $qrcode?$qrcode:'';
			$priceArr = explode(';',trim($priceArr,';'));
			foreach($priceArr as $key=>$val){
				$priceArr[$key] = explode(',',$val);
			}
			M()->startTrans();
			if($goodtype == 1 || $goodtype == 3 || $goodtype == 4 || $goodtype == 5){
				foreach($priceArr as $key=>$val){
					$priceArrData['grouponprice'] = $val[1];
					$priceArrData['updatetime'] = $time;
					$result = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'id'=>$val[0]))->save($priceArrData);
				}
			}else{
				foreach($priceArr as $key=>$val){
					$priceArrData['grouponprice'] = $val[1];
					$priceArrData['updatetime'] = $time;
					$result = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$val[0]))->save($priceArrData);
				}
			}
			if($id){
				$where['companyid'] = $this->companyid;
				$where['id'] = $id;
				$data['updatetime'] = $time;
				$setSuc = M('mall_groupon_activity')->where($where)->save($data);
			}else{
				$data['id'] = guidNow();
				$data['status'] = 1;
				$data['companyid'] = $this->companyid;
				$data['updatetime'] = $data['createtime'] = $time;
				$setSuc = M('mall_groupon_activity')->add($data);
			}
			if($setSuc){
				//成功之后将商品改为拼团商品
				$groupData['isgroupon'] =  1;
				$groupData['updatetime'] = $time+1;
				$groupResult = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodid))->save($groupData);
			}
			if($setSuc && $result && $groupResult){
				M()->commit();
				$return['id'] = $id;
				$return['code'] = 200;
				$return['tips'] = '操作成功';
			}else{
				M()->rollback();
				$return['code'] = 300;
				$return['tips'] = '操作失败';
			}
			echo json_encode($return);
		}else{
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'eshop','url'=>'javascript:void(0);','rel'=>'','target'=>''),array('name'=>'拼团活动','url'=>U('EshopDiscount/group'),'rel'=>'','target'=>''),array('name'=>'拼团活动详情','url'=>'','rel'=>'','target'=>'')));
				
			$id = $this->_get('id');
			$info = M()->table('tp_mall_groupon_activity as act')->join(array("LEFT JOIN tp_mall_goods as goods ON act.goodid = goods.id"))->where(array('act.companyid'=>$this->companyid,'act.id'=>$id))->field('act.id as gid,act.goodid,act.limitbuy,act.limitnum,act.groupnum,act.qrcode,act.starttime,act.endtime,act.status,goods.id,goods.title,goods.goodtype,goods.originalprice,goods.saleprice,goods.grouponprice,goods.stockamount')->find();
			$goodSkuList = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$info['goodid']))->field('id,goodid,name,originalprice,saleprice,grouponprice,intprice,imgurl,sort,stockamount,salenum')->order('sort asc')->select();
			if(!$info){
				$info['limitbuy'] = 1;
				$info['status'] = 1;
			}
			$this->assign('goodSkuList',$goodSkuList);
			$this->assign('info',$info);
			$this->display();
		}
	}
	/**
	 * 删除
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-13
	 */
	public function ajaxDelGroup(){
		$return['code'] = 300;
		$return['tips'] = '操作失败';
	
		$where['companyid'] = $this->companyid;
		$where['id'] = $this->_post('id');
		$goodid = M('mall_groupon_activity')->where(array('companyid'=>$this->companyid,'id'=>$where['id']))->getField('goodid');
		$delSuc = M('mall_groupon_activity')->where($where)->delete();
		$goodData['isgroupon'] = 2;
		$goodData['updatetime'] = time();
		$goodResult = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodid))->save($goodData);
		if($delSuc && $goodResult){
			$return['code'] = 200;
			$return['tips'] = '操作成功';
		}
		echo json_encode($return);
	}
	/**
	 * 终止活动
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-13
	 */
	public function ajaxShutDownGroup(){
		$return['code'] = 300;
		$return['tips'] = '操作失败';
	
		$where['companyid'] = $this->companyid;
		$where['id'] = $this->_post('id');
		$goodid = M('mall_groupon_activity')->where(array('companyid'=>$this->companyid,'id'=>$where['id']))->getField('goodid');
		$activityData['status'] = 4;
		$activityData['time'] = time();
		$shutSuc = M('mall_groupon_activity')->where($where)->save($activityData);
		$goodData['isgroupon'] = 2;
		$goodData['updatetime'] = time();
		$goodResult = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodid))->save($goodData);
		if($shutSuc && $goodResult){
			$return['code'] = 200;
			$return['tips'] = '操作成功';
		}
		echo json_encode($return);
	}
	/**
	 * 商品参与活动设置
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-8-4
	 */
	public function goodSet(){
		if(IS_POST){
			$time = time();
			$return['code'] = 'error';
			$return['tips'] = 'error:500';
			
			$id = $this->_post('id');
			$codingno = $this->_post('codingno');
			$codingok = $this->_post('codingok');
			$_POST['codingno'] = str_replace("，",",",$codingno);
			$_POST['codingok'] = str_replace("，",",",$codingok);
			if($id){
				$where['companyid'] = $this->companyid;
				$where['id'] = $id;
				$_POST['updatetime'] = $time;
				$setSuc = M('eshop_discount')->where($where)->save($_POST);
			}else{
				$_POST['companyid'] = $this->companyid;
				$_POST['updatetime'] = $_POST['createtime'] = $time;
				$setSuc = M('eshop_discount_set')->add($_POST);
			}
			if($setSuc){
				$return['code'] = 'success';
				$return['tips'] = '操作成功';
			}
			echo json_encode($return);
		}else{
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'eshop','url'=>'','rel'=>'','target'=>''),array('name'=>'eshop整单优惠活动','url'=>U('EshopDiscount/index'),'rel'=>'','target'=>''),array('name'=>'编辑整单优惠活动','url'=>'','rel'=>'','target'=>'')));
			
			$id = $this->_get('id');
			$info = M('eshop_discount')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('id,isopen,codingno,codingok')->find();
			$this->assign('info',$info);
			$this->display();
		}
	}
	/**
	 * 下载模板
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-6-14
	 */
	public function dowlondExcel(){
		$file_path = $_SERVER['DOCUMENT_ROOT'].'/Tpl/static/special_spdm_templet.csv';
		//首先要判断给定的文件存在与否
		if(!file_exists($file_path)){
			echo "没有该文件文件";
			return ;
		}
		$fp = fopen($file_path,"r");
		$file_size=filesize($file_path);
		//下载文件需要用到的头
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length:".$file_size);
		Header("Content-Disposition: attachment; filename=".'商品代码导入模板.csv');
		$buffer = 1024;
		$file_count = 0;
		//向浏览器返回数据
		while(!feof($fp) && $file_count<$file_size){
			$file_con=fread($fp,$buffer);
			$file_count+=$buffer;
			echo $file_con;
		}
		fclose($fp);
	}
	/**
	 * 导入数据
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-6-14
	 */
	public function importGoods(){
		$return['code'] = 'error';
		$return['tips'] = 'error:500';
		
		$filename = '.'.$this->_post('file');
		$filename2 = substr($filename, -24);
		$fp = fopen($filename, "r");
		while(!feof($fp)){
			if($da = fread($fp,1024*1024*2)){
				$num=substr_count($da,"\n");
				$i+=$num;
			}
		}
		if($filename){
			$j = 0;
			$spdm = '';
			$f = fopen($filename,'r');
			while(!feof($f)){
				$j++;
				$returnid = '';
				$info = '';
				$arr = '';
				$line = fgets($f);
				if($j >= 2){
					$info = explode(',',$line);
					$arr['spdm'] = trim(preg_replace('/(^\s*")|("\s*$)/', '', $info[0]));  //商品代码
					$arr['spdm'] = mb_convert_encoding($arr['spdm'], "UTF-8", "GBK");
					//echo "<pre />";print_r($arr)."<br />";exit();
					if($arr['spdm']){
						$spdm .= $arr['spdm'].',';
					}else{
						if($j >= ($i-1)){
							break;
						}
					}
					unset($arr['spdm']);
				}
			}
			if($spdm){
				$return['code'] = 'success';
				$return['tips'] = '操作成功';
				$return['spdm'] = substr($spdm, 0, -1);
			}
			//删除文件
			if(file_exists($filename)){
				unlink($filename);
			}
		}
		echo json_encode($return);
	}
	/**
	 * 删除
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-8-4
	 */
	public function ajaxDel(){
		$return['code'] = 'error';
		$return['tips'] = 'error:500';
	
		$where['companyid'] = $this->companyid;
		$where['id'] = $this->_post('id');
		$delSuc = M('eshop_discount')->where($where)->delete();
		if($delSuc){
			$return['code'] = 'success';
			$return['tips'] = '操作成功';
		}
		echo json_encode($return);
	}
	/**
	 * ajax--获取商品列表
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-13
	 */
	public function ajaxGoods(){
		$return['code'] = 400;
		$return['msg'] = '好像出错了%>_<%';
	
		$title = $this->_post('title');
		$ids = $this->_post('ids');
		$type = $this->_post('type');
		$where['companyid'] = $this->companyid;
		if($title){
			$where['title'] = array('like','%'.$title.'%');
			$where2['title'] = array('like','%'.$title.'%');
		}
		if($ids){
			if($type==1){
				$where['id'] = array('not in',$ids);
			}else{
				$where['id'] = array('in',$ids);
			}
		}
		$where['isgroupon'] = '2';
		$where['isoffshelves'] = '2';
		$where['issoldout'] = '2';
		$where2['isoffshelves'] = '2';
		$where2['issoldout'] = '2';
		$where2['id'] = array('in',$ids);
		$tags = M('mall_goods')->where($where)->field('id,goodtype,title,voucherimgurl,updatetime')->order('updatetime DESC')->select();
		$ishave = M('mall_goods')->where($where2)->count();
		if($tags){
			$return['code'] = 200;
			foreach($tags as $key=>$val){
				if($type==1){
					$return['html'] .= '<li data-id="'.$val['id'].'">'.$val['title'].'</li>';
				}else{
					$return['html'] .= '<li class="slideinfo" data-id="'.$val['id'].'">';
					$return['html'] .= '<span>'.$val['title'].'</span>';
					$return['html'] .= '<ul class="edit-group-wrap-2 clearfix">';
					$return['html'] .= '<li><i class="edit-icon-group be-romove js-be-romove"></i></li>';
					$return['html'] .= '</ul>';
					$return['html'] .= '</li>';
				}
			}
			$return['msg'] = '操作成功';
		}else if($ishave){
			$return['code'] = 300;
			$return['msg'] = '这个商品已经在右侧选中栏了';
		}else{
			$return['code'] = 300;
			$return['msg'] = '没有这个商品哦';
		}
		echo json_encode($return);
	}
	/**
	 * ajax 获取商品的详细信息
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-12
	 */
	public function ajaxGetGoodsInfo(){
		$return['code'] = 300;
		$return['msg'] = '好像出错了%>_<%';
	
		$where['companyid'] = $this->companyid;
		$id = rtrim($this->_post('goodid'),',');
		$where['id'] = $id;
		$where['isoffshelves'] = '2';
		$where['issoldout'] = '2';
		
		$goodsInfo = M('mall_goods')->where($where)->field('id,goodtype,title,voucherimgurl,updatetime,originalprice,saleprice,grouponprice,stockamount')->find();
		if($goodsInfo['goodtype'] == 1 || $goodsInfo['goodtype'] == 3 || $goodsInfo['goodtype'] == 4 || $goodsInfo['goodtype'] == 5){
			$goodSkuList = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$id))->field('id,goodid,name,originalprice,saleprice,grouponprice,intprice,imgurl,sort,stockamount,salenum')->order('sort asc')->select();
		}
		if($goodsInfo){
			if($goodsInfo['goodtype'] == 1 || $goodsInfo['goodtype'] == 3 || $goodsInfo['goodtype'] == 4 || $goodsInfo['goodtype'] == 5){
				foreach($goodSkuList as $key=>$val){
					$return['html'] .= '<tr data-id="'.$val['id'].'"><td>'.$val['name'].'</td><td>'.$val['originalprice'].'元</td><td>'.$val['saleprice'].'元</td><td><input class="inline w50" type="text" name="grouponprice'.$val['id'].'" value="" placeholder="0.00" data-id="'.$val['id'].'"></td><td>'.$val['stockamount'].'</td></tr>';
				}
			}else{
				$return['html'] .= '<tr data-id="'.$goodsInfo['id'].'"><td>'.$goodsInfo['title'].'</td><td>'.$goodsInfo['originalprice'].'元</td><td>'.$goodsInfo['saleprice'].'元</td><td><input class="inline w50" type="text" name="grouponprice'.$goodsInfo['id'].'" value="" placeholder="0.00" data-id="'.$goodsInfo['id'].'"></td><td>'.$goodsInfo['stockamount'].'</td></tr>';
			}
			$return['goodid'] = $id;
			$return['goodname'] = $goodsInfo['title'];
			$return['goodtype'] = $goodsInfo['goodtype'];
			$return['code'] = 200;
			$return['msg'] = '操作成功';
		}else{
			$return['html'] .= '<tr class="text-center not-hover"><td colspan="5">暂无</td></tr>';
			$return['goodid'] = $id;
			$return['goodname'] = $goodsInfo['title'];
			$return['goodtype'] = $goodsInfo['goodtype'];
			$return['code'] = 300;
			$return['msg'] = '操作失败';
		}
		echo json_encode($return);
	}
	/**
	 * 二维码
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-7-15
	 */
	public function erweima(){
		$url=base64_decode($this->_get('link'));
		$this->getQRcode($url);
	}
}
?>
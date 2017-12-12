<?php
/**
 * eshop商品品类管理
 * @author    姚成凯<kevin@renlaifeng.cn>
 * @since     2016-8-7
 * @version   1.0
 */
class EshopClassAction extends UserAction{
	
	private $companyid;
	
	private $shopsid;
	
	private $uid;
	
	public function _initialize(){
		parent::_initialize();
		//$this->checkCompanyScrm5Permissions(67,TRUE);
		$this->companyid = session('cid');
		$this->shopsid = session('shopsid');
		$this->uid = session('uid');
	}
	/**
	 * 商品品类管理
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-8-7
	 */
	public function index(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'eshop','url'=>'','rel'=>'','target'=>''),array('name'=>'eshop商品品类管理','url'=>'','rel'=>'','target'=>'')));
		$list = M('eshop_class')->where(array('companyid'=>$this->companyid,'ptid'=>''))->field('id,name,tags,ordertype,sort')->order('sort,updatetime desc')->select();
		if($list){
			foreach($list as $key=>$val){
				$list[$key]['tags2'] = M('eshop_tag')->where(array('companyid'=>$this->companyid,'id'=>array('in',$val['tags'])))->field('title')->select();
				$list[$key]['child'] = M('eshop_class')->where(array('companyid'=>$this->companyid,'ptid'=>$val['id']))->field('id,name,tags,ordertype,sort')->order('sort,updatetime desc')->select();
				if($list[$key]['child']){
					foreach($list[$key]['child'] as $ckey=>$cval){
						$list[$key]['child'][$ckey]['tags2'] = M('eshop_tag')->where(array('companyid'=>$this->companyid,'id'=>array('in',$cval['tags'])))->field('title')->select();
					}
				}
			}
		}
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * 设置商品品类管理
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-8-9
	 */
	public function set(){
		if(IS_POST){
			$return['code'] = 'error';
			$return['tips'] = '操作失败';
			$time = time();
			$id = $this->_post('id');
			$_POST['updatetime'] = $time;
			if($id){
				$where['companyid'] = $this->companyid;
				$where['id'] = $id;
				$classSuc = M('eshop_class')->where($where)->save($_POST);
			}else{
				$_POST['id'] = guidNow();
				$_POST['companyid'] = $this->companyid;
				$_POST['createtime'] = $_POST['updatetime'];
				$classSuc = M('eshop_class')->add($_POST);
			}
			if($classSuc){
				$return['code'] = 'success';
				$return['tips'] = '操作成功';
			}
			echo json_encode($return);
		}else{
			$this->ImagesAll($this->companyid);
			
			$id = $this->_get('id');
			if($id){
				$this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'eshop','url'=>'','rel'=>'','target'=>''),array('name'=>'eshop商品品类管理','url'=>U('EshopClass/index'),'rel'=>'','target'=>''),array('name'=>'编辑商品品类','url'=>'','rel'=>'','target'=>'')));
				$info = M('Eshop_class')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('id,name,ptid,tags,title,ordertype,sort,shareimg,sharefriendstitle,sharedes')->find();
				if($info['ptid'] && $info['tags']==','){
					$info['tags'] = M('Eshop_class')->where(array('companyid'=>$this->companyid,'id'=>$info['ptid']))->getField('tags');
				}
				//标签
				$info['tag'] = M('eshop_tag')->where(array('companyid'=>$this->companyid,'id'=>array('in',$info['tags'])))->field('id,title')->select();
				//父级的标签
				$pInfo = M('Eshop_class')->where(array('companyid'=>$this->companyid,'id'=>$info['ptid']))->getField('tags');
				$this->assign('pInfo',$pInfo);
			}else{
				$this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'eshop','url'=>'','rel'=>'','target'=>''),array('name'=>'eshop商品品类管理','url'=>U('EshopClass/index'),'rel'=>'','target'=>''),array('name'=>'创建商品品类','url'=>'','rel'=>'','target'=>'')));
				$info['sort'] = '50';
				$info['ordertype'] = '1';
			}
			//分类
			$info['class'] = M('eshop_class')->where(array('companyid'=>$this->companyid,'ptid'=>''))->field('id,name')->select();
			$this->assign('info',$info);
			$this->display();
		}
	}
	/**
	 * ajax--删除品类
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-4-6
	 */
	public function ajaxDelClass(){
		$return['code'] = 'error';
		$return['tips'] = 'error:500';
		
		$id = $this->_post('id');
		$sucAss = M('eshop_class')->where(array('companyid'=>$this->companyid,'id'=>$id))->delete();
		M('eshop_class')->where(array('companyid'=>$this->companyid,'ptid'=>$id))->delete();
		if($sucAss){
			$return['code'] = 'success';
			$return['tips'] = '操作成功';
		}
		echo json_encode($return);
	}
	/**
	 * 异步获得品类标签tags
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-7-16
	 */
	public function ajaxGetTags(){
		$return['html'] = '';
		
		$tagString = $this->_post('tags');
		$title = $this->_post('title');
		$ptags = $this->_post('ptags');
		$where['companyid'] = $this->companyid;
		if($title){
			$where['title'] = array('like','%'.$title.'%');
		}
		$tags = M('eshop_tag')->where($where)->field('id,title')->order('createtime DESC')->select();
		if($tags){
			foreach($tags as $key=>$val){
				$return['html'] .= '<tr><td>'.$val['title'].'</td><td>';
				if(strpos($tagString, ','.$val['id'].',')!==false){
					$return['html'] .= '<a href="javascript:void(0);" class="tips onTageButton" style="display: none;" data-id="'.$val['id'].'" data-title="'.$val['title'].'">选取</a>';
					if(strstr($ptags, ','.$val['id'].',')!==false){
						$return['html'] .= '<a href="javascript:void(0);" class="tips selectTags" style="display: inline-block;" data-id="'.$val['id'].'" data-title="'.$val['title'].'">取消</a>';
					}else{
						$return['html'] .= '<a href="javascript:void(0);" class="tips offTageButton selectTags" style="display: inline-block;" data-id="'.$val['id'].'" data-title="'.$val['title'].'">取消</a>';
					}
				}else{
					$return['html'] .= '<a href="javascript:void(0);" class="tips onTageButton" style="display: inline-block;" data-id="'.$val['id'].'" data-title="'.$val['title'].'">选取</a>';
					$return['html'] .= '<a href="javascript:void(0);" class="tips offTageButton" style="display: none;" data-id="'.$val['id'].'" data-title="'.$val['title'].'">取消</a>';
				}
				$return['html'] .= '</td></tr>';
			}
		}
		echo json_encode($return);
	}
	/**
	 * 自定义排序--异步编辑商品排序
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-8-9
	 */
	public function ajaxgoodedit(){
		$return['code'] = 'error';
		$return['tips'] = 'error:500';
		
		$id = $this->_post('gid'); //mall_tags_goods_link表ID
		$sort = $this->_post('sort'); //mall_tags_goods_link表SORT
		$saveSuc = M('mall_tags_goods_link')->where(array('companyid'=>$this->companyid,'id'=>$id))->save(array('sort'=>$sort,'updatetime'=>time()));
		if($saveSuc){
			$return['code'] = 'success';
			$return['tips'] = '操作成功';
		}
		echo json_encode($return);
	}
	/**
	 * 异步获得父级品类标签tags
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-7-16
	 */
	public function ajaxParentTag(){
		$return['html'] = '';
		$return['tagsTringId'] = '';
		
		$id = $this->_post('id');
		$where['companyid'] = $this->companyid;
		$where['id'] = $this->_post('cid');
		$tags = M('eshop_class')->where($where)->getField('tags');
		$where['id'] = array('in',$tags);
		$tagList = M('eshop_tag')->where($where)->field('id,title')->select();
		if($tagList){
			foreach($tagList as $key=>$val){
				$return['tagsTringId'] .= $val['id'].',';
				$return['html'] .= '<div class="group-tag mt-3"><span class="func">'.$val['title'].'<a href="javascript:void(0)" data-id="'.$id.'" data-tagsid="'.$val['id'].'"></a></span></div>';
			}
		}
		echo json_encode($return);
	}
	/**
	 * 二维码
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-7-16
	 */
	public function erweima(){
		$url = base64_decode($this->_get('link'));
		$this->getQRcode($url);
	}
}
?>
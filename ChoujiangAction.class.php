<?php
/**
 * 
 * 高力年会抽奖活动
 * 
 * @author      Tomas<416369046@qq.com>
 * @since     2015-12-14
 * @version   1.0
 */
class ChoujiangAction extends BaseAction{
	
	public function __construct(){
		parent::__construct();
	}
	/**
	 * 首页展示
	 * @author   Tomas<416369046@qq.com>
	 * @since  2015-12-14
	 */
	public function index(){
		$this->display();
	}
	/**
	 * 执行抽奖 
	 * @author   Tomas<416369046@qq.com>
	 * @since  2015-12-14
	 */
	public function start(){
		$type=$this->_post('type');
		if($type == '6'){
			$info = M('gaoli_price')->where(array('isfive'=>'1'))->field('num')->order('rand()')->limit('1')->find();
			if($info){
				$info1 = implode($info);
				$data['isfive'] = 2;
				$info2 = str_split($info1);
				$num = $info2['5'];
				$result = M('gaoli_price')->where('num like "%'.$num.'"')->save($data);
			}
		}elseif($type=='0' || $type=='1' || $type=='2' || $type=='3'|| $type=='4' || $type=='5'){
			$info = M('gaoli_price')->where(array('isprice'=>'1'))->field('num')->order('rand()')->limit('1')->find();
			if($info){
				$info1 = implode($info);
					$data['isprice'] = 2;
					$data['grade'] = $type;
					$result = M('gaoli_price')->where('num = '.$info['num'])->save($data);
					$info2 = str_split($info1);
			}
		}
		echo json_encode($info2);
	}
	/**
	 * 高力数据重置
	 * @author   Tomas<416369046@qq.com>
	 * @since  2016-1-16
	 */
	public function reset(){
		$data['grade'] = '';
		$data['isprice'] = '1';
		$data['isfive'] = '1';
		//echo $sql = "UPDATE `tp_gaoli_price` set `grade`='',`isprice`='1',`isfive`='1' where 1";
		$result = M('gaoli_price')->where(1)->save($data);
		//echo M()->getLastSql();echo '<br/>';
		echo '共修改了'.$result.'行';
	}
	/**
	 * 将数据EXCEL导出 
	 * @author   Tomas<416369046@qq.com>
	 * @since  2016-1-16
	 */
	public function exportExcel(){
		set_time_limit(0);
		$where['isprice']  = array('like', '%2%');
		$where['isfive']  = array('like','%2%');
		$where['_logic'] = 'or';
		$map['_complex'] = $where;
		$PrizeList = M('gaoli_price')->where($where)->order('id ASC')->select();
		//dump($PrizeList);
		$data = array('0'=>array('0'=>'','1'=>'','2'=>'','3'=>'','4'=>''));
		if($PrizeList){
			foreach($PrizeList as $pKey=>$pVal){
				if($pVal['grade'] == '0'){
					$pVal['grade'] = '特等奖';
				}elseif($pVal['grade'] == '1'){
					$pVal['grade'] = '一等奖';
				}elseif($pVal['grade'] == '2'){
					$pVal['grade'] = '二等奖';
				}elseif($pVal['grade'] == '3'){
					$pVal['grade'] = '三等奖';
				}elseif($pVal['grade'] == '4'){
					$pVal['grade'] = '四等奖';
				}elseif($pVal['grade'] == '5'){
					$pVal['grade'] = '五等奖';
				}elseif($pVal['grade'] == '6'){
					$pVal['grade'] = '六等奖';
				}
				if($pVal['isprice'] == 2){
					$pVal['isprice'] = '恭喜，中奖啦！';
				}else{
					$pVal['isprice'] = '';
				}
				if($pVal['isfive'] == 2){
					$pVal['isfive'] = '恭喜，中了六等奖！';
				}else{
					$pVal['isfive'] = '';
				}
				$data[$pKey] = array($pVal['id'],$pVal['num'],$pVal['grade'],$pVal['isprice'],$pVal['isfive']);
				unset($paytime);
			}
		}
		$filename="中奖信息";
		$headArr=array('编号','中奖号码','中奖等级','是否中奖','是否中六等奖');
		$this->getExcel($filename,$headArr,$data);
	}
}
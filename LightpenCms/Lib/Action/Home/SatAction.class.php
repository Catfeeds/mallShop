<?php
/**
 * 官网sat反馈页
 *
 * @author    Dunn<dunn@renlaifeng.cn>
 * @since     2016-05-09
 * @version   1.0
 */
class SatAction extends HomeBaseAction{
	public function __construct(){
		parent::__construct();
	}
	/*
	 * sat满意调查
	 */
	public function index(){
		if(session('cid')){
			$this->display();
		}else{
			$this->error('请先登录', U('Index/index'));
		}
	}
	/*
	 * ajax 满意调查
	 */
	public function ajaxIndex(){
		$data['phone'] = session('phone');
		$data['username'] = session('uname');
		$data['companyid'] = session('cid');
		$data['createtime'] = time();
		$data['ip'] = get_client_ip(0);
		$question = $this->_request('question');
		for($i=1;$i<9;$i++){
			$data['question'.$i] = $question[$i];
		}
		$data['question9'] =$this->_post('question9');
		$data['question10'] =$this->_post('question10');
		$s = $data['question1']+$data['question2']+$data['question3']+$data['question4']+$data['question5']+$data['question6']+$data['question7']+$data['question8'];
		$sat = $s/8;
		$data['sat'] =round($sat,2);
		$res = M()->table('tp_check_sat')->add($data);
		if($res){
			$ajax['code']='200';
			$ajax['msg']='提交成功，感谢您的反馈';
		}else{
			$ajax['code']='300';
			$ajax['msg']='提交失败';
		}
		echo json_encode($ajax);
	}
	/*
	 * ajax  服务反馈
	 */
	public function ajaxServe(){
		if($this->_post('aeuser')){
			$data['phone'] = session('phone');
			$data['username'] = session('uname');
			$data['companyid'] = session('cid');
			$data['type'] = 1;
			$data['sname'] =$this->_post('aeuser');
			$data['mark'] =$this->_post('mark');
			$data['createtime'] = time();
			$data['ip'] = get_client_ip(0);
			$data['remark'] =$this->_post('remark');
			$res = M()->table('tp_check_sat_serve')->add($data);
		}
		$data1['phone'] = session('phone');
		$data1['username'] = session('uname');
		$data1['companyid'] = session('cid');
		$data1['type'] = 2;
		$data1['sname'] =$this->_post('amuser');
		$data1['mark'] =$this->_post('mark1');
		$data1['createtime'] = time();
		$data1['ip'] = get_client_ip(0);
		$data1['remark'] =$this->_post('remark1');
		$res1 = M()->table('tp_check_sat_serve')->add($data1);
		if($res||$res1){
			$ajax['code']='200';
			$ajax['msg']='提交成功，感谢您的反馈';
		}else{
			$ajax['code']='300';
			$ajax['msg']='提交失败';
		}
		echo json_encode($ajax);
	}
	/*
	 * ajax  当前页面优化建议
	 */
	public function ajaxBack(){
	    $returnData['code'] = 300;
	    $returnData['tips'] = '抱歉，服务器繁忙，请稍后重试';
	    $companyid = session('cid');
	    $uid = session('uid');
	    if($companyid && $uid){
	        $info = M()->table('tp_users')->where(array('companyid'=>$companyid,'id'=>$uid))->field('id,truename,username,phone')->find();
	        if($info){
	            $message = $this->_post('message');
	            $url = $this->_post('url');
	            if($message && $url){
	                $data['companyid'] = $companyid;
	                $data['url'] = $url;
	                $data['message'] = $message;
	                $data['bugtype'] = 1;
	                if($info['username']){
	                    $data['bname'] = $info['username'];
	                }
	                if($info['phone']){
	                    $data['bmobile'] = $info['phone'];
	                }
	                $data['ip'] = get_client_ip(0);
	                $data['createtime'] = time();
	                $data['bugsource'] = '4';
	                $img1 = $this->_post('img1Src');
	                $img2 = $this->_post('img2Src');
	                $img3 = $this->_post('img3Src');
	                if($img1){
	                    $data['img1'] = $img1;  
	                }
	                if($img2){
	                    $data['img2'] = $img2;
	                }
	                if($img3){
	                    $data['img3'] = $img3;
	                }
	                $return = M()->table('tp_check_sat_back')->add($data);
	                if($return){
	                	//获AED的手机号码
	                	$mobile = M('sell_staffs')->where(array('position'=>5))->getField('mobile');
	                	$AEDName = M('sell_staffs')->where(array('position'=>5))->getField('name');
	                	$content = 'Hi，'.$AEDName.'同学，有客户反馈的bug了，快去check查看并分配跟进的AE吧。Bug编号：'.$return;
	                	$this->sendSms($mobile, $content, $companyid);
	                	//操作成功将短信息发给PM 获取到PM的手机号码
	                	$mobile1 = M('sell_staffs')->where(array('position'=>9))->getField('mobile');
	                	$pmName1 = M('sell_staffs')->where(array('position'=>9))->getField('name');
	                	$content1 = 'Hi，'.$pmName1.'同学，';
	                	$content1 .= '又一个bug来了，快去check查看吧！';
	                	$content1 .= 'Bug编号：'.$return;
	                	$this->sendSms($mobile1, $content1, $companyid);
	                	$this->sendSms('13564012907','Hi,lando大哥，新收到一个BUG，快去check中查看吧，Bug编号：'.$return, $companyid);
	                    $returnData['code'] = 200;
	                    $returnData['tips'] = '恭喜，问题反馈成功';
	                }
	            }
	        }else{
	            $returnData['code'] = 300;
	            $returnData['tips'] = '抱歉，您未登录，请先登录';
	        }
	    }else{
	        $returnData['code'] = 300;
	        $returnData['tips'] = '抱歉，您未登录，请先登录后在提交问题';
	    } 
	    echo json_encode($returnData);
	}
	
	public function ajaxBack1(){
		$data['companyid'] = session('cid');
		$data['url'] = htmlspecialchars_decode(htmlspecialchars_decode($this->_post('url')));
		$data['message'] = $this->_post('message');
		$data['bname'] = $this->_post('bname');
		$data['bmobile'] = $this->_post('bmobile');
		$data['createtime'] = time();
		$data['ip'] = get_client_ip(0);
		$res = M()->table('tp_check_sat_back')->add($data);
		if($res){
			$ajax['code']='200';
			$ajax['msg']='提交成功，感谢您的反馈';
		}else{
			$ajax['code']='300';
			$ajax['msg']='提交失败';
		}
		echo json_encode($ajax);
	}	
}
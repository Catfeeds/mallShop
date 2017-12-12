<?php
/**
 * 公告管理
 *
 * @author    Asa<asa@renlaifeng.cn>
 * @since     2016-0329
 * @version   1.0
 */
class QuestionsAction extends HomeBaseAction{
	
	public function __construct(){
		parent::__construct();
		/* if (!session('userid')||!session('username')){
			session('userid',null);
			session('username',null);
			session("role",null);
			$this->redirect('Check/login');
		} */
		$this->home_url='questions';
		$this->que=M("check_questions_class");
		$this->queart=M("check_questions_info");
		$this->userip=M('check_questions_userip');
	}
	public function style(){
		$this->display();
	}
	public function index(){
		
		$ip = get_client_ip(0);
		//echo $ip;
		
		$i=0;
		$count=$this->que->where(array('parentid'=>0))->count();
		$page = new Page($count,10);
		$list=$this->que->where(array('parentid'=>0))->limit($page->firstRow.','.$page->listRows)->order('createtime desc')->select();
		$this->assign('page',$page->show());
		foreach($list as $val){
			$num = 0;$b=0;
			$list1[$i]=$val;
			$list2=$this->que->where(array('parentid'=>$val['id']))->order('createtime desc')->select();
			foreach ($list2 as $val2){
				$list3[$b]=$val2;
				$list3[$b]['num2']=$this->queart->where(array('classid'=>$val2['id']))->count();
				$num = $num+$list3[$b]['num2'];
				$b++;
			}
			$list1[$i]['list2']=$list3;
			$list1[$i]['num']=$this->queart->where(array('classid'=>$val['id']))->count();
			$list1[$i]['num3']=$b;
			$i++;
			$list3=null;
		}
		$this->list=$list1;
		$this->classa=$this->que->where(array('parentid'=>0))->select();
		$this->hotlist=$this->queart->order('view desc')->limit(0,8)->select();
		$this->display();
	}
	
	/**
	 * 问题列表
	 */
	public function quelist(){
		$this -> info = $info = $this->que->where(array('id'=>$this->_get('id')))->find();
		$list = $this->que->where(array('parentid'=>$this->_get('id')))->select();
		foreach($list as $key => $val){
			$list1[$key]=$val;
			$count = $list1[$key]['artcount']=$this->queart->where(array('classid'=>$val['id']))->count();
			if($count>8){$i=7;}else{$i=8;}
			$list1[$key]['art']=$this->queart->where(array('classid'=>$val['id']))->limit(0,$i)->select();
		}
		$this->list = $list1;
		$this->display();
	}
	/**
	 * 文章列表
	 */
	public function artlist(){
		$this -> info2 = $info2 = $this->que->where(array('id'=>$this->_get('id')))->find();
		$this -> info3 = $this->que->where(array('id'=>$info2['parentid']))->find();
		$count =  $this->queart->where(array('classid'=>$this->_get('id')))->count();
		$page = new Page($count,20);
		$this->info = $info = $this->queart->where(array('classid'=>$this->_get('id')))->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->show());
		$this->display();
	}
	/**
	 * 文章详情
	 */
	public function art(){
		$ip = get_client_ip(0);
		$artid = $art['artid']=$this->_get('id');
		$art['updatetime']=time();
		$art['userip']=$ip;
		$count = $this->userip->where(array('userip'=>$ip))->count();
		if($count>=5){
			$ipinfo = $this->userip->where(array('userip'=>$ip,'artid'=>$artid))->find();
			if($ipinfo){
				$this->userip->where(array('id'=>$ipinfo['id']))->save(array('updatetime'=>time()));
			}else{
				$ipid = $this->userip->where(array('userip'=>$ip))->order('updatetime asc')->limit(0,1)->getfield('id');
				$this->userip->where(array('id'=>$ipid))->save($art);
			}
		}else{
			$ipinfo = $this->userip->where(array('userip'=>$ip,'artid'=>$artid))->find();
			if($ipinfo){
				$this->userip->where(array('userip'=>$ip,'artid'=>$artid))->save($art);
			}else{
				$art['createtime']=time();
				$this->userip->add($art);
			}
			
		}
		$this->info = $info = $this->queart->where(array('id'=>$this->_get('id')))->find();
		$data['view']=$info['view']+1;
		$this->queart->where(array('id'=>$this->_get('id')))->save($data);
		$this -> info2 = $info2 = $this->que->where(array('id'=>$info['classid']))->find();
		$this -> info3 = $this->que->where(array('id'=>$info2['parentid']))->find();
		$this -> hostory = $this->userip->table("tp_check_questions_userip cqu")
		->join("tp_check_questions_info cqi on cqi.id=cqu.artid")->where(array('userip'=>$ip))
		->order("cqu.updatetime desc")->select();
		$this->display();
	}
	/**
	 * 是否有用
	 */
	public function isuse(){
		$id=$this->_post('id');
		$type=$this->_post('type');
		$info = $this->queart->where(array('id'=>$id))->find();
		if($type==1){
			$data['onuse']=$info['onuse']+1;
			$ajax['code']=1;
			$ajax['msg']='谢谢！';
		}else{
			$data['unuse']=$info['unuse']+1;
			$ajax['code']=2;
			$ajax['msg']='感谢你的反馈。';
		}
		$this->queart->where(array('id'=>$id))->save($data);
		echo json_encode($ajax);
	}
	/**
	 * 搜索
	 */
	public function search(){
		$where['title']=array('like','%'.$this->_request('search').'%');
		$this -> search = $this->_request('search');
		$count =  $this->queart->table('tp_check_questions_info qi')->join("tp_check_questions_class qc on qc.id=qi.classid")
		->join("tp_check_questions_class qc2 on qc2.id = qc.parentid")->where($where)->count();
		$page = new Page($count,10);
		$this->list = $info = $this->queart->table('tp_check_questions_info qi')->join("tp_check_questions_class qc on qc.id=qi.classid")
		->join("tp_check_questions_class qc2 on qc2.id = qc.parentid")
		->where($where)->limit($page->firstRow.','.$page->listRows)->field("qi.*,qc.classname,qc2.classname classname2")->select();
		//dump($info);
		$this->assign('page',$page->show());
		$this->display();
	}

	
}
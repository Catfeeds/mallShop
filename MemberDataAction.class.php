<?php
/**
 * 老会员数据导入
 * @author    Leo<1251868177@qq.com>
 * @since     2016-10-24
 * @version   1.0
 */
class MemberDataAction extends UserAction{

	private $uid;
	
	private $companyid;
	
	private $shopsid;
	
	public function __construct(){
		parent::__construct();
		$this->uid = session('uid');
		$this->companyid = session('cid');
		$this->shopsid = session('shopsid');
		$this->checkCompanyScrm5Permissions(29,TRUE);
	}
	/**
	 * 首页
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-10-24
	 */
	public function index(){
	    $this->makeTopUrl = $this->makeTopUrl_User(array($this->APPRECIATION_WELCOME,array('name'=>'数据通','url'=>'','rel'=>'','target'=>''),array('name'=>'老会员导入','url'=>'','rel'=>'','target'=>'')));
	    // tp_member_data_import_task  =>  导入记录表   
	    $where['companyid'] = $this->companyid;
	    $where['status'] = array('IN', '1,2,3,4');
	    $list = M('member_data_import_task')->where($where)->order('tstime DESC')->field('id,sucnum,fainum,status,tstime')->select();
	    $this->assign('list', $list);
	    $this->display();
	}
	/**
	 * 老会员数据导入模板
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-10-24
	 */
	public function dowlondExcel(){
	    $file_path = $_SERVER['DOCUMENT_ROOT'].'/Tpl/static/old_member_import_template.csv';
	    if(!file_exists($file_path)){
	        echo "没有该文件文件";
	        return ;
	    }
	    $fp=fopen($file_path,"r");
	    $file_size=filesize($file_path);
	    Header("Content-type: application/octet-stream");
	    Header("Accept-Ranges: bytes");
	    Header("Accept-Length:".$file_size);
	    Header("Content-Disposition: attachment; filename=".'老会员导入模板.csv');  // 保存的名字
	    $buffer=1024;
	    $file_count=0;
	    while(!feof($fp) && $file_count<$file_size){
	        $file_con=fread($fp,$buffer);
	        $file_count+=$buffer;
	        echo $file_con;
	    }
	    fclose($fp);
	}
	/**
	 * 开始导入
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-10-25
	 */
	public function importData(){
	    $returnData['code'] = 300;
	    $returnData['tips'] = '抱歉，服务器繁忙，请稍后重试';
	    C('TOKEN_ON',false);
	    $filename = str_replace(C('site_url'), '.', '.'.$this->_post('url'));
	    if($filename){
	        $time = time();
	        $fp=fopen($filename, "r");
	        $i=0;
	        while(!feof($fp)) {
	            if($da = fread($fp,1024*1024*2)){
	                $num=substr_count($da,"\n");
	                $i+=$num;
	            }
	        }
	        fclose($fp);
	        if($i<1002 && $i>0){
	            $taskData['id'] = guidNow();
                $taskData['allnum'] = ($i-1);
                $taskData['companyid'] = $this->companyid;
                $taskData['status'] = '1';   // 开始导入
                $taskData['filename'] = $filename;
                $taskData['tstime'] = $taskData['createtime'] = $taskData['updatetime'] = $time;
                $taskReturn = M('member_data_import_task')->add($taskData);
                if($taskReturn){
                    $string = '<tr>';
                    $string .= '<td>';
                    $string .= format_time($time, 'ymdhis');
                    $string .= '</td>';
                    $string .= '<td class="member_data_status_';
                    $string .= $taskData['id'];
                    $string .= '">导入中</td>';
                    $string .= '<td><a href="javascript:void(0);" class="tips">成功导入<span class="member_data_sucnum_';
                    $string .= $taskData['id'];
                    $string .= '">0</span>条，导入失败<span class="member_data_fainum_';
                    $string .= $taskData['id'];
                    $string .= '">0</span>条</a></td>';
                    $string .= '</tr>';

                    $returnData['code'] = 200;
                    $returnData['taskId'] = $taskData['id'];
                    $returnData['tips'] = '文件导入准备就绪';
                    $returnData['html'] = $string;
                }
	        }else{  // 数据多于1000条
	            $returnData['code'] = 300;
	            $returnData['tips'] = '数据大于1000条，请重新选择导入文件，或分批导入';
	        }
	    }else{
	        $returnData['code'] = 300;
	        $returnData['tips'] = '请选择上传文件';
	    }
	    echo json_encode($returnData);
	}
	/**
	 * 实时返回任务进度
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-10-25
	 */
	public function taskInfo(){
	    $returnData['code'] = 300;
	    $returnData['tips'] = '抱歉，服务器繁忙，请稍后重试';
	    $taskId = $this->_post('taskId');
	    if($taskId){
    	    $impInfo = M('member_data_import_task')->where(array('companyid'=>$this->companyid, 'id'=>$taskId))->field('id,allnum,sucnum,fainum,status')->find();
    	    if($impInfo['status'] == 1 || $impInfo['status'] == 2 || $impInfo['status'] == 3|| $impInfo['status'] == 4){
    	        $returnData['code'] = 200;
    	        $returnData['tips'] = '查询成功';
    	        $returnData['status'] = $impInfo['status'];   // 导入状态
    	        // $returnData['nownum'] = $impInfo['sucnum']+$impInfo['fainum'];   // 已完成条数（成功条数+失败条数）
    	        // $returnData['allnum'] = $impInfo['allnum'];   // 总条数
    	        $returnData['sucnum'] = $impInfo['sucnum']?$impInfo['sucnum']:'0';   // 成功条数
    	        $returnData['fainum'] = $impInfo['fainum']?$impInfo['fainum']:'0';   // 失败条数
    	    }
	    }
	    echo json_encode($returnData);
	}
}
?>
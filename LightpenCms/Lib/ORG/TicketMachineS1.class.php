<?php
/**
 * 
 * 小票WIFI打印机
 * 文档地址：http://open.printcenter.cn:8080/deviceapi.jsp?id=1456814344348
 * 
 * S1小票机2.0以上版本适用
 * 一、字体放大
 * 命令^Nn：
 * 该命令位于所有数据前面，用于控制打印张数，可以不加，不加默认打一张。
 * 例如： ^N5
 * 打印机打印测试
 * Print order data
 * ****************
 * 表示打印 5 张相同订单。
 * 命令^Hn
 * 该命令位于每行数据首位，用于控制此行字体纵向放大 n 倍，可以不加，不加默认不放大。
 * 例如：^H2 放大 2 倍
 * 此时“放大 2 倍”这几个字会纵向放大 2 倍，效果见下图。
 * 注意：正常大小的字体，每行最多可以打 16 个汉字或 32 个英文或数字，如果每行超过最大字数限制，会出现多出的字覆盖这行最开始的字，
 * 导致看起来乱码，所以使用该命令请确保字数在每行最大字数内，如果超过，请手动回车换行。
 * 命令^Wn
 * 该命令位于每行数据首位，用于控制此行字体横向放大 n 倍，可以不加，不加默认不放大。
 * 例如：^W2 放大 2 倍
 * 此时“放大 2 倍”这几个字会横向放大 2 倍，效果见下图。
 * 注意：正常大小的字体，每行最多可以打 16 个汉字或 32 个英文或数字，由于^Wn 命令会横向放大 n 倍，如果每行超过（最大字数/n）限制，
 * 会出现多出的字覆盖这行最开始的字，导致看起来乱码，所以使用该命令请确保字数在每行字数在（最大字数/n）内，如果超过，请手动回车换行。
 * 如^W4 测试打印机放大，共 7 个汉字，放大后会占用 28 个汉字的位置，已经超过每行最多 16 个汉字。
 * 命令^Bn
 * 该命令位于每行数据首位，用于控制此行字体横向纵向同时放大 n 倍，可以不加，不加默认不放大。
 * 例如：^B2 放大 2 倍
 * 此时“放大 2 倍”这几个字会横向纵向同时放大 2 倍，效果见下图。
 * 注意：正常大小的字体，每行最多可以打 16 个汉字或 32 个英文或数字，由于^Bn 命令会横向纵向同时放大 n 倍，如果每行超过（最大字数/n）限制，
 * 会出现多出的字覆盖这行最开始的字，导致看起来乱码，所以使用该命令请确保字数在每行字数在（最大字数/n）内，如果超过，请手动回车换行。如 ^B3 测试打印机放大，
 * 共 7 个汉字，放大后会占用 21 个汉字的位置，已经超过每行最多 16 个汉字。
 * 命令^Qn
 * 该命令位于需要打印的宣传关注二维码链接首位，用于将链接自动转换成二维码，达到宣传关注的目的。n 的值为二维码链接的字符长度，用 ASCII 编码表示。
 * 此命令，可以不加，不加默认不打。
 * 例如：^Q +http://weixin.qq.com/r/2Eg2LkzEKRFWrQhN9123
 * 此时 http://weixin.qq.com/r/2Eg2LkzEKRFWrQhN9123 的长度为 43，对应 ASCII 值是’+’，计算机可以自动计算长度，这里为了描述清晰，
 * 查出 43 对应的 ASCII 字符。此链接经过转换后的二维码效果见下图。
 * 注意：此二维码最多支持 49 个字符，请不要加入中文。打印机会自动更具字符多少转换成大小不一样的二维码，智能打印在打印纸中间。使用最为广泛的就是微信公众号的关注二维码。
 * 命令^Pn
 * 该命令位于需要打印的动态支付二维码链接首位，用于将链接自动转换成二维码，达到支付宝等支付的目的（支付成功后，此二维码失效，即：扫描第二次不起作用）。
 * n 的值为二维码链接的长度，用 ASCII 编码表示。此命令，可以不加，不加默认不打。
 * 例如：^P(https://qr.alipay.com/pmr1bs2a1i1udbumf7
 * 此时“https://qr.alipay.com/pmr1bs2a1i1udbumf7”的长度为 40，对应 ASCII 值是’(’，计算机可以自动计算长度，这里为了描述清晰，查出 40 对应的 ASCII 字符。
 * 此链接经过转换后的支付二维码效果见下图。
 * 注意：此动态支付二维码最多支持 49 个字符，请不要加入中文。打印机会自动更具字符多少转换成大小不一样的二维码，智能打印在打印纸中间。
 * 使用最为广泛的就是外卖或用餐结束后，结账时无需再使用现金或者刷卡浪费时间，直接使用支付宝扫描，输入密码支付完成即可，效率将非常高。
 * 命令^On
 * 该命令位于需要打印的动态条码（一维码）链接首位，用于将链接自动转换成扫描枪扫描的一维码，达到迅速录入的目的。n 的值为一维码的长度，用
 * ASCII 编码表示。此命令，可以不加，不加默认不打。
 * 例如： ^P test12345
 * 此时“test12345”的长度为 9，对应 ASCII 值是’ ’(水平制表符)，计算机可以自动计算长度，这里为了描述清晰，查出 9 对应的 ASCII 字符。此条码转换后的效果见下图。
 * 注意：此动态条码最多支持 13 个字符，请不要加入中文。打印机会自动更具字符多少转换成大小不一样的条码，智能打印在打印纸中间。使用最为广泛的就是物流、仓库和超市等地方，
 * 达到迅速录入或查找等应用。
 * 
 * @author    Lando<806728685@qq.com>
 * @since     2016-3-1
 * @version   1.0
 */
class TicketMachineS1
{
	//分隔
	const DATA_SEND_URL = 'http://open.printcenter.cn:8080/';
	private $result;
	private $device_no;// 打印机编号
	private $key;// 密钥
	
	public function __construct($option)
	{
	    $this->device_no = $option['device_no'];
	    $this->key = $option['key'];
	}
	/**
	 * 
	 * 测试案例
	 * 
	 * @author Lando<806728685@qq.com>
	 * @since  2016-3-1
	 */
	public function testPrintOrder(){
	    $this->device_no = 'kdt1025353';
	    $this->key = 'c1a62';
	    $content = "^N1^F1\n";
	    $content .= "^B2 测\n";
	    $content .= "^B2 测试\n";
	    $content .= "^B2 测试打\n";
	    $content .= "^B2 测试打印\n";
	    $content .= "^B2 测试打印测\n";
	    $content .= "^B2 测试打印测试\n";
	    $content .= "^B2 测试打印测试打\n";
	    $content .= "^B2 测试打印测试打印\n";
	    
	    $content .= "^B1 测\n";
	    $content .= "^B1 测试\n";
	    $content .= "^B1 测试打\n";
	    $content .= "^B1 测试打印\n";
	    $content .= "^B1 测试打印测\n";
	    $content .= "^B1 测试打印测试\n";
	    $content .= "^B1 测试打印测试打\n";
	    $content .= "^B1 测试打印测试打印\n";
	    
	    $content .= "^H2 测\n";
	    $content .= "^H2 测试\n";
	    $content .= "^H2 测试打\n";
	    $content .= "^H2 测试打印\n";
	    $content .= "^H2 测试打印测\n";
	    $content .= "^H2 测试打印测试\n";
	    $content .= "^H2 测试打印测试打\n";
	    $content .= "^H2 测试打印测试打印\n";
	    
	    $content .= "^H1 测\n";
	    $content .= "^H1 测试\n";
	    $content .= "^H1 测试打\n";
	    $content .= "^H1 测试打印\n";
	    $content .= "^H1 测试打印测\n";
	    $content .= "^H1 测试打印测试\n";
	    $content .= "^H1 测试打印测试打\n";
	    $content .= "^H1 测试打印测试打印\n";
	    
	    $content .= "^W2 测\n";
	    $content .= "^W2 测试\n";
	    $content .= "^W2 测试打\n";
	    $content .= "^W2 测试打印\n";
	    $content .= "^W2 测试打印测\n";
	    $content .= "^W2 测试打印测试\n";
	    $content .= "^W2 测试打印测试打\n";
	    $content .= "^W2 测试打印测试打印\n";
	     
	    $content .= "^W1 测\n";
	    $content .= "^W1 测试\n";
	    $content .= "^W1 测试打\n";
	    $content .= "^W1 测试打印\n";
	    $content .= "^W1 测试打印测\n";
	    $content .= "^W1 测试打印测试\n";
	    $content .= "^W1 测试打印测试打\n";
	    $content .= "^W1 测试打印测试打印\n";
	    $result = $this->sendPrintDataInfo($content);
	    
	}
	public function testPrintOrder1(){
	    $this->device_no = 'kdt1025353';
	    $this->key = 'c1a62';
	    $content = "^N1^F1\n";
	    $content .= "^B2 测试打印\n";
	    $content .= "名称　　　　　 单价  数量 金额\n";
	    $content .= "--------------------------------\n";
	    $content .= "饭　　　　　　 1.0    1   1.0\n";
	    $content .= "炒饭　　　　　 10.0   10  10.0\n";
	    $content .= "蛋炒饭　　　　 10.0   10  100.0\n";
	    $content .= "鸡蛋炒饭　　　 100.0  1   100.0\n";
	    $content .= "番茄蛋炒饭　　 1000.0 1   100.0\n";
	    $content .= "西红柿蛋炒饭　 1000.0 1   100.0\n";
	    $content .= "西红柿鸡蛋炒饭 100.0  10  100.0\n";
	    $content .= "备注：加辣\n";
	    $content .= "--------------------------------\n";
	    $content .= "^H2合计：xx.0元\n";
	    $content .= "^H2送货地点：上海市虹口区xx路xx号\n";
	    $content .= "^H2联系电话：13888888888888\n";
	    $content .= "^H2订餐时间：2014-08-08 08:08:08\n";
	    $qrlength=chr(strlen('http://www.mobiwind.cn'));
	    $content .= "^Q".$qrlength."http://www.mobiwind.cn\n";
	    $result = $this->sendPrintDataInfo($content);
	    var_dump($result);
	     
	}
	
	/**
	 *
	 * 打印内容
	 *
	 * @param string $orderInfo  打印内容
	 * @param string $times  固定传1
	 * @return JSON 字符串  $result  返回数据
	 * ----------S1小票机返回的结果有如下几种：----------
     *    {"responseCode":0,"msg":"订单添加成功，打印完成","orderindex":"xxxxxxxxxxxxxxxxxx"}
     *    {"responseCode":1,"msg":"订单添加成功，正在打印中","orderindex":"xxxxxxxxxxxxxxxxxx"}
     *    {"responseCode":2,"msg":"订单添加成功，但是打印机缺纸，无法打印","orderindex":"xxxxxxxxxxxxxxxxxx"}
     *    {"responseCode":3,"msg":"订单添加成功，但是打印机不在线","orderindex":"xxxxxxxxxxxxxxxxxx"}
     *    ----------以上情况无须再次发送订单;下面的情况需要进行错误处理----------
     *    {"responseCode":10,"msg":"内部服务器错误；"}
     *    {"responseCode":11,"msg":"参数不正确；"}
     *    {"responseCode":12,"msg":"打印机未添加到服务器；"}
     *    {"responseCode":13,"msg":"未添加为订单服务器；"}
     *    {"responseCode":14,"msg":"订单服务器和打印机不在同一个组；"}
     *    {"responseCode":15,"msg":"订单已经存在，不能再次打印；"}
	 * 
	 * @author Lando<806728685@qq.com>
	 * @since  2016-3-1
	 */
	public function sendPrintDataInfo($orderInfo,$times = 1){ 
	    // $times打印次数
	    $sendData = array(
	            'deviceNo'=>$this->device_no,
	            'key'=>$this->key,
	            'printContent'=>$orderInfo,
	            'times'=>$times
	    );
	    $url = self::DATA_SEND_URL."addOrder";
	    $options = array(
	            'http' => array(
	                    'header' => "Content-type: application/x-www-form-urlencoded ",
	                    'method'  => 'POST',
	                    'content' => http_build_query($sendData),
	            ),
	    );
	    $context  = stream_context_create($options);
	    $result = file_get_contents($url, false, $context);
	    return $result;
	}
	/**
	 * 
	 * 查询订单打印状态
	 * 
	 * @param string $orderindex  发送数据时反馈的打印订单号
	 * @return JSON 字符串  $result  返回数据
	 * ----------S1小票机返回的结果有如下几种：----------
     *    {"responseCode":0,"msg":"打印成功"}
     *    {"responseCode":1,"msg":"正在打印中"}
     *    {"responseCode":2,"msg":"打印机缺纸"}
     *    {"responseCode":3,"msg":"打印机下线"}
     *    {"responseCode":16,"msg":"订单不存在"}
	 * @author Lando<806728685@qq.com>
	 * @since  2016-3-1
	 */
	public function checkPrintOrderInfo($orderindex){
	    // $times打印次数
	    $sendData = array(
	            'deviceNo'=>$this->device_no,
	            'key'=>$this->key,
	            'orderindex'=>$orderindex
	    );
	    $url = self::DATA_SEND_URL."queryOrder";
	    $options = array(
	            'http' => array(
	                    'header' => "Content-type: application/x-www-form-urlencoded ",
	                    'method'  => 'POST',
	                    'content' => http_build_query($sendData),
	            ),
	    );
	    $context  = stream_context_create($options);
	    $result = file_get_contents($url, false, $context);
	    return $result;
	}
	/**
	 *
	 * 查询打印机状态
	 *
	 * @param string $device_no  打印机编号
	 * @param string $key   密钥
	 * @return JSON 字符串  $result  返回数据
	 * ----------S1小票机返回的结果有如下几种：----------
     *    {"responseCode":0,"msg":"打印机正常在线"}
     *    {"responseCode":2,"msg":"打印机缺纸"}
     *    {"responseCode":3,"msg":"打印机下线"}
	 * @author Lando<806728685@qq.com>
	 * @since  2016-3-1
	 */
	public function checkPrintMachineInfo(){
	    // $times打印次数
	    $sendData = array(
	            'deviceNo'=>$this->device_no,
	            'key'=>$this->key
	    );
	    $url = self::DATA_SEND_URL."queryPrinterStatus";
	    $options = array(
	            'http' => array(
	                    'header' => "Content-type: application/x-www-form-urlencoded ",
	                    'method'  => 'POST',
	                    'content' => http_build_query($sendData),
	            ),
	    );
	    $context  = stream_context_create($options);
	    $result = file_get_contents($url, false, $context);
	    return $result;
	}
	
}

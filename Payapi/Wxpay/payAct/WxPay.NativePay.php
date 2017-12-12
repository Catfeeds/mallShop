<?php
require_once "../lib/WxPay.Api.php";

/**
 * 
 * 刷卡支付实现类
 * @author widyhu
 *
 */
class NativePay
{
    
    
    /**
     * TODO: 修改这里配置为您自己申请的商户信息
     * 微信公众号信息配置
     *
     * APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
     *
     * MCHID：商户号（必须配置，开户邮件中可查看）
     *
     * KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
     * 设置地址：https://pay.weixin.qq.com/index.php/account/api_cert
     *
     * APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置），
     * 获取地址：https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN
     * @var string
     */
    protected $appid = '';
    protected $mchid = '';
    protected $key = '';
    protected $appsecret = '';
    
    public function __construct($options)
    {
        $this->appid = isset($options['appid'])?$options['appid']:'';
        $this->mchid = isset($options['mchid'])?$options['mchid']:'';
        $this->key = isset($options['key'])?$options['key']:'';
        $this->appsecret = isset($options['appsecret'])?$options['appsecret']:'';
    }
	/**
	 * 
	 * 生成扫描支付URL,模式一
	 * @param BizPayUrlInput $bizUrlInfo
	 */
    public function GetPrePayUrl($productId)  // 传入商品订单号
    {
        $options['appid'] = $this->appid;
        $options['mchid'] = $this->mchid;
        $options['key'] = $this->key;
        $options['appsecret'] = $this->appsecret;
        $biz = new WxPayBizPayUrl($options);   
        $biz->SetProduct_id($productId);     // 设置商品id
        $tools = new WxPayApi($options);
        $values = $tools->bizpayurl($biz);
        $url = "weixin://wxpay/bizpayurl?" . $this->ToUrlParams($values);
        return $url;
    }
	
	/**
	 * 
	 * 参数数组转换为url参数
	 * @param array $urlObj
	 */
	private function ToUrlParams($urlObj)
	{
		$buff = "";
		foreach ($urlObj as $k => $v)
		{
			$buff .= $k . "=" . $v . "&";
		}

		$buff = trim($buff, "&");
		return $buff;
	}
	
	/**
	 * 
	 * 生成直接支付url，支付url有效期为2小时,模式二
	 * @param UnifiedOrderInput $input
	 */
	public function GetPayUrl($input)
	{
		if($input->GetTrade_type() == "NATIVE")
		{
		    $options['appid'] = $this->appid;
		    $options['mchid'] = $this->mchid;
		    $options['key'] = $this->key;
		    $options['appsecret'] = $this->appsecret;
		    $tools = new WxPayApi($options);
			$result = $tools->unifiedOrder($input);
			return $result;
		}
	}
}
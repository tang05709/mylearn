<?php
namespace App\Library;

class WechatPay 
{
  private $appid = null;
  private $appsecret = null;
  private $mchid = null;
  private $notifyUrl = null;
  private $paykey = null;
  const PAY_URL = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

  public function __construct()
  {
    $this->appid = env('wechat_appid');
    $this->appsecret = env('wechat_appsecret');
    $this->mchid = env('wechat_mchid');
    $this->notifyUrl = env('wechat_notify_url');
    $this->paykey = env('wechat_key');
  }

  public function paySign($data)
  {
    $nonceStr = $this->getNonceStr();
    $payData = [
      'appid' => $this->appid,
      'mch_id' => $this->mchid,
      'nonce_str' => $nonceStr,
      'body' => $data['body'],
      'out_trade_no' => $data['ordersn'],
      'total_fee' => bcmul ($data['amount'], 100), // 单位：分
      'spbill_create_ip' => $data['ip'],
      'notify_url' => $this->notifyUrl,
      'trade_type' => 'JSAPI',
      'openid' => $data['openid']
    ];
    // 拼接url
    $url = $this->toUrlParams($payData);
    // 签名
    $sign = $this->makeSign($url);
    $payData['sign'] = $sign;
    $xml = $this->toXml($payData);
    return $this->postXmlCurl($xml);
  }

  /**
   * 随机字符串
   */
  public function getNonceStr($length = 32) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		} 
		return $str;
  }
  
  /**
   * 数组拼接url
   */
  public function toUrlParams($data)
	{
    //按字典序排序参数
		ksort($data);
		$query = '';
		foreach ($data as $k => $v)
		{
			$query .= $k . "=" . $v . "&";
		}
		$query = trim($query, "&");
		return $query;
  }
  
  /**
   * 数组拼接xml
   */
  public function toXml($data)
	{
    	$xml = "<xml>";
    	foreach ($data as $key=>$val)
    	{
    		if (is_numeric($val)){
    			$xml.="<".$key.">".$val."</".$key.">";
    		}else{
    			$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
    		}
      }
      $xml.="</xml>";
      return $xml; 
	}
  
  public function makeSign($url)
	{
    //在stringA最后拼接上key并md5加密
		$strKey = md5($url.'&key='.$this->paykey);
		//所有字符转为大写
		return strtoupper($strKey);
  }
  
  /**
   * 发送数据
   */
  private function postXmlCurl($xml)
	{		
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch,CURLOPT_URL, self::PAY_URL);
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//post提交方式
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		//运行curl
		$data = curl_exec($ch);
		//返回结果
		if($data){
			curl_close($ch);
			return $data;
		} else { 
			$error = curl_errno($ch);
			curl_close($ch);
			return [
        'err_code' => 1,
        'err_code_des' => $error,
      ];
		}
	}
	
}
?>

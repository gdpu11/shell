<?php
namespace Utils;
/**
 * curl封装
 * @author mmfei<wlfkongl@163.com>
 */
class CurlUtils
{
	private static $_curlInfo = null;

	/**
	 * 获取请求结果信息
	 * @author WangYongJun@kugou.net
	 * @date   2016-12-05
	 * @return [type]     [description]
	 */
	public static function getCurlInfo() {
		return self::$_curlInfo;
	}
	private static $cookie_arr = array(
		'_utma'=>'51854390.1081609616.1491554927.1491554927.1494485214.2',
		'__utmc'=>'51854390',
		'__utmv'=>'51854390.100--|2=registration_date=20160119=1^3=entry_date=20160119=1',
		'__utmz'=>'51854390.1494485214.2.2.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/',
		'_xsrf'=>'d92dda0a2597a8dc99a8dbf6dfef6dc1',
		'_zap'=>'bfac6e93-86f9-438a-a8b1-7af0f57d99a9',
		'acw_tc'=>'AQAAACpKmiEF+gAAxKzXDmw3Q5LnJnzT',
		'aliyungf_tc'=>'AQAAAGoxITgcogcAu+wSDnMGiPbFskVB',
		'cap_id'=>'"MzVkOTJiOTUyYmVhNDA0Yjk2ZDQzOWVmMzkzMjVmZDk=|1494485183|4feb381cc9106b3bce6578d13c6c3eb03c0e3cb8"',
		'd_c0'=>'"AABCT-sdhguPTp0HENc3BtMzgZfbe9xoJw0=|1490758991"',
		'l_n_c'=>'1',
		'q_c1'=>'32fa6ca9dd034413a9449f5090359780|1493975504000|1490758991000',
		'r_cap_id'=>'"NDAzN2M0NDk4Y2Y3NGFiYmFiODJkZjA0NjE5ZTYyYjg=|1494485183|e065d07edefce46cdbcc6ea2c2934e1e6730416d"',
		'z_c0'=>'Mi4wQUJES2wxb1VWZ2tBQUVKUDZ4MkdDeGNBQUFCaEFsVk55WlU3V1FDX1dsOV9GNVpDd3RjZ',
	);

	private static function genCookie() {
		$cookie = '';
		foreach (self::$cookie_arr as $key => $value) {
			if($key != 'z_c0')
				$cookie .= $key . '=' . $value . ';';
			else
				$cookie .= $key . '=' . $value;
		}

		return $cookie;
	}
	private static  function get_rand_ip(){
	  $arr_1 = array("218","218","66","66","218","218","60","60","202","204","66","66","66","59","61","60","222","221","66","59","60","60","66","218","218","62","63","64","66","66","122","211");
	  $randarr= mt_rand(0,count($arr_1));
	  $ip1id = $arr_1[$randarr];
	  $ip2id=  round(rand(600000,  2550000)  /  10000);
	  $ip3id=  round(rand(600000,  2550000)  /  10000);
	  $ip4id=  round(rand(600000,  2550000)  /  10000);
	  return  $ip1id . "." . $ip2id . "." . $ip3id . "." . $ip4id;
	}
	public static function zhihuCurl($url) {
		$ip = self::get_rand_ip();
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$ip, 'CLIENT-IP:'.$ip));  //构造IP  

	    // curl_setopt($ch, CURLOPT_REFERER, 'https://www.zhihu.com');  

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书

		curl_setopt($ch, CURLOPT_COOKIE, self::genCookie());
		// curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$str = curl_exec($ch);  
	    curl_close($ch);  
	    return $str; 
	}

	public static function ccurl($cookie,$user_agent,$destURL, $paramStr='',$flag='get',$ip='10.57.22.151',$fromurl='http://www.baidu.com'){  
	    $curl = curl_init();  
	    if($flag=='post'){//post传递  
	        curl_setopt($curl, CURLOPT_POST, 1);  
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $paramStr);  
	    }  
	    curl_setopt($curl, CURLOPT_URL, $destURL);//地址  
	      
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$ip, 'CLIENT-IP:'.$ip));  //构造IP  
	      
	      
	    curl_setopt($curl, CURLOPT_REFERER, $fromurl);  
	    curl_setopt($curl, CURLOPT_TIMEOUT, 10);#10s超时时间  
	    $user_agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 Safari/537.36 SE 2.X MetaSr 1.0';  
	    curl_setopt ($curl, CURLOPT_USERAGENT, $user_agent);  
	    //curl_setopt ($curl, CURLOPT_COOKIEJAR, $cookie);  
	    curl_setopt ($curl, CURLOPT_COOKIEFILE, $cookie);  
	      
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);  
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
	    $str = curl_exec($curl);  
	    curl_close($curl);  
	    return $str;  
	}  

	/**
	 * 发送post请求
	 * @param string $url
	 * @param $param
	 * @param string $cookieFile
	 * @param string $error			错误内容 - 回调返回
	 * @return string
	 */
	public static function sendPost($url , $param = array() , $cookieFile = null, &$error = null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//https
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书
	    $user_agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 Safari/537.36 SE 2.X MetaSr 1.0';  
	    curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent);  

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		if(!is_null($cookieFile))
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
		$output = curl_exec($ch);
		if ($output === FALSE)
		{
			throw new \Exception(curl_error($ch), 1);
		}
		self::$_curlInfo = curl_getinfo($ch);
		curl_close($ch);
		return $output;
	}
	
	/**
	 * 发送get请求
	 * @param string $url
	 * @param $param
	 * @param string $error			错误内容 - 回调返回
	 * @return string
	 */
	public static function sendGet($url , $param = array() , &$error = null)
	{
		if($param)
		{
			if(false === strpos($url, '?'))
			{
				$url .= "?".http_build_query($param);
			}
			else
			{
				$url = rtrim($url , '&');
				if(!preg_match("/\\?$/", $url))
					$url .= "&";
				$url .= http_build_query($param);
			}
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		//添加https不验证证书
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$output = curl_exec($ch);
		if ($output === FALSE)
		{
			\Fx\DAL\FxLog\FxLog::insertLog($url,'sendGet');
			throw new \Exception(curl_error($ch), 1);
		}
		self::$_curlInfo = curl_getinfo($ch);
		curl_close($ch);
		return $output;
	}
	/**
	 * 模拟http请求
	 * @param string $methodType
	 * @param string $ip
	 * @param string $port
	 * @param string $uri
	 * @param string $getdata
	 * @param string $postdata
	 * @param string $cookie
	 * @param string $custom_headers
	 * @param string $timeout
	 * @param string $req_hdr
	 * @param string $res_hdr
	 * @return string
	 */
	public static function httpRequest(
			$methodType = 'GET',             /* HTTP Request Method (GET and POST supported) */
			$ip,                       /* Target IP/Hostname */
			$port = 80,                /* Target TCP port */
			$uri = '/',                /* Target URI */
			$getdata = array(),        /* HTTP GET Data ie. array('var1' => 'val1', 'var2' => 'val2') */
			$postdata = array(),       /* HTTP POST Data ie. array('var1' => 'val1', 'var2' => 'val2') */
			$cookie = array(),         /* HTTP Cookie Data ie. array('var1' => 'val1', 'var2' => 'val2') */
			$custom_headers = array(), /* Custom HTTP headers ie. array('Referer: http://localhost/ */
			$timeout = 1,           /* Socket timeout in seconds */
			$req_hdr = false,          /* Include HTTP request headers */
			$res_hdr = false           /* Include HTTP response headers */
	)
	{
		$ret = '';
		$methodType = strtoupper($methodType);
		$cookie_str = '';
		$getdata_str = count($getdata) ? '?' : '';
		$postdata_str = '';
	
		foreach ($getdata as $k => $v)
			$getdata_str .= urlencode($k) .'='. urlencode($v) . '&';
	
		foreach ($postdata as $k => $v)
			$postdata_str .= urlencode($k) .'='. urlencode($v) .'&';
	
		foreach ($cookie as $k => $v)
			$cookie_str .= urlencode($k) .'='. urlencode($v) .'; ';
	
		$crlf = "\r\n";
		$req = $methodType .' '. $uri . $getdata_str .' HTTP/1.1' . $crlf;
		$req .= 'Host: '. $ip . $crlf;
		$req .= 'User-Agent: Mozilla/5.0 Firefox/3.6.12' . $crlf;
		$req .= 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' . $crlf;
		$req .= 'Accept-Language: en-us,en;q=0.5' . $crlf;
		$req .= 'Accept-Encoding: deflate' . $crlf;
		$req .= 'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7' . $crlf;
	
		foreach ($custom_headers as $k => $v)
			$req .= $k .': '. $v . $crlf;
	
		if (!empty($cookie_str))
			$req .= 'Cookie: '. substr($cookie_str, 0, -2) . $crlf;
	
		if ($methodType == 'POST' && !empty($postdata_str))
		{
			$postdata_str = substr($postdata_str, 0, -1);
			$req .= 'Content-Type: application/x-www-form-urlencoded' . $crlf;
			$req .= 'Content-Length: '. strlen($postdata_str) . $crlf . $crlf;
			$req .= $postdata_str;
		}
		else $req .= $crlf;
	
		if ($req_hdr)
			$ret .= $req;
	
		if (($fp = @fsockopen($ip, $port, $errno, $errstr)) == false)
			return "Error $errno: $errstr\n";
	
		stream_set_timeout($fp, 0, $timeout * 1000);

		fputs($fp, $req);
		while ($line = fgets($fp)) $ret .= $line;
		fclose($fp);
	
		if (!$res_hdr)
			$ret = substr($ret, strpos($ret, "\r\n\r\n") + 4);

		return $ret;
	}	
	
	/**
	 * 模拟http请求(与c++服务器通讯)
	 * @param string $methodType
	 * @param string $ip
	 * @param string $port
	 * @param string $uri
	 * @param string $getdata
	 * @param string $postdata
	 * @param string $cookie
	 * @param string $custom_headers
	 * @param string $timeout
	 * @param string $req_hdr
	 * @param string $res_hdr
	 * @return string
	 * @author lilingfei
	 */
	public static function httpRequestWhitC(
			$methodType = 'GET',             /* HTTP Request Method (GET and POST supported) */
			$ip,                       /* Target IP/Hostname */
			$port = 80,                /* Target TCP port */
			$uri = '/',                /* Target URI */
			$getdata = array(),        /* HTTP GET Data ie. array('var1' => 'val1', 'var2' => 'val2') */
			$postdata = array(),       /* HTTP POST Data ie. array('var1' => 'val1', 'var2' => 'val2') */
			$cookie = array(),         /* HTTP Cookie Data ie. array('var1' => 'val1', 'var2' => 'val2') */
			$custom_headers = array(), /* Custom HTTP headers ie. array('Referer: http://localhost/ */
			$timeout = 1,           /* Socket timeout in seconds */
			$req_hdr = false,          /* Include HTTP request headers */
			$res_hdr = false           /* Include HTTP response headers */
	)
	{
		$ret = '';
		$methodType = strtoupper($methodType);
		$cookie_str = '';
		$getdata_str = count($getdata) ? '?' : '';
		$postdata_str = '';
	
		foreach ($getdata as $k => $v){
			$getdata_str .= urlencode($k) .'='. urlencode($v) . '&';
		}
		$postdata_str = json_encode($postdata);
	
		foreach ($cookie as $k => $v){
			$cookie_str .= urlencode($k) .'='. urlencode($v) .'; ';
		}
			
		$crlf = "\r\n";
		$req = $methodType .' '. $uri . $getdata_str .' HTTP/1.1' . $crlf;
		$req .= 'Host: '. $ip . $crlf;
		$req .= 'User-Agent: Mozilla/5.0 Firefox/3.6.12' . $crlf;
		$req .= 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' . $crlf;
		$req .= 'Accept-Language: en-us,en;q=0.5' . $crlf;
		$req .= 'Accept-Encoding: deflate' . $crlf;
		$req .= 'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7' . $crlf;
	
		foreach ($custom_headers as $k => $v){
			$req .= $k .': '. $v . $crlf;
		}
		
		if (!empty($cookie_str)){
			$req .= 'Cookie: '. substr($cookie_str, 0, -2) . $crlf;
		}
			
		if ($methodType == 'POST' && !empty($postdata_str)){
			$req .= 'Content-Type: application/x-www-form-urlencoded' . $crlf;
			$req .= 'Content-Length: '. strlen($postdata_str) . $crlf . $crlf;
			$req .= $postdata_str;
		}
		else {
			$req .= $crlf;
		}
		if ($req_hdr){
			$ret .= $req;
		}
		if (($fp = @fsockopen($ip, $port, $errno, $errstr)) == false){
			$message = "点唱机获取房间报错："."Error $errno: $errstr\n";
			\Fx\DAL\FxLog\FxLog::insertLog($message);
			return false;
		}
		
		stream_set_timeout($fp, 0, $timeout * 100000);

		fputs($fp, $req);
		
		while ($line = fgets($fp)) $ret .= $line;
		fclose($fp);
		
		if (!$res_hdr){
			$ret = substr($ret, strpos($ret, "\r\n\r\n") + 4);
		}
		return $ret;
	}
	/**
	 * 模拟http请求 -- 非正式协议
	 * @param string $methodType
	 * @param string $ip
	 * @param string $port
	 * @param string $uri
	 * @param string $getdata
	 * @param string $postdata
	 * @param string $cookie
	 * @param string $custom_headers
	 * @param string $timeout
	 * @param string $req_hdr
	 * @param string $res_hdr
	 * @return string
	 */
	public static function httpRequestForKugou(
			$methodType = 'GET',             /* HTTP Request Method (GET and POST supported) */
			$ip,                       /* Target IP/Hostname */
			$port = 80,                /* Target TCP port */
			$uri = '/',                /* Target URI */
			$getdata = array(),        /* HTTP GET Data ie. array('var1' => 'val1', 'var2' => 'val2') */
			$postdata = array(),       /* HTTP POST Data ie. array('var1' => 'val1', 'var2' => 'val2') */
			$cookie = array(),         /* HTTP Cookie Data ie. array('var1' => 'val1', 'var2' => 'val2') */
			$custom_headers = array(), /* Custom HTTP headers ie. array('Referer: http://localhost/ */
			$timeout = 1,           	/* Socket timeout in seconds */
			$req_hdr = false,          /* Include HTTP request headers */
			$res_hdr = false           /* Include HTTP response headers */
	)
	{
		$ret = '';
		$methodType = strtoupper($methodType);
		$cookie_str = '';
		$getdata_str = count($getdata) ? '?' : '';
		$postdata_str = '';
	
		foreach ($getdata as $k => $v)
			$getdata_str .= urlencode($k) .'='. urlencode($v) . '&';
	
// 		foreach ($postdata as $k => $v)
// 			$postdata_str .= urlencode($k) .'='. urlencode($v) .'&';
		$postdata_str = json_encode($postdata);
		$postdata_str= preg_replace(array("#\\\u([0-9a-f][0-9a-f][0-9a-f][0-9a-f])#ie","/\"(\d+)\"/",), array("iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))","\\1"), $postdata_str);

		foreach ($cookie as $k => $v)
			$cookie_str .= urlencode($k) .'='. urlencode($v) .'; ';
	
		$crlf = "\r\n";
		$req = $methodType .' '. $uri . $getdata_str .' HTTP/1.1' . $crlf;
		$req .= 'Host: '. $ip . $crlf;
		$req .= 'User-Agent: Mozilla/5.0 Firefox/3.6.12' . $crlf;
		$req .= 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' . $crlf;
		$req .= 'Accept-Language: en-us,en;q=0.5' . $crlf;
		$req .= 'Accept-Encoding: deflate' . $crlf;
		$req .= 'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7' . $crlf;
	
		foreach ($custom_headers as $k => $v)
			$req .= $k .': '. $v . $crlf;
	
		if (!empty($cookie_str))
			$req .= 'Cookie: '. substr($cookie_str, 0, -2) . $crlf;
	
		if ($methodType == 'POST' && !empty($postdata_str))
		{
// 			$postdata_str = substr($postdata_str, 0, -1);
			$req .= 'Content-Type: application/x-www-form-urlencoded' . $crlf;
			$req .= 'Content-Length: '. strlen($postdata_str) . $crlf . $crlf;
			$req .= $postdata_str;
		}
		else $req .= $crlf;
	
		if ($req_hdr)
			$ret .= $req;
	
		if (($fp = @fsockopen($ip, $port, $errno, $errstr)) == false)
			return "Error $errno: $errstr\n";
	
		stream_set_timeout($fp, 0, $timeout * 1000);
	
		fputs($fp, $req);
		while ($line = fgets($fp)) $ret .= $line;
		fclose($fp);
		if (!$res_hdr)
			$ret = substr($ret, strpos($ret, "\r\n\r\n") + 4);

		return $ret;
	}
}
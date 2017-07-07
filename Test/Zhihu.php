<?php
namespace Test;
use DB\AnchorTABLE;
use DB\Anchor1TABLE;
use DB\MsgSourceTABLE;
use DB\HashTABLE;
use DB\MsgTABLE;
use DB\ZhUserTABLE;
use DB\ZhUserDetailTABLE;
use DB\ZhUserEdTABLE;
use DB\DBConnect;
use Utils\RedisUtil;
use Utils\CurlUtils;
use Utils\Common;
class Zhihu
{
	//php G:\nginx\shell\cli.php Zhihu getMsg
	///usr/local/php/bin/php  /data/zhihu/shell/cli.php Zhihu getMsg
	public static function getDataByUrl($url){
		$result = CurlUtils::zhihuCurl($url);
		$result = htmlspecialchars_decode($result);
		$data = array();
		// preg_match_all('#<a class="UserLink-link" target="_blank" href="\/people\/(.*?)">(.*?)</a>#', $result, $u_out);
		preg_match_all('#\",\"urlToken\":\"(.*?)\",#', $result, $data['urlToken']);
		preg_match_all('#false,\"avatarUrl\":\"(.*?)\",#', $result, $data['avatarUrl']);
		preg_match_all('#,\"headline\":\"(.*?)\",#', $result, $data['headline']);
		preg_match_all('#,\"name\":\"(.*?)\",#', $result, $data['name']);
		preg_match_all('#,\"articlesCount\":(.*?),#', $result, $data['articlesCount']);
		preg_match_all('#\"people\",\"answerCount\":(.*?),#', $result, $data['answerCount']);
		preg_match_all('#\",\"userType\":\"(.*?)\",#', $result, $data['userType']);
		preg_match_all('#,\"id\":\"(.*?)\",\"articlesCount#', $result, $data['id']);

		$data['articlesCount'][0] = array_slice($data['articlesCount'][0], -20);
		$data['articlesCount'][1] = array_slice($data['articlesCount'][1], -20);
		$data['name'][0] = array_slice($data['name'][0], -20);
		$data['name'][1] = array_slice($data['name'][1], -20);
		$data['headline'][0] = array_slice($data['headline'][0], -20);
		$data['headline'][1] = array_slice($data['headline'][1], -20);
		$data['urlToken'][0] = array_slice($data['urlToken'][0], -20);
		$data['urlToken'][1] = array_slice($data['urlToken'][1], -20);
		return $data;
	}

	public static function getDataByMultiUrl($url){
		$result = CurlUtils::zhihuMultiCurl($url);
		$data = array();
		foreach ($result as $key => $value) {
			$value = htmlspecialchars_decode($value);
			// preg_match_all('#<a class="UserLink-link" target="_blank" href="\/people\/(.*?)">(.*?)</a>#', $value, $u_out);
			preg_match_all('#\",\"urlToken\":\"(.*?)\",#', $value, $data[$key]['urlToken']);
			preg_match_all('#false,\"avatarUrl\":\"(.*?)\",#', $value, $data[$key]['avatarUrl']);
			preg_match_all('#,\"headline\":\"(.*?)\",#', $value, $data[$key]['headline']);
			preg_match_all('#,\"name\":\"(.*?)\",#', $value, $data[$key]['name']);
			preg_match_all('#,\"articlesCount\":(.*?),#', $value, $data[$key]['articlesCount']);
			preg_match_all('#\"people\",\"answerCount\":(.*?),#', $value, $data[$key]['answerCount']);
			preg_match_all('#\",\"userType\":\"(.*?)\",#', $value, $data[$key]['userType']);
			preg_match_all('#,\"id\":\"(.*?)\",\"articlesCount#', $value, $data[$key]['id']);
			$data[$key]['articlesCount'][0] = array_slice($data[$key]['articlesCount'][0], -20);
			$data[$key]['articlesCount'][1] = array_slice($data[$key]['articlesCount'][1], -20);
			$data[$key]['name'][0] = array_slice($data[$key]['name'][0], -20);
			$data[$key]['name'][1] = array_slice($data[$key]['name'][1], -20);
			$data[$key]['headline'][0] = array_slice($data[$key]['headline'][0], -20);
			$data[$key]['headline'][1] = array_slice($data[$key]['headline'][1], -20);
			$data[$key]['urlToken'][0] = array_slice($data[$key]['urlToken'][0], -20);
			$data[$key]['urlToken'][1] = array_slice($data[$key]['urlToken'][1], -20);
		}
		return $data;
	}
	public static function saveData($data){
		$insert = array();
		$cur = array();

		$count = count($data['urlToken'][1]);
		for ($i=0; $i < $count; $i++) { 
			foreach ($data as $key => $value) {
				$cur[$key] = isset($data[$key][1][$i])?$data[$key][1][$i]:'';
			}
			$insert[] = $cur;
		}
		return ZhUserDetailTABLE::addOne($insert);
	}
	//php G:\nginx\shell\cli.php Zhihu getMsg
	///usr/local/php/bin/php  /data/zhihu/shell/cli.php Zhihu getMsg
	public static function getMsg(){
		$url = 'https://www.zhihu.com/people/laruence/followers?page=2';
		$data = self::getDataByUrl($url);
		self::saveData($data);	
		exit();
	}	
	//php G:\nginx\shell\cli.php Zhihu forbidden
	///usr/local/php/bin/php  /data/zhihu/shell/cli.php Zhihu forbidden
	public static function forbidden(){
		while (1) {
			file_get_contents('https://www.zhihu.com/people/laruence/followers?page=2');
			sleep(60);
		}
	}
	//php G:\nginx\shell\cli.php Zhihu getFollowing
	//关注
	public static function getFollowing(){
		$user_type = 'following';
		self::getUserByType($user_type);
	}
	//php G:\nginx\shell\cli.php Zhihu getFollowers
	//关注者-粉丝
	public static function getFollowers(){
		$user_type = 'followers';
		self::getUserByType($user_type);
	}
	//php G:\nginx\shell\cli.php Zhihu getFollowing
	//关注者--粉丝
	public static function getUserByType($user_type){
		date_default_timezone_set("PRC");
		$limitKey = 'ZH_REQUEST';
		$redisKey = 'ZH_'.$user_type;
		if (RedisUtil::llen($redisKey) == 0)
		{
			RedisUtil::sadd($redisKey, 'li-qiang-2-73');
		}
		$page = 1;
		$status = 1;
		while (1) {

			if (!RedisUtil::exists($limitKey)) {
				sleep(30);
				RedisUtil::incr($limitKey);
				RedisUtil::expire($limitKey,30);
			}

			if ($status==1) {
				$urlToken = RedisUtil::spop($redisKey);
			}

			if (!$urlToken)
			{
				$time = date('Y-m-d H:i:s',time());echo $time.'--list--empty';

				exit();
			}elseif (ZhUserEdTABLE::getOne(array('urlToken'=>$urlToken,'type'=>$user_type))) {
				$page = 1;
				$status = 1;
				$time = date('Y-m-d H:i:s',time());
				echo $time.'--continue--'.$urlToken."\n";
				continue;
			}
			$url = 'https://www.zhihu.com/people/' . $urlToken . '/' . $user_type.'?page='.$page;
			$data = self::getDataByUrl($url);

			self::saveData($data);	
			//判断是否有20个
			if (isset($data['urlToken'][1][19])) {
				$page++;
				$status = 0;
			}else{
				ZhUserEdTABLE::addOne(array('urlToken'=>$urlToken,'type'=>$user_type));
				$page = 1;
				$status = 1;
			}
			$time = date('Y-m-d H:i:s',time());
			echo $time.'--savedata--'.count($data['urlToken'][1]).'---'.$urlToken."\n";
			foreach ($data['urlToken'][1] as $key => $value) {
				RedisUtil::sadd($redisKey, $value);
			}
			$time = date('Y-m-d H:i:s',time());
			echo $time.'--finish--'.$urlToken."\n";
			// sleep(2);
		}		
	}
	//php G:\nginx\shell\cli.php Zhihu getMultiFollowing
	//关注
	public static function getMultiFollowing(){
		$user_type = 'following';
		self::getMultiUserByType($user_type);
	}
	//php G:\nginx\shell\cli.php Zhihu getMultiFollowers
	//关注者-粉丝
	public static function getMultiFollowers(){
		$user_type = 'followers';
		self::getMultiUserByType($user_type);
	}
	//php G:\nginx\shell\cli.php Zhihu getFollowing
	//关注者--粉丝
	public static function getMultiUserByType($user_type){
		date_default_timezone_set("PRC");
		$limitKey = 'ZH_REQUEST';
		$redisKey = 'ZH_'.$user_type;
		if (RedisUtil::llen($redisKey) == 0)
		{
			RedisUtil::sadd($redisKey, 'li-qiang-2-73');
		}
		$urlArr = array();
		$Arr = array(array('status'=>1,'page'=>1),array('status'=>1,'page'=>1),array('status'=>1,'page'=>1));
		while (1) {
			if (!RedisUtil::exists($limitKey)) {
				sleep(30);
				RedisUtil::incr($limitKey);
				RedisUtil::expire($limitKey,30);
			}
			$i = 0;
			foreach ($Arr as $key => &$value) {
				if ($value['status']==1) {
					while (1) {
						$url = RedisUtil::spop($redisKey);
						if (!ZhUserEdTABLE::getOne(array('urlToken'=>$url,'type'=>$user_type))) {
							$value['urlToken'] = $url;
							break;
						}
					}
				}
				if (!empty($value['urlToken'])) {
					$i++;
					$value['url'] = 'https://www.zhihu.com/people/' . $value['urlToken'] . '/' . $user_type.'?page='.$value['page'];
				}
			}
			if ($i==0)
			{
				$time = date('Y-m-d H:i:s',time());echo $time.'--list--empty';
				exit();
			}
			foreach ($Arr as $key => $value) {
				$urlArr[$key] = $value['url'];
			}
			$data = self::getDataByMultiUrl($urlArr);
			$time = date('Y-m-d H:i:s',time());
			foreach ($data as $key => $value) {
				if (empty($value['urlToken'][1])) {
					continue;
				}
				self::saveData($value);	
				//判断是否有20个
				if (isset($value['urlToken'][1][19])) {
					$Arr[$key]['page']++;
					$Arr[$key]['status'] = 0;
				}else{
					ZhUserEdTABLE::addOne(array('urlToken'=>$Arr[$key]['urlToken'],'type'=>$user_type));
					$Arr[$key]['page'] = 1;
					$Arr[$key]['status'] = 1;
				}
				echo $time.'--savedata--'.count($value['urlToken'][1]).'---'.$Arr[$key]['urlToken']."\n";
				foreach ($value['urlToken'][1] as $val) {
					RedisUtil::sadd($redisKey, $val);
				}
			}
			$time = date('Y-m-d H:i:s',time());
			// echo $time.'--finish--'.$urlToken."\n";
			echo $time.'--finish--'."\n";
			// sleep(2);
		}		
	}


}
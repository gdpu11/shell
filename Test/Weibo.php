<?php
namespace Test;
use DB\AnchorTABLE;
use DB\Anchor1TABLE;
use DB\MsgSourceTABLE;
use DB\HashTABLE;
use DB\MsgTABLE;
use DB\ZhUserTABLE;
use DB\WeiboUserDetailTABLE;
use DB\WeiboListTABLE;
use DB\WeiboUserEdTABLE;
use DB\DBConnect;
use Utils\RedisUtil;
use Utils\CurlUtils;
use Utils\Common;
class Weibo
{
	//php G:\nginx\shell\cli.php Weibo getDataByUrl
	///usr/local/php/bin/php  /data/zhihu/shell/cli.php Weibo getMsg
	public static function getDataByUrl($url){
		$data = array();
		$result = CurlUtils::weiboCurl($url);
		$result = stripslashes($result);
		preg_match_all('#\" src=\"\/\/(.*?)\"\>rnt#', $result, $data['src']);
		preg_match_all('#\"_blank\" title=\"(.*?)\" href#', $result, $data['nick']);
		preg_match_all('#tt\<img usercard=\"id=(.*?)\&refer_flag=#', $result, $data['uid']);
		preg_match_all('#\/fans\?current=fans\" \>(.*?)\<\/a\>\<\/em\>\<\/span\>rntttttt\<span class=\"conn_type W_vline S_line1\"\>微博#', $result, $data['fans']);
		preg_match_all('#\/follow\" \>(.*?)\<\/a\>\<\/em\>\<\/span\>rntttttt\<span class=\"conn_type W_vline S_line1\"\>粉丝#', $result, $data['follow']);
		preg_match_all('#地址\<\/em\>\<span\>(.*?)\<\/span\>rnttttt\<#', $result, $data['addr']);
		preg_match_all('#class=\"info_intro\"\>(.*?)\<span\>(.*?)\<\/span\>\<\/div\>rntttttttt\<div class=\"info_from\"\>#', $result, $data['introduce']);
		preg_match_all('#微博\<em class=\"count\"\>(.*?)\" \>(.*?)\<\/a\>\<\/em\>\<\/span\>rnttttttttttrntttt#', $result, $data['postCount']);
		preg_match_all('#followTab\.index\",\"domid\":\"(.*?)\",\"css\":\[\"style\/css\/module\/pagecard\/PCD_connectlist\.css#', $result, $data['pageIndex']);
		preg_match_all('#\<a class=\"tab_item S_line1 tab_cur\" bpfilter=\"page\" href=\"\/p\/(.*?)\/follow\?pids=Pl_Official_HisRelation_#', $result, $data['pageId']);
		preg_match_all('#var \$CONFIG = \{\}\;(.*?)\<\/script\>#is', $result, $data['CONFIG']);
		$data['introduce'][1] = $data['introduce'][2];
		$data['postCount'][1] = $data['postCount'][2];
		foreach ($data as $key => &$value) {
			$value = $value[1];
			if ($key=='CONFIG') {
				$value = isset($value[0])?$value[0]:'';
			}
		}
		$CONFIG = array();	
		if (!empty($data['CONFIG'])) {
			$data['CONFIG'] = str_replace('new Date()', 'time()*1000', $data['CONFIG']);
			$data['CONFIG'] = str_replace('["', '\'', $data['CONFIG']);
			$data['CONFIG'] = str_replace(']; ', '\';', $data['CONFIG']);
			$data['CONFIG'] = str_replace('$webim', 'webim', $data['CONFIG']);
			eval($data['CONFIG']);
		}
		
		$data['CONFIG'] = $CONFIG;
		return $data;
	}

	public static function saveData($data){
		WeiboUserDetailTABLE::addOne(array_merge(array('pageIndex'=>$data['pageIndex'][0]),$data['CONFIG']) );
		if (!empty($data['CONFIG'])) {
			$uid = array(
				'pageIndex'=>$data['pageIndex'][0],
				'pageId'=>$data['pageId'][0],
				'uid'=>$data['CONFIG']['oid'],
				);
			RedisUtil::sadd('WEIBO_User_List', serialize($uid));
		}
		unset($data['CONFIG']);
		$insert = array();
		$cur = array();
		$count = count($data['uid']);
		for ($i=0; $i < $count; $i++) { 
			foreach ($data as $key => $value) {
				$cur[$key] = isset($data[$key][$i])?$data[$key][$i]:'';
			}
			if (!is_array($cur)) {
				continue;
			}
			$insert[] = $cur;
		}
		return WeiboListTABLE::addOne($insert);
	}
	//php G:\nginx\shell\cli.php Weibo getfollow
	///usr/local/php/bin/php  /data/zhihu/shell/cli.php Weibo getMsg
	public static function getfollow(){
		$type = 'follow?';
		self::getUserByType($type);

	}	
	//php G:\nginx\shell\cli.php Weibo getfans
	///usr/local/php/bin/php  /data/zhihu/shell/cli.php Weibo getMsg
	public static function getfans(){
		$type = 'follow?relate=fans&';
		self::getUserByType($type);
	}	
	//php G:\nginx\shell\cli.php Weibo getUserDetail
	///usr/local/php/bin/php  /data/zhihu/shell/cli.php Weibo getMsg
	public static function getUserDetail(){
		$type = 'UserDetail';
		self::getUserDetailByType($type);
	}	
	//php G:\nginx\shell\cli.php Weibo getFollowing
	//关注者--粉丝
	public static function getUserDetailByType($type){
		date_default_timezone_set("PRC");
		$limitKey = 'WEIBO_REQUEST';
		$redisKey = 'WEIBO_'.$type;
		
		if (RedisUtil::llen($redisKey) == 0)
		{
			RedisUtil::sadd($redisKey, '2365723822');
		}
		while (1) {
			/*if (!RedisUtil::exists($limitKey)) {
				sleep(30);
				RedisUtil::incr($limitKey);
				RedisUtil::expire($limitKey,30);
			}*/
			// echo RedisUtil::scard($redisKey);exit();
			
			while (1) {
				$uid = RedisUtil::spop($redisKey);
				if (!WeiboUserEdTABLE::getOne(array('uid'=>$uid,'type'=>$type))) {
					break;
				}
			}
			if (!$uid)
			{
				$time = date('Y-m-d H:i:s',time());echo $time.'--list--empty';
				exit();
			}

			$url = 'http://weibo.com/'.$uid.'/fans?current=fans';
			// $url = 'http://weibo.com/p/'.$pageId.'/'.$type.'page='.$page.'#'.$pageIndex;
			$data = self::getDataByUrl($url);
			self::saveData($data);	

			$time = date('Y-m-d H:i:s',time());
			echo $time.'--savedata--'.count($data['uid']).'---'.$uid."\n";
			if (RedisUtil::scard($redisKey)<500) {
				foreach ($data['uid'] as $key => $value) {
					RedisUtil::sadd($redisKey, $value);
				}
			}
			
			WeiboUserEdTABLE::addOne(array('uid'=>$uid,'type'=>$type));
			$time = date('Y-m-d H:i:s',time());
			echo $time.'--finish--'.$uid."\n";
			// sleep(2);
		}		
	}
	//php G:\nginx\shell\cli.php Weibo getFollowing
	//关注者--粉丝
	public static function getUserByType($type,$getType = 1){
		date_default_timezone_set("PRC");
		$redisKey = 'WEIBO_User_List';
		// $pageIndexKey = 'WEIBO_pageIndex_'.$type;
		// $pageIdKey = 'WEIBO__pageId'.$type;
		if (RedisUtil::llen($redisKey) == 0)
		{
			$uid = array(
				'pageIndex'=>'Pl_Official_HisRelation__60',
				'pageId'=>'1005052365723822',
				'uid'=>2365723822,
				);
			RedisUtil::sadd($redisKey, serialize($uid));
		}
		$page = 1;
		$status = 1;

		while (1) {

			/*if (!RedisUtil::exists($limitKey)) {
				sleep(30);
				RedisUtil::incr($limitKey);
				RedisUtil::expire($limitKey,30);
			}*/

			if ($status==1) {
				while (1) {
					$uid = RedisUtil::spop($redisKey);
					$uid = unserialize($uid);
					if (!WeiboUserEdTABLE::getOne(array('uid'=>$uid['uid'],'type'=>$type))) {
						$page = 1;
						$status = 1;
						$time = date('Y-m-d H:i:s',time());
						// echo $time.'--continue--'.$uid['uid']."\n";
						break;
					}
				}
			}
			if (!$uid)
			{
				$time = date('Y-m-d H:i:s',time());echo $time.'--list--empty';
				exit();
			}

			// $url = 'http://weibo.com/'.$uid.'/fans?current=fans';
			$url = 'http://weibo.com/p/'.$uid['pageId'].'/'.$type.'page='.$page.'#'.$uid['pageIndex'];
			$data = self::getDataByUrl($url);
			// print_r($data);exit();
			if (isset($data['uid'][0])) {
				echo count($data['uid'])."\n";
				self::saveData($data);	
			}
			//判断是否有20个
			if (isset($data['uid'][19])) {
				$page++;
				$status = 0;
			}else{
				WeiboUserEdTABLE::addOne(array('uid'=>$uid,'type'=>$type));
				$page = 1;
				$status = 1;
			}
			$time = date('Y-m-d H:i:s',time());
			// echo $url;
			// echo $time.'--savedata--'.count($data['uid']).'---'.$uid['uid']."\n";
			/*foreach ($data['uid'] as $key => $value) {
				RedisUtil::sadd($redisKey, $value);
			}*/
			// echo $time.'--finish--'.$uid['uid']."\n";
			// sleep(2);
		}		
	}
	
}
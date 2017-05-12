<?php
namespace Test;
use DB\AnchorTABLE;
use DB\Anchor1TABLE;
use DB\MsgSourceTABLE;
use DB\HashTABLE;
use DB\MsgTABLE;
use DB\ZhUserTABLE;
use DB\DBConnect;
use Utils\RedisUtil;
use Utils\CurlUtils;
use Utils\Common;
class Zhihu
{
	//php G:\nginx\shell\cli.php Zhihu getUser
	public static function getUser(){
		if (RedisUtil::llen('request_queue') == 0)
		{
			RedisUtil::sadd('request_queue', 'li-qiang-2-73');
		}
		$user_type = 'followers';
		$page = 0;
		$key = 1;
		while (1) {
			if ($key==1) {
				$uid = RedisUtil::spop('request_queue');
			}
			$url = 'https://www.zhihu.com/people/' . $uid . '/' . $user_type.'?page='.$page;
			$result = CurlUtils::zhihuCurl($url);
			preg_match_all('#<a class="UserLink-link" target="_blank" href="\/people\/(.*?)">(.*?)</a>#', $result, $u_out);
			print_r($u_out);
			$data = array();
			$i = 0;
			foreach ($u_out[1] as $key => $value) {
				if ($key%2 == 0&&!empty($value)) {
					RedisUtil::sadd('request_queue', $value);
					$data[$i]['uid'] =   $value;
					$data[$i]['img'] =   $u_out[2][$key];
					$data[$i]['name'] =   $u_out[2][$key+1];
					$i++;
				}
				# code...
			}
			if (count($u_out[1])==36) {
				$page++;
				$key = 0;
			}else{
				$page= 0;
				$key = 1;
			}
			if (!empty($data)) {
				ZhUserTABLE::addOne($data);
			}
		}
	}
}
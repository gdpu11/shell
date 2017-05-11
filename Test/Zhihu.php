<?php
namespace Test;
use DB\AnchorTABLE;
use DB\Anchor1TABLE;
use DB\MsgSourceTABLE;
use DB\HashTABLE;
use DB\MsgTABLE;
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
			RedisUtil::lpush('request_queue', 'li-qiang-2-73');
		}
		$user_type = 'followers';
		$page = 0;
		while (1) {
			$uid = RedisUtil::lpop('request_queue');
			$url = 'https://www.zhihu.com/people/' . $uid . '/' . $user_type.'?page='.$page;
			$result = CurlUtils::zhihuCurl($url);
			preg_match_all('#<a class="UserLink-link" target="_blank" href="\/people\/(.*?)">(.*?)</a>#', $result, $u_out);
			$data = array();
			$i = 0;
			foreach ($u_out[1] as $key => $value) {
				if ($key%2 == 0) {
					$data[$i]['uid'] =   $value;
					$data[$i]['img'] =   $u_out[2][$key];
					$data[$i]['name'] =   $u_out[2][$key+1];
					$i++;
				}
				# code...
			}
			print_r($data);exit();
		}
	}
}
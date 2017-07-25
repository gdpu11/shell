<?php
namespace Test;

use DB\AliTABLE;
use Utils\RedisUtil;

class Ali
{
	public static function getali(){

		$time = 5;

		$startKey = __FUNCTION__;
		if (RedisUtil::exists($startKey)) {
			// sleep(30);
			$id = RedisUtil::incr($startKey);
			// RedisUtil::expire($startKey,30);
		}else{
			$id = 36628080;
			RedisUtil::set($startKey,$id);
		}
			// $id = 36626741;
		$content =  "Content-Disposition: form-data; 
		_csrf_token=\"2a0713a746f67be3a6eaf2498d1ca757\"; 
		contentId=\"".$id."\"; 
		contentType=\"COMPANY\"; 
		sellerMemberId=\"\"\n"; 
		$opts = array('http' =>
			        array(
		            'method'  => 'POST',
		            'header' => ':authority:purchase.1688.com'."\r\n"
						.':method:POST'."\r\n"
						.':path:/favorites/add_to_favorites.htm'."\r\n"
						.':scheme:https'."\r\n"
						.'accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'."\r\n"
						// .'accept-encoding:gzip, deflate'."\r\n"
						.'accept-language:zh-CN,zh;q=0.8'."\r\n"
						.'cache-control:max-age=0'."\r\n"
						.'content-length:99'."\r\n"
						.'content-type:application/x-www-form-urlencoded'."\r\n"
						.'cookie:JSESSIONID=9L78l9qw1-dN1Ys1U4W2Dl9kRgPA-eM0kMQQ-nyOL; cna=5uTkEdtdcxACAQ4S7LvKDQbv; cookie1=BdM0yO4M2ve2%2F6GBip3tA89bd6%2BmJx%2FVgCZmCbXANS4%3D; cookie2=1c0f323d2e4bef75f7c8bfc201b45ebe; cookie17=Uoe0bUt%2F%2FrCnPw%3D%3D; uss=UR2NjczBazSjTiff9ZPW0axmpnw46Nz%2BcI3Ftxe3INNM9hGEZcXM%2Bp1a3w%3D%3D; t=a02544b73a9fd263e867b8638e17a6f4; _tb_token_=e671eefb3bb3e; sg=157; __cn_logon__=true; __cn_logon_id__=gdpu11; ali_apache_track="c_ms=1|c_mid=b2b-1600327515|c_lid=gdpu11"; ali_apache_tracktmp="c_w_signed=Y"; cn_tmp="Z28mC+GqtZ1waru4w6kjau/aRCobqNdabaWTDqYYOYjx3BJZf9BR447G9TsJcO8n+elQ6kMpFECVhh8zoVBIg69y/rSD9ppJ5s4rM6nX098I0hqsYx2ZnU+Ti/4INuzXqlfNtz4ZlhpNWFXblkqsCnJ5uOy8BKO3X2IiO+k9/wt836lf2VgRNb2bnC2ggVF3uFBb59wFm28cvRKfVupE98ifIp41Eyo1zyDQNMk6XE4="; _cn_slid_=vbQEJ44hHm; tbsnid=OM7YsQqTUl9t9vV%2FsSRU8mgfXErszIa%2BlysU6PGaSIc6sOlEpJKl9g%3D%3D; LoginUmid="8Sqcaf5aU%2FD8dlhkB5NTCPI%2FbyJwsOAvccw36LNHa49TG4BC5yy9jw%3D%3D"; userID="%2BTZ1ATiQ%2BU6K%2FhvPk1RWIUYghLzxWWn%2B%2BsGi33baZlM6sOlEpJKl9g%3D%3D"; last_mid=b2b-1600327515; unb=1600327515; __last_loginid__=gdpu11; login="kFeyVBJLQQI%3D"; _csrf_token=1500961647943; UM_distinctid=15d7855b21f3ee-094d01276b6842-1571466f-1fa400-15d7855b220586; ali_ab=14.215.172.196.1500962471158.5; _is_show_loginId_change_block_=b2b-1600327515_false; _show_force_unbind_div_=b2b-1600327515_false; _show_sys_unbind_div_=b2b-1600327515_false; _show_user_unbind_div_=b2b-1600327515_false; userIDNum=lDtWkCp106nFSJDXntqVGw%3D%3D; _nk_=rS6M4t5tu9E%3D; __rn_alert__=false; _tmp_ck_0="zbfvknZrLpv3AvsZSq9440DtEegIza%2FUVgs83bcKEyABrLvhK2czDaqTbkIIFNe%2BfpRGWAICIaQIqfUra%2Fg3qnTs8Zr%2FuwxDt8wiVpxirTJYcfD0yYrkUDgDrhXTtXObZBmDfIHpsbjVXb76PctupHHMU1MCGBja4z5ltGA3JVw8%2FbJPPOMqsrvAGypm9GNHxLRpiXlmc8ILSZ99iXZpHR9lKgvKxuwRV%2FfDa9ICbyCIUOR41sKicx5l3XF5nvs64Bd32PVJjTSihjIsQaWG8dIUZwXHBQ4uzzYgUHgrrXOGCp9CazxCR5WRJZqyjeJQgRQAX%2FZhSr770CLlDgchFKDiGeOZssJ42KUN2YfXFT4XBlM7wEtS1P0T2r1oQoXSpdrS7cOYqRlYV3UuqY%2BC8e9YrOyP3ze9ESpAD3WagOxhIhNsN12tptlgfe6cqgO6g1jmdbfVPDzvX1Lya1r7UTY5Hd774SQyOYFN6lAx13VclCrk1o9M4joLP5SPpBjMs2elkQDMr0n1O58aLVgMmSVlj0X1hkWKVuth%2FNA%2BMoU%3D"; alicnweb=lastlogonid%3Dgdpu11%7Ctouch_tb_at%3D1500968570679; isg=Avf3mwaDYrhpreY04oxnUYpYiOuBFHsf5r7Xl0mkE0Yt-BY6UY1ablherkdC'."\r\n"
						.'origin:https://purchase.1688.com'."\r\n"
						.'referer:https://purchase.1688.com/favorites/add_to_favorites.htm?spm=a26105.207177701.0.0.moD856&content_type=COMPANY&content_id='.$id."\r\n"
						.'upgrade-insecure-requests:1'."\r\n"
						.'user-agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.221 Safari/537.36 SE 2.X MetaSr 1.0'."\r\n",
						'content'=>$content 
			        )
			    );
	    $context  = stream_context_create($opts);
	    if(!$context){
	        echo "upload faild";
	        exit(1);
	    } 
		$url = 'https://purchase.1688.com/favorites/add_to_favorites.htm?spm=a26105.207177701.0.0.moD856&content_type=COMPANY&content_id='.$id;
		// $url = 'https://purchase.1688.com/favorites/add_to_favorites.htm?spm=a26105.207177701.0.0.moD856&content_type=COMPANY&content_id=88888888';
	    $result = file_get_contents($url, false, $context);
		sleep(1);
		// print_r($result);
		$result = iconv('GB2312', 'UTF-8', $result);
		preg_match_all('#\)\;\"\>(.*?)\<\/a\>\<\/dt\>#', $result, $data['name']);
		preg_match_all('#\<span class=\"label\"\>(.*?)[.\n]#', $result, $data['user']);
		preg_match_all('#此信息收藏人气\:(.*?)<\/strong><\/p>#', $result, $data['hot']);
		// preg_match_all('#<dd class=\"mode\"><span class=\"label\">(.*?)<\/dd>#', $result, $data['mode']);
		// preg_match_all('#<dd class=\"txt\"><span class=\"label\">(.*?)<\/dd>#', $result, $data['txt']);
		preg_match_all('#\<dd class=\"city\"\>(.*?)\<\/dd\>#', $result, $data['city']);
		if (!isset($data['name'][1])||empty($data['name'][1])) {
			print_r(iconv('UTF-8', 'GB2312', $result));
			$nullcount = RedisUtil::incr($startKey.'-nullcount');
			if ($nullcount > $time) {
				$id = RedisUtil::incr($startKey);
				RedisUtil::set($startKey,$id-($time+1));
				RedisUtil::set($startKey.'-nullcount',1);
				print_r($id);
				sleep(10);
			}else{
				print_r($id);
			}
			// RedisUtil::decr($startKey);
		}else{
			RedisUtil::set($startKey.'-nullcount',1);
			foreach ($data['user'][1] as $key => &$value) {
				$value = strip_tags($value);
			}
			$ali = array(
				'id'=>$id,
				'company'=>$data['name'][1][0],
				'name'=>$data['user'][1][0],
				'mode'=>$data['user'][1][1],
				'main'=>$data['user'][1][2],
				'hot'=>isset($data['hot'][1][0])?$data['hot'][1][0]:0,
				'city'=>isset($data['city'][1][0])?$data['city'][1][0]:0,
				'add_time'=>time(),
				);
			AliTABLE::addOne($ali);
			print_r($id);
			// AliTABLE::updateByWhere(array('id'=>$id),array('city'=>$ali['city']));
		}
	}

	//php G:\nginx\shell\cli.php Ali getAliCli
	//关注者--粉丝
	public static function getAliCli(){
		while (1) {
			self::getali();
		}		
	}
	
}
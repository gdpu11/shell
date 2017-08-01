<?php
namespace Test;

use DB\AliTABLE;
use Utils\RedisUtil;
use Utils\CurlUtils;

class Ali
{ 
	private static $keyword = array('中山','江门','珠海');

	private static function sendMsg(){
		$ali = AliTABLE::getOne(array('id'=>36615040));
		$status =0;
		foreach (self::$keyword as $key => $value) {
			if (strpos($ali['company'].$ali['city'], $value)!== false) {
				$status = 1;
			}
		}
		if ($status == 1) {
			self::send("<a target='_blank' href='".$ali['url']."'>{$ali['company']}</a>");
		}
	}
	public static function login(){
		$data = array(
				'TPL_username'=>'455019211@qq.com',
				'TPL_password'=>'',
				'ncoSig'=>'',
				'ncoSessionid'=>'',
				'ncoToken'=>'2f0a75fb65717d7fa30f4dd231d5a56c33d00306',
				'slideCodeShow'=>'false',
				'useMobile'=>'false',
				'lang'=>'zh_CN',
				'loginsite'=>'3',
				'newlogin'=>'0',
				'TPL_redirect_url'=>'https=://login.1688.com/member/jump.htm?target=https%3A%2F%2Flogin.1688.com%2Fmember%2FmarketSigninJump.htm%3FDone%3Dhttps%253A%252F%252Fwww.1688.com%252F',
				'from'=>'b2b',
				'fc'=>'default',
				'style'=>'b2b',
				'css_style'=>'b2b',
				'keyLogin'=>'false',
				'qrLogin'=>'true',
				'newMini'=>'false',
				'newMini2'=>'true',
				'tid'=>'',
				'loginType'=>'3',
				'minititle'=>'b2b',
				'minipara'=>'',
				'pstrong'=>'',
				'sign'=>'',
				'need_sign'=>'',
				'isIgnore'=>'',
				'full_redirect'=>'true',
				'sub_jump'=>'',
				'popid'=>'',
				'callback'=>'',
				'guf'=>'',
				'not_duplite_str'=>'',
				'need_user_id'=>'',
				'poy'=>'',
				'gvfdcname'=>'',
				'gvfdcre'=>'',
				'from_encoding'=>'',
				'sub'=>'false',
				'TPL_password_2'=>'4542aef419c0afe181a959575b2d882a5ee92184b303322286f9f47bd21c94e469cd79426cb48fa3c40ca67ce05e879a5ab0a1d9a576d52a3d24d319100bc3aa021445356935c91bb3d87095d577014cf41119e7cfe6c40937609b626672e1376fff226447faa7af36b355950c8a3710a8073bd1ab466bc03c2221510a8b4afa',
				'loginASR'=>'1',
				'loginASRSuc'=>'1',
				'allp'=>'',
				'oslanguage'=>'zh-CN',
				'sr'=>'1920*1080',
				'osVer'=>'windows|6.1',
				'naviVer'=>'chrome|56.0292487',
				'osACN'=>'Mozilla',
				'osAV'=>'5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36',
				'osPF'=>'Win32',
				'miserHardInfo'=>'',
				'appkey'=>'',
				'nickLoginLink'=>'',
				'mobileLoginLink'=>'https=://login.taobao.com/member/login.jhtml?style=b2b&css_style=b2b&from=b2b&newMini2=true&full_redirect=true&redirect_url=https=://login.1688.com/member/jump.htm?target=https%3A%2F%2Flogin.1688.com%2Fmember%2FmarketSigninJump.htm%3FDone%3Dhttps%253A%252F%252Fwww.1688.com%252F&reg=http="//member.1688.com/member/join/enterprise_join.htm?lead=https%3A%2F%2Fwww.1688.com%2F&leadUrl=https%3A%2F%2Fwww.1688.com%2F&tracelog=notracelog_s_reg&useMobile=true',
				'showAssistantLink'=>'false',
				'um_token'=>'HV01PAAZ0b871ed00e12ecbb597b0cfb0047a860',
				'ua'=>'096#LKzPBPPk1nPPTU1UPPPPPdHVY0IenRSVkUFL5j4lwDKVhjWV923d5jWLkGKTtTILYDK2tYCL1xmE5/ZVFUKLnFyLYRFV5Pj1P1MvtBFsu/kLtmzTA5diNQeIvU8bt1420PTGsRzPh6JvIMdZoNhcRRjLdKjqCkIqsse0hUw8PgeTPRPFtK/DRKECX9I1sRMft5wGuZXddk8ysHPsCUj1P1MvtBFsu7kYNmzTA5diNQeIvU8bt1420PTGsRzPh6JvIMdZDswSRRjLdKjqCkIqsse0hUw8PgeWPRPz6JUz1Ax1PP6tbPRzsRzPh6JvIMdZVAuBRRjLdKjqCkIqsse0hUw8PgeWPRPz6JUz1Vj1P1W/pIMPSUWfJplXXR1HDWpUUqlQwM6cUEDxHRm1P1vppKigMFt8qoqa+rYDLYsrUOZI1hjb8Pm1P1vppKIgHwp8qnKX+rYDLYsrUOZI1hjb8Pm1PPp7/8igUes8qmR2w51QuL8uPRPZpp/UiHuKJNQ9fq4+IG2AwXN3gK3xdJPWPRPz6JUz1sj1PP9Kq9YgARM6PR1uJih1PtdSKvq1w2rSRD55YyGCoWCtI/rf7+K+FUd+8NsAaofEDQa5XJGW8oFDVx5qUE54+MuMj+D4QlSgPr+okirjwH4AuRzP1MZe1UmcFYM1PPktbPI0QNW4qem1PArfJ8PPd8WfJpNfPrMfAOLGJpd2P5dV/JpfJ8X1d8W7wpNfsUzfAOvHARzATOsYog/kuCZBUWGIDDmVcOqvx0XZFUnRp/qJB6xQnw5wMp9eDDmVcOqvxiPEv6Ai44NQ7k/atYlQYN9gngzIkvDAYXks2HOmwx5gpudasx2uHvlitgiL8N9LJFRIFUOmwx5gp13xPFNgU+yeRf3/Qq+0aLREv6smwx57pPL4hY9fkyAeRfYqQEgu/0c528as/xVsHjRJsx5g3wVeRfx28XDiJ3jvFjHP/xHKc1IQA/5QKNyN6zJGcNQ5/xk528as/x2uBKiazL5U4xOihhP2oJ+0aLREv6smwx5grjINAwa8clheRfx284yFa0XhEcps/xNgGzd+E/5QKNyN6s823wls/4ks28askC9KfIUXsxHK7afIhb8EKN9z/4/z5bEuJCtbUuMQnYawQNymPQUtMpVExTMFFzo0qFfq78RQnYawQNymthzIHNs2OTJnC1Vm/XZR+s4jZeZKUvVv6s8NxLZs/4U8l1az/Wv9B6uUs3poKpqADh4uHlHs/4U8FPh0pY5QHjRJsWvik2HVDhzIHlHs/4M8v6bswL5UHjRJsaZikvtV1n8EKN9z/p3528as/x2uHjRJsx5g3/OIzDP27qatpXLCyUD0o0NgGzdavFfwHNav1A8EKN9z/O89y1SypXZDjKuHsYpIk2HVDhzIHlHs/4ks2HbBgYp+HjRJsx573i72PHMVB29z/4/zNPq8c0NgGzdasx2uB2yN6s82KNy78dj1LK9tpJHHg6cMR0Ng8pyeRf3/hRzP1eGEPXP9PRPzHF7h8ax1PP6tbRRzW8zPuN7JPPeXYSv7OqsU8M1wk2o1w9wG1RzPupppuHyRMNsaNDHS2cCQM22A/imz95lPV8zP1zve1PRTPRPFtK/DRK0XzmI1sRMft5wGuZXddk8ysHPsCUj1P1MvtBFsufrwWmzTA5diNQeIvU8bt1420PTGsRzPh6JvIMdZDTAzRRjLdKjqCkIqsse0hUw8PgeTPRPFtK/DRKi4jCI1sRMft5wGuZXddk8ysHPsCUj1P1MvtBFsu+rFrmzTA5diNQeIvU8bt1420PTGsRzPh6JvIMdZbAKXRRjLdKjqCkIqsse0hUw8Pge=',
				);
		$header = array(
				':authority:login.taobao.com',
				':method:POST',
				':path:/member/login.jhtml?redirectURL=https%3A%2F%2Flogin.1688.com%2Fmember%2Fjump.htm%3Ftarget%3Dhttps%253A%252F%252Flogin.1688.com%252Fmember%252FmarketSigninJump.htm%253FDone%253Dhttps%25253A%25252F%25252Fwww.1688.com%25252F',
				':scheme:https',
				'accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
				// .'accept-encoding:gzip, deflate, br',
				'accept-language:zh-CN,zh;q=0.8',
				'cache-control:max-age=0',
				'content-length:3895',
				'content-type:application/x-www-form-urlencoded',
				'cookie:_uab_collina=150123642781385372696893; t=1e064eef32b28747376accedcdaeb52c; cookie2=1b6fab0e873564b6d43be572148edc04; v=0; _tb_token_=344b36bb6537a; cna=v5bGEKuar1cCAQ7XpQPZwRIh; _umdata=70CF403AFFD707DF578913EE8A97B6E3B2B5C06AE2F77797D94AAB081F153A2D352C25766FB77F56CD43AD3E795C914C0C037CB457453EF6A40E4EC2BAA806D6; isg=AuPj1dtjvrGs3nKFCb5dbSMrcichyR0ohpQZ9BVAHcK5VAF2kKgHasHCOBMg',
				'origin:https://login.taobao.com',
				'referer:https://login.taobao.com/member/login.jhtml?redirectURL=https%3A%2F%2Flogin.1688.com%2Fmember%2Fjump.htm%3Ftarget%3Dhttps%253A%252F%252Flogin.1688.com%252Fmember%252FmarketSigninJump.htm%253FDone%253Dhttps%25253A%25252F%25252Fwww.1688.com%25252F',
				'upgrade-insecure-requests:1',
				'user-agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36.',
			    );
		$url = 'https://login.1688.com/member/jump.htm?target=https%3A%2F%2Flogin.1688.com%2Fmember%2FmarketSigninJump.htm%3FDone%3Dhttps%253A%252F%252Fwww.1688.com%252F';
		
		list($result,$header) = CurlUtils::sendPost($url , $data , $header,true);
		

		/*$context  = stream_context_create($opts);
	    if(!$context){
	        echo "upload faild";
	        exit(1);
	    } 
	    $result = file_get_contents($url, false, $context);*/
		// print_r($result);
		// print_r($header);
		// preg_match_all('#Set-Cookie: (.*?)[\r\n]#',$header,$cookie);
		// print_r($cookie);

		// $cookie = $cookie[1][0].';'.$cookie[1][1];

		RedisUtil::set('cookie',$cookie);
		// $cookie = RedisUtil::get('cookie');

		print_r($result);
		exit();

		$url = 'https://purchase.1688.com/favorites/add_to_favorites.htm?spm=a26105.207177701.0.0.moD856&content_type=COMPANY&content_id=36631936';
		$id=36631936;
		$content =  "Content-Disposition: form-data; 
		_csrf_token=>\"601b53fe14d712ca686443bbb7ce6155\"; 
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
						.'cookie:'.$cookie."\r\n"
						.'origin:https://purchase.1688.com'."\r\n"
						.'referer:https://purchase.1688.com/favorites/add_to_favorites.htm?spm=a26105.207177701.0.0.moD856&content_type=COMPANY&content_id='.$id."\r\n"
						.'upgrade-insecure-requests:1'."\r\n"
						.'user-agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.221 Safari/537.36 SE 2.X MetaSr 1.0'."\r\n",
						'content'=>$content 
			        )
			    );
	    $context  = stream_context_create($opts);
	    $result = file_get_contents($url, false, $context);
	    print_r($result);
	    exit();
	}

	public static function getali(){
		ini_set("display_errors", "Off");

		$time = 5;

		$startKey = __FUNCTION__.'a';
		if (RedisUtil::exists($startKey)) {
			// sleep(30);
			$id = RedisUtil::incr($startKey);
			// RedisUtil::expire($startKey,30);
		}else{
			$id = 36636224;
			RedisUtil::set($startKey,$id);
		}
		$content =  "Content-Disposition: form-data; 
		_csrf_token=\"569f7f77df1f1d1e963eec1d2ae62782\"; 
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
						.'cookie:JSESSIONID=9L78l9qw1-dN1Ys1U4W2Dl9kRgPA-eM0kMQQ-nyOL; ali_apache_track="c_ms=1|c_mid=b2b-1600327515|c_lid=gdpu11"; ali_apache_tracktmp="c_w_signed=Y"; UM_distinctid=15d7855b21f3ee-094d01276b6842-1571466f-1fa400-15d7855b220586; ali_beacon_id=14.18.236.187.1501053772390.920661.0; cna=5uTkEdtdcxACAQ4S7LvKDQbv; ali_ab=14.215.172.196.1500962471158.5; alicnweb=lastlogonid%3Dgdpu11%7Ctouch_tb_at%3D1501560721325; cookie1=BdM0yO4M2ve2%2F6GBip3tA89bd6%2BmJx%2FVgCZmCbXANS4%3D; cookie2=1c0f323d2e4bef75f7c8bfc201b45ebe; cookie17=Uoe0bUt%2F%2FrCnPw%3D%3D; uss=UR2NjczBazSjTiff9ZPW0axmpnw46Nz%2BcI3Ftxe3INNM9hGEZcXM%2Bp1a3w%3D%3D; t=a02544b73a9fd263e867b8638e17a6f4; _tb_token_=e671eefb3bb3e; sg=157; __cn_logon__=true; __cn_logon_id__=gdpu11; cn_tmp="Z28mC+GqtZ1waru4w6kjau/aRCobqNdabaWTDqYYOYjx3BJZf9BR447G9TsJcO8n+elQ6kMpFEDT7vhiDq5cdiV3ky1O1PvkMwlqGzjgDAWcVm9YydHC+JoB/lJcplW9A5LukQOpLm4soa/bIG1xlC0u9cxISMU8YTA1s6vS+wyDrznggSZsbu2mWs3IqvCDdiHIie23oSQMCgzMm98fjeCryMp1TgAyHek+dSosYBQ="; _cn_slid_=vbQEJ44hHm; tbsnid=OM7YsQqTUl9t9vV%2FsSRU8mgfXErszIa%2BlysU6PGaSIc6sOlEpJKl9g%3D%3D; LoginUmid="8Sqcaf5aU%2FD8dlhkB5NTCPI%2FbyJwsOAvccw36LNHa49TG4BC5yy9jw%3D%3D"; userID="%2BTZ1ATiQ%2BU6K%2FhvPk1RWIUYghLzxWWn%2B%2BsGi33baZlM6sOlEpJKl9g%3D%3D"; last_mid=b2b-1600327515; unb=1600327515; __last_loginid__=gdpu11; login="kFeyVBJLQQI%3D"; _csrf_token=1501560741677; userIDNum=lDtWkCp106nFSJDXntqVGw%3D%3D; _nk_=rS6M4t5tu9E%3D; _is_show_loginId_change_block_=b2b-1600327515_false; _show_force_unbind_div_=b2b-1600327515_false; _show_sys_unbind_div_=b2b-1600327515_false; _show_user_unbind_div_=b2b-1600327515_false; __rn_alert__=false; _tmp_ck_0="zbfvknZrLptz%2FdlVETwLh59jD%2B4fYmcugyCrVMGdrtv1ukJIXS4ev62BhXnoVaB4VrUIt2a%2BcIJGQ9lLor8r37JsZyXMMbB0%2FzFI065drtMVgTUgqAD07QWM9W%2FjU%2Fue46kg9YDnRqTITnb3T%2FdTTuLyW%2FtjC8TGYndQjQHC0%2FWJG9pkCPklfd0sPu8blLHC0Rrp4Hyqc2cTeUzLVtpXbvqlX67oVI0itZmFW%2BewN5qIH2ypbqsDRjITMIpA9pxhNUFGDG7U9TdKmhHUFtrtFhjk16TxlMckePgE0imRw2pocD3IgvwevhiCaMNoN3RpuvHn91jwtIeGdys4gNkkXLYsQdj4vNyIZLCV63qSKZtP3lBu9mQThFcwVf1w4MDl%2F7A1OQoJZR9N%2Fj7B44Z47lA71t7aQeq77rD%2B0tSFeesOgcugLlrrOORSAKZ3mMquqLVsyC1wd75G%2B0fZWimxnxh1o2DGmmzmiI6vyFq31WAIeNToAfmmea2Tyr1o7Dtq4N5CdxWyopWgy5BZwj%2Bhk0EwDDTuMYjNFSKr4BsCXfE%3D"; isg=Avj4F-_y1SSRATmpOQGQBAFNx6ZKyezq_euoajJpOzPmTZo323Cte-WvcVqZ'."\r\n"
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
		$result = iconv('gb2312','utf-8//TRANSLIT//IGNORE', $result);
		// preg_match_all('#\)\;\"\>(.*?)\<\/a\>\<\/dt\>#', $result, $data['name']);
		preg_match_all('#1688\.com\" title=\"(.*?)\" target=\"\_blank#', $result, $data['name']);
		preg_match_all('#\<span class=\"label\"\>(.*?)[.\n]#', $result, $data['user']);
		preg_match_all('#此信息收藏人气\:(.*?)<\/strong><\/p>#', $result, $data['hot']);
		preg_match_all('#https\:\/\/shop(.*?)1688\.com#', $result, $data['url']);
		// preg_match_all('#<dd class=\"mode\"><span class=\"label\">(.*?)<\/dd>#', $result, $data['mode']);
		// preg_match_all('#<dd class=\"txt\"><span class=\"label\">(.*?)<\/dd>#', $result, $data['txt']);
		preg_match_all('#\<dd class=\"city\"\>[.\n](.*?)[.\n]#', $result, $data['city']);
		if (!isset($data['name'][1][1])||empty($data['name'][1][1])) {
			// print_r(iconv('UTF-8', 'GB2312', $result));
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
				'company'=>$data['name'][1][1],
				'name'=>$data['user'][1][0],
				'mode'=>$data['user'][1][1],
				'main'=>$data['user'][1][2],
				'hot'=>isset($data['hot'][1][0])?$data['hot'][1][0]:0,
				'url'=>isset($data['url'][0][0])?$data['url'][0][0]:0,
				'city'=>isset($data['city'][1][0])?$data['city'][1][0]:0,
				'add_time'=>time(),
				);
			$status = 0;
			foreach (self::$keyword as $key => $value) {
				if (strpos($ali['company'].$ali['city'], $value)!== false) {
					$status = 1;
				}
			}
			if ($status == 1&&$ali['url']!=0) {
				self::send("<a target='_blank' href='".$ali['url']."'>{$ali['company']}</a>");
			}
			AliTABLE::addOne($ali);
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

	private static function getAccessToken(){
		if (!RedisUtil::exists('access_token')) {
			$out = CurlUtils::sendGet('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx44a3fd1cc1e43b48&secret=9746ffe36ee46fb1d5ab7725f2fe2d4f');
			$out = json_decode($out);
			if (isset($out->access_token)) {
				RedisUtil::set('access_token',$out->access_token);
				RedisUtil::expire('access_token',$out->expires_in);
				return $out->access_token;
			}
		}else{
			return RedisUtil::get('access_token');
		}
	}

	private static function getMsg($touser,$content){
		return '{
	        "touser":"'.$touser.'",
	        "msgtype":"text",
	        "text":
	        {
	             "content":"'.$content.'"
	        }
	    }';

	}
	private static function send($content = 'hello'){
		$ac_to = self::getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$ac_to;
		// $json1 = json_encode(array('touser' => 'oaOXBvmiNQX2HLEtD2YamCuhws6M', 'msgtype' => 'text', 'text' => array('content'=>$content)));
		$json1 = self::getMsg('oaOXBvmiNQX2HLEtD2YamCuhws6M',$content);
		$json2 = self::getMsg('oaOXBvs3ilOM1Qsu747wz0dRvg54',$content);
		// $json2 = json_encode(array('touser' => 'oaOXBvs3ilOM1Qsu747wz0dRvg54', 'msgtype' => 'text', 'text' => array('content'=>$content)));
		list($a[0],$a[1]) = CurlUtils::http_post_json($url, $json1);
		list($a[0],$a[1]) = CurlUtils::http_post_json($url, $json2);
		// CurlUtils::http_post_json($url, $json2);
		exit();
	}


}
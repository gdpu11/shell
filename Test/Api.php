<?php
namespace Test;
use DB\AnchorTABLE;
use DB\Anchor1TABLE;
use DB\MsgSourceTABLE;
use DB\MsgTABLE;
use DB\ZhUserEdTABLE;
use DB\AliTABLE;
use DB\DBConnect;
use Utils\CurlUtils;
use Utils\Common;
class Api extends \Test\ApiBase 
{
	/**
	 * [getMsg 获取信息列表]
	 * @return [type] [description]
	 */
	public static function getServer(){
		print_r($_SERVER);
		exit();
	}
	public static function getTable($value){
		return 
		"<tfoot>
    	<tr>
        <td>{$value['id']}</td>
        <td><a href='".$value['url']."'>{$value['company']}</a></td>
        <td>{$value['name']}</td>
        <td>{$value['hot']}</td>
        <td>{$value['city']}</td>
        <td>{$value['main']}</td>
        <td>{$value['mode']}</td>
        <td>".date('Y-m-d H:i:s',$value['add_time'])."</td>
        </tr>
	    </tfoot>";
	}

	public static function getali(){
		$id = $_GET['id'];
		$p = $_GET['p'];
		$where['id'] = array('>'=>$id);
		$where['order'] ='id asc';
		$sums = AliTABLE::getsums($where);
		$data = AliTABLE::getAlls($where,$p,1);
		$result = '';
		foreach ($data as $key => $value) {
			$id = $value['id'];
			$result .= self::getTable($value);
		}
		echo json_encode(array('id'=>$id,'data'=>$result));
		exit();
	}

	public static function getSSali(){
		$id = 36626746;
		$content =  "Content-Disposition: form-data; 
		_csrf_token=\"601b53fe14d712ca686443bbb7ce6155\"; 
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
						.'cookie:JSESSIONID=9L78l9qw1-dN1Ys1U4W2Dl9kRgPA-eM0kMQQ-nyOL; ali_apache_track="c_ms=1|c_mid=b2b-1600327515|c_lid=gdpu11"; ali_apache_tracktmp="c_w_signed=Y"; UM_distinctid=15d7855b21f3ee-094d01276b6842-1571466f-1fa400-15d7855b220586; cna=5uTkEdtdcxACAQ4S7LvKDQbv; ali_beacon_id=14.18.236.187.1501053772390.920661.0; ali_ab=14.215.172.196.1500962471158.5; cookie1=BdM0yO4M2ve2%2F6GBip3tA89bd6%2BmJx%2FVgCZmCbXANS4%3D; cookie2=1c0f323d2e4bef75f7c8bfc201b45ebe; cookie17=Uoe0bUt%2F%2FrCnPw%3D%3D; uss=UR2NjczBazSjTiff9ZPW0axmpnw46Nz%2BcI3Ftxe3INNM9hGEZcXM%2Bp1a3w%3D%3D; t=a02544b73a9fd263e867b8638e17a6f4; _tb_token_=e671eefb3bb3e; sg=157; __cn_logon__=true; __cn_logon_id__=gdpu11; cn_tmp="Z28mC+GqtZ1waru4w6kjau/aRCobqNdabaWTDqYYOYjx3BJZf9BR447G9TsJcO8n+elQ6kMpFECWzPFy5S1NvGwKymz3SxbRYQmvqDhLprMe2QKtABXWk15VL/sIe+hvS9pfk9wgSLSXJhRG1b61rNweA6TO/fCswJc9En40i4VUKMLbWzJ2gsP63kxnhra34R//HiPesK4Qyly4h+ubbjIGJ6vAhy9GwjpvGd7zeCI="; _cn_slid_=vbQEJ44hHm; tbsnid=OM7YsQqTUl9t9vV%2FsSRU8mgfXErszIa%2BlysU6PGaSIc6sOlEpJKl9g%3D%3D; LoginUmid="8Sqcaf5aU%2FD8dlhkB5NTCPI%2FbyJwsOAvccw36LNHa49TG4BC5yy9jw%3D%3D"; userID="%2BTZ1ATiQ%2BU6K%2FhvPk1RWIUYghLzxWWn%2B%2BsGi33baZlM6sOlEpJKl9g%3D%3D"; last_mid=b2b-1600327515; unb=1600327515; __last_loginid__=gdpu11; login="kFeyVBJLQQI%3D"; _csrf_token=1501153937030; _is_show_loginId_change_block_=b2b-1600327515_false; _show_force_unbind_div_=b2b-1600327515_false; _show_sys_unbind_div_=b2b-1600327515_false; _show_user_unbind_div_=b2b-1600327515_false; userIDNum=lDtWkCp106nFSJDXntqVGw%3D%3D; _nk_=rS6M4t5tu9E%3D; __rn_alert__=false; _tmp_ck_0="4k69KaJay1m7U4yBDRICqoPFiNuoQ4Q518rFtksCN91XTLRS4u9sgppHwBqI0juIzT1AgXMMdxHO454HhOJ9EDxqZ7Np0SyliD%2Bk2dk334haSeDzZHbQCx6qZ5%2F7NXceZ7DWxc9A7rHKoFLvjU4lO95fyD3asYnu1gyTlKk4pnF%2BZ82Pm2pJWEmQ8RM15zdVELjg76fGVtXnb4j%2BKwkvw7QaqxCKwZ1djfZt39t776iqu1P2zns%2FL%2BgMi0ZmeikSqNSu1S8GAh%2BpdGwfPVzvakw8eKtVcOjg6zoDszEeVbe8nxdZgV4iV39uiibIM0XHjMI45QEeG4y5%2BYuoJrsJzQs5mnU9v%2FtHX6%2F3W72N0r9lHx5HOER7USiNzUwCE35ZlzLj879KvV8hlQhsDBNTw%2FEiZ7uF%2F2bdjPsFK4nCYEJ%2Ffj8f7k7Q00YfCZdx5QQWI4OMqITE2%2FOUWA7wZ3QjCcjUXd4yubRFzkqskQZyHjLVy7EV0XBhTU1RE%2B5Q9H%2BTHEOWg92h%2FKzJVknQnm1NaoRiQyoOv9KDKNCccR3kcmo%3D"; alicnweb=lastlogonid%3Dgdpu11%7Ctouch_tb_at%3D1501153925489; isg=Ak1NmAZmKFWxxYx2JNqds1RGUmkHgjH9SHB91Y_SyORThmw4V3p-zG_UhAUE'."\r\n"
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
		preg_match_all('#\<span class=\"label\"\>(.*?)[.\r\n]#', $result, $data['user']);
		preg_match_all('#https\:\/\/shop(.*?)1688\.com#', $result, $data['url']);
		preg_match_all('#此信息收藏人气\:(.*?)<\/strong><\/p>#', $result, $data['hot']);
		// preg_match_all('#<dd class=\"mode\"><span class=\"label\">(.*?)<\/dd>#', $result, $data['mode']);
		// preg_match_all('#<dd class=\"txt\"><span class=\"label\">(.*?)<\/dd>#', $result, $data['txt']);
		preg_match_all('#\<dd class=\"city\"\>(.*?)\<\/dd\>#', $result, $data['city']);
		print_r($url);
		exit();

	}


	public static function getWeibo(){
		$url = 'http://weibo.com/6089568504/follow';
		$data = array();
		// $url = 'http://www.shell.com/Api/getServer';
		$result = CurlUtils::weiboCurl($url);
		$result = stripslashes($result);
		print_r($result);exit();
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
		$data['CONFIG'] = str_replace('new Date()', 'time()*1000', $data['CONFIG']);
		$data['CONFIG'] = str_replace('["', '\'', $data['CONFIG']);
		$data['CONFIG'] = str_replace(']; ', '\';', $data['CONFIG']);
		$data['CONFIG'] = str_replace('$webim', 'webim', $data['CONFIG']);
		eval($data['CONFIG']);
		$data['CONFIG'] = $CONFIG;
		print_r($data);
		// http://weibo.com/p/1006066089568504/follow?relate=fans&page=1#Pl_Official_HisRelation__46
		// http://weibo.com/p/1006066089568504/follow?relate=fans&page=2#Pl_Official_HisRelation__46
		exit();
	}
	public static function getMultiMsg(){
		$url = array(
			'https://www.zhihu.com/people/laruence/followers?page=1',
			'https://www.zhihu.com/people/laruence/followers?page=2',
			'https://www.zhihu.com/people/laruence/followers?page=3',
			);
		$result = CurlUtils::zhihuMultiCurl($url);
		print_r($result);
		exit();
	}
	public static function getMsg(){
		$url = 'https://www.zhihu.com/people/li-qiang-2-73/following';
		$url = 'https://www.zhihu.com/people/laruence/followers?page=2';
		// $url = 'http://www.shell.com/Api/getMsg';
		$result = CurlUtils::zhihuCurl($url);
		$result = htmlspecialchars_decode($result);
		$data = array();
		print_r($url);
		print_r($result);
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
		exit();

		return $data;
		print_r($_COOKIE);
		print_r($_SERVER);
		exit();
		$curpage = isset($_GET['p'])?$_GET['p']:1;
		$sum = MsgSourceTABLE::getSums(array());
		$pageSize = isset($_GET['s'])?$_GET['s']:20;
		$sumpage = ceil($sum/$pageSize);
		$head = array(
			'id'=>'ID',
			'msg_md5'=>'信息MD5',
			'userid'=>'用户id',
			'status'=>'次数',
			'add_time'=>'添加时间',
			);
		$result = MsgSourceTABLE::getAlls(array(),$curpage,$pageSize);
		foreach ($result as $key => &$value) {
			$value['id'] = $value['msg_md5'];
			$value['add_time'] = date('Y-m-d H:i:s',$value['add_time']);
		}
		echo json_encode(array('head'=>$head,'data'=>$result,'page'=>self::getPage($curpage,$sumpage,'/tpl/lanse/book.html')));
		exit();
	}
	/**
	 * [getMsg 获取信息列表]
	 * @return [type] [description]
	 */
	public static function delMsg(){
		$id = isset($_POST['id'])?$_POST['id']:'';
		if (empty($id)) {
			echo json_encode(array('code'=>-1,'url'=>'/tpl/lanse/book.html?'.http_build_query($_GET)));
			exit();
		}
		if (MsgSourceTABLE::delete(array('msg_md5'=>$id))) {
			echo json_encode(array('code'=>1,'url'=>'/tpl/lanse/book.html?'.http_build_query($_GET)));
		}else{
			echo json_encode(array('code'=>-1,'url'=>'/tpl/lanse/book.html?'.http_build_query($_GET)));
		}
		exit();
	}
}
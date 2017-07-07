<?php
namespace Test;
use DB\AnchorTABLE;
use DB\Anchor1TABLE;
use DB\MsgSourceTABLE;
use DB\MsgTABLE;
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
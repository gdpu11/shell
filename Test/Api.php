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
	public static function getMsg(){
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
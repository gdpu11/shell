<?php
namespace Test;
use DB\AnchorTABLE;
use DB\Anchor1TABLE;
use DB\MsgSourceTABLE;
use DB\HashTABLE;
use DB\MsgTABLE;
use DB\DBConnect;
use Utils\CurlUtils;
use Utils\Common;
class Test
{
	public static function login(){
/*		$result = array(
			'status'=>1,
			'info'=>'成功',
			'url'=>'/tpl/lanse/ndex.html',
			);
		echo json_encode($result);
		exit();*/
		header("location: /tpl/lanse/index.html"); 
		exit; 
	}
	public static function index(){
		phpinfo();exit();
		$list = AnchorTABLE::getAlls(array(),1,1);
		// for ($i=0; $i < 100000; $i++) {

		// 	$list = AnchorTABLE::addAnchor($data);
		// }
		print_r($list);
	}	
	//php G:\nginx\shell\cli.php Test hash
	public static function hash(){
		
		while (true) {
			$where['status'] = 0;
			$hashList = HashTABLE::getAlls($where,4,20);
			if (empty($hashList)) {
				exit();
			}
			foreach ($hashList as $key => $value) {
				$desc = Common::getResPrivilege($value['hash']);
				HashTABLE::updateByWhere(array('hash'=>$value['hash']),array('status_desc'=>$desc,'status'=>1));		
			}
		}
	}
	//php G:\nginx\shell\cli.php Test ugc
	public static function ugc(){
		$url = 'kgugc.5sing.kugou.com/api.php?m=Song&f=getUserUploadList&sign=si%2FWT%2BS%2FJIAX%2FhHE63u9lRw8sHK6pY4Xp9aBoXtvNazVrmmoNZWgudSvBdzRvnhcwC%2B9njyNYUVuA6vVqAv58PzbahuXQmffsr6dBLrRJhw%3D&page=1&page_size=20&token=241eeae5f7e0c1c0711385ce8a21ffc8803850bab84534613ebd75d8d2f00eec ';
        /*$params = array(
            'f'=>'getUserUploadList',
            'token'=>'241eeae5f7e0c1c0711385ce8a21ffc8803850bab84534613ebd75d8d2f00eec',
            'm'=>'Song',
            'sign'=>'si%2FWT%2BS%2FJIAX%2FhHE63u9lRw8sHK6pY4Xp9aBoXtvNazVrmmoNZWgudSvBdzRvnhcwC%2B9njyNYUVuA6vVqAv58PzbahuXQmffsr6dBLrRJhw%3D',
            );*/
        for ($i=0; $i < 1000; ++$i) { 
			$result  =  json_decode(CurlUtils::sendGet($url));
			echo $i."\n";
			if ($result->code!=0) {
				print_r($result->code);
			}
        }
	}

	//php G:\nginx\shell\cli.php Test getMsgass
	public static function getMsgass(){
		$send = '想你';
		$i = 0;
		$j = 0;
		$arrr = array(
			'a010ccb66b0a94102fe3bd54ad3cbfbc',
			'ec1135777fd4a3cb738acb0f12b91900',
			);
		while (++$i) {
			$where['order'] = array('status asc','add_time desc');
			$where['msg_md5'] = array('!='=>'0');
			$msgOne = MsgSourceTABLE::getById($where);
			$send = $msgOne['msg'];
			$sendMd5 = $msgOne['msg_md5'];
			$result = Common::getMsg($send);
			echo $sendMd5.'--';
			echo md5($result)."\n";
			if (in_array(md5($result), $arrr)||$sendMd5==md5($result)) {
				MsgSourceTABLE::updateByWhere($msgOne,array('status'=>$msgOne['status']+1));
				$msgOne = MsgSourceTABLE::getById($where);
				$send = $msgOne['msg'];
				$sendMd5 = $msgOne['msg_md5'];
				continue;
			}
			$msg = MsgTABLE::getById(array('msg_md5'=>$sendMd5));
			if (!empty($msg)) {
				$reply_msg = unserialize($msg['reply_msg']);
				if (empty($reply_msg)||!in_array($result, $reply_msg)) {
					$reply_msg[] = $result;
					$reply_msg = array_unique($reply_msg);
					$msg['reply_msg'] = serialize($reply_msg);
					$msg['last_time'] = time();
					MsgTABLE::updateByWhere(array('msg_md5'=>$sendMd5),$msg);
				}
			}else{
				$msg['msg_md5'] = 	$sendMd5;
				$msg['msg'] 	= 	$send;
				$msg['reply_msg'] = serialize(array($result));
				$msg['last_time'] = time();
				$msg['add_time'] =  time();
				MsgTABLE::addMsg($msg);
			}
			$j = self::addMsg($result,$j);
			self::addSourceMsg($result,md5($result));
			// sleep(1);
		}
		
	}


	public static function addMsg($result,$i){
		$msg = MsgTABLE::getById(array('msg_md5'=>md5($result)));
		if (empty($msg)) {
			echo $i++;
			// iconv("UTF-8", "GB2312//IGNORE", $result);
			$msg['msg_md5'] = 	md5($result);
			$msg['msg'] 	= 	$result;
			$msg['reply_msg'] = serialize(array());
			$msg['last_time'] = time();
			$msg['add_time'] =  time();
			MsgTABLE::addMsg($msg);
			return $i;
		}
		return true;
	}
	public static function addSourceMsg($msgs,$msgMd5){
		$msg = MsgSourceTABLE::getById(array('msg_md5'=>$msgMd5));
		if (empty($msg)) {
			$msg['msg_md5'] = 	$msgMd5;
			$msg['msg'] 	= 	$msgs;
			$msg['userid'] =  0;
			$msg['add_time'] =  time();
			$msg['status'] =  0;
			MsgSourceTABLE::addMsg($msg);
		}else{
			MsgSourceTABLE::updateByWhere($msg,array('status'=>$msg['status']+1));

		}
		return true;
	}

	public static function getMsg(){
		while (true) {
			$result = Common::leaveword(rand(pow(10,(8-1)), pow(10,8)-1));
			if(Common::ifHaveChstr($result)) return $result;
		}
	}
	//php G:\nginx\shell\cli.php Test getSourceMsg 30120000
	public static function getSourceMsg(){
		$flag = $GLOBALS['argv'][3];
		$i = $GLOBALS['argv'][3];
		while (++$i<($flag+10000000)) {
			$userid = $i;
			$result = Common::getWord($userid);
			if (!empty($result)) {
				echo $userid.'--';
				foreach ($result as $key => $value) {
					$msg['msg_md5'] = 	md5($value->comments[0]->content);
					$msg['msg'] 	= 	$value->comments[0]->content;
					$msg['userid'] =  $userid;
					$msg['add_time'] =  time();
					$msg['status'] =  0;
					MsgSourceTABLE::addMsg($msg);
				}
			}
		}
	}


	public static function addAnchor(){
		$data['name'] = 1;
		$data['ID_number'] = 1;
		$data['ID_photo'] = 1;
		$data['phone'] = 1;
		$data['contact'] = 1;
		$data['if_anchor'] = 1;
		$data['anchor_desc'] = 1;
		$data['if_have_show'] = 1;
		$data['show_photo'] = 1;
		$data['show_desc'] = 1;
		$data['show_url'] = 1;
		$data['if_have_fans'] = 1;
		$data['fans_photo'] = 1;
		$data['fans_desc'] = 1;
		$data['audio_url'] = 1;
		$data['show_url'] = 1;
		$data['source'] = 1;
		$data['platform'] = '';
		$data['add_time'] = time();
		$data['status'] = 0;
		$data['reason'] = "";
		$data['remark'] = "";
		$list = AnchorTABLE::addAnchor($data);
		print_r($list);
	}

}
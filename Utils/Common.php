<?php
namespace Utils;

use Utils\CurlUtils;
/**
 * curl封装
 * @author mmfei<wlfkongl@163.com>
 */
class Common
{
    /**
    * [getOpenid 获取酷狗曲库播放相关信息]
    * @author 李强
    * @date   2017-2-9
    * @param  [type] $access_token [酷狗access_token]
    * @return [type]               [openid]
    */
    public static function getResPrivilege($hash){
        $url = 'http://media.store.kugou.com/v1/get_res_privilege';
        $params = array(
            'behavior'=>'download',
            'relate'=>0,
            'appid'=>1005,
            'userid'=>0,
            'token'=>'',
            'vip'=>0,
            'clientver'=>'9999',
            'resource'=>array(
                0=>array(
                    'id'=>0,
                    'type'=>'audio',
                    'name'=>'',
                    'hash'=>$hash
                )
                )
        );
        $resultData = CurlUtils::sendPost($url,json_encode($params));
        $resultData = json_decode($resultData);
        if ($resultData->status==1) {
            if ($resultData->data[0]->privilege == 5 || $resultData->data[0]->privilege == 4 ||  $resultData->data[0]->cid == -1 ) {
                if ($resultData->data[0]->privilege == 0) {
                    return 0;//灰色版权
                }
                return '无授权歌曲';//无授权歌曲
            }else{
                return '已授权歌曲';//
            }
        }else{
            return -2;//请求出错
        }
    }
    public static function getResPrivileges($hash = array()){
        $url = 'http://media.store.kugou.com/v1/get_res_privilege';
        $params = array(
            'behavior'=>'download',
            'relate'=>0,
            'appid'=>1005,
            'userid'=>0,
            'token'=>'',
            'vip'=>0,
            'clientver'=>'9999',
            'resource'=>$hash,
        );
        $resultData = CurlUtils::sendPost($url,json_encode($params));
        $resultData = json_decode($resultData);
        if ($resultData->status==1) {
            if ($resultData->data[0]->privilege == 5 || $resultData->data[0]->privilege == 4 ||  $resultData->data[0]->cid == -1 ) {
                if ($resultData->data[0]->privilege == 0) {
                    return 0;//灰色版权
                }
                return '无授权歌曲';//无授权歌曲
            }else{
                return '已授权歌曲';//
            }
        }else{
            return -2;//请求出错
        }
    }


    public static function login($username,$password){
        //访问首页，存储cookie数据
        $cookie_file = tempnam('/tmp', 'cookie');//dirname(__FILE__) . '/cookie.txt';
        $home_url = 'https://www.zhihu.com/';
        $ch=curl_init($home_url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //SSL 报错时使用
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //SSL 报错时使用
        curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie_file); //存储提交后得到的cookie数据
        curl_exec($ch);
        curl_close($ch);

        if(stripos($username, '@') !== false) {
            $login_url = 'https://www.zhihu.com/login/email';
            $email = urlencode($username);
            $post_fields = "_xsrf=useless&email={$email}&password={$password}&rememberme=y";    //偶然发现_xsrf根本没用到~~
        } else {
            $login_url = 'https://www.zhihu.com/login/phone_num';
            $phone = $username;
            $post_fields = "_xsrf=useless&phone_num={$phone}&password={$password}&rememberme=y";    //偶然发现_xsrf根本没用到~~
        }

        $referer = 'Referer: https://www.zhihu.com/';

        //提交登录表单请求
        $ch=curl_init($login_url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_REFERER, $referer);    //来路模拟
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //SSL 报错时使用
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //SSL 报错时使用
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_fields);
        curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie_file); //存储提交后得到的cookie数据
        curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie_file); //使用提交后得到的cookie数据做参数
        $contents=curl_exec($ch);
    	curl_exec($ch);
        curl_close($ch);

        //登录成功后，获取首页数据
        $ch=curl_init($home_url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //SSL 报错时使用
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //SSL 报错时使用
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie_file); //使用提交后得到的cookie数据做参数
        $contents=curl_exec($ch);
        curl_close($ch);

        echo $contents;
        exit;
    }

    /*
　　* 函数用途：同一域名对应多个IP时，获取指定服务器的远程网页内容
　　* 创建时间：2008-12-09
　　* 创建人：张宴（blog.s135.com）
　　* 参数说明：
　　*    $ip   服务器的IP地址
　　*    $host   服务器的host名称
　　*    $url   服务器的URL地址（不含域名）
　　* 返回值：
　　*    获取到的远程网页内容
　　*    false   访问远程网页失败
    
    

　　public static function HttpVisit($ip, $host, $url){
　　    $errstr = '';
　　    $errno = '';
　　    $fp = fsockopen ($ip, 80, $errno, $errstr, 90);
　　    if (!$fp)
　　    {
　　         return false;
　　    }
　　    else
　　    {
　　        $out = "GET {$url} HTTP/1.1/r/n";
　　        $out .= "Host:{$host}/r/n";
　　        $out .= "Connection: close/r/n/r/n";
　　        fputs ($fp, $out);
　　
　　        while($line = fread($fp, 4096)){
　　           $response .= $line;
　　        }
　　        fclose( $fp );
　　
　　        //去掉Header头信息
　　        $pos = strpos($response, "/r/n/r/n");
　　        $response = substr($response, $pos + 4);
　　
　　        return $response;
　　    }
　　}*/
    
    //获取随机ip
    public static function getRankIp(){
        return '66.66.'.rand(0,16).'.'.rand(0,158);
    }

    //获取随机ip
    public static function getMsg($msg){
        $url = 'http://platform.tuling123.com/turing-smartdevice-demo/home.do';
        $params = array(
            'callbackparam'=>'',
            'cmd'=>urlencode($msg)
            );
        $result = json_decode(rtrim(ltrim(CurlUtils::sendGet($url,$params),'('),')'));
        return $result->data->text;
    }

    /**69.评论显示（新版)
     * [leaveword 留言板]
     * @param  [type] $sign [用户sign]
     * @param  [type] $pageindex [获取页]
     * @param  [type] $pagesize  [每页条数]
     * @return [type]       [留言板列表]
     */
    public static function leaveword($userid,$maxId=0,$pagesize=20){
        $url = 'http://mobileapi.5sing.kugou.com/comments/list';
        $param = array(
                'rootId' => $userid,
                'rootKind' => 'guestBook',
                'maxId' => $maxId,
                'limit' => $pagesize,
                );      
        $resultData = json_decode(CurlUtils::sendGet($url,$param));
        if ($resultData->count>0) {
            // $msg = '';
            // foreach ($resultData->data as $key => $value) {
            //     $msg .=$value->comments[0]->content;
            // }
            // mb_internal_encoding("UTF-8");
            // return $resultData->data;
            return $resultData->data[rand(0,$resultData->count-1)]->comments[0]->content;
            // return mb_substr($msg,rand(0,mb_strlen($msg)-1),rand(0,mb_strlen($msg)-1));
        } 
    }
    /**69.评论显示（新版)
     * [leaveword 留言板]
     * @param  [type] $sign [用户sign]
     * @param  [type] $pageindex [获取页]
     * @param  [type] $pagesize  [每页条数]
     * @return [type]       [留言板列表]
     */
    public static function getWord($userid,$maxId=0,$pagesize=20){
        $url = 'http://mobileapi.5sing.kugou.com/comments/list';
        $param = array(
                'rootId' => $userid,
                'rootKind' => 'guestBook',
                'maxId' => $maxId,
                'limit' => $pagesize,
                );      
        $resultData = json_decode(CurlUtils::sendGet($url,$param));
        if ($resultData->count>0) {
            // $msg = '';
            // foreach ($resultData->data as $key => $value) {
            //     $msg .=$value->comments[0]->content;
            // }
            // mb_internal_encoding("UTF-8");
            // return $resultData->data;
            return $resultData->data;
            // return mb_substr($msg,rand(0,mb_strlen($msg)-1),rand(0,mb_strlen($msg)-1));
        } 
    }

    public static function ifHaveChstr($str){
     if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $str, $match)) {
          return 1; // echo '含有汉字';
        } else {
           return 0; //echo '不含有汉字';
        }   
    }
    
}
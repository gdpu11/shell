<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
// phpinfo();
// 定义一个值为服务端根路径的常量 ROOT_PATH
if ('\\' === DIRECTORY_SEPARATOR) // Windows 环境下
    define('ROOT_PATH', strtr(__DIR__, DIRECTORY_SEPARATOR, '/') . '/');
else
    define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);

//全局设置
include(ROOT_PATH.'Config/config_dev.php');

$GLOBALS['CONFIG'] = $config;

// print_r($argv);

//自动加载类
function autoLoad($className){
	$arr = explode("\\", $className);
    include(ROOT_PATH.implode("/", $arr).'.php');
}
spl_autoload_register('autoLoad');
$arr = explode("/", $_SERVER['REDIRECT_URL']);
$arr[1] = 'Test\\'.ucfirst($arr[1]);
$arr[1]::$arr[2]();


//连接数据库
// DBConnect::getInstance($config);


// print_r(exec("/web/shell/mkdir.sh"));
// echo hello_world();


?>
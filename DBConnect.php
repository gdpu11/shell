<?php
namespace DBConnect;
/**
 * auther soulence
 * 调用数据类文件
 * modify 2015/06/12
 */
class DBConnect
{
    private static $_instance = [];

    private static function connect($params)
    {
        try { 
            $pdo = new \PDO($params['db']['dbType'].':host='.$params['db']['dbHost'].':port='.$params['db']['dbPort'].';dbname='.$params['db']['dbName'],$params['db']['dbUser'],$params['db']['dbPassword'],
            array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;",
                \PDO::ATTR_ERRMODE =>  \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC)
            ); 
        } catch (\PDOException $e) {  
            throw new \Exception($e->getMessage()); 
            //exit('连接失败:'.$e->getMessage()); 
            return false; 
        }

        if(!$pdo) { 
            throw new \Exception('\PDO CONNECT ERROR'); 
            return false; 
        } 
 
        return $pdo;
    }

    /**
     * 得到操作数据库对象
     * @param string $dbname 对应的数据库是谁
     * @param bool $attr  是否长连接
     * return false说明给定的数据库不存在
     */
    public static function getInstance($params)
    {
        $key = $params['db']['dbName'];
        if (!isset(self::$_instance[$key]) || !is_object(self::$_instance[$key]))
            self::$_instance[$key] = self::connect($params);
        return self::$_instance[$key];
    }

}
?>
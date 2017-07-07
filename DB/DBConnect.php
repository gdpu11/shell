<?php
namespace DB;
class DBConnect
{
    private static $_instance ;

    public static $FIELDS ;

    public static $TABLENAME =  'zh_user';

    public static function connect($params)
    {
        try { 
            $pdo = new \PDO($params['db']['dbType'].':host='.$params['db']['dbHost'].';port='.$params['db']['dbPort'].';dbname='.$params['db']['dbName'],$params['db']['dbUser'],$params['db']['dbPassword'],
            array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;")
            ); 
        } catch (\PDOException $e) {  
            throw new \Exception($e->getMessage()); 
            return false; 
        }

        if(!$pdo) { 
            throw new \Exception('\PDO CONNECT ERROR'); 
            return false; 
        } 
 
        return $pdo;
    }

    /**
     * 获取记录总数
     * @author liqiang<qianglee@kugou.net>
     * @date   2017-04-17     
     * @param  [type] $SCHEMA [表结构]
     * @param  [array]     $where [条件]
     * @return [type]          [description]
     */
    public static function getNowClass() {
        return __CLASS__;
    }
    /**
     * 获取记录总数
     * @author liqiang<qianglee@kugou.net>
     * @date   2017-04-17     
     * @param  [type] $SCHEMA [表结构]
     * @param  [array]     $where [条件]
     * @return [type]          [description]
     */
    public static function _SetTabeField() {
        if (isset(self::$FIELDS)&&!empty(self::$FIELDS)){
            return;
        }
        $pdo = self::getInstance();
        echo self::_getTableName(self::$TABLENAME);exit();
        $ps = $pdo->prepare('DESC '.self::_getTableName(self::$TABLENAME));  
        $ps->execute();  
        $table_fields = $ps->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($table_fields as $key => $value) {
            $value['Type'] = explode('(', $value['Type']);
            $value['Type'] = current($value['Type']);
            $fields[$value['Field']] = $value;
        }
        unset($table_fields);
        self::$FIELDS = $fields;
    }

    /**
     * 得到操作数据库对象
     * @param string $params 对应的数据库配置
     * return false说明给定的数据库不存在
     */
    public static function getInstance($params = null)
    {
        $cls = self::getNowClass();
        print_r($cls);
        exit();
        if (!isset(self::$_instance) || !is_object(self::$_instance)){
            $params = empty($params)?$GLOBALS['CONFIG']:$params;
            self::$_instance = self::connect($params);
        }
        return self::$_instance;
    }

    /**
     * 添加记录
     * @author liqiang<qianglee@kugou.net>
     * @date   2017-04-17
     * @return [type]          [description]
     */
    public static function add($data) {
        $tbName = self::_getTableName($data);
        $addFields = self::_getAddFields($data);
        $sql = "INSERT INTO `{$tbName}` {$addFields}";
        $pdo = self::getInstance();
        if (isset($_GET['showSql'])&&$_GET['showSql']=='sql') {
            echo $sql;
            print_r($data);
            exit();
        }
        $ps = $pdo->prepare($sql);
        return $ps->execute();
    }
    
    /**
     * [updateById 根据条件更新记录]
     * @author qianglee@kugou.net
     * @date   2017-04-17     
     * @param  [type] $where  [查询条件]
     * @param  array  $data   [更新的字段]
     * @param  [type] $SCHEMA [表结构]
     * @return [type]         [description]
     */
    public static function update($where,$data=array()) {
        if (empty($where)) {
            return false;
        }        
        $tbName = self::_getTableName($where);
        $update = self::_getUpdateFields($data);
        $whe = self::_getWhereFields($where);
        $sql = "UPDATE {$tbName} set {$update} {$whe}";
        $pdo = self::getInstance();
        $ps = $pdo->prepare($sql);
        if (isset($_GET['showSql'])&&$_GET['showSql']=='sql') {
            echo $sql;
            print_r($data);
            exit();
        }
        foreach ($data as $key => $value) {
            if ((!empty($value)||(is_numeric($value)&&$value==0))) {
                if (self::$FIELDS[$key]['type'] == 'varchar') {
                    $ps->bindValue($key, $value, \PDO::PARAM_STR);
                }else{
                    $ps->bindValue($key, $value);    
                }
            }   
        }
        foreach ($where as $key => $value) {
            if ((!empty($value)||(is_numeric($value)&&$value==0))) {
                if ($value['type'] == 'varchar') {
                    $ps->bindValue($key, $value['value'], \PDO::PARAM_STR);
                }else{
                    $ps->bindValue($key, $value['value']);    
                }
            }   
        }
        return $ps->execute();
    }

    /**
     * [getOne 根据条件获取一行记录]
     * @author qianglee@kugou.net
     * @date   2017-04-17     
     * @param  [type] $where  [查询条件]
     * @param  array  $fields [需要的字段]
     * @param  [type] $SCHEMA [表结构]
     * @return [type]         [一行记录]
     */
    public static function getOne($where,$fields=array()) {
        if (empty($where)) {
            return false;
        }
        $tbName = self::_getTableName($where);
        $fields = self::_getFieldsStr($fields);
        $whe = self::_getWhereFields($where);
        $sql = "SELECT {$fields} FROM {$tbName} {$whe}";
        if (isset($_GET['showSql'])&&$_GET['showSql']=='sql') {
            echo $sql;
            print_r($where);
            exit();
        }
        $pdo = self::getInstance();
        $ps = $pdo->prepare($sql);
        foreach ($where as $key => $value) {
            if ((!empty($value)||(is_numeric($value)&&$value==0))) {
                if ($value['type'] == 'varchar') {
                    $ps->bindValue($key, $value['value'], \PDO::PARAM_STR);
                }else{
                    $ps->bindValue($key, $value['value']);    
                }
            }   
        }
        $ps->execute();
        return $ps->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * 获取记录列表
     * @author qianglee@kugou.net
     * @date   2017-04-17     
     * @param  [array]     $where [条件]
     * @param  [int]     $page [分页]
     * @param  [type] $SCHEMA [表结构]
     * @return [type]          [description]
     */
    public static function getAll($where = array(),$page=1,$pageszie=20) {
        $tbName = self::_getTableName($where);
        $fields = self::_getFieldsStr(array());
        $whe = self::_getWhereFields($where);
        $page = intval($page-1)*$pageszie;
        $sql = "SELECT {$fields} FROM {$tbName} {$whe} limit {$page},{$pageszie}";
        $pdo = self::getInstance();
        if (isset($_GET['showSql'])&&$_GET['showSql']=='sql') {
            echo $sql;
            print_r($where);
            exit();
        }
        $ps = $pdo->prepare($sql);
        foreach ($where as $key => $value) {
            if ((!empty($value)||(is_numeric($value)&&$value==0))) {
                if ($value['type'] == 'varchar') {
                    $ps->bindValue($key, $value['value'], \PDO::PARAM_STR);
                }else{
                    $ps->bindValue($key, $value['value']);    
                }
            }   
        }
        $ps->execute();
        return $ps->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * 获取记录总数
     * @author liqiang<qianglee@kugou.net>
     * @date   2017-04-17     
     * @param  [type] $SCHEMA [表结构]
     * @param  [array]     $where [条件]
     * @return [type]          [description]
     */
    public static function getSums($where) {
        $tbName = self::_getTableName($where);
        $whe = self::_getWhereFields($where);
        $sql = "SELECT count(1) as sum FROM {$tbName} {$whe}";
        $pdo = self::getInstance();
        $ps = $pdo->prepare($sql);
        foreach ($where as $key => $value) {
            if ((!empty($value)||(is_numeric($value)&&$value==0))) {
                if ($value['type'] == 'varchar') {
                    $ps->bindValue($key, $value['value'], \PDO::PARAM_STR);
                }else{
                    $ps->bindValue($key, $value['value']);    
                }
            }   
        }
        $ps->execute();
        $count = $ps->fetch(\PDO::FETCH_ASSOC);
        return $count['sum'];
    }

    /**
     * 删除记录
     * @author liqiang<qianglee@kugou.net>
     * @date   2017-04-17     
     * @param  [type] $SCHEMA [表结构]
     * @param  [array]     $where [条件]
     * @return [type]          [description]
     */
    public static function delete($where) {
        if (empty($where)) {
            return false;
        }
        $tbName = self::_getTableName($where);
        $whe = self::_getWhereFields($where);
        $sql = "DELETE FROM {$tbName} {$whe}";
        $pdo = self::getInstance();
        $ps = $pdo->prepare($sql);
        if (empty($where)) {
            return false;
        }
        foreach ($where as $key => $value) {
            if ((!empty($value)||(is_numeric($value)&&$value==0))) {
                if ($value['type'] == 'varchar') {
                    $ps->bindValue($key, $value['value'], \PDO::PARAM_STR);
                }else{
                    $ps->bindValue($key, $value['value']);    
                }
            }   
        }
        return $ps->execute();
    }

    /**
     * [_getTableIndex 获取表名，有分表要配置]
     * 配置分表下标
     * @author leeqiang@kugou.net
     * @date   2017-04-17
     * @param  [type] $rule [分表规则]
     * @param  [type] $SCHEMA    [表名]
     * @return [type]               [返回下标]
     */
    public static function _getTableName($data = array()){
        $tableName = self::$TABLENAME;
        if (isset(self::$PARTITION)) {
            //数据为多维数组时，只去一个
            if (count($data) != count($data, 1)) {
                $data = current($data);
            }
            $value = $data[self::$PARTITION['field']];
            $type  = self::$PARTITION['type'];
            switch ($type) {
                case 'id':
                    // 按照id范围分表
                    $index  = floor($value / self::$PARTITION['num']);
                    $tableName =  self::$TABLENAME.self::$PARTITION['suffix'].$index;
                case 'year':
                    // 按照年份分表
                    $index = date('Y', $value);
                    $tableName =  self::$TABLENAME.self::$PARTITION['suffix'].$index;
                case 'mod':
                    // 按照id的模数分表
                    $index = ($value % self::$PARTITION['num']);
                    if (isset($_GET['showSql'])&&$_GET['showSql']=='sql') {
                        print_r(self::$TABLENAME.self::$PARTITION['suffix'].$index);
                        exit();
                    }
                    $tableName =  self::$TABLENAME.self::$PARTITION['suffix'].$index;
                case 'md5':
                    // 按照md5的序列分表
                    $index = (ord(substr(md5($value), 0, 1)) % self::$PARTITION['num']);
                    $tableName =  self::$TABLENAME.self::$PARTITION['suffix'].$index;
                default:
                    $tableName =  self::$TABLENAME;
            }
        }
        self::_SetTabeField($tableName);
    }

    /**
     * [_getWhereFields 拼接条件查询]
     * @author leeqiang@kugou.net
     * @date   2017-04-17
     * @param  [type] $where [查询条件引用，并返回pdo对应字段key跟value]
     * @return [type]         [返回字符串]
     */
    public static function _getWhereFields(&$where){
        $whe = '';
        $bindValue = array();
        $GroupBy = '';
        $OrderBy = '';
        if (!empty($where)&&is_array($where)) {
            $i = 0;
            foreach ($where as $key => $value) {
                if (isset(self::$FIELDS[$key])&&(!empty($value)||(is_numeric($value)&&$value==0))) {
                    if (is_array($value)) {
                        foreach ($value as $key1 => $value1) {
                            if (count($value)>1) {
                                if (self::$FIELDS[$key]['type']=='varchar') {

                                    $whe .= " AND {$key} IN ('".implode("','", $value)."')";//$where['status'] = array(0,1,2,3,4);IN 查询
                                }else{
                                    $whe .= " AND {$key} IN (".implode(',', $value).")";//$where['status'] = array(0,1,2,3,4);IN 查询
                                }
                                break;
                            }else{
                                if (is_numeric($key1)) {
                                    $whe .= " AND {$key} = :{$key}".$i;//$where['status'] = array(0);
                                }else{
                                    $whe .= " AND {$key} {$key1} :{$key}".$i;//$where['status'] = array('!='=>0);
                                }  
                            }
                            if (strtolower($key1)=='like') {
                                $value1 = '%'.$value1.'%';//$where['status'] = array('like'=>0);
                            }
                            $bindValue[':'.$key.$i]['type'] = self::$FIELDS[$key]['type'];
                            $bindValue[':'.$key.$i++]['value'] = $value1;
                        }
                    }else{
                        $whe .= " AND {$key} = :{$key}";//$where['status'] = 0;
                        $bindValue[':'.$key]['type'] = self::$FIELDS[$key]['type'];
                        $bindValue[':'.$key]['value'] = $value;
                    }
                }elseif (strtolower($key)=='group') {
                    if (is_array($value)) {
                        $GroupBy = ' GROUP BY '.implode(',', $value);//$where['group'] = array('status','type');
                    }else{
                        $GroupBy = ' GROUP BY '.$value;//$where['group'] = 'status';
                    }
                }elseif (strtolower($key)=='order') {
                    if (is_array($value)) {
                        $OrderBy = ' ORDER BY '.implode(',', $value);//$where['order'] = array('status desc',' id asc');
                    }else{
                        $OrderBy = ' ORDER BY '.$value;//$where['order'] = 'status desc';
                    }
                }
            }
        }
        $where = $bindValue;
        //查询条件为空时
        if (empty($whe)) {
            return $GroupBy.$OrderBy;
        }
        return 'WHERE '.ltrim($whe,' AND').$GroupBy.$OrderBy;
    }
    /**
     * [_getAddFields 拼接添加字段]
     * @author leeqiang@kugou.net
     * @date   2017-04-17
     * @param  [type] $fields [更新字段]
     * @return [type]         [返回字符串]
     */
    public static function _getAddFields($data){
        $add_key = '';
        $add_value = ' value';
        //判断是否为批量插入
        if (count($data) == count($data, 1)) {
            $add_key = self::_getAddKey($data);
            $add_value = self::_getAddValue($data);
        }else{
            $add_key = self::_getAddKey(current($data));
            foreach ($data as $key => $value) {
                $add_value .= self::_getAddValue($value).',';
            }
            $add_value =  rtrim($add_value,',');
        }
        return $add_key.$add_value;
    }

    /**
     * [_getAddKey 拼接添加字段]
     * @author leeqiang@kugou.net
     * @date   2017-04-17
     * @param  [type] $data [添加字段]
     * @return [type]         [返回字符串]
     */
    public static function _getAddKey($data){
        $fieldKey = array_keys(self::$FIELDS);
        $dataKey = array_keys($data);
        $saveKey = array_intersect($dataKey,$fieldKey);
        $str = '('.implode(',', $saveKey).')';
        return $str;
    }
    /**
     * [_getAddValue 拼接添加字段值]
     * @author leeqiang@kugou.net
     * @date   2017-04-17
     * @param  [type] $data   [字段]
     * @param  array  $SCHEMA [表结构]
     * @param  string $Prefix [前缀]
     * @return [type]         [description]
     */
    public static function _getAddValue($data){
        $fieldKey = array_keys(self::$FIELDS);
        $dataKey = array_keys($data);
        $saveKey = array_intersect($dataKey,$fieldKey);
        $saveValue = array();
        foreach ($saveKey as $key => $value) {
            $saveValue[] = "'".$data[$value]."'";
        }
        $str = '('.implode(',', $saveValue).')';
        return $str;
    }

    /**
     * [_getUpdateFields 拼接更新字段]
     * @author leeqiang@kugou.net
     * @date   2017-04-17
     * @param  [type] $fields [更新字段]
     * @return [type]         [返回字符串]
     */
    public static function _getUpdateFields($data){
        $str = '';
        foreach ($data as $key => $value) {
            if (isset(self::$FIELDS[$key])&&(!empty($value)||(is_numeric($value)&&$value==0))) {
                $str.="{$key} = :{$key},";
            }
        }
        return rtrim($str,',');
    }

    /**
     * [_getFieldsStr 获取sql查询的数据库字段]
     * @author qianglee@kugou.net
     * @date   2017-04-17
     * @param  array  $needFields [需要的字段]
     * @param  array  $SCHEMA     [表结构]
     * @return [string]           [字段名称]
     */
    public static function _getFieldsStr($needFields = array()) {

        $fieldStr = '';
        if (!empty($needFields)) {
            foreach ($needFields as $key => $value) {
                if (isset(self::$FIELDS[$key])) {
                    $fieldStr .= " {$key} as {$value},";
                }
            }
        } else {
            foreach (self::$FIELDS as $key => $value) {
                $fieldStr .= " {$key} as {$key},";
            }
        }
        return trim($fieldStr, ",").' ';
    }
}
?>
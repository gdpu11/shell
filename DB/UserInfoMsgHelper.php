<?php
/**
 * 用户映射分组模块数据处理
 * 
 * @copyright 广州酷狗科技有限公司版权所有 Copyright (c) 2004-2014 (http://www.kugou.com)
 * @author 都要（zhangdongyao@kugou.net）
 * @date 2014-5-21
 *
 */
namespace wsing\core\mysql\helpers\sing;

use wsing\core\mysql\entities\PdoOperateBase;


class UserInfoMsgHelper extends PdoOperateBase
{
	/** 
     * 数据源结构$_SCHEMA_ 
     * 当前数据源所对应的数据库表的简单定义 
     * 
     * @var array 
     */ 
    protected static $_SCHEMA_ = array(
        'DBNAME'=>'sing_test',
        'PARTITION'=> array(
          'suffix' => '_',// 要分表的后缀比如 tablename_中的'_'
          'field' => 'uid',// 要分表的字段 通常数据会根据某个字段的值按照规则进行分表,我们这里按照用户的id进行分表
          'type' => 'mod',// 分表的规则 包括id year mod md5 函数 和首字母，此处选择mod（求余）的方式
          'num' => '10',// 分表的数目 可选 实际分表的数量，在建表阶段就要确定好数量，后期不能增减表的数量
        ),
        'TABLENAME'=>'User_Info',//用户与关注用户通知
        'PRIMARY'=>'id',//主键索引
        'FIELDS'=>array(
            'UserID' => array('type'=>'int'),//
            'NickName' => array('type'=>'varchar'),//
            'Sex' => array('type'=>'int'),//
            'Question' => array('type'=>'varchar'),//
            'Answer' => array('type'=>'varchar'),//
            'Email' => array('type'=>'varchar'),//
            'Born' => array('type'=>'varchar'),//
            'Province' => array('type'=>'varchar'),//
            'City' => array('type'=>'varchar'),//
            'Memo' => array('type'=>'longtext'),//
            'Img' => array('type'=>'varchar'),//
            'QQ' => array('type'=>'varchar'),//
            'LoginCount' => array('type'=>'bigint'),//
            'CreateTime' => array('type'=>'datetime'),//
            'Flag' => array('type'=>'tinyint'),//
            'Last_Logintime' => array('type'=>'datetime'),//
            'IsSexChange' => array('type'=>'tinyint'),//
            'LastUpdateTime' => array('type'=>'datetime'),//
            'Authentication' => array('type'=>'int'),//
            'Remark' => array('type'=>'varchar'),//
            'RegClient' => array('type'=>'varchar'),//
            'EmailAuthenTime' => array('type'=>'datetime'),//
            'TotalYC' => array('type'=>'int'),//
            'TotalFC' => array('type'=>'int'),//
            'TotalBZ' => array('type'=>'int'),//
            'TotalFans' => array('type'=>'int'),//
            'TotalFriend' => array('type'=>'int'),//
            'YcRQ' => array('type'=>'bigint'),//
            'FcRQ' => array('type'=>'bigint'),//
            'LoginIP' => array('type'=>'varchar'),//
            'TagProfession' => array('type'=>'varchar'),//
            'TagLanguage' => array('type'=>'varchar'),//
            'TagYear' => array('type'=>'varchar'),//
            'TagStyle' => array('type'=>'varchar'),//
            'VipGold' => array('type'=>'int'),//
            'Recommend' => array('type'=>'int'),//
            'Signed' => array('type'=>'int'),//
            'Musician' => array('type'=>'int'),//
            'Manager' => array('type'=>'int'),//
            'MobileLogin' => array('type'=>'int'),//
            'Star' => array('type'=>'int'),//
        ),
    );

    /**
     * 添加记录，支持批量
     * @author liqiang<455019211@qq.com>
     * @date   2017-04-18
     * @return [type]          [description]
     */
    public static function addOne($data) {
        try {
            return self::addBySchema($data,self::$_SCHEMA_);
        }
        catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * [getById 根据条件获取行信息]
     * @author qianglee@kugou.net
     * @date   2016-12-15
     * @param  [type]  $id        [id]
     * @param  integer $icid      [分表id]
     * @param  array   $fields    [字段]
     * @param  [type]  $SCHEMA [表名]
     * @return [type]             [description]
     */
    public static function getOne($where = array(),$fields=array()) {
        try {
            return self::getOneBySchema($where,$fields,self::$_SCHEMA_);
        }
        catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * [updateByWhere 根据条件更新记录]
     * @author qianglee@kugou.net
     * @date   2017-04-18     
     * @param  [type] $where  [查询条件]
     * @param  array  $data   [更新的字段]
     * @return [type]         [description]
     */
    public static function updateByWhere($where = array(),$data=array()) {
        try {
            return self::updateByIdBySchema($where,$data,self::$_SCHEMA_);
        }
        catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * [getAlls 根据条件获取总记录列表]
     * @author qianglee@kugou.net
     * @date   2017-04-18     
     * @param  [type]  $where    [查询条件]
     * @param  integer $page     [页码]
     * @param  integer $pageszie [分页大小]
     * @return [type]            [description]
     */
    public static function getAlls($where = array(),$page=1,$pageszie=20,$fields = array()) {
        try {
            return self::getAllBySchema($where,$page,$pageszie,self::$_SCHEMA_,$fields);
        }
        catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * [getSums 根据条件获取总条数]
     * @author qianglee@kugou.net
     * @date   2017-04-18     
     * @param  [type] $where  [查询条件]
     * @return [type]         [description]
     */
    public static function getSums($where = array()) {
        try {
            return self::getSumsBySchema($where,self::$_SCHEMA_);
        }
        catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * [deleteById 根据条件删除]
     * @author qianglee@kugou.net
     * @date   2017-04-18
     * @param  array  $where [查询条件]
     * @return [type]        [description]
     */
    public static function delete($where = array()) {
        try {
            return self::deleteBySchema($whe,self::$_SCHEMA_);
        }
        catch (\Exception $exception) {
            return false;
        }
    }
}
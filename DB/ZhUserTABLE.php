<?php
/**
 * 用户映射分组模块数据处理
 * 
 * @copyright 广州酷狗科技有限公司版权所有 Copyright (c) 2004-2014 (http://www.kugou.com)
 * @author 都要（zhangdongyao@kugou.net）
 * @date 2014-5-21
 *
 */
namespace DB;

use DB\PdoOperateBase;


class ZhUserTABLE extends PdoOperateBase
{
  /** 
     * 数据源结构$_SCHEMA_ 
     * 当前数据源所对应的数据库表的简单定义 
     * 
     * @var array 
     */ 
    
    protected static $_SCHEMA_ = array(
        'TABLENAME'=>'zh_user',
        'PRIMARY'=>'msg_md5',//主键索引
        'FIELDS'=>array(
            'id'=>array( 'type'=>'int', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'uid'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'name'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'img'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
        ),
    );

    /**
     * 添加记录
     * @author liqiang<455019211@qq.com>
     * @date   2017-03-03
     * @return [type]          [description]
     */
    public static function addOne($data) {
        return self::addBySchema($data,self::$_SCHEMA_);
    }

    /**
     * [getById 根据条件获取一条记录]
     * @author qianglee@kugou.net
     * @date   2016-12-15
     * @param  [type]  $id        [id]
     * @param  integer $icid      [分表id]
     * @param  array   $fields    [字段]
     * @param  [type]  $SCHEMA [表名]
     * @return [type]             [description]
     */
    public static function getOne($where,$fields=array()) {
        return self::getOneBySchema($where,$fields,self::$_SCHEMA_);
    }

    /**
     * [updateByWhere 根据条件更新记录]
     * @author qianglee@kugou.net
     * @date   2017-03-03     
     * @param  [type] $where  [查询条件]
     * @param  array  $data   [更新的字段]
     * @return [type]         [description]
     */
    public static function updateByWhere($where,$data=array()) {
        return self::updateByIdBySchema($where,$data,self::$_SCHEMA_);
    }

    /**
     * [getAlls 根据条件获取总记录列表]
     * @author qianglee@kugou.net
     * @date   2017-03-03     
     * @param  [type]  $where    [查询条件]
     * @param  integer $page     [页码]
     * @param  integer $pageszie [分页大小]
     * @return [type]            [description]
     */
    public static function getAlls($where,$page=1,$pageszie=20) {
        return self::getAllBySchema($where,$page,$pageszie,self::$_SCHEMA_);
    }

    /**
     * [getSums 根据条件获取总条数]
     * @author qianglee@kugou.net
     * @date   2017-03-03     
     * @param  [type] $where  [查询条件]
     * @return [type]         [description]
     */
    public static function getSums($where = array()) {
        return self::getSumsBySchema($where,self::$_SCHEMA_);
    }

    /**
     * [deleteById 根据条件删除]
     * @author qianglee@kugou.net
     * @date   2017-03-03
     * @param  array  $where [查询条件]
     * @return [type]        [description]
     */
    public static function delete($where = array()) {
        return self::deleteBySchema($where,self::$_SCHEMA_);
    }
}
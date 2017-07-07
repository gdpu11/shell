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


class WeiboUserDetailTABLE extends PdoOperateBase
{
  /** 
     * 数据源结构$_SCHEMA_ 
     * 当前数据源所对应的数据库表的简单定义 
     * 
     * @var array 
     */ 
    
    protected static $_SCHEMA_ = array(
        'TABLENAME'=>'weibo_user_detail',
        'PRIMARY'=>'uid',//主键索引
        'FIELDS'=>array(
            'domain'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'oid'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'page_id'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'onick'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'pageIndex'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            /*'islogin'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'skin'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'background'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'scheme'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'colors_type'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'uid'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'nick'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'sex'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'watermark'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'lang'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'avatar_large'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'timeDiff'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'servertime'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'location'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'pageid'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'title_value'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'webim'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'miyou'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'brand'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'bigpipe'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'bpType'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'cssPath'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'imgPath'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'jsPath'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'mJsPath'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'mCssPath'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'version'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'g_mathematician'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'isAuto'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5
            'timeweibo'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //消息md5*/
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
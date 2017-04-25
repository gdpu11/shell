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

use DB\DBConnect;


class Anchor1TABLE extends DBConnect
{
	/** 
     * 数据源结构$_SCHEMA_ 
     * 当前数据源所对应的数据库表的简单定义 
     * 
     * @var array 
     */ 
    protected static $_SCHEMA_ = array(
        'TABLENAME'=>'a_anchor',
        'PRIMARY'=>'id',//主键索引
        'FIELDS'=>array(
            'id'=>array( 'type'=>'int', 'isAllowNull'=>true, ), //自增ID
            'name'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), //主播名字
            'ID_number'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), // 身份证号
            'ID_photo'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), // 身份证照片
            'phone'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), // 手机号
            'contact'=>array( 'type'=>'varchar', 'isAllowNull'=>true, 'defaultValue'=>'', ), // 其他联系方式，QQ或者微信
            'if_anchor'=>array( 'type'=>'varchar', 'isAllowNull'=>false, 'defaultValue'=>'', ), // 是否曾经是主播
            'anchor_desc'=>array( 'type'=>'varchar', 'isAllowNull'=>false, 'defaultValue'=>'', ), // 主播描述
            'if_have_show'=>array( 'type'=>'varchar', 'isAllowNull'=>false, 'defaultValue'=>'', ), // 是否出过专辑或者是否有自己的节目
            'show_photo'=>array( 'type'=>'varchar', 'isAllowNull'=>false, 'defaultValue'=>'', ), // 节目或者专辑截图
            'show_desc'=>array( 'type'=>'varchar', 'isAllowNull'=>false, 'defaultValue'=>'', ), // 节目描述
            'show_url'=>array( 'type'=>'varchar', 'isAllowNull'=>false, 'defaultValue'=>'', ), // 节目链接
            'audio_url'=>array( 'type'=>'varchar', 'isAllowNull'=>false, 'defaultValue'=>'', ), // 音频链接
            'if_have_fans'=>array( 'type'=>'varchar', 'isAllowNull'=>false, 'defaultValue'=>'', ), // 是否在其他平台拥有粉丝
            'fans_photo'=>array( 'type'=>'varchar', 'isAllowNull'=>false, 'defaultValue'=>'', ), // 粉丝截图
            'fans_desc'=>array( 'type'=>'varchar', 'isAllowNull'=>false, 'defaultValue'=>'', ), // 粉丝描述
            'platform'=>array( 'type'=>'int', 'isAllowNull'=>false, 'defaultValue'=>'', ), // 平台id
            'add_time'=>array( 'type'=>'int', 'isAllowNull'=>false, 'defaultValue'=>'', ), // 添加时间
            'status'=>array( 'type'=>'int', 'isAllowNull'=>false, 'defaultValue'=>'', ), // 状态，0未审核，1审核通过，2不通过
            'reason'=>array( 'type'=>'varchar', 'isAllowNull'=>false, 'defaultValue'=>'', ), //不通过理由
            'remark'=>array( 'type'=>'varchar', 'isAllowNull'=>false, 'defaultValue'=>'', ), //备注
            'source'=>array( 'type'=>'varchar', 'isAllowNull'=>false, 'defaultValue'=>'', ), //来源
        ),
    );
    /**
     * 添加记录
     * @author liqiang<455019211@qq.com>
     * @date   2017-03-03
     * @return [type]          [description]
     */
    public static function addAnchor($data) {
        return self::addBySchema($data,self::$_SCHEMA_);
    }

    /**
     * [getById 根据条件获取一首歌信息]
     * @author qianglee@kugou.net
     * @date   2016-12-15
     * @param  [type]  $id        [id]
     * @param  integer $icid      [分表id]
     * @param  array   $fields    [字段]
     * @param  [type]  $SCHEMA [表名]
     * @return [type]             [description]
     */
    public static function getById($where,$fields=array()) {
        if (is_numeric($where)) {
            $where['id'] = $where;
        }
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
        if (is_numeric($where)) {
            $where['id'] = $where;
        }
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
    public static function deleteById($where = array()) {
        if (!is_array($where)) {
            $whe['id'] = $where;
        }
        return self::deleteBySchema($whe,self::$_SCHEMA_);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijuanzhi
 * Date: 2016/12/10
 * Time: 10:28
 */

class CacheConfig
{
    public $cacheTime;
    public $cacheParams;

    /**
     * CacheConfig constructor.
     * @param int $cacheTime 缓存刷新时间间隔，单位为秒
     * @param array([k] => [v]) $cacheParams 参数数组
     */
    public function __construct($cacheTime, $cacheParams)
    {
        $this->cacheTime = $cacheTime;
        $this->cacheParams = $cacheParams;
    }
}
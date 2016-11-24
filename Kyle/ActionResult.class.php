<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-10-23
 * Time: 16:22
 */

/**
 * action返回的结果类
 * Class ActionResult
 */
abstract class ActionResult
{
    /**
     * 执行
     * @return mixed
     */
    public abstract function executeResult();
}
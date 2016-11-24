<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-11-19
 * Time: 11:09
 * 验证接口
 */

interface IValidate
{
    /**
     * 验证数据的有效性
     * @param mixed $object
     * @param mixed|null $rules
     * @return bool
     */
    public static function validate($object, $rules = null);
}
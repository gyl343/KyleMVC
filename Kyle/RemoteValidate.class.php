<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-11-19
 * Time: 11:48
 */

class RemoteValidate implements IValidate
{

    /**
     * 验证数据的有效性
     * @param mixed $object
     * @param string|array([0], [1]) $rules
     * string: 为验证函数的名字
     * array: [0]表示类名，[1]表示方法名。注：方法为静态方法。
     * 函数或方法的返回值必须为布尔值。
     *
     * @return bool
     */
    public static function validate($object, $rules = null)
    {
        // TODO: Implement validate() method.
        if (!RequiredValidate::validate($object, null))
            return true;
        if ($rules == null)
            return false;
        return call_user_func($rules, $object);
    }
}
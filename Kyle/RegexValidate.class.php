<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-11-19
 * Time: 11:44
 */

class RegexValidate implements IValidate
{

    /**
     * 验证数据的有效性
     * @param mixed $object
     * @param string $rules 正则表达式
     * @return bool
     */
    public static function validate($object, $rules = null)
    {
        // TODO: Implement validate() method.
        if (!RequiredValidate::validate($object, null))
            return true;
        if ($rules == null)
            return false;
        $res = preg_match($rules, $object);
        if ($res == 1)
            return true;
        else if ($res == 0)
            return false;
        else
            return $res;
    }
}
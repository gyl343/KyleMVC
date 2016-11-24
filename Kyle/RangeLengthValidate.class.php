<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-11-19
 * Time: 11:30
 */

class RangeLengthValidate implements IValidate
{

    /**
     * 验证数据的有效性
     * @param string $object
     * @param array([0], [1]) $rules
     * 一个数组，[0]为最小值，[1]为最大值。
     * @return bool
     */
    public static function validate($object, $rules = null)
    {
        // TODO: Implement validate() method.
        if (!RequiredValidate::validate($object, null))
            return true;
        if ($rules == null || !isset($rules[0]) || !isset($rules[1]))
            return false;
        if (mb_strlen($object, PHP_CHARSET) >= $rules[0] && mb_strlen($object, PHP_CHARSET) <= $rules[1])
            return true;
        return false;
    }
}
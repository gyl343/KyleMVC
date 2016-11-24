<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-11-19
 * Time: 11:40
 */

class RangeValidate implements IValidate
{

    /**
     * 验证数据的有效性
     * @param int|float $object
     * @param array([0], [1]) $rules
     * [0]表示最小值，[1]表示最大值
     * @return bool
     */
    public static function validate($object, $rules = null)
    {
        // TODO: Implement validate() method.
        if (!RequiredValidate::validate($object, null))
            return true;
        if ($rules == null || !isset($rules[0]) || !isset($rules[1]))
            return false;
        if ($object >= $rules[0] && $object <= $rules[1])
            return true;
        return false;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-11-19
 * Time: 11:39
 */

class MinValidate implements IValidate
{

    /**
     * 验证数据的有效性
     * @param int|float $object
     * @param int|float $rules
     * @return bool
     */
    public static function validate($object, $rules = null)
    {
        // TODO: Implement validate() method.
        if (!RequiredValidate::validate($object, null))
            return true;
        if ($rules == null)
            return false;
        if ($object >= $rules)
            return true;
        return false;
    }
}
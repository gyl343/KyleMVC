<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-11-19
 * Time: 11:23
 */

class MaxLengthValidate implements IValidate
{

    /**
     * 验证数据的有效性
     * @param string $object
     * @param int $rules 最大长度
     * @return bool
     */
    public static function validate($object, $rules = null)
    {
        // TODO: Implement validate() method.
        if ($rules == null)
            return false;
        if (mb_strlen($object, PHP_CHARSET) <= $rules)
            return true;
        return false;
    }
}
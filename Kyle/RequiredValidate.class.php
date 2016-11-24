<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-11-19
 * Time: 11:12
 */

class RequiredValidate implements IValidate
{

    /**
     * 验证数据的有效性
     * @param mixed $object
     * @param mixed|null $rules
     * 如果为'' 表示要对数据进行trim()函数操作，否则不用
     * @return bool
     */
    public static function validate($object, $rules = null)
    {
        // TODO: Implement validate() method.
        if ($rules === '')
        {
            if (trim($object) == '' || trim($object) == null)
                return false;
        }
        else
        {
            if ($object == '' || $object == null)
                return false;
        }

        return true;
    }
}
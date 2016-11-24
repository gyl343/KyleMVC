<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-11-19
 * Time: 20:44
 */

/**
 * Class EqualToValidate
 * 与其他属性比较是否相等的验证类
 */
class EqualToValidate implements IValidate
{

    /**
     * 验证数据的有效性
     * @param mixed $object
     * @param array([0], [1])|null $rules
     * 其中[0]表示要比较的属性的名称；
     * [1]表示要比较的属性的值，在配置的时候设置为任意值。
     * @return bool
     */
    public static function validate($object, $rules = null)
    {
        // TODO: Implement validate() method.
        if ($rules == null || !is_array($rules) || !isset($rules[1]) || !isset($rules[0]))
            return false;
        if ($object == $rules[1])
            return true;
        return false;
    }
}
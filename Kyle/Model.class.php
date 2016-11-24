<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-11-19
 * Time: 10:49
 * 视图模型类
 */

/**
 * Class Model 视图模型类
 */
abstract class Model
{
    /**
     * 更新视图模型
     * @param array $object
     */
    public final function updateModel($object)
    {
        $vars = get_class_vars($this->getClassName());

        foreach ($vars as $k => $v)
        {
            if (array_key_exists($k, $object))
            {
                $this->$k = $object[$k];
            }
        }
    }

    /**
     * 更新视图模型并且验证数据
     * @param array|object $object
     * @param bool $debug 调试模式
     * @return bool
     */
    public final function updateModelWithValidate($object, $debug = false)
    {
        $arr = $this->getValidateRules();
        if (count($arr) > 0)
        {
            foreach ($arr as $k => $v)
            {
                foreach ($v as $rule => $p)
                {
                    $validate_class = new ReflectionClass($rule . 'Validate');
                    $func = $validate_class->getMethod('validate');

                    //为EqualTo中的属性赋值
                    if ($rule == ValidateRules::EqualTo)
                    {
                        isset($object[$p[0]]) ? $p[1] = $object[$p[0]] : $p[1] = null;
                    }

                    if (gettype($object) == 'array' && isset($object[$k]))
                        $res = $func->invokeArgs(null, array($object[$k], $p));
                    else if (gettype($object) == 'object' && property_exists(get_class($object), $k))
                        $res = $func->invokeArgs(null, array($object->$k, $p));
                    else
                        $res = false;

                    if ($res === false)
                    {
                        if ($debug)
                        {
                            die("Validate Error: \"$k\" is not validated.");
                        }
                        return false;
                    }
                }
            }
        }
        $this->updateModel($object);
        return true;
    }

    /**
     * 返回视图模型类的名字
     * @return string
     */
    protected abstract function getClassName();

    /**
     * 返回验证的规则，在这里面配置验证的规则。
     * @return array
     *
     * 举例：
       array(
           'username' => array(
               ValidateRules::Required => '',//或 ValidateRules::Required => null
               ValidateRules::Max => 10,
               ValidateRules::Min => 5,
               ValidateRules::Range => array(5, 10),
               ValidateRules::MaxLength => 20,
               ValidateRules::MinLength => 10,
               ValidateRules::RangeLength => array(10, 20),
               ValidateRules::Regex => '/^([+-]?)\d*\.?\d+$/',
               ValidateRules::Remote => 'valiUserName',//或 ValidateRules::Remote => array('FormValidate', 'valiUserName')
               ValidateRules::EqualTo => array('username1', null)
           )
       );
     * username: 为类要验证的属性名
     *
     * 1、Required有两种情况：''表示要使用trim()函数进行去空格，null表示不使用trim()去空格。
     * 2、Max表示验证数字不得大于所给定的值。
     * 3、Min表示验证数字不得小于所给定的值。
     * 4、Range表示验证数字必须在给定的范围内。
     * 5、MaxLength表示验证输入数据的长度不得大于所给定的值。
     * 6、MinLength表示验证输入数据的长度不得小于所给定的值。
     * 7、RangeLength表示验证输入数据的长度必须在给定的范围内。
     * 8、Regex表示验证输入数据必须匹配所给定的正则表达式。
     * 9、Remote表示自定义验证，有两种情况：'valiUserName'表示调用valiUserName函数；
     *    array('FormValidate', 'valiUserName')表示调用FormValidate类的valiUserName方法。
     * 其中函数或方法只能有一个参数，即当前数据。
     * 10、EqualTo表示验证必须与另外一个给定的属性的值相等。
     *
     */
    protected abstract function getValidateRules();
}

/**
 * Class ValidateRules 验证规则类
 */
class ValidateRules
{
    //必填
    const Required = 'Required';
    //验证输入值必须小于等于规定的值
    const Max = 'Max';
    //验证输入值必须大于等于规定的值
    const Min = 'Min';
    //验证输入值必须在规定值的范围内
    const Range = 'Range';
    //验证输入值长度必须小于等于规定的值
    const MaxLength = 'MaxLength';
    //验证输入值长度必须大于等于规定的值
    const MinLength = 'MinLength';
    //验证输入值长度必须在规定值的范围内
    const RangeLength = 'RangeLength';
    //验证输入值必须满足规定的正则表达式
    const Regex = 'Regex';
    //通过调用函数或方法验证数据（ajax）
    const Remote = 'Remote';
    //验证输入值与另外一个输入值是否相等
    const EqualTo = 'EqualTo';
}
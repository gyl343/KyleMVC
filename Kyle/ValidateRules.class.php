<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijuanzhi
 * Date: 2016/12/12
 * Time: 09:03
 */

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
<?php

/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-11-19
 * Time: 15:44
 */
class User extends Model
{
    public $username;
    public $username1;
    public $password;


    /**
     * 返回视图模型类的名字
     * @return string
     */
    protected function getClassName()
    {
        // TODO: Implement getClassName() method.
        return 'User';
    }


    /**
     * 返回验证的规则，在这里面配置验证的规则。
     * @return array
     *
     * 举例：
     * array(
     * 'username' => array(
     * ValidateRules::Required => null,
     * ValidateRules::Max => 10,
     * ValidateRules::Min => 5,
     * ValidateRules::Range => array(5, 10),
     * ValidateRules::MaxLength => 20,
     * ValidateRules::MinLength => 10,
     * ValidateRules::RangeLength => array(10, 20),
     * ValidateRules::Regex => '/^([+-]?)\d*\.?\d+$/',
     * ValidateRules::Remote => 'valiUserName',//或 ValidateRules::Remote => array('FormValidate', 'valiUserName')
     * ValidateRules::EqualTo => array('username1', null)
     * )
     * );
     * username: 为类要验证的属性名
     */
    protected function getValidateRules()
    {
        // TODO: Implement getValidateRules() method.
        return array(
            'username' => array(
                ValidateRules::Required => null,
            ),
            'username1' => array(
                ValidateRules::Required => null,
                ValidateRules::EqualTo => array('username', null)
            ),
            'password' => array(
                ValidateRules::Required => null,
                ValidateRules::RangeLength => array(10, 20)
            )
        );
    }
}
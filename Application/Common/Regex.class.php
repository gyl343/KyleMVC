<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-10-25
 * Time: 18:20
 */

class Regex
{
    //浮点数
    const Float = '/^([+-]?)\d*\.\d+$/';

    //正浮点数
    const PositiveFloat = '/^[1-9]\d*.\d*|0.\d*[1-9]\d*$/';

    //正浮点数和零
    const PositiveFloatAndZero = '/^[1-9]\d*.\d*|0.\d*[1-9]\d*|0?.0+|0$/';

    //负浮点数
    const NegativeFloat = '/^-([1-9]\d*.\d*|0.\d*[1-9]\d*)$/';

    //负浮点数和零
    const NegativeFloatAndZero = '/^(-([1-9]\d*.\d*|0.\d*[1-9]\d*))|0?.0+|0$/';

    //整数
    const Int = '/^-?[1-9]\d*$/';

    //正整数和零
    const PositiveIntAndZero = '/^[1-9]\d*|0$/';

    //正整数
    const PositiveInt = '/^[1-9]\d*$/';

    //负整数
    const NegativeInt = '/^-[1-9]\d*$/';

    //负整数和零
    const NegativeIntAndZero = '/^-[1-9]\d*|0$/';

    //数字
    const Num = '/^([+-]?)\d*\.?\d+$/';

    //仅ACSII字符
    const Ascii = '/^[\x00-\xFF]+$/';

    //颜色
    const Color = '/^[a-fA-F0-9]{6}$/';

    //日期
    const Date = '/^\d{4}(\-|\/|\.)\d{1,2}\1\d{1,2}$/';

    //身份证
    const IdCard = '/^[1-9]([0-9]{14}|[0-9]{17}|[0-9]{16}(x|X))$/';

    //IP地址
    const IP = '/^(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)$/';

    //字母
    const Letter = '/^[A-Za-z]+$/';

    //小写字母
    const LowerLetter = '/^[a-z]+$/';

    //大写字母
    const UpperLetter = '/^[A-Z]+$/';

    //非空
    const NotEmpty = '/^\S+$/';

    //数字
    const FullNumber = '/^[0-9]+$/';

    //图片
    const Picture = '/(.*)\.(jpg|bmp|gif|ico|pcx|jpeg|tif|png|raw|tga)$/';

    //QQ号码
    const QQ = '/^[1-9]*[1-9][0-9]*$/';

    //压缩文件
    const RAR = '/(.*)\.(rar|zip|7zip|tgz)$/';

    //电话号码(包括验证国内区号,国际区号,分机号)
    const Tel = '/^[0-9\-()（）]{7,18}$/';

    //url
    const Url = '/^http[s]?:\/\/([\w-]+\.)+[\w-]+([\w-./?%&=]*)?$/';

    //用户名
    const UserName = '/^[a-zA-Z][a-zA-Z0-9_]{3,23}$/';

    //手机号
    const Phone = '/^0?(13|15|18|14|17)[0-9]{9}$/';

    //邮箱
    const Email = '/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/';

    //邮编
    const PostalCode = '/^[0-9]{6}$/';
}
<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijuanzhi
 * Date: 2016/12/12
 * Time: 09:05
 */

//路由匹配枚举(模拟)
class RegexRouteEnum
{
    //匹配的是{controller}
    const Controller = 1;

    //匹配的是{action}
    const Action = 2;

    //匹配的是{:参数名}
    const Variable = 3;

    //匹配字符串
    const MatchStr = 4;

    //匹配混合格式
    const Mixed = 5;
}
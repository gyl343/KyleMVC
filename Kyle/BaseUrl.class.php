<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijuanzhi
 * Date: 2016/12/22
 * Time: 11:48
 */

class BaseUrl
{
    public static function byRoute($action, $controller, $params = [], $routeName = '')
    {
        global $kyle_route;

        $params['controller'] = $controller;
        $params['action'] = $action;

        return $kyle_route->getUri($params, isNullOrEmpty($routeName) ? 'Default' : $routeName);
    }
}
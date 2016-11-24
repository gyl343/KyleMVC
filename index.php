<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-10-23
 * Time: 17:31
 * 项目的入口文件
 * 建议每一个模块使用一个入口文件，注：当前文件为主模块文件，主模块名称为Index。
 * 入口文件格式参考本文件
 * 比如后台管理模块，可以配置为define('MODULE_NAME','Admin');
 * 模块要建立在Application文件夹下，文件夹名称为配置的模块名称
 *
 * 在使用本框架时，不要使用controller和action作为参数名称，以免造成程序冲突。
 */

//禁用错误报告，上线后要取消注释
//error_reporting(0);
function all_error_handler()
{
    header('HTTP/1.1 404 Not Found');
    include '404.html';
    exit;
}
if(count(explode('index.php', $_SERVER['REQUEST_URI'])) > 1 && explode('index.php', $_SERVER['REQUEST_URI'])[1] != '')
{
    all_error_handler();
}

//引入配置文件
require_once 'config.php';

//此处定义模块名称
define('MODULE_NAME', 'Index');

//配置缓存路径
define('CACHE_DIRECTORY', WEB_ROOT . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR . 'Cache');

//入口文档名称。
define('ENTRANCE_DOC', 'index.php');

//页面上访问路径前要加上这个常量，如果通过服务器配置将index.php去掉了，将此常量修改为空字符串''。
//define('URL_HEADER', '/index.php');
define('URL_HEADER', '');

//引入php主程序
require_once 'Kyle/kyle.php';
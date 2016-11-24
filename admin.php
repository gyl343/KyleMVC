<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-10-24
 * Time: 8:51
 */

//引入配置文件
require_once 'config.php';

//此处定义模块名称
define('MODULE_NAME', 'Admin');

//入口文档名称。
define('ENTRANCE_DOC', 'admin.php');

//页面上访问路径前要加上这个常量，如果通过服务器配置将index.php去掉了，将此常量修改为空字符串''。
define('URL_HEADER', '/admin.php');

//引入php主程序
require_once 'Kyle/kyle.php';
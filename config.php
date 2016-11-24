<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-10-23
 * Time: 17:24
 * 网站的一些基本配置信息
 *
 * 说明一下文件夹的结构
 *
 * Kyle文件夹里面存放的是核心文件
 * Application里面存放的是网站的所有文件，其中
 *      Controllers文件夹里面存放控制器类文件
 *      Views文件夹存放视图文件，里面要按控制器建立文件夹、按action建立视图
 *          例如：HomeController下的IndexAction返回一个视图则视图存放目录为Views/Home/Index.php
 *      Content文件夹存放网站的一些资源，如图片、css、js等。
 *      Common文件夹存放网站要使用的一些类和函数文件
 *      Areas文件夹存放其它区域。
 *          例如现在后台要存放在Admin这个区域里，则在项目根目录建立admin.php入口文件，里面的代码参考index.php。
 *          然后这个区域里面的控制器和视图分别存放在Areas/Admin/Controllers和Areas/Admin/Views里面。
 *      Models文件夹存放视图类文件。
 *
 */
define('PHP_EXT', '.php');//php后缀
define('PHP_CLASS_EXT', '.class.php');//php类后缀
define('PHP_CHARSET', 'UTF-8');//编码
define('WEB_ROOT', $_SERVER['DOCUMENT_ROOT']);//网站根目录
//启动session
//session_start();

/**
 * 获取路由配置
 * @return array
 */
function getRouteRule()
{
    return require getPathByStr('Configs/RouteConfig.php');
}

/**
 * 获取数据库配置
 * @return array
 */
function getDbConfig()
{
    return require getPathByStr('Configs/DbConfig.php');
}

//通过模块配置路径
function getPathByModule($moduleName)
{
    if ($moduleName == 'Index') {
        return WEB_ROOT . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR;
    } else {
        return WEB_ROOT . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR . 'Areas' . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR;
    }
}

/**
 * 返回无法自动加载的文件的绝对路径
 *
 * @param string $url 格式为从网站根目录开始的字符串：aaa/bbb.php
 * @return string
 */
function getPathByStr($url)
{
    $arr = explode('/', $url);
    $str = WEB_ROOT . DIRECTORY_SEPARATOR;
    for ($i = 0; $i < count($arr); $i++)
    {
        $str .= $arr[$i] . DIRECTORY_SEPARATOR;
    }
    $str = substr($str, 0, strlen($str) - 1);
    return $str;
}

//自动加载
include getPathByStr('Configs/AutoLoad.class.php');
AutoLoad::Register();
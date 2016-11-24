<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2016-09-01
 * Time: 15:06
 */
/**
 * 自动加载类文件
 * Class AutoLoad
 */
class AutoLoad
{
    /**
     * 注册加载函数
     *
     * @return bool
     */
    public static function Register()
    {
        if (function_exists('__autoload'))
        {
            spl_autoload_register('__autoload');
        }
        return spl_autoload_register(array('AutoLoad', 'Load'));
    }

    /**
     * 自动加载类文件
     *
     * @param $class_name
     * @return bool
     */
    public static function Load($class_name)
    {
        //类已加载
        if (class_exists($class_name, false))
        {
            return false;
        }

        $path = '';
        $pathArr = array(
            'kylePath' => WEB_ROOT . DIRECTORY_SEPARATOR .'Kyle' . DIRECTORY_SEPARATOR . $class_name . PHP_CLASS_EXT,
            'commonPath' => WEB_ROOT .DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR . 'Common' . DIRECTORY_SEPARATOR . $class_name . PHP_CLASS_EXT,
            'modelPath' => WEB_ROOT .DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . $class_name . PHP_CLASS_EXT,
            'enumPath' => WEB_ROOT .DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR . 'Enums' . DIRECTORY_SEPARATOR . $class_name . PHP_CLASS_EXT
        );

        foreach ($pathArr as $k => $v)
        {
            if ((file_exists($v) === true) && (is_readable($v) === true))
            {
                $path = $v;
                break;
            }
        }

        if ($path == '')
            return false;
        require $path;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-10-23
 * Time: 17:56
 */

$kyle_route = new Route();

try
{
    $kyle_class_file_path = getPathByModule(MODULE_NAME) . 'Controllers' . DIRECTORY_SEPARATOR . $kyle_route->getController() . 'Controller' . PHP_CLASS_EXT;
    if (file_exists($kyle_class_file_path) !== true || is_readable($kyle_class_file_path) !== true)
        throw new Exception('The Controller you visit is not exist!');
    require_once $kyle_class_file_path;

    //通过反射获取类
    $_class = new ReflectionClass($kyle_route->getController() . 'Controller');
    //实例化类实体
    $controller = $_class->newInstanceArgs();

    if (!$_class->hasMethod($kyle_route->getAction()))
        throw new Exception('The Action you visit is not exist!');

    //通过反射获取当前类的方法
    $action = $_class->getMethod($kyle_route->getAction());
	//通过反射获取方法的参数
	$paramNames = $action->getParameters();
    if (count($paramNames) == 0)
    {
        //执行该方法
        $result = $action->invoke($controller);
        Layout::start();
        $result->executeResult();
        Layout::end();
        if (!isNullOrEmpty(Layout::get()))
            require Layout::get();
        else
            echo Layout::$content;
    }
    else
    {
        //获取参数数组
        $params = array();
        $getOrPost = strtoupper($_SERVER['REQUEST_METHOD']) == 'POST' ? $_POST : $_GET;
        for ($i = 0; $i < count($paramNames); $i++)
        {
            $k = $paramNames[$i];//$k是ReflectionParameter类型
            $name = $k->getName();
            if (!isset($getOrPost[$name]))
            {
                if ($k->isDefaultValueAvailable())
                {
                    $params[$i] = $k->getDefaultValue();
                }
                else
                    throw new Exception("Undefined variable '$name'!");
            }
            else
                $params[$i] = $getOrPost[$name];
        }
        //======================================
        //执行该方法
        $result = $action->invokeArgs($controller, $params);
        Layout::start();
        $result->executeResult();
        Layout::end();
        if (!isNullOrEmpty(Layout::get()))
            require Layout::get();
        else
            echo Layout::$content;
    }
}
catch (Exception $e)
{
    header('HTTP/1.1 404 Not Found');
    echo "<h1>404 NOT FOUND!</h1>";
    echo '<h2>' . $e->getMessage() . '&nbsp;[The module\'s name is ' . MODULE_NAME . ']</h2>';
}
<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-10-23
 * Time: 16:26
 */

class ViewResult extends ActionResult
{
    public function __construct($viewBag = null, $cacheConfig = null)
    {
        $this->Model = null;
        $this->ViewBag = $viewBag;
        $this->cacheConfig = $cacheConfig;
        $this->Controller = null;
        $this->Action = null;
    }
    //返回到页面的数据(object)
    private $Model;
    //返回到页面的数据包(ViewBagModel)
    private $ViewBag;

    //缓存配置
    /**
     * @var CacheConfig
     */
    private $cacheConfig;

    //要返回的视图和控制器的名字
    private $Controller;
    private $Action;
    public function setAC($action, $controller)
    {
        $this->Controller = $controller;
        $this->Action = $action;
    }

    public function setModel($model)
    {
        $this->Model = $model;
    }

    /**
     * 实行结果
     */
    public function executeResult()
    {
        // TODO: Implement executeResult() method.
		header('Content-type: text/html; charset=' . PHP_CHARSET);
        $Model = $this->Model;
        $ViewBag = $this->ViewBag;

        //引用页面
        global $kyle_route;

        $viewPath = '';

        //判断模块名称
        if (MODULE_NAME == 'Index')
        {
            $viewPath = WEB_ROOT . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . ($this->Controller == null ? $kyle_route->getController() : $this->Controller) . DIRECTORY_SEPARATOR . ($this->Action == null ? $kyle_route->getAction() : $this->Action) . PHP_EXT;
        }
        else
        {
            $viewPath = WEB_ROOT . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR
                . 'Areas' . DIRECTORY_SEPARATOR . MODULE_NAME . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . ($this->Controller == null ? $kyle_route->getController() : $this->Controller) . DIRECTORY_SEPARATOR . ($this->Action == null ? $kyle_route->getAction() : $this->Action) . PHP_EXT;
        }

        if (file_exists($viewPath) !== true || is_readable($viewPath) !== true)
            throw new Exception('The View you visit is not exist!');
        //缓存设置
        if ($this->cacheConfig == null)
        {
            require_once $viewPath;
        }
        else
        {
            $dirName = CACHE_DIRECTORY . DIRECTORY_SEPARATOR . ($this->Controller == null ? $kyle_route->getController() : $this->Controller);
            if (!is_dir($dirName))
            {
                mkdir($dirName);
            }
            $fileName = $dirName . DIRECTORY_SEPARATOR . ($this->Action == null ? $kyle_route->getAction() : $this->Action) . '_';
            foreach ((array)$this->cacheConfig->cacheParams as $k => $v)
            {
                $fileName .= "$k&$v" . '_';
            }
            $fileName = substr($fileName, 0, strlen($fileName) - 1);
            $fileName .= '.html';

            if (is_file($fileName) && time() - filemtime($fileName) <= $this->cacheConfig->cacheTime)
            {
                require_once $fileName;
            }
            else
            {
                ob_start();
                require_once $viewPath;
                file_put_contents($fileName, ob_get_contents());
            }
        }
    }
}

/**
 * 加载分布页
 *
 * @param string $action 动作
 * @param string|null $controller 控制器
 * @param array([key]=>[value]) $params 参数数组
 */
function renderPartial($action, $controller = null, $params = array())
{
    global $kyle_route;
    foreach ($params as $k => $v)
    {
        $_GET[$k] = $v;
    }

    try
    {
        if ($controller == null)
            $controller = $kyle_route->getController();
        $controller_class_path = getPathByModule(MODULE_NAME) . 'Controllers' . DIRECTORY_SEPARATOR . $controller . 'Controller' . PHP_CLASS_EXT;
        if (file_exists($controller_class_path) !== true || is_readable($controller_class_path) !== true)
            throw new Exception('The Controller you visit is not exist!');
        require_once $controller_class_path;
        $_class = new ReflectionClass($controller . 'Controller');
        $controllerClass = $_class->newInstanceArgs();
        if (!$_class->hasMethod($action))
            throw new Exception('The Action you visit is not exist!');

        $actionMethod = $_class->getMethod($action);
        $result = $actionMethod->invoke($controllerClass);
        //判断是否存在该方法。
        if (method_exists($result, 'setAC'))
            $result->setAC($action, $controller);
        $result->executeResult();
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
}
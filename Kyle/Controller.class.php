<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-10-23
 * Time: 15:55
 */

class Controller
{

    /**
     * @var ViewBagModel
     */
    public $ViewBag;

    public function __construct()
    {
        $this->ViewBag = new ViewBagModel();
    }


    /**
     * 返回视图
     *
     * @param object|null $model 视图Model
     * @param CacheConfig $cacheConfig 缓存配置
     * @return ViewResult
     */
    protected function View($model = null, $cacheConfig = null)
    {
        $result = new ViewResult($this->ViewBag, $cacheConfig);
        if ($model != null)
        {
            $result->setModel($model);
        }
        return $result;
    }

    /**
     * 返回字符串
     *
     * @param string $content 要返回的字符串
     * @return ContentResult
     */
    protected function Content($content)
    {
        $result = new ContentResult($content);
        return $result;
    }

    /**
     * 返回json数据
     * 目前只支持string和array
     *
     * @param mixed $object 要转换的数据
     * @param string $out_charset 要转换成的编码
     * @param string $in_charset 原编码
     * @return JsonResult
     */
    protected function Json($object, $out_charset = null, $in_charset = PHP_CHARSET)
    {
        $result = new JsonResult($object, $out_charset, $in_charset);
        return $result;
    }

    /**
     * 是否为POST请求
     *
     * @return bool
     */
    protected function isPost()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) == 'POST';
    }

    /**
     * 跳转到控制器
     *
     * @param string $action 动作名称
     * @param string|null $controller 控制器名称
     * @param array([key]=>[value]) $paramArr get参数数组
     * @return RedirectResult
     */
    protected function RedirectToView($action, $controller = null, $paramArr = array())
    {
        if ($controller == null)
        {
            global $kyle_route;
            $controller = $kyle_route->getController();
        }
        $paramStr = '';
        if (count($paramArr) > 0)
        {
            $paramStr = '?';
            foreach ($paramArr as $key=>$val)
            {
                $paramStr .= $key . '=' . $val . '&';
            }
            $paramStr = substr($paramStr, 0, strlen($paramStr) - 1);
        }
        $result = new RedirectResult(URL_HEADER . '/' . $controller . '/' . $action . $paramStr);
        return $result;
    }

    /**
     * 跳转到路径
     *
     * @param string $url 要跳转的路径
     * @return RedirectResult
     */
    protected function RedirectToUrl($url)
    {
        $result = new RedirectResult($url);
        return $result;
    }
}

class CacheConfig
{
    public $cacheTime;
    public $cacheParams;

    /**
     * CacheConfig constructor.
     * @param int $cacheTime 缓存刷新时间间隔，单位为秒
     * @param array([k] => [v]) $cacheParams 参数数组
     */
    public function __construct($cacheTime, $cacheParams)
    {
        $this->cacheTime = $cacheTime;
        $this->cacheParams = $cacheParams;
    }
}
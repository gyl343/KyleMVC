<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-10-23
 * Time: 17:19
 *
 *  #^(/(?P<controller>[\w]+)(/(?P<action>[\w]+)(/(?P<id>[\w]+))?)?)?#i
 * url匹配规则：
 * 只有controller,action,id(变量)进行一次或零次匹配；字符串常量和组合体进行完全匹配
 *
 * 开始找规律：拿到路由规则通过'/'进行分割形成数组。
 * 开始循环数组拼接组成正则表达式。
 * 循环判断每一项为哪种类型（{controller},{action},{:id},string,组合体）
 * 【1】若为{controller},{action},{:id}中的一种则拼接为/(?P<controller>[\w]+),其他两种只用替换其中的'controller'即可。
 * 【2】若为string，则拼接为/string;
 * 【3】若为组合体例如:controller_action或者:y_:m_:d，则拼接为(?P<controller>[\w]+)_(?P<action>[\w]+)或(?P<y>[\w]+)_(?P<m>[\w]+)_(?P<d>[\w]+)。组合体顾名思义就由有【1】和【2】以及一些字符所组成。
 *
 * 最后匹配的【1】中的数据个数如果为n，则再拼接n个)?
 * 拼接前缀和后缀分别为：#^和#i
 */
require_once 'CommonFunc.php';
/**
 *
 * Class Route
 */
class Route
{
    //路由规则数组
    /**
     * @var array
     */
    private $rules;
    //控制器名称
    private $controller;
    public function getController()
    {
        return $this->controller;
    }
    //动作名称
    private $action;
    public function getAction()
    {
        return $this->action;
    }
    //匹配到的路由的索引
    private $currentRouteIndex;
    public function getCurrentRouteIndex()
    {
        return $this->currentRouteIndex;
    }
    //匹配到的参数数组
    private $params = array();
    //用户请求路径
    private $uri;

    public function __construct()
    {
        $isPost = $_SERVER['REQUEST_METHOD'] == 'POST';
        $this->rules = getRouteRule();
        $this->uri = $this->getRequestUri();

        //匹配路由
        $arr = $this->matchRoute();

        foreach ($arr as $key => $value)
        {
            if ($key == 'controller')
            {
                $this->controller = ucfirst(strtolower($value));
                $this->params['controller'] = $this->controller;
            }
            else if ($key == 'action')
            {
                $this->action = ucfirst(strtolower($value));
                $this->params['action'] = $this->action;
            }
            else if (!is_int($key))
            {
                if($isPost)
                {
                    $_POST[$key] = $value;
                }
                else
                {
                    $_GET[$key] = $value;
                }
            }
        }

        if ($isPost)
        {
            foreach ($_POST as $k => $v)
            {
                $this->params[$k] = $v;
            }
        }
        else
        {
            foreach ($_GET as $k => $v)
            {
                $this->params[$k] = $v;
            }
        }

        //如果没有完全匹配到，调用默认设置
        if (!array_key_exists('controller', $arr))
        {
            $this->controller = $this->rules[$this->currentRouteIndex]['defaults']['controller'];
            $this->params['controller'] = $this->controller;
        }

        if (!array_key_exists('action', $arr))
        {
            $this->action = $this->rules[$this->currentRouteIndex]['defaults']['action'];
            $this->params['action'] = $this->action;
        }
    }


    /**
     * 返回处理后的用户请求的路径
     *
     * @return string 返回处理后的用户请求的路径
     */
    private function getRequestUri()
    {
        if (stristr($_SERVER["REQUEST_URI"], ENTRANCE_DOC))//包含模块名称的路径
        {
            return explode('?', substr(request_uri(), strlen('/' . ENTRANCE_DOC . '/')))[0];
        }
        else//web服务器处理后的不包含模块名称的路径
        {
            return explode('?', substr(request_uri(), strlen('/')))[0];
        }
    }

    /**
     * 匹配路由
     */
    private function matchRoute()
    {
        $matchArr = array();//最后匹配的数组
        $this->currentRouteIndex = 'Default';
        //循环路由数组开始匹配,得到正则表达式
        foreach($this->rules as $ckey => $cval)
        {
            $routeArrByStr = explode('/', $cval['rule']);
            $regexStr = '#^';
            if($ckey == 'Default')
            {

                //========获取正则表达式开始============
                $k = 0;//对匹配【1】的进行计数
                for ($m = 0; $m < count($routeArrByStr); $m++)
                {
                    $match = $this->regexConfig($routeArrByStr[$m]);
                    if ($match[0] == RegexRouteEnum::Controller)
                    {
                        $regexStr .= '(/(?P<controller>[\w]+)';
                        $k++;
                    }
                    else if ($match[0] == RegexRouteEnum::Action)
                    {
                        $regexStr .= '(/(?P<action>[\w]+)';
                        $k++;
                    }
                    else if ($match[0] == RegexRouteEnum::Variable)
                    {
                        $regexStr .= '(/(?P<' . $match[1]['variable'] . '>[\w]+)';
                        $k++;
                    }
                    else if ($match[0] == RegexRouteEnum::MatchStr)
                    {
                        $regexStr .= '/' . strtolower($routeArrByStr[$m]);
                    }
                    else
                    {
                        /*
                         * 1、去掉字符串中的':'。
                         * 2、再将字符串中的'{'和'}'分别替换成'/(?P<'和'>[\w]+)'。
                         * 3、再将替换好的字符串拼接起来。
                         * */
                        $str = str_replace(':', '', $routeArrByStr[$m]);
                        $str = str_replace('.', '\.', $str);
                        $str = str_replace('{', '(?P<', $str);
                        $str = str_replace('}', '>[\w]+)', $str);
                        $regexStr .= '/' . $str;
                    }
                }
                for ($j = 0; $j < $k; $j++)
                {
                    $regexStr .= ')?';
                }
                $regexStr .= '#i';

                //开始匹配路由
                preg_match($regexStr, '/' . $this->uri, $matchArr);
                if (count($matchArr) > 0 && $matchArr[0] != null)
                {
                    //$this->DQueryStrings = isset($cval['dQueryStrings']) ? $cval['dQueryStrings'] : null;
                    $this->currentRouteIndex = $ckey;
                    break;
                }
                else
                {
                    $matchArr = array();
                }
            }
            else
            {

                for ($m = 0; $m < count($routeArrByStr); $m++)
                {
                    $match = $this->regexConfig($routeArrByStr[$m]);
                    if ($match[0] == RegexRouteEnum::Controller)
                    {
                        $regexStr .= '/(?P<controller>[\w]+)';
                    }
                    else if ($match[0] == RegexRouteEnum::Action)
                    {
                        $regexStr .= '/(?P<action>[\w]+)';
                    }
                    else if ($match[0] == RegexRouteEnum::Variable)
                    {
                        $regexStr .= '/(?P<' . $match[1]['variable'] . '>[\w]+)';
                    }
                    else if ($match[0] == RegexRouteEnum::MatchStr)
                    {
                        $regexStr .= '/' . strtolower($routeArrByStr[$m]);
                    }
                    else
                    {
                        /*
                         * 1、去掉字符串中的':'。
                         * 2、再将字符串中的'{'和'}'分别替换成'/(?P<'和'>[\w]+)'。
                         * 3、再将替换好的字符串拼接起来。
                         * */
                        $str = str_replace(':', '', $routeArrByStr[$m]);
                        $str = str_replace('.', '\.', $str);
                        $str = str_replace('{', '(?P<', $str);
                        $str = str_replace('}', '>[\w]+)', $str);
                        $regexStr .= '/' . $str;
                    }
                }
                $regexStr .= '$#i';

                //开始匹配路由
                preg_match($regexStr, '/' . $this->uri, $matchArr);

                if (count($matchArr) > 0 && $matchArr[0] != null)
                {
                    //$this->DQueryStrings = isset($cval['dQueryStrings']) ? $cval['dQueryStrings'] : null;
                    $this->currentRouteIndex = $ckey;
                    break;
                }
                else
                {
                    $matchArr = array();
                }
            }
        }
        return $matchArr;
    }

    /**
     * 主要检查路由规则中是否会匹配'{controller}','{action}','{:参数名}'，string，混合格式
     *
     * @param string $routeStr 当前匹配的路由规则分割后的字符串
     *
     * @return array 路由匹配枚举值，若匹配{:id}则返回匹配数组
     */
    private function regexConfig($routeStr)
    {
        $arr = array();
        if ($routeStr == '{controller}')
        {
            return array(RegexRouteEnum::Controller, array());
        }
        else if ($routeStr == '{action}')
        {
            return array(RegexRouteEnum::Action, array());
        }
        else if (preg_match('#^\{\:(?P<variable>[\w]+)\}$#i', $routeStr, $arr))//匹配参数{:id}
        {
            return array(RegexRouteEnum::Variable, $arr);
        }
        else if (preg_match('#^[\w]+$#i', $routeStr))//匹配字符串string
        {
            return array(RegexRouteEnum::MatchStr, array());
        }
        else//匹配混合格式
        {
            return array(RegexRouteEnum::Mixed, array());
        }
    }

    /**
     * 根据路由返回链接
     *
     * @param array([k]=>[v]) $params
     * 例如：array('controller'=>'Home', 'action'=>'Index', 'id'=>'1')
     * 其中'controller'为控制器，'action'为动作，其他表示参数
     *
     * @param string|null $routeName
     *
     * 说明：
     * 1、如果不传第二个参数，默认使用当前匹配的路由，
     * 这时参数$param可以不传controller和action两个参数。
     *
     * 2、如果传了第二个参数，则参数$param必须传递controller和action两个参数。
     *
     * @return string
     */
    public function getUri($params, $routeName = null)
    {
        $uri = '';
        $p = array();
        $rule = '';//路由规则
        $defaults = array();//路由默认参数
        //以当前匹配到的路由为准
        if (!array_key_exists($routeName, $this->rules))
        {
            $rule = $this->rules[$this->currentRouteIndex]['rule'];
            $defaults = $this->rules[$this->currentRouteIndex]['defaults'];
            foreach ($this->params as $k => $v)
            {
                $p[$k] = $v;
            }

            foreach ($params as $k => $v)
            {
                $p[$k] = $v;
            }
        }
        else//以传入的路由名称找到的路由为准
        {
            $rule = $this->rules[$routeName]['rule'];
            $defaults = $this->rules[$routeName]['defaults'];
            $p = $params;
        }
        $routeArr = explode('/', $rule);
        for ($i = 0; $i < count($routeArr); $i++)
        {
            $match = $this->regexConfig($routeArr[$i]);
            if ($match[0] == RegexRouteEnum::Controller)
            {
                if (!isset($p['controller']))
                    $uri .= '/' . $defaults['controller'];
                else
                {
                    $uri .= '/' . $p['controller'];
                    unset($p['controller']);
                }
            }
            else if ($match[0] == RegexRouteEnum::Action)
            {
                if (!isset($p['action']))
                    $uri .= '/' . $defaults['action'];
                else
                {
                    $uri .= '/' . $p['action'];
                    unset($p['action']);
                }
            }
            else if ($match[0] == RegexRouteEnum::Variable)
            {
                if (!isset($p[$match[1]['variable']]))
                    $uri .= '/' . '0';
                else
                {
                    $uri .= '/' . $p[$match[1]['variable']];
                    unset($p[$match[1]['variable']]);
                }
            }
            else if ($match[0] == RegexRouteEnum::MatchStr)
            {
                $uri .= '/' . strtolower($routeArr[$i]);
            }
            else
            {
                /*
                 * 1、去掉字符串中的':'。
                 * 2、再将字符串中的'{'和'}'分别替换成'/(?P<'和'>[\w]+)'。
                 * 3、再将替换好的字符串拼接起来。
                 * */
                $mixStr = $routeArr[$i];
                $str = str_replace('{:', '{:(?P<', $routeArr[$i]);
                $str = str_replace('.', '\.', $str);
                $str = str_replace('{controller}', isset($p['controller']) ? $p['controller'] : '', $str);
                $str = str_replace('{action}', isset($p['action']) ? $p['action'] : '', $str);
                $str = str_replace('}', '>[\w]+)}', $str);
                $str = '#^' . $str . '#i';
                $mixMatch = array();

                if (preg_match($str, $routeArr[$i], $mixMatch))
                {
                    foreach ($mixMatch as $k => $v)
                    {
                        if (!is_int($k))
                        {
                            if (isset($p[$v]))
                            {
                                $mixStr = str_replace('{:' . $k . '}', $p[$v], $mixStr);
                                unset($p[$v]);
                            }
                            else
                            {
                                $mixStr = str_replace('{:' . $k . '}', '0', $mixStr);
                            }
                        }
                    }
                }
                $uri .= '/' . $mixStr;
            }
        }
        $str = '?';
//        foreach ($p as $k=>$v)
//        {
//            if ($k != 'controller' && $k != 'action')
//            {
//                $str .= $k . '=' . $v . '&';
//            }
//        }
        foreach ($this->params as $k => $v)
        {
            if ($k != 'controller' && $k != 'action' && !isset($params[$k]))
            {
                $str .= $k . '=' . $v . '&';
            }
        }
        $str = substr($str, 0, strlen($str) - 1);
        return $uri . $str;
    }
}

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
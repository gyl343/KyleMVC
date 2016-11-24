<?php
//error_reporting(0);
//require_once 'Pager.class.php';

/**
 * PagerViewModel short summary.
 *
 * PagerViewModel 分页展示类.
 *
 * @version 1.0
 * @author gyl343
 *
 */
class PagerViewModel
{
    //共x条(int)
    public $AllCount;
    //每页x条(int)
    public $PageSize;
    //第几页(int)
    public $NowPage;

    //每页多少条(int)
    public $PageNumSize;

    public $Min;
    public $Max;


    public $Url;

    //分页参数
    public $PageParam;

    //共几页(int)
    public $MaxPage;

    //是否使用路由
    private $IsRoute;
    //要传给路由的参数数组
    private $RouteParams;

    public function IsFirst()
    {
        return $this->NowPage == 1;
    }

    public function IsLast()
    {
        return $this->NowPage == $this->MaxPage;
    }

    /**
     * 构造函数
     * $url(询问是否可以获取地址和请求参数)
     *
     * @param int $allCount 总共有多少条数据
     * @param int $pageSize 每页有多少条数据，默认10条
     * @param int $pageNumSize 分页条显示分页个数，默认5条
     * @param string $pageParam 分页参数，默认为page
     * @param string $url 请求路径
     * @param bool $isRoute 是否使用路由
     *          false 表示根据自己传递的url参数获取路径；
     *          true 表示根据路由获取路径。
     * @param array([k]=>[v]) $routeParams 要传给路由的参数数组
     */
    public function __construct($allCount, $pageSize = 10, $pageNumSize = 5, $pageParam = 'page', $url, $isRoute = false, $routeParams = array())
    {
        $this->PageParam = $pageParam;
        $this->Url = $url;

        if (isset($_GET[$pageParam])) {
            $this->NowPage = $_GET[$pageParam];
        } else if (isset($_POST[$pageParam])) {
            $this->NowPage = $_POST[$pageParam];
        } else {
            $this->NowPage = 1;
        }

        $this->AllCount = $allCount;
        $this->PageSize = $pageSize <= 0 ? 10 : $pageSize;
        $this->MaxPage = intval($this->AllCount / $pageSize);
        if ($this->AllCount % $pageSize != 0) $this->MaxPage++;

        $this->NowPage = min(array($this->MaxPage, $this->NowPage));

        $this->NowPage = max(array($this->NowPage, 1));
        $this->PageNumSize = $pageNumSize;

        $minAndmax = $this->GetPageNum($this->AllCount, $this->PageSize, $this->PageNumSize, $this->NowPage);

        $this->Min = $minAndmax[0];
        $this->Max = $minAndmax[1];

        $this->IsRoute = $isRoute;
        $this->RouteParams = $routeParams;
    }

    /**
     *    获取分页链接的请求路径
     *
     * @param int $page 页码
     *
     *
     * @return string 返回请求路径
     * */
    public function GetHref($page)
    {
        if ($page <= 1) {
            $page = 1;
        }

        if ($page >= $this->MaxPage) {
            $page = $this->MaxPage;
        }

        $str = '';
        if ($this->IsRoute)
        {
            global $kyle_route;
            $this->RouteParams['page'] = $page;
            $str = $kyle_route->getUri($this->RouteParams, 'Page');
            return $str;
        }
        else
        {
            $str = $this->Url;
        }

        if (count($_GET) > 0)
        {
            $str .= '?';

            if (isset($_GET[$this->PageParam])) {
                foreach ($_GET as $key => $value) {
                    if ($key == $this->PageParam) {
                        $str .= $key . '=' . $page . '&';
                    } else {
                        $str .= $key . '=' . $value . '&';
                    }
                }
            } else {
                foreach ($_GET as $key => $value) {
                    $str .= $key . '=' . $value . '&';
                }
                $str .= $this->PageParam . '=' . $page . '&';
            }

            $str = substr($str, 0, strlen($str) - 1);
            return $str;
        }
        else if (count($_POST) > 0)
        {
            if (count($_POST) > 1)
            {
                $str .= '?';
            }
            else if (count($_POST) == 1 && $this->IsRoute)
            {
                $str .= '?';
            }

            if (isset($_POST[$this->PageParam]))
            {
                foreach ($_POST as $key => $value)
                {
                    if ($key == $this->PageParam)
                    {
                        if (!$this->IsRoute)
                            $str .= $key . '=' . $page . '&';
                    }
                    else
                    {
                        $str .= $key . '=' . $value . '&';
                    }
                }
            }
            else
            {
                foreach ($_POST as $key => $value)
                {
                    $str .= $key . '=' . $value . '&';
                }
                if (!$this->IsRoute)
                    $str .= $this->PageParam . '=' . $page . '&';
            }
            $str = substr($str, 0, strlen($str) - 1);
            return $str;
        }
        else
        {
            if ($this->IsRoute)
                return $str;
            return $str . '?' . $this->PageParam . '=' . $page;
        }
    }

    /**
     * 获取分页条链接的最大和最小值
     *
     * @param int $allcount 总共有多少条数据
     * @param int $pagesize 每页有多少条数据
     * @param int $pagenumsize 分页条显示分页个数
     * @param int $page 当前页
     *
     * @return array([0], [1]) 整数数组，长度为2；[0]表示最小值，[1]表示最大值
     * */
    private function GetPageNum($allcount, $pagesize, $pagenumsize, $page)
    {
        $max = 0;
        $min = 0;
        $maxpage = intval($allcount / $pagesize);
        if ($allcount % $pagesize != 0) $maxpage++;

        if ($page > 1) {
            if ($page >= $maxpage) {
                $max = $maxpage;

                $min = max(array(1, intval($page - $pagenumsize / 2)));
            } else {

                $min = max(array(1, intval($page - $pagenumsize / 2)));

                $max = min(array($maxpage, $min + $pagenumsize - 1));

                // if($max-$min<$pagenumsize)
                // {
                // if($min==1)
                // {
                // $max=min(array($min+$pagenumsize,$maxpage+1));
                // }
                // else if($max==$maxpage+1)
                // {
                // $min=max(array(1,$max-$pagenumsize));
                // }
                // }

            }
        } else {
            $min = 1;

            $max = min(array($maxpage, $min + $pagenumsize - 1));
        }
        return array($min, $max);
    }
}

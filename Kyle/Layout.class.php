<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijuanzhi
 * Date: 2016/12/22
 * Time: 15:22
 */

class Layout
{
    /**
     * 模板页对应的文件路径
     * @var string
     */
    private static $layoutUrl;

    /**
     * js文件引用代码
     * @var array
     */
    private static $js;

    /**
     * css文件引用代码(头部)
     * @var array
     */
    private static $css;
    /**
     * 页面详细内容
     * @var string
     */
    public static $content;

    /**
     * 开始
     */
    public static function start()
    {
        ob_start();
    }

    /**
     * 结束
     */
    public static function end()
    {
        $str = ob_get_contents();
        ob_end_clean();
        if ($str !== false)
        {
            self::renderContent($str);
        }
    }

    /**
     *
     * @param string $str
     */
    public static function renderContent($str)
    {
        self::$content .= $str;
    }

    /**
     * 设置要引用的js
     *
     * @param string $renderName 要引用到的位置名称
     * @param string $jsUrl js文件的路径
     */
    public static function renderJavaScriptByUrl($renderName, $jsUrl)
    {
        $str = "<script type=\"javascript\" src=\"${jsUrl}\"></script>";
        self::$js[$renderName] .= $str;
    }

    /**
     * 设置要引用的css
     *
     * @param string $renderName 要引用到的位置名称
     * @param string $cssUrl css文件的路径
     */
    public static function renderCss($renderName, $cssUrl)
    {
        $str = "<link type=\"text/css\" href=\"${cssUrl}\" />";
        self::$css[$renderName] .= $str;
    }

    /**
     * 要引用的js
     *
     * @param string $renderName 要引用到的位置名称
     * @return string
     */
    public static function getJavaScript($renderName)
    {
        return isset(self::$js[$renderName]) ? self::$js[$renderName] : '';
    }
    /**
     * 要引用的css
     *
     * @param string $renderName 要引用到的位置名称
     * @return string
     */
    public static function getCss($renderName)
    {
        return isset(self::$css[$renderName]) ? self::$css[$renderName] : '';
    }

    /**
     * 设置模板页路径
     * @param string $url
     */
    public static function set($url)
    {
        self::$layoutUrl = $url;
    }

    /**
     * 获取模板页路径
     * @return string
     */
    public static function get()
    {
        return self::$layoutUrl;
    }

}
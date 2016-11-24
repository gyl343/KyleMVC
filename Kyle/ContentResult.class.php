<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-10-23
 * Time: 16:29
 */

class ContentResult extends ActionResult
{
    /**
     * 构造函数
     * @param string $content
     */
    public function __construct($content)
    {
        $this->Content = $content;
    }

    //返回的字符串
    private $Content;

    /**
     * 返回结果
     * @return mixed
     */
    public function executeResult()
    {
        // TODO: Implement executeResult() method.
		header('Content-type: text/html; charset=' . PHP_CHARSET);
        echo $this->Content;
        exit;
    }
}
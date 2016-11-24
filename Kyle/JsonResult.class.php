<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-10-23
 * Time: 16:34
 */

class JsonResult extends ActionResult
{
    /**
     * 构造函数
     * 目前只支持数组和字符串
     *
     * @param $object
     * @param $out_charset
     * @param $in_charset
     */
    public function __construct($object, $out_charset = null, $in_charset = PHP_CHARSET)
    {
        $this->Object = $object;
        $this->out_charset = $out_charset;
        $this->in_charset = $in_charset;
    }

    private $Object;
    private $out_charset;
    private $in_charset;
    /**
     * 执行
     * @return string json格式字符串
     */
    public function executeResult()
    {
        // TODO: Implement executeResult() method.
		header('Content-type: text/html; charset=' . ($this->out_charset == null ? PHP_CHARSET : $this->out_charset));
        if (is_array($this->Object))
        {
            $json = JsonHelper::arrayToJson($this->Object);
            if ($this->out_charset == null)
            {
                echo $json;
            }
            else
            {
                echo iconv($this->in_charset, $this->out_charset . '//IGNORE', $json);
            }
        }
        else
        {
            $json0 = json_encode($this->Object);
            if ($json0 === false)
            {
                $json = urldecode(json_encode(urlencode($this->Object)));
                if ($this->out_charset == null)
                {
                    echo $json;
                }
                else
                {
                    echo iconv($this->in_charset, $this->out_charset . '//IGNORE', $json);
                }
            }
            else
            {
                echo $json0;
            }
        }
        exit;
    }
}
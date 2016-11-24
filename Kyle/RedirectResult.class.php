<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-10-24
 * Time: 9:50
 */

class RedirectResult extends ActionResult
{
    public function __construct($redirectUrl)
    {
        $this->RedirectUrl = $redirectUrl;
    }

    private $RedirectUrl;

    /**
     * 执行
     * @return mixed
     */
    public function executeResult()
    {
        // TODO: Implement executeResult() method.
        header('Location:' . $this->RedirectUrl);
    }
}
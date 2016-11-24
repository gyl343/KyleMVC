<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-10-23
 * Time: 16:02
 */

/**
 * 视图包类
 * Class ViewBagModel
 */
class ViewBagModel
{
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        if (isset($this->$name))
        {
            return $this->$name;
        }
        else
        {
            return null;
        }
    }
}
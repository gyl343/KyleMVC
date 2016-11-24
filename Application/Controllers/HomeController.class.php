<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-10-23
 * Time: 22:32
 */

class HomeController extends Controller
{
    public function Index()
    {
        if ($this->isPost())
        {
            //这里写POST请求的代码

        }
        //演示如何使用ViewBag和Model
        $this->ViewBag->test = 'this is a ViewBag data.';//ViewBag
        return $this->View('this is a Model data.');//Model
    }

    public function Test()
    {
        if ($this->isPost())
        {
            $user = new User();
            if ($user->updateModelWithValidate($_POST))
            {
                return $this->Content('username : ' . $user->username . '<br/>password : ' . $user->password);
            }
            return $this->Content('error');
        }
        return $this->View();
    }
}
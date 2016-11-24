<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2016-09-01
 * Time: 15:01
 */

return array(
    //**********自己的路由配置写在这里**********
    'Html'=>array(
        'rule' => '{controller}/{:id}.html',
        'defaults' => array('controller'=>'News', 'action'=>'Item', 'id'=>0)
    ),
    //****************************************

    //请勿修改
    'Default' => array(
        'rule' => '{controller}/{action}/{:id}',
        'defaults' => array('controller' => 'Home', 'action' => 'Index', 'id' => 0)
    )
);
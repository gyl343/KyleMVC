<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2015-10-23
 * Time: 23:12
 */
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta content="text/html" charset="GBK">
    <title>KyleMVC</title>
    <style>
        body {
            font-family: "微软雅黑";
        }
    </style>
</head>
<body>
<div style="font-size: 200px;">
    &nbsp;&nbsp;:)&nbsp;&nbsp;<span style="font-size: 100px;">Index</span>
</div>
<h1>Welcome to use Simple KyleMVC.</h1>

<h2>&nbsp;&nbsp;&nbsp;&nbsp;It proves that you has been installed successfully if you can see this page. </h2>

<h2>&nbsp;&nbsp;&nbsp;&nbsp;[Now the page you visit is the IndexAction in the HomeController]</h2>
<h2 style="color: #286A46;">&nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $Model;?>]，[<?php echo $ViewBag->test;?>]</h2>
</body>
</html>

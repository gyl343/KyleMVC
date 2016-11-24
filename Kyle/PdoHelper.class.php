<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2016-09-05
 * Time: 17:34
 */

class PdoHelper
{
    /**
     * @var PDO
     */
    private static $_conn;
    private function setConn()
    {
        if (self::$_conn == null)
        {
            $mysql = getDbConfig();
            $host = $mysql['MYSQL_HOST'];
            $user = $mysql['MYSQL_USER'];
            $pwd = $mysql['MYSQL_PASSWORD'];
            $dbname = $mysql['MYSQL_DATABASE_NAME'];
            $charset = $mysql['MYSQL_CHARSET'];
            $dsn = "mysql:host=$host; dbname=$dbname; charset=$charset";

            try {
                //初始化一个PDO对象
                $conn = new PDO($dsn, $user, $pwd);
                //如果你的SQL服务器不真正的支持预处理,我们可以很容易的通过如下方式在PDO初始化时传参来修复这个问题:
                $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->query("set names $charset;");
                self::$_conn = $conn;
            } catch (PDOException $e) {
                die("PDO_EXCEPTION: " . $e->getMessage() . "<br/>");
            }
        }
    }

    public function __construct()
    {
        $this->setConn();
    }

    /**
     *操作sql语句，返回结果集
     *
     * @param string $sql sql语句
     * @param array $param 参数集(默认值为空)
     *
     * @return array
     * */
    public function executeData($sql, $param = null)
    {
        try
        {
            //准备sql语句
            $rs = self::$_conn->prepare($sql);
            //执行语句返回结果集
            $rs->execute($param);
            //获取结果集中的所有数据
            $arr = $rs->fetchAll(PDO::FETCH_ASSOC);

            return $arr;
        }
        catch (PDOException $e)
        {
            die("PDO_EXCEPTION: " . $e->getMessage() . "<br/>");
        }
    }

    /**
     * 操作sql语句，返回受影响的行数
     *
     * @param string $sql sql语句
     * @param array $param 参数集(默认值为空)
     *
     * @return int 受影响的行数
     * */
    public function executeNonQuery($sql, $param = null)
    {
        try
        {
            //准备sql语句
            $rs = self::$_conn->prepare($sql);
            //执行语句返回结果集
            $bool = $rs->execute($param);
            //获取受影响的行数
            $count = $rs->rowCount();

            if($count > 0)
            {
                return $count;
            }
            else
            {
                if($bool === false)
                {
                    return $count;
                }
                else
                {
                    return 1;
                }
            }

            //return $count;
        }
        catch(PDOException $e)
        {
            die("PDO_EXCEPTION: " . $e->getMessage() . "<br/>");
        }
    }


    /**
     * 操作sql语句，返回数据集的第一行第一列
     *
     * @param string $sql sql语句
     * @param array $param 参数集(默认值为空)
     *
     * @return mixed 数据集的第一行第一列
     * */
    public function executeScalar($sql, $param = null)
    {
        try
        {
            //准备sql语句
            $rs = self::$_conn->prepare($sql);
            //执行语句返回结果集
            $rs->execute($param);
            //获取结果集中的第一行第一列的数据
            $object = $rs->fetchColumn();

            return $object;
        }
        catch (PDOException $e)
        {
            die("PDO_EXCEPTION: " . $e->getMessage() . "<br/>");
        }
    }

    /**
     * 返回最后插入的数据的ID
     * @param null|string $name
     * @return string
     */
    public function lastInsertId($name = null)
    {
        return self::$_conn->lastInsertId($name);
    }

    /**
     * 启动事务
     */
    public function beginTransaction()
    {
        self::$_conn = null;
        $this->setConn();
		self::$_conn->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
        self::$_conn->beginTransaction();
    }

    /**
     * 提交事务
     */
    public function commit()
    {
        self::$_conn->commit();
        self::$_conn = null;
    }

    /**
     * 回滚事务
     */
    public function rollBack()
    {
        self::$_conn->rollBack();
        self::$_conn = null;
    }
}
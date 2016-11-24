<?php
/**
 * Created by PhpStorm.
 * User: gyl343
 * Date: 2016-09-01
 * Time: 1:00
 */

abstract class Database
{
    public function __construct($modelName = '')
    {
        try
        {
            $tables = $this->setTables();
            if (array_key_exists($modelName, $tables)) {
                $this->tableName = $tables[$modelName];
            } else {
                throw new Exception('It does not exist the table in the database.');
            }
        }
        catch (Exception $e)
        {
            die('EXCEPTION: ' . $e->getMessage());
        }
    }

    /**
     * @var string 表名
     */
    protected $tableName;
    /**
     * @var array 参数数组
     */
    protected static $params;
    /**
     * @var string sql语句
     */
    protected static $sql;

    /**
     * 抽象方法
     * 返回数据库中包含的表
     *
     * @return array
     * array(
     *     [model name] => [table name]
     * )
     * [model name]表示与数据库中表相对应的Model
     * [table name]表示数据库中表的名字
     */
    protected abstract function setTables();

    protected final function clearSql()
    {
        self::$sql = '';
        self::$whereSql = '';
        self::$whereCounter = 0;
        self::$orderBySql = '';
        self::$groupBySql = '';
        self::$joinSql = '';
        self::$pagerSql = '';
    }

    protected final function clearParams()
    {
        self::$params = array();
    }

    /**
     * 私有方法
     * @param string $columns
     * @param string $alias
     * @param bool $isScalar 是否返回第一行第一列
     *
     * @return mixed
     */
    private final function select0($columns = '*', $alias = '', $isScalar = false)
    {
        self::$sql = 'SELECT ' . $columns . ' FROM ' . $this->tableName . ($alias == '' ? '' : ' AS ' . $alias);
        self::$sql .= self::$joinSql;
        self::$sql .= self::$whereSql;
        self::$sql .= self::$groupBySql;
        self::$sql .= self::$orderBySql;
        self::$sql .= self::$pagerSql;

        $helper = new PdoHelper();
        $res = null;
        if ($isScalar)
        {
            $res = $helper->executeScalar(self::$sql, self::$params);
        }
        else
        {
            $res = $helper->executeData(self::$sql, self::$params);
        }
        $this->clearSql();
        $this->clearParams();
        return $res;
    }

    /**
     * @param string|array $columns
     * string: 'column1,column2,...'
     * array: array('column1', 'column2',...)
     * @param string $alias 别名
     *
     * @return array
     */
    public final function select($columns = '*', $alias = '')
    {
        return $this->select0($columns, $alias, false);
    }

    /**
     * 返回count() 聚合
     * @param string $column 要聚合的列名
     * @return int
     */
    public final function count($column = '1')
    {
        return $this->select0('count(' . $column . ')', '', true);
    }

    /**
     * insert 方法
     * @param array $columns
     * @param array $values
     * @param int &$lastInsertId
     *
     * @return int
     */
    public final function insert($columns, $values, &$lastInsertId = 0)
    {
        try {
            if (!is_array($columns) || !is_array($values)) {
                throw new Exception('$columns and $values must be array.');
            }
            self::$sql = 'INSERT INTO ' . $this->tableName;
            $str = '';
            $strP = '';
            $str .= ' (';
            $strP .= ' (';
            for ($i = 0; $i < count($columns); $i++) {
                $str .= $columns[$i] . ',';
                $strP .= ':' . trim($columns[$i]) . ',';
                if (!isset($values[$i])) {
                    throw new Exception('Undefine Index: ' . $i . ' in $values');
                }
                self::$params[':' . trim($columns[$i])] = $values[$i];
            }
            $str = substr($str, 0, strlen($str) - 1);
            $strP = substr($strP, 0, strlen($strP) - 1);
            $str .= ' )';
            $strP .= ' )';
            self::$sql .= $str;
            self::$sql .= ' VALUES';
            self::$sql .= $strP;

            $helper = new PdoHelper();
            $count = $helper->executeNonQuery(self::$sql, self::$params);
            $lastInsertId = $helper->lastInsertId();
            $this->clearSql();
            $this->clearParams();
            return $count;
        } catch (Exception $e) {
            $this->clearSql();
            $this->clearParams();
            die('EXCEPTION: ' . $e->getMessage());
        }
    }

    /**
     * update 方法
     * @param array $columns
     * @param array $values
     * @return int
     */
    public final function update($columns, $values)
    {
        try {
            if (!is_array($columns) || !is_array($values)) {
                throw new Exception('$columns and $values must be array.');
            }
            self::$sql = 'UPDATE ' . $this->tableName . ' SET ';
            $str = '';
            for ($i = 0; $i < count($columns); $i++) {
                $str .= $columns[$i] . '=:' . $columns[$i] . ',';
                if (!isset($values[$i])) {
                    throw new Exception('Undefine Index: ' . $i . ' in $values');
                }
                self::$params[':' . $columns[$i]] = $values[$i];
            }
            $str = substr($str, 0, strlen($str) - 1);
            self::$sql .= $str;
            self::$sql .= self::$whereSql;
            $helper = new PdoHelper();
            $count = $helper->executeNonQuery(self::$sql, self::$params);
            $this->clearSql();
            $this->clearParams();
            return $count;
        } catch (Exception $e) {
            self::clearParams();
            self::clearSql();
            die('EXCEPTION: ' . $e->getMessage());
        }
    }

    /**
     * delete 方法
     * 可以与where方法配合使用
     *
     * @return int
     */
    public final function delete()
    {
        self::$sql = 'DELETE FROM ' . $this->tableName;
        self::$sql .= self::$whereSql;
        $helper = new PdoHelper();
        $count = $helper->executeNonQuery(self::$sql, self::$params);
        $this->clearSql();
        $this->clearParams();
        return $count;
    }

    /**
     * @var string where子句
     */
    protected static $whereSql;
    /**
     * @var int where计数器
     */
    protected static $whereCounter = 0;

    /**
     * @param array $where
     *
     * 数组中这几个键分别表示：
     * '_logic': 表示逻辑关系
     * 分别有：'AND' 和 'OR'两种
     *
     * '_op': 表示运算关系
     * 分别有：'=', '>', '>=', '<=', '<>', 'LIKE', 'IN'
     *
     * @return $this
     */
    public final function where($where)
    {
        try
        {
            if (!is_array($where))
            {
                throw new Exception('$where must be array');
            }

            if (strlen(self::$whereSql) > 6)
                self::$whereSql .= ' AND';
            else if (strlen(self::$whereSql) == 0)
                self::$whereSql .= ' WHERE';
            /**
             * a = 1 AND b = 1 (>, <, >=, <=, <>)
             * a = 1 OR b = 1 (>, <, >=, <=, <>)
             * a LIKE ''
             * a IN (1, 2)
             */
            $_logic = ' AND';
            if (array_key_exists('_logic', $where))
            {
                $_logic = $this->checkLogic($where['_logic']);
            }

            foreach ($where as $k => $v)
            {
                $_op = ' = ';
                if (!is_array($v))
                {
                    self::$whereSql .= ' (' . $k . $_op . ':' . $k . self::$whereCounter . ')';
                    self::$params[':' . $k . self::$whereCounter] = $v;
                }
                else
                {
                    self::$whereSql .= ' (';

                    if (!is_array($v[0]))
                    {
                        self::$whereSql .= ' ' . $k . ' ' . $v[0] . ' :' . $k . self::$whereCounter;
                        self::$params[':' . $k . self::$whereCounter] = $v[1];
                    }
                    else
                    {
                        $_vLogic = ' AND';
                        if (array_key_exists('_logic', $v))
                        {
                            $_vLogic = $this->checkLogic($v['_logic']);
                        }
                        $i = 0;
                        foreach ($v as $vk => $vv)
                        {
                            if (is_int($vk))
                            {
                                if (strtoupper(trim($vv[0])) == 'IN')
                                {
                                    $in = $vv[1];
                                    $inArr = array();
                                    if (!is_array($in))
                                    {
                                        $in = trim($in, ' ()\t\n\r\0\x0B');
                                        $inArr = explode(',', $in);
                                    }
                                    else
                                    {
                                        $inArr = $in;
                                    }
                                    $inStr = ' (';
                                    $j = 0;
                                    foreach ($inArr as $il)
                                    {
                                        $inStr .= ':' . $k . self::$whereCounter . $i . $j . ',';
                                        self::$params[':' . $k . self::$whereCounter . $i . $j] = trim($il);
                                        $j++;
                                    }
                                    $inStr = substr($inStr, 0, strlen($inStr) - 1);
                                    $inStr .= ' )';
                                    self::$whereSql .= ' ' . $k . ' ' . $vv[0] . $inStr . $_vLogic;
                                }
                                else
                                {
                                    self::$whereSql .= ' ' . $k . ' ' . $vv[0] . ' ' . ':' . $k . self::$whereCounter . $i . $_vLogic;
                                    self::$params[':' . $k . self::$whereCounter . $i] = $vv[1];
                                }
                                $i++;
                            }
                        }
                        self::$whereSql = substr(self::$whereSql, 0, strlen(self::$whereSql) - strlen($_vLogic));
                    }

                    self::$whereSql .= ')';
                }
                self::$whereSql .= $_logic;
            }
            self::$whereSql = substr(self::$whereSql, 0, strlen(self::$whereSql) - strlen($_logic));
            self::$whereCounter++;
            return $this;
        }
        catch (Exception $e)
        {
            self::clearParams();
            self::clearSql();
            die('EXCEPTION: ' . $e->getMessage());
        }
    }

    /**
     * @var string order by子句
     */
    protected static $orderBySql;
    /**
     * 排序
     * @param string $column
     * @param bool $isAsc 是否为正序排列
     * @return $this
     */
    public final function orderBy($column, $isAsc = true)
    {
        if (strlen(self::$orderBySql) == 0)
        {
            self::$orderBySql .= ' ORDER BY ' . $column . ($isAsc ? '' : ' DESC');
        }
        else
        {
            self::$orderBySql .= ',' . $column . ($isAsc ? '' : ' DESC');
        }
        return $this;
    }

    /**
     * @var string group by子句
     */
    protected static $groupBySql;

    /**
     * 分组，目前只支持按一个字段分组
     *
     * @param string $column
     * @return $this
     */
    public final function groupBy($column)
    {
        if (strlen(self::$groupBySql) == 0)
        {
            self::$groupBySql .= ' GROUP BY ' . $column;
        }
        return $this;
    }

    /**
     * @var string join子句
     */
    protected static $joinSql;
    /**
     * 目前无效
     * @return $this
     */
    public final function join()
    {
        return $this;
    }
    /**
     * 目前无效
     * @return $this
     */
    public final function leftJoin()
    {
        return $this->join();
    }
    /**
     * 目前无效
     * @return $this
     */
    public final function rightJoin()
    {
        return $this->join();
    }


    protected static $pagerSql;
    /**
     * 分页
     * @param int $page 要获取第几页
     * @param int $pageSize 每页的数量
     * @return $this
     */
    public final function pager($page, $pageSize)
    {
        if (strlen(self::$pagerSql) == 0)
        {
            self::$pagerSql .= ' LIMIT :skip, :pagesize';
            self::$params[':skip'] = ($page - 1) * $pageSize;
            self::$params[':pagesize'] = $pageSize;
        }
        return $this;
    }

    /**
     * 验证逻辑方式
     * @param string $logic
     * @return string
     */
    private function checkLogic($logic)
    {
        if (strtoupper(trim($logic)) == 'OR')
            return ' OR';
        return ' AND';
    }
}


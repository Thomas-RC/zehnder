<?php
namespace Database;


use PDO;
use PDOException;

class DBdriver
{

    protected $DSN, $DBS;
    private $type;
    private $where;
    private $data;
    private $dataUpdate;

    protected static $_instance;

    public function __construct()
    {
        $dbHost = env('DB_HOST');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbName = env('DB_NAME');
        $dbPort = env('DB_PORT');
        $dbChar = env('DB_CHAR');

        $this->DSN = "mysql:host={$dbHost};dbname={$dbName};port={$dbPort};charset={$dbChar}";
        try {
            $this->DBS = new PDO($this->DSN, $dbUser, $dbPass);
            $this->DBS->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->DBS->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $ex) {
            return false;
        }
        self::$_instance = $this;
        return true;
    }

    public static function getInstance(): DBdriver
    {
        return self::$_instance;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * @param mixed $where
     */
    public function setWhere($where): void
    {
        $this->where = $where;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getDataUpdate()
    {
        return $this->dataUpdate;
    }

    /**
     * @param mixed $dataUpdate
     */
    public function setDataUpdate($dataUpdate): void
    {
        $this->dataUpdate = $dataUpdate;
    }


    
    public function dbOne($statement, $input_parameters = [])
    {
        try {
            $sth = $this->DBS->prepare($statement);
            $sth->execute($input_parameters);

            return $sth->fetch();
        } catch (PDOException $ex) {
            return false;
        }
    }

    public function dbAll($statement, $input_parameters = false)
    {
        try {
            $sth = $this->DBS->prepare($statement);
            if ($input_parameters)
                $sth->execute($input_parameters);
            else
                $sth->execute();
            return $sth->fetchAll();
        } catch (PDOException $ex) {
            return false;
        }
    }

    public function dbAct($statement, $input_parameters = [])
    {
        try {
            $sth = $this->DBS->prepare($statement);
            $sth->execute($input_parameters);
            return true;
        } catch (PDOException $ex) {
            echo $ex->errorInfo[2];
            return false;
        }
    }

    public function dbCount($statement, $input_parameters = [])
    {
        try {
            $sth = $this->DBS->prepare($statement);
            $sth->execute($input_parameters);
            return $sth->rowCount();
        } catch (PDOException $ex) {
            //throw new Exception($ex->getMessage());
            return false;
        }
    }

    public function dbQuote($string)
    {
        return $this->DBS->quote($string);
    }

    public function get(string $table, string $params = "*", string $type='')
    {
        $this->setType($type);
        try {
            $res = $this->autoExecute("SELECT {$params} FROM {$table}");
        }
        catch (PDOException $ex)
        {
            throw new PDOException($ex->getMessage());
        }
        return $res;
    }

    public function insert(string $table, array $params = [])
    {
        $paramArray = [];
        $valueSign = [];
        $valueArray = [];

        while( current($params) != false)
        {
            $valueArray = array_values($params);
            $valueSign[] = "?";
            $paramArray[] = key($params);
            next($params);
        }

        $paramsEnd = implode(',', $paramArray);
        $valueSign = implode(',', $valueSign);

        $this->setData($valueArray);
        $this->setType('insert');
        try {
            $res = $this->autoExecute("INSERT INTO {$table} ({$paramsEnd}) VALUES ({$valueSign})");
        }
        catch (PDOException $ex)
        {
            throw new PDOException($ex->getMessage());
        }
        return $res;
    }

    public function update(string $table, array $params = [])
    {
        $paramArray = [];
        $valueArray = [];

        while( current($params) != false)
        {
            $valueArray = array_values($params);
            $paramArray[] = key($params).' = ?';
            next($params);
        }
        $paramsEnd = implode(',', $paramArray);

        $this->setDataUpdate($valueArray);
        $this->setType('update');

        try {
            $res = $this->autoExecute("UPDATE {$table} SET {$paramsEnd}");
        }
        catch (PDOException $ex)
        {
            throw new PDOException($ex->getMessage());
        }
        return $res;

    }

    public function where(string $where = '', array $data = [])
    {
        $this->setData($data);
        if (!empty($where))
        {
            $where = " where " . str_replace([',', ';'], [' and ', ' or '], $where);
        }
        $this->setWhere($where);
    }

    private function autoExecute(string $sql)
    {
        $type = $this->getType();
        $data = $this->getData();
        $dataUpdate = $this->getDataUpdate();
        if(is_array($dataUpdate))
            $dataUpdate = array_merge($dataUpdate,$data);

        switch ($type)
        {
            default:
                $res = $this->dbAll($sql . $this->where, $data);
                break;
            case'insert':
            case 'delete':
                $res = $this->dbAct($sql . $this->where, $data);
                break;
            case 'update':
                $res = $this->dbAct($sql . $this->where, $dataUpdate);
                break;
            case'selectOne':
                $res = $this->dbOne($sql . $this->where, $data);
                break;
        }

        unset($this->where);
        unset($this->data);

        return $res;
    }


    public function __destruct()
    {
        return $this->DBS = null;
    }

}
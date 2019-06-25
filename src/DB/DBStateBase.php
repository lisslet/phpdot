<?php

namespace Dot\DB;

abstract class DBStateBase implements DBStateInterface
{
    public $caller;
    protected $sql;
    protected $_state;
    protected $result;

    function __construct($state)
    {
        $this->_state = $state;
    }

    /**
     * @return DBStateInterface
     */
    function execute(): DBStateInterface
    {
        $this->_state->execute();
        return $this;
    }

    /**
     * @return DBResultInterface
     */
    abstract protected function getResult(): DBResultInterface;

    /**
     * @param string $type the type of variable
     * @return \stdClass
     * @throws Error
     */
    function fetch($type = DB::FETCH_TYPE)
    {
        switch ($type) {
            case DB::ASSOC :
                return $this->fetchAssoc();
                break;
            case DB::BOTH :
                return $this->fetchBoth();
                break;
            default:
                throw new \Error('Wrong type for fetch');
        }
    }

    /**
     * @return \stdClass
     */
    abstract function result(): DBResultInterface;

    function fetchBoth()
    {
        return $this->result()->fetchBoth();
    }

    /**
     * @return \stdClass
     */
    function fetchAssoc()
    {
        return $this->result()->fetchAssoc();
    }

    function column(string $name)
    {
        $result = [];
        $records = $this->result();
        while ($record = $records->fetchAssoc()) {
            $result[] = $record[$name];
        }
        return $result;
    }
    /*
    function setCaller(DBSQLCallerInterface $caller)
    {
        $this->caller = $caller;
    }

    function setSql(DBSQLInterface $sql)
    {
        $this->sql = $sql;
    }
    */
}
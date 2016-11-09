<?php

require_once "result.php";

class DB_Mysql{
    protected $user;
    protected $pass;
    protected $dbhost;
    protected $dbname;
    protected $dbh;
    
    public function __construct($user, $pass, $dbhost, $dbname) {
        $this->user =  $user;
        $this->pass = $pass;
        $this->dbhost = $dbhost;
        $this->dbname = $dbname;
    }
    protected function connect(){
        $this->dbh = mysql_pconnect($this->dbhost, $this->user, $this->pass);
        if(!is_resource($this->dbh)){
            throw new Exception;
        }
        if(!mysql_select_db($this->dbname, $this->dbh)){
            throw new Exception;
        }
    }
    public function execute($query) {
        if(!$this->dbh){
            $this->connect();
        }
        $ret = mysql_query($query, $this->dbh);
        if(!$ret){
            throw new Exception;
        }
        else if (!is_resource($ret)) {
            return TRUE;
        } else {
            $stmt =  new DB_Statement($this->dbh, $query);
            $stmt->result = $ret;
            return $stmt;
        }
    }
    public function prepare($query) {
        if(!$this->dbh){
            $this->connect();
        }
        return new DB_Statement($this->dbh, $query);
    }
}
class DB_Statement implements IteratorAggregate{
    public $result;
    protected $binds;
    protected $query;
    protected $dbh;
    
    function getIterator() {
        return new DB_Result($this);
    }
    public function __construct($dbh, $query) {
        $this->query = $query;
        $this->dbh = $dbh;
        if(!is_resource($dbh)){
            throw new Exception("Некорректное соединение с базой данных");
        }
    }
    public function fetch_row(){
        if(!$this->result){
            throw new Exception("Запрос не выполнен");
        }
        return mysql_fetch_row($this->result);
    }
    public function fetch_assoc(){
        return mysql_fetch_assoc($this->result);
    }
    public function fetchall_assoc(){
        $retval = array();
        while($row = $this->fetch_assoc()){
            $retval[] = $row;
        }
        return $retval;
    }
    public function execute() {
        $binds = func_get_args();
        foreach($binds as $index => $name){
            $this->binds[$index + 1] = $name;
        }
        $query = $this->query;
        foreach ($this->binds as $ph => $pv) {
            $query = str_replace(":$ph", "'".mysql_escape_string($pv)."'", 
                    $query);
        }
        $this->result = mysql_query($query, $this->dbh);
        if(!$this->result){
            throw new MysqlException;
        }
        return $this;
    }
//    public function fetch() {
//        return new DB_Result($this);
//    }
}

?>


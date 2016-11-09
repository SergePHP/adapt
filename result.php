<?php

class DB_Result implements Iterator{
    protected $stmt;
    protected $result = array();
    protected $rowIndex = 0;
    protected $currIndex = 0;
    protected $max = 0;
    protected $done = false;
    
    public function __construct(DB_Statement $stmt) {
        $this->stmt = $stmt;
        $row = $this->stmt->fetch_assoc();
        if(!$row){
            $this->done = true;
            $this->max = $this->currIndex;
            return;
        }
            $this->result[] = $row;
            return;
    }
    function rewind() {
        $this->currIndex = 0;
    }
    function valid() {
        if($this->done && $this->max == $this->currIndex){
            return false;
        }
        return true;
    }
    function key(){
        return $this->currIndex;
    }
    function current() {
        return $this->result[$this->currIndex];
    }
    function next() {
        if($this->done && $this->max == $this->currIndex){
            return false;
        }
        $offset = $this->currIndex + 1;
        if(!$this->result[$offset]){
            $row = $this->stmt->fetch_assoc();
            if(!$row){
                $this->done = true;
                $this->max = $this->currIndex;
                return false;
            }
            $this->result[$offset] = $row;
            ++$this->rowIndex;
            ++$this->currIndex;
            return $this;

            } else {
                ++$this->currIndex;
                return $this;
            }
    }
//    public function first() {
//        if(!$this->result){
//            $this->result[$this->rowIndex++] = $this->stmt->fetch_assoc();
//        }
//        $this->currIndex = 0;
//        return $this;
//    }
//    public function last(){
//        if(!$this->done){
//            array_push($this->result, $this->stmt->fetchall_assoc());
//        }
//        $this->done = true;
//        $this->currIndex = $this->rowIndex = count($this->result) - 1;
//        return $this;
//    }
//    public function next(){
//        if($this->done){
//            return false;
//        }
//        $offset = $this->currIndex + 1;
//        if(!$this->result[$offset]){
//            $row = $this->stmt->fetch_assoc();
//            if(!$row){
//                $this->done = true;
//                return false;
//            }
//            $this->result[$offset] = $row;
//            ++$this->rowIndex;
//            ++$this->currIndex;
//            return $this;
//
//            } else {
//                ++$this->currIndex;
//                return $this;
//            }
//    }
//    public function prev() {
//        if($this->currIndex == 0){
//            return false;
//        }
//        --$this->currIndex;
//        return $this;
//    }
//    public function __get($name) {
//        if(array_key_exists($name, $this->result[$this->currIndex])){
//            return $this->result[$this->currIndex][$name];
//        }
//    }
}


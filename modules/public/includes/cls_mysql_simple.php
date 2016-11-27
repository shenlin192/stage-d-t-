<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/25
 * Time: 18:49
 */
class cls_mysql_simple{
    private $host='localhost';
    private $name='root';
    private $pwd='ab01';
    private $dBase='yikaicar';
    private $conn;
    private $result='';
    private $msg='';
    private $fields;
    private $fieldNum=0;
    private $rowsNum=0;
    private $rowsRst='';
    private $filesArray=array();
    private $rowsArray=array();

    public function __construct($host='', $name='', $pwd='', $dBase='')
    {
        if($host){
            $this->host=$host;
        }
        if($name){
            $this->name=$name;
        }
        if($pwd){
            $this->pwd=$pwd;
        }
        if($dBase){
            $this->dBase=$dBase;
        }
        $this->init_conn();

    }
    public function init_conn(){
        $this->conn=mysqli_connect($this->host,$this->name,$this->pwd,$this->dBase);
        mysqli_query($this->conn,'set names utf8');
    }
    public function mysql_query_rsl($sql){
        if(!$this->conn){
            $this->init_conn();
        }
        $this->result=mysqli_query($this->conn,$sql);
    }
    public function getRowsNum($sql){
        $this->mysql_query_rsl($sql);
        if(!mysqli_errno($this->conn)){
            return mysqli_num_rows($this->result);
        }else{
            return 0;
        }
    }
    public function getRowsRst($sql){
        $this->mysql_query_rsl($sql);
        if(!mysqli_errno($this->conn)){
            $this->rowsRst=mysqli_fetch_array($this->result,MYSQLI_ASSOC);
            return $this->rowsRst;
        }else{
            return "";
        }
    }
    public function getRowsArray($sql){
        $this->mysql_query_rsl($sql);
        if(!mysqli_errno($this->conn)){
            while($row=mysqli_fetch_array($this->result,MYSQLI_ASSOC)){
                $this->rowsArray[]=$row;
            }
            return $this->rowsArray;
        }else{
            return "";
        }
    }
    public function uidRst($sql){
        if(!$this->conn){
            $this->init_conn();
        }
        mysqli_query($this->conn,$sql);
        $this->rowsNum=mysqli_affected_rows($this->conn);
        if(!mysqli_errno()){
            return $this->rowsNum;
        }else{
            return "";
        }

    }
    function close_rst(){
        mysqli_free_result($this->result);
        $this->msg="";
        $this->fieldNum=0;
        $this->rowsNum=0;
        $this->filesArray="";
        $this->rowsArray="";
    }
    function close_conn(){
        $this->close_rst();
        mysqli_close($this->conn);
        $this->conn="";
    }


}

<?php
/**
 * Created by PhpStorm.
 * User: Nived
 * Date: 24-06-2016
 * Time: 10:56 AM
 */

class Table
{
    var $name;
    var $db;
    var $schema = array();

    function __construct($n,$d)
    {
        $this->name = $n;
        $this->db = $d;
        $struct = $this->db->query("PRAGMA table_info(".$this->name.");");
        while($row = $struct->fetchArray(SQLITE3_ASSOC) )
            $this->schema[$row["name"]] = $row["type"];

    }

    function addRow($fields)
    {
        $exc = "INSERT INTO ".$this->name." (";
        $size = sizeof($fields);
        $count = 0;
        foreach ($fields as $key => $value)
        {
            $count++;
            if($key!='ID' && array_key_exists($key,$this->schema))
            {
                $exc = $exc.$key;
                if($count!=$size)
                    $exc = $exc.',';
            }

        }
        $exc = $exc.") VALUES (";
        $count = 0;
        foreach ($fields as $key => $value)
        {
            $count++;
            if($key!='ID' && array_key_exists($key,$this->schema))
            {
                if($this->schema[$key]=="TEXT")
                    $exc = $exc."'".$value."'";
                else
                    $exc = $exc.$value;
                if($count!=$size)
                    $exc = $exc.',';
            }
            else
            {
                return false;
            }

        }
        $exc = $exc.");";
        //echo $exc;
        $this->db->query($exc);
    }

    function getRowByID($id)
    {
        //SELECT * from COMPANY WHERE ID = 2;
        $exc = "SELECT * from ".$this->name." WHERE ID = ".strval($id)." ;";
        $ret = $this->db->query($exc);
        $row = $ret->fetchArray(SQLITE3_ASSOC);
        return $row;
    }

    function getRowsByField($fields)
    {
        $exc = "SELECT * from ".$this->name." WHERE ";
        $count = 0;
        $size = sizeof($fields);
        foreach ($fields as $key => $value)
        {
            $count++;
            if(array_key_exists($key,$this->schema))
            {
                $exc = $exc.$key." = ".$value;
                if($count!=$size)
                    $exc = $exc.' and ';
            }
            else
            {
                return false;
            }

        }
        $ret = $this->db->query($exc);
        $data = array();
        while($row = $ret->fetchArray(SQLITE3_ASSOC) )
        {
            array_push($data,$row);
        }
        return $data;
    }

    function updateRowByID($id,$fields)
    {
        $exc = "UPDATE ".$this->name." SET ";
        $count = 0;
        $size = sizeof($fields);
        foreach ($fields as $key => $value)
        {
            $count++;
            if($key!='ID' && array_key_exists($key,$this->schema))
            {
                if($this->schema[$key]=="TEXT")
                    $exc = $exc.$key." = '".$value."'";
                else
                    $exc = $exc.$key." = ".$value;
                if($count!=$size)
                    $exc = $exc.' , ';
            }
            else
            {
                return false;
            }

        }
        $exc = $exc." WHERE ID =".strval($id)." ;";
        $this->db->query($exc);
    }

    function deleteRowByID($id)
    {
        $exc = "DELETE FROM ".$this->name." WHERE ID = ".strval($id)." ;";
        $this->db->query($exc);
    }


}


class DB
{
    static $TEXT = 101;
    static $INT = 102;
    var $exc;
    var $db;
    function __construct($filename)
    {
        $this->db = new SQLite3($filename);
    }

    function createTable($name,$fields)
    {
        $this->exc = "CREATE TABLE `".$name."` (`ID`	INTEGER PRIMARY KEY AUTOINCREMENT";
        foreach ($fields as $key => $value)
        {
            if($value==DB::$TEXT)
                $this->exc = $this->exc.", '".$key."'  TEXT";
            if($value==DB::$INT)
                $this->exc = $this->exc.", '".$key."'  INTEGER";
        }
        $this->exc = $this->exc.");";
        $this->db->query($this->exc);
        return new Table($name,$this->db);
    }

    function  getTable($name)
    {
        if(sizeof($this->db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$name';")->fetchArray())==1)
            return false;
        return new Table($name,$this->db);
    }

    function deleteTable($name)
    {
        $exc = "DROP TABLE $name ;";
        $this->db->query($exc);
    }
}
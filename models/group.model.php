<?php

class GroupModel
{
    public $group_id;
    public $group_name;
    public $group_parent;

    public $page;
    public $limit;
    public $keyword;

    public function __construct($object)
    {
            if (!$object) {
                PrintJSON("", "data is empty!", 0);
                die();
        }
        foreach ($object as $property => $value) {
            if (property_exists('GroupModel', $property)) {
                $this->$property = $value;
            }
        }
    }
    function checkId()
    {
        $db = new db_mssql();
        $sql = "select * from cfg_group where group_id='$this->group_id' ";
        $name = $db->query($sql);
        
        if ($name == 0) {
            PrintJSON(""," group ID: ".$this->group_id. " is not available!", 0);
            die();
        } 
    }
    function validateName()
    {
        $db = new db_mssql();
        $sql = "select * from cfg_group where group_name='$this->group_name' ";
        $name = $db->query($sql);

        if ($name > 0) {
            PrintJSON(""," group name: ".$this->group_name. " already exist", 0);
            die();
        } else  if ($this->group_name == "") {
            PrintJSON("", " group name is empty ", 0);
            die();
        }
    }
    function validateGroup_parent()
    {
        $db = new db_mssql();
        $sql = "select * from cfg_group where group_id='$this->group_parent' ";
        $name = $db->query($sql);
        
        if ($name == 0) {
            PrintJSON(""," group parent ID: ".$this->group_parent. " is not available!", 0);
            die();
        } 
    }
}

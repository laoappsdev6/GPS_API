<?php

class UserModel
{
    public $usrid_;
    public $uname_;
    public $login_;
    public $upass_;
    public $email_;
    public $rtime_;
    public $rmail_;
    public $mtype_;
    public $valid_;
    public $group_;
    public $uphone_;
    public $olimit_;
    public $self_;

    public function __construct($object, $needEmpty = false)
    {
        if (!$needEmpty) {
            if (!$object) {
                PrintJSON("", "data is empty", 0);
                die();
            }
        };
        foreach ($object as $property => $value) {
            if (property_exists('UserModel', $property)) {
                $this->$property = $value;
            }
        }
    }
    function checkAllProperties()
    {
        foreach ($this as $property => $value) {
            $this->validate($property);
        }
    }
    function checkId()
    {
        $db = new db_mssql();
        $sql = "select * from sys_user where user_id='$this->usrid_' ";
        $name = $db->query($sql);
        
        if ($name == 0) {
            PrintJSON(""," user ID: ".$this->usrid_. " is not available!", 0);
            die();
        } 
    }
    function checkself()
    {
        if (!is_numeric($this->self)) {
            PrintJSON("", "Self is number only", 0);
        }
    }
    function validate($p)
    {
        switch ($p) {
            case 'uname_':
                $this->validateUsername();
                break;
            case 'login_':
                $this->validateLoginname();
                break;
            case 'upass_':
                $this->validatePassword();
                break;
            case 'valid_':
                $this->valaidateValid();
                break;
            case 'group_':
                $this->validateGroup();
                break;
            case 'uphone_':
                $this->validatePhonenumber();
                break;
            case 'olimit_':
                $this->validateObjectlimit();
                break;
        }
    }
    function validateUsername()
    {
        if ( strlen($this->uname_) < 3) {
            PrintJSON("", "Username is short", 0);
            die();
        }
    }
    function validateLoginname()
    {
        $db = new db_mssql();
        $sql = "select * from sys_user where login_name='$this->login_' and user_id!='$this->usrid_' ";
        $name = $db->query($sql);
        
        if ($name > 0) {
            PrintJSON(""," login name: ".$this->login_. " already exit!", 0);
            die();
        } 
        else if (strlen($this->login_) < 2) {
            PrintJSON("", "Name is short ", 0);
            die();
        }
    }
    function validatePassword()
    {
        $uppercase = preg_match('@[A-Z]@', $this->upass_);
        $lowercase = preg_match('@[a-z]@', $this->upass_);
        $number = preg_match('@[0-9]@', $this->upass_);

        if (strlen($this->upass_) < 6) {
            PrintJSON("", "password must be then 6 digists", 0);
            die();
        }
    }
    function valaidateValid()
    {
        if (!is_numeric($this->valid_)) {
            PrintJSON("", "Valid is number only", 0);
            die();
        }
    }
    function validateGroup()
    {
        $db = new db_mssql();
        $sql = "select * from cfg_group where group_id IN($this->group_) ";
        $name = $db->query($sql);
        
        if ($name == 0) {
            PrintJSON(""," group ID: ".$this->group_. " is not available!", 0);
            die();
        } 
    }
    function validatePhonenumber()
    {
        $number = preg_match('@[0-9]@', $this->uphone_);
        if (!$number || strlen($this->uphone_) < 10) {
            PrintJSON("", "Phonenumber must be 10 digists and number only", 0);
            die();
        }
    }
    function validateObjectlimit()
    {
        if (!is_numeric($this->olimit_)) {
            PrintJSON("", "Object limit is number only", 0);
            die();
        }
    }

}
?>
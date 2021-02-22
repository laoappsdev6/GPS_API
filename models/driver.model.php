<?php

class DriverModel
{
    public $jno;
    public $name;
    public $oid;
    public $sex;
    public $ip;
    public $id;
    public $l;
    public $isd;
    public $exd;
    public $tel;
    public $rfid;
    public $co;
    public $addr;
    public $r;
    public $p;
    public function __construct($object, $needEmpty = false)
    {
        if (!$needEmpty) {
            if (!$object) {
                PrintJSON("", "data is empty", 0);
                die();
            }
        };
        foreach ($object as $property => $value) {
            if (property_exists('DriverModel', $property)) {
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
        if (strlen($this->jno) < 3) {
            PrintJSON("", "ID must be greater than 3 digits", 0);
            die();
        }
    }
    function validate($p)
    {
        switch ($p) {
            case 'name':
                $this->validateNmae();
                break;
            case 'sex':
                $this->validateSex();
                break;
            case 'ip':
                $this->validateIp();
                break;
            case 'id':
                $this->validateId();
                break;
            case 'l':
                $this->validateL();
                break;
            case 'isd':
                $this->validateIsd();
                break;
            case 'exd':
                $this->validateExd();
                break;
            case 'tel':
                $this->validateTel();
                break;
            case 'rfid':
                $this->validateRfid();
                break;
            case 'addr':
                $this->validateAddr();
                break;
        }
    }
  
    function validateNmae()
    {
        if (strlen($this->name) < 3) {
            PrintJSON("", "Driver name is short ", 0);
            die();
        }
    }
    function validateSex()
    {
        if (!is_numeric($this->sex)) {
            PrintJSON("", "Sex is number only", 0);
            die();
        }
    }
    function validateIp()
    {
        if ($this->ip == "") {
            PrintJSON("", "Ip is empty", 0);
            die();
        }
    }
    function validateId()
    {
        if ($this->id =="") {
            PrintJSON("", "Id is not valid", 0);
            die();
        }
    }
    function validateL()
    {
        if ($this->l =="") {
            PrintJSON("", "License is not valid", 0);
            die();
        }
    }
    function validateIsd()
    {
        $dateTime = DateTime::createFromFormat('Y-m-d', $this->isd);
        if (!$dateTime) {
            PrintJSON("", "Isd is not DateTime format", 0);
            die();
        }
    }
    function validateExd()
    {
        $dateTime = DateTime::createFromFormat('Y-m-d', $this->exd);
        if (!$dateTime) {
            PrintJSON("", "Exd is not DateTime format", 0);
            die();
        }
    }
    function validateTel()
    {
        $number = preg_match('@[0-9]@', $this->tel);
        if (!$number || strlen($this->tel) < 10) {
            PrintJSON("", "Phonenumber must be 10 deigists and number only", 0);
            die();
        }
    }
    function validateRfid()
    {
        $db = new db_mssql();
        $sql = "select * from cfg_driver where rfid='$this->rfid' and  job_number != '$this->jno'";
        $name = $db->query($sql);

        if ($name > 0) {
            PrintJSON("", " RFID NO: " . $this->rfid . " already exist", 0);
            die();
        } else if ($this->rfid == "") {
            PrintJSON("", "RFID is empty", 0);
            die();
        }
    }
    function validateAddr()
    {
        if (strlen($this->addr) < 3) {
            PrintJSON("", "address is short ", 0);
            die();
        }
    }
}


?>
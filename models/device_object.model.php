<?php

class DeviceObjectModel
{
    public $objid;
    public $dtype;
    public $dstate;
    public $devno;
    public $simno;
    public $dpass;
    public $stamp;
    public $iaddr;
    public $estamp;
    public $cinfo;
    public $ginfo;
    public $okind;
    public $oflag;
    public $uflag;
    public $ztime;
    public $driver;
    public $remark;
    public function __construct($object, $needEmpty = false)
    {
        if (!$needEmpty) {
            if (!$object) {
                PrintJSON("", "Ddata is empty", 0);
                die();
            }
        };
        foreach ($object as $property => $value) {
            if (property_exists('DeviceObjectModel', $property)) {
                $this->$property = $value;
            }
        }
    }

    public function checkAllProperties()
    {
        foreach ($this as $property => $value) {
            $this->validate($property);
        }
    }

    public function checkId()
    {
        $db = new db_mssql();
        $sql = "select * from cfg_object where object_id='$this->objid' ";
        $name = $db->query($sql);

        if ($name == 0) {
            PrintJSON("", " object ID: " . $this->objid . " is not available!", 0);
            die();
        }
    }
    public function validate($p)
    {
        switch ($p) {
            case 'dtype':
                $this->validateDtype();
                break;
            case 'dstate':
                $this->validateDstate();
                break;
            case 'devno':
                $this->validateDevno();
                break;
            case 'simno':
                $this->validateSimno();
                break;
            case 'stamp':
                $this->validateStamp();
                break;
            case 'estamp':
                $this->validateEstamp();
                break;
            case 'cinfo':
                $this->validateCinfo();
                break;
            case 'ginfo':
                $this->validateGinfo();
                break;
            case 'oflag':
                $this->validateOflag();
                break;
            case 'ztime':
                $this->validateZtime();
                break;
            case 'driver':
                $this->validateDriver();
                break;
        }
    }

    public function validateDtype()
    {
        if (!is_numeric($this->dtype)) {
            PrintJSON("", "Dtype is number only", 0);
            die();
        }
    }
    public function validateDstate()
    {
        if (!is_numeric($this->dstate)) {
            PrintJSON("", "Dstate is number only", 0);
            die();
        }
    }
    public function validateDevno()
    {
        $db = new db_mssql();
        $sql = "select * from cfg_device where device_no='$this->devno' and object_id !='$this->objid' ";
        $name = $db->query($sql);

        if ($name > 0) {
            PrintJSON("", " Device NO: " . $this->devno . " already exist", 0);
            die();
        } else if (!is_numeric($this->devno)) {
            PrintJSON("", "Device NO is number only", 0);
            die();
        }
    }
    public function Devno_changeGPS()
    {
        $db = new db_mssql();
        $sql = "select * from cfg_device where device_no='$this->devno'";
        $name = $db->query($sql);

        if ($name == 0) {
            PrintJSON("", " Device NO: " . $this->devno . " is not available", 0);
            die();
        } else if (!is_numeric($this->devno)) {
            PrintJSON("", "Device NO is number only", 0);
            die();
        }
    }
    public function validateSimno()
    {
        $db = new db_mssql();
        $sql = "select * from cfg_device where device_sim='$this->simno' and object_id !='$this->objid'";
        $name = $db->query($sql);

        if ($name > 0) {
            PrintJSON("", " Sim NO: " . $this->simno . " already exist", 0);
            die();
        } else if (!is_numeric($this->simno)) {
            PrintJSON("", "Device sim is number only", 0);
            die();
        }
    }
    public function validateStamp()
    {
        $dateTime = DateTime::createFromFormat('Y-m-d h:i:s', $this->stamp);
        if (!$dateTime) {
            PrintJSON("", "stamp is not DateTime format", 0);
            die();
        }
    }
    public function validateEstamp()
    {
        $dateTime = DateTime::createFromFormat('Y-m-d h:i:s', $this->estamp);
        if (!$dateTime) {
            PrintJSON("", "expired is not DateTime format", 0);
            die();
        }
    }
    public function validateCinfo()
    {
        if (!is_numeric($this->cinfo)) {
            PrintJSON("", "Customer id is number only", 0);
            die();
        }
    }
    public function validateGinfo()
    {
        $db = new db_mssql();
        $sql = "select * from cfg_group where group_id='$this->ginfo' ";
        $name = $db->query($sql);

        if ($name == 0) {
            PrintJSON("", " group ID: " . $this->ginfo . " is not available!", 0);
            die();
        }
    }
    public function validateOflag()
    {
        $db = new db_mssql();
        $sql = "select * from cfg_object where object_flag=N'$this->oflag' and object_id !='$this->objid'";
        // echo $sql;die();
        $name = $db->query($sql);

        if ($name > 0) {
            PrintJSON("", " Object flag: " . $this->oflag . " already exist", 0);
            die();
        } else if ($this->oflag == "") {
            PrintJSON("", "Object Flag is empty", 0);
            die();
        }
    }

    public function validateZtime()
    {
        if ($this->ztime == "") {
            PrintJSON("", "Time Zone is not valid", 0);
            die();
        }
    }
    public function validateDriver()
    {
        if (empty($this->driver) || $this->driver ==" ") {
            PrintJSON("", " driver is empty!", 0);
            die();
        }
    }
}

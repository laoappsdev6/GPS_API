<?php

class PlaceModel
{

    public $zid;
    public $aname;
    public $acolor;
    public $atype;
    public $apts;
    public $zoom;
    public $es;
    public $ins;
    public function __construct($object, $needEmpty = false)
    {
        if (!$needEmpty) {
            if (!$object) {
                PrintJSON("", "data is empty", 0);
                die();
            }
        };
        foreach ($object as $property => $value) {
            if (property_exists('PlaceModel', $property)) {
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
        $sql = "select * from cfg_place where place_id='$this->zid' ";
        $name = $db->query($sql);
        
        if ($name == 0) {
            PrintJSON(""," place ID: ".$this->zid. " is not available!", 0);
            die();
        } 
    }
    function validate($p)
    {
        switch ($p) {
            case 'aname':
                $this->validateArea_name();
                break;
            case 'acolor':
                $this->validateArea_color();
                break;
            case 'atype':
                $this->validateArea_type();
                break;
            case 'apts':
                $this->validateArea_pts();
                break;
            case 'zoom':
                $this->validateZoom();
                break;
            case 'es':
                $this->validateEs();
                break;
            case 'ins':
                $this->validateIns();
                break;
        }
    }

    function validateArea_name()
    {
        if (strlen($this->aname) < 3) {
            PrintJSON("", "Area name is short ", 0);
            die();
        }
    }
    function validateArea_color()
    {
        $color = preg_match_all('/#(?:[0-9a-fA-F]{6})/', $this->acolor);
        if (!$color) {
            PrintJSON("", "Color is not valid ", 0);
            die();
        }
    }
    function validateArea_type()
    {
        if (!is_numeric($this->atype)) {
            PrintJSON("", "Area type is number only", 0);
            die();
        }
    }
    function validateArea_pts()
    {
        if ($this->apts == "") {
            PrintJSON("", "Area pts value invalid", 0);
            die();
        }
    }
    function validateZoom()
    {
        if (!is_numeric($this->zoom)) {
            PrintJSON("", "Area zoom is number only", 0);
            die();
        }
    }
    function validateEs()
    {
        if (!is_numeric($this->es)) {
            PrintJSON("", "Enable speed limit is number only", 0);
            die();
        }
    }
    function validateIns()
    {
        if (!is_numeric($this->ins)) {
            PrintJSON("", "Inside speed limit is number only", 0);
            die();
        }
    }

}

?>
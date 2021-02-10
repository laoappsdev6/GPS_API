<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../controllers/user.controller.php";
include_once "../models/user.model.php";

try {

    Initialization();

    $json = json_decode(file_get_contents('php://input'), true);

    $m = GetMethod();

    $control = new UserController();

    if ($m == "adduser") {
        $user = new UserModel($json, true);
        $user->checkAllProperties();
        $control->addUser($user);
    } else if ($m == "updateuser") {
        $user = new UserModel($json);
        $user->checkId();
        $user->checkAllProperties();
        $control->updateUser($user);
    } else if ($m == "deleteuser") {
        $user = new UserModel($json, true);
        $user->checkId();
        $control->deleteUser($user);
    } else if ($m == "userlist") {
        $user = new UserModel($json, true);
        $usrid = $user->usrid_;
        if (IsMyself()) {
            $usrid = $_SESSION["uid"];
        }
        $control->userlist($usrid);
    } else if ($m == "userlistpage") {
        $page = (object) $json;
        $control->UserListPage($page);
    } else if ($m == "userlist_one") {
        $id = (object) $json;
        $control->userList_one($id);
    }else if ($m == "userlistgroup") {
        $page = (object) $json;
        $control->UserListGroup($page);
    }else if ($m == "changepassword") {
        $page = (object) $json;
        $control->changePassword($page);
    } else {
        PrintJSON("", "Method not provided", 0);
    }
} catch (Exception $e) {
    print_r($e);

}

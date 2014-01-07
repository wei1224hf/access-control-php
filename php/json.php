<?php
if(!isset($_GET['class']))die('class missed!');
$class = htmlspecialchars($_REQUEST['class'],ENT_QUOTES);
include_once 'tools.php';

include_once 'basic_group.php';
include_once 'basic_user.php';
include_once 'basic_parameter.php';


$data = array();
if($class=='tools')         	 $data = tools::callFunction();

if($class=='basic_user')         $data = basic_user::callFunction();  
if($class=='basic_group')        $data = basic_group::callFunction();
if($class=='basic_parameter')    $data = basic_parameter::callFunction();

echo json_encode($data);

if(tools::$conn <> NULL)tools::closeConn();
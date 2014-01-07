<?php
$data = array("status"=>2,"msg"=>"");
$fileExt = explode('.',$_FILES["file"]["name"]);
$fileExt = end($fileExt);
$path =  "../file/upload/f".rand(10000, 99999).".".$fileExt;
if ($_FILES["file"]["error"] > 0){
    $data['msg'] = $_FILES["file"]["error"];
}
else{
    move_uploaded_file($_FILES["file"]["tmp_name"],$path);
    $data = array("status"=>1,"path"=>$path,"msg"=>"ok ".$_FILES["file"]["type"]." ".$_FILES["file"]["size"]);
    $_REQUEST["path"] = $path;
}

if(isset($_GET['class'])){
    $class = htmlspecialchars($_REQUEST['class'],ENT_QUOTES);
    include_once 'tools.php';
    include_once 'basic_group.php';
    include_once 'basic_user.php';
    
    $data = array();
    if($class=='basic_user')        	$data = basic_user::callFunction();  
    
    if(tools::$conn <> NULL)tools::closeConn();
}

echo json_encode($data);
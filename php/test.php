<?php 
// $filedir = "../file/icon16X16/";
// $allfiles = [];
// if ($dh = opendir($filedir)) {//打开目录并赋值一个目录句柄(directory handle)
// 	while (FALSE !== ($filestring = readdir($dh))) {//读取目录中的文件名
// 		if ($filestring != '.' && $filestring != '..' && $filestring != '.svn') {//如果不是.和..(每个目录下都默认有.和..)
// 			if (is_dir($filedir . $filestring)) {//该文件名是一个目录时
// 				continue;
// 			} else if (is_file($filedir . $filestring)) {

// 				//;$allfiles[] = $filedir . $filestring;
// 				$filename_ = substr($filestring, 0, strlen($filestring)-10).".png";
// 				copy($filedir . $filestring,$filedir . $filename_);
// 				if(strpos($filestring, "16X16")){
// 					unlink($filedir . $filestring);
// 				}				
// 			}
// 		}
// 	}
// }
// print_r($allfiles)
//phpinfo();
//session_start();
//print_r($_SESSION);
phpinfo();
?>
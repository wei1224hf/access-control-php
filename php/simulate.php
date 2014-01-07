<?php 

include_once "tools.php";
include_once 'basic_group.php';
include_once 'basic_user.php';

include_once '../libs/phpexcel/Classes/PHPExcel.php';
include_once '../libs/phpexcel/Classes/PHPExcel/IOFactory.php';
include_once '../libs/phpexcel/Classes/PHPExcel/Writer/Excel5.php';

class simulate{
	
	public static function basic_group($total){
		$t_return = array("status"=>"1","msg"=>"");
		$conn = tools::getConn();
	
		$sql = "delete from basic_group where type = '40' OR type = '30'";
		tools::query($sql,$conn);
		$sql = "delete from basic_node where tablename = 'basic_group'";		
		tools::query($sql,$conn);
		$sql = "select code,value from basic_parameter where reference = 'zone' and code like '3311__' order by code ";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}			
		
		tools::query("START TRANSACTION;",$conn);
		$count = 0;
		//县级市
		for($i=2;$i<count($data);$i++){
			$sql = "insert into basic_node(code,name,tablename) values ('".$data[$i]['code']."','".$data[$i]['value']."','basic_group'); ";			
			tools::query($sql,$conn);$count++;
			$sql = "insert into basic_node(code,name,tablename) values ('".$data[$i]['code']."-79','".$data[$i]['value']."水利管理','basic_group'); ";
			tools::query($sql,$conn);$count++;
			
			$sql = "insert into basic_group(code,name,id,type,status) values (
					'".$data[$i]['code']."-79-01'
					,'".$data[$i]['value']."县水利局'
					,'".(10000+$i)."'
					,'30'
					,'10'); ";
			tools::query($sql,$conn);$count++;
						
			$sql = "insert into basic_node(code,name,tablename) values ('".$data[$i]['code']."-7910','".$data[$i]['value']."防洪管理','basic_group'); ";
			tools::query($sql,$conn);
			$sql = "insert into basic_group(code,name,id,type,status) values (
					'".$data[$i]['code']."-7910-01'
					,'".$data[$i]['value']."县防汛办'
					,'".(20000+$i)."'
					,'30'
					,'10'); ";
			tools::query($sql,$conn);$count++;
					
			$sql = "insert into basic_node(code,name,tablename) values ('".$data[$i]['code']."-7921','".$data[$i]['value']."水库管理','basic_group'); ";
			tools::query($sql,$conn);$count++;
			//每个县3个水库,分别是 1个大型水库 1个 中型水库 1个小水库
			for($i2=1;$i2<=3;$i2++){
				$sql = "insert into basic_group(code,name,id,type,status) values (
					'".$data[$i]['code']."-7921-".( ($i-1)*10+$i2)."'
					,'".$data[$i]['value']."水库".rand(10000, 99999)."'
					,'".(30000+$i*10+$i2)."'
					,'30'
					,'10'); ";
				tools::query($sql,$conn);$count++;
			}
		}
		
		$data = array();
		$data[] = array(
				'code'=>'33'
				,'value'=>'浙江省'
		);
		$data[] = array(
				'code'=>'3311'
				,'value'=>'丽水市'
		);
		$data[] = array(
				'code'=>'3311-79'
				,'value'=>'丽水市水利管理业'
		);
		$data[] = array(
				'code'=>'3311-7921'
				,'value'=>'丽水市防汛业'
		);		
		for($i=0;$i<count($data);$i++){
			$sql = "insert into basic_node(code,name,tablename) values ('".$data[$i]['code']."','".$data[$i]['value']."','basic_group'); ";
			tools::query($sql,$conn);$count++;
		}
		
		$sql = "insert into basic_group(code,name,id,type,status) values (
					'3311-79-01'
					,'丽水市水利局'
					,'1000'
					,'30'
					,'10'); ";
		tools::query($sql,$conn);$count++;
			
		//4个科室
		for($i=1;$i<=4;$i++){
			$sql = "insert into basic_group(code,name,id,type,status) values (
					'3311-79-01-0'.$i
					,'丽水市水利局科室'.$i
					,'".(1000+$i)."'
					,'40'
					,'10'); ";
			tools::query($sql,$conn);$count++;
		}
		
		tools::query("COMMIT;",$conn);		
		
		$t_return['msg'] = "count: ".$count;
		return $t_return;
	}
	
	public static function basic_group___localzone(){
		$t_return = array("status"=>"1","msg"=>"");
		$conn = tools::getConn();
	
		$sql = "delete from basic_group where type = '40' OR type = '30'";
		tools::query($sql,$conn);
		$sql = "delete from basic_node where tablename = 'basic_group'";
		tools::query($sql,$conn);
		$sql = "select code,value from basic_parameter where reference = 'localzone' 
				and code like '3311%000' 
				and code not in('331100000000','331101000000')
				order by code ";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}
		
		$id_basic_group = tools::getTableId("basic_group");
		$id_oa_person = tools::getTableId("oa_person");
	
		$count = 0;
		$sqls = array();
		for($i=0;$i<count($data);$i++){
			$item = $data[$i];
			$code = $item['code'];
			
			//县级市
			$reg = '/^\d*000000$/';
			if(preg_match($reg,$code)){				
				$code = str_replace("000", "", $code);
				//1个水利局,1个防汛办公室,1个大型水库或中型水库
				$id_basic_group++;
				$dbRow = array(
					 'name'=>$item['value']."水利局".rand(1,100)
					,'code'=>$code."-79-01"
					,'id'=>$id_basic_group
					,'count_users'=>rand(4,20)
					,'type'=>'30'
					,'status'=>'10'
					,'remark'=>'basic_group___localzone'
					,'chief_id'=>rand($id_oa_person-100,$id_oa_person)						
				);
				$sql = "insert into basic_group (";
				$sql_ = ") values (";
				$keys = array_keys($dbRow);
				for($j2=0;$j2<count($keys);$j2++){
					$sql .= $keys[$j2].",";
					$sql_ .= "'".$dbRow[$keys[$j2]]."',";
				}
				$sql = substr($sql, 0,strlen($sql)-1);
				$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
				$sql = $sql.$sql_;
				$sql = strtolower($sql);				
				$sqls[] = $sql;$count++;
				
				$id_basic_group++;
				$dbRow = array(
					'name'=>$item['value']."防汛办".rand(1,100)
					,'code'=>$code."-79-02"
					,'id'=>$id_basic_group
					,'count_users'=>rand(4,20)
					,'type'=>'30'
					,'status'=>'10'
					,'remark'=>'basic_group___localzone'
					,'chief_id'=>rand($id_oa_person-100,$id_oa_person)
				);
				$sql = "insert into basic_group (";
				$sql_ = ") values (";
				$keys = array_keys($dbRow);
				for($j2=0;$j2<count($keys);$j2++){
					$sql .= $keys[$j2].",";
					$sql_ .= "'".$dbRow[$keys[$j2]]."',";
				}
				$sql = substr($sql, 0,strlen($sql)-1);
				$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
				$sql = $sql.$sql_;
				$sql = strtolower($sql);
				$sqls[] = $sql;$count++;	

				$id_basic_group++;
				$dbRow = array(
					'name'=>$item['value']."大型水库".rand(1,100)
					,'code'=>$code."-79-03"
					,'id'=>$id_basic_group
					,'count_users'=>rand(4,20)
					,'type'=>'30'
					,'status'=>'10'
					,'remark'=>'basic_group___localzone'
					,'chief_id'=>rand($id_oa_person-100,$id_oa_person)
				);
				$sql = "insert into basic_group (";
				$sql_ = ") values (";
				$keys = array_keys($dbRow);
				for($j2=0;$j2<count($keys);$j2++){
					$sql .= $keys[$j2].",";
					$sql_ .= "'".$dbRow[$keys[$j2]]."',";
				}
				$sql = substr($sql, 0,strlen($sql)-1);
				$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
				$sql = $sql.$sql_;
				$sql = strtolower($sql);
				$sqls[] = $sql;$count++;	

				$dbRow = array(
						'name'=>$item['value']
						,'code'=>$code
						,'tablename'=>'basic_group'
				);
				$sql = "insert into basic_node (";
				$sql_ = ") values (";
				$keys = array_keys($dbRow);
				for($j2=0;$j2<count($keys);$j2++){
					$sql .= $keys[$j2].",";
					$sql_ .= "'".$dbRow[$keys[$j2]]."',";
				}
				$sql = substr($sql, 0,strlen($sql)-1);
				$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
				$sql = $sql.$sql_;
				$sql = strtolower($sql);
				$sqls[] = $sql;$count++;
				
				$dbRow = array(
						'name'=>$item['value']."-水利管理行业"
						,'code'=>$code."-79"
						,'tablename'=>'basic_group'
				);
				$sql = "insert into basic_node (";
				$sql_ = ") values (";
				$keys = array_keys($dbRow);
				for($j2=0;$j2<count($keys);$j2++){
					$sql .= $keys[$j2].",";
					$sql_ .= "'".$dbRow[$keys[$j2]]."',";
				}
				$sql = substr($sql, 0,strlen($sql)-1);
				$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
				$sql = $sql.$sql_;
				$sql = strtolower($sql);
				$sqls[] = $sql;$count++;				
			}
			//街道 或者 乡镇
			else if(preg_match('/^\d*000$/',$code)){
				continue;
				$code = str_replace("000", "", $code);
				//居委会, 有义务上报				
				$id_basic_group++;
				$dbRow = array(
					'name'=>$item['value']."居委会".rand(1,100)
					,'code'=>$code."-94-01"
					,'id'=>$id_basic_group
					,'count_users'=>rand(4,20)
					,'type'=>'30'
					,'status'=>'10'
					,'remark'=>'basic_group___localzone'
					,'chief_id'=>rand($id_oa_person-100,$id_oa_person)
				);
				$sql = "insert into basic_group (";
				$sql_ = ") values (";
				$keys = array_keys($dbRow);
				for($j2=0;$j2<count($keys);$j2++){
					$sql .= $keys[$j2].",";
					$sql_ .= "'".$dbRow[$keys[$j2]]."',";
				}
				$sql = substr($sql, 0,strlen($sql)-1);
				$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
				$sql = $sql.$sql_;
				$sql = strtolower($sql);
				$sqls[] = $sql;$count++;
				
				$dbRow = array(
					'name'=>$item['value']
					,'code'=>$code
					,'tablename'=>'basic_group'
				);
				$sql = "insert into basic_node (";
				$sql_ = ") values (";
				$keys = array_keys($dbRow);
				for($j2=0;$j2<count($keys);$j2++){
					$sql .= $keys[$j2].",";
					$sql_ .= "'".$dbRow[$keys[$j2]]."',";
				}
				$sql = substr($sql, 0,strlen($sql)-1);
				$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
				$sql = $sql.$sql_;
				$sql = strtolower($sql);
				$sqls[] = $sql;$count++;	

				$dbRow = array(
						'name'=>$item['value']."-国家行政机构"
						,'code'=>$code."-94"
						,'tablename'=>'basic_group'
				);
				$sql = "insert into basic_node (";
				$sql_ = ") values (";
				$keys = array_keys($dbRow);
				for($j2=0;$j2<count($keys);$j2++){
					$sql .= $keys[$j2].",";
					$sql_ .= "'".$dbRow[$keys[$j2]]."',";
				}
				$sql = substr($sql, 0,strlen($sql)-1);
				$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
				$sql = $sql.$sql_;
				$sql = strtolower($sql);
				$sqls[] = $sql;$count++;				
			}
		}		
		
		$dbRow = array(
				'name'=>"浙江省"
				,'code'=>"33"
				,'tablename'=>'basic_group'
		);
		$sql = "insert into basic_node (";
		$sql_ = ") values (";
		$keys = array_keys($dbRow);
		for($j2=0;$j2<count($keys);$j2++){
			$sql .= $keys[$j2].",";
			$sql_ .= "'".$dbRow[$keys[$j2]]."',";
		}
		$sql = substr($sql, 0,strlen($sql)-1);
		$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
		$sql = $sql.$sql_;
		$sql = strtolower($sql);
		$sqls[] = $sql;$count++;
		
		$dbRow = array(
			'name'=>"丽水市"
			,'code'=>"3311"
			,'tablename'=>'basic_group'
		);
		$sql = "insert into basic_node (";
		$sql_ = ") values (";
		$keys = array_keys($dbRow);
		for($j2=0;$j2<count($keys);$j2++){
			$sql .= $keys[$j2].",";
			$sql_ .= "'".$dbRow[$keys[$j2]]."',";
		}
		$sql = substr($sql, 0,strlen($sql)-1);
		$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
		$sql = $sql.$sql_;
		$sql = strtolower($sql);
		$sqls[] = $sql;$count++;	

		$code = 3311;
		$id_basic_group++;
		$dbRow = array(
				'name'=>"丽水市防汛办".rand(1,100)
				,'code'=>$code."-79-02"
				,'id'=>$id_basic_group
				,'count_users'=>rand(4,20)
				,'type'=>'30'
				,'status'=>'10'
				,'remark'=>'basic_group___localzone'
				,'chief_id'=>rand($id_oa_person-100,$id_oa_person)
		);
		$sql = "insert into basic_group (";
		$sql_ = ") values (";
		$keys = array_keys($dbRow);
		for($j2=0;$j2<count($keys);$j2++){
			$sql .= $keys[$j2].",";
			$sql_ .= "'".$dbRow[$keys[$j2]]."',";
		}
		$sql = substr($sql, 0,strlen($sql)-1);
		$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
		$sql = $sql.$sql_;
		$sql = strtolower($sql);
		$sqls[] = $sql;$count++;
		
		$id_basic_group++;
		$dbRow = array(
				'name'=>"丽水市水利局".rand(1,100)
				,'code'=>$code."-79-01"
				,'id'=>$id_basic_group
				,'count_users'=>rand(4,20)
				,'type'=>'30'
				,'status'=>'10'
				,'remark'=>'basic_group___localzone'
				,'chief_id'=>rand($id_oa_person-100,$id_oa_person)
		);
		$sql = "insert into basic_group (";
		$sql_ = ") values (";
		$keys = array_keys($dbRow);
		for($j2=0;$j2<count($keys);$j2++){
			$sql .= $keys[$j2].",";
			$sql_ .= "'".$dbRow[$keys[$j2]]."',";
		}
		$sql = substr($sql, 0,strlen($sql)-1);
		$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
		$sql = $sql.$sql_;
		$sql = strtolower($sql);
		$sqls[] = $sql;$count++;
		
		$department = array("组织人事科","党政办公室","计划财务科","工程管理科","农田水利科","政策法规科","水资源管理科","建设计划科");
		for($i=1;$i<count($department);$i++){
			$id_basic_group++;
			$dbRow = array(
					'name'=>"丽水市水利局".$department[$i]
					,'code'=>$code."-79-01-".($i+10)
					,'id'=>$id_basic_group
					,'count_users'=>rand(4,20)
					,'type'=>'40'
					,'status'=>'10'
					,'remark'=>'basic_group___localzone'
					,'chief_id'=>rand($id_oa_person-100,$id_oa_person)
			);
			$sql = "insert into basic_group (";
			$sql_ = ") values (";
			$keys = array_keys($dbRow);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$dbRow[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sql = strtolower($sql);
			$sqls[] = $sql;$count++;
		}
		
		tools::transaction($conn);
		if(tools::$dbtype=="mssql"){
			$str = implode(";",$sqls);
			tools::query($str,$conn);
		}
		else{
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn);
			}
		}	
		tools::commit($conn);
		
		tools::updateTableId("basic_group");
		$t_return['msg'] = "count: ".$count;
		return $t_return;
	}	
	
	public static function st_stbprp_b(){
		$t_return = array("status"=>"1","msg"=>"");
		$conn = tools::getConn();
		$sql = "delete from st_stbprp_b";
		tools::query($sql,$conn);

		$count = 0;
		$types = array("mm","bb","dd","tt","dp","ss","pp","zz","rr","zg","zb");
		$sqls = array();
		for($i=0;$i<1000;$i++){
			$t_data = array(
				 "stcd"=>$i
				,"stnm"=>"Station".$i
				,'lgtd'=>simulate::$gis_x+(rand(1,100)>50?1:(-1)) * rand(10000,99999)/1000000
				,'lttd'=>simulate::$gis_y+(rand(1,100)>50?1:(-1)) * rand(10000,99999)/1000000					
				,"sttp"=>$types[rand(0,10)]
			);
			
			$sql = "insert into st_stbprp_b (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sql = strtolower($sql);
			$sqls[] = $sql;$count++;
		}
		
		$conn_write = tools::getConn();
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}
		
		$t_return['msg'] = "count: ".$count;

		return $t_return;
	}
	
	public static function zone___localzone(){
		$conn_read = tools::getConn();
		$conn_write = tools::getConn(TRUE);
		
		$sql = "delete from zone ";
		tools::updateTableId("zone");
		$id_zone = tools::getTableId("zone");
		tools::query($sql,$conn_write);$count = 0;
		$sql = "select code,value from basic_parameter where reference = 'localzone'
				and code not in('331100000000','331101000000')
				order by code ";
		$res = tools::query($sql,$conn_read);		
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}		
		//tools::closeConn($conn_read);
		//print_r($data);exit();
		
		$sqls = array();
		for($i=0;$i<count($data);$i++){
			$id_zone ++;
			$item = $data[$i];
			$code = $item['code'];
			$name = $item['value'];
			
			$x = simulate::$gis_x+(rand(1,100)>50?1:(-1)) * rand(10000,99999)/1000000;
			$y = simulate::$gis_y+(rand(1,100)>50?1:(-1)) * rand(10000,99999)/1000000;
			$dbRow = array(
				'id'=>$id_zone
				,'name'=>$item['value']
				,'code'=>$code		
				
				,'gis_2d_center_x'=>$x
				,'gis_2d_center_y'=>$y
				,'gis_2d_wkt'=>'POINT('.$x.' '.$y.')'
				,'landline'=>rand(100000,999999)	
				,'cellphone'=>rand(100000,999999).rand(100000,999999)
				,'subordinate'=>1
			);
			
			//县级市 或 城区
			if(preg_match('/^\d*000000$/',$code)){
				if (strpos($name,'区') !== false){
					$dbRow['type'] = 10;
				}
				else{
					$dbRow['type'] = 6;
				}				
				$dbRow['code'] = substr($code, 0,strrpos($code,"000000"));
				
				$dbRow['photo'] = "../file/upload/test/photo/zone/".rand(1,5).".jpg";
				$dbRow['directions'] = $item['value']."是一个大城市,拥有人口XXX,企业XXX,建筑XXXX<br/>
						是中国最著名的工商业城市和国际都会，是全国最大的综合性工业城市，亦为中国的经济、交通、科技、工业、金融、贸易、会展和航运中心。
						GDP总量居中国城市之首。
						<br/>
						港货物吞吐量和集装箱吞吐量均居世界第一，是一个良好的滨江滨海国际性港口。
						<br/>正致力于在2020年建成国际金融、航运和贸易中心";
				$dbRow['leaders'] = "市长:".simulate::randomName()."<br/>
						市委书记:".simulate::randomName()."<br/>
						副市长:".simulate::randomName().",".simulate::randomName().",".simulate::randomName()."<br/>";
				$dbRow['traffic'] = "铁路:".rand(1,4)."条<br/>
									公路:".rand(20,100)."条<br/>
									河流:".rand(3,5)."条<br/>
									";
				$dbRow['localism'] = "方言1,方言2,方言3";
				$dbRow['size'] = rand(10000,99999);
				$dbRow['gdp'] = rand(10000,99999);
				$dbRow['population'] = rand(10000,99999);
				$dbRow['company'] = rand(10000,99999);
				$dbRow['building'] = rand(10000,99999);
			}
			else if(preg_match('/^\d*000$/',$code)){
				if (strpos($name,'街道') !== false){
					$dbRow['type'] = 11;
				}
				else{
					$dbRow['type'] = 7;
				}
				$dbRow['code'] = substr($code, 0,strrpos($code,"000"));
				
				$dbRow['photo'] = "../file/upload/test/photo/zone/".rand(6,10).".jpg";
				$dbRow['directions'] = $item['value']."是一个大街道,拥有人口XXX,企业XXX,建筑XXXX<br/>
						位于下城区东南部，与上城、江干交接，东临贴沙河、西依东河、北靠大运河，南接庆春路与上城交界，三水相绕，环境宜人<b/>
						辖区总面积2.729平方公里，呈不规则块状，共辖珠儿潭、卖鱼桥、双荡弄、仓基、霞湾巷、仁和仓、长乐苑七个社区。<br/>
						街道办事处机构内设党政办公室、经济发展科、城市管理科、综治司法科、社会事务科、计划生育科，并建有资产管理中心、招商中心、劳动和社会保障站、社区服务中心等机构<br/>";
				$dbRow['leaders'] = "主任:".simulate::randomName()."<br/>
						书记:".simulate::randomName()."<br/>
						副市长:".simulate::randomName().",".simulate::randomName().",".simulate::randomName()."<br/>";
				$dbRow['traffic'] = "公路:".rand(20,100)."条<br/>
									 河流:".rand(3,5)."条<br/>
									";
				$dbRow['localism'] = "方言1,方言2,方言3";
				$dbRow['size'] = rand(1000,9999);
				$dbRow['gdp'] = rand(1000,9999);
				$dbRow['population'] = rand(1000,9999);
				$dbRow['company'] = rand(1000,9999);
				$dbRow['building'] = rand(1000,9999);
			}			
			else{					
				if (strpos($name,'社区') !== false){
					$dbRow['type'] = 12;
				}
				else{
					$dbRow['type'] = 8;
				}	
				$dbRow['subordinate'] = 0;
				
				$dbRow['photo'] = "../file/upload/test/photo/zone/".rand(11,15).".jpg";
				$dbRow['directions'] = $item['value']."是一个村庄,拥有人口XXX,企业XXX,建筑XXXX<br/>
						离市区19.8公里处，人口0.15万。<br/>
						四百年前有程、何、赖3姓居民在此聚居，后吴姓从福建省迁入，今存吴、何两姓。<br/>
						村处梅林圩西面，故名西门。聚落呈块状分布，建筑多为混凝土结构平房<br/>";
				$dbRow['leaders'] = "村长:".simulate::randomName()."<br/>
						书记:".simulate::randomName()."<br/>
						副村长:".simulate::randomName().",".simulate::randomName().",".simulate::randomName()."<br/>";
				$dbRow['traffic'] = "公路:".rand(2,10)."条<br/>
									 河流:".rand(1,2)."条<br/>
									";
				$dbRow['localism'] = "方言1,方言2,方言3";
				$dbRow['size'] = rand(100,999);
				$dbRow['gdp'] = rand(100,999);
				$dbRow['population'] = rand(100,999);
				$dbRow['company'] = rand(100,999);
				$dbRow['building'] = rand(100,999);				
			}
			
			$sql = "insert into zone (";
			$sql_ = ") values (";
			$keys = array_keys($dbRow);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$dbRow[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sql = strtolower($sql);
			//echo $sql;exit();
			$sqls[] = $sql;$count++;
		}

		$id_zone ++;
		$dbRow = array(
				'id'=>$id_zone
				,'name'=>"丽水市"
				,'code'=>"3311"				
				
				,'gis_2d_center_x'=>simulate::$gis_x+(rand(1,100)>50?1:(-1)) * rand(10000,99999)/1000000
				,'gis_2d_center_y'=>simulate::$gis_y+(rand(1,100)>50?1:(-1)) * rand(10000,99999)/1000000
				
				,'subordinate'=>1
		);		
		$dbRow['photo'] = "../file/upload/test/photo/zone/".rand(1,5).".jpg";
		$dbRow['directions'] = $item['value']."是一个大城市,拥有人口XXX,企业XXX,建筑XXXX<br/>
				是中国最著名的工商业城市和国际都会，是全国最大的综合性工业城市，亦为中国的经济、交通、科技、工业、金融、贸易、会展和航运中心。
				GDP总量居中国城市之首。
				<br/>
				港货物吞吐量和集装箱吞吐量均居世界第一，是一个良好的滨江滨海国际性港口。
				<br/>正致力于在2020年建成国际金融、航运和贸易中心";
		$dbRow['leaders'] = "市长:".simulate::randomName()."<br/>
				市委书记:".simulate::randomName()."<br/>
				副市长:".simulate::randomName().",".simulate::randomName().",".simulate::randomName()."<br/>";
		$dbRow['traffic'] = "机场:".rand(1,3)."个<br/>
							铁路:".rand(1,4)."条<br/>
							公路:".rand(20,100)."条<br/>
							河流:".rand(3,5)."条<br/>
							";
		$dbRow['localism'] = "方言1,方言2,方言3";
		$dbRow['size'] = rand(10000,99999);
		$dbRow['gdp'] = rand(10000,99999);
		$dbRow['population'] = rand(10000,99999);
		$dbRow['company'] = rand(10000,99999);
		$dbRow['building'] = rand(10000,99999);
		
		$sql = "insert into zone (";
		$sql_ = ") values (";
		$keys = array_keys($dbRow);
		for($j2=0;$j2<count($keys);$j2++){
			$sql .= $keys[$j2].",";
			$sql_ .= "'".$dbRow[$keys[$j2]]."',";
		}
		$sql = substr($sql, 0,strlen($sql)-1);
		$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
		$sql = $sql.$sql_;
		$sql = strtolower($sql);
		$sqls[] = $sql;$count++;
		
		$id_zone++;
		$dbRow = array(
				'name'=>"浙江省"
				,'code'=>"33"
			
				,'gis_2d_center_x'=>simulate::$gis_x+(rand(1,100)>50?1:(-1)) * rand(10000,99999)/1000000
				,'gis_2d_center_y'=>simulate::$gis_y+(rand(1,100)>50?1:(-1)) * rand(10000,99999)/1000000
				,'subordinate'=>1
				,'id'=>$id_zone
		);
		$sql = "insert into zone (";
		$sql_ = ") values (";
		$keys = array_keys($dbRow);
		for($j2=0;$j2<count($keys);$j2++){
			$sql .= $keys[$j2].",";
			$sql_ .= "'".$dbRow[$keys[$j2]]."',";
		}
		$sql = substr($sql, 0,strlen($sql)-1);
		$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
		$sql = $sql.$sql_;
		$sql = strtolower($sql);
		$sqls[] = $sql;$count++;		

		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);			
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				$sqls[$i] = str_replace("\n", "", $sqls[$i]);
				$sqls[$i] = str_replace("\t", "", $sqls[$i]);
				$sqls[$i] = str_replace("\r", "", $sqls[$i]);
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}
		
		tools::updateTableId("zone");
		$t_return['msg'] = "count: ".$count;
		tools::closeConn($conn_write);
		return $t_return;
	}
	
	//地级市
	public static function zone(){	
		$t_return = array("status"=>"1","msg"=>"");
		$conn = tools::getConn();
		$sql = "delete from zone ";
		tools::query($sql,$conn);
		
		$data = array();
		$sql = "select code,value as name from basic_parameter where reference = 'zone' and code like '3311__' order by code ";
		$res = tools::query($sql,$conn);
		while($temp = tools::fetch_assoc($res)){
			$temp['type'] = 6;
			$temp['subordinate'] = 5;
			$data[] = $temp;
		}
		
		$data2 = array();
		//遍历各个县级市
		for($i=2;$i<count($data);$i++){
			
			//县级市市中心的 街道
			for($i3=1;$i3<=5;$i3++){
				$data2[] = array(
						'name'=>'街道'.rand(1000,9999)
						,'code'=>$data[$i]['code']."A".$i3
						,'type'=>11
						,'subordinate'=>5
				);
				//市中心的 小区/小区/住宅区
				for($i4=1;$i4<=5;$i4++){
					$data2[] = array(
						'name'=>'小区'.rand(1000,9999)
						,'code'=>$data[$i]['code']."A".$i3."A".$i4
						,'type'=>12
						,'subordinate'=>0
					);
				}
			}			
			
			//镇
			for($i2=1;$i2<=5;$i2++){
				$data2[] = array(
					'name'=>'镇'.rand(1000,9999)
					,'code'=>$data[$i]['code']."0".$i2
					,'type'=>7
					,'subordinate'=>5
				);
				
				//村
				for($i3=1;$i3<=8;$i3++){
					$data2[] = array(
						'name'=>'村'.rand(1000,9999)
						,'code'=>$data[$i]['code']."0".$i2."0".$i3
						,'type'=>8
						,'subordinate'=>0
					);
				}	

				//街道
				for($i3=1;$i3<=3;$i3++){
					$data2[] = array(
						'name'=>'街道'.rand(1000,9999)
						,'code'=>$data[$i]['code']."0".$i2."A".$i3
						,'type'=>11
						,'subordinate'=>0
					);
				}
			}
		}
		
		//地级市市中心
		for($i3=1;$i3<=2;$i3++){

			$data2[] = array(
					'name'=>'城区'.rand(1000,9999)
					,'code'=>"3311A".$i3
					,'type'=>10
					,'subordinate'=>5
			);

			for($i4=1;$i4<=5;$i4++){
				$data2[] = array(
					'name'=>'街道'.rand(1000,9999)
					,'code'=>"3311A".$i3."A".$i4
					,'type'=>11
					,'subordinate'=>5
				);
				
				for($i5=1;$i5<=5;$i5++){
					$data2[] = array(
						'name'=>'小区'.rand(1000,9999)
						,'code'=>"3311A".$i3."A".$i4."A".$i5
						,'type'=>12
						,'subordinate'=>0
					);
				}
			}
		}
		
		$data[] = array(
			'code'=>'33'
			,'name'=>'浙江省'
		);	
		
		tools::query("START TRANSACTION;",$conn);
		$count = 0;
		for($i=0;$i<count($data);$i++){
			$t_data = $data[$i];
			$sql = "insert into zone (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			tools::query($sql,$conn);$count++;
		}
		
		for($i=0;$i<count($data2);$i++){
			$t_data = $data2[$i];
			$sql = "insert into zone (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			tools::query($sql,$conn);$count++;
		}

		tools::query("COMMIT;",$conn);$count++;
		
		$t_return['msg'] = "count: ".$count;
		return $t_return;
	}
	
	public static function zone_gis(){
		$t_return = array("status"=>"1","msg"=>"");
		$str = "";
		$file_handle = fopen("../sql/data_zone.sql", "r");
		while (!feof($file_handle)) {
			$line = fgets($file_handle);
			$str .= $line;
		}
		fclose($file_handle);
		
		$conn = tools::getConn();
		$count = 0;
		
		$sqls = explode(";", $str); 
		for($i=0;$i<count($sqls)-1;$i++){
			tools::query($sqls[$i],$conn);$count++;
			//break;
		}
		$t_return['msg'] = "count: ".$count;
		return $t_return;
	}
	
	public static function zone_9_gis(){
		$conn_read = tools::getConn();
		$conn_write = tools::getConn(TRUE);
		
		$sql = "select * from zone where code like '______' order by code";
		$res = tools::query($sql, $conn_read);
		$sqls = array();
		while($temp = tools::fetch_assoc($res)){
			$sql2 = "select * from zone where code like '".$temp['code']."___'";
			
			$res2 = tools::query($sql2, $conn_read);
			while($temp2 = tools::fetch_assoc($res2)){
				$x = floatval($temp['gis_2d_center_x']) +(rand(1,100)>50?1:(-1)) * rand(100,999)/10000;
				$y = floatval($temp['gis_2d_center_y']) +(rand(1,100)>50?1:(-1)) * rand(100,999)/10000;
				$sql_w = "update zone set gis_2d_center_x='".$x."',gis_2d_center_y='".$y."',gis_2d_wkt='POINT(".$x." ".$y.")' where code = '".$temp2['code']."'";
				$sqls[] = $sql_w;
			}
		}

		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}
		
		return array(
			'status'=>1
			,'msg'=>1
		);
	}
	
	public static function zone_12_gis(){
		$conn_read = tools::getConn();
		$conn_write = tools::getConn(TRUE);
	
		$sql = "select * from zone where code like '_________' order by code";
		$res = tools::query($sql, $conn_read);
		$sqls = array();
		while($temp = tools::fetch_assoc($res)){
			$sql2 = "select * from zone where code like '".$temp['code']."___'";
				
			$res2 = tools::query($sql2, $conn_read);
			while($temp2 = tools::fetch_assoc($res2)){
				$x = floatval($temp['gis_2d_center_x']) +(rand(1,100)>50?1:(-1))*3 * rand(100,999)/100000;
				$y = floatval($temp['gis_2d_center_y']) +(rand(1,100)>50?1:(-1))*3 * rand(100,999)/100000;
				$sql_w = "update zone set gis_2d_center_x='".$x."',gis_2d_center_y='".$y."',gis_2d_wkt='POINT(".$x." ".$y.")' where code = '".$temp2['code']."'";
				$sqls[] = $sql_w;
			}
		}
	
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}
	
		return array(
				'status'=>1
				,'msg'=>1
		);
	}

	public static function building(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$sql = "delete from building ";
		tools::query($sql,$conn);
		
		$sql = "select * from zone where subordinate = 0 ";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}
		
		$t_return = array("status"=>"1","msg"=>"count:".count($data),"data"=>$data);
		return $t_return;
	}
	
	public static function building_step(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn_write = tools::getConn();
		$code = $_REQUEST['code'];
		$len = strlen($code);
		
		
		tools::updateTableId("building");
		$id = tools::getTableId("building");
		$sqls = array();
		for($i=10;$i<=rand(20,30);$i++){
			$id ++;
			
			$height_bottom = 0;
			
			$rand = rand(1,100);
			$name = "建筑";
			$type = "";
			$floor = 0;
			$count_room = 0;
			$count_people = 0;
			$height_top = 0;
			$floors = array(1,2,3,
							5,10,20,50);
			
			//10% 高层住房
			if($rand>90){
				$type = 3105;
				$floor = $floors[rand(3,6)];
				$name = "高层住房";
			}
			//20% 底层住房
			else if($rand>70){
				$type = 3103;
				$floor = $floors[rand(0,2)];
				$name = "底层住房";
			}
			//20%小商铺
			else if($rand>50){
				$type = 311202;		
				$floor = $floors[rand(0,2)];
				$name = "小商铺";	
			}
			//5%办公大楼
			else if($rand>45){
				$type = 311201;	
				$floor = $floors[rand(3,6)];
				$name = "办公大楼";
			}
			//5% 制造业
			else if($rand>40){
				$type = 3201;
				$floor = $floors[rand(0,1)];
				$name = "制造业";
			}
			//5% 农业
			else if($rand>35){
				$type = 3302;		
				$floor = $floors[rand(0,1)];
				$name = "农业";
			}
			//3%学校
			else if($rand>32){
				$type = 340101;		
				$floor = $floors[rand(2,4)];
				$name = "学校";
			}
			//2%医院
			else if($rand>30){
				$type = 340102;
				$floor = $floors[rand(2,4)];
				$name = "医院";
			}
			//5%餐饮
			else if($rand>25){
				$type = 340201;			
				$floor = $floors[rand(0,2)];
				$name = "餐饮";
			}
			//4%超市
			else if($rand>21){
				$type = 340202;		
				$floor = $floors[rand(0,2)];
				$name = "超市";
			}
			//1%酒店
			else if($rand>20){
				$type = 340203;	
				$floor = $floors[rand(0,6)];
				$name = "酒店";
			}
			//2%娱乐场所 
			else if($rand>18){
				$type = 3403;
				$floor = $floors[rand(0,4)];
				$name = "娱乐场所 ";
			}
			//5%政府机构
			else if($rand>13){
				$type = 3111;
				$floor = $floors[rand(0,4)];
				$name = "政府机构 ";
			}
			//1%体育设施
			else if($rand>12){
				$type = 3404;
				$floor = 1;
				$name = "体育设施";
			}
			//1%环卫设施
			else if($rand>11){
				$type = 3406;
				$floor = 1;
				$name = "环卫设施";
			}
			//1%公共传媒设施
			else if($rand>10){
				$type = 3405;
				$floor = $floors[rand(2,4)];
				$name = "公共传媒设施";
			}
			//10% 额外的  底层住房
			else {
				$type = 3103;	
				$floor = $floors[rand(0,2)];
				$name = "底层住房";
			}
			
			$count_room = $floor * rand(3, 6);
			$count_people = $count_room * rand(0,4);
			$height_top = $floor * 3;			

			$rand = rand(1,100);
			$time_build = rand(0,5*365) ;
			//10%的房子 50年前建造
			if($rand>15)$time_built = rand(40*365/2,50*365/2);
			//20%的房子 40年前建造
			if($rand>35)$time_built =rand(30*365,40*365);
			//20%的房子 30年前建造
			if($rand>55)$time_built = rand(20*365,30*365);
			//40%的房子 20年前到5年前建造
			if($rand>95)$time_built = rand(5*365,20*365);
			$time_built = $time_build + rand(0,2*365);

			$area_ground = rand(50,1000);
			$area_use = $area_ground*rand(100,500)/100;
			$gis_2d_center_x = floatval($_REQUEST['gis_2d_center_x']) +(rand(1,100)>50?1:(-1))*3 * rand(100,999)/100000;
			$gis_2d_center_y = floatval($_REQUEST['gis_2d_center_y']) +(rand(1,100)>50?1:(-1))*3 * rand(100,999)/100000;
			$t_data = array(
				 'code'=>$code."-".$i
				,'name'=>$name.rand(10000, 99999)
				,'id'=>$id
				,'creater_id'=>1
				,'updater_id'=>1
				,'creater_group_code'=>1
				,'type'=>$type
				,'status'=>rand(1,5)
					
				,'time_built'=>date('Y-m-d',strtotime('-'.$time_built.' day'))
				,'time_build'=>date('Y-m-d',strtotime('-'.$time_build.' day'))
				,'money_built'=>rand(1000,9999)
					
				,'floor'=>$floor
				,'floor_underground'=>rand(0,3)
				,'count_room'=>$count_room
				,'count_people'=>$count_people
				,'count_resident'=>rand(0,20)
				,'address'=>'a long long long long long long long long address'
				,'directions'=>'a long long long long long long long long long long long long long long long long long long long long long long long long long long text'
					
				,'height_top'=>$height_top
				,'height_bottom'=>$height_bottom
				,'area_ground'=>$area_ground
				,'area_use'=>$area_use
					
				,'owner'=>simulate::randomName()
				,'manager'=>simulate::randomName()
				,'theuser'=>simulate::randomName()
					
				,'month_electricity'=>rand(1,1000)
				,'month_water'=>rand(1,1000)
					
				,'used'=>rand(1,3)
				,'structure'=>rand(1,4)
				,'level_net'=>rand(1,100)
				,'level_earthquake'=>rand(1,100)
				,'level_fire'=>rand(1,1000)
				,'level_lightning'=>rand(1,100)		

				,'gis_2d_center_x'=>$gis_2d_center_x
				,'gis_2d_center_y'=>$gis_2d_center_y
				,'gis_2d_wkt'=>"POINT(".$gis_2d_center_x." ".$gis_2d_center_y.")"

				,'photo'=>'../file/upload/test/photo/building/'.rand(1,10).".jpg"
				,'remark'=>'building'
			);
			
			$sql = "insert into building (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			
			$sqls[] = $sql;$count++;
		}
		
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);			
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}	

		tools::updateTableId("building");
		$t_return['msg'] = $count;
		return $t_return;
	}	
	
	public static function building_gis(){
		$t_return = array("status"=>"1","msg"=>"");
		$str = "";
		$file_handle = fopen("../sql/data_building.sql", "r");
		while (!feof($file_handle)) {
			$line = fgets($file_handle);
			$str .= $line;
		}
		fclose($file_handle);
	
		$conn = tools::getConn();
	
		$sqls = explode(";", $str);
		for($i=0;$i<count($sqls);$i++){
			tools::query($sqls[$i],$conn);$count++;
		}
		$t_return['msg'] = "count: ".$count;
		return $t_return;
	}
	
	public static function family(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$sql = "delete from family ";
		tools::query($sql,$conn);
	
		$sql = "select code,type from building where type in ('3103','3105') and code like '%1_'";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}
	
		$t_return = array("status"=>"1","msg"=>"count:".count($data),"data"=>$data);
		return $t_return;
	}
	
	public static function family_step(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn_write = tools::getConn();
		$code = $_REQUEST['code'];
		$type = $_REQUEST['type'];
	
		tools::updateTableId("family");
		$id = tools::getTableId("family");
		$len = 1;
		if($type=='3103')$len = rand(1,3);
		if($type=='3105')$len = rand(5,20);
		
		$sqls = array();$count = 0;
		for($i=10;$i<=$len+10;$i++){
			$id ++;
	
			$rand = rand(1,100);
			$time = date('Y-m-d', strtotime('-'.rand(0,5*365).' day'));
			//10%的房子 50年前建造
			if($rand>15)$time = date('Y-m-d', strtotime('-'.rand(40*365/2,50*365/2).' day'));
			//20%的房子 40年前建造
			if($rand>35)$time = date('Y-m-d', strtotime('-'.rand(30*365,40*365).' day'));
			//20%的房子 30年前建造
			if($rand>55)$time = date('Y-m-d', strtotime('-'.rand(20*365,30*365).' day'));
			//40%的房子 20年前到5年前建造
			if($rand>95)$time = date('Y-m-d', strtotime('-'.rand(5*365,20*365).' day'));
				
			$t_data = array(
				 'code'=>$code."-".$i
				,'name'=>'家庭'.rand(10000, 99999)
				,'id'=>$id
				,'creater_id'=>1
				,'updater_id'=>1
				,'creater_group_code'=>1
				,'type'=>rand(1,8)
				,'status'=>rand(1,2)
				,'remark'=>'family'
					
				,'owner'=>simulate::randomName()
				,'owner_person_id'=>rand(100,400)
				,'time_founded'=>$time
				,'time_over'=>$time
				,'types'=>rand(3,7)
				,'year_income'=>rand(1000,20000)
				,'count_member'=>rand(1,20)
				,'photo'=>"../file/upload/test/photo/family/".rand(1,10).".jpg"
				,'phone'=>"6239".rand(1000,9999)
				,'fax'=>"6239".rand(1000,9999)
					
				,'gis_2d_center_x'=>simulate::$gis_x+(rand(1,100)>50?1:(-1)) * rand(10000,99999)/1000000 
				,'gis_2d_center_y'=>simulate::$gis_y+(rand(1,100)>50?1:(-1)) * rand(10000,99999)/1000000 

				,'year_electricity'=>rand(1,9999)
				,'year_water'=>rand(1,9999)
			
			);
				
			$sql = "insert into family (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
				
			$sqls[] = $sql;$count ++;
		}

		$conn_write = tools::getConn();
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}		
	
		tools::updateTableId("family");
		$t_return['msg'] = "code: ".$code.", count: ".$count;
		return $t_return;
	}	
	
	public static function resident(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$sql = "delete from resident where remark = 'resident' ";
		tools::query($sql,$conn);
		$sql = "delete from family_member ";
		tools::query($sql,$conn);		
		$sql = "delete from oa_person where remark = 'resident' ";
		tools::query($sql,$conn);		
	
		$sql = "select code,type from family where code like '%1_' ";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}
	
		$t_return = array("status"=>"1","msg"=>"count:".count($data),"data"=>$data);
		return $t_return;
	}
	
	public static $jobs = array("洗碗工","扫大街","快递员","会计","计算机码畜","自由职业者","软件工程师","土木工程师","平面设计师","房产经纪人","建筑工人","公务员","司机");
	public static $job_titles = array("董事长","总经理","部门经理","普通职员","技术总监");
	public static $zonecodes = array(11,12,13,14,15,
		21,22,23,
		31,32,33,34,35,36,37,
		41,42,43,44,45,46,
		50,51,52,53,54,
		61,62,63,64,65,
		71
		);
	
	public static function resident_step(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$code = $_REQUEST['code'];
		$type = $_REQUEST['type'];
		$conn_write = tools::getConn();
		tools::updateTableId("resident");
		tools::updateTableId("oa_person");
		tools::updateTableId("family_member");		
		$id_resident = tools::getTableId("resident");
		$id_family_member = tools::getTableId("family_member");
		$id_oa_person = tools::getTableId("oa_person");
		
		$count = 0;
		$len = 1;
		$sqls = array();
		$relations = array();
		if($type==1){
			$len = 2;
			$relations = array("00","1029");
		}
		if($type==2){
			$len = rand(3,4);
			$relations = array("00","1029","1031","1035");
		}
		if($type==3){
			$len = 8;
			$relations = array("00","1029","1031","1035","1001","1002","1009","1010");
		}
		if($type==4){
			$len = 2;
			$relations = array("00","1031");
		}
		if($type==5){
			$len = rand(3,4);
			$relations = array("00","1029","1031","1035");
		}
		if($type==6){
			$len = 2;
			$relations = array("00","1029");
		}
		if($type==7){
			$len = 1;
			$relations = array("00");
		}
		if($type==8){
			$len = rand(5,20);
			$relations = array("00","9001");
		}
		
		for($i=10;$i<$len+10;$i++){
			$id_family_member ++;
			$id_oa_person ++;
			$id_resident ++;

			$relation = 0;
			if($type==8 && $i>10){
				$relation = '9001';
			}
			else{
				$relation = $relations[$i-10];
			}
			
			$birthday = '2000-01-01';
			$gender = "1";
			
			//本人 25 到 45 男
			if($relation=='00'){
				$gender = 1;
				$birthday = date('Y-m-d', strtotime('-'.rand(25*365,45*365).' day'));
			}
			//老婆 25 到 45 女
			if($relation=='1029'){
				$gender = 2;
				$birthday = date('Y-m-d', strtotime('-'.rand(25*365,45*365).' day'));
			}	
			//儿子 2 到 18 男
			if($relation=='1031'){
				$gender = 1;
				$birthday = date('Y-m-d', strtotime('-'.rand(2*365,18*365).' day'));
			}	
			//女儿 2 到 18 女
			if($relation=='1035'){
				$gender = 2;
				$birthday = date('Y-m-d', strtotime('-'.rand(2*365,18*365).' day'));
			}		
			//爸  50 到 90 男
			if($relation=='1001'){
				$gender = 1;
				$birthday = date('Y-m-d', strtotime('-'.rand(5*365,90*365).' day'));
			}	
			//妈  50 到 90 女
			if($relation=='1002'){
				$gender = 2;
				$birthday = date('Y-m-d', strtotime('-'.rand(5*365,90*365).' day'));
			}	
			//租客 
			if($relation=='9001'){
				$gender = rand(1,2);
				$birthday = date('Y-m-d', strtotime('-'.rand(16*365,50*365).' day'));
			}	

			if($type==6||$type==7){
				$birthday = date('Y-m-d', strtotime('-'.rand(5*365,90*365).' day'));
			}
			
			//oa_person
			$jobs = simulate::$jobs;
			$job_titles = simulate::$job_titles;
			$t_data = array(
				 'name'=>simulate::randomName()
				,'id'=>$id_oa_person
				,'creater_id'=>1
				,'updater_id'=>1
				,'creater_group_code'=>1
				,'type'=>1
				,'status'=>1
				,'remark'=>'resident'
					
				,'birthday'=>$birthday
				,'photo'=>'../file/upload/test/photo/resident/'.rand(1,10).".jpg"
				,'nationality'=>'中国'
				,'cardid'=>'330281'.str_replace("-", "", $birthday).rand(1000,9999)
				,'nation'=>(rand(1,100)>90)?rand(2,56):1
				,'card'=>rand(1,3)				
				,'marriage'=>rand(1,4)*10
				,'degree'=>rand(1,9)*10
				,'politically'=>rand(1,13)
				,'gender'=>rand(1,2)
					
				,'job'=>$jobs[rand(0,count($jobs)-1)]
				,'job_company'=>"某某某企业".rand(100000,999999)
				,'job_title'=>$job_titles[rand(0,count($job_titles)-1)]
				,'phone_job_company'=>"6239".rand(1000,9999)
				,'fax_job_company'=>"6239".rand(1000,9999)
				,'phone_home'=>"6239".rand(1000,9999)
				,'cellphone'=>'1831486'.rand(1000,9999)
						
			);
			
			$sql = "insert into oa_person (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[] = $sql;$count++;

			//resident
			$t_data = array(
				 'code'=>$code."-".$i
				,'name'=>simulate::randomName()
				
				,'id'=>$id_resident
				,'creater_id'=>1
				,'updater_id'=>1
				,'creater_group_code'=>1
				,'type'=>rand(1,4)
				,'status'=>rand(1,6)	
				,'remark'=>'resident'	

				,'time_in'=>$birthday
				,'time_out'=>date('Y-m-d', strtotime('-'.rand(-25*365,25*365).' day'))
				,'person_id'=>$id_oa_person
				,'salary'=>rand(0,1000000)
				,'types'=>rand(10,19)
				,'titles'=>'某某某头衔'
				,'degree_school'=>'某某某大学'
				,'degree_school_code'=>'a'
				,'address_birth_code'=>simulate::$zonecodes[rand(0,count(simulate::$zonecodes)-1)]
				,'job_code'=>chr(rand(65,84))				
				,'job_status'=>rand(1,3)
				,'danger'=>rand(1,100)
				,'protected'=>rand(1,100)
				,'socia_lsecurity'=>'33028'.rand(10000,90000)
				,'medicare'=>'33028'.rand(10000,90000)
				,'log_affair'=>'a long long long long long long long long long long long long long long long long text'
				,'log_penalty'=>'a long long long long long long long long long long long long long long long long text'
						
				,'gis_2d_center_x'=>simulate::$gis_x+(rand(1,100)>50?1:(-1)) * rand(10000,99999)/1000000 
				,'gis_2d_center_y'=>simulate::$gis_y+(rand(1,100)>50?1:(-1)) * rand(10000,99999)/1000000 					
			);
	
			$sql = "insert into resident (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[] = $sql;$count++;
			
			//family_member
			$t_data = array(
				 'code_family'=>$code
				,'code_resident'=>$code."-".$i
				,'id_oa_person'=>$id_oa_person
				,'relation'=>$relation
				,'status'=>rand(1,5)
				,'name'=>simulate::randomName()
				,'room'=>rand(1,5)
				,'time_in'=>$birthday
				,'time_out'=>date('Y-m-d', strtotime('-'.rand(5*365,20*365).' day'))
		
				,'id'=>$id_family_member			
			);
			
			$sql = "insert into family_member (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;			
			$sqls[] = $sql;$count++;
		}

		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}			

		tools::updateTableId("resident");
		tools::updateTableId("oa_person");
		tools::updateTableId("family_member");
		$t_return['msg'] = "code: ".$code.", count: ".$count;
		return $t_return;
	}	
	
	public static function randomName(){
		$name = "";
		$name_1 = "赵钱孙李周吴郑王冯陈楮卫蒋沈韩杨朱秦尤许何吕施张孔曹严华金魏陶姜戚谢邹喻柏水窦章云苏潘葛奚范彭郎鲁韦昌马苗凤花方俞任袁柳酆鲍史唐费廉岑薛雷贺倪汤";
		$name_2 ="安邦安福安歌安国安和安康安澜安民安宁安平安然安顺"
				."宾白宾鸿宾实彬彬彬炳彬郁斌斌斌蔚滨海波光波鸿波峻"
				."才捷才良才艺才英才哲才俊成和成弘成化成济成礼成龙"
				."德本德海德厚德华德辉德惠德容德润德寿德水德馨德曜"
				."飞昂飞白飞飙飞掣飞尘飞沉飞驰飞光飞翰飞航飞翮飞鸿"
				."刚豪刚洁刚捷刚毅高昂高岑高畅高超高驰高达高澹高飞"
				."晗昱晗日涵畅涵涤涵亮涵忍涵容涵润涵涵涵煦涵蓄涵衍"
				."嘉赐嘉德嘉福嘉良嘉茂嘉木嘉慕嘉纳嘉年嘉平嘉庆嘉荣"
				."开畅开诚开宇开济开霁开朗凯安凯唱凯定凯风凯复凯歌"
				."乐安乐邦乐成乐池乐和乐家乐康乐人乐容乐山乐生乐圣"
				."茂才茂材茂德茂典茂实茂学茂勋茂彦敏博敏才敏达敏叡"
				."朋兴朋义彭勃彭薄彭湃彭彭彭魄彭越彭泽彭祖鹏程鹏池";

		$name_1_ = intval( strlen($name_1)*rand(1,100)/100/3 );
		$name_2_ = intval( strlen($name_2)*rand(0,100)/100/3 );
		$name = substr($name_1, $name_1_*3,3).substr($name_2, $name_2_*3,6);
	
		return $name;
	}			
	
	public static function basic_user(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$sql = "delete from basic_user where type != '10' ";
		tools::query($sql,$conn);
		$sql = "delete from oa_person where remark = 'basic_user' ";
		tools::query($sql,$conn);		
		$sql = "delete from basic_group_2_user where group_code not in('10','99') ";
		tools::query($sql,$conn);
	
		$sql = "select code from basic_group where (type = 30 and code not like '3311-%') or (type = 40 and code like '3311-%') order by id desc ";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp['code'];
		}
	
		$t_return = array("status"=>"1","msg"=>"count:".count($data),"data"=>$data);
		return $t_return;
	}
	
	public static function basic_user_step(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$code = $_REQUEST['code'];
		$len = strlen($code);
	
		$id_basic_user = tools::getTableId("basic_user");
		$id_oa_person = tools::getTableId("oa_person");
		$id_resident = tools::getTableId("resident");

		$count = 0;
		$sqls = array();
		for($i=10;$i<=rand(15,25);$i++){
			$id_basic_user ++;
			$id_oa_person ++;
			$id_resident ++;
				
			$birthday = date('Y-m-d', strtotime('-'.rand(15*365,30*365).' day'));
			$title = array("镇长","副镇长","书记","科长","厅长","副科长","局长","副局长","村长","副厂长");
			
			$name = simulate::randomName(); 
			$t_data = array(
				 'name'=> $name
				,'id'=>$id_oa_person
				
				,'creater_id'=>1
				,'updater_id'=>1
				,'creater_group_code'=>1
				,'type'=>'20'
				,'status'=>'10'

				,'remark'=>'basic_user'
				,'birthday'=>$birthday
				,'photo'=>'../file/upload/test/photo/resident/'.rand(1,10).".jpg"
				,'nationality'=>'中国'
				,'nation'=>(rand(1,100)>90)?rand(2,56):1
				,'card'=>1
				,'cardid'=>'330281'.str_replace("-", "", $birthday).rand(1000,9999)
				,'marriage'=>rand(1,2)
				,'degree'=>rand(1,9)*10
				,'politically'=>rand(1,13)
				,'cellphone'=>"18314".rand(100000,999999)
				,'email'=>"email".rand(1000, 999)."@email.com"
				,'qq'=>rand(100000,999999)
				,'address'=>'Address , long long long long'
				,'address_birth'=>'Address , long long long long'
				,'gender'=>rand(1,2)
					
				,'job_title'=>$title[rand(0,count($title)-1)]
				,'job_company'=>"莫某某单位"
				,'phone_job_company'=>'825'.rand(10000,99999)
				,'fax_job_company'=>'825'.rand(10000,99999)
				,'phone_home'=>'825'.rand(10000,99999)
			);
				
			$sql = "insert into oa_person (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;				
			$sqls[] = $sql;$count++;
			
			$t_data = array(
				 'username'=>$code."--".$i
				,'password'=>md5( $code."--".$i )
				,'group_code'=>$code
				,'group_all'=>$code
				,'id_person'=>$id_oa_person
				,'id'=>$id_basic_user
		
				,'creater_id'=>1
				,'updater_id'=>1
				,'creater_group_code'=>1
				,'type'=>'20'
				,'status'=>'10'
			);
			
			$sql = "insert into basic_user (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;			
			$res = $sqls[] = $sql;$count++;
			if(!$res){
				$t_return['msg'] = mysql_error();
				$t_return['status'] = 2;
				return $t_return;
			}		

			$t_data = array(
				 'user_code'=>$code."--".$i
				,'group_code'=>$code
			);
				
			$sql = "insert into basic_group_2_user (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;				
			$sqls[] = $sql;$count++;		
		}
	
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "COMMIT transaction";
			$str = implode(";",$sqls);			
			tools::query($str,$conn);
		}
		else{
			tools::transaction($conn);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn);
			}
			tools::commit($conn);
		}
		

		tools::updateTableId("basic_user");
		tools::updateTableId("oa_person");
		tools::updateTableId("resident");
		$t_return['msg'] = "code: ".$code.", count: ".$count;
		return $t_return;
	}		

	public static function company(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$sql = "delete from company ";
		tools::query($sql,$conn);
	
		$sql = "SELECT
			building.id,
			building.code,
			building.name,
			building.gis_2d_center_x,
			building.gis_2d_center_y
			FROM
			building where type > '3111' and type < '3401' and code like '%-1_' ";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}
	
		$t_return = array("status"=>"1","msg"=>"count:".count($data),"data"=>$data);
		return $t_return;
	}
	
	public static function company_step(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();		
		$code = $_REQUEST['code'];
		$len = strlen($code);
		$conn_write = tools::getConn();
		tools::updateTableId("company");
		$id = tools::getTableId("company");
		$count = 0;
		$sqls = array();
		for($i=10;$i<=rand(10,25);$i++){
			$id ++;				
			
			$tax = explode("-", $code);
			$tax = $tax[0];
			$gis_2d_center_x = floatval($_REQUEST['gis_2d_center_x']) +(rand(1,100)>50?1:(-1))*3 * rand(100,999)/1000000;
			$gis_2d_center_y = floatval($_REQUEST['gis_2d_center_y']) +(rand(1,100)>50?1:(-1))*3 * rand(100,999)/1000000;			
			$t_data = array(
				 'code'=>$code."-".$i
				,'name'=>"某某某企业".rand(100000, 999999)
				,'code_dept'=>rand(100000, 999999)
				,'tax'=>$tax
				,'address'=>'long long long long long address.long long long long long address.long long long long long address.'
				,'owner'=>simulate::randomName()
				,'owner_id'=>rand(200,300)
				,'cellphone'=>'1831476'.rand(1000,9999)
				,'phone'=>'1831476'.rand(1000,9999)
				,'business'=>'some business'.rand(10000,99999)
				,'business_code'=>chr(rand(97,115))		
				,'gis_2d_center_x'=>$gis_2d_center_x
				,'gis_2d_center_y'=>$gis_2d_center_y
				,'code_building'=>$code
				,'time_in'=>date('Y-m-d', strtotime('-'.rand(15*365,30*365).' day'))
				,'time_out'=>date('Y-m-d', strtotime('-'.rand(15*365,30*365).' day'))
				,'photo'=>"../file/upload/test/photo/company/".rand(1,10).".jpg"
				,'count_employee'=>rand(3,20)
				,'property'=>rand(1000,5000000)
				,'turnover'=>rand(1000,5000000)
				,'id'=>$id
				,'type'=>rand(1,3)*10
				,'status'=>rand(1,3)*10
				,'remark'=>'company'
				,'creater_id'=>'1'
				,'updater_id'=>'1'
				,'creater_group_code'=>'1'						
			);
				
			$sql = "insert into company (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[]= $sql;$count++;
		}
	
		
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}
		
		tools::updateTableId("company");
		$t_return['msg'] = "code: ".$code.", count: ".$count;
		return $t_return;
	}		

	public static function oa_statistics(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$sql = "delete from oa_statistics where remark = 'oa_statistics' ";
		tools::query($sql,$conn);
		$sql = "delete from oa_statistics_detail where remark = 'oa_statistics' ";
		tools::query($sql,$conn);		
	
		$sql = "SELECT
			zone.name,
			zone.code,
			zone.id,
			zone.gis_2d_center_x,
			zone.gis_2d_center_y
			FROM
			zone
				where subordinate>0
		";
		$res = tools::query($sql,$conn);
		$data = array();
		tools::fetch_assoc($res);
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}
	
		$t_return = array("status"=>"1","msg"=>"count:".count($data),"data"=>$data);
		return $t_return;
	}
	
	public static function oa_statistics_step(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn_write = tools::getConn();
		$conn_read = tools::getConn(TRUE);
		$code = $_REQUEST['code'];
		$name = $_REQUEST['name'];
		$code = explode("-", $code);
		$code = $code[0];
	
		tools::updateTableId("oa_statistics");
		tools::updateTableId("oa_statistics_detail");		
		$id_oa_statistics = tools::getTableId("oa_statistics");		
		$id_oa_statistics_detail = tools::getTableId("oa_statistics_detail");
		$count = 0;$count2 = 0;$sqls = array();
		
		//3至5年
		for($i=10;$i<=rand(23,25);$i++){			
			$year = 2013-$i+9;
			
			//每年 1 到 4 起洪涝灾害			
			for($i2=10;$i2<=rand(10,19);$i2++){
				$id_oa_statistics ++;
				$gis_2d_center_x = floatval($_REQUEST['gis_2d_center_x']) +(rand(1,100)>50?1:(-1))*3 * rand(100,999)/100000;
				$gis_2d_center_y = floatval($_REQUEST['gis_2d_center_y']) +(rand(1,100)>50?1:(-1))*3 * rand(100,999)/100000;				
				$rowData = array(
					'id'=>$id_oa_statistics
					,'code'=>$code."-".$i."-".$i2
					,'name'=>$name."灾害统计报表 ".rand(1000,9999)
					,'status'=>rand(1,5)
					,'type'=>rand(1,8)
					,'creater_id'=>'1'
					,'updater_id'=>'1'
					,'creater_group_code'=>'10'
					,'time_created'=>'2000-11-11'
					,'time_lastupdated'=>'2000-11-11'
					,'count_updated'=>'1'
					,'remark'=>'oa_statistics'
			
					,'time_start'=>$year."-".(($i2-9)*1)."-0".rand(1,9)
					,'time_stop'=>$year."-".(($i2-9)*1)."-".rand(20,25)
					,'gis_2d_center_x'=>$gis_2d_center_x
					,'gis_2d_center_y'=>$gis_2d_center_y
					,'gis_2d_wkt'=>"POINT(".$gis_2d_center_x." ".$gis_2d_center_y.")"
				);
				if($rowData['type']==5)continue;
				$sql = "insert into oa_statistics (";
				$sql_ = ") values (";
				$keys = array_keys($rowData);
				for($j2=0;$j2<count($keys);$j2++){
					$sql .= $keys[$j2].",";
					$sql_ .= "'".$rowData[$keys[$j2]]."',";
				}
				$sql = substr($sql, 0,strlen($sql)-1);
				$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
				$sql = $sql.$sql_;				
				$sqls[] = $sql;$count ++;
				//header("Content-type: text/html; charset=utf-8");
				//echo $sql;exit();
				
				$len = strlen($code);
				$sql_select = "select code,value from basic_parameter where reference = 'localzone' 
						and code like  '".$code.(($len==6)?"______":"___")."' ";
				
				$res2 = tools::query($sql_select,$conn_read);
				if(!$res2){
					$t_return['msg'] = mysql_error();
					$t_return['status'] = 2;
					$t_return['sql'] = $sql_select;
					return $t_return;
				}
				$detail_index = 9;
				$rand_max = 10;
				$rand_min = 1;
				while($temp = tools::fetch_assoc($res2)){
					$id_oa_statistics_detail++;
					$detail_index++;
					if($detail_index==10)continue;
					$rowData = array(
						 'id'=>$id_oa_statistics_detail
						,'code'=>$code."-".$i."-".$i2."-".$detail_index
						,'name'=>$temp['value']
						,'status'=>rand(1, 2)
						,'type'=>rand(1, 2)
						,'creater_id'=>'1'
						,'updater_id'=>'1'
						,'creater_group_code'=>'10'
						,'time_created'=>'2000-11-11'
						,'time_lastupdated'=>'2000-11-11'
						,'count_updated'=>'1'
						,'remark'=>'oa_statistics'

						,'label1'=>$temp['code']
						,'num1'=>rand($rand_min,$rand_max)
						,'num2'=>rand($rand_min,$rand_max)
						,'num3'=>rand($rand_min,$rand_max)
						,'num4'=>rand($rand_min,$rand_max)
						,'num5'=>rand($rand_min,$rand_max)
						,'num6'=>rand($rand_min,$rand_max)
						,'num7'=>rand($rand_min,$rand_max)
						,'num8'=>rand($rand_min,$rand_max)
						,'num9'=>rand($rand_min,$rand_max)	

						,'num10'=>rand($rand_min,$rand_max)	
						,'num11'=>rand($rand_min,$rand_max)	
						,'num12'=>rand($rand_min,$rand_max)	
						,'num13'=>rand($rand_min,$rand_max)	
						,'num14'=>rand($rand_min,$rand_max)	
						,'num15'=>rand($rand_min,$rand_max)	
						,'num16'=>rand($rand_min,$rand_max)	
						,'num17'=>rand($rand_min,$rand_max)	
						,'num18'=>rand($rand_min,$rand_max)	
						,'num19'=>rand($rand_min,$rand_max)	
						,'num20'=>rand($rand_min,$rand_max)	
						,'num21'=>rand($rand_min,$rand_max)	
						,'num22'=>rand($rand_min,$rand_max)	
						,'num23'=>rand($rand_min,$rand_max)	
						,'num24'=>rand($rand_min,$rand_max)	
						,'num25'=>rand($rand_min,$rand_max)	
					);
					
					$sql = "insert into oa_statistics_detail (";
					$sql_ = ") values (";
					$keys = array_keys($rowData);
					for($j2=0;$j2<count($keys);$j2++){
						$sql .= $keys[$j2].",";
						$sql_ .= "'".$rowData[$keys[$j2]]."',";
					}
					$sql = substr($sql, 0,strlen($sql)-1);
					$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
					$sql = $sql.$sql_;
					$sqls[] = $sql;$count2 ++;

				}				
			}
		}
	
		if(tools::$dbtype=="mssql"){
			//array_unshift($sqls , 'begin transaction');
			//$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
			//print_r($sqls);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}		
		tools::updateTableId("oa_statistics");
		tools::updateTableId("oa_statistics_detail");
		$t_return['msg'] = "code: ".$code.", count: ".$count.", count2: ".$count2;
		return $t_return;
	}	
	
	public static function oa_plan(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$sql = "delete from oa_plan where remark = 'oa_plan' ";
		tools::query($sql,$conn);
	
		$sql = "select code from basic_group where type = '30' ";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp['code'];
		}
	
		$t_return = array("status"=>"1","msg"=>"count:".count($data),"data"=>$data);
		return $t_return;
	}
	
	public static function oa_plan_step(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$code = $_REQUEST['code'];
		$len = strlen($code);
		$conn_write = tools::getConn();
		tools::updateTableId("oa_plan");
		$id_oa_plan = tools::getTableId("oa_plan");
		//3 years, each 12 months, each 4 weeks
		$count = 0;	$sqls = array();
		for($i=10;$i<=15;$i++){
			
			$year = 1990+$i;
			$id_oa_plan ++;
			$data = array(
				 'code'=>$code."-".$i
				,'name'=>'年度工作安排'.rand(10000,99999)
				,'content'=>'a long long long string,a long long long string,a long long long string,a long long long string,a long long long string,a long long long string, '
				,'files'=>'../file/upload/test/photo/building/1.jpg'
				,'evaluate'=>rand(1,5)
				,'evaluate_remark'=>'审核通过'
				,'requirement'=>'文字描述的前提条件, a long long long string,a long long long string,a long long long string,a long long long string,'
				,'plan_time_start'=>$year."-01-01"
				,'plan_time_stop'=>$year."-12-30"
				,'plan_personhour'=>rand(100,5000)
				,'plan_money'=>rand(100,5000)
				,'plan_output'=>rand(100,5000)
				,'result_time_start'=>$year."-0".rand(1,5)."-0".rand(1,9)
				,'result_time_stop'=>($year+1)."-0".rand(1,9)."-0".rand(1,9)
				,'result_personhour'=>rand(100,5000)
				,'result_money'=>rand(100,5000)
				,'result_output'=>rand(100,5000)
				,'count_work'=>rand(100,5000)
				,'count_plan'=>rand(100,5000)
				,'group_incharge'=>$code
				,'user_incharge'=>rand(100,300)
				,'groups_participate'=>'ALL'
				,'groups_weight'=>'0'
				,'quotes'=>'0'
				,'quotes_weight'=>0
				,'deviation'=>rand(50,100)
				,'appraise'=>rand(50,100)
				,'id'=>$id_oa_plan
				,'creater_id'=>'1'
				,'updater_id'=>'1'
				,'creater_group_code'=>'1'
				,'time_created'=>"2011-01-01"
				,'time_lastupdated'=>"2011-01-01"
				,'count_updated'=>10
				,'type'=>rand(1,5)
				,'status'=>rand(1,5)
				,'remark'=>'oa_plan'
			);
			
			$sql = "insert into oa_plan (";
			$sql_ = ") values (";
			$keys = array_keys($data);
			for($j=0;$j<count($keys);$j++){
				$sql .= $keys[$j].",";
				$sql_ .= "'".$data[$keys[$j]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;			
			$sqls[] = $sql;$count++;			
						
			for($i2=1;$i2<=12;$i2++){				
				$month = ($i2<10)?"0".$i2:$i2;
				
				$id_oa_plan ++;
				$data["plan_time_start"] = $year."-".$month."-01";
				$data["plan_time_stop"] = $year."-".$month."-28";
				$data["result_time_start"] = $year."-".$month."-0".rand(1,5);
				$data["result_time_stop"] = $year."-".$month."-2".rand(7,9);	
				$data["code"] = $code."-".$i."-".$month;
				$data["name"] = "月度工作安排".rand(10000,99999);
				$data["id"] = $id_oa_plan;
				
				$sql = "insert into oa_plan (";
				$sql_ = ") values (";
				$keys = array_keys($data);
				for($j=0;$j<count($keys);$j++){
					$sql .= $keys[$j].",";
					$sql_ .= "'".$data[$keys[$j]]."',";
				}
				$sql = substr($sql, 0,strlen($sql)-1);
				$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
				$sql = $sql.$sql_;
				$sqls[] = $sql;$count++;
				
				
				for($i3=1;$i3<=22;$i3+=7){
					$day = ($i3<10)?"0".$i3:$i3;
					$day2 = (($i3+7)<10)?"0".($i3+7):($i3+7);
					
					$id_oa_plan ++;
					$data["plan_time_start"] = $year."-".$month."-".$day;
					$data["plan_time_stop"] = $year."-".$month."-".$day2;
					$data["result_time_start"] = $year."-".$month."-".$day;
					$data["result_time_stop"] = $year."-".$month."-".$day2;
					$data["code"] = $code."-".$i."-".$month."-".$day;
					$data["name"] = "周工作安排".rand(10000,99999);
					$data["id"] = $id_oa_plan;
					
					$sql = "insert into oa_plan (";
					$sql_ = ") values (";
					$keys = array_keys($data);
					for($j=0;$j<count($keys);$j++){
						$sql .= $keys[$j].",";
						$sql_ .= "'".$data[$keys[$j]]."',";
					}
					$sql = substr($sql, 0,strlen($sql)-1);
					$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
					$sql = $sql.$sql_;
					$sqls[] = $sql;$count++;					
				}
				
			}
		}
		
		$conn_write = tools::getConn();
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}		
	
		tools::updateTableId("oa_plan");
		$t_return['msg'] = "code: ".$code.", count: ".$count;
		return $t_return;
	}	

	public static function oa_work(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$sql = "delete from oa_work where remark = 'oa_work' ";
		tools::query($sql,$conn);
	
		$sql = "select code,result_time_start as time_start from oa_plan limit 40";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}
	
		$t_return = array("status"=>"1","msg"=>"count:".count($data),"data"=>$data);
		return $t_return;
	}
	
	
	public static function oa_work_step(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$code = $_REQUEST['code'];
		$time_start = $_REQUEST['time_start'];
		$time_start_arr = explode("-", $time_start);
		$conn_write = tools::getConn();
		tools::updateTableId("oa_work");
		$id_oa_work = tools::getTableId("oa_work");
		$sqls = array();$count = 0;
		
		for($i=1;$i<=7;$i++){
				
			$year = 1990+$i;
			$id_oa_work ++;
			$data = array(
				 'code'=>$code."-0".$i
				,'code_from'=>($i==1)?"0":($code."-0".($i-1))
				,'plan'=>$code
				,'title'=>"工作内容".rand(1000,9999)
				,'address'=>'a long long long address,a long long long address,a long long long address,a long long long address,'
				,'time'=>$time_start_arr[0]."-".$time_start_arr[1]."-".(intval($time_start_arr[2])+$i)
				,'hour'=>rand(1,4)
				,'content'=>'a long long long long string,a long long long long string,a long long long long string,a long long long long string,a long long long long string,'
				,'businesstype'=>rand(1,4)
				,'target'=>' '
				,'path_photo'=>"../file/upload/test/photo/building/".rand(1,10).".jpg"
				,'gis_lat'=>simulate::$gis_x+(rand(1,100)>50?1:(-1)) * rand(10000,99999)/1000000 
				,'gis_lot'=>simulate::$gis_y+(rand(1,100)>50?1:(-1)) * rand(10000,99999)/1000000 					
				,'id'=>$id_oa_work
				,'creater_id'=>'1'
				,'updater_id'=>'1'
				,'creater_group_code'=>'10'
				,'time_created'=>'2011-01-01'
				,'time_lastupdated'=>'2011-01-01'
				,'count_updated'=>'1'
				,'type'=>rand(1,4)
				,'status'=>rand(1,4)
				,'remark'=>'oa_work'				
			);
				
			$sql = "insert into oa_work (";
			$sql_ = ") values (";
			$keys = array_keys($data);
			for($j=0;$j<count($keys);$j++){
				$sql .= $keys[$j].",";
				$sql_ .= "'".$data[$keys[$j]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[] = $sql;$count++;	
		}
	
		$conn_write = tools::getConn();
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}		
		tools::updateTableId("oa_work");
		$t_return['msg'] = "code: ".$code.", count: ".$count;
		return $t_return;
	}	
	
	public static function hydro_emergency_team(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$sql = "delete from hydro_emergency_team where remark = 'hydro_emergency_team' ";
		tools::query($sql,$conn);
		$sql = "delete from hydro_emergency_team_member where remark = 'hydro_emergency_team' ";
		tools::query($sql,$conn);	
		$sql = "delete from oa_person where remark = 'hydro_emergency_team' ";
		tools::query($sql,$conn);			
	
		$sql = "select * from zone where subordinate = 0 order by code ";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}
	
		$t_return = array("status"=>"1","msg"=>"count:".count($data),"data"=>$data);
		return $t_return;
	}
	
	public static $gis_x = 119.91723;
	public static $gis_y = 28.46261;
	public static function hydro_emergency_team_step(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$code = $_REQUEST['code'];
		$x = $_REQUEST['x'];
		$y = $_REQUEST['y'];
		$len = strlen($code);
		$conn_write = tools::getConn();
		tools::updateTableId("hydro_emergency_team");
		tools::updateTableId("hydro_emergency_team_member");
		tools::updateTableId("oa_person");		
		$id_hydro_emergency_team = tools::getTableId("hydro_emergency_team");
		$id_hydro_emergency_team_member = tools::getTableId("hydro_emergency_team_member");
		$id_oa_person = tools::getTableId("oa_person");
		$count = 0;	$sqls = array();
		
		$id_hydro_emergency_team ++;
		$x = floatval($x) +(rand(1,100)>50?1:(-1))*5 * rand(100,999)/1000000;
		$y = floatval($y) +(rand(1,100)>50?1:(-1))*5 * rand(100,999)/1000000;
		$t_data = array(
			'code'=>$code."-"."10"
			,'name'=>"抢险队伍".rand(10000, 99999)
			,'id'=>$id_hydro_emergency_team
			,'creater_id'=>1
			,'updater_id'=>1
			,'creater_group_code'=>1
			,'type'=>'79-0'.rand(1,6)
			,'status'=>rand(1,3)
			,'remark'=>'hydro_emergency_team'
			
			,'contacter'=>simulate::randomName()
			,'chief_name'=>simulate::randomName()
			,'chief_code'=>$code."-"."10"."-10"
			,'cellphone'=>'183148'.rand(10000,99999)
			,'landline'=>'82-1177'.rand(10000,99999)
			,'directions'=>'队伍创建时间为 '.rand(1980,2012).'-'.rand(1,12).'-'.rand(1,27).',平均每年出勤次数  '.rand(1,100) .' 次.
					<br/>  拥有抢险物资为:
					<br/>  冲锋舟 2 艘,麻袋 40 只,铲子,20把,应急医疗箱 100份
					<br/>  团队主要负责的区域为 '.rand(10000,99999).' 乡镇到 XXXXXX 街道之间的 XX 米的 XX公路,XXX公路'
			,'address'=>'一个地址'
			,'gis_2d_center_x'=>$x 
			,'gis_2d_center_y'=>$y
			,'members'=>rand(5,40)
			,'photo'=>'../file/upload/test/photo/team/'.rand(1,10).'.jpg'
			,'expert'=>rand(1,5)
			,'responsibility'=>'关于队伍职能的更详细的介绍<br/>主要负责XXXXXX,XXXXX,XXX<br/>'
			,'time_founded'=>rand(1980,2000)."-".rand(10,12)."-".rand(10,27)
			,'has_supply'=>rand(0,1)
			,'supplement'=>'对于 物资储备的更详细的描述:<br/>有XXX多少,XXX多少,XXXX多少等等'
		);
		$sql = "insert into hydro_emergency_team (";
		$sql_ = ") values (";
		$keys = array_keys($t_data);
		for($j2=0;$j2<count($keys);$j2++){
			$sql .= $keys[$j2].",";
			$sql_ .= "'".$t_data[$keys[$j2]]."',";
		}
		$sql = substr($sql, 0,strlen($sql)-1);
		$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
		$sql = $sql.$sql_;
		$sqls[] = $sql;$count++;
		
		for($i=10;$i<=rand(13,30);$i++){
			$id_hydro_emergency_team_member ++;
			$id_oa_person ++;
			
			$export = array("医护","电器维修","司机","驾船","电工");
			$government_title = array("镇长","副镇长","村长","副村长","村支书","镇书记","无");
			$t_data = array(
				 "code"=>$code."-"."10-".$i
				,'name'=>simulate::randomName()
				,'id'=>$id_hydro_emergency_team_member
				,'creater_id'=>1
				,'updater_id'=>1
				,'creater_group_code'=>1
				,'type'=>rand(1,4)
				,'status'=>rand(1,3)
				,'remark'=>'hydro_emergency_team'

				,'cellphone'=>'183148'.rand(10000,99999)
				,'government_title'=>$government_title[rand(0, 6)]
				,'id_person'=>$id_oa_person
				,'expert'=>$export[rand(0, 4)]
			);
				
			$sql = "insert into hydro_emergency_team_member (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[] = $sql;$count++;
			
			$birthday = date('Y-m-d', strtotime('-'.rand(15*365,30*365).' day'));
			$t_data = array(
				 'name'=>simulate::randomName()
				,'id'=>$id_oa_person
				,'creater_id'=>1
				,'updater_id'=>1
				,'creater_group_code'=>1
				,'type'=>1
				,'status'=>1
				,'remark'=>'hydro_emergency_team'

				,'birthday'=>$birthday
				,'photo'=>'../file/upload/test/photo/resident/'.rand(1,10).".jpg"
				,'nationality'=>'中国'
				,'nation'=>(rand(1,100)>90)?rand(2,56):1
				,'card'=>1
				,'cardid'=>'330281'.str_replace("-", "", $birthday).rand(1000,9999)
				,'marriage'=>rand(1,2)*10
				,'degree'=>rand(1,9)*10
				,'politically'=>rand(1,13)
				,'cellphone'=>"18314".rand(100000,999999)
				,'email'=>"email".rand(1000, 999)."@email.com"
				,'qq'=>rand(100000,999999)
				,'address'=>'Address , long long long long'
				,'address_birth'=>'Address , long long long long'
				,'gender'=>rand(1,2)
					
				,'job_title'=>"某一种工作"
				,'job_company'=>"某一家单位"
				,'phone_job_company'=>'825'.rand(10000,99999)
				,'fax_job_company'=>'825'.rand(10000,99999)
				,'phone_home'=>'825'.rand(10000,99999)
			);
			
			$sql = "insert into oa_person (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[] = $sql;$count++;			
		}
		
		$conn_write = tools::getConn();
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}		
	
		tools::updateTableId("hydro_emergency_team");
		tools::updateTableId("hydro_emergency_team_member");
		tools::updateTableId("oa_person");
		$t_return['msg'] = "code: ".$code.", count: ".$count;
		$t_return['count'] = $count;
		return $t_return;
	}	
	
	public static function hydro_reservoir(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$sql = "delete from hydro_reservoir where remark = 'hydro_reservoir' ";
		tools::query($sql,$conn);
	
		$sql = "select * from zone where code like '______' ";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}
	
		$t_return = array("status"=>"1","msg"=>"count:".count($data),"data"=>$data);
		return $t_return;
	}
	
	public static function hydro_reservoir_step(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$code = $_REQUEST['code'];
		$len = strlen($code);
		$conn_write = tools::getConn();
		tools::updateTableId("hydro_reservoir");
		$id_hydro_reservoir = tools::getTableId("hydro_reservoir");
		$count = 0;
		$sqls = array();
		$types = array(0,1,2,3,11);
		for($i=10;$i<=rand(10,25);$i++){
			$id_hydro_reservoir ++;
				
			$gis_2d_center_x = floatval($_REQUEST['gis_2d_center_x']) +(rand(1,100)>50?1:(-1))*2 * rand(100,999)/10000;
			$gis_2d_center_y = floatval($_REQUEST['gis_2d_center_y']) +(rand(1,100)>50?1:(-1))*2 * rand(100,999)/10000;
			$t_data = array(
				 'id'=>$id_hydro_reservoir
				,'creater_id'=>'1'
				,'updater_id'=>'1'
				,'creater_group_code'=>'1'
				,'time_created'=>'2000-01-01'
				,'time_lastupdated'=>'2000-01-01'
				,'count_updated'=>'1'
				,'type'=>$types[rand(0,4)]
				,'status'=>'1'
				,'remark'=>'hydro_reservoir'
				,'code'=>$code.$i
				,'name'=>$_REQUEST['name']."水库".rand(1111,2222)
				,'summary'=>'这是一个水库'
				,'photo'=>"../file/upload/test/photo/hydro_reservoir/".rand(1,9).".jpg"
				,'cellphone'=>rand(100000,133333).rand(10000,99999)

				,'dam_gis_wkt'=>"POINT(".$gis_2d_center_x." ".$gis_2d_center_y.")"
				,'dam_code_building'=>'0'
				,'lake_gis_wkt'=>'0'
				,'lake_code_nature'=>'0'
				,'sluice_gis_wkt'=>'0'
				,'sluice_code_building'=>'0'
				,'gis_2d_center_x'=>$gis_2d_center_x
				,'gis_2d_center_y'=>$gis_2d_center_y

				,'farmland'=>rand(100,300)
				,'population'=>rand(100,300)
				,'road'=>rand(100,300)
				,'income'=>rand(100,300)
				,'collapse'=>rand(100,300)
				,'usefulness'=>1

				,'rain_total'=>rand(100,300)
				,'rain'=>rand(100,300)
				,'level_flood_design'=>rand(100,300)
				,'level_flood'=>rand(100,300)
				,'level_season'=>rand(100,300)
				,'level_'=>rand(100,300)
				,'level_dead'=>rand(100,300)
				,'capacity_total'=>rand(100,300)
				,'capacity'=>rand(100,300)
				,'capacity_dead'=>rand(100,300)
				,'discharge'=>rand(100,300)

				,'count_device'=>rand(1,5)
				,'defense'=>rand(1,5)
			
			);
	
			$sql = "insert into hydro_reservoir (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[]= $sql;$count++;
		}
	
	
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}
	
		tools::updateTableId("hydro_reservoir");
		$t_return['msg'] = "code: ".$code.", count: ".$count;
		return $t_return;
	}
	
	public static function hydro_responsibility(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$sql = "delete from hydro_responsibility where remark = 'hydro_responsibility' ";
		tools::query($sql,$conn);
	
		$sql = "select id,code,name,gis_2d_center_x,gis_2d_center_y from zone where code not like '____________' and code not like '__' and code not like '____' order by code ";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}
	
		$t_return = array("status"=>"1","msg"=>"count:".count($data),"data"=>$data);
		return $t_return;
	}
	
	public static function hydro_responsibility_step(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$code = $_REQUEST['code'];
		$len = strlen($code);
		$conn_write = tools::getConn();
		tools::updateTableId("hydro_responsibility");
		$id_hydro_responsibility = tools::getTableId("hydro_responsibility");
		$id_oa_person = tools::getTableId("oa_person");
		$id_basic_user = tools::getTableId("basic_user");
		$count = 0;
		$sqls = array();
		$types = array(
				11
				,12
				,13
				,14
				,15
				,16
				
				,9016
				,9017
				,9018
				,9019
								
						
		);
		
		$title = array("镇长","副镇长","书记","科长","厅长","副科长","局长","副局长","村长","副厂长");
		
		for($i=10;$i<=rand(10,25);$i++){
			$id_hydro_responsibility ++;
	
			$gis_2d_center_x = floatval($_REQUEST['gis_2d_center_x']) +(rand(1,100)>50?1:(-1))*2 * rand(100,999)/10000;
			$gis_2d_center_y = floatval($_REQUEST['gis_2d_center_y']) +(rand(1,100)>50?1:(-1))*2 * rand(100,999)/10000;
			$t_data = array(
				 'id'=>$id_hydro_responsibility
				,'creater_id'=>'1'
				,'updater_id'=>'1'
				,'creater_group_code'=>'10'
				,'time_created'=>'2011-01-01'
				,'time_lastupdated'=>'2011-01-02'
				,'count_updated'=>'2'
				,'type'=>$types[rand(0,count($types)-1)]
				,'status'=>rand(1,2)
				,'remark'=>'hydro_responsibility'
				,'code'=>$_REQUEST['code']."-".$i
				,'name'=>$_REQUEST['name']."防汛责任".rand(1,1000)

				,'director'=>simulate::randomName()
				,'id_person_director'=>rand($id_oa_person-100,$id_oa_person)
				,'title'=>$title[rand(0,count($title)-1)]
				,'department'=>$_REQUEST['name']
				,'cellphone'=>rand(134000,183000).rand(10000,99999)
					
				,'director2'=>simulate::randomName()
				,'id_person_director2'=>rand($id_oa_person-100,$id_oa_person)
				,'title2'=>$title[rand(0,count($title)-1)]
				,'department2'=>$_REQUEST['name']
				,'cellphone2'=>rand(134000,183000).rand(10000,99999)
				
				,'director3'=>simulate::randomName()
				,'id_person_director3'=>rand($id_oa_person-100,$id_oa_person)
				,'title3'=>$title[rand(0,count($title)-1)]
				,'department3'=>$_REQUEST['name']
				,'cellphone3'=>rand(134000,183000).rand(10000,99999)
				
				,'director4'=>simulate::randomName()
				,'id_person_director4'=>rand($id_oa_person-100,$id_oa_person)
				,'title4'=>$title[rand(0,count($title)-1)]
				,'department4'=>$_REQUEST['name']
				,'cellphone4'=>rand(134000,183000).rand(10000,99999)			
					
				,'photo'=>"../file/upload/test/photo/building/".rand(1,10).".jpg"
				,'id_region'=>rand(1,10)
				,'directions'=>'防汛责任制的简要说明<br/>
					责任范围描述:XXXXXXXXX,XXXX,XXX,XXX,XXX<br/>
					相关人物,部门,可调动的资源:XXX,XXX,XXX,XXX,XXXXXXX<br/>
					险情发生后,所需要遵循的事后处理流程为:XXX,XXX,XXX,XXXXXX,XXXXXXX'
				,'time_established'=>rand(2000,2013)."-".rand(1,11)."-".rand(1,30)
				,'gis_2d_center_x'=>$gis_2d_center_x
				,'gis_2d_center_y'=>$gis_2d_center_y
				,'gis_2d_wkt'=>"POINT(".$gis_2d_center_x." ".$gis_2d_center_y.")"
				,'count_inspection'=>rand(1,100)
				,'time_last_inspection'=>'2013-11-11'
				,'directions_region'=>'对于责任片区域的描述:<br/>
					应该会有图片:<img src="../file/upload/test/photo/building/9.jpg" /><br/>
					责任片含有的 人口,建筑,经济方式等等:XXX,XXX,XXX,XXXXXXX'
				,'log_inspection'=>'重大的巡检日志:<br/>
					XXXX年XX月,巡检时发现XX处有裂痕<br/>
					已经想上级提交报告,但是尚未维修,极其危险'
			);
	
			$sql = "insert into hydro_responsibility (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[]= $sql;$count++;
		}
	
	
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}
	
		tools::updateTableId("hydro_responsibility");
		$t_return['msg'] = "code: ".$code.", count: ".$count;
		return $t_return;
	}	
	
	public static function oa_department(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$sql = "delete from oa_department where remark = 'oa_department' ";
		tools::query($sql,$conn);
	
		$sql = "select code,name from zone where code like '____%' order by code ";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}
	
		$t_return = array("status"=>"1","msg"=>"count:".count($data),"data"=>$data);
		return $t_return;
	}
	
	public static function oa_department_step(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$code = $_REQUEST['code'];
		$name = $_REQUEST['name'];
		$len = strlen($code);
		$conn_write = tools::getConn();
		tools::updateTableId("oa_department");
		$id_oa_department = tools::getTableId("oa_department");
		$id_oa_person = tools::getTableId("oa_person");
		
		$count = 0;
		$sqls = array();
		$thrRate = 50;	
		if($len==4){
			$thrRate = 0;
		}
		else if($len==6){
			$thrRate = 25;
		}
		else if($len==9){
			$thrRate = 50;
		}		
		else if($len==12){
			$thrRate = 100;
		}		
		
		$t_data = array(
				'id'=>$id_oa_department
				,'creater_id'=>'1'
				,'updater_id'=>'1'
				,'creater_group_code'=>'10'
				,'time_created'=>'2011-01-01'
				,'time_lastupdated'=>'2011-01-02'
				,'count_updated'=>'2'
				,'type'=>rand(1,5)
				,'status'=>rand(1,2)
				,'remark'=>'oa_department'
				,'code'=>$code."-10"
				,'name'=>$name."行政中心"
		
				,'subordinate'=>0
				,'code_zone'=>$code
				,'chief'=>simulate::randomName()
				,'id_person_chief'=>rand($id_oa_person,$id_oa_person-100)
				,'cellphone'=>rand(134000,183000).rand(10000,99999)
				,'landline'=>rand(134000,183000).rand(10,99)
				,'fax'=>rand(134000,183000).rand(10,99)
				,'website'=>'http://www.baidu.com'
				,'other_contact'=>'其他联系方式<br/>其他电话: XXXXXX,XXXXXX,XXXXX<br/>其他手机: XXXXXX,XXXXXX,XXX'
				,'photo'=>'a'
				,'count_person'=>rand(100,200)
				,'count_person_inoffice'=>rand(100,200)
				,'office_place'=>'一个工作地点,位于,XXXXXXXXX,离街道XXX有多少距离<br/>或者是:<br/>某某某大楼,XX层,XXX号'
				,'directions'=>'简单的描述<br/>可以有照片,或者其他的东西'
		);
		$sql = "insert into oa_department (";
		$sql_ = ") values (";
		$keys = array_keys($t_data);
		for($j2=0;$j2<count($keys);$j2++){
			$sql .= $keys[$j2].",";
			$sql_ .= "'".$t_data[$keys[$j2]]."',";
		}
		$sql = substr($sql, 0,strlen($sql)-1);
		$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
		$sql = $sql.$sql_;
		$sqls[]= $sql;$count++;
		
		if(rand(1,100)>$thrRate+10){		
			for($i=11;$i<rand(13,25);$i++){
				$id_oa_department++;
				$t_data = array(
						'id'=>$id_oa_department
						,'creater_id'=>'1'
						,'updater_id'=>'1'
						,'creater_group_code'=>'10'
						,'time_created'=>'2011-01-01'
						,'time_lastupdated'=>'2011-01-02'
						,'count_updated'=>'2'
						,'type'=>rand(1,5)
						,'status'=>rand(1,2)
						,'remark'=>'oa_department'
						,'code'=>$code."-".$i
						,'name'=>$name."XXXX单位或公司企业"
				
						,'subordinate'=>0
						,'code_zone'=>$code
						,'chief'=>simulate::randomName()
						,'id_person_chief'=>rand($id_oa_person,$id_oa_person-100)
						,'cellphone'=>rand(134000,183000).rand(10000,99999)
						,'landline'=>rand(134000,183000).rand(10,99)
						,'fax'=>rand(134000,183000).rand(10,99)
						,'website'=>'http://www.baidu.com'
						,'other_contact'=>'其他联系方式<br/>其他电话: XXXXXX,XXXXXX,XXXXX<br/>其他手机: XXXXXX,XXXXXX,XXX'
						,'photo'=>'a'
						,'count_person'=>rand(100,200)
						,'count_person_inoffice'=>rand(100,200)
						,'office_place'=>'一个工作地点,位于,XXXXXXXXX,离街道XXX有多少距离<br/>或者是:<br/>某某某大楼,XX层,XXX号'
						,'directions'=>'简单的描述<br/>可以有照片,或者其他的东西'
				);
				
				$subordinate = 0;
				if(rand(1,100)>$thrRate+20)$subordinate = 1;
				$t_data['subordinate'] = $subordinate;
				
				$sql = "insert into oa_department (";
				$sql_ = ") values (";
				$keys = array_keys($t_data);
				for($j2=0;$j2<count($keys);$j2++){
					$sql .= $keys[$j2].",";
					$sql_ .= "'".$t_data[$keys[$j2]]."',";
				}
				$sql = substr($sql, 0,strlen($sql)-1);
				$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
				$sql = $sql.$sql_;
				$sqls[]= $sql;$count++;
				
				if($subordinate==0)continue;
				
				for($i2=11;$i2<rand(13,17);$i2++){
					$id_oa_department++;
					$t_data = array(
							'id'=>$id_oa_department
							,'creater_id'=>'1'
							,'updater_id'=>'1'
							,'creater_group_code'=>'10'
							,'time_created'=>'2011-01-01'
							,'time_lastupdated'=>'2011-01-02'
							,'count_updated'=>'2'
							,'type'=>rand(1,5)
							,'status'=>rand(1,2)
							,'remark'=>'oa_department'
							,'code'=>$code."-".$i.$i2
							,'name'=>$name."XXXX单位或公司企业某部门"
			
							,'subordinate'=>0
							,'code_zone'=>$code
							,'chief'=>simulate::randomName()
							,'id_person_chief'=>rand($id_oa_person,$id_oa_person-100)
							,'cellphone'=>rand(134000,183000).rand(10000,99999)
							,'landline'=>rand(134000,183000).rand(10,99)
							,'fax'=>rand(134000,183000).rand(10,99)
							,'website'=>'http://www.baidu.com'
							,'other_contact'=>'其他联系方式<br/>其他电话: XXXXXX,XXXXXX,XXXXX<br/>其他手机: XXXXXX,XXXXXX,XXX'
							,'photo'=>'a'
							,'count_person'=>rand(100,200)
							,'count_person_inoffice'=>rand(100,200)
							,'office_place'=>'一个工作地点,位于,XXXXXXXXX,离街道XXX有多少距离<br/>或者是:<br/>某某某大楼,XX层,XXX号'
							,'directions'=>'简单的描述<br/>可以有照片,或者其他的东西'
					);
					
					$subordinate = 0;
					if(rand(1,100)>$thrRate+30)$subordinate = 1;
					$t_data['subordinate'] = $subordinate;
					
					$sql = "insert into oa_department (";
					$sql_ = ") values (";
					$keys = array_keys($t_data);
					for($j2=0;$j2<count($keys);$j2++){
						$sql .= $keys[$j2].",";
						$sql_ .= "'".$t_data[$keys[$j2]]."',";
					}
					$sql = substr($sql, 0,strlen($sql)-1);
					$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
					$sql = $sql.$sql_;
					$sqls[]= $sql;$count++;
					
					if($subordinate==0)continue;
					
					for($i3=11;$i3<rand(13,17);$i3++){
						$id_oa_department++;
						$t_data = array(
								'id'=>$id_oa_department
								,'creater_id'=>'1'
								,'updater_id'=>'1'
								,'creater_group_code'=>'10'
								,'time_created'=>'2011-01-01'
								,'time_lastupdated'=>'2011-01-02'
								,'count_updated'=>'2'
								,'type'=>rand(1,5)
								,'status'=>rand(1,2)
								,'remark'=>'oa_department'
								,'code'=>$code."-".$i.$i2.$i3
								,'name'=>$name."XXXX单位或公司企业某部门某部门"
								
								,'subordinate'=>0
								,'code_zone'=>$code
								,'chief'=>simulate::randomName()
								,'id_person_chief'=>rand($id_oa_person,$id_oa_person-100)
								,'cellphone'=>rand(134000,183000).rand(10000,99999)
								,'landline'=>rand(134000,183000).rand(10,99)
								,'fax'=>rand(134000,183000).rand(10,99)
								,'website'=>'http://www.baidu.com'
								,'other_contact'=>'其他联系方式<br/>其他电话: XXXXXX,XXXXXX,XXXXX<br/>其他手机: XXXXXX,XXXXXX,XXX'
								,'photo'=>'a'
								,'count_person'=>rand(100,200)
								,'count_person_inoffice'=>rand(100,200)
								,'office_place'=>'一个工作地点,位于,XXXXXXXXX,离街道XXX有多少距离<br/>或者是:<br/>某某某大楼,XX层,XXX号'
								,'directions'=>'简单的描述<br/>可以有照片,或者其他的东西'
						);
					
						$sql = "insert into oa_department (";
						$sql_ = ") values (";
						$keys = array_keys($t_data);
						for($j2=0;$j2<count($keys);$j2++){
							$sql .= $keys[$j2].",";
							$sql_ .= "'".$t_data[$keys[$j2]]."',";
						}
						$sql = substr($sql, 0,strlen($sql)-1);
						$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
						$sql = $sql.$sql_;
						$sqls[]= $sql;$count++;
					}
				}
			}
		}
	
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}
	
		tools::updateTableId("oa_department");
		$t_return['msg'] = "code: ".$code.", count: ".$count;
		return $t_return;
	}	
	
	

	public static function oa_department_member(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$sql = "delete from oa_department_member where remark = 'oa_department_member' ";
		tools::query($sql,$conn);
	
		$sql = "select code from oa_department";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}
	
		$t_return = array("status"=>"1","msg"=>"count:".count($data),"data"=>$data);
		return $t_return;
	}
	
	public static function oa_department_member_step(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$code = $_REQUEST['code'];
		$name = $_REQUEST['name'];
		$len = strlen($code);
		$conn_write = tools::getConn();
		tools::updateTableId("oa_department_member");
		$id_oa_department_member = tools::getTableId("oa_department_member");
		$id_oa_person = tools::getTableId("oa_person");
	
		$count = 0;
		$sqls = array();
		$thrRate = 50;
		
		$title = array("镇长","副镇长","书记","科长","厅长","副科长","局长","副局长","村长","副厂长");
		for($i=11;$i<rand(13,25);$i++){
			$id_oa_department_member++;
			$t_data = array(
					'id'=>$id_oa_department_member
					,'creater_id'=>'1'
					,'updater_id'=>'1'
					,'creater_group_code'=>'10'
					,'time_created'=>'2011-01-01'
					,'time_lastupdated'=>'2011-01-02'
					,'count_updated'=>'2'
					,'type'=>rand(1,5)
					,'status'=>rand(1,2)
					,'remark'=>'oa_department_member'
					,'code'=>$code."-".$i
					,'name'=>simulate::randomName()

					,'cellphone'=>rand(1000,9999).rand(1000,9999).rand(100,999)
					,'cellphone2'=>rand(1000,9999).rand(1000,9999).rand(100,999)
					,'landline'=>rand(1000,9999).rand(1000,9999).rand(100,999)
					,'email'=>rand(1000,9999).rand(1000,9999).rand(100,999)."@a.com"
					,'title'=>$title[rand(0,count($title)-1)]
					,'directions'=>'说明描述什么的'
					,'id_person'=>rand($id_oa_person,$id_oa_person-100)
					,'duty'=>'说明描述什么的'
					
			);

			$sql = "insert into oa_department_member (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[]= $sql;$count++;			
		}
	
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}
	
		tools::updateTableId("oa_department");
		$t_return['msg'] = "code: ".$code.", count: ".$count;
		return $t_return;
	}	
	
	public static function check_kpi(){
		$t_return = array("status"=>"2","msg"=>"");
		$path_xls = "../sql/simulate_".tools::getConfigItem("IL8N").".xls";
		$PHPReader = PHPExcel_IOFactory::createReader('Excel5');
		$PHPReader->setReadDataOnly(true);
		$phpexcel = $PHPReader->load($path_xls);
		$conn_write = tools::getConn();
		
		tools::query("delete from check_kpi;",$conn_write);		
		$sqls = array();		
		$currentSheet = $phpexcel->getSheetByName("data_check_kpi");
		$row = $currentSheet->getHighestRow();
		for($i=2;$i<=$row;$i++){
			$t_data = array(
					 'id'=>trim($currentSheet->getCell('A'.$i)->getCalculatedValue())
					,'name'=>trim($currentSheet->getCell('B'.$i)->getCalculatedValue())
					,'code'=>trim( $currentSheet->getCell('C'.$i)->getCalculatedValue()) 
					,'description'=>trim($currentSheet->getCell('D'.$i)->getCalculatedValue())
					,'cent'=>trim($currentSheet->getCell('E'.$i)->getCalculatedValue())
					,'passline'=>trim($currentSheet->getCell('F'.$i)->getCalculatedValue())
					,'method'=>trim($currentSheet->getCell('G'.$i)->getCalculatedValue())
					,'target'=>trim($currentSheet->getCell('H'.$i)->getCalculatedValue())
					,'unit'=>trim($currentSheet->getCell('I'.$i)->getCalculatedValue())
					,'request'=>trim($currentSheet->getCell('J'.$i)->getCalculatedValue())
					,'request_num'=>trim($currentSheet->getCell('K'.$i)->getCalculatedValue())
					,'time_report_start'=>trim($currentSheet->getCell('L'.$i)->getCalculatedValue())
					,'time_report_end'=>trim($currentSheet->getCell('M'.$i)->getCalculatedValue())
					,'time_check_start'=>trim($currentSheet->getCell('N'.$i)->getCalculatedValue())
					,'time_check_end'=>trim($currentSheet->getCell('O'.$i)->getCalculatedValue())
					,'subordinate'=>trim($currentSheet->getCell('P'.$i)->getCalculatedValue())
			);
			$sql = "insert into check_kpi (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[]= $sql;$count++;
		}
		
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}
		
		tools::updateTableId("check_kpi");
		$t_return['msg'] = " count: ".$count;
		return $t_return;
	}
	
	public static function basic_parameter(){
		$t_return = array("status"=>"2","msg"=>"");
		$path_xls = "../sql/simulate_".tools::getConfigItem("IL8N").".xls";
		$PHPReader = PHPExcel_IOFactory::createReader('Excel5');
		$PHPReader->setReadDataOnly(true);
		$phpexcel = $PHPReader->load($path_xls);
		$conn_write = tools::getConn();
		tools::query("delete from basic_parameter where reference in ('zone','industry','localzone');",$conn_write);
	
		$currentSheet = $phpexcel->getSheetByName("data_basic_parameter");
		$row = $currentSheet->getHighestRow();
		for($i=2;$i<=$row;$i++){
			$code_read = $currentSheet->getCell('B'.$i)->getValue();
			if($code_read==null)continue;
			$code_read = str_replace("'", "", $code_read);
			$sql_insert = "insert into basic_parameter(value,code,reference) values ('".trim($currentSheet->getCell('A'.$i)->getValue())."','".$code_read."','".$currentSheet->getCell('C'.$i)->getValue()."')";
			$sqls[] = $sql_insert."\n";
			$count ++;
			//$res = tools::query($sql_insert,$conn);
		}
	
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}
	
		$t_return['msg'] = " count: ".$count;
		return $t_return;
	}	
	
	
	public static function oa_department__hydro(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$sql = "delete from oa_department where remark = 'oa_department__hydro' ";
		tools::query($sql,$conn);
	
		$sql = "select code,name from zone where code like '______' order by code ";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}
	
		$t_return = array("status"=>"1","msg"=>"count:".count($data),"data"=>$data);
		return $t_return;
	}
	
	public static function oa_department__hydro_step(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$code = $_REQUEST['code'];
		$name = $_REQUEST['name'];
		$len = strlen($code);
		$conn_write = tools::getConn();
		tools::updateTableId("oa_department");
		$id_oa_department = tools::getTableId("oa_department");
		$id_oa_person = tools::getTableId("oa_person");
	
		$count = 0;
		$sqls = array();

		$departments = array("政府行政中心","人民武装部","水务局","教育局","卫生局","公安局","民政局","财政局","住房和城乡建设局","交通运输局"
		,"农林局","供销社","国土局");
		
		for($i=0;$i<count($departments);$i++){
			$id_oa_department ++;
			$t_data = array(
					'id'=>$id_oa_department
					,'creater_id'=>'1'
					,'updater_id'=>'1'
					,'creater_group_code'=>'10'
					,'time_created'=>'2011-01-01'
					,'time_lastupdated'=>'2011-01-02'
					,'count_updated'=>'2'
					,'type'=>rand(1,5)
					,'status'=>rand(1,2)
					,'remark'=>'oa_department__hydro'
					,'code'=>$code."-94-".($i+10)
					,'name'=>$name.$departments[$i]
			
					,'subordinate'=>0
					,'code_zone'=>$code
					,'chief'=>simulate::randomName()
					,'id_person_chief'=>rand($id_oa_person,$id_oa_person-100)
					,'cellphone'=>rand(134000,183000).rand(10000,99999)
					,'landline'=>rand(134000,183000).rand(10,99)
					,'fax'=>rand(134000,183000).rand(10,99)
					,'website'=>'http://www.baidu.com'
					,'other_contact'=>'其他联系方式<br/>其他电话: XXXXXX,XXXXXX,XXXXX<br/>其他手机: XXXXXX,XXXXXX,XXX'
					,'photo'=>'a'
					,'count_person'=>rand(100,200)
					,'count_person_inoffice'=>rand(100,200)
					,'office_place'=>'一个工作地点,位于,XXXXXXXXX,离街道XXX有多少距离<br/>或者是:<br/>某某某大楼,XX层,XXX号'
					,'directions'=>'简单的描述<br/>可以有照片,或者其他的东西'
					,'types'=>'79-01'
			);
			
			$sql = "insert into oa_department (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[]= $sql;$count++;
		}
		
		$count_r_1_2 = rand(1,3);
		for($i=0;$i<$count_r_1_2;$i++){
			
			$id_oa_department ++;
			$t_data = array(
					'id'=>$id_oa_department
					,'creater_id'=>'1'
					,'updater_id'=>'1'
					,'creater_group_code'=>'10'
					,'time_created'=>'2011-01-01'
					,'time_lastupdated'=>'2011-01-02'
					,'count_updated'=>'2'
					,'type'=>rand(1,5)
					,'status'=>rand(1,2)
					,'remark'=>'oa_department__hydro'
					,'code'=>$code."-79-".($i+10)
					,'name'=>$name."大中型水库".$i
						
					,'subordinate'=>0
					,'code_zone'=>$code
					,'chief'=>simulate::randomName()
					,'id_person_chief'=>rand($id_oa_person,$id_oa_person-100)
					,'cellphone'=>rand(134000,183000).rand(10000,99999)
					,'landline'=>rand(134000,183000).rand(10,99)
					,'fax'=>rand(134000,183000).rand(10,99)
					,'website'=>'http://www.baidu.com'
					,'other_contact'=>'其他联系方式<br/>其他电话: XXXXXX,XXXXXX,XXXXX<br/>其他手机: XXXXXX,XXXXXX,XXX'
					,'photo'=>'a'
					,'count_person'=>rand(100,200)
					,'count_person_inoffice'=>rand(100,200)
					,'office_place'=>'一个工作地点,位于,XXXXXXXXX,离街道XXX有多少距离<br/>或者是:<br/>某某某大楼,XX层,XXX号'
					,'directions'=>'简单的描述<br/>可以有照片,或者其他的东西'
					,'types'=>'79-03'
			);
				
			$sql = "insert into oa_department (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[]= $sql;$count++;
		}
		
		$count_r_3_4 = rand(3,5);
		for($i=0;$i<$count_r_3_4;$i++){
				
			$id_oa_department ++;
			$t_data = array(
					'id'=>$id_oa_department
					,'creater_id'=>'1'
					,'updater_id'=>'1'
					,'creater_group_code'=>'10'
					,'time_created'=>'2011-01-01'
					,'time_lastupdated'=>'2011-01-02'
					,'count_updated'=>'2'
					,'type'=>rand(1,5)
					,'status'=>rand(1,2)
					,'remark'=>'oa_department__hydro'
					,'code'=>$code."-79-".($i+20)
					,'name'=>$name."小型水库".$i
		
					,'subordinate'=>0
					,'code_zone'=>$code
					,'chief'=>simulate::randomName()
					,'id_person_chief'=>rand($id_oa_person,$id_oa_person-100)
					,'cellphone'=>rand(134000,183000).rand(10000,99999)
					,'landline'=>rand(134000,183000).rand(10,99)
					,'fax'=>rand(134000,183000).rand(10,99)
					,'website'=>'http://www.baidu.com'
					,'other_contact'=>'其他联系方式<br/>其他电话: XXXXXX,XXXXXX,XXXXX<br/>其他手机: XXXXXX,XXXXXX,XXX'
					,'photo'=>'a'
					,'count_person'=>rand(100,200)
					,'count_person_inoffice'=>rand(100,200)
					,'office_place'=>'一个工作地点,位于,XXXXXXXXX,离街道XXX有多少距离<br/>或者是:<br/>某某某大楼,XX层,XXX号'
					,'directions'=>'简单的描述<br/>可以有照片,或者其他的东西'
					,'types'=>'79-04'
			);
		
			$sql = "insert into oa_department (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[]= $sql;$count++;
		}
		
		$count_d = rand(3,5);
		for($i=0;$i<$count_d;$i++){
		
			$id_oa_department ++;
			$t_data = array(
					'id'=>$id_oa_department
					,'creater_id'=>'1'
					,'updater_id'=>'1'
					,'creater_group_code'=>'10'
					,'time_created'=>'2011-01-01'
					,'time_lastupdated'=>'2011-01-02'
					,'count_updated'=>'2'
					,'type'=>rand(1,5)
					,'status'=>rand(1,2)
					,'remark'=>'oa_department__hydro'
					,'code'=>$code."-79-".($i+40)
					,'name'=>$name."堤坝".$i
		
					,'subordinate'=>0
					,'code_zone'=>$code
					,'chief'=>simulate::randomName()
					,'id_person_chief'=>rand($id_oa_person,$id_oa_person-100)
					,'cellphone'=>rand(134000,183000).rand(10000,99999)
					,'landline'=>rand(134000,183000).rand(10,99)
					,'fax'=>rand(134000,183000).rand(10,99)
					,'website'=>'http://www.baidu.com'
					,'other_contact'=>'其他联系方式<br/>其他电话: XXXXXX,XXXXXX,XXXXX<br/>其他手机: XXXXXX,XXXXXX,XXX'
					,'photo'=>'a'
					,'count_person'=>rand(100,200)
					,'count_person_inoffice'=>rand(100,200)
					,'office_place'=>'一个工作地点,位于,XXXXXXXXX,离街道XXX有多少距离<br/>或者是:<br/>某某某大楼,XX层,XXX号'
					,'directions'=>'简单的描述<br/>可以有照片,或者其他的东西'
					,'types'=>'79-05'
			);
		
			$sql = "insert into oa_department (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[]= $sql;$count++;
		}
	
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}
	
		tools::updateTableId("oa_department");
		$t_return['msg'] = "code: ".$code.", count: ".$count;
		return $t_return;
	}
	
	
	public static function oa_department_member__hydro(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$sql = "delete from oa_department_member where remark = 'oa_department_member__hydro' ";
		tools::query($sql,$conn);
	
		$sql = "select code,types from oa_department where remark = 'oa_department__hydro'";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}
	
		$t_return = array("status"=>"1","msg"=>"count:".count($data),"data"=>$data);
		return $t_return;
	}
	
	public static function oa_department_member__hydro_step(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$code = $_REQUEST['code'];
		$name = $_REQUEST['name'];
		$len = strlen($code);
		$conn_write = tools::getConn();
		tools::updateTableId("oa_department_member");
		$id_oa_department_member = tools::getTableId("oa_department_member");
		$id_oa_person = tools::getTableId("oa_person");
	
		$count = 0;
		$sqls = array();
		$thrRate = 50;
	
		$title = array("局长","副局长","科员","干部","主任","副主任");
		for($i=11;$i<rand(14,19);$i++){
			$id_oa_department_member++;
			$t_data = array(
					'id'=>$id_oa_department_member
					,'creater_id'=>'1'
					,'updater_id'=>'1'
					,'creater_group_code'=>'10'
					,'time_created'=>'2011-01-01'
					,'time_lastupdated'=>'2011-01-02'
					,'count_updated'=>'2'
					,'type'=>rand(1,5)
					,'status'=>rand(1,2)
					,'remark'=>'oa_department_member__hydro'
					,'code'=>$code."-".$i
					,'name'=>simulate::randomName()
	
					,'cellphone'=>rand(1000,9999).rand(1000,9999).rand(100,999)
					,'cellphone2'=>rand(1000,9999).rand(1000,9999).rand(100,999)
					,'landline'=>rand(1000,9999).rand(1000,9999).rand(100,999)
					,'email'=>rand(1000,9999).rand(1000,9999).rand(100,999)."@a.com"
					,'title'=>$title[rand(0,count($title)-1)]
					,'directions'=>'说明描述什么的'
					,'id_person'=>rand($id_oa_person,$id_oa_person-100)
					,'duty'=>'说明描述什么的'	
					,'types'=>$_REQUEST['types']		
			);
	
			$sql = "insert into oa_department_member (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[]= $sql;$count++;
		}
	
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}
	
		tools::updateTableId("oa_department");
		$t_return['msg'] = "code: ".$code.", count: ".$count;
		return $t_return;
	}
	
	public static function materiel(){
		$t_return = array("status"=>"2","msg"=>"");
		$count = 0;
		$path_xls = "../sql/simulate_".tools::getConfigItem("IL8N").".xls";
		$PHPReader = PHPExcel_IOFactory::createReader('Excel5');
		$PHPReader->setReadDataOnly(true);
		$phpexcel = $PHPReader->load($path_xls);
		$conn_write = tools::getConn();
		tools::query("delete from materiel ;",$conn_write);
		
		$currentSheet = $phpexcel->getSheetByName("data_materiel");
		$id = tools::getTableId("materiel");
		$highestColumm = $currentSheet->getHighestColumn();
		$highestColumm_ = ord($highestColumm);
		$highestRow = $currentSheet->getHighestRow();
		$columns = array();
		for($i=65;$i<=$highestColumm_;$i++){
			$column = $currentSheet->getCell(chr($i).'1')->getCalculatedValue();
			$columns[] = $column;
		}		
		
    	$items = array();
    	$sqls = array();
    	for($i=3;$i<=$highestRow;$i++){
    		$id ++;
    		$item = array();
    		for($i2=65;$i2<=$highestColumm_;$i2++){
    			$value = $currentSheet->getCell(chr($i2).$i)->getCalculatedValue();
    			$item[$columns[$i2-65]] = $value; 
    		}
    		$items[] = $item;
    		$item['id'] = $id;
    		$item['description'] = 'materiel';
    		
    		$sql = "insert into materiel (";
    		$sql_ = ") values (";
    		$keys = array_keys($item);
    		for($i2=0;$i2<count($keys);$i2++){
    			$sql .= $keys[$i2].",";
    			$sql_ .= "'".$item[$keys[$i2]]."',";
    		}
    		$sql = substr($sql, 0,strlen($sql)-1);
    		$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
    		$sql = $sql.$sql_;
    		$sqls[]= $sql;
    		$count++;
    	}
	
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}
		
		$t_return['msg'] = " count: ".$count;
		return $t_return;
	}
	

	public static function storehouse(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$conn = tools::getConn();
		$sql = "delete from storehouse where remark = 'storehouse' ";
		tools::query($sql,$conn);
	
		$sql = " select id,code,name,gis_2d_center_x,gis_2d_center_y from zone where code like '______' order by code ";
		$res = tools::query($sql,$conn);
		$data = array();
		while($temp = tools::fetch_assoc($res)){
			$data[] = $temp;
		}
	
		$t_return = array("status"=>"1","msg"=>"count:".count($data),"data"=>$data);
		return $t_return;
	}
	
	public static function storehouse_step(){
		$t_return = array("status"=>"1","msg"=>"");
		$datas = array();
		$code = $_REQUEST['code'];
		$x = $_REQUEST['gis_2d_center_x'];
		$y = $_REQUEST['gis_2d_center_y'];
		$len = strlen($code);		
		
		$conn_read = tools::getConn();
		$conn_write = tools::getConn(TRUE);
		tools::updateTableId("storehouse");
		tools::updateTableId("storehouse_inventory");
		$id_storehouse = tools::getTableId("storehouse");
		$id_storehouse_inventory = tools::getTableId("storehouse_inventory");
		$id_oa_person = tools::getTableId("oa_person");
		$id_basic_user = tools::getTableId("basic_user");
		
		$sql_mt_type = "select * from materiel order by code";		
		$res = tools::query($sql_mt_type, $conn_read);
		$mt_type = array();
		while ($temp = tools::fetch_assoc($res)){
			$mt_type[] = $temp;
		}
		$count = 0;	$sqls = array();
	
		
		for($r = 0;$r<rand(2,5);$r++){
			$id_storehouse ++;
			$x = floatval($x) +(rand(1,100)>50?1:(-1))*5 * rand(100,999)/100000;
			$y = floatval($y) +(rand(1,100)>50?1:(-1))*5 * rand(100,999)/100000;
			$t_data = array(
					'code'=>$code."-".(10+$r)
					,'name'=>$_REQUEST['name']."-物资储备仓库".rand(1000,9999)
					,'id'=>$id_storehouse
					,'creater_id'=>1
					,'updater_id'=>1
					,'creater_group_code'=>1
					,'type'=>'79-0'.rand(1,3)
					,'status'=>rand(1,3)
					,'remark'=>'storehouse'
					,'time_created'=>date('Y-m-d')
					,'time_lastupdated'=>date('Y-m-d')
					,'count_updated'=>rand(1,10)			
			
					,'gis_2d_center_x'=>$x
					,'gis_2d_center_y'=>$y
					,'id_building'=>'0'
					,'id_user_manager'=>rand($id_basic_user-10,$id_basic_user)
					,'manager'=>simulate::randomName()
					,'gov_leader'=>simulate::randomName()
					,'traffic'=>rand(1,3)
					,'id_os_person'=>rand($id_oa_person-10,$id_oa_person)
					,'cellphone'=>rand(1000,9999).rand(1000,9999).rand(100,999)
					,'landline'=>'82-'.rand(1000,9999).rand(1000,9999)
					,'fax'=>'82-'.rand(1000,9999).rand(1000,9999)
					,'total_price'=>rand(1000,9999)
					,'types'=>rand(1,3)
					,'time_founded'=>rand(1980,2000)."-".rand(10,12)."-".rand(10,27)
					,'area'=>rand(1000,9999)
					,'photo'=>'../file/upload/test/photo/storehouse/'.rand(1,10).'.jpg'
					,'directions'=>'对这个物资仓库的描述<br/>
							存储有XXX物资,XXX物资,XXXXX物资.<br/>
							曾经发生过 XXX 次火灾<br/>
							一些额外的特殊说明:<br/>
							运输方式: XXX,存货方式: XXXXX	'			
			);
			$sql = "insert into storehouse (";
			$sql_ = ") values (";
			$keys = array_keys($t_data);
			for($j2=0;$j2<count($keys);$j2++){
				$sql .= $keys[$j2].",";
				$sql_ .= "'".$t_data[$keys[$j2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[] = $sql;$count++;
			
			
			for($i=0;$i<count($mt_type);$i++){
				$id_storehouse_inventory ++;
				$count = rand(1,100);
				$price = rand(1000,9999);
				$price_total = $count * $price;
				$t_data = array(
					'code'=>$code."-".(10+$r)."-".(100+$i)
					,'name'=>$mt_type[$i]['name'].rand(10000,2000)
					,'id'=>$id_storehouse_inventory
					,'creater_id'=>1
					,'updater_id'=>1
					,'creater_group_code'=>1
					,'type'=>'79-0'.rand(1,3)
					,'status'=>rand(1,3)
					,'remark'=>'storehouse'
					,'time_created'=>date('Y-m-d')
					,'time_lastupdated'=>date('Y-m-d')
					,'count_updated'=>rand(1,10)		

					,'code_materiel'=>$mt_type[$i]['code']
					,'count'=>$count
					,'place'=>'货架 '.rand(100,200)."行".rand(1,5)."列".rand(1,5)
					,'price'=>$price
					,'total_price'=>$price_total
					,'directions'=>'对这个物资的描述'
					,'photo'=>'../file/upload/test/photo/materiel/'.rand(1,30).'.jpg'
						
				);
				
				$sql = "insert into storehouse_inventory (";
				$sql_ = ") values (";
				$keys = array_keys($t_data);
				for($j2=0;$j2<count($keys);$j2++){
					$sql .= $keys[$j2].",";
					$sql_ .= "'".$t_data[$keys[$j2]]."',";
				}
				$sql = substr($sql, 0,strlen($sql)-1);
				$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
				$sql = $sql.$sql_;
				$sqls[] = $sql;$count++;
			}
		}		
	
		$conn_write = tools::getConn();
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}
	
		tools::updateTableId("storehouse");
		tools::updateTableId("storehouse_inventory");
		$t_return['msg'] = "code: ".$code.", count: ".$count;
		$t_return['count'] = $count;
		return $t_return;
	}
	
	public static function oa_statistics_detail__readexcel(){
		$t_return = array("status"=>"2","msg"=>"");
		$count = 0;
		$path_xls = "../sql/simulate_".tools::getConfigItem("IL8N").".xls";
		$PHPReader = PHPExcel_IOFactory::createReader('Excel5');
		$PHPReader->setReadDataOnly(true);
		$phpexcel = $PHPReader->load($path_xls);
		$conn_write = tools::getConn();
		tools::query("delete from oa_statistics_detail where remark = 'oa_statistics_detail__readexcel' ;",$conn_write);
		tools::query("delete from oa_statistics where remark = 'oa_statistics_detail__readexcel' ;",$conn_write);
	
		$currentSheet = $phpexcel->getSheetByName("data_oa_statistics_detail");
		$id = tools::getTableId("oa_statistics_detail");
		$highestColumm = $currentSheet->getHighestColumn();
		$highestColumm_ = ord($highestColumm);
		$highestRow = $currentSheet->getHighestRow();
		$columns = array();
		for($i=65;$i<=$highestColumm_;$i++){
			$column = $currentSheet->getCell(chr($i).'1')->getCalculatedValue();
			$columns[] = $column;
		}
	
		$items = array();
		$sqls = array();
		for($i=3;$i<=$highestRow;$i++){
			$value = $currentSheet->getCell("A".$i)->getCalculatedValue();
			if($value==NULL || $value=="") continue;
			$id ++;
			$item = array();
			for($i2=65;$i2<=$highestColumm_;$i2++){
				$value = $currentSheet->getCell(chr($i2).$i)->getCalculatedValue();
				$item[$columns[$i2-65]] = $value;
			}
			$items[] = $item;
			$item['id'] = $id;
			$item['creater_id'] = 1;
			$item['updater_id'] = 1;
			$item['creater_group_code'] = 1;
			$item['time_created'] = date('Y-m-d');
			$item['time_lastupdated'] = date('Y-m-d');
			$item['count_updated'] = 1;
			$item['status'] = 1;
			$item['remark'] = 'oa_statistics_detail__readexcel';
	
			$sql = "insert into oa_statistics_detail (";
			$sql_ = ") values (";
			$keys = array_keys($item);
			for($i2=0;$i2<count($keys);$i2++){
				$sql .= $keys[$i2].",";
				$sql_ .= "'".$item[$keys[$i2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[]= $sql;
			$count++;
		}
		
		
		$currentSheet = $phpexcel->getSheetByName("data_oa_statistics");
		$id = tools::getTableId("oa_statistics");
		$highestColumm = $currentSheet->getHighestColumn();
		$highestColumm_ = ord($highestColumm);
		$highestRow = $currentSheet->getHighestRow();
		$columns = array();
		for($i=65;$i<=$highestColumm_;$i++){
			$column = $currentSheet->getCell(chr($i).'1')->getCalculatedValue();
			$columns[] = $column;
		}
		
		for($i=3;$i<=$highestRow;$i++){
			$value = $currentSheet->getCell("A".$i)->getCalculatedValue();
			if($value==NULL || $value=="") continue;
			$id ++;
			$item = array();
			for($i2=65;$i2<=$highestColumm_;$i2++){
				$value = $currentSheet->getCell(chr($i2).$i)->getCalculatedValue();
				$item[$columns[$i2-65]] = $value;
			}
			$items[] = $item;
			$item['id'] = $id;
			$item['creater_id'] = 1;
			$item['updater_id'] = 1;
			$item['creater_group_code'] = 1;
			$item['time_created'] = date('Y-m-d');
			$item['time_lastupdated'] = date('Y-m-d');
			$item['count_updated'] = 1;
			$item['remark'] = 'oa_statistics_detail__readexcel';
		
			$sql = "insert into oa_statistics (";
			$sql_ = ") values (";
			$keys = array_keys($item);
			for($i2=0;$i2<count($keys);$i2++){
				$sql .= $keys[$i2].",";
				$sql_ .= "'".$item[$keys[$i2]]."',";
			}
			$sql = substr($sql, 0,strlen($sql)-1);
			$sql_ = substr($sql_, 0,strlen($sql_)-1).")";
			$sql = $sql.$sql_;
			$sqls[]= $sql;
			$count++;
		}
	
		if(tools::$dbtype=="mssql"){
			array_unshift($sqls , 'begin transaction');
			$sqls[] = "commit transaction";
			$str = implode(";",$sqls);
			tools::query($str,$conn_write);
		}
		else{
			tools::transaction($conn_write);
			for($i=0;$i<count($sqls);$i++){
				tools::query($sqls[$i], $conn_write);
			}
			tools::commit($conn_write);
		}
	
		$t_return['msg'] = " count: ".$count;
		return $t_return;
	}
}

$functionName = $_REQUEST['function'];
$data = array();

if($functionName=="basic_group"){
	$data = simulate::basic_group(2000);
}
if($functionName=="basic_group___localzone"){
	$data = simulate::basic_group___localzone();
}
else if($functionName=="basic_user"){
	$data = simulate::basic_user(2000);
}
else if($functionName=="basic_user_step"){
	$data = simulate::basic_user_step(2000);
}
else if($functionName=="st_stbprp_b"){
	$data = simulate::st_stbprp_b(2000);
}
else if($functionName=="zone_gis"){
	$data = simulate::zone_gis();
}
else if($functionName=="zone_9_gis"){
	$data = simulate::zone_9_gis();
}
else if($functionName=="zone_12_gis"){
	$data = simulate::zone_12_gis();
}
else if($functionName=="zone"){
	$data = simulate::zone();
}
else if($functionName=="zone___localzone"){
	$data = simulate::zone___localzone();
}
else if($functionName=="building"){
	$data = simulate::building();
}
else if($functionName=="building_step"){
	$data = simulate::building_step();
}
else if($functionName=="building_gis"){
	$data = simulate::building_gis();
}
else if($functionName=="family"){
	$data = simulate::family();
}
else if($functionName=="family_step"){
	$data = simulate::family_step();
}
else if($functionName=="resident"){
	$data = simulate::resident();
}
else if($functionName=="resident_step"){
	$data = simulate::resident_step();
}
else if($functionName=="company"){
	$data = simulate::company();
}
else if($functionName=="company_step"){
	$data = simulate::company_step();
}
else if($functionName=="oa_plan"){
	$data = simulate::oa_plan();
}
else if($functionName=="oa_plan_step"){
	$data = simulate::oa_plan_step();
}
else if($functionName=="oa_work"){
	$data = simulate::oa_work();
}
else if($functionName=="oa_work_step"){
	$data = simulate::oa_work_step();
}
else if($functionName=="oa_statistics"){
	$data = simulate::oa_statistics();
}
else if($functionName=="oa_statistics_step"){
	$data = simulate::oa_statistics_step();
}
else if($functionName=="hydro_emergency_team"){
	$data = simulate::hydro_emergency_team();
}
else if($functionName=="hydro_emergency_team_step"){
	$data = simulate::hydro_emergency_team_step();
}
else if($functionName=="hydro_reservoir"){
	$data = simulate::hydro_reservoir();
}
else if($functionName=="hydro_reservoir_step"){
	$data = simulate::hydro_reservoir_step();
}
else if($functionName=="hydro_responsibility"){
	$data = simulate::hydro_responsibility();
}
else if($functionName=="hydro_responsibility_step"){
	$data = simulate::hydro_responsibility_step();
}
else if($functionName=="oa_department"){
	$data = simulate::oa_department();
}
else if($functionName=="oa_department_step"){
	$data = simulate::oa_department_step();
}
else if($functionName=="oa_department_member"){
	$data = simulate::oa_department_member();
}
else if($functionName=="oa_department_member_step"){
	$data = simulate::oa_department_member_step();
}
else if($functionName=="check_kpi"){
	$data = simulate::check_kpi();
}
else if($functionName=="basic_parameter"){
	$data = simulate::basic_parameter();
}
else if($functionName=="oa_department__hydro"){
	$data = simulate::oa_department__hydro();
}
else if($functionName=="oa_department__hydro_step"){
	$data = simulate::oa_department__hydro_step();
}
else if($functionName=="oa_department_member__hydro"){
	$data = simulate::oa_department_member__hydro();
}
else if($functionName=="oa_department_member__hydro_step"){
	$data = simulate::oa_department_member__hydro_step();
}
else if($functionName=="storehouse"){
	$data = simulate::storehouse();
}
else if($functionName=="storehouse_step"){
	$data = simulate::storehouse_step();
}
else if($functionName=="materiel"){
	$data = simulate::materiel();
}
else if($functionName=="oa_statistics_detail__readexcel"){
	$data = simulate::oa_statistics_detail__readexcel();
}
else if($functionName=="test"){
	//$data = simulate::randomName();
	header("Content-type: text/html; charset=utf-8"); 
	//echo $data;exit();
	echo strtotime("2007-12-23");
}

echo json_encode($data);
if(tools::$conn!=null)tools::closeConn();
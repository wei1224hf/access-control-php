var server_path = "../php/simulate.php";

var basic_group = function(){
	$.ajax({
		url: server_path+"?function=basic_group"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='basic_group()']").parent().parent()).append(response.msg+"<br/>");
		}
		,error : function(response){		
			alert("net error");
		}
	});	
};

var st_stbprp_b = function(){
	$.ajax({
		url: server_path+"?function=st_stbprp_b"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='st_stbprp_b()']").parent().parent()).append(response.msg+"<br/>");
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var basic_group___localzone = function(){
	$.ajax({
		url: server_path+"?function=basic_group___localzone"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='basic_group___localzone()']").parent().parent()).append(response.msg+"<br/>");
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var basic_user = function(){
	if(basic_user_steps != 1){
		basic_user_steps ++;
		basic_user_step();
		return;
	}	
	$.ajax({
		url: server_path+"?function=basic_user"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='basic_user()']").parent().parent()).append(response.msg+"<br/>");
			code_basic_user = response.data;
			basic_user_step();
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var code_basic_user = [];
var basic_user_steps = 1;
var basic_user_step = function(){
	$.ajax({
		url: server_path+"?function=basic_user_step"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
				
			,code: code_basic_user[basic_user_steps]
        }         
		,success : function(response) {
			$(".directions",$("[onclick='basic_user()']").parent().parent()).html(code_basic_user.length - basic_user_steps);
			if(basic_user_steps>=code_basic_user.length-1)return;
			basic_user_steps++;
			basic_user_step();
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var zone = function(){
	$.ajax({
		url: server_path+"?function=zone"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='zone()']").parent().parent()).append(response.msg+"<br/>");
		}
		,error : function(response){		
			alert("net error");
		}
	});	
};

var zone___localzone = function(){
	$.ajax({
		url: server_path+"?function=zone___localzone"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='zone___localzone()']").parent().parent()).append(response.msg+"<br/>");
		}
		,error : function(response){		
			alert("net error");
		}
	});	
};

var zone_gis = function(){
	$.ajax({
		url: server_path+"?function=zone_gis"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='zone_gis()']").parent().parent()).append(response.msg+"<br/>");
		}
		,error : function(response){		
			alert("net error");
		}
	});	
};

var zone_9_gis = function(){
	$.ajax({
		url: server_path+"?function=zone_9_gis"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='zone_9_gis()']").parent().parent()).append(response.msg+"<br/>");
		}
		,error : function(response){		
			alert("net error");
		}
	});	
};

var zone_12_gis = function(){
	$.ajax({
		url: server_path+"?function=zone_12_gis"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='zone_12_gis()']").parent().parent()).append(response.msg+"<br/>");
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var building = function(){
	if(building_steps != 1){
		building_steps ++;
		building_step();
		return;
	}
	$.ajax({
		url: server_path+"?function=building"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='building()']").parent().parent()).append(response.msg+"<br/>");
			building_code = response.data;
			building_step();
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var building_code = [];
var building_steps = 1;
var building_step = function(){
	var data = building_code[building_steps];
	data.executor = "";
	data.session = "";
	$.ajax({
		url: server_path+"?function=building_step"
		,dataType: 'json'
        ,type: "POST"		
        ,data: data     
		,success : function(response) {
			$(".directions",$("[onclick='building()']").parent().parent()).html( building_code.length - building_steps  );
			if(building_steps>=building_code.length-1)return;
			if(response.status==2)return;
			building_steps++;
			building_step();
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var building_gis = function(){
	$.ajax({
		url: server_path+"?function=building_gis"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='building_gis()']").parent().parent()).append(response.msg+"<br/>");
		}
		,error : function(response){		
			alert("net error");
		}
	});	
};


var family = function(){
	if(family_steps != 1){
		family_steps ++;
		family_step();
		return;
	}
	$.ajax({
		url: server_path+"?function=family"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='family()']").parent().parent()).append(response.msg+"<br/>");
			code_family = response.data;
			family_step();
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var code_family = [];
var family_steps = 1;
var family_step = function(){
	$.ajax({
		url: server_path+"?function=family_step"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
				
			,code: code_family[family_steps]['code']
			,type: code_family[family_steps]['type']
        }         
		,success : function(response) {
			$(".directions",$("[onclick='family()']").parent().parent()).html(code_family.length - family_steps);
			if(family_steps>=code_family.length-1)return;
			family_steps++;
			family_step();
		}
		,error : function(response){		
			alert("net error");
		}
	});	
};

var company = function(){
	if(company_steps != 1){
		company_steps ++;
		company_step();
		return;
	}
	$.ajax({
		url: server_path+"?function=company"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='company()']").parent().parent()).append(response.msg+"<br/>");
			company_code = response.data;
			company_step();
		}
		,error : function(response){		
			alert("net error");
		}
	});	
};

var company_code = [];
var company_steps = 1;
var company_step = function(){
	var data = company_code[company_steps];
	data.executor = "";
	data.session = "";
	$.ajax({
		url: server_path+"?function=company_step"
		,dataType: 'json'
        ,type: "POST"		
        ,data: data       
		,success : function(response) {
			$(".directions",$("[onclick='company()']").parent().parent()).html(company_code.length - company_steps);
			if(response.status!="1")return;
			if(company_steps>=company_code.length-1)return;
			company_steps++;
			company_step();
		}
		,error : function(response){		
			alert("net error");
		}
	});	
};

var oa_statistics = function(){
	if(oa_statistics_steps != 1){
		oa_statistics_steps ++;
		oa_statistics_step();
		return;
	}
	
	$.ajax({
		url: server_path+"?function=oa_statistics"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='oa_statistics()']").parent().parent()).append(response.msg+"<br/>");
			oa_statistics_code = response.data;
			oa_statistics_step();
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var oa_statistics_code = [];
var oa_statistics_steps = 1;
var oa_statistics_step = function(){
    var data = oa_statistics_code[oa_statistics_steps];
    data.executor = "";
    data.session = "";
	$.ajax({
		url: server_path+"?function=oa_statistics_step"
		,dataType: 'json'
        ,type: "POST"		
        ,data: data        
		,success : function(response) {
			$(".directions",$("[onclick='oa_statistics()']").parent().parent()).html(oa_statistics_code.length - oa_statistics_steps);
			if(response.status!="1")return;
			if(oa_statistics_steps>=oa_statistics_code.length-1)return;
			oa_statistics_steps++;
			oa_statistics_step();
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var resident = function(){
	if(resident_steps != 1){
		resident_steps ++;
		resident_step();
		return;
	}
	$.ajax({
		url: server_path+"?function=resident"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='resident()']").parent().parent()).append(response.msg+"<br/>");
			code_resident = response.data;
			resident_step();
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var code_resident = [];
var resident_steps = 1;
var resident_step = function(){
	$.ajax({
		url: server_path+"?function=resident_step"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
				
			,code: code_resident[resident_steps]['code']
			,type: code_resident[resident_steps]['type']
        }         
		,success : function(response) {
			if(response.status==1){
				$(".directions",$("[onclick='resident()']").parent().parent()).html(code_resident.length - resident_steps);
				if(resident_steps>=code_resident.length-1)return;
				resident_steps++;
				resident_step();
			}else{
				$(".directions",$("[onclick='resident()']").parent().parent()).html(response.msg);
			}
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var oa_plan = function(){
	if(oa_plan_steps != 1){
		oa_plan_steps ++;
		oa_plan_step();
		return;
	}	
	$.ajax({
		url: server_path+"?function=oa_plan"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='oa_plan()']").parent().parent()).append(response.msg+"<br/>");
			oa_plan_code = response.data;
			oa_plan_step();
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var oa_plan_code = [];
var oa_plan_steps = 1;
var oa_plan_step = function(){
	$.ajax({
		url: server_path+"?function=oa_plan_step"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
				
			,code: oa_plan_code[oa_plan_steps]
        }         
		,success : function(response) {
			if(response.status==1){
				$(".directions",$("[onclick='oa_plan()']").parent().parent()).html(oa_plan_code.length - oa_plan_steps);
				if(oa_plan_steps>=oa_plan_code.length-1)return;
				oa_plan_steps++;
				oa_plan_step();
			}else{
				$(".directions",$("[onclick='oa_plan()']").parent().parent()).html(response.msg);
			}
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var oa_work = function(){
	if(oa_work_steps != 1){
		oa_work_steps ++;
		oa_work_step();
		return;
	}
	$.ajax({
		url: server_path+"?function=oa_work"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='oa_work()']").parent().parent()).append(response.msg+"<br/>");
			oa_work_code = response.data;
			oa_work_step();
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var oa_work_code = [];
var oa_work_steps = 1;
var oa_work_step = function(){
	$.ajax({
		url: server_path+"?function=oa_work_step"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
				
			,code: oa_work_code[oa_work_steps]['code']
			,time_start: oa_work_code[oa_work_steps]['time_start']
        }         
		,success : function(response) {
			if(response.status==1){
				$(".directions",$("[onclick='oa_work()']").parent().parent()).html(oa_work_code.length - oa_work_steps);
				if(oa_work_steps>=oa_work_code.length-1)return;
				oa_work_steps++;
				oa_work_step();
			}else{
				$(".directions",$("[onclick='oa_work()']").parent().parent()).html(response.msg);
			}
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var hydro_emergency_team = function(){
	if(hydro_emergency_team_steps != 1){
		hydro_emergency_team_steps ++;
		hydro_emergency_team_step();
		return;
	}	
	$.ajax({
		url: server_path+"?function=hydro_emergency_team"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
        }         
		,success : function(response) {
			$(".directions",$("[onclick='hydro_emergency_team()']").parent().parent()).append(response.msg+"<br/>");
			code_hydro_emergency_team = response.data;
			hydro_emergency_team_step();
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var code_hydro_emergency_team = [];
var hydro_emergency_team_steps = 1;
var hydro_emergency_team_step = function(){
	$.ajax({
		url: server_path+"?function=hydro_emergency_team_step"
		,dataType: 'json'
        ,type: "POST"		
        ,data: {
			 executor: ""
			,session: ""
				
			,code: code_hydro_emergency_team[hydro_emergency_team_steps].code
			,x: code_hydro_emergency_team[hydro_emergency_team_steps].gis_2d_center_x
			,y: code_hydro_emergency_team[hydro_emergency_team_steps].gis_2d_center_y
        }         
		,success : function(response) {
			$(".directions",$("[onclick='hydro_emergency_team()']").parent().parent()).html(code_hydro_emergency_team.length - hydro_emergency_team_steps);
			if(hydro_emergency_team_steps>=code_hydro_emergency_team.length-1)return;
			hydro_emergency_team_steps++;
			hydro_emergency_team_step();
		}
		,error : function(response){		
			alert("net error");
		}
	});	
}

var hydro_reservoir = function(){
    if(hydro_reservoir_steps != 1){
        hydro_reservoir_steps ++;
        hydro_reservoir_step();
        return;
    }
    $.ajax({
        url: server_path+"?function=hydro_reservoir"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: {
             executor: ""
            ,session: ""
        }         
        ,success : function(response) {
            $(".directions",$("[onclick='hydro_reservoir()']").parent().parent()).append(response.msg+"<br/>");
            hydro_reservoir_code = response.data;
            hydro_reservoir_step();
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};

var hydro_reservoir_code = [];
var hydro_reservoir_steps = 1;
var hydro_reservoir_step = function(){
    var data = hydro_reservoir_code[hydro_reservoir_steps];
    data.executor = "";
    data.session = "";
    $.ajax({
        url: server_path+"?function=hydro_reservoir_step"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: data       
        ,success : function(response) {
            $(".directions",$("[onclick='hydro_reservoir()']").parent().parent()).html(hydro_reservoir_code.length - hydro_reservoir_steps);
            if(response.status!="1")return;
            if(hydro_reservoir_steps>=hydro_reservoir_code.length-1)return;
            hydro_reservoir_steps++;
            hydro_reservoir_step();
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};

var hydro_responsibility = function(){
    if(hydro_responsibility_steps != 1){
        hydro_responsibility_steps ++;
        hydro_responsibility_step();
        return;
    }
    $.ajax({
        url: server_path+"?function=hydro_responsibility"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: {
             executor: ""
            ,session: ""
        }         
        ,success : function(response) {
            $(".directions",$("[onclick='hydro_responsibility()']").parent().parent()).append(response.msg+"<br/>");
            hydro_responsibility_code = response.data;
            hydro_responsibility_step();
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};

var hydro_responsibility_code = [];
var hydro_responsibility_steps = 1;
var hydro_responsibility_step = function(){
    var data = hydro_responsibility_code[hydro_responsibility_steps];
    data.executor = "";
    data.session = "";
    $.ajax({
        url: server_path+"?function=hydro_responsibility_step"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: data       
        ,success : function(response) {
            $(".directions",$("[onclick='hydro_responsibility()']").parent().parent()).html(hydro_responsibility_code.length - hydro_responsibility_steps);
            if(response.status!="1")return;
            if(hydro_responsibility_steps>=hydro_responsibility_code.length-1)return;
            hydro_responsibility_steps++;
            hydro_responsibility_step();
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};

var oa_department = function(){
    if(oa_department_steps != 0){
        oa_department_steps ++;
        oa_department_step();
        return;
    }
    $.ajax({
        url: server_path+"?function=oa_department"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: {
             executor: ""
            ,session: ""
        }         
        ,success : function(response) {
            $(".directions",$("[onclick='oa_department()']").parent().parent()).append(response.msg+"<br/>");
            oa_department_code = response.data;
            oa_department_step();
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};

var oa_department_code = [];
var oa_department_steps = 0;
var oa_department_step = function(){
    var data = oa_department_code[oa_department_steps];
    data.executor = "";
    data.session = "";
    $.ajax({
        url: server_path+"?function=oa_department_step"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: data       
        ,success : function(response) {
            $(".directions",$("[onclick='oa_department()']").parent().parent()).html(oa_department_code.length - oa_department_steps);
            if(response.status!="1")return;
            if(oa_department_steps>=oa_department_code.length-1)return;
            oa_department_steps++;
            oa_department_step();
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};


var oa_department_member = function(){
    if(oa_department_member_steps != 0){
        oa_department_member_steps ++;
        oa_department_member_step();
        return;
    }
    $.ajax({
        url: server_path+"?function=oa_department_member"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: {
             executor: ""
            ,session: ""
        }         
        ,success : function(response) {
            $(".directions",$("[onclick='oa_department_member()']").parent().parent()).append(response.msg+"<br/>");
            oa_department_member_code = response.data;
            oa_department_member_step();
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};

var oa_department_member_code = [];
var oa_department_member_steps = 0;
var oa_department_member_step = function(){
    var data = oa_department_member_code[oa_department_member_steps];
    data.executor = "";
    data.session = "";
    $.ajax({
        url: server_path+"?function=oa_department_member_step"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: data       
        ,success : function(response) {
            $(".directions",$("[onclick='oa_department_member()']").parent().parent()).html(oa_department_member_code.length - oa_department_member_steps);
            if(response.status!="1")return;
            if(oa_department_member_steps>=oa_department_member_code.length-1)return;
            oa_department_member_steps++;
            oa_department_member_step();
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};

var check_kpi = function(){
    $.ajax({
        url: server_path+"?function=check_kpi"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: {
             executor: ""
            ,session: ""
        }         
        ,success : function(response) {
            $(".directions",$("[onclick='check_kpi()']").parent().parent()).append(response.msg+"<br/>");
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};

var materiel = function(){
    $.ajax({
        url: server_path+"?function=materiel"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: {
             executor: ""
            ,session: ""
        }         
        ,success : function(response) {
            $(".directions",$("[onclick='materiel()']").parent().parent()).append(response.msg+"<br/>");
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};

var oa_statistics_detail__readexcel = function(){
    $.ajax({
        url: server_path+"?function=oa_statistics_detail__readexcel"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: {
             executor: ""
            ,session: ""
        }         
        ,success : function(response) {
            $(".directions",$("[onclick='oa_statistics_detail__readexcel()']").parent().parent()).append(response.msg+"<br/>");
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};

var basic_parameter = function(){
    $.ajax({
        url: server_path+"?function=basic_parameter"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: {
             executor: ""
            ,session: ""
        }         
        ,success : function(response) {
            $(".directions",$("[onclick='basic_parameter()']").parent().parent()).append(response.msg+"<br/>");
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};

var oa_department__hydro = function(){
    if(oa_department__hydro_steps != 0){
        oa_department__hydro_steps ++;
        oa_department__hydro_step();
        return;
    }
    $.ajax({
        url: server_path+"?function=oa_department__hydro"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: {
             executor: ""
            ,session: ""
        }         
        ,success : function(response) {
            $(".directions",$("[onclick='oa_department__hydro()']").parent().parent()).append(response.msg+"<br/>");
            oa_department__hydro_code = response.data;
            oa_department__hydro_step();
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};

var oa_department__hydro_code = [];
var oa_department__hydro_steps = 0;
var oa_department__hydro_step = function(){
    var data = oa_department__hydro_code[oa_department__hydro_steps];
    data.executor = "";
    data.session = "";
    $.ajax({
        url: server_path+"?function=oa_department__hydro_step"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: data       
        ,success : function(response) {
            $(".directions",$("[onclick='oa_department__hydro()']").parent().parent()).html(oa_department__hydro_code.length - oa_department__hydro_steps);
            if(response.status!="1")return;
            if(oa_department__hydro_steps>=oa_department__hydro_code.length-1)return;
            oa_department__hydro_steps++;
            oa_department__hydro_step();
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};


var oa_department_member__hydro = function(){
    if(oa_department_member__hydro_steps != 0){
        oa_department_member__hydro_steps ++;
        oa_department_member__hydro_step();
        return;
    }
    $.ajax({
        url: server_path+"?function=oa_department_member__hydro"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: {
             executor: ""
            ,session: ""
        }         
        ,success : function(response) {
            $(".directions",$("[onclick='oa_department_member__hydro()']").parent().parent()).append(response.msg+"<br/>");
            oa_department_member__hydro_code = response.data;
            oa_department_member__hydro_step();
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};

var oa_department_member__hydro_code = [];
var oa_department_member__hydro_steps = 0;
var oa_department_member__hydro_step = function(){
    var data = oa_department_member__hydro_code[oa_department_member__hydro_steps];
    data.executor = "";
    data.session = "";
    $.ajax({
        url: server_path+"?function=oa_department_member__hydro_step"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: data       
        ,success : function(response) {
            $(".directions",$("[onclick='oa_department_member__hydro()']").parent().parent()).html(oa_department_member__hydro_code.length - oa_department_member__hydro_steps);
            if(response.status!="1")return;
            if(oa_department_member__hydro_steps>=oa_department_member__hydro_code.length-1)return;
            oa_department_member__hydro_steps++;
            oa_department_member__hydro_step();
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};


var storehouse = function(){
    if(storehouse_steps != 0){
        storehouse_steps ++;
        storehouse_step();
        return;
    }
    $.ajax({
        url: server_path+"?function=storehouse"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: {
             executor: ""
            ,session: ""
        }         
        ,success : function(response) {
            $(".directions",$("[onclick='storehouse()']").parent().parent()).append(response.msg+"<br/>");
            storehouse_code = response.data;
            storehouse_step();
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};

var storehouse_code = [];
var storehouse_steps = 0;
var storehouse_step = function(){
    var data = storehouse_code[storehouse_steps];
    data.executor = "";
    data.session = "";
    $.ajax({
        url: server_path+"?function=storehouse_step"
        ,dataType: 'json'
        ,type: "POST"       
        ,data: data       
        ,success : function(response) {
            $(".directions",$("[onclick='storehouse()']").parent().parent()).html(storehouse_code.length - storehouse_steps);
            if(response.status!="1")return;
            if(storehouse_steps>=storehouse_code.length-1)return;
            storehouse_steps++;
            storehouse_step();
        }
        ,error : function(response){        
            alert("net error");
        }
    }); 
};
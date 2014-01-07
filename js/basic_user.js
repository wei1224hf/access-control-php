
var basic_user = {

     loginData: {}
    ,permission: []

    ,loginForm : function(){
        $(document.body).append('<form id="form"></form>');
        
        $("#form").ligerForm({
             inputWidth: 170
            ,labelWidth: 90
            ,space: 40
            ,fields: [
                 { display: il8n.basic_user.username, name: "username",  type: "text",  validate : {required:true,minlength:3,maxlength:40} }
                ,{ display: il8n.basic_user.password, name: "password",  type: "password", validate : {required:true,minlength:3,maxlength:40} }
            ]
        });
        $("#form").append('<br/><br/><br/><table style="width:280px"><tr><td style="width:50%"></td>'
                +'<td  style="width:50%"><input type="submit" id="bt_login" value="'+il8n.basic_user.login+'" class="l-button l-button-submit" style="width:119px;" /></td></tr></table>' );
        
        var v = $("#form").validate({
            debug: true,
            errorPlacement: function (lable, element) {
                if (element.hasClass("l-textarea")) {
                element.addClass("l-textarea-invalid");
                }
                else if (element.hasClass("l-text-field")) {
                element.parent().addClass("l-text-invalid");
                } 
            },
            success: function (lable) {
                var element = $("[ligeruiid="+$(lable).attr('for')+"]",$("form"));
                if (element.hasClass("l-textarea")) {
                    element.removeClass("l-textarea-invalid");
                } else if (element.hasClass("l-text-field")) {
                    element.parent().removeClass("l-text-invalid");
                }
            },
            submitHandler: function () {
            	$("#bt_login").hide();
            	if( top === self){
            		basic_user.login($('[ligeruiid=username]').val(), MD5( MD5( $('[ligeruiid=password]').val() ) +((new Date()).getHours())) ,"console.debug(basic_user);");
            	}
            	else{
            		parent.basic_user.login($('[ligeruiid=username]').val(), MD5( MD5( $('[ligeruiid=password]').val() ) +((new Date()).getHours())) ,"layout.loadIcons();$.ligerui.get('win_10').close();");
            	}
            }
        });
    }
    
    ,login: function(username,password,afterAjax){
        if(this.ajaxState==true)return;
        this.ajaxState = true;
        //console.debug(username+" "+password);
        $.ajax({
            url : config_path__basic_user__login,
            data : {
                username: username,
                password: password,
                executor: "",
                session: ""
            },
            type : "POST",
            dataType: 'json',
            success : function(data) {                
                basic_user.ajaxState = false;
                if(data.status=='1' || data.status=='3'){
                    if(data.status=='3')alert(data.msg);
                    sys_top_window = 1;
                    basic_user.loginData = data.logindata;                    
                    basic_user.permission = data.permissions;

                    SetCookie("myApp_username",username,0.5);
                    SetCookie("myApp_password",password,0.5); 
                    
                    //console.debug(afterAjax);
                    if ( typeof(afterAjax) == "string" ){
                        eval(afterAjax);
                    }else if( typeof(afterAjax) == "function"){
                        afterAjax();
                    }        
                }else{
                    alert(data.msg);
                    delCookie("myApp_username");
                    delCookie("myApp_password");
                }
            },
            error : function(){
                $.ligerDialog.error('net error');
            }
        });
    }

    ,updateSession: function(){
        $.ajax({
            url : config_path__basic_user__updateSession,
            data : {
                executor: top.basic_user.loginData.username,
                session: top.basic_user.loginData.session
            },
            type : "POST",
            dataType: 'json',    
            success : function(data) {
                top.basic_user.loginData.session = data.session;                
            },
            error : function(){
                $.ligerDialog.error('net error');
            }
        });
    }
   
    ,add_register: function(){
        var config = {
                id: 'basic_user__add',
                fields: [
                     { display: il8n.basic_user.username, name: "basic_user__username", type: "text",  validate: { required:true, minlength:3, maxlength:10} }
                    ,{ display: il8n.basic_user.password, name: "basic_user__password", type: "password", validate: { required:true } }
                ]
            };
            
        $(document.body).append("<form id='form'></form>");
        $('#form').ligerForm(config);            
        $('#form').append('<br/><br/><br/><br/><input type="submit" value="'+il8n.basic_normal.submit+'" id="basic_user__submit" class="l-button l-button-submit" />' );
        
        var v = $('#form').validate({
            debug: true,
            errorPlacement: function (lable, element) {
                if (element.hasClass("l-text-field")) {
                    element.parent().addClass("l-text-invalid");
                } 
            },
            success: function (lable) {
                var element = $("[ligeruiid="+$(lable).attr('for')+"]",$("form"));
                if (element.hasClass("l-text-field")) {
                    element.parent().removeClass("l-text-invalid");
                }
            },
            submitHandler: function () {
                if(basic_user.ajaxState)return;
                basic_user.ajaxState = true;
                
                $.ajax({
                    url: config_path__basic_user__add_register,
                    data: {                        
                        data:$.ligerui.toJSON({
                             executor: $.ligerui.get('basic_user__username').getValue()
                            ,password: $.ligerui.get('basic_user__password').getValue()
                        })
                    },
                    type: "POST",
                    dataType: 'json',                        
                    success: function(response) {        
                        if(response.status=="1"){
                            basic_user.ajaxState = false;
                            alert(response.msg);
                            $("#basic_user__submit").remove();
                        }else{
                            basic_user.ajaxState = false;
                            alert(response.msg);
                        }
                    },
                    error : function(){
                        alert('disConnect');
                    }
                });    
            }
        });
    }
    
    ,grid: function(){
        var config = {
                id: 'basic_user__grid'
                ,height:'100%'
                ,columns: [
                     { display: il8n.basic_normal.id, name: 'id', isSort: true, hide:true }
                    ,{ display: il8n.basic_user.username, name: 'username', width:120 }
                    ,{ display: il8n.basic_user.money, name: 'money' }
                    ,{ display: il8n.basic_user.credits, name: 'credits', hide:true }
                    ,{ display: il8n.basic_user.time_created, name: 'time_created', hide:true }
                    ,{ display: il8n.basic_user.type, name: 'type_', isSort: false }
                    ,{ display: il8n.basic_normal.status, name: 'status_', isSort: false }                    
                    ,{ display: il8n.basic_user.group_name, name: 'group_name', isSort: false, width:120 }
                    ,{ display: il8n.basic_user.group_code, name: 'group_code', width:80 }                    
                ]
        		,pageSize:20 
        		,rownumbers:true
                ,parms : {
                    executor: top.basic_user.loginData.username
                    ,session: top.basic_user.loginData.session     
                    ,search: "{}"
                },
                url: config_path__basic_user__grid,
                method: "POST",                
                toolbar: { items: []}
        };
        
        var permission = top.basic_user.permission;
        for(var i=0;i<permission.length;i++){
            if(permission[i].code=='12'){
                permission = permission[i].children;                
            }
        }
        for(var i=0;i<permission.length;i++){
            if(permission[i].code=='1202'){
                permission = permission[i].children;                
            }
        }        
        
        for(var i=0;i<permission.length;i++){    
            
            var theFunction = function(){};
            var thecode = permission[i].code;
            var actioncode = thecode.substring(thecode.length-2,thecode.length);
            
            if(actioncode=='01'){
                theFunction = basic_user.search;
            }
            else if(actioncode=='02'){
                theFunction = function(){
                    var selected = basic_user.grid_getSelectOne();
                    var win = top.$.ligerDialog.open({ 
                         url: 'basic_user__view.html?random='+Math.random()+"&id="+selected.id
                        ,height: 500
                        ,width: 400
                        ,title: il8n.basic_normal.view
                        ,isHidden: false
                        ,id: "basic_user__view___"+selected.id
						, showMax: true
						, showToggle: true
						, showMin: true	
						, modal: false
                    });
                    win.doMax();
                };
            }                
            else if(actioncode=='21'){
                theFunction = function(){
                    var win = top.$.ligerDialog.open({ 
                         url: 'basic_user__add.html?random='+Math.random()
                        ,height: 500
                        ,width: 400
                        ,title: il8n.basic_normal.add
                        ,isHidden: false
						, showMax: true
						, showToggle: true
						, showMin: true	
						, modal: false
                    });
                    win.doMax();
                };
            }            
            else if(actioncode=='22'){
                theFunction = function(){
                    var selected = basic_user.grid_getSelectOne();
                    var win = top.$.ligerDialog.open({ 
                         url: 'basic_user__modify.html?random='+Math.random()+"&id="+selected.id
                        ,height: 500
                        ,width: 400
                        ,title: il8n.basic_normal.modify
                        ,isHidden: false
                        ,id: "basic_user__modify___"+selected.id
						,showMax: true
						,showToggle: true
						,showMin: true	
						,modal: false
                    });
                    win.doMax();
                };
            }
            else if(actioncode=='23'){
                theFunction = basic_user.remove;
                config.checkbox = true;
            }
            else if(actioncode=='41'){
                theFunction = function(){
                    var selected = basic_user.grid_getSelectOne();                    
                    var win = top.$.ligerDialog.open({ 
                         url: 'basic_user__group_get.html?username='+selected.username+'&random='+Math.random()
                        ,height: 400
                        ,width: 400
                        ,title: selected.username
                        ,isResize: true
                        ,isHidden: false
                    });    
                    win.doMax();
                }
            }            
            
            config.toolbar.items.push({line: true });
            config.toolbar.items.push({
                text: permission[i].name , img:permission[i].icon , click : theFunction 
            });    
        }
        
        $(document.body).ligerGrid(config);
    }
    
    ,grid_getSelectOne: function(){
        var selected;
        if($.ligerui.get('basic_user__grid').options.checkbox){
            selected = $.ligerui.get('basic_user__grid').getSelecteds();
            if(selected.length!=1){ 
                alert(il8n.basic_normal.selectOne);
                return;
            }
            selected = selected[0];
        }else{
            selected = $.ligerui.get('basic_user__grid').getSelected();
            if(selected==null){
                alert(il8n.basic_normal.selectOne);
                return;
            }
        }    
        return selected;
    }
    
    ,center: function(){
        $(document.body).html("<div id='menu'></div><div id='content' style='width:"+($(window).width()-250)+"px;margin-top:5px;'></div>");
        var htmls = "";
        
        var data = top.basic_user.loginData;
        if(typeof(data.photo)=="undefined") data.photo = '../file/tavatar.gif';
        il8n_ = "basic_user";
        for(var j in data){   
            if(j=='session'||j=='status'||j=='type'||j=='group_code'||j=='group_all'||j=='id'||j=='session'||j=='client'||j=='permissions')continue;                
            if(j=='photo'){
                htmls += '<div style="position:absolute;right:5px;top:25px;background-color: rgb(220,250,245);width:166px;height:176px;"><img style="margin:2px;" src="'+data[j]+'" width="160" height="170" /></div>';
                continue;
            }
            
            var key = il8n.basic_user[j];
            if(j=='group_name'){
                htmls += "<span class='view_lable' style='width:95%'>"+key+"</span><span style='width:95%' class='view_data'>"+data[j]+"</span>";
            }else{
                htmls += "<span class='view_lable'>"+key+"</span><span class='view_data'>"+data[j]+"</span>";
            }            
            
        }; 
        $("#content").html(htmls);
        var permission = top.basic_user.permission;
       
        for(var i=0;i<permission.length;i++){
            if(permission[i].code=='11'){
                if(typeof(permission[i].children)=='undefined')return;
                permission = permission[i].children;
                break;
            }
        }     
        
        var items = []; 
        for(var i=0;i<permission.length;i++){
            items.push({line:true});
            var config = {text:permission[i].name,img:permission[i].icon};
            if(permission[i].code == "1199"){
                config.click = function(){
                    top.basic_user.logout();
                };
            }else if(permission[i].code == "1123"){
                config.click = function(){
                    top.$.ligerDialog.open({
                        isHidden: false
                        ,id: "win_user_modify_myself" 
                        ,height: 400
                        ,width: 400
                        ,url: "basic_user__modify_myself.html?id="+top.basic_user.loginData.id
                        ,showMax: true
                        ,showToggle: true
                        ,showMin: true
                        ,isResize: true
                        ,modal: true
                        ,slide: false    
                        ,title: il8n.basic_normal.modify
                    });
                                    
                };
            }
            items.push(config);
        }

        $("#menu").ligerToolBar({
            items: items
        });
    }    
    
    ,logout: function(){
        
        delCookie("myApp_username");
        delCookie("myApp_password");
        $.ajax({
            url: config_path__basic_user__logout,
            dataType: "json",
            data : {
                executor: top.basic_user.loginData.username
                ,session: top.basic_user.loginData.session
            },
            type : "POST",
            success : function(msg) {
                top.window.location.reload();
            }
        });
    }
    
    ,remove: function(){
        var selected = $.ligerui.get('basic_user__grid').getSelecteds();
        if(selected.length==0){alert(il8n.basic_normal.noSelect);return;}
        if(confirm( il8n.basic_normal.sureToDelete )){
            var usernames = "";
            for(var i=0; i<selected.length; i++){
                usernames += selected[i].username+",";
            }
            usernames = usernames.substring(0,usernames.length-1);                
            
            $.ajax({
                url: config_path__basic_user__remove,
                data: {
                    usernames: usernames 
                    
                    ,executor: top.basic_user.loginData.username
                    ,session: top.basic_user.loginData.session
                }
                ,type: "POST"
                ,dataType: 'json'
                ,success: function(response) {
                    if(response.status=="1"){
                        $.ligerui.get('basic_user__grid').loadData();
                    }else{
                        alert(response.msg);
                    }
                },
                error : function(){
                    alert("net error");
                }
            });                
        }        
    }
    
    ,add: function(){
        var config = {
            id: 'basic_user__add',
            fields: [
                 { display: il8n.basic_user.username, name: "username", type: "text",  validate: { required:true, minlength:3, maxlength:10} }
                ,{ display: il8n.basic_user.password, name: "password", type: "password", validate: { required:true } }
                ,{ display: il8n.basic_user.group, name: "group_code", type: "text", validate: {required:true} }                
                ,{ display: il8n.basic_user.money, name: "money", type: "text", validate: {required:true} }                
                ,{ display: il8n.basic_user.credits, name: "credits", type: "text", validate: {required:true} }                
                ,{ display: il8n.basic_normal.type, name: "type", type: "select", options: { data: basic_parameter_data.basic_user__type, valueField: "code", textField: "value", slide: false }, validate: { required: true } }
                ,{ display: il8n.basic_normal.status, name: "status", type: "select", options: { data: basic_parameter_data.basic_user__status, valueField: "code", textField: "value", slide: false }, validate: { required: true } }
            ]
        };
        
        $(document.body).append("<form id='form'></form>");
        $('#form').ligerForm(config);            
        $('#form').append('<br/><br/><br/><br/><input type="submit" value="'+il8n.basic_normal.submit+'" id="basic_user__submit" class="l-button l-button-submit" />' );
        
        var v = $('#form').validate({
            debug: true,
            errorPlacement: function (lable, element) {
                if (element.hasClass("l-text-field")) {
                    element.parent().addClass("l-text-invalid");
                } 
            },
            success: function (lable) {
                var element = $("[ligeruiid="+$(lable).attr('for')+"]",$("form"));
                if (element.hasClass("l-text-field")) {
                    element.parent().removeClass("l-text-invalid");
                }
            },
            submitHandler: function () {
                if(basic_user.ajaxState)return;
                basic_user.ajaxState = true;
                $("#basic_user__submit").attr("value",il8n.basic_user.waitting);
                
				var data = {};
				var doms = $("[ligeruiid]",$('#form'));
				for(var i=0;i<doms.length;i++){
				    var theid = $(doms[i]).attr('ligeruiid');
				    var thetype = $(doms[i]).attr('ltype');                                                        
				 
			        var thevalue = $.ligerui.get(theid).getValue();
			        if(thetype=='date')thevalue = $('#'+theid).val();
				    if(thevalue!="" && thevalue!=0 && thevalue!="0" && thevalue!=null){
				    	
				        data[theid]=thevalue;
				    }
				}
                
                $.ajax({
                    url: config_path__basic_user__add,
                    data: {
                         executor: top.basic_user.loginData.username
                        ,session: top.basic_user.loginData.session
                        
                        ,data:$.ligerui.toJSON(data)
                    },
                    type: "POST",
                    dataType: 'json',                        
                    success: function(response) {        
                        //服务端添加成功,修改 AJAX 通信状态,修改按钮的文字信息,读取反馈信息
                        if(response.status=="1"){
                            basic_user.ajaxState = false;
                            alert(il8n.basic_normal.done);
                            $("#basic_user__submit").remove();
                        //服务端添加失败
                        }else{
                            alert(response.msg);
                            basic_user.ajaxState = false;
                            $("#basic_user__submit").attr("value", il8n.basic_normal.submit );
                        }
                    },
                    error : function(){
                        alert(il8n.disConnect);
                    }
                });    
            }
        });
    }

    
    ,ajaxState: false    
    ,modify: function(){
		config_path__basic_user__add = config_path__basic_user__modify;
		basic_user.add();	
		$('[ligeruiid=username]').attr("disabled","disabled");
		$('[ligeruiid=username]').css("background-color","#EEEEEE");
		$('[ligeruiid=username]').parent().css("background-color","#EEEEEE");
		$.ajax({
			url: config_path__basic_user__view,
			data: {
                 executor: top.basic_user.loginData.username
                ,session: top.basic_user.loginData.session
                
				,id: getParameter("id", window.location.toString() )
			},
			type: "POST",
			dataType: 'json',						
			success: function(response) {		
				var data = response.data;

				var doms = $("[ligeruiid]",$('#form'));
				for(var i=0;i<doms.length;i++){
				    var theid = $(doms[i]).attr('ligeruiid');
				    var thetype = $(doms[i]).attr('ltype');
				    if(data[theid]=="-")data[theid] = "";
			        $.ligerui.get(theid).setValue(data[theid]);
				}
			},
			error : function(){
				alert(il8n.basic_user.disConnect);
			}
		});
    }
    
    
    ,modify_myself: function(){
    	basic_user.modify();
		
		$('[ligeruiid=group_code],[ligeruiid=money],[ligeruiid=credits],[ligeruiid=type],[ligeruiid=status]').attr("disabled","disabled");
		$('[ligeruiid=group_code],[ligeruiid=money],[ligeruiid=credits],[ligeruiid=type],[ligeruiid=status]').css("background-color","#EEEEEE");
		$('[ligeruiid=group_code],[ligeruiid=money],[ligeruiid=credits],[ligeruiid=type],[ligeruiid=status]').parent().css("background-color","#EEEEEE");
		
		$(".l-trigger,.l-trigger-icon",$('[ligeruiid=type],[ligeruiid=status]').parent()).hide();
    }
    
    ,searchOptions: {}    
    ,search: function(){
        var formD;
        if($.ligerui.get("formD")){
            formD = $.ligerui.get("formD");
            formD.show();
        }else{
            $(document.body).append("<form id='form'></form>");
            $("#form").ligerForm({
                inputWidth: 170
                ,labelWidth: 90
                ,space: 40
                ,fields: [
                     { display: il8n.basic_user.username, name: "username", newline: false, type: "text" }
                    ,{ display: il8n.basic_normal.type, name: "type", newline: true, type: "select", options :{data : basic_parameter_data.basic_user__type, valueField : "code" , textField: "value" } }
                    ,{ display: il8n.basic_normal.status, name: "status", newline: true, type: "select", options :{data : basic_parameter_data.basic_user__status, valueField : "code" , textField: "value" } }
                    ,{ display: il8n.basic_user.group_code, name: "group_code", newline: true, type: "text" }
                ]
            }); 
            $.ligerDialog.open({
                 id: "formD"
                ,width: 350
                ,height: 200
                ,content: form
                ,title: il8n.basic_normal.search
                ,buttons : [
                    {text: il8n.basic_normal.clear, onclick:function(){
						$.ligerui.get("basic_user__grid").options.parms.search = "{}";
						$.ligerui.get("basic_user__grid").setOptions({newPage:1});
						$.ligerui.get("basic_user__grid").loadData();
						
						var doms = $("[ligeruiid]",$('#form'));
						for(var i=0;i<doms.length;i++){
						    var theid = $(doms[i]).attr('ligeruiid');
						    $.ligerui.get(theid).setValue('');
						}
                    }},
                    {text: il8n.basic_normal.search, onclick:function(){
				    	var data = {};
						var doms = $("[ligeruiid]",$('#form'));
						for(var i=0;i<doms.length;i++){
						    var theid = $(doms[i]).attr('ligeruiid');
						    var thetype = $(doms[i]).attr('ltype');                                                        
						 
					        var thevalue = $.ligerui.get(theid).getValue();
					        if(thetype=='date')thevalue = $('#'+theid).val();
						    if(thevalue!="" && thevalue!=0 && thevalue!="0" && thevalue!=null){
						        eval("data."+theid+"='"+thevalue+"'");
						    }
						}
						
						$.ligerui.get("basic_user__grid").options.parms.search= $.ligerui.toJSON(data);
						$.ligerui.get("basic_user__grid").setOptions({newPage:1});
						$.ligerui.get("basic_user__grid").loadData();
                }}]
            });
        }
    }
    
    ,view: function(){
        var id = getParameter("id", window.location.toString() );
        $(document.body).html("<div id='menu'  ></div><div id='content' style='width:"+($(window).width()-170)+"px;margin-top:5px;'></div>");
        var htmls = "";
        $.ajax({
            url: config_path__basic_user__view+"&rand="+Math.random()
            ,data: {
                id: id 
                ,executor: top.basic_user.loginData.username
                ,session: top.basic_user.loginData.session
            },
            type: "POST",
            dataType: 'json',
            success: function(response) {
                if(response.status!="1")return;

            	var data = response.data;
            	basic_user.viewData = response.data;
            	if(typeof(data.photo)=="undefined") data.photo = '../file/tavatar.gif';
            	
            	var tablename = "basic_user";
            	for(var j in data){               		
            		if(j=='sql'||j=='type'||j=='status'||j=='used'||j=='structure'||j=='x')continue;
            		if(j=='photo'){
        				htmls += '<div style="position:absolute;right:5px;top:25px;background-color: rgb(220,250,245);width:166px;height:176px;"><img style="margin:2px;" src="'+data[j]+'" width="160" height="170" /></div>'
        				continue;
            		}
            		
            		if(j=="address"||j=="directions"||j=="remark"){
            			var key = il8n[tablename][j];
            			htmls+="<div style='width:100%;float:left;display:block;margin-top:5px;'/>";
            			htmls += "<span class='view_lable' style='width:95%'>"+key+"</span><span style='width:95%' class='view_data'>"+data[j]+"</span>";
            			continue;
            		}     
            		
            		if(j=="level_net"||j=="floor"||j=="height_bottom"||j=="owner"){
            			htmls+="<div style='width:100%;float:left;display:block;margin-top:5px;'/>";
            		}
       		
            		if(j=='id'){
            			tablename = "basic_normal";
            			htmls+="<div style='width:100%;float:left;display:block;margin-top:5px;'/>";
            		}           		

            		var key = il8n[tablename][j];
            		htmls += "<span class='view_lable'>"+key+"</span><span class='view_data'>"+data[j]+"</span>";
            	}; 
            	
            	$("#content").html(htmls);
                                
                //查看详细,页面上也有按钮的
                var items = [];                //TODO
                var permission = top.basic_user.permission;
                for(var i=0;i<permission.length;i++){
                    if(permission[i].code=='12'){
                        if(typeof(permission[i].children)=='undefined')return;
                        permission = permission[i].children;
                        break;
                    }
                }      
                for(var i=0;i<permission.length;i++){
                    if(permission[i].code=='1202'){
                        if(typeof(permission[i].children)=='undefined')return;
                        permission = permission[i].children;
                        break;
                    }
                }   
                for(var i=0;i<permission.length;i++){
                    if(permission[i].code=='120202'){
                        if(typeof(permission[i].children)=='undefined')return;
                        permission = permission[i].children;
                        break;
                    }
                }            
                
                for(var i=0;i<permission.length;i++){       
                    var theFunction = function(){};
                    if(permission[i].code=='12020203'){
                        //权限
                        theFunction = function(){
                                
                        };
                    }else if(permission[i].code=='12020223'){
                        //删除
                        theFunction = function(){
                            if(!confirm( il8n.basic_normal.sureToDelete ))return;
                            
                            $.ajax({
                                url: config_path__basic_user__remove,
                                data: {
                                    usernames: data.username
                                    
                                    ,executor: top.basic_user.loginData.username
                                    ,session: top.basic_user.loginData.session
                                }
                                ,type: "POST"
                                ,dataType: 'json'
                                ,success: function(response) {                                    
                                    alert(response.msg);
                                },
                                error : function(){
                                    alert("net error");
                                }
                            });    
                        };
                    }else if(permission[i].code=='12020222'){
                        theFunction = function(){
                            top.$.ligerDialog.open({ 
                                url: 'basic_user__modify.html?id='+data.id+'&random='+Math.random()
                                ,height: 350
                                ,width: 400
                                ,title: data.username
                                ,isHidden: false
                                ,id: 'basic_user__modify_'+data.id
                            });    
                        };
                    }
                    
                    items.push({line: true });    
                    items.push({text: permission[i].name , img:permission[i].icon , click : theFunction});                    
                }                

                $("#menu").ligerToolBar({
                    items:items
                });

            },
            error : function(){               
                alert(il8n.disConnect);
            }
        });
    }
};
insert into basic_user(id,username,password,group_code,type,status,money,credits) values (
					'2'
					,'admin'
					,'21232f297a57a5a743894a0e4a801fc3'
					,'10'
					,'10'
					,'10'
					,'10000'
					,'10000'									
			)
;insert into basic_group_2_user(user_code,group_code) values ('admin','10')
;insert into basic_user(id,username,password,group_code,type,status,money,credits) values (
					'3'
					,'guest'
					,'084e0343a0486ff05530df6c705c8bb4'
					,'99'
					,'10'
					,'10'
					,'10000'
					,'10000'									
			)
;insert into basic_group_2_user(user_code,group_code) values ('guest','99')
;insert into basic_user(id,username,password,group_code,type,status,money,credits) values (
					'4'
					,'3311-79-01-01--01'
					,'ef41329ed1ed0d3a6382873fe6cf0962'
					,'3311-79-01-01'
					,'20'
					,'10'
					,'10000'
					,'10000'									
			)
;insert into basic_group_2_user(user_code,group_code) values ('3311-79-01-01--01','3311-79-01-01')
;insert into basic_user(id,username,password,group_code,type,status,money,credits) values (
					'5'
					,'3311-79-01-01--02'
					,'ffd6873581ec21d42d125a6074cdd409'
					,'3311-79-01-01'
					,'20'
					,'10'
					,'10000'
					,'10000'									
			)
;insert into basic_group_2_user(user_code,group_code) values ('3311-79-01-01--02','3311-79-01-01')
;insert into basic_user(id,username,password,group_code,type,status,money,credits) values (
					'6'
					,'3311-79-01-02--01'
					,'f60c080143611aadef5f4119cb34c959'
					,'3311-79-01-02'
					,'20'
					,'10'
					,'10000'
					,'10000'									
			)
;insert into basic_group_2_user(user_code,group_code) values ('3311-79-01-02--01','3311-79-01-02')
;insert into basic_user(id,username,password,group_code,type,status,money,credits) values (
					'7'
					,'3311-79-01-02--02'
					,'e447204f31e4d8a0a37bf0cccff95a8e'
					,'3311-79-01-02'
					,'20'
					,'10'
					,'10000'
					,'10000'									
			)
;insert into basic_group_2_user(user_code,group_code) values ('3311-79-01-02--02','3311-79-01-02')
;insert into basic_user(id,username,password,group_code,type,status,money,credits) values (
					'8'
					,'3311-79-01-03--01'
					,'30c7ad2dc3465712a0679c52cb615226'
					,'3311-79-01-03'
					,'20'
					,'10'
					,'10000'
					,'10000'									
			)
;insert into basic_group_2_user(user_code,group_code) values ('3311-79-01-03--01','3311-79-01-03')
;insert into basic_user(id,username,password,group_code,type,status,money,credits) values (
					'9'
					,'3311-79-01-04--01'
					,'c469de1430d7a3ad016fbb9ac7630687'
					,'3311-79-01-04'
					,'20'
					,'10'
					,'10000'
					,'10000'									
			)
;insert into basic_group_2_user(user_code,group_code) values ('3311-79-01-04--01','3311-79-01-04')
;insert into basic_user(id,username,password,group_code,type,status,money,credits) values (
					'10'
					,'3311-79-01-05--01'
					,'b93810321c586dc17b1b5032945ee953'
					,'3311-79-01-05'
					,'20'
					,'10'
					,'10000'
					,'10000'									
			)
;insert into basic_group_2_user(user_code,group_code) values ('3311-79-01-05--01','3311-79-01-05')
;insert into basic_group(id,name,code,type,status) values ('2','管理员','10','10','10')
;insert into basic_group(id,name,code,type,status) values ('3','注册待审批','98','10','10')
;insert into basic_group(id,name,code,type,status) values ('4','访客','99','10','10')
;insert into basic_group(id,name,code,type,status) values ('5','组管理员','X1','50','10')
;insert into basic_node(name,code,tablename) values ('浙江省','33','basic_group')
;insert into basic_node(name,code,tablename) values ('丽水市','3311','basic_group')
;insert into basic_node(name,code,tablename) values ('丽水市水利管理业','3311-79','basic_group')
;insert into basic_group(id,name,code,type,status) values ('9','丽水市水利局','3311-79-01','30','10')
;insert into basic_group(id,name,code,type,status) values ('10','科室1','3311-79-01-01','40','10')
;insert into basic_group(id,name,code,type,status) values ('11','科室2','3311-79-01-02','40','10')
;insert into basic_group(id,name,code,type,status) values ('12','科室3','3311-79-01-03','40','10')
;insert into basic_group(id,name,code,type,status) values ('13','科室4','3311-79-01-04','40','10')
;insert into basic_group(id,name,code,type,status) values ('14','科室5','3311-79-01-05','40','10')
;insert into basic_permission(name,code,type,icon,path) values ('登陆','10','20','../file/icon48x48/10.png','basic_user__login.html')
;insert into basic_permission(name,code,type,icon,path) values ('注册','13','20','../file/icon48x48/13.png','basic_user__add_register.html')
;insert into basic_permission(name,code,type,icon,path) values ('关于本系统','99','20','../file/icon48x48/99.png','about.html')
;insert into basic_permission(name,code,type,icon,path) values ('用户中心','11','20','../file/icon48x48/11.png','basic_user__center.html')
;insert into basic_permission(name,code,type,icon,path) values ('修改','1123','30','../file/icon16x16/23.png','')
;insert into basic_permission(name,code,type,icon,path) values ('退出','1199','30','../file/icon16x16/99.png','')
;insert into basic_permission(name,code,type,icon,path) values ('管理中心','12','10','../file/icon48x48/12.png','')
;insert into basic_permission(name,code,type,icon,path) values ('用户组','1201','20','../file/icon48x48/1201.png','basic_group__grid.html')
;insert into basic_permission(name,code,type,icon,path) values ('查询','120101','30','../file/icon16x16/01.png','')
;insert into basic_permission(name,code,type,icon,path) values ('详细','120102','30','../file/icon16x16/02.png','')
;insert into basic_permission(name,code,type,icon,path) values ('添加','120121','30','../file/icon16x16/21.png','')
;insert into basic_permission(name,code,type,icon,path) values ('修改','120122','30','../file/icon16x16/22.png','')
;insert into basic_permission(name,code,type,icon,path) values ('删除','120123','30','../file/icon16x16/23.png','')
;insert into basic_permission(name,code,type,icon,path) values ('权限','120140','30','../file/icon16x16/40.png','')
;insert into basic_permission(name,code,type,icon,path) values ('用户列表','1202','20','../file/icon48x48/1202.png','basic_user__grid.html')
;insert into basic_permission(name,code,type,icon,path) values ('查询','120201','30','../file/icon16x16/01.png','')
;insert into basic_permission(name,code,type,icon,path) values ('详细','120202','30','../file/icon16x16/02.png','')
;insert into basic_permission(name,code,type,icon,path) values ('导入','120211','30','../file/icon16x16/11.png','')
;insert into basic_permission(name,code,type,icon,path) values ('导出','120212','30','../file/icon16x16/12.png','')
;insert into basic_permission(name,code,type,icon,path) values ('添加','120221','30','../file/icon16x16/21.png','')
;insert into basic_permission(name,code,type,icon,path) values ('修改','120222','30','../file/icon16x16/22.png','')
;insert into basic_permission(name,code,type,icon,path) values ('删除','120223','30','../file/icon16x16/23.png','')
;insert into basic_permission(name,code,type,icon,path) values ('参数','1203','20','../file/icon48x48/1203.png','basic_parameter__grid.html')
;insert into basic_permission(name,code,type,icon,path) values ('查询','120301','30','../file/icon16x16/01.png','')
;insert into basic_permission(name,code,type,icon,path) values ('添加','120321','30','../file/icon16x16/21.png','')
;insert into basic_permission(name,code,type,icon,path) values ('删除','120323','30','../file/icon16x16/23.png','')
;insert into basic_permission(name,code,type,icon,path) values ('重置内存','120342','30','../file/icon16x16/42.png','')
;insert into basic_permission(name,code,type,icon,path) values ('文件夹图标','xx','10','../file/icon48x48/xx.png','')
;insert into basic_permission(name,code,type,icon,path) values ('功能弹窗图标','xx01','20','../file/icon48x48/xx01.png','somepage_XX01.html')
;insert into basic_permission(name,code,type,icon,path) values ('功能弹窗图标2','xx02','20','../file/icon48x48/xx02.png','somepage_XX02.html')
;insert into basic_permission(name,code,type,icon,path) values ('按钮1','xx0201','30','../file/icon16x16/01.png','')
;insert into basic_permission(name,code,type,icon,path) values ('按钮2','xx0202','30','../file/icon16x16/02.png','')
;insert into basic_permission(name,code,type,icon,path) values ('按钮3','xx0203','30','../file/icon16x16/03.png','')
;insert into basic_group_2_permission (permission_code,group_code) values('10','99')
;insert into basic_group_2_permission (permission_code,group_code) values('13','99')
;insert into basic_group_2_permission (permission_code,group_code) values('99','99')
;insert into basic_group_2_permission (permission_code,group_code) values('11','10')
;insert into basic_group_2_permission (permission_code,group_code) values('11','3311-79-01-01')
;insert into basic_group_2_permission (permission_code,group_code) values('11','3311-79-01-02')
;insert into basic_group_2_permission (permission_code,group_code) values('11','3311-79-01-03')
;insert into basic_group_2_permission (permission_code,group_code) values('11','3311-79-01-04')
;insert into basic_group_2_permission (permission_code,group_code) values('11','3311-79-01-05')
;insert into basic_group_2_permission (permission_code,group_code) values('1123','10')
;insert into basic_group_2_permission (permission_code,group_code) values('1123','3311-79-01-01')
;insert into basic_group_2_permission (permission_code,group_code) values('1123','3311-79-01-02')
;insert into basic_group_2_permission (permission_code,group_code) values('1123','3311-79-01-03')
;insert into basic_group_2_permission (permission_code,group_code) values('1123','3311-79-01-04')
;insert into basic_group_2_permission (permission_code,group_code) values('1123','3311-79-01-05')
;insert into basic_group_2_permission (permission_code,group_code) values('1199','10')
;insert into basic_group_2_permission (permission_code,group_code) values('1199','3311-79-01-01')
;insert into basic_group_2_permission (permission_code,group_code) values('1199','3311-79-01-02')
;insert into basic_group_2_permission (permission_code,group_code) values('1199','3311-79-01-03')
;insert into basic_group_2_permission (permission_code,group_code) values('1199','3311-79-01-04')
;insert into basic_group_2_permission (permission_code,group_code) values('1199','3311-79-01-05')
;insert into basic_group_2_permission (permission_code,group_code) values('12','10')
;insert into basic_group_2_permission (permission_code,group_code) values('1201','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120101','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120102','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120121','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120122','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120123','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120140','10')
;insert into basic_group_2_permission (permission_code,group_code) values('1202','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120201','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120202','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120211','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120212','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120221','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120222','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120223','10')
;insert into basic_group_2_permission (permission_code,group_code) values('1203','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120301','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120321','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120323','10')
;insert into basic_group_2_permission (permission_code,group_code) values('120342','10')
;insert into basic_group_2_permission (permission_code,group_code) values('xx','10')
;insert into basic_group_2_permission (permission_code,group_code) values('xx','3311-79-01-01')
;insert into basic_group_2_permission (permission_code,group_code) values('xx','3311-79-01-02')
;insert into basic_group_2_permission (permission_code,group_code) values('xx','3311-79-01-03')
;insert into basic_group_2_permission (permission_code,group_code) values('xx','3311-79-01-04')
;insert into basic_group_2_permission (permission_code,group_code) values('xx','3311-79-01-05')
;insert into basic_group_2_permission (permission_code,group_code) values('xx01','10')
;insert into basic_group_2_permission (permission_code,group_code) values('xx01','3311-79-01-01')
;insert into basic_group_2_permission (permission_code,group_code) values('xx01','3311-79-01-02')
;insert into basic_group_2_permission (permission_code,group_code) values('xx01','3311-79-01-03')
;insert into basic_group_2_permission (permission_code,group_code) values('xx01','3311-79-01-04')
;insert into basic_group_2_permission (permission_code,group_code) values('xx01','3311-79-01-05')
;insert into basic_group_2_permission (permission_code,group_code) values('xx02','10')
;insert into basic_group_2_permission (permission_code,group_code) values('xx02','3311-79-01-02')
;insert into basic_group_2_permission (permission_code,group_code) values('xx02','3311-79-01-03')
;insert into basic_group_2_permission (permission_code,group_code) values('xx02','3311-79-01-04')
;insert into basic_group_2_permission (permission_code,group_code) values('xx02','3311-79-01-05')
;insert into basic_group_2_permission (permission_code,group_code) values('xx0201','10')
;insert into basic_group_2_permission (permission_code,group_code) values('xx0201','3311-79-01-03')
;insert into basic_group_2_permission (permission_code,group_code) values('xx0201','3311-79-01-04')
;insert into basic_group_2_permission (permission_code,group_code) values('xx0201','3311-79-01-05')
;insert into basic_group_2_permission (permission_code,group_code) values('xx0202','10')
;insert into basic_group_2_permission (permission_code,group_code) values('xx0202','3311-79-01-04')
;insert into basic_group_2_permission (permission_code,group_code) values('xx0202','3311-79-01-05')
;insert into basic_group_2_permission (permission_code,group_code) values('xx0203','10')
;insert into basic_group_2_permission (permission_code,group_code) values('xx0203','3311-79-01-05')

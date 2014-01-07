create table basic_parameter (
  code varchar(200) not null
 ,value varchar(200) 
 ,reference varchar(200) not null
 ,extend1 int 
 ,extend2 int 
 ,extend3 int 
 ,extend4 varchar(200) 
 ,extend5 varchar(200) 
 ,extend6 varchar(200) 
 );
 alter table basic_parameter add constraint pk__b_p primary key ( code,reference );
 
 create table basic_user (
  username varchar(200) not null unique
 ,password varchar(200) not null
 ,money int default '0'
 ,credits int default '0'
 ,group_code varchar(200) not null
 ,group_all varchar(200) 
 ,id_person int 
 ,lastlogintime datetime 
 ,lastlogouttime datetime 
 ,count_actions int 
 ,count_actions_period int 
 ,count_login int 
 ,id int primary key
 ,creater_id int 
 ,updater_id int 
 ,creater_group_code varchar(200) 
 ,time_created datetime 
 ,time_lastupdated datetime 
 ,count_updated int 
 ,type int not null
 ,status int not null
 ,remark varchar(200) 
 );
 
 insert into basic_parameter (code,value,reference,extend4) values ('10','正常','basic_user__status','10');
 insert into basic_parameter (code,value,reference,extend4) values ('20','关闭','basic_user__status','20');
 insert into basic_parameter (code,value,reference,extend4) values ('10','系统','basic_user__type','10');
 insert into basic_parameter (code,value,reference,extend4) values ('20','业务','basic_user__type','20');
 insert into basic_parameter (code,value,reference,extend4) values ('30','接口','basic_user__type','30');
 
 create table basic_user_session (
  user_id int unique
 ,user_code varchar(200) unique
 ,group_code varchar(200) 
 ,user_type varchar(200) 
 ,permissions varchar(3000) 
 ,groups varchar(200) 
 ,ip varchar(200) 
 ,client varchar(200) 
 ,gis_lat varchar(200) 
 ,gis_lot varchar(200) 
 ,lastaction varchar(200) 
 ,lastactiontime datetime 
 ,count_actions int 
 ,count_login int 
 ,session char(32) 
 ,status int 
 );
 
 insert into basic_parameter (code,value,reference,extend4) values ('10','WEB在线','basic_user_session__status','10');
 insert into basic_parameter (code,value,reference,extend4) values ('20','Android在线','basic_user_session__status','20');
 insert into basic_parameter (code,value,reference,extend4) values ('99','退出','basic_user_session__status','99');
 
 create table basic_memory (
  code varchar(200) 
 ,type int 
 ,extend1 int 
 ,extend2 int 
 ,extend3 int 
 ,extend4 varchar(200) 
 ,extend5 varchar(200) 
 ,extend6 varchar(200) 
 );
 
 create table basic_group (
  name varchar(200) 
 ,code varchar(200) unique
 ,id int primary key
 ,count_users int 
 ,type varchar(20) 
 ,status int 
 ,remark text 
 ,chief_id int 
 );
 
 insert into basic_parameter (code,value,reference,extend4) values ('10','系统','basic_group__type','10');
 insert into basic_parameter (code,value,reference,extend4) values ('30','单位','basic_group__type','30');
 insert into basic_parameter (code,value,reference,extend4) values ('40','部门','basic_group__type','40');
 insert into basic_parameter (code,value,reference,extend4) values ('50','职位','basic_group__type','50');
 insert into basic_parameter (code,value,reference,extend4) values ('10','正常','basic_group__status','10');
 insert into basic_parameter (code,value,reference,extend4) values ('20','关闭','basic_group__status','20');
 
 create table basic_group_2_user (
  user_code varchar(40) not null
 ,group_code varchar(40) not null
 );
 alter table basic_group_2_user add constraint pk__b_g_2_u primary key ( user_code,group_code );
 
 create table basic_permission (
  name varchar(20) 
 ,type int 
 ,code varchar(20) unique
 ,icon varchar(200)  
 ,path varchar(200)  
 ,remark varchar(200)  
 ,status int default '0'
 );
 
 insert into basic_parameter (code,value,reference,extend4) values ('10','节点','basic_permission__type','10');
 insert into basic_parameter (code,value,reference,extend4) values ('20','页面','basic_permission__type','20');
 insert into basic_parameter (code,value,reference,extend4) values ('30','按钮','basic_permission__type','30');
 insert into basic_parameter (code,value,reference,extend4) values ('40','逻辑','basic_permission__type','40');
 
 create table basic_group_2_permission (
  permission_code varchar(40) not null
 ,group_code varchar(40) not null
 ,cost int default '0'
 ,credits int default '0'
 );
 alter table basic_group_2_permission add constraint pk__b_g_2_p primary key ( permission_code,group_code );
 
 create table basic_node (
  code varchar(200)  
 ,name varchar(200)  
 ,tablename varchar(200)  
 );

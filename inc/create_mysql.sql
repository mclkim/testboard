############################################################
# 테이블 명<table_name>을 변경하여 생성하기
# 실행방법
# mysql -u [user] -p < create_mysql.sql
############################################################
# 데이타베이스(DATABASE) 생성
# CREATE SCHEMA `testboard` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
# use `testboard`;
############################################################
# 테이블(TABLE) 생성
create table test #<table_name>
(
    id                  bigint  unsigned not null primary key,
    pid                 bigint  unsigned not null default 0,
    ppid                bigint  unsigned not null default 0,
    depth               integer default 0,
    name                varchar(20) null,
    email               varchar(40) null,
    homeurl             varchar(255) null,
    jumin               varchar(20) null,
    category            varchar(40) null,#레코드 추가(2004.05.14)
    subject             varchar(80) null,
    passwd              varchar(10) null,
    htmltype            varchar(1) default '0',
    privatetype         varchar(1) default '0',
    attachfile          text null,
    small_image         varchar(20) null,
    readcount           integer default 0,
    content             text null,
    opinion             text null,
    anniversary         varchar(20) null,#레코드 추가(2003.04.14)
    in_date             varchar(10) null,
    in_time             integer default 0,
    mo_time             integer default 0,
    ip                  varchar(15) null,
    domain              varchar(80) null,
    index(ppid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

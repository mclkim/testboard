-----------------------------------------------------------
-- sqlplus scott/tiger@localhost @create_oracle.sql
-----------------------------------------------------------
-- 테이블(TABLE) 생성
create table test--<table_name>
(
    id                  number not null primary key,
    pid                 number default 0 not null,
    ppid                number default 0 not null,
    depth               number default 0,
    name                varchar2(20),
    email               varchar2(40),
    homeurl             varchar2(255),
    jumin               varchar2(20),
    category            varchar2(40),
    subject             varchar2(80),
    passwd              varchar2(10),
    htmltype            varchar2(1) default '0',
    privatetype         varchar2(1) default '0',
    attachfile          varchar2(4000),
    small_image         varchar(20),
    readcount           number default 0,
    content             clob,
    opinion             clob,
    anniversary         varchar2(20),
    in_date             varchar2(10),
    in_time		number default 0,
    mo_time		number default 0,
    ip                  varchar2(15),
    domain              varchar2(80)
);
-----------------------------------------------------------
-- 인덱스(index) 생성
--create index <table_name>_idx_02 on <table_name> (ppid);
--create index test_idx_02 on test (ppid);
-----------------------------------------------------------
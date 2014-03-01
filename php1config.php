<?php
/************************************************************************\
 * 프로그램명 : 환경설정
 * 특기사항   : 1.환경파일 포멧을 변경함.(2001.03.23)
                2.각 폴더를 볼수 없게...(2001.03.23)
                3.찾기,정렬 프로그램 파라미터
                4.아이콘 버튼로 바꿀때...(2001.03.23)
                5.메세지 국가별 파일설정(2002.07.13)
                6.회원 데이타베이스 정의(2001.05.01)
                7.환경설정 초기값(2001.09.05)
 * 관련테이블:
 * services:
 * 작 성 자: 김명철
 * 작성일자: 2001/01
 * 수 정 자:
 * 수정일자:
 * 수정내역:
\************************************************************************/
if (defined ( '_config_' ))
	return;
define ( '_config_', true );
/**
 * **********************************************************************\
 * [].아래부터 수정,삭제를 하시믄 안돼요...
 * \***********************************************************************
 */
require_once ('inc/common.inc');
require_once ('php1lib.php');
/**
 * **********************************************************************\
 * [].시스템설정 초기값
 * \***********************************************************************
 */
function setsystemconfig($db) {
	// 프로그램 버젼관리에 대한 정보
	$sysconf ["program_name"] = program_name;
	$sysconf ["program_ver"] = program_ver;
	$sysconf ["program_date"] = program_date;
	
	// 저작권 보호에 대한 정보
	$sysconf ["description"] = "무료 게시판 소스";
	$sysconf ["author"] = "김명철";
	$sysconf ["contact"] = "durk007@chollian.net";
	$sysconf ["content"] = "$sysconf[program_name] $sysconf[program_ver] $sysconf[program_date]";
	$sysconf ["keywords"] = "$sysconf[program_name] $sysconf[program_ver] $sysconf[program_date]";
	
	// 스킨파일을 만든사람에 대한 정보
	$sysconf ["board_maker"] = "durk007@chollian.net";
	$sysconf ["board_home"] = "http://testboard.wo.tc";
	$sysconf ["skin_maker"] = "durk007@chollian.net";
	$sysconf ["skin_home"] = "http://testboard.wo.tc";
	
	// 프로그램명
	$sysconf ["test"] = "test.php";
	$sysconf ["test4conv"] = "test4conv.php";
	$sysconf ["testadmin"] = "testadmin.php";
	$sysconf ["testboard"] = "testboard.php";
	$sysconf ["testdata"] = "testdata.php";
	$sysconf ["testmail"] = "testmail.php";
	$sysconf ["testman"] = "testman.php";
	$sysconf ["testmember"] = "testmember.php";
	
	// 절대경로
	$sysconf ["path_host"] = path_fix ( dirname ( (__file__) ) );
	
	// 데이타 절대경로
	$sysconf ["path_docs"] = path_fix ( "$sysconf[path_host]/docs" );
	$sysconf ["path_images"] = path_fix ( "$sysconf[path_host]/images" );
	$sysconf ["path_inc"] = path_fix ( "$sysconf[path_host]/inc" );
	$sysconf ["path_skins"] = path_fix ( "$sysconf[path_host]/skins" );
	$sysconf ["path_users"] = path_fix ( "$sysconf[path_host]/db/.users" );
	$sysconf ["path_group"] = path_fix ( "$sysconf[path_host]/db/.group" );
	$sysconf ["path_db"] = path_fix ( "$sysconf[path_host]/db/$db" );
	
	$sysconf ["path_data"] = path_fix ( "$sysconf[path_db]/data" );
	$sysconf ["path_image"] = path_fix ( "$sysconf[path_db]/image" );
	$sysconf ["path_memo"] = path_fix ( "$sysconf[path_db]/memo" );
	$sysconf ["path_temp"] = path_fix ( "$sysconf[path_db]/temp" );
	$sysconf ["path_upload"] = path_fix ( "$sysconf[path_db]/upload" );
	$sysconf ["home_users"] = path_fix ( "$sysconf[path_db]/users" ); // 추가(2003.09.09)
	                                                                  
	// 상대경로(url)
	$sysconf ["path_home"] = http_url;
	
	// 데이타 상대경로(url)
	$sysconf ["home_docs"] = "$sysconf[path_home]/docs";
	$sysconf ["home_images"] = "$sysconf[path_home]/images";
	$sysconf ["home_inc"] = "$sysconf[path_home]/inc";
	$sysconf ["home_skins"] = "$sysconf[path_home]/skins";
	$sysconf ["home_db"] = "$sysconf[path_home]/db/$db";
	
	$sysconf ["home_image"] = "$sysconf[home_db]/upload";
	$sysconf ["icon_image"] = "$sysconf[home_images]/icon/default";
	$sysconf ["type_image"] = "$sysconf[home_images]/type/default";
	$sysconf ["img_image"] = "$sysconf[home_images]/img";
	$sysconf ["skin_image"] = "$sysconf[home_images]/file";
	
	// 환경파일 저장경로
	// 1.환경파일 포멧을 변경함.(2001.03.23)
	$sysconf ["file_cfg"] = path_fix ( "$sysconf[path_db]/$db.cgi" );
	$sysconf ["file_idx"] = path_fix ( "$sysconf[path_db]/$db.idx" );
	$sysconf ["file_cnt"] = path_fix ( "$sysconf[path_db]/$db.cnt" );
	$sysconf ["file_log"] = path_fix ( "$sysconf[path_db]/$db.log" );
	$sysconf ["file_sql"] = path_fix ( "$sysconf[path_db]/$db.sql" );
	
	// 2.각 폴더를 볼수 없게...(2001.03.23)
	$sysconf ["file_def"] = path_fix ( "$sysconf[path_host]/db/system.cgi" );
	$sysconf ["file_html"] = path_fix ( "$sysconf[path_host]/index.html" );
	$sysconf ["file_zip"] = path_fix ( "$sysconf[path_host]/zipfinder.txt" );
	
	// 3.찾기 프로그램 파라미터
	// 4.정렬 프로그램 파라미터
	if (is_windows ()) {
		$sysconf ["grepcmd"] = "grep";
		$sysconf ["findall"] = "grep -lio";
		$sysconf ["findcmd"] = "grep -lwo";
		$sysconf ["findfile"] = "grep -wo";
		$sysconf ["sortcmd"] = "grep -o";
		$sysconf ["listcmd"] = "dir /b /d";
		$sysconf ["typecmd"] = "type";
		$sysconf ["movecmd"] = "move /y";
		$sysconf ["accecmd"] = "";
	} else {
		$sysconf ["grepcmd"] = "grep";
		$sysconf ["findall"] = "grep -li";
		$sysconf ["findcmd"] = "grep -lw";
		$sysconf ["findfile"] = "grep -w";
		$sysconf ["sortcmd"] = "grep";
		$sysconf ["listcmd"] = "ls -1";
		$sysconf ["typecmd"] = "cat";
		$sysconf ["movecmd"] = "mv -f";
		$sysconf ["accecmd"] = "chmod -r " . mode_symbols;
	} // endif
	  
	// 메인프로그램 설정
	$sysconf ["langfile"] = array (
			'kor.inc',
			'eng.inc' 
	);
	$sysconf ["testboard"] = array (
			'testboard.php',
			'testmember.php',
			'testmail.php' 
	);
	$sysconf ["db_engine"] = array (
			'file',
			'mysql',
			'oci8',
			'pop3' 
	);
	$sysconf ["skinname"] = array (
			'skin1album',
			'skin1babel',
			'skin1beos',
			'skin1blog',
			'skin2blog',
			'skin1calendar',
			'skin1link',
			'skin1main',
			'skin1naver',
			'skin1notice',
			'skin1pds',
			'skin1pink',
			'skin1poll',
			'skin1rand' 
	);
	return $sysconf;
}
/**
 * **********************************************************************\
 * [].설정 초기값
 * \***********************************************************************
 */
function setdefineconfig() {
	// 프로그램 버젼관리에 대한 정보
	$defconf ["program_name"] = program_name;
	$defconf ["program_ver"] = program_ver;
	$defconf ["program_date"] = program_date;
	
	// 관리자 정보에 대한 설명
	// 본 소스는 초기 비밀번호가 있슴다.
	// 관리자의 아이디',초기비밀번호',이메일',홈페이지를 설정할 수 있슴다.
	$defconf ["admin_name"] = '@-^-@';
	$defconf ["admin_mail"] = 'durk007@chollian.net';
	$defconf ["admin_home"] = 'http://testboard.wo.tc';
	$defconf ["admin_password"] = '';
	
	$defconf ["langfile"] = 'kor.inc';
	$defconf ["db_engine"] = 'file';
	
	// 데이타베이스 환경설정(mysql)
	$defconf ["file_host"] = str_replace ( '\\', '/', dirname ( __FILE__ ) ) . "/db"; // 호스트
	$defconf ["file_user"] = 'n/a'; // 사용자
	$defconf ["file_pass"] = 'n/a'; // 암호
	$defconf ["file_name"] = 'n/a'; // 데이타베이스
	                                
	// 데이타베이스 환경설정(mysql)
	$defconf ["mysql_host"] = 'localhost'; // 호스트
	$defconf ["mysql_user"] = 'n/a'; // 사용자
	$defconf ["mysql_pass"] = 'n/a'; // 암호
	$defconf ["mysql_name"] = 'n/a'; // 데이타베이스
	                                 
	// 데이타베이스 환경설정(oracle)
	$defconf ["oracle_host"] = 'localhost'; // 호스트
	$defconf ["oracle_user"] = 'n/a'; // 사용자
	$defconf ["oracle_pass"] = 'n/a'; // 암호
	$defconf ["oracle_name"] = 'n/a'; // 데이타베이스
	                                  
	// 환경설정(pop3)
	$defconf ["pop3_host"] = 'localhost'; // 호스트
	$defconf ["pop3_user"] = 'n/a'; // 사용자
	$defconf ["pop3_pass"] = 'n/a'; // 암호
	$defconf ["pop3_name"] = 'n/a'; // 데이타베이스
	
	return $defconf;
}
function loaddefineconfig($filename) {
	// 환경설정 초기값
	$data = setdefineconfig ();
	// 환경설정파일
	$temp = file2array ( $filename );
	
	return a4b ( $data, $temp );
}
function savedefineconfig($filename, $defconf) {
	return array2file ( $filename, $defconf );
}
/**
 * **********************************************************************\
 * //환경설정 초기값
 * \***********************************************************************
 */
function setenvironmentconfig($defconf) {
	$envconf ["access_read"] = 0; // 글 읽기권한(0:누구나,1:관리자만)
	$envconf ["access_reply"] = 0; // 답 쓰기권한(0:누구나,1:관리자만)
	$envconf ["access_write"] = 0; // 글 쓰기권한(0:누구나,1:관리자만)
	$envconf ["admin_home"] = $defconf ["admin_home"];
	$envconf ["admin_mail"] = $defconf ["admin_mail"];
	$envconf ["admin_name"] = $defconf ["admin_name"];
	$envconf ["admin_password"] = $defconf ["admin_password"];
	$envconf ["autohidecont"] = 0; // 기본 본문 메시지
	$envconf ["autolink"] = 1; // 본문내에 url이 있으면 자동 인식
	$envconf ["autotitle"] = 2; // 제목과 그림 출력방식
	$envconf ["backcolor"] = "#ffffff"; // 바탕에 출력되는 배경색
	$envconf ["backimage"] = "";
	$envconf ["badbanner"] = ".cashsurfers.com,1500통,8억,가입축하금,노머니,돈나무,a11advantage,adcamp,adclick,adhappy.co,alladvantage";
	$envconf ["badhome"] = "testhome.co.kr";
	$envconf ["badip"] = "127.0.0.1";
	$envconf ["badmail"] = "test@testhome.co.kr";
	$envconf ["badname"] = "홍길동";
	$envconf ["badprotect"] = 1; // 글 차단
	$envconf ["badword"] = "새끼,병신,지랄,씨팔,십팔,니기미,찌랄,쌍년,쌍놈,빙신,좆까,좆같은게,잡놈,벼엉신,씨발,시벌,씨벌,떠그랄,좆밥,등신,싸가지,미친놈,미친넘,찌랄,씨밸넘,성인,成人,몰카,포르노";
	$envconf ["boardalign"] = "center"; // 게시판 정렬
	$envconf ["boardimage"] = "./images/title.gif";
	$envconf ["boardtitle"] = "$defconf[program_name] $defconf[program_ver] $defconf[program_date]";
	$envconf ["bordercolor"] = "silver"; // 테이블 선 색
	$envconf ["browsertitle"] = "$defconf[program_name] $defconf[program_ver] $defconf[program_date]";
	$envconf ["cellpadding"] = "3"; // 테이블(셀) 여백 설정
	$envconf ["cellspacing"] = "0"; // 테이블(셀) 칸 간견
	$envconf ["charset"] = "utf-8";
	$envconf ["contbuttonpos"] = 2; // 본문버튼위치(0:출력하지않음,1:상단,2:하단,3:상하모두)
	$envconf ["db_engine"] = $defconf ["db_engine"]; // 데이타베이스 설정
	$envconf ["dan_size"] = "2"; // 단의 갯수
	$envconf ["databkcol"] = "#fafaff"; // 본문 내용 배경색
	$envconf ["dataheadbkcol"] = "#dfefff"; // 본문 제목 배경색
	$envconf ["dataheadtxtcol"] = "#000000"; // 본문 제목 글자색
	$envconf ["datatxtcol"] = "#000000"; // 본문 내용 글자색
	$envconf ["defcolor"] = "#000000"; // 바탕에 출력되는 글자색
	$envconf ["defcontent"] = ""; // 기본 본문 메시지
	$envconf ["deffont"] = "굴림";
	$envconf ["deffontsize"] = "10";
	$envconf ["dobaelimit"] = "0"; // 시간&ip당 글쓰기 갯수를 제한합니다.
	$envconf ["emailcontent"] = "";
	$envconf ["emailsubject"] = "";
	$envconf ["entertype"] = 1; // cr/lf 처리 옵션
	$envconf ["fileicon"] = 1; // 파일 형식 아이콘
	$envconf ["filelimit"] = "2048"; // 첨부파일 제한크기(단위 1k)
	$envconf ["attachfile"] = 1; // 첨부파일 가능여부(0:disable,1:enable)
	$envconf ["foldericon"] = 1; // 폴더 아이콘
	$envconf ["gradcolor"] = "#bafaca"; // 그라데이션 기능
	$envconf ["hlinkcolor"] = "deeppink"; // 링크 컬러(hover)
	$envconf ["htmlhead"] = "<p align=center>"; // 사용자 태그 윗부분
	$envconf ["subject"] = ""; // 제목입력 부분
	$envconf ["htmltail"] = "</p>"; // 사용자 태그 아래부분
	$envconf ["langfile"] = $defconf ["langfile"];
	$envconf ["linkcolor"] = "blue"; // 링크 컬러
	$envconf ["linktarget"] = "_blank";
	$envconf ["linktype"] = 0; // 링크타입(0:밑줄없음,1:밑줄)
	$envconf ["listbkcoleven"] = "#f7f7f7"; // 목록 짝수 배경색
	$envconf ["listbkcolodd"] = "#ffffff"; // 목록 홀수 배경색
	$envconf ["listbuttonpos"] = 2; // 목록버튼위치(0:출력하지않음,1:상단,2:하단,3:상하모두)
	$envconf ["listheadbkcol"] = "#87caff"; // 목록 헤더 배경색
	$envconf ["listheadtxtcol"] = "#0060ff"; // 목록 헤더 글자색
	$envconf ["listtxtcoleven"] = "#000000"; // 목록 짝수 글자색
	$envconf ["listtxtcolodd"] = "#000000"; // 목록 홀수 글자색
	$envconf ["mailhtml"] = 1; // html형식으로 메일보내기
	$envconf ["maxcont"] = "0"; // 본문입력 제한
	$envconf ["maxsubj"] = "80"; // 제목입력 제한
	$envconf ["mustattachfile"] = 0; // 첨부파일 필수입력
	$envconf ["musthome"] = 0; // 홈페이지 필수입력
	$envconf ["mustjumin"] = 0; // 주민번호 필수입력
	$envconf ["mustmail"] = 0; // 이메일 필수입력
	$envconf ["pageline"] = "12"; // 한 페이지당 출력할 목록 갯수
	$envconf ["postadmin"] = 0; // 글 작성시 관리자에게 메일 발송
	$envconf ["postreply"] = 0; // 답변글 작성시 원본 글작성자에게 메일 발송
	$envconf ["reline"] = ":";
	$envconf ["resubject"] = "re:";
	$envconf ["retext"] = "wrote:";
	$envconf ["readcount"] = 1; // 조회수
	$envconf ["short_cont"] = "0"; // 본문출력길이
	$envconf ["short_name"] = "8"; // 이름출력길이
	$envconf ["short_subject"] = "50"; // 제목출력길이
	$envconf ["show_anniversary"] = 0; // 기념일
	$envconf ["show_checkbox"] = 0; // 체크박스 기능
	$envconf ["show_opinion"] = 0; // 의견달기 메뉴
	$envconf ["show_docnum"] = 1; // 문서번호 보이기
	$envconf ["show_findmenu"] = 1; // 검색메뉴 보이기
	$envconf ["findlist"] = "subject,name,content,email,homeurl,in_date,readcount,ip"; // 검색
	$envconf ["show_homepage"] = 1; // 홈페이지 입력
	$envconf ["show_mail"] = 1; // 이메일 입력
	$envconf ["show_htmltype"] = 1; // 태그문서 입력
	$envconf ["show_ip"] = 0; // 아이피 보이기
	$envconf ["show_inputform"] = 0; // 입력폼
	$envconf ["show_list"] = 1; // 본문 밑으로 문서목록 보이기(해당 글과 관련된 글)
	$envconf ["show_name"] = 1; // 이름
	$envconf ["show_pagemenu"] = 1; // 페이지 이동메뉴 보이기
	$envconf ["show_passwd"] = 1; // 비밀번호 입력
	$envconf ["show_privatetype"] = 1; // 비공개 문서 입력
	$envconf ["show_sortmenu"] = 0; // 정렬버튼 보이기
	$envconf ["show_indate"] = 1; // 등록일자 보이기
	$envconf ["show_modate"] = 0; // 수정일자 보이기
	$envconf ["skinname"] = ""; // 스킨 파일
	$envconf ["smtp_server"] = "localhost";
	$envconf ["tableborder"] = "0"; // 테이블 선 두께
	$envconf ["tablewidth"] = "95%"; // 게시판 너비
	$envconf ["testboard"] = $defconf ["testboard"]; // 메인프로그램 설정
	$envconf ["titlebkcol"] = "#336699"; // 타이틀 배경색
	$envconf ["titletxtcol"] = "#ffffff"; // 타이틀 글자색
	$envconf ["usegrad"] = 0; // 그라데이션 기능
	$envconf ["useskin"] = 0; // 스킨 사용
	$envconf ["useuser"] = 0; // 회원관리
	$envconf ["userprotect"] = 0; // 사용자 차단
	$envconf ["vlinkcolor"] = "purple"; // 링크 컬러(visited)
	$envconf ["show_note"] = 0; // 공지여부
	$envconf ["notefile"] = ""; // 공지파일
	
	$envconf ["refresh_time"] = 300;
	$envconf ["show_category"] = 0; // 카테고리
	$envconf ["category"] = "낙서장,게시판"; // 카테고리
	
	return $envconf;
}
function loadenvironmentconfig($filename) {
	global $sysconf, $defconf;
	
	$def = array_merge ( $sysconf, $defconf );
	
	// 환경설정 초기값
	$data = setenvironmentconfig ( $def );
	
	// 환경설정파일
	$temp = file2array ( $filename );
	
	return a4b ( $data, $temp );
}
function saveenvironmentconfig($filename, $envconf) {
	return array2file ( $filename, $envconf );
}
/**
 * **********************************************************************\
 * \***********************************************************************
 */
unset ( $sysconf );
unset ( $defconf );
unset ( $envconf );

// 환경설정 초기값 설정(시스템)
$sysconf = setsystemconfig ( $db );

// 환경설정 초기값 읽기(기본)
$defconf = loaddefineconfig ( $sysconf ["file_def"] );

// 환경설정 초기값 읽기(게시판)
$envconf = loadenvironmentconfig ( $sysconf ["file_cfg"] );

// 국가별 메세지 읽기(2002.07.13)
$langfile = path_fix ( "$sysconf[path_inc]/$envconf[langfile]" );
if (is_readable ( $langfile ))
	require_once ($langfile);

/**
 * **********************************************************************
 * _debug($sysconf);
 * _debug($defconf);
 * _debug($envconf);
 * **********************************************************************
 */

/**
 * **********************************************************************
 * [].설치파일이 없을 경우 설치 프로그램으로 점프하기
 * **********************************************************************
 */
if ($sysconf ["program_date"] != $defconf ["program_date"] && ! eregi ( "setup.php", php_self )) {
	echo "<script> this.location.replace('setup.php'); </script>";
	exit ();
}
?>

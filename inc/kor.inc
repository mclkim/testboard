<?php
/************************************************************************\
[].한글이 아닌 다른언어의 게시판으로 사용하고자 하는 경우에는
[].아래 메시지를 사용하고자 하는 언어로 바꾸어서 사용하면 됩니다.
\************************************************************************/
if (defined ( '_kor_' ))
	return;
define ( '_kor_', true );

// //////////////////////////////////////
$envconf ["charset"] = "utf-8";

// //////////////////////////////////////
$btn ["add"] = "추가";
$btn ["adjust"] = "정리";
$btn ["conv"] = "데이타변환";
$btn ["admin"] = "관리자";
$btn ["apply"] = "적용";
$btn ["back"] = "전화면으로";
$btn ["blank"] = "::";
$btn ["cancel"] = "   취    소   ";
$btn ["close"] = "화면닫기";
$btn ["create"] = "생성";
$btn ["del"] = "삭제";
$btn ["delete"] = "삭제";
$btn ["find"] = "검색";
$btn ["findpass"] = "비밀번호찾기";
$btn ["forward"] = "메일로 전달하기";
$btn ["help"] = "도움말";
$btn ["home"] = "홈";
$btn ["join"] = "회원가입";
$btn ["list"] = "목록";
$btn ["login"] = "로그인";
$btn ["logout"] = "로그아웃";
$btn ["member"] = "정보변경";
$btn ["modify"] = "수정";
$btn ["next"] = "다음";
$btn ["ok"] = "   확    인   ";
$btn ["permit"] = "퍼미션";
$btn ["post"] = "우편번호찾기";
$btn ["prev"] = "이전";
$btn ["print"] = "인쇄하기";
$btn ["reply"] = "답변";
$btn ["send"] = "메일보내기";
$btn ["set"] = "설정";
$btn ["sort"] = "정렬";
$btn ["write"] = "글쓰기";

// //////////////////////////////////////
$step ["admin_adjust"] = "관리자 권한 정리";
$step ["admin_conv"] = "관리자 권한 데이타변환";
$step ["admin_delete"] = "관리자 권한 삭제";
$step ["admin_login"] = "관리자 접속";
$step ["admin_logout"] = "관리자 로그아웃";
$step ["admin_modify"] = "관리자 권한 수정";
$step ["admin_permit"] = "관리자 권한 퍼미션";
$step ["admin_save"] = "관리자 권한 저장";
$step ["check_id"] = "";
$step ["config"] = "환경설정";
$step ["delete"] = "글 삭제";
$step ["delete_all"] = "";
$step ["down"] = "다운로드";
$step ["find"] = "찾기";
$step ["findpass"] = "비밀번호찾기";
$step ["forward"] = "전달하기";
$step ["forwardmail"] = "메일전달하기";
$step ["join"] = "회원등록";
$step ["list"] = "글 목록";
$step ["login"] = "로그인";
$step ["logout"] = "로그아웃";
$step ["member"] = "정보변경";
$step ["modify"] = "글 수정";
$step ["new_folder"] = "게시판 만들기";
$step ["newpass"] = "관리자 비밀번호 변경";
$step ["opinion"] = "의견달기";
$step ["poll"] = "투표하기";
$step ["print"] = "글 인쇄";
$step ["read"] = "글 읽기";
$step ["remote"] = "글 읽기";
$step ["reply"] = "글 답장";
$step ["save"] = "글 저장";
$step ["sendmail"] = "메일보내기";
$step ["stat"] = "통계현황";
$step ["user_login"] = "회원 로그인";
$step ["user_logout"] = "회원 로그아웃";
$step ["view_all"] = "";
$step ["write"] = "글 쓰기";
$step ["webmail"] = "웹메일";

// //////////////////////////////////////
$menu ["access"] = "접속:";
$menu ["auto"] = "자동";
$menu ["below"] = "이하";
$menu ["disable"] = "사용안함";
$menu ["enable"] = "사용";
$menu ["hide"] = "감추기";
$menu ["max"] = "최대:";
$menu ["menu1"] = "관리자 환경";
$menu ["menu2"] = "게시판 환경";
$menu ["menu3"] = "사용자 설정";
$menu ["menu4"] = "목록 & 본문";
$menu ["menu5"] = "색상설정";
$menu ["menu6"] = "스킨 & 공지";
$menu ["menu7"] = "글 읽기&쓰기";
$menu ["menu8"] = "기타 환경";
$menu ["min"] = "최소:";
$menu ["need"] = "필수";
$menu ["page"] = "쪽번호:";
$menu ["reset"] = "  초기값  ";
$menu ["saveexit"] = "      저장하고 나가기      ";
$menu ["show"] = "보이기";
$menu ["to"] = "오늘:";
$menu ["total"] = "게시물:";

// //////////////////////////////////////
$label ["accept"] = "동의";
$label ["access_read"] = "글 읽기 권한";
$label ["access_reply"] = "답글 작성 권한";
$label ["access_write"] = "글 작성 권한";
$label ["address"] = "주소";
$label ["admin_home"] = "관리자 홈페이지";
$label ["admin_mail"] = "관리자 메일";
$label ["admin_name"] = "관리자 이름";
$label ["admin_password"] = "관리자 비밀번호";
$label ["all"] = "모두";
$label ["anniversary"] = "기록일";
$label ["attachfile"] = "첨부파일";
$label ["autohidecont"] = "기본 본문 메시지";
$label ["autolink"] = "자동 링크";
$label ["autotitle"] = "게시판 제목 출력";
$label ["backcolor"] = "게시판 배경 색상";
$label ["backimage"] = "게시판 배경 그림";
$label ["badbanner"] = "광고글 차단";
$label ["badhome"] = "홈페이지 차단";
$label ["badip"] = "ip 차단";
$label ["badmail"] = "이메일 차단";
$label ["badname"] = "이름차단";
$label ["badprotect"] = "글 차단";
$label ["badword"] = "음란어 차단";
$label ["birth"] = "생년월일";
$label ["boardalign"] = "게시판 정렬";
$label ["boardimage"] = "게시판 제목 그림";
$label ["boarditem"] = "게시물관리";
$label ["boardtitle"] = "게시판 타이틀";
$label ["bordercolor"] = "테이블 테두리 색상";
$label ["browsertitle"] = "브라우저 타이틀";
$label ["cellpadding"] = "테이블(셀) 여백 설정";
$label ["cellspacing"] = "테이블(셀) 칸 간견";
$label ["checkbox"] = "ⓒ";
$label ["checkbox"] = "√";
$label ["contbuttonpos"] = "본문 버튼 위치";
$label ["content"] = "내용";
$label ["dan_size"] = "단의 갯수";
$label ["databkcol"] = "본문 내용 배경색";
$label ["dataheadbkcol"] = "본문 제목 배경색";
$label ["dataheadtxtcol"] = "본문 제목 글자색";
$label ["datatxtcol"] = "본문 내용 글자색";
$label ["db_engine"] = "데이타베이스 타입";
$label ["defcolor"] = "게시판 글자 색상";
$label ["defcontent"] = "기본 본문 메시지";
$label ["deffont"] = "기본 글꼴";
$label ["deffontsize"] = "기본 글꼴 크기";
$label ["dept"] = "부서(학년)";
$label ["depth"] = "depth";
$label ["division"] = "구분";
$label ["dobaelimit"] = "연속 글쓰기";
$label ["domain"] = "접속도메인";
$label ["email"] = "이메일";
$label ["category"] = "카테고리";
$label ["emailcontent"] = "감사 메일 내용";
$label ["emailsubject"] = "감사 메일 제목";
$label ["entertype"] = "본문내 cr&lf";
$label ["fileicon"] = "ⓘ";
$label ["filelimit"] = "";
$label ["foldericon"] = "쓰레드 아이콘";
$label ["from_email"] = "보낸 사람 이메일";
$label ["from_email"] = "보낸사람 이메일";
$label ["from_name"] = "보낸 사람 이름";
$label ["from_name"] = "보낸사람";
$label ["gradcolor"] = "그라데이션 시작색";
$label ["hlinkcolor"] = "링크 색상(hover)";
$label ["hobby"] = "취미(관심분야)";
$label ["homeurl"] = "홈페이지";
$label ["htmlhead"] = "사용자 태그 상단";
$label ["htmltail"] = "사용자 태그 하단";
$label ["htmltype"] = "htmltype";
$label ["htmltype"] = "태그문서";
$label ["id"] = "id";
$label ["in_date"] = "등록일자";
$label ["intro"] = "인사말&자기소개";
$label ["ip"] = "접속ip";
$label ["is_marry"] = "결혼유무";
$label ["jobis"] = "직업";
$label ["jumin"] = "주민번호";
$label ["langfile"] = "언어파일";
$label ["lastlogin"] = "최종방문일시";
$label ["level"] = "level";
$label ["linkcolor"] = "링크 색상(link)";
$label ["linktarget"] = "";
$label ["linktype"] = "링크 모양";
$label ["listbkcoleven"] = "목록 짝수 배경색";
$label ["listbkcolodd"] = "목록 홀수 배경색";
$label ["listbuttonpos"] = "목록 버튼 위치";
$label ["listheadbkcol"] = "목록 헤더 배경색";
$label ["listheadtxtcol"] = "목록 헤더 글자색";
$label ["listtxtcoleven"] = "목록 짝수 글자색";
$label ["listtxtcolodd"] = "목록 홀수 글자색";
$label ["mailhtml"] = "메일 형식";
$label ["mailing"] = "메일 수신여부";
$label ["maxcont"] = "본문 입력 제한";
$label ["maxsubj"] = "제목 입력 제한";
$label ["mo_date"] = "수정일자";
$label ["mustattachfile"] = "첨부파일";
$label ["mustfield"] = "필수 입력사항";
$label ["musthome"] = "홈페이지";
$label ["mustjumin"] = "주민번호 필수입력";
$label ["mustmail"] = "이메일";
$label ["name"] = "이름";
$label ["no"] = "순서";
$label ["notefile"] = "공지파일";
$label ["opinion"] = "짧은의견";
$label ["os"] = "사용환경";
$label ["pageline"] = "페이지당 목록 수";
$label ["passwd"] = "비밀번호";
$label ["phone"] = "전화번호";
$label ["pid"] = "pid";
$label ["point"] = "점수";
$label ["post"] = "우편번호";
$label ["postadmin"] = "메일 발송";
$label ["postreply"] = "메일 발송";
$label ["ppid"] = "ppid";
$label ["price"] = "가격";
$label ["private"] = "private";
$label ["privatetype"] = "비공개문서";
$label ["querycount"] = "방문횟수";
$label ["readcount"] = "조회수";
$label ["reline"] = "답변글 본문";
$label ["repasswd"] = "재확인";
$label ["resubject"] = "답변글 제목";
$label ["retext"] = "답변글 작성자";
$label ["returnmail"] = "답변메일받기";
$label ["sale"] = "판매가";
$label ["sex"] = "성별";
$label ["short_cont"] = "본문출력길이";
$label ["short_name"] = "이름출력길이";
$label ["short_subject"] = "제목출력길이";
$label ["show_anniversary"] = "기록일 입력";
$label ["show_category"] = "카테고리 입력";
$label ["show_checkbox"] = "체크박스 기능";
$label ["show_opinion"] = "의견달기 메뉴";
$label ["show_docnum"] = "문서 번호";
$label ["showfield"] = "항목 보이기";
$label ["show_findmenu"] = "검색 메뉴";
$label ["show_homepage"] = "홈페이지 입력";
$label ["show_htmltype"] = "태그문서 입력";
$label ["show_indate"] = "등록일자";
$label ["show_inputform"] = "입력폼";
$label ["show_ip"] = "ip 정보";
$label ["show_list"] = "본문 아래 목록";
$label ["show_mail"] = "이메일 입력";
$label ["show_modate"] = "수정일자";
$label ["show_name"] = "이름";
$label ["show_note"] = "공지여부";
$label ["show_pagemenu"] = "페이지 이동메뉴";
$label ["show_passwd"] = "비밀번호 입력";
$label ["show_privatetype"] = "비공개문서 입력";
$label ["show_sortmenu"] = "정렬 메뉴";
$label ["skinname"] = "스킨 파일";
$label ["smtp_server"] = "smtp 서버";
$label ["source"] = "출처";
$label ["special"] = "경력&특기사항";
$label ["step"] = "step";
$label ["subject"] = "제목";
$label ["subject"] = "제목";
$label ["tableborder"] = "테이블 테두리 두께";
$label ["tablewidth"] = "게시판 너비";
$label ["testboard"] = "메인프로그램명";
$label ["titlebkcol"] = "타이틀 배경색";
$label ["titletxtcol"] = "타이틀 글자색";
$label ["to_email"] = "받는 사람 이메일";
$label ["to_email"] = "받는사람 이메일";
$label ["to_name"] = "받는 사람 이름";
$label ["to_name"] = "받는사람";
$label ["uid"] = "사용자";
$label ["password"] = "비밀번호";
$label ["usegrad"] = "그라데이션 기능";
$label ["userprotect"] = "사용자 차단";
$label ["useskin"] = "스킨 사용";
$label ["useuser"] = "회원관리";
$label ["vlinkcolor"] = "링크 색상(visit)";
$label ["volume"] = "용량";
$label ["work"] = "근무처(학교)";
$label ["workurl"] = "근무처홈피";

// //////////////////////////////////////
$hint ["accept"] = "위의 라이센스를 모두 읽었으며 동의합니다.";
$hint ["access_read"] = array (
		"누구나",
		"관리자만",
		"글작성시 공개/비공개 선택" 
);
$hint ["access_reply"] = array (
		"누구나",
		"관리자만" 
);
$hint ["access_write"] = array (
		"누구나",
		"관리자만" 
);
$hint ["address"] = "";
$hint ["admin_home"] = "(예:http://www.chollian.net)";
$hint ["admin_mail"] = "(예:durk007@chollian.net)";
$hint ["admin_name"] = "<input type='button' value='$step[newpass]' class='button' onclick=remotewindow('$sysconf[testadmin]?db=$db&mode=newpass')>";
$hint ["admin_password"] = "관리자 비밀번호";
$hint ["anniversary"] = "<input type='button' value='calendar' class='button' onclick=calendar(anniversary) readonly>";
$hint ["attachfile"] = array (
		$menu ["disable"],
		$menu ["enable"] 
);
$hint ["autohidecont"] = array (
		'항상 보이기',
		'자동 숨기기' 
);
$hint ["autolink"] = "본문내에 url이 존재하는 경우 자동으로 링크로 보여줍니다.";
$hint ["autotitle"] = array (
		$menu ["hide"],
		$menu ["show"],
		$menu ["auto"] 
);
$hint ["backcolor"] = "<input type='button' value='색' class='button' onclick=setcolor('backcolor')>";
$hint ["backimage"] = "경로포함(예: ./images/title.gif)";
$hint ["badbanner"] = "광고글 차단 목록입니다. ,(콤마)로 연결하세요";
$hint ["badhome"] = "게시판에 접근하지 못하게 됩니다.,(콤마)로 연결하세요";
$hint ["badip"] = "게시판에 접근하지 못하게 됩니다.,(콤마)로 연결하세요";
$hint ["badmail"] = "게시판에 접근하지 못하게 됩니다.,(콤마)로 연결하세요";
$hint ["badname"] = "게시판에 접근하지 못하게 됩니다.,(콤마)로 연결하세요";
$hint ["badprotect"] = array (
		$menu ["disable"],
		$menu ["enable"] 
);
$hint ["badword"] = "음란어 차단 목록입니다. ,(콤마)로 연결하세요";
$hint ["birth"] = "<input type='button' value='달력' class='button' onclick=calendar(birth) readonly>";
$hint ["boardalign"] = array (
		'left',
		'center',
		'right' 
);
$hint ["boardimage"] = "경로포함(예: ./images/title.gif)";
$hint ["boardtitle"] = "";
$hint ["bordercolor"] = "<input type='button' value='색' class='button' onclick=setcolor('bordercolor')>";
$hint ["browsertitle"] = "";
$hint ["cellpadding"] = "각 셀(칸)안의 내용물과 그셀을 둘러싼 외곽선과의 여백 설정";
$hint ["cellspacing"] = "칸 간격을 나타낸다. 셀간의 간격이 생긴다.";
$hint ["contbuttonpos"] = array (
		"출력안함",
		"상단",
		"하단",
		"상하모두" 
);
$hint ["content"] = "내용";
$hint ["dan_size"] = "단의 갯수";
$hint ["databkcol"] = "<input type='button' value='색' class='button' onclick=setcolor('databkcol')>";
$hint ["dataheadbkcol"] = "<input type='button' value='색' class='button' onclick=setcolor('dataheadbkcol')>";
$hint ["dataheadtxtcol"] = "<input type='button' value='색' class='button' onclick=setcolor('dataheadtxtcol')>";
$hint ["datatxtcol"] = "<input type='button' value='색' class='button' onclick=setcolor('datatxtcol')>";
$hint ["db_engine"] = $sysconf ["db_engine"];
$hint ["defcolor"] = "<input type='button' value='색' class='button' onclick=setcolor('defcolor')>";
$hint ["defcontent"] = "글 작성시 보이는 내용입니다.<br>자동 숨기기로 설정하면 커서가 있는 경우 본문 내용이 자동으로 숨겨집니다.";
$hint ["deffont"] = "기본 글꼴";
$hint ["deffontsize"] = "기본 글꼴 크기";
$hint ["dept"] = "";
$hint ["depth"] = "depth";
$hint ["division"] = "";
$hint ["dobaelimit"] = "시간/ip당 글쓰기 갯수를 제한합니다.(0이면 검사안함)";
$hint ["domain"] = "접속도메인";
$hint ["email"] = "(예:durk007@chollian.net)";
$hint ["category"] = explode ( ",", $envconf ["category"] );
$hint ["emailcontent"] = "";
$hint ["emailsubject"] = "";
$hint ["entertype"] = array (
		$menu ["disable"],
		$menu ["enable"] 
);
$hint ["fileicon"] = "파일 형식 아이콘";
$hint ["filelimit"] = "업로드 제한크기(단위:kbyte)";
$hint ["foldericon"] = "쓰레드 아이콘";
$hint ["from_email"] = "(예:durk007@chollian.net)";
$hint ["from_name"] = "빈칸없이 입력해 주십시오.(예:홍길동)";
$hint ["gradcolor"] = "<input type='button' value='색' class='button' onclick=setcolor('gradcolor')>";
$hint ["hlinkcolor"] = "<input type='button' value='색' class='button' onclick=setcolor('hlinkcolor')>";
$hint ["homeurl"] = "(예:http://www.chollian.net)";
$hint ["htmlhead"] = "사용자 태그 상단";
$hint ["htmltail"] = "사용자 태그 하단";
$hint ["htmltype"] = array (
		"text",
		"html",
		"html&ltbr&gt" 
);
$hint ["id"] = "id";
$hint ["in_date"] = "생성일자";
$hint ["intro"] = "";
$hint ["ip"] = "접속ip";
$hint ["jumin"] = "숫자만 입력 가능(예:8001011177921)";
$hint ["langfile"] = $sysconf ["langfile"];
$hint ["lastlogin"] = "";
$hint ["level"] = "level";
$hint ["linkcolor"] = "<input type='button' value='색' class='button' onclick=setcolor('linkcolor')>";
$hint ["linktarget"] = "linktarget";
$hint ["linktype"] = array (
		"none",
		"underline",
		"under/overline" 
);
$hint ["listbkcoleven"] = "<input type='button' value='색' class='button' onclick=setcolor('listbkcoleven')>";
$hint ["listbkcolodd"] = "<input type='button' value='색' class='button' onclick=setcolor('listbkcolodd')>";
$hint ["listbuttonpos"] = array (
		"출력안함",
		"상단",
		"하단",
		"상하모두" 
);
$hint ["listheadbkcol"] = "<input type='button' value='색' class='button' onclick=setcolor('listheadbkcol')>";
$hint ["listheadtxtcol"] = "<input type='button' value='색' class='button' onclick=setcolor('listheadtxtcol')>";
$hint ["listtxtcoleven"] = "<input type='button' value='색' class='button' onclick=setcolor('listtxtcoleven')>";
$hint ["listtxtcolodd"] = "<input type='button' value='색' class='button' onclick=setcolor('listtxtcolodd')>";
$hint ["mailhtml"] = array (
		"text",
		"html" 
);
$hint ["mailing"] = array (
		$menu ["disable"],
		$menu ["enable"] 
);
$hint ["maxcont"] = "(단위:칸)";
$hint ["maxsubj"] = "(단위:칸)";
$hint ["mo_date"] = "수정일자";
$hint ["mustattachfile"] = "첨부파일";
$hint ["musthome"] = "홈페이지";
$hint ["mustjumin"] = "주민번호";
$hint ["mustmail"] = "이메일";
$hint ["name"] = "빈칸없이 입력해 주십시오.(예:홍길동)";
$hint ["notefile"] = "파일명(id)을 기준으로 공지합니다.,(콤마)로 연결하세요";
$hint ["opinion"] = "짧은의견";
$hint ["os"] = "";
$hint ["pageline"] = "한 페이지당 출력할 목록 갯수";
$hint ["passwd"] = "수정,삭제시에 필요합니다.";
$hint ["phone"] = "(예:02-1234-5678)";
$hint ["pid"] = "pid";
$hint ["point"] = "(단위:원)";
$hint ["post"] = "<input type='button' value='우편번호찾기' class='button' onclick=setpost('post','address');>";
$hint ["postadmin"] = "글 작성시 관리자에게 메일 발송";
$hint ["postreply"] = "답변글 작성시 원본 글작성자에게 메일 발송 ";
$hint ["ppid"] = "ppid";
$hint ["price"] = "(단위:원)";
$hint ["private"] = array (
		"일반회원",
		"준회원",
		"정회원",
		"으뜸회원",
		"특별회원",
		"운영자",
		"주인" 
);
$hint ["privatetype"] = array (
		"public",
		"private",
		"protect" 
);
$hint ["querycount"] = "(단위:회)";
$hint ["readcount"] = "조회수";
$hint ["reline"] = "[출력 예] :본문 내용입니다.";
$hint ["repasswd"] = "비밀번호 확인을 위해 다시 한번 입력해 주십시요.";
$hint ["resubject"] = "[출력 예] re: 안녕하세요.";
$hint ["retext"] = "[출력 예] wrote: 홍길동.";
$hint ["returnmail"] = array (
		"disable",
		"enable" 
);
$hint ["sale"] = "(단위:원)";
$hint ["sex"] = array (
		"남자",
		"여자" 
);
$hint ["short_cont"] = "지정된 길이 이상의 본문은 ... 로 나머지 표시 (0:사용안함)";
$hint ["short_name"] = "지정된 길이 이상의 이름은 ... 로 나머지 표시 (0:사용안함)";
$hint ["short_subject"] = "지정된 길이 이상의 제목은 ... 로 나머지 표시 (0:사용안함)";
$hint ["show_anniversary"] = array (
		$menu ["hide"],
		$menu ["show"] 
);
$hint ["show_category"] = array (
		$menu ["hide"],
		$menu ["show"] 
);
$hint ["show_checkbox"] = array (
		"숨기기",
		"선택 보기",
		"선택 보기/삭제" 
);
$hint ["show_opinion"] = "의견달기 메뉴"; // ※ 의견쓰기는 100자까지 가능합니다.
$hint ["show_docnum"] = "문서 번호";
$hint ["show_findmenu"] = "검색 메뉴";
$hint ["show_homepage"] = array (
		$menu ["hide"],
		$menu ["show"] 
);
$hint ["show_htmltype"] = array (
		$menu ["hide"],
		$menu ["show"] 
);
$hint ["show_indate"] = "등록일자";
$hint ["show_inputform"] = "입력폼";
$hint ["show_ip"] = "ip 정보";
$hint ["show_list"] = "본문 아래 목록";
$hint ["show_mail"] = array (
		$menu ["hide"],
		$menu ["show"] 
);
$hint ["show_modate"] = "수정일자";
$hint ["show_name"] = "이름";
$hint ["show_note"] = array (
		$menu ["disable"],
		$menu ["enable"] 
);
$hint ["show_pagemenu"] = "페이지 이동메뉴";
$hint ["show_passwd"] = array (
		$menu ["hide"],
		$menu ["show"] 
);
$hint ["show_privatetype"] = array (
		$menu ["hide"],
		$menu ["show"] 
);
$hint ["show_sortmenu"] = "정렬 메뉴";
$hint ["skinname"] = $sysconf ["skinname"];
$hint ["smtp_server"] = "(예: 127.0.0.1, hitel.net등) ";
$hint ["special"] = "";
$hint ["step"] = "step";
$hint ["subject"] = "html 사용불가";
$hint ["tableborder"] = "0이나 1정도가 적당합니다";
$hint ["tablewidth"] = "예) 픽셀: 500 / 퍼센트: 80%";
$hint ["testboard"] = $sysconf ["testboard"];
$hint ["titlebkcol"] = "<input type='button' value='색' class='button' onclick=setcolor('titlebkcol')>";
$hint ["titletxtcol"] = "<input type='button' value='색' class='button' onclick=setcolor('titletxtcol')>";
$hint ["to_email"] = "(여러명에게 보낼 경우 ,로 구분)";
$hint ["to_name"] = "빈칸없이 입력해 주십시오.(예:홍길동)";
$hint ["uid"] = "<input type='button' value='중복확인' class='button' onclick=check_uid(uid);>";
$hint ["usegrad"] = "색상을 사용하려면 목록홀수배경에 시작색, 목록짝수배경에 끝색을 설정하세요.";
$hint ["usegrad"] = array (
		"사용안함",
		"밝게",
		"어둡게" 
);
$hint ["userprotect"] = array (
		$menu ["disable"],
		$menu ["enable"] 
);
$hint ["useskin"] = array (
		$menu ["disable"],
		$menu ["enable"] 
);
$hint ["useuser"] = array (
		$menu ["disable"],
		$menu ["enable"] 
);
$hint ["vlinkcolor"] = "<input type='button' value='색' class='button' onclick=setcolor('vlinkcolor')>";
$hint ["work"] = "";
$hint ["workurl"] = "";

$hint ["fontface"] = array (
		"돋움",
		"굴림",
		"바탕",
		"궁서",
		"Verdana",
		"Times" 
);
$hint ["fontsize"] = array (
		"크기",
		1,
		2,
		3,
		4,
		5,
		6,
		7 
);

// //////////////////////////////////////
$msg ["_update"] = "관리자만 접근 가능한 페이지입니다.<br><br>다른 아이디로 접속되어 있다면 로그아웃 하신 후 다시 시도해 보세요!";
$msg ["_update"] = "업데이트 정보를 받는중...";
$msg ["_update"] = "인터넷을 통해 최신 제품으로 업데이트 합니다.";
$msg ["_update"] = "최신 제품으로 업데이트 하시겠습니까?<br><br>작업중인 작업은 종료합니다.";
$msg ["_update"] = "최신 제품으로 업데이트가 완료했습니다.";
$msg ["_update"] = "최종 제품의 업데이트가 설치되어 있습니다.<br><br>더이상 설치 정보가 없습니다.";
$msg ["admin_only"] = "관리자 전용입니다.";
$msg ["check_home"] = '정확한 홈페이지 주소를 입력해주세요.';
$msg ["check_mail"] = '정확한 이메일을 입력해주세요.';
$msg ["check_pass"] = '암호가 일치하지 않습니다.';
$msg ["cmt_perm"] = '의견을 적을 수 있는 권한이 없습니다.<br>혹은 관리자 메뉴에서 의견글 보이기 옵션을 설정해주세요.';
$msg ["cmt_save"] = '데이타를 저장할 수 없습니다.<br>디렉토리와 파일 퍼미션을 확인해주세요.';
$msg ["db_code"] = '잘못된 코드입니다.';
$msg ["db_conv_already"] = '이미 새 버전의 database 형식입니다.';
$msg ["db_del"] = 'db파일에서 해당 자료를 삭제할 수 없습니다.';
$msg ["db_delreply"] = '관련글이 있는 경우 삭제할 수 없습니다.<br>먼저 관련글을 삭제해주세요.';
$msg ["db_edit"] = '에러! 수정할 수 없습니다.';
$msg ["db_index"] = 'index 파일을 열 수 없습니다.';
$msg ["db_invalid"] = 'db구조가 잘못되었습니다.(%s)<br>디렉토리와 파일 퍼미션을 확인해주세요.';
$msg ["db_new"] = '새로운 게시판 생성하지 못하도록 설정되어있습니다.';
$msg ["db_notfound"] = '[%s] 게시판을 찾을 수 없습니다.<br>먼저 게시판을 생성해주십시오.';
$msg ["db_open"] = 'db를 생성할 수 없습니다.<br>디렉토리와 파일 퍼미션을 확인해주세요.';
$msg ["db_perm"] = '퍼미션 설정이 잘못되었습니다.<br>디렉토리(777)와 파일(666)의 퍼미션을 확인해주세요.';
$msg ["db_read"] = '게시물을 읽을 수 없습니다.<br>디렉토리와 파일 퍼미션을 확인해주세요.';
$msg ["db_reindex"] = '언제나 친숙한 메시지... -_-a<br>계정용량 줄이기위해서 db최적화 작업을 하고 있는 중이예요.<br>쬐끔만 기다려주시면 작업이 끝난답니다. *^^*';
$msg ["db_reply"] = '답변글을 작성할 수 없습니다.';
$msg ["db_save"] = '데이타를 저장할 수 없습니다.<br>디렉토리와 파일 퍼미션을 확인해주세요.';
$msg ["err_create_index"] = "인덱스 파일을 생성할 수가 없습니다";
$msg ["err_create_member"] = "회원 파일을 생성할 수가 없습니다.<br><br>이미 존재하는 파일입니다.";
$msg ["err_def"] = "입력된 모든 정보가 정확한지 확인해 보세요.<br>자세한 문의는 %s 로 하시기 바랍니다.<p>";
$msg ["err_dobae_text"] = "도배 방지를 위해 30초에 1개씩만 글을 올리실 수 있습니다.";
$msg ["err_equal_content"] = "같은 내용의 글은 등록할수가 없습니다.<br><br>확인해 주십시오.";
$msg ["err_error"] = "오류가 발생했슴다.";
$msg ["err_exist_db"] = "관리자에 의해서 게시판 생성이 금지 되었습니다.";
$msg ["err_exist_email"] = "이미 등록되어 있는 이메일 입니다.";
$msg ["err_exist_id"] = "이미 등록되어 있는 아이디 입니다.";
$msg ["err_exist_reply"] = "답변을 먼저 삭제하십시요.";
$msg ["err_file_limit"] = "죄송합니다.<br><br>첨부파일는 %skbyte 이하만 허용됩니다.";
$msg ["err_found"] = "조건에 해당하는 자료가 없습니다.<br><br>확인해 주십시오.";
$msg ["err_host_method"] = "정상적으로 글을 작성하여 주시기 바랍니다.";
$msg ["err_no_param"] = "파라미터 정의가 잘못되었습니다.";
$msg ["err_pass"] = "잘못된 비밀번호입니다..";
$msg ["err_permission"] = "파일을 생성할 수가 없습니다.<br><br>디렉토리(파일)의 퍼미션(쓰기권한)을 확인하세요!!";
$msg ["err_post_method"] = "허용되지 않는 접근입니다.";
$msg ["exec_argv"] = '파라미터가 잘못되었습니다.';
$msg ["exec_db"] = '게시판 이름을 찾을수 없습니다.<br>다음과 같은 형식으로 실행하기 바랍니다.<br><font color=red>testboard.php?db=사용자db명</font>';
$msg ["file_bad"] = '업로드된 파일형식은 직접 올리지 못하도록 설정되어 있습니다.<br>압축해서 다시 올려주세요.';
$msg ["file_down"] = '파일 다운받을 수 있는 권한이 없습니다.<br>먼저 로그인을 해주세요.';
$msg ["file_exist"] = '파일이 존재하지 않습니다. 링크가 틀리거나 파일이 삭제된 것 같습니다.';
$msg ["file_invalid"] = '잘못된 파일입니다.';
$msg ["file_size_file"] = '파일은 %skb이하의 크기만 올릴 수 있습니다.';
$msg ["file_size_img"] = '이미지는 %skb이하의 크기만 올릴 수 있습니다.';
$msg ["file_temp"] = '임시파일을 생성할 수 없습니다.';
$msg ["file_up"] = '파일을 업로드할 수 없도록 설정되어 있습니다.';
$msg ["find_cache"] = '검색 정보를 읽을 수 없습니다. 다시 검색하기 바랍니다.';
$msg ["find_invalid"] = '검색 범위 혹은 인덱스가 잘못되었습니다.';
$msg ["find_word"] = '검색항목을 선택하지 않았거나 찾을 단어를 입력하지 않았습니다.';
$msg ["info_def"] = "자세한 문의는 %s 로 하시기 바랍니다.<p>";
$msg ["info_member_only"] = "회원 로그인이 필요한 메뉴를 선택하셨습니다.<br><br>id와 password를 입력하십시오.";
$msg ["info_ok"] = "정상처리되었습니다.";
$msg ["info_save"] = "입력하신 사항을 저장하시겠습니까?";
$msg ["info_save_member"] = "저장되었습니다.<br><br>데이타 접근은 권한승격후 가능합니다.";
$msg ["info_send_mail"] = "메일이 성공적으로 발송되었습니다.";
$msg ["init_cfg"] = '환경설정 파일을 읽을 수 없습니다.<br>파일이 존재하는지 혹은 system.cgi에서 경로설정을 정확하게 설정하기 바랍니다.';
$msg ["key_invalid"] = '등록코드가 일치하지 않습니다.';
$msg ["limit_reply"] = '더이상 관련글을 작성할 수 없습니다.';
$msg ["link_file"] = '무단으로 자료를 링크하지 마십시요.';
$msg ["log_admin"] = '관리자만 관리자 메뉴에 접속할 수 있습니다.';
$msg ["log_admin_bad_user"] = '무단으로 관리자 메뉴에 접속하지 마세요.';
$msg ["mail_error"] = '메일을 발송할 수 없습니다.<br>메일서버를 다시 설정해주세요.';
$msg ["mail_forward"] = '게시물을 메일로 발송했습니다.';
$msg ["mail_sender"] = '메일을 발송할 수 없습니다.<br>메일경로 혹은 서버문제입니다.';
$msg ["member_admin"] = '관리자 메뉴에 접속할수 있는 권한이 없습니다.';
$msg ["member_db"] = '회원db가 존재하지 않습니다.';
$msg ["member_list"] = '목록 보기 권한이 없습니다.\n먼저 사용자 로긴을 해주세요.';
$msg ["member_login"] = '회원제 게시판입니다.\n먼저 사용자 로긴을 해주세요.';
$msg ["member_notmatch"] = '회원이 아니거나 비밀번호가 일치하지 않습니다.';
$msg ["member_only"] = "회원 전용입니다.";
$msg ["member_pass"] = '회원이 아니거나 비밀번호가 일치하지 않습니다.';
$msg ["member_read"] = '본문 보기 권한이 없습니다.\n먼저 사용자 로긴을 해주세요.';
$msg ["member_reply"] = '답변을 할수 있는 권한이 없습니다.';
$msg ["member_session"] = '회원세션이 존재하지 않습니다.';
$msg ["member_write"] = '글쓰기 권한이 없습니다.';
$msg ["mnu_admin_login"] = '관리자 접속';
$msg ["mnu_dbadd"] = '새 게시판을 추가했습니다.';
$msg ["mnu_dbaddnot"] = '새 게시판이 추가되지 않았습니다.';
$msg ["mnu_dbdel"] = '게시판을 삭제했습니다.';
$msg ["mnu_dbpermit"] = '게시물의 퍼미션을 일괄 정리했습니다.';
$msg ["mnu_dbrebuild"] = '게시판을 복구했습니다.';
$msg ["mnu_delete"] = '글삭제';
$msg ["mnu_findlast"] = '마지막 페이지입니다. 더이상 검색된 내용이 존재하지 않습니다.';
$msg ["mnu_findnext"] = '%d개 단위로 검색됩니다. 다음 검색을 하려면 검색버튼을 클릭하십시요.';
$msg ["mnu_findnext2"] = '현재 검색한 %d개 게시물내에서는 검색된 내용이 존재하지 않습니다.<br>다음 검색을 하려면 next버튼을 클릭하십시요.';
$msg ["mnu_modify"] = '글수정';
$msg ["mnu_readtext"] = '본문 읽기';
$msg ["mnu_voteadd"] = '게시물을 추천했습니다.';
$msg ["mnu_write"] = '글쓰기';
$msg ["msg_not_group"] = '그룹파일이 존재하지 않습니다.';
$msg ["msg_not_member"] = '회원이 아닌 분에게는 쪽지를 보낼 수 없습니다.';
$msg ["msg_not_perm"] = '쪽지를 보낼 수 있는 권한이 없습니다.';
$msg ["msg_send"] = '쪽지를 보냈습니다.';
$msg ["need_file"] = '반드시 파일을 업로드 해야 합니다.';
$msg ["need_home"] = '홈페이지를 입력해주세요.';
$msg ["need_id"] = 'id를 입력해주세요.';
$msg ["need_img"] = '반드시 미리보기 파일을 업로드 해야 합니다.';
$msg ["need_mail"] = '이메일을 입력해주세요.';
$msg ["need_name"] = '이름을 입력해주세요.';
$msg ["need_pass"] = '비밀번호를 입력해주세요.';
$msg ["need_subj"] = '제목을 입력해주세요.';
$msg ["need_text"] = '내용을 입력해주세요.';
$msg ["no_attach_file"] = "첨부파일에 대한 정보가 없습니다";
$msg ["no_define_db"] = "게시판 이름을 지정해 주셔야 합니다.<br><br>(예:testboard.php?db=사용자db명).";
$msg ["no_exist_id"] = "등록가능한 아이디입니다.";
$msg ["no_filter_type"] = "등록하기에 적합한 단어가 아닙니다.<br><br>확인해 주십시오.";
$msg ["no_input_pass"] = "암호를 입력하지 않았습니다.";
$msg ["no_match_pass"] = "입력된 두개의 암호가 다릅니다.<br><br>다시 입력해주세요.";
$msg ["no_private_type"] = "파일접근 권한이 없습니다.<br><br>혹은 비밀번호가 일치하지 않습니다.";
$msg ["not_support"] = '현재 버전에서 지원되지 않는 기능입니다.';
$msg ["out_of_mem"] = '메모리가 부족합니다.';
$msg ["par_enctype"] = '지원되지 않는 형식입니다.';
$msg ["par_header"] = '헤더구조에 이상이 있습니다.';
$msg ["par_method"] = '지원되지 않는 형식입니다.';
$msg ["pass_already"] = '비밀번호가 이미 존재합니다.';
$msg ["pass_exist"] = '초기 비밀번호가 존재하지 않습니다.<br>환경설정 파일에 초기 비밀번호를 설정해주십시요.';
$msg ["pass_init"] = '기본 암호로 설정되었습니다.<br>다시 관리자 모드로 접속해주십시요.';
$msg ["pass_notmatch"] = '두개의 비밀번호가 서로 일치하지 않습니다.';
$msg ["path_db"] = 'db디렉토리를 읽을 수 없습니다.<br>디렉토리와 파일 퍼미션을 확인해주세요.';
$msg ["save_pass"] = '암호를 저장할 수 없습니다.<br>퍼미션 혹은 경로 설정 문제입니다.';
$msg ["setup_notice"] = "변경된 내용이 저장되었습니다.";
$msg ["skin_exist"] = '%s 스킨 파일을 열 수 없습니다.';
$msg ["skin_invalid"] = '스킨 구조가 잘못되었습니다.';
$msg ["socket_code"] = '서버에서 잘못된 코드를 보냈습니다.';
$msg ["socket_connect"] = '서버에 접속할 수 없습니다.';
$msg ["socket_create"] = '소켓을 생성할 수 없습니다.';
$msg ["socket_user"] = '보드를 불법으로 사용하고 있습니다.<br>곧 사용이 차단됩니다.';
$msg ["socket_verify"] = '사용자 인증을 받을수 없습니다.';
$msg ["socket_write"] = '소켓이상으로 데이타를 전송할 수 없습니다.';
$msg ["vote_already"] = '이미 추천하셨습니다.';
$msg ["work_completed"] = '작업이 완료되었습니다.';
?>
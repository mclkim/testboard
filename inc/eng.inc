<?php
/************************************************************************\
[].한글이 아닌 다른언어의 게시판으로 사용하고자 하는 경우에는
[].아래 메시지를 사용하고자 하는 언어로 바꾸어서 사용하면 됩니다.
\************************************************************************/
if (defined ( '_eng_' ))
	return;
define ( '_eng_', true );

$envconf ["charset"] = "iso-8859-1";
/**
 * **********************************************************************\
 * \***********************************************************************
 */
$btn ["adjust"] = "adjust";
$btn ["conv"] = "convert";
$btn ["admin"] = "admin";
$btn ["apply"] = "apply";
$btn ["back"] = "move back";
$btn ["blank"] = "::";
$btn ["cancel"] = "   cancel   ";
$btn ["close"] = "close";
$btn ["delete"] = "delete";
$btn ["find"] = "find";
$btn ["findpass"] = "find password";
$btn ["forward"] = "forward";
$btn ["help"] = "help";
$btn ["home"] = "home";
$btn ["join"] = "join";
$btn ["list"] = "list";
$btn ["login"] = "login";
$btn ["logout"] = "logout";
$btn ["member"] = "member&modify";
$btn ["modify"] = "modify";
$btn ["next"] = "next";
$btn ["ok"] = "   o    k   ";
$btn ["permit"] = "permission";
$btn ["post"] = "find post";
$btn ["prev"] = "prev";
$btn ["print"] = "print";
$btn ["reply"] = "reply";
$btn ["send"] = "send mail";
$btn ["set"] = "set";
$btn ["sort"] = "sort";
$btn ["write"] = "write";
/**
 * **********************************************************************\
 * \***********************************************************************
 */
$label ["accept"] = "accept";
$label ["access_read"] = "access read";
$label ["access_reply"] = "access reply";
$label ["access_write"] = "access write";
$label ["address"] = "address";
$label ["admin_mail"] = "administrator email";
$label ["admin_home"] = "administrator homepage";
$label ["admin_name"] = "administrator id";
$label ["admin_password"] = "administrator password";
$label ["all"] = "all";
$label ["anniversary"] = "anniversary";
$label ["autohidecont"] = "기본 본문 메시지";
$label ["autolink"] = "auto link";
$label ["autotitle"] = "board subject output";
$label ["backcolor"] = "board background color";
$label ["backimage"] = "board background image";
$label ["badbanner"] = "광고글 차단";
$label ["badhome"] = "홈페이지 차단";
$label ["badip"] = "ip 차단";
$label ["badmail"] = "이메일 차단";
$label ["badname"] = "이름차단";
$label ["badword"] = "음란어 차단";
$label ["birth"] = "birth";
$label ["boardalign"] = "board align";
$label ["boardimage"] = "board image";
$label ["boarditem"] = "board item";
$label ["boardtitle"] = "board title";
$label ["bordercolor"] = "border color";
$label ["tableborder"] = "border style";
$label ["browsertitle"] = "browser title";
$label ["checkbox"] = "ⓒ";
$label ["cellpadding"] = "cellpadding";
$label ["cellspacing"] = "cellspacing";
$label ["useuser"] = "member connection";
$label ["contbuttonpos"] = "본문 버튼 위치";
$label ["content"] = "content";
$label ["db_engine"] = "database type";
$label ["dan_size"] = "단의 갯수";
$label ["databkcol"] = "body background";
$label ["dataheadbkcol"] = "body subject background";
$label ["dataheadtxtcol"] = "body subject char";
$label ["datatxtcol"] = "body char";
$label ["defcolor"] = "board char color";
$label ["defcontent"] = "기본 본문 메시지";
$label ["deffont"] = "font";
$label ["deffontsize"] = "font size";
$label ["dept"] = "dept";
$label ["depth"] = "thread depth";
$label ["division"] = "division";
$label ["dobaelimit"] = "연속 글쓰기";
$label ["show_docnum"] = "문서 번호";
$label ["domain"] = "domain";
$label ["email"] = "email";
$label ["emailcontent"] = "감사 mail body";
$label ["emailsubject"] = "감사 mail subject";
$label ["entertype"] = "body cr&lf";
$label ["fileicon"] = "ⓘ";
$label ["filelimit"] = "";
$label ["attachfile"] = "attach file";
$label ["foldericon"] = "쓰레드 아이콘";
$label ["from_email"] = "from email";
$label ["from_name"] = "from name";
$label ["gradcolor"] = "그라데이션 시작색";
$label ["hlinkcolor"] = "hover color";
$label ["hobby"] = "hobby";
$label ["homeurl"] = "homeurl";
$label ["htmlhead"] = "user head tag";
$label ["htmltail"] = "user tail tag";
$label ["htmltype"] = "html type";
$label ["id"] = "id";
$label ["in_date"] = "insert date";
$label ["intro"] = "intro";
$label ["ip"] = "ip";
$label ["is_marry"] = "marry";
$label ["jobis"] = "jobs";
$label ["jumin"] = "jumin";
$label ["langfile"] = "language file";
$label ["lastlogin"] = "lastlogin";
$label ["level"] = "level";
$label ["linkcolor"] = "link color";
$label ["linktarget"] = "";
$label ["linktype"] = "link type";
$label ["listbkcoleven"] = "list even background";
$label ["listbkcolodd"] = "list odd background";
$label ["listbuttonpos"] = "목록 버튼 위치";
$label ["listheadbkcol"] = "list header background";
$label ["listheadtxtcol"] = "list header char";
$label ["listtxtcoleven"] = "list even char";
$label ["listtxtcolodd"] = "list odd char";
$label ["mailhtml"] = "mail type";
$label ["mailing"] = "mailing";
$label ["maxcont"] = "body max length";
$label ["maxsubj"] = "subject max length";
$label ["mo_date"] = "modify date";
$label ["mustattachfile"] = "attach file";
$label ["mustfield"] = "필수 입력사항";
$label ["musthome"] = "homepage";
$label ["mustjumin"] = "주민번호 필수입력";
$label ["mustmail"] = "email";
$label ["name"] = "name";
$label ["no"] = "no";
$label ["opinion"] = "opinion";
$label ["os"] = "os";
$label ["pageline"] = "페이지당 목록 수";
$label ["passwd"] = "password";
$label ["phone"] = "phone";
$label ["pid"] = "pid";
$label ["point"] = "point";
$label ["post"] = "post";
$label ["postadmin"] = "mail post";
$label ["postreply"] = "mail post";
$label ["ppid"] = "ppid";
$label ["price"] = "price";
$label ["private"] = "private";
$label ["privatetype"] = "private type";
$label ["querycount"] = "query count";
$label ["reline"] = "reply 본문";
$label ["repasswd"] = "repassword";
$label ["resubject"] = "reply 제목";
$label ["retext"] = "reply 작성자";
$label ["readcount"] = "read";
$label ["returnmail"] = "return mail";
$label ["sale"] = "sale";
$label ["sex"] = "sex";
$label ["short_cont"] = "본문출력길이";
$label ["short_name"] = "이름출력길이";
$label ["short_subject"] = "제목출력길이";
$label ["show_anniversary"] = "anniversary";
$label ["show_checkbox"] = "checkbox";
$label ["show_opinion"] = "의견달기 메뉴";
$label ["showfield"] = "항목 보이기";
$label ["show_findmenu"] = "검색 메뉴";
$label ["show_homepage"] = "홈페이지 입력";
$label ["show_mail"] = "이메일 입력";
$label ["show_htmltype"] = "태그문서 입력";
$label ["show_ip"] = "from ip";
$label ["show_inputform"] = "입력폼";
$label ["show_list"] = "본문 아래 목록";
$label ["show_name"] = "이름";
$label ["show_pagemenu"] = "페이지 이동메뉴";
$label ["show_passwd"] = "비밀번호 입력";
$label ["show_privatetype"] = "비공개문서 입력";
$label ["show_sortmenu"] = "정렬 메뉴";
$label ["show_indate"] = "등록일자";
$label ["show_modate"] = "수정일자";
$label ["skinname"] = "skin name";
$label ["smtp_server"] = "smtp server";
$label ["special"] = "special";
$label ["step"] = "step";
$label ["subject"] = "subject";
$label ["tablewidth"] = "board width";
$label ["testboard"] = "메인프로그램명";
$label ["titlebkcol"] = "title background";
$label ["titletxtcol"] = "title char";
$label ["to_email"] = "to email";
$label ["to_name"] = "to name";
$label ["uid"] = "User Id";
$label ["password"] = "Password";
$label ["usegrad"] = "그라데이션 기능";
$label ["useskin"] = "skin enable";
$label ["badprotect"] = "글 차단";
$label ["userprotect"] = "사용자 차단";
$label ["vlinkcolor"] = "visit color";
$label ["work"] = "work";
$label ["workurl"] = "workurl";
$label ["show_note"] = "공지여부";
$label ["notefile"] = "공지파일";
/**
 * **********************************************************************\
 * \***********************************************************************
 */
$menu ["access"] = "access:";
$menu ["auto"] = "auto";
$menu ["below"] = "below";
$menu ["disable"] = "disable";
$menu ["enable"] = "enable";
$menu ["hide"] = "hide";
$menu ["menu1"] = "administrator";
$menu ["menu2"] = "bulletin";
$menu ["menu3"] = "user";
$menu ["menu4"] = "list&content";
$menu ["menu5"] = "color";
$menu ["menu6"] = "skin&note";
$menu ["menu7"] = "read&write";
$menu ["menu8"] = "etc";
$menu ["max"] = "max:";
$menu ["min"] = "min:";
$menu ["need"] = "need";
$menu ["page"] = "page:";
$menu ["reset"] = "  reset  ";
$menu ["saveexit"] = "      save & exit      ";
$menu ["show"] = "show";
$menu ["to"] = "to:";
$menu ["total"] = "total:";
/**
 * **********************************************************************\
 * \***********************************************************************
 */
$step ["admin_adjust"] = "관리자 권한 정리";
$step ["admin_conv"] = "관리자 권한 데이타변환";
$step ["admin_delete"] = "admin delete";
$step ["admin_login"] = "admin login";
$step ["admin_logout"] = "admin logout";
$step ["admin_modify"] = "admin modify";
$step ["admin_permit"] = "관리자 권한 퍼미션";
$step ["admin_save"] = "관리자 권한 저장";
$step ["check_id"] = "";
$step ["config"] = "set configuration";
$step ["delete"] = "delete";
$step ["delete_all"] = "delete all";
$step ["down"] = "download";
$step ["find"] = "find";
$step ["findpass"] = "find password";
$step ["forward"] = "forward mail";
$step ["forwardmail"] = "forward mail";
$step ["join"] = "join";
$step ["list"] = "list";
$step ["login"] = "login";
$step ["logout"] = "logout";
$step ["member"] = "member&modify";
$step ["modify"] = "modify";
$step ["new_folder"] = "new folder";
$step ["newpass"] = "set supervisor password";
$step ["opinion"] = "opinion";
$step ["poll"] = "poll";
$step ["print"] = "print";
$step ["read"] = "read the contents";
$step ["remote"] = "remote the contents";
$step ["reply"] = "reply";
$step ["save"] = "save";
$step ["sendmail"] = "send mail";
$step ["stat"] = "stat";
$step ["user_login"] = "user login";
$step ["user_logout"] = "user logout";
$step ["view_all"] = "view all";
$step ["write"] = "write";
/**
 * **********************************************************************\
 * \***********************************************************************
 */
$hint ["accept"] = "위의 라이센스를 모두 읽었으며 동의합니다.";
$hint ["access_read"] = array (
		"nobody",
		"administrator",
		"글작성시 공개/비공개 선택" 
);
$hint ["access_reply"] = array (
		"nobody",
		"administrator" 
);
$hint ["access_write"] = array (
		"nobody",
		"administrator" 
);
$hint ["address"] = "";
$hint ["admin_mail"] = "(ex:mclkim@daum.net)";
$hint ["admin_home"] = "(ex:http://www.daum.net)";
$hint ["admin_name"] = "<input type='button' value='$step[newpass]' class='button' onclick=remotewindow('$sysconf[testadmin]?db=$db&mode=newpass')>";
$hint ["admin_password"] = "administrator password";
$hint ["anniversary"] = "<input type='button' value='calendar' class='button' onclick=calendar(anniversary) readonly>";
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
$hint ["backcolor"] = "<input type='button' value='color' class='button' onclick=setcolor('backcolor')>";
$hint ["backimage"] = "경로포함(ex: ./images/title.gif)";
$hint ["badbanner"] = "광고글 차단 list입니다. ,(콤마) 로 연결하세요";
$hint ["badhome"] = "게시판에 접근하지 못하게 됩니다.,(콤마) 로 연결하세요";
$hint ["badip"] = "게시판에 접근하지 못하게 됩니다.,(콤마) 로 연결하세요";
$hint ["badmail"] = "게시판에 접근하지 못하게 됩니다.,(콤마) 로 연결하세요";
$hint ["badname"] = "게시판에 접근하지 못하게 됩니다.,(콤마) 로 연결하세요";
$hint ["badword"] = "음란어 차단 list입니다. ,(콤마) 로 연결하세요";
$hint ["birth"] = "<input type='button' value='calendar' class='button' onclick=calendar(birth) readonly>";
$hint ["boardalign"] = array (
		'left',
		'center',
		'right' 
);
$hint ["boardimage"] = "경로포함(ex:./images/title.gif)";
$hint ["boardtitle"] = "";
$hint ["bordercolor"] = "<input type='button' value='color' class='button' onclick=setcolor('bordercolor')>";
$hint ["tableborder"] = "0이나 1정도가 적당합니다";
$hint ["browsertitle"] = "";
$hint ["cellpadding"] = "각 셀(칸)안의 내용물과 그셀을 둘러싼 외곽선과의 여백 설정";
$hint ["cellspacing"] = "칸 간격을 나타낸다. 셀간의 간격이 생긴다.";
$hint ["useuser"] = array (
		$menu ["disable"],
		$menu ["enable"] 
);
$hint ["contbuttonpos"] = array (
		"출력안함",
		"상단",
		"하단",
		"상하모두" 
);
$hint ["content"] = "content";
$hint ["db_engine"] = $sysconf ["db_engine"];
$hint ["dan_size"] = "단의 갯수";
$hint ["databkcol"] = "<input type='button' value='color' class='button' onclick=setcolor('databkcol')>";
$hint ["dataheadbkcol"] = "<input type='button' value='color' class='button' onclick=setcolor('dataheadbkcol')>";
$hint ["dataheadtxtcol"] = "<input type='button' value='color' class='button' onclick=setcolor('dataheadtxtcol')>";
$hint ["datatxtcol"] = "<input type='button' value='color' class='button' onclick=setcolor('datatxtcol')>";
$hint ["defcolor"] = "<input type='button' value='color' class='button' onclick=setcolor('defcolor')>";
$hint ["defcontent"] = "글 작성시 보이는 내용입니다.<br>자동 숨기기로 설정하면 커서가 있는 경우 본문 내용이 자동으로 숨겨집니다.";
$hint ["deffont"] = "기본 글꼴";
$hint ["deffontsize"] = "기본 글꼴 size";
$hint ["dept"] = "";
$hint ["depth"] = "thread depth";
$hint ["division"] = "";
$hint ["dobaelimit"] = "시간/ip당 글쓰기 갯수를 제한합니다.(0이면 검사안함)";
$hint ["show_docnum"] = "문서 번호";
$hint ["domain"] = "connect domain";
$hint ["email"] = "(ex:durk007@chollian.net)";
$hint ["emailcontent"] = "";
$hint ["emailsubject"] = "";
$hint ["entertype"] = array (
		$menu ["disable"],
		$menu ["enable"] 
);
$hint ["fileicon"] = "파일 형식 아이콘";
$hint ["filelimit"] = "업로드 제한크기(단위:kbyte)";
$hint ["attachfile"] = array (
		$menu ["disable"],
		$menu ["enable"] 
);
$hint ["foldericon"] = "쓰레드 아이콘";
$hint ["from_email"] = "(ex:mclkim@daum.net)";
$hint ["from_name"] = "빈칸없이 입력해 주십시오.(ex:홍길동)";
$hint ["gradcolor"] = "<input type='button' value='color' class='button' onclick=setcolor('gradcolor')>";
$hint ["hlinkcolor"] = "<input type='button' value='color' class='button' onclick=setcolor('hlinkcolor')>";
$hint ["homeurl"] = "(ex:http://www.chollian.net)";
$hint ["htmlhead"] = "user html head";
$hint ["htmltail"] = "user html tail";
$hint ["htmltype"] = array (
		"text",
		"html",
		"html&ltbr&gt" 
);
$hint ["id"] = "id";
$hint ["in_date"] = "생성일자";
$hint ["intro"] = "intro";
$hint ["ip"] = "remote ip";
$hint ["jumin"] = "숫자만 입력 가능(ex:8001011177921)";
$hint ["langfile"] = $sysconf ["langfile"];
$hint ["lastlogin"] = "";
$hint ["level"] = "level";
$hint ["linkcolor"] = "<input type='button' value='color' class='button' onclick=setcolor('linkcolor')>";
$hint ["linktarget"] = "link target";
$hint ["linktype"] = array (
		"none",
		"underline",
		"under/overline" 
);
$hint ["listbkcoleven"] = "<input type='button' value='color' class='button' onclick=setcolor('listbkcoleven')>";
$hint ["listbkcolodd"] = "<input type='button' value='color' class='button' onclick=setcolor('listbkcolodd')>";
$hint ["listbuttonpos"] = array (
		"출력안함",
		"상단",
		"하단",
		"상하모두" 
);
$hint ["listheadbkcol"] = "<input type='button' value='color' class='button' onclick=setcolor('listheadbkcol')>";
$hint ["listheadtxtcol"] = "<input type='button' value='color' class='button' onclick=setcolor('listheadtxtcol')>";
$hint ["listtxtcoleven"] = "<input type='button' value='color' class='button' onclick=setcolor('listtxtcoleven')>";
$hint ["listtxtcolodd"] = "<input type='button' value='color' class='button' onclick=setcolor('listtxtcolodd')>";
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
$hint ["mustattachfile"] = "attach file";
$hint ["musthome"] = "homepage";
$hint ["mustjumin"] = "주민번호";
$hint ["mustmail"] = "email";
$hint ["name"] = "빈칸없이 입력해 주십시오.(ex:홍길동)";
$hint ["opinion"] = "opinion";
$hint ["os"] = "";
$hint ["pageline"] = "한 페이지당 출력할 목록 갯수";
$hint ["passwd"] = "수정,삭제시에 필요합니다.";
$hint ["phone"] = "(ex:02-1234-5678)";
$hint ["pid"] = "pid";
$hint ["point"] = "(단위:원)";
$hint ["post"] = "<input type='button' value='우편번호찾기' class='button' onclick=setpost('post','address');>";
$hint ["postadmin"] = "글 작성시 관리자에게 메일 발송";
$hint ["postreply"] = "답변글 작성시 원본 글작성자에게 메일 발송 ";
$hint ["ppid"] = "ppid";
$hint ["price"] = "(단위:원)";
$hint ["private"] = array (
		"a",
		"b",
		"c",
		"d",
		"e",
		"f" 
);
$hint ["privatetype"] = array (
		"public",
		"private",
		"protect" 
);
$hint ["querycount"] = "(단위:회)";
$hint ["reline"] = "[출력 예] :본문 내용입니다. ";
$hint ["repasswd"] = "password 확인을 위해 다시 한번 입력해 주십시요.";
$hint ["resubject"] = "[출력 예] re: 안녕하세요. ";
$hint ["retext"] = "[출력 예] wrote: 홍길동.";
$hint ["readcount"] = "조회수";
$hint ["returnmail"] = array (
		$menu ["disable"],
		$menu ["enable"] 
);
$hint ["sale"] = "(단위:원)";
$hint ["sex"] = array (
		"male",
		"female" 
);
$hint ["short_cont"] = "지정된 길이 이상의 본문은 ... 로 나머지 표시 (0:사용안함)";
$hint ["short_name"] = "지정된 길이 이상의 이름은 ... 로 나머지 표시 (0:사용안함)";
$hint ["short_subject"] = "지정된 길이 이상의 제목은 ... 로 나머지 표시 (0:사용안함)";
$hint ["show_anniversary"] = array (
		$menu ["hide"],
		$menu ["show"] 
);
$hint ["show_checkbox"] = array (
		"숨기기",
		"선택 보기",
		"선택 보기/삭제" 
);
$hint ["show_opinion"] = "의견달기 메뉴";
$hint ["show_findmenu"] = "검색 메뉴";
$hint ["show_homepage"] = array (
		$menu ["hide"],
		$menu ["show"] 
);
$hint ["show_mail"] = array (
		$menu ["hide"],
		$menu ["show"] 
);
$hint ["show_htmltype"] = array (
		$menu ["hide"],
		$menu ["show"] 
);
$hint ["show_ip"] = "from ip";
$hint ["show_inputform"] = "입력폼";
$hint ["show_list"] = "본문 아래 목록";
$hint ["show_name"] = "이름";
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
$hint ["show_indate"] = "등록일자";
$hint ["show_modate"] = "수정일자";
$hint ["skinname"] = $sysconf ["skinname"];
$hint ["smtp_server"] = "(예: 127.0.0.1, hitel.net등) ";
$hint ["special"] = "";
$hint ["step"] = "step";
$hint ["subject"] = "html disable";
$hint ["tablewidth"] = "예) 픽셀: 500 / 퍼센트: 80%";
$hint ["testboard"] = $sysconf ["testboard"];
$hint ["titlebkcol"] = "<input type='button' value='color' class='button' onclick=setcolor('titlebkcol')>";
$hint ["titletxtcol"] = "<input type='button' value='color' class='button' onclick=setcolor('titletxtcol')>";
$hint ["to_email"] = "(여러명에게 보낼 경우 ,로 구분)";
$hint ["to_name"] = "빈칸없이 입력해 주십시오.(ex:홍길동)";
$hint ["uid"] = "<input type='button' value='check uid' class='button' onclick=check_uid(uid)>";
$hint ["usegrad"] = array (
		"사용안함",
		"밝게",
		"어둡게" 
);
$hint ["useskin"] = array (
		$menu ["disable"],
		$menu ["enable"] 
);
$hint ["badprotect"] = array (
		$menu ["disable"],
		$menu ["enable"] 
);
$hint ["userprotect"] = array (
		$menu ["disable"],
		$menu ["enable"] 
);
$hint ["vlinkcolor"] = "<input type='button' value='color' class='button' onclick=setcolor('vlinkcolor')>";
$hint ["work"] = "";
$hint ["workurl"] = "";
$hint ["show_note"] = array (
		$menu ["disable"],
		$menu ["enable"] 
);
$hint ["notefile"] = "파일명(id)을 기준으로 공지합니다.,(콤마) 로 연결하세요";
/**
 * **********************************************************************\
 * \***********************************************************************
 */
$msg ["admin_only"] = "administrator only.";
$msg ["err_create_index"] = "인덱스 파일을 생성할 수가 없습니다";
$msg ["err_create_member"] = "회원 파일을 생성할 수가 없습니다.<br><br>이미 존재하는 파일입니다.";
$msg ["err_def"] = "입력된 모든 정보가 정확한지 확인해 보세요.<br>자세한 문의는 %s 로 하시기 바랍니다.<p>";
$msg ["err_equal_content"] = "같은 내용의 글은 등록할수가 없습니다.<br><br>attention please.";
$msg ["err_dobae_text"] = "도배 방지를 위해 30초에 1개씩만 글을 올리실 수 있습니다.";
$msg ["err_error"] = "오류가 발생했슴다.";
$msg ["err_exist_db"] = "administrator에 의해서 board 생성이 금지 되었습니다.";
$msg ["err_exist_email"] = "이미 등록되어 있는 email 입니다.";
$msg ["err_exist_id"] = "이미 등록되어 있는 아이디 입니다.";
$msg ["err_exist_reply"] = "reply을 먼저 삭제하십시요.";
$msg ["err_file_limit"] = "sorry.<br><br>the file you are trying to upload is too big(%skbyte).";
$msg ["err_found"] = "조건에 해당하는 자료가 없습니다.<br><br>attention please.";
$msg ["err_host_method"] = "정상적으로 글을 작성하여 주시기 바랍니다.";
$msg ["err_no_param"] = "invalid parameter.";
$msg ["err_pass"] = "password가 일치하지 않습니다.";
$msg ["err_permission"] = "파일을 생성할 수가 없습니다.<br><br>디렉토리(파일)의 퍼미션(쓰기권한)을 확인하세요!!";
$msg ["err_post_method"] = "허용되지 않는 접근입니다.";
$msg ["info_def"] = "자세한 문의는 %s 로 하시기 바랍니다.<p>";
$msg ["info_member_only"] = "회원 로그인이 필요한 menu를 선택하셨습니다.<br><br>id와 password를 입력하십시오.";
$msg ["info_save"] = "입력하신 사항을 저장하시겠습니까?";
$msg ["info_save_member"] = "저장되었습니다.<br><br>데이타 접근은 권한승격후 가능합니다.";
$msg ["info_send_mail"] = "email이 정상적으로 발송되었습니다.";
$msg ["member_only"] = "member only.";
$msg ["no_define_db"] = "not defined db name";
$msg ["no_exist_id"] = "등록가능한 아이디입니다.";
$msg ["no_filter_type"] = "bad word detected.<br><br>attention please.";
$msg ["no_input_pass"] = "not input password.";
$msg ["no_match_pass"] = "입력된 두개의 암호가 다릅니다.<br><br>다시 입력해주세요.";
$msg ["no_private_type"] = "파일접근 권한이 없습니다.<br><br>혹은 password가 일치하지 않습니다.";
$msg ["setup_notice"] = "changes have been saved.";
?>

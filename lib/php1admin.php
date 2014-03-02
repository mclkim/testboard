<?php
/************************************************************************\
 * 프로그램명 : 관리자환경설정
 * 특기사항   : 1.관리자 환경별로 그룹화(2003.04.01)
                2.스킨파일 기능(예정)
                3.관리자 비밀번호 검사
                4.비밀번호 설정하기-프로그램변경(2001.12.27)
                5.초기값으로(예정)
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/03
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1form.php');
class form1admin extends form1form {
	function resetstyle() {
		global $envconf;
		
		echo ("<style>\n");
		
		echo (".rolloff{\n");
		echo ("font-size:12px;\n");
		echo ("background-color:#f0f0f0;\n");
		echo ("border-top:#7baeff ridge 1px;\n");
		echo ("border-left:#7baeff ridge 1px;\n");
		echo ("border-right:#7baeff ridge 1px;\n");
		echo ("border-bottom:#7baeff ridge 1px;\n");
		echo ("}\n\n");
		
		echo (".rollon{\n");
		echo ("font-size:14px;font-weight:bold;\n");
		echo ("background-color:#ffffff;\n");
		echo ("border-top:#7baeff solid 2px;\n");
		echo ("border-left:#7baeff solid 2px;\n");
		echo ("border-right:#7baeff solid 2px;\n");
		echo ("border-bottom:#7baeff solid 0px;\n");
		echo ("}\n\n");
		
		echo ("</style>\n");
	}
	function resetjavascript() {
		global $sysconf;
		
		echo ("\n<!-- javascript design -->\n");
		echo ("<script language='javascript' src='$sysconf[home_js]/default.js'></script>\n");
		echo ("<script language='javascript' src='$sysconf[home_js]/admin.js'></script>\n");
	}
	function showlabel($field, $bgcolor = '#f0f0f0', $align = 'right', $width = '25', $color = '#000000') 	// override
	{
		global $label;
		
		$result = if_exists ( $label, $field, $field );
		if (intval ( $width ) > 0 && intval ( $width ) < 101)
			$width .= '%';
		echo ("<td align='$align' bgcolor='$bgcolor' width='$width'><font color='$color'>{$result}&nbsp;</font></td>\n");
	}
	
	// 초기값,저장
	function showcommit() {
		global $menu;
		
		echo ("\t<table width='100%' border=0 cellspacing=0 cellpadding=3>\n");
		echo ("\t<tr>\n");
		echo ("\t<td align=center><input type='reset' value='$menu[reset]' class='button'></td>\n");
		echo ("\t<td align=center><input type='submit' value='$menu[saveexit]' class='button'></td>\n");
		echo ("\t</tr>\n");
		echo ("\t</table>\n");
	}
	
	// 관리자 환경설정 화면정의
	function configform() {
		global $sysconf, $envconf, $hint, $menu;
		
		echo ("<center>\n");
		echo ("\n<!-- configform design -->\n");
		echo ("<form name='configform' method='post' action='$this->prog'>\n");
		echo ("<input type='hidden' name='db'   value='$this->db'>\n");
		echo ("<input type='hidden' name='page' value='$this->page'>\n");
		echo ("<input type='hidden' name='mode' value='admin_save'>\n");
		
		// //////////////////메뉴설정////////////////////
		echo ("\n<!-- tabindex -->\n");
		echo ("<legend>\n");
		echo ("<table width='100%' border=0 cellspacing=0 cellpadding=3>\n");
		echo ("<tr align='center'>\n");
		echo ("<td class=rolloff id=line onclick='tabindex(0)' style='cursor:hand'> $menu[menu1] </td>\n");
		echo ("<td class=rolloff id=line onclick='tabindex(1)' style='cursor:hand'> $menu[menu2] </td>\n");
		echo ("<td class=rolloff id=line onclick='tabindex(2)' style='cursor:hand'> $menu[menu3] </td>\n");
		echo ("<td class=rolloff id=line onclick='tabindex(3)' style='cursor:hand'> $menu[menu4] </td>\n");
		echo ("<td class=rolloff id=line onclick='tabindex(4)' style='cursor:hand'> $menu[menu5] </td>\n");
		echo ("<td class=rolloff id=line onclick='tabindex(5)' style='cursor:hand'> $menu[menu6] </td>\n");
		echo ("<td class=rolloff id=line onclick='tabindex(6)' style='cursor:hand'> $menu[menu7] </td>\n");
		echo ("<td class=rolloff id=line onclick='tabindex(7)' style='cursor:hand'> $menu[menu8] </td>\n");
		echo ("</tr>\n");
		echo ("</table>\n");
		echo ("</legend>\n");
		// //////////////////메뉴설정////////////////////
		echo ("\t<table width='100%' border=0 cellspacing=0 cellpadding=3>\n");
		echo ("\t<tr>\n");
		echo ("\t<td id='innerHTM' valign=top>\n");
		
		echo ("\n<!-- //$menu[menu1]//-->\n");
		echo ("<div id='tab'>\n");
		echo ("<table width='100%' border=0 cellspacing=0 cellpadding=3>\n");
		echo ("<tr>\n");
		echo ("<td align=center width=100% height=23 colspan=2 bgcolor=#6061be>\n");
		echo ("<font color='white'><b>$menu[menu1]</b></font>\n");
		echo ("</td></tr>\n");
		
		// 관리자 이름
		$this->textbox ( "admin_name", $envconf ["admin_name"], 8, 8 );
		
		// 관리자 이메일
		$this->textbox ( "admin_mail", $envconf ["admin_mail"], 25, 50 );
		
		// 관리자 홈페이지
		$this->textbox ( "admin_home", $envconf ["admin_home"], 25, 50 );
		
		// 메인프로그램
		$this->selectbox ( "testboard", $envconf ["testboard"] );
		
		// 데이타베이스 엔진을 환경파일에서 선택,연결하기(2002.07.13)
		$this->selectbox ( "db_engine", $envconf ["db_engine"] );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// smtp 서버이름
		$this->textbox ( "smtp_server", $envconf ["smtp_server"], 25, 50 );
		
		// 메일 형식
		$this->radiobox ( "mailhtml", $envconf ["mailhtml"] );
		
		// 글 작성시 관리자에게 메일 발송
		$this->checkbox ( "postadmin", $envconf ["postadmin"] );
		
		// 답변글 작성시 원본 글작성자에게 메일 발송
		$this->checkbox ( "postreply", $envconf ["postreply"] );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// 감사 메일 제목
		$this->textbox ( "emailsubject", $envconf ["emailsubject"], 55, 55 );
		
		// 감사 메일 내용
		$this->memobox ( "emailcontent", $envconf ["emailcontent"], 5, 55 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		echo ("</table>\n");
		
		// 초기값,저장
		$this->showcommit ();
		echo ("</div>\n");
		// //////////////////메뉴설정////////////////////
		echo ("\n<!-- //$menu[menu2]//-->\n");
		echo ("<div id='tab'>\n");
		echo ("<table width='100%' border=0 cellspacing=0 cellpadding=3>\n");
		echo ("<tr>\n");
		echo ("<td align=center width=100% height=23  colspan=2 bgcolor=#6061be>\n");
		echo ("<font color='white'><b>$menu[menu2]</b></font>\n");
		echo ("</td></tr>\n");
		
		// 메세지 국가별 파일설정(2002.07.13)
		$this->selectbox ( "langfile", $envconf ["langfile"] );
		
		// 게시판 위치
		$this->selectbox ( "boardalign", $envconf ["boardalign"] );
		
		// 브라우저 타이틀
		$this->textbox ( "browsertitle", $envconf ["browsertitle"], 50, 50 );
		
		// 게시판 타이틀
		$this->textbox ( "boardtitle", $envconf ["boardtitle"], 50, 50 );
		
		// 게시판 제목 그림
		$this->textbox ( "boardimage", $envconf ["boardimage"], 25, 50 );
		
		// 게시판 제목 출력방식
		$this->radiobox ( "autotitle", $envconf ["autotitle"] );
		
		// 게시판 배경 그림
		$this->textbox ( "backimage", $envconf ["backimage"], 25, 50 );
		
		// 게시판 배경 색상
		$this->textbox ( "backcolor", $envconf ["backcolor"], 8, 8 );
		
		// 게시판 글꼴 색상
		$this->textbox ( "defcolor", $envconf ["defcolor"], 8, 8 );
		
		// 기본 글꼴,크기
		$this->textbox ( "deffont", $envconf ["deffont"], 8, 8 );
		$this->textbox ( "deffontsize", $envconf ["deffontsize"], 8, 8 );
		
		// 게시판 너비
		$this->textbox ( "tablewidth", $envconf ["tablewidth"], 5, 5 );
		
		// 테이블 간격(cellspacing/cellpadding)
		$this->textbox ( "cellspacing", $envconf ["cellspacing"], 5, 5 );
		$this->textbox ( "cellpadding", $envconf ["cellpadding"], 5, 5 );
		
		// 테이블 테두리 두께
		$this->textbox ( "tableborder", $envconf ["tableborder"], 5, 5 );
		
		// 테이블 테두리 색상
		$this->textbox ( "bordercolor", $envconf ["bordercolor"], 8, 8 );
		
		// 링크 타입
		$this->radiobox ( "linktype", $envconf ["linktype"] );
		
		// 링크 색상
		$this->textbox ( "linkcolor", $envconf ["linkcolor"], 8, 8 );
		$this->textbox ( "hlinkcolor", $envconf ["hlinkcolor"], 8, 8 );
		$this->textbox ( "vlinkcolor", $envconf ["vlinkcolor"], 8, 8 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		echo ("</table>\n");
		
		// 초기값,저장
		$this->showcommit ();
		echo ("</div>\n");
		// //////////////////메뉴설정////////////////////
		echo ("\n<!-- //$menu[menu3]//-->\n");
		echo ("<div id='tab'>\n");
		echo ("<table width='100%' border=0 cellspacing=0 cellpadding=3>\n");
		echo ("<tr>\n");
		echo ("<td align=center width=100% height=23  colspan=2 bgcolor=#6061be>\n");
		echo ("<font color='white'><b>$menu[menu3]</b></font>\n");
		echo ("</td></tr>\n");
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// 사용자 html head
		$this->memobox ( "htmlhead", $envconf ["htmlhead"], 8, 55 );
		
		// 사용자 html tail
		$this->memobox ( "htmltail", $envconf ["htmltail"], 8, 55 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		echo ("</table>\n");
		
		// 초기값,저장
		$this->showcommit ();
		echo ("</div>\n");
		// //////////////////메뉴설정////////////////////
		echo ("\n<!-- //$menu[menu4]//-->\n");
		echo ("<div id='tab'>\n");
		echo ("<table width='100%' border=0 cellspacing=0 cellpadding=3>\n");
		echo ("<tr>\n");
		echo ("<td align=center width=100% height=23  colspan=2 bgcolor=#6061be>\n");
		echo ("<font color='white'><b>$menu[menu4]</b></font>\n");
		echo ("</td></tr>\n");
		
		// 자동 링크 완성
		$help = "&nbsp;target:&nbsp;<input type='text' name='linktarget' value='$envconf[linktarget]' size=10 class=editbox>";
		$this->checkbox ( "autolink", $envconf ["autolink"], $help );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// 체크박스 기능
		$this->radiobox ( "show_checkbox", $envconf ["show_checkbox"] );
		
		// 항목보이기
		$this->showlabel ( "showfield" );
		echo ("<td>");
		$this->showcheck ( "show_docnum", $envconf ["show_docnum"] );
		$this->showcheck ( "fileicon", $envconf ["fileicon"] );
		$this->showcheck ( "foldericon", $envconf ["foldericon"] );
		$this->showcheck ( "show_name", $envconf ["show_name"] );
		$this->showcheck ( "show_indate", $envconf ["show_indate"] );
		$this->showcheck ( "show_modate", $envconf ["show_modate"] );
		$this->showcheck ( "readcount", $envconf ["readcount"] );
		
		$this->showcheck ( "show_list", $envconf ["show_list"] );
		$this->showcheck ( "show_pagemenu", $envconf ["show_pagemenu"] );
		$this->showcheck ( "show_findmenu", $envconf ["show_findmenu"] );
		$this->showcheck ( "show_sortmenu", $envconf ["show_sortmenu"] );
		$this->showcheck ( "show_ip", $envconf ["show_ip"] );
		$this->showcheck ( "show_inputform", $envconf ["show_inputform"] );
		$this->showcheck ( "show_opinion", $envconf ["show_opinion"] );
		echo ("</td></tr>");
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// 버튼 위치
		$this->radiobox ( "listbuttonpos", $envconf ["listbuttonpos"] );
		$this->radiobox ( "contbuttonpos", $envconf ["contbuttonpos"] );
		
		// 제목출력길이
		$this->textbox ( "short_subject", $envconf ["short_subject"], 5, 5 );
		
		// 이름출력길이
		$this->textbox ( "short_name", $envconf ["short_name"], 5, 5 );
		
		// 본문출력길이
		$this->textbox ( "short_cont", $envconf ["short_cont"], 5, 5 );
		
		// 본문 내용 cr/lf 처리여부
		$this->radiobox ( "entertype", $envconf ["entertype"] );
		
		// 표시 줄수
		$this->textbox ( "pageline", $envconf ["pageline"], 5, 5 );
		
		// 단의 갯수
		$this->textbox ( "dan_size", $envconf ["dan_size"], 5, 5 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		echo ("</table>\n");
		
		// 초기값,저장
		$this->showcommit ();
		echo ("</div>\n");
		// //////////////////메뉴설정////////////////////
		echo ("\n<!-- //$menu[menu5]//-->\n");
		echo ("<div id='tab'>\n");
		echo ("<table width='100%' border=0 cellspacing=0 cellpadding=3>\n");
		echo ("<tr>\n");
		echo ("<td align=center width=100% height=23  colspan=2 bgcolor=#6061be>\n");
		echo ("<font color='white'><b>$menu[menu5]</b></font>\n");
		echo ("</td></tr>\n");
		
		// 타이틀 색상
		$this->textbox ( "titletxtcol", $envconf ["titletxtcol"], 8, 8 );
		$this->textbox ( "titlebkcol", $envconf ["titlebkcol"], 8, 8 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// 본문 제목 색상
		$this->textbox ( "dataheadbkcol", $envconf ["dataheadbkcol"], 8, 8 );
		$this->textbox ( "dataheadtxtcol", $envconf ["dataheadtxtcol"], 8, 8 );
		
		// 본문 내용 색상
		$this->textbox ( "databkcol", $envconf ["databkcol"], 8, 8 );
		$this->textbox ( "datatxtcol", $envconf ["datatxtcol"], 8, 8 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// 목록 헤더 색상
		$this->textbox ( "listheadbkcol", $envconf ["listheadbkcol"], 8, 8 );
		$this->textbox ( "listheadtxtcol", $envconf ["listheadtxtcol"], 8, 8 );
		
		// 목록 홀수 색상
		$this->textbox ( "listbkcolodd", $envconf ["listbkcolodd"], 8, 8 );
		$this->textbox ( "listtxtcolodd", $envconf ["listtxtcolodd"], 8, 8 );
		
		// 목록 짝수 색상
		$this->textbox ( "listbkcoleven", $envconf ["listbkcoleven"], 8, 8 );
		$this->textbox ( "listtxtcoleven", $envconf ["listtxtcoleven"], 8, 8 );
		
		// 그라데이션 기능
		$this->radiobox ( "usegrad", $envconf ["usegrad"] );
		
		// 그라데이션 시작색
		$this->textbox ( "gradcolor", $envconf ["gradcolor"], 8, 8 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		echo ("</table>\n");
		
		// 초기값,저장
		$this->showcommit ();
		echo ("</div>\n");
		// //////////////////메뉴설정////////////////////
		echo ("\n<!-- //$menu[menu6]//-->\n");
		echo ("<div id='tab'>\n");
		echo ("<table width='100%' border=0 cellspacing=0 cellpadding=3>\n");
		echo ("<tr>\n");
		echo ("<td align=center width=100% height=23  colspan=2 bgcolor=#6061be>\n");
		echo ("<font color='white'><b>$menu[menu6]</b></font>\n");
		echo ("</td></tr>\n");
		
		// 스킨 사용유무
		$this->radiobox ( "useskin", $envconf ["useskin"] );
		
		// 스킨파일
		$this->selectbox ( "skinname", $envconf ["skinname"] );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// 공지여부
		$this->radiobox ( "show_note", $envconf ["show_note"] );
		
		// 공지파일
		$this->memobox ( "notefile", $envconf ["notefile"], 5, 55 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		echo ("</table>\n");
		
		// 초기값,저장
		$this->showcommit ();
		echo ("</div>\n");
		// //////////////////메뉴설정////////////////////
		echo ("\n<!-- //$menu[menu7]//-->\n");
		echo ("<div id='tab'>\n");
		echo ("<table width='100%' border=0 cellspacing=0 cellpadding=3>\n");
		echo ("<tr>\n");
		echo ("<td align=center width=100% height=23  colspan=2 bgcolor=#6061be>\n");
		echo ("<font color='white'><b>$menu[menu7]</b></font>\n");
		echo ("</td></tr>\n");
		
		// 글 읽기 권한
		$this->radiobox ( "access_read", $envconf ["access_read"] );
		
		// 글 작성 권한
		$this->radiobox ( "access_write", $envconf ["access_write"] );
		
		// 답변글 작성 권한
		$this->radiobox ( "access_reply", $envconf ["access_reply"] );
		
		// 제목 입력 길이 제한
		$this->textbox ( "maxsubj", $envconf ["maxsubj"], 5, 5 );
		
		// 본문 입력 길이 제한
		$this->textbox ( "maxcont", $envconf ["maxcont"], 5, 5 );
		
		// 연속 글쓰기
		$this->textbox ( "dobaelimit", $envconf ["dobaelimit"], 5, 5 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// 답변글 머리말
		$this->textbox ( "resubject", $envconf ["resubject"], 10, 10 );
		
		// 답변글 작성자
		$this->textbox ( "retext", $envconf ["retext"], 10, 10 );
		
		// 답변글 본문
		$this->textbox ( "reline", $envconf ["reline"], 10, 10 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// 기본 본문 메시지
		$this->memobox ( "defcontent", $envconf ["defcontent"], 5, 55 );
		$this->radiobox ( "autohidecont", $envconf ["autohidecont"] );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// 이메일 입력
		$this->radiobox ( "show_mail", $envconf ["show_mail"] );
		
		// 홈페이지 입력
		$this->radiobox ( "show_homepage", $envconf ["show_homepage"] );
		
		// 기념일 입력
		$this->radiobox ( "show_anniversary", $envconf ["show_anniversary"] );
		
		// 카테고리 입력
		$this->radiobox ( "show_category", $envconf ["show_category"] );
		
		// 비밀번호 입력
		$this->radiobox ( "show_passwd", $envconf ["show_passwd"] );
		
		// 태그문서 입력
		$this->radiobox ( "show_htmltype", $envconf ["show_htmltype"] );
		
		// 비공개 문서 입력
		$this->radiobox ( "show_privatetype", $envconf ["show_privatetype"] );
		
		// 필수 입력사항
		$this->showlabel ( "mustfield" );
		echo ("<td>");
		$this->showcheck ( "mustjumin", $envconf ["mustjumin"] );
		$this->showcheck ( "mustmail", $envconf ["mustmail"] );
		$this->showcheck ( "musthome", $envconf ["musthome"] );
		$this->showcheck ( "mustattachfile", $envconf ["mustattachfile"] );
		echo ("</td></tr>");
		
		// 첨부파일
		$help = "<input type='text' name='filelimit' value='$envconf[filelimit]' size=5 class=editbox>&nbsp;<font class=hint>$hint[filelimit]</font>";
		$this->radiobox ( "attachfile", $envconf ["attachfile"], $help );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		echo ("</table>\n");
		
		// 초기값,저장
		$this->showcommit ();
		echo ("</div>\n");
		// //////////////////메뉴설정////////////////////
		echo ("\n<!-- //$menu[menu8]//-->\n");
		echo ("<div id='tab'>\n");
		echo ("<table width='100%' border=0 cellspacing=0 cellpadding=3>\n");
		echo ("<tr>\n");
		echo ("<td align=center width=100% height=23  colspan=2 bgcolor=#6061be>\n");
		echo ("<font color='white'><b>$menu[menu8]</b></font>\n");
		echo ("</td></tr>\n");
		
		// 회원관리
		$help = "<input type='button' value='회원관리' class='button' onclick=popupwindow('$sysconf[testmember]?db=$this->db')>";
		$this->radiobox ( "useuser", $envconf ["useuser"], $help );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// 사용자 차단
		$this->radiobox ( "userprotect", $envconf ["userprotect"] );
		
		// 사용자 차단
		$this->memobox ( "badip", $envconf ["badip"], 5, 55 );
		$this->memobox ( "badname", $envconf ["badname"], 5, 55 );
		$this->memobox ( "badmail", $envconf ["badmail"], 5, 55 );
		$this->memobox ( "badhome", $envconf ["badhome"], 5, 55 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// 글 차단
		$this->radiobox ( "badprotect", $envconf ["badprotect"] );
		$this->memobox ( "badword", $envconf ["badword"], 5, 55 );
		$this->memobox ( "badbanner", $envconf ["badbanner"], 5, 55 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		echo ("</table>\n");
		
		// //////////////////초기값,저장////////////////////
		$this->showcommit ();
		echo ("</div>\n");
		
		echo ("\t</td>\n");
		echo ("\t</tr>\n");
		echo ("\t</table>\n");
		
		echo ("</form>\n");
		echo ("</center>\n");
		
		echo ("<script language='javascript'>tabindex(0);</script>\n");
	}
	
	// 관리자 비밀번호 검사하기
	function checkpassword($userid, $passwd) {
		global $envconf;
		return (is_equal ( $passwd, $envconf ["admin_password"] ));
	}
}
?>

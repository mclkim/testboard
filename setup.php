<?php
/************************************************************************\
 * 프로그램명 : 설치프로그램
 * 특기사항   :
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2003/04
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1form.php');
class form1setup extends form1form {
	function _resetstyle() {
		echo ("<style>@import url('../skin/default/testboard.css');</style>");
	}
	
	// 환경설정 초기값 재정의
	function resetconfig() {
		global $sysconf, $envconf, $btn, $hint;
		
		// only try to send out headers in case
		// those were not sent already
		if (! headers_sent ()) {
			header ( "cache-control: private" );
			header ( "pragma: no-cache" );
		}
		
		$envconf ["tablewidth"] = "700"; // 게시판 너비
		$envconf ["backcolor"] = $envconf ["titlebkcol"];
		
		$hint ["admin_name"] = "";
		$hint ["mysql_host"] = "mysql db의 호스트 네임을 입력하세요";
		$hint ["mysql_user"] = "mysql db의 계정의 id를 입력하세요";
		$hint ["mysql_pass"] = "mysql db의 패스워드를 입력하세요";
		$hint ["mysql_name"] = "mysql db의 네임을 입력하세요";
	}
	function showlabel($field, $bgcolor = '#f0f0f0', $align = 'right', $width = '25', $color = '#ffffff') {
		global $envconf, $label;
		
		$result = if_exists ( $label, $field, $field );
		if (intval ( $width ) > 0 && intval ( $width ) < 101)
			$width .= '%';
		echo ("<th align='$align' bgcolor='$envconf[titlebkcol]' width='$width'><font color='$color'>{$result}&nbsp;</font></th>\n");
	}
	
	// 라이센스
	function readmeform() {
		global $sysconf, $defconf, $btn;
		
		$filename = path_fix ( "$sysconf[path_docs]/readme.txt" );
		$readme = file_get_contents ( $filename );
		
		echo ("<style type='text/css'>");
		echo ("body {margin-left:0cm; margin-top:3cm; margin-right:0cm; margin-bottom:0cm;}");
		echo ("</style>");
		
		echo ("\n<!-- readmeform design -->\n");
		echo ("<center>\n");
		echo ("<table width=600 border=0 cellspacing=5 cellpadding=0>\n");
		// //////////////////////////////////////
		echo ("<tr><td align=center>\n");
		$this->showmemo ( "<testboard>", $readme, 20, 80 );
		echo ("</td></tr>\n");
		// //////////////////////////////////////
		if (get_perms ( "./db" ) == '707' || get_perms ( "./db" ) == '777') {
			echo ("<form name='readmeform' method='post' action='$this->prog'>\n");
			echo ("<input type='hidden' name='mode' value='config'>\n");
			
			echo ("<tr><td align=center>\n");
			if (empty ( $defconf ["admin_password"] ))
				echo ("<font color=white>*.본 소스는 초기 패스워드가 없습니다.<font><p>\n");
			
			echo ("<input type='submit' value='$btn[ok]' class='button'>\n");
			echo ("</td></tr>\n");
			echo ("</form>\n");
		} else {
			echo ("<form name='readmeform' method='post' action='$this->prog'>\n");
			echo ("<input type='hidden' name='mode' value=''>\n");
			
			echo ("<tr><td align=center>\n");
			echo ("<font color=red>'$sysconf[path_db]' 디렉토리의 퍼미션이 '777'로 되어 있지 않습니다.<font><p>\n");
			echo ("<font color=white>'$sysconf[path_db]' 디렉토리가 없으면 생성하여,<font><p>\n");
			echo ("<font color=white>*.텔넷 이나 ftp에서 퍼미션을 조정하세요.<font><p>\n");
			echo ("<input type='submit' value='퍼미션 조정하였습니다' class='button'>\n");
			echo ("</td></tr>\n");
			echo ("</form>\n");
		} // end if else
		  // //////////////////////////////////////
		echo ("</table>\n");
		echo ("</center>\n");
	}
	
	// 관리자 환경설정 화면정의
	// 환경설정후 내용을 이메일로 전달하기(예정)
	function configform() {
		global $defconf, $menu;
		
		echo ("\n<!-- configform design -->\n");
		echo ("<form name='configform' method='post' action='$this->prog'>\n");
		echo ("<input type='hidden' name='mode' value='admin_save'>\n");
		
		echo ("<center>\n");
		echo ("\t<table width=600 border=0 cellspacing=5 cellpadding=0>\n");
		echo ("\t<tr>\n");
		echo ("\t<td>\n");
		// //////////////////////////////////////
		echo ("<table width=100% border=0 cellspacing=0 cellpadding=3>\n");
		
		// 관리자 이름
		$this->textbox ( "admin_name", $defconf ["admin_name"], 8, 8 );
		$this->textbox ( "admin_mail", $defconf ["admin_mail"], 25, 50 );
		$this->textbox ( "admin_home", $defconf ["admin_home"], 25, 50 );
		$this->passwordbox ( "admin_password", $defconf ["admin_password"], 8, 8 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		$this->selectbox ( "langfile", $defconf ["langfile"] );
		$this->selectbox ( "db_engine", $defconf ["db_engine"] );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// 관리자 이름
		$this->textbox ( "file_host", $defconf ["file_host"], 50, 50 );
		$this->readbox ( "file_user", $defconf ["file_user"], 10, 10 );
		$this->readbox ( "file_pass", $defconf ["file_pass"], 10, 10 );
		$this->readbox ( "file_name", $defconf ["file_name"], 25, 50 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// 관리자 이름
		$this->textbox ( "mysql_host", $defconf ["mysql_host"], 25, 50 );
		$this->textbox ( "mysql_user", $defconf ["mysql_user"], 10, 10 );
		$this->passwordbox ( "mysql_pass", $defconf ["mysql_pass"], 10, 10 );
		$this->textbox ( "mysql_name", $defconf ["mysql_name"], 25, 50 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// 관리자 이름
		$this->textbox ( "oracle_host", $defconf ["oracle_host"], 25, 50 );
		$this->textbox ( "oracle_user", $defconf ["oracle_user"], 10, 10 );
		$this->passwordbox ( "oracle_pass", $defconf ["oracle_pass"], 10, 10 );
		$this->readbox ( "oracle_name", $defconf ["oracle_name"], 25, 50 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		// 관리자 이름
		$this->textbox ( "pop3_host", $defconf ["pop3_host"], 25, 50 );
		$this->readbox ( "pop3_user", $defconf ["pop3_user"], 10, 10 );
		$this->readbox ( "pop3_pass", $defconf ["pop3_pass"], 10, 10 );
		$this->readbox ( "pop3_name", $defconf ["pop3_name"], 25, 50 );
		
		// 수평 분리선
		$this->linebox ( 100, 2 );
		
		echo ("</table>\n");
		// //////////////////////////////////////
		echo ("<table width=100% border=0 cellspacing=0 cellpadding=3>\n");
		echo ("<tr>\n");
		echo ("<td align=center><input type='reset' value='$menu[reset]' class='button'></td>\n");
		echo ("<td align=center><input type='submit' value='$menu[saveexit]' class='button'></td>\n");
		echo ("</tr>\n");
		echo ("</table>\n");
		// //////////////////////////////////////
		echo ("\t</td></tr></table>\n");
		echo ("</form>\n");
		echo ("</center>\n");
	}
	
	// 관리자 비밀번호 검사하기
	function checkpassword($userid, $passwd) {
		global $defconf;
		// *.본 소스는 초기 패스워드가 없습니다
		return ($passwd == $defconf ["admin_password"]);
	}
}

// 인스턴스 변수 $inst를 new 연산자를 이용해 지정하고 있다.
$inst = new form1setup ();

// 모드설정
$inst->mode ( $_REQUEST );
$inst->mode_swap ( $inst->mode, $inst->cmd );

// 홈페이지 시작
switch ($inst->mode) {
	case '' :
		$inst->htmlheader ( false );
		$inst->columnheader ();
		$inst->readmeform ();
		$inst->columnbottom ( 'center' );
		$inst->htmlbottom ( false );
		break;
	
	case 'admin_login' :
		$inst->testboardadminlogin ( '', $inst->cmd );
		break;
	
	case 'config' :
		if (! $inst->checkpassword ( $userid, $passwd ))
			$inst->htmlerror ( $msg ["err_pass"] );
		$inst->htmlheader ( false );
		$inst->columnheader ();
		$inst->configform ();
		$inst->columnbottom ( 'center' );
		$inst->htmlbottom ( false );
		break;
	
	case 'mysql' :
		$inst->obj = $inst->factory ( $inst->mode );
		$inst->obj->_connect_db ();
		$inst->htmlinfo ( $msg ["info_ok"] );
		break;
	
	case 'admin_save' :
		// 편법을 이용한 글쓰기 방지(2003.03.13)
		if (! is_host ())
			$inst->htmlerror ( $msg ["err_host_method"] );
			
			// 접근허용검사(2001.12.27)
		if (! is_post ())
			$inst->htmlerror ( $msg ["err_post_method"] );
			
			// 데이타저장하기
		$defconf = a4b ( $defconf, $sysconf );
		$defconf = a4b ( $defconf, $_POST );
		
		if (! savedefineconfig ( $sysconf ["file_def"], $defconf ))
			$inst->htmlerror ( $msg ["err_permission"] );
		
		echo "<script> this.location.replace('testman.php'); </script>";
		break;
	
	default :
		$inst->htmlerror ( $msg ["err_no_param"] );
		break;
} // end switch

$inst->free ();
?>

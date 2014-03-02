<?php
/************************************************************************\
 * 프로그램명 : 메인프로그램(메일)
 * 특기사항   :
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2004/04
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('../lib/php1board.php');

// <주의>파일이름과 클래스이름을 일치해야...
class form1mail extends form1board {
	// 환경설정 초기값 재정의
	function resetconfig() {
		global $sysconf, $envconf, $defconf, $btn, $hint;
		
		$envconf ["testboard"] = 'testmail.php'; // 메인프로그램 설정
		$envconf ["db_engine"] = 'pop3'; // 데이타베이스 설정
		$envconf ["useuser"] = 1; // 회원관리
		$envconf ["pageline"] = "30"; // 한 페이지당 출력할 목록 갯수
		
		$hint ["userid"] = '@' . $defconf ["pop3_host"];
		
		$defconf ["pop3_user"] = $this->session->getsess ( "userid" );
		$defconf ["pop3_pass"] = $this->session->getsess ( "passwd" );
		$this->sess_user = $this->session->getsess ( "userid" );
	}
	function checkpassword($userid, $passwd) {
		return $this->obj->_connect ();
	}
}

// 인스턴스 변수 $inst를 new 연산자를 이용해 지정하고 있다.
$inst = new form1mail ();

// 모드설정
$inst->mode ( $_REQUEST );
$inst->mode_swap ( $inst->mode, $inst->cmd );
// todo::
$inst->db = $inst->session->getsess ( "userid" );

// 홈페이지 시작
switch ($inst->mode) {
	case '' :
	case 'list' :
		$inst->refresh ( 'webmail' );
		break;
	
	case 'admin_logout' :
	case 'user_login' :
	case 'login' :
		// 로그인하기
		$inst->testboarduserlogin ( $userid, $inst->cmd );
		break;
	
	case 'admin_logout' :
	case 'user_logout' :
	case 'logout' :
		// 세션파일 파기(2003.04.20)
		$inst->session->destroy ();
		unset ( $inst->session );
		$inst->commit ( 'webmail' );
		break;
	
	case 'webmail' :
		if (! $inst->checkpassword ( $userid, $passwd )) {
			unset ( $inst->session );
			$inst->htmlerror ( $msg ["no_private_type"] );
		}
		$inst->testboardindex ();
		$inst->testboardlist ();
		break;
	
	case 'read' :
		if (! $inst->checkpassword ( $userid, $passwd )) {
			unset ( $inst->session );
			$inst->htmlerror ( $msg ["no_private_type"] );
		}
		$inst->testboardindex ( false );
		$inst->testboardread ();
		break;
	
	default :
		$inst->htmlerror ( $msg ["err_no_param"] );
		break;
} // end switch

$inst->free ();
?>

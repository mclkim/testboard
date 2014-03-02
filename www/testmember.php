<?php
/************************************************************************\
 * 프로그램명 : 메인프로그램(회원관리)
 * 특기사항   : 1.메모(쪽지)기능(예정)
                2.쪽지보내기(예정)
                3.회원정보공개하기(예정)
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/04
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('../lib/php1member.php');

// 인스턴스 변수 $inst를 new 연산자를 이용해 지정하고 있다.
$inst = new form1member ();

// 모드설정
$inst->mode ( $_REQUEST );
$inst->mode_swap ( $inst->mode, $inst->cmd );

// 홈페이지 시작
switch ($inst->mode) {
	case '' :
	case 'list' :
		// 회원파일찾기
		$inst->index = $inst->alluserfile ();
		$inst->testboardlist ();
		break;
	
	case 'print' :
		if (! $inst->session->is_reg ( $inst->sid ))
			$inst->htmlerror ( $msg ["no_private_type"] );
		$inst->testboardremote ();
		break;
	
	case 'remote' :
	case 'read' :
		if (! $inst->checkpassword ( $userid, $passwd ))
			$inst->htmlerror ( $msg ["err_pass"] );
		$inst->testboardremote ();
		break;
	
	case 'write' :
	case 'modify' :
	case 'member' :
		// 정보변경
		if (! $inst->checkpassword ( $userid, $passwd ))
			$inst->htmlerror ( $msg ["err_pass"] );
		$inst->testboardinput ();
		break;
	
	case 'admin_login' :
		$inst->testboardadminlogin ( '', $inst->cmd );
		break;
	
	case 'user_login' :
		// 로그인하기
		$inst->testboarduserlogin ( $userid, $inst->cmd );
		break;
	
	case 'admin_logout' :
	case 'user_logout' :
	case 'logout' :
		// 세션파일 파기(2003.04.20)
		$inst->session->destroy ();
		$inst->commit ();
		break;
	
	// 사용중인 아이디가 있는지 확인(2002.03.14)
	// 중복 아이디 검색
	case 'check_id' :
		if ($inst->loaduserfile ( $userid ))
			$inst->htmlerror ( $msg ["err_exist_id"] );
		else
			$inst->htmlinfo ( $msg ["no_exist_id"], 'history.go(-2)' );
		break;
	
	case 'join' :
		$inst->testboardinput ();
		break;
	
	case 'find_pass' :
		// 회원정보 메일보내기
		if (! $inst->findpassword ( $email ))
			$inst->htmlerror ( $msg ["err_error"] );
		$inst->htmlinfo ( $msg ["info_send_mail"], 'history.go(-4)' );
		break;
	
	case 'admin_save' :
		// 편법을 이용한 글쓰기 방지(2003.03.13)
		if (! is_host ())
			$inst->htmlerror ( $msg ["err_host_method"] );
			
			// 접근허용검사(2001.12.27)
		if (! is_post ())
			$inst->htmlerror ( $msg ["err_post_method"] );
			
			// 데이타 초기값
		$data = $def_user;
		
		// 회원정보변경인경우
		if ($temp = $inst->loaduserfile ( $userid ))
			$data = a4b ( $data, $temp );
			
			// 데이타(글) 저장하기
		$data = a4b ( $data, $_POST );
		
		// 비밀번호설정
		if (is_equal ( $passwd, $repasswd ))
			$data ["passwd"] = $passwd;
		else
			$inst->htmlerror ( $msg ["no_match_pass"] );
			
			// 회원파일 저장하기
		if ($inst->saveuserfile ( $data ))
			$inst->htmlinfo ( $msg ["info_save_member"], 'history.go(-3)' );
		break;
	
	case 'admin_modify' :
		// 관리자 비밀번호 검사하기
		if (! $inst->checkpassword ( $userid, $passwd ))
			$inst->htmlerror ( $msg ["err_pass"] );
		$inst->testboardconfig ();
		break;
	
	case 'admin_delete' :
		// 관리자 비밀번호 검사하기
		if (! $inst->checkpassword ( $userid, $passwd ))
			$inst->htmlerror ( $msg ["err_pass"] );
		$inst->moveuserfile ( $userid );
		$inst->refresh ();
		break;
	
	default :
		$inst->htmlerror ( $msg ["err_no_param"] );
		break;
} // end switch

$inst->free ();
?>

<?php
/************************************************************************\
 * 프로그램명 : 메인프로그램(관리자)
 * 특기사항   :
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/01
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('../lib/php1admin.php');

// 사용자 db가 없다면...
if (empty ( $db )) {
	showerror ( "not defined db name! please read readme.txt" );
} // endif
  
// 인스턴스 변수 $inst를 new 연산자를 이용해 지정하고 있다.
$inst = new form1admin ();

// 모드설정
$inst->mode ( $_REQUEST );
$inst->mode_swap ( $inst->mode, $inst->cmd );

// 홈페이지 시작
switch ($inst->mode) {
	case '' :
		$inst->refresh ( 'config' );
		break;
	
	case 'admin_login' :
		$inst->testboardadminlogin ( '', $inst->cmd );
		break;
	
	case 'config' :
		// 관리자 비밀번호 검사하기
		if (! $inst->checkpassword ( $userid, $passwd ))
			$inst->htmlerror ( $msg ["err_pass"] );
		$inst->testboardconfig ();
		break;
	
	case 'newpass' :
		// 관리자 비밀번호 검사하기
		if (! $inst->checkpassword ( $userid, $passwd ))
			$inst->htmlerror ( $msg ["err_pass"] );
			
			// 편법을 이용한 글쓰기 방지(2003.03.13)
		if (! is_host ())
			$inst->htmlerror ( $msg ["err_host_method"] );
			
			// 접근허용검사(2001.12.27)
		if (! is_post ())
			$inst->htmlerror ( $msg ["err_post_method"] );
			
			// 비밀번호설정
		if (is_equal ( $newpass, $confirm ))
			$envconf ["admin_password"] = $newpass;
		else
			$inst->htmlerror ( $msg ["no_match_pass"] );
			
			// 환경설정 파일을 저장한다.
		if (! saveenvironmentconfig ( $sysconf ["file_cfg"], $envconf ))
			$inst->htmlerror ( $msg ["err_permission"] );
			
			// 메세지출력
		$inst->htmlinfo ( $msg ["setup_notice"] );
		break;
	
	case 'admin_save' :
		// 편법을 이용한 글쓰기 방지(2003.03.13)
		if (! is_host ())
			$inst->htmlerror ( $msg ["err_host_method"] );
			
			// 접근허용검사(2001.12.27)
		if (! is_post ())
			$inst->htmlerror ( $msg ["err_post_method"] );
			
			// 비밀번호보호
		unset ( $_POST ["admin_password"] );
		
		// 데이타저장하기
		$envconf = a4b ( $envconf, $_POST );
		
		// 환경설정 파일을 저장한다.
		if (! saveenvironmentconfig ( $sysconf ["file_cfg"], $envconf ))
			$inst->htmlerror ( $msg ["err_permission"] );
			
			// 새로고침
		$inst->commit ();
		break;
	
	case 'new_folder' :
		// 관리자 비밀번호를 입력해야 폴더를 만든다..
		if (! $inst->checkpassword ( $userid, $passwd ))
			$inst->htmlerror ( $msg ["err_pass"] );
			
			// 데이타베이스를 사용하여 테이블생성 스키마로 필요한 테이블을 생성한다.(예정)
		if (! $inst->obj->create_db ( $inst->db ))
			$inst->htmlerror ( $msg ["err_error"] );
			
			// 폴더생성
		if (! $inst->testboardnewfolder ())
			$inst->htmlerror ( $msg ["err_permission"] );
			
			// 환경설정 파일을 저장한다.
		if (! saveenvironmentconfig ( $sysconf ["file_cfg"], $envconf ))
			$inst->htmlerror ( $msg ["err_permission"] );
			
			// 새로고침
		$inst->refresh ( 'config' );
		break;
	
	default :
		$inst->htmlerror ( $msg ["err_no_param"] );
		break;
} // end switch

$inst->free ();
?>

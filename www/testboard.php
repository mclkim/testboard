<?php
/************************************************************************\
 * 프로그램명 : 메인프로그램(게시판)
 * 특기사항   : 1.비밀번호 암호화(예정)
                2.프린트기능보완(예정)
                3.자동메일기능보완(예정)
                4.공지기능 추가(2002.11.05)
                5.선택글 모두보기 기능(2003.04.01)
                6.선택글 삭제하기 기능(예정)
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/01
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once '../lib/php1board.php';

// 사용자 db가 없다면...
if (empty ( $db )) {
	showerror ( "not defined db name! please read readme.txt" );
} // endif
  
// 사용자 db 폴더가 없다는 것은 최초로 실행됨을 의미한다.
  // 관리자 비밀번호를 확인해서. 새 게시판을 생성할 것인지?(2001.04.01)
$filename = $sysconf ["file_cfg"];

if (! file_exists ( $filename ) || ! is_file ( $filename ) || ! is_readable ( $filename )) {
	echo "<script> this.location.replace('testadmin.php?db=$db&mode=new_folder'); </script>";
	exit ();
} // endif else
  
// 클래스 생성//스킨파일적용
$inst = testboard::skins ( $envconf ["skinname"] );

// 모드설정&변경
$inst->mode ( $_REQUEST );
$inst->mode_swap ( $inst->mode, $inst->cmd );

// 홈페이지 시작
switch ($inst->mode) {
	case '' :
		// 쿠키사용
		if (! $_COOKIE ["$inst->db/count"]) {
			// 접속수 증가하기
			$inst->readhitcount ( 1 );
			if (! defined ( '_debug_' ))
				setcookie ( "$inst->db/count", "cookie_count", time () + 3600 );
		} // end if
	
	case 'find' :
	case 'list' :
		$inst->testboardindex ();
		$inst->testboardlist ();
		break;
	
	case 'read' :
		if (! $inst->checkpassword ( $userid, $passwd ))
			$inst->htmlerror ( $msg ["no_private_type"] );
		$inst->testboardindex ( false );
		$inst->testboardread ();
		break;
	
	case 'print' :
		if (! $inst->session->is_reg ( $inst->sid ))
			$inst->htmlerror ( $msg ["no_private_type"] );
		$inst->testboardremote ();
		break;
	
	case 'remote' :
		if (! $inst->checkpassword ( $userid, $passwd ))
			$inst->htmlerror ( $msg ["no_private_type"] );
		$inst->testboardindex ( false );
		$inst->testboardremote ();
		break;
	
	case 'reply' :
	case 'write' :
	case 'modify' :
		if (! $inst->checkpassword ( $userid, $passwd ))
			$inst->htmlerror ( $msg ["no_private_type"] );
		$inst->testboardinput ();
		break;
	
	case 'delete' :
		if (! $inst->checkpassword ( $userid, $passwd ))
			$inst->htmlerror ( $msg ["no_private_type"] );
		$inst->testboardmove ();
		$inst->refresh ();
		break;
	
	case 'save' :
		// 편법을 이용한 글쓰기 방지(2003.03.13)
		if (! is_host ())
			$inst->htmlerror ( $msg ["err_host_method"] );
			
			// 접근허용검사(2001.12.27)
		if (! is_post ())
			$inst->htmlerror ( $msg ["err_post_method"] );
			
			// 도배인지 아닌지 검사;;
			// 우선 같은 아이피대에 초이내의 글은 도배로 간주
			// 도배 방지를 위해 30초에 1개씩만 글을 올리실 수 있습니다.
			// 서버와 클라이언트의 시간차이로 쿠키기능을 제대로 사용할수 없슴
		if (is_equal ( $save_remote, remote_addr ) && time () < $save_time + 30)
			$inst->htmlerror ( $msg ["err_dobae_text"] );
			
			// 필터링
		if ($inst->checkfilter ( $_POST ))
			$inst->htmlerror ( $msg ["no_filter_type"] );
			
			// 비밀번호 획득 취약점(예상)
		$inst->testboardsave ( $_POST, $inst->cmd );
		
		// ip 와 쿠키 사용으로 글 도배 방지
		if (! defined ( '_debug_' )) {
			setcookie ( "save_remote", remote_addr );
			setcookie ( "save_time", time () );
		} // endif
		
		if (! defined ( '_debug_' ))
			$inst->refresh ();
		break;
	
	case 'down' :
		if (! is_host ())
			die ();
			// 사용권한검사
		if (! $inst->checkpassword ( $userid, $passwd ))
			$inst->htmlerror ( $msg ["no_private_type"] );
			// 현재글의 다운수를 올림
		$inst->savehitdown ( $inst->db, $inst->id );
		// 다운로드(파일이 없을경우 무시함)
		$inst->testboarddown ();
		// $inst->refresh();
		break;
	
	case 'admin_login' :
		$inst->testboardadminlogin ( '', $inst->cmd );
		break;
	
	case 'user_login' :
		// 로그인하기
		$inst->testboarduserlogin ( $userid, $inst->cmd );
		break;
	
	case 'login' :
		// 로그인 세션 설정(2002.03.13)
		if (! $inst->usercheck ())
			$inst->htmlerror ( $msg ["err_pass"] );
			// 로그인횟수 기록하기
		$inst->savequerycount ( $userid );
		$inst->refresh ();
		break;
	
	case 'admin_logout' :
	case 'user_logout' :
	case 'logout' :
		// 세션파일 파기(2003.04.20)
		$inst->session->destroy ();
		unset ( $inst->session );
		$inst->commit ();
		break;
	
	case 'poll' :
		/**
		 * *todo:
		 * //같은 번호를 클릭한 경우
		 * if ($cook_pollid==$poll) unset($poll);
		 *
		 * //같은 사람이 클릭한 경우
		 * if ($cook_remote==remote_addr) unset($poll);
		 * **
		 */
		// ip 및 쿠키 사용으로 복수투표 방지 기능(하루에 한번).
		if (! defined ( '_debug_' )) {
			setcookie ( "cook_pollid", $poll, time () + 3600 );
			setcookie ( "cook_remote", remote_addr, time () + 3600 );
		} // endif
		  // 투표하기
		if (isset ( $poll ))
			$inst->rollhitcount ( $poll );
		$inst->testboardpoll ();
		break;
	
	case 'opinion' :
		$inst->saveopinion ( $name, $opinion );
		$inst->refresh ( $inst->cmd );
		break;
	
	case 'find' :
		$inst->refresh ();
		break;
	
	case 'stat' :
		$inst->testboardstat ();
		break;
	
	case 'view_all' :
		// 비공개글인경우 제외(2003.04.01)
		$inst->testboardreadlist ( $selected );
		break;
	
	case 'delete_all' :
		break;
	
	default :
		$inst->htmlerror ( $msg ["err_no_param"] );
		break;
} // end switch

$inst->free ();
?>

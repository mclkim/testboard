<?php
/************************************************************************\
 * 프로그램명 : 화면정의(데이타관리)
 * 특기사항   : 1.데이타정보변경(예정)
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2002/09
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1board.php');

// 사용자 db가 없다면...
if (empty ( $db )) {
	showerror ( "not defined db name! please read readme.txt" );
} // endif
  
// 사용자 db 폴더가 없다는 것은 최초로 실행됨을 의미한다.
  // 관리자 비밀번호를 확인해서. 새 게시판을 생성할 것인지?(2001.04.01)
$filename = $sysconf ["file_cfg"];

if (! file_exists ( $filename ) || ! is_file ( $filename ) || ! is_readable ( $filename )) {
	Header ( "Location: testadmin.php?db=$db&mode=new_folder" );
	exit ();
} // endif else
class testdata extends form1board {
	// 환경설정 초기값 재정의
	function resetconfig() {
		global $sysconf, $envconf, $btn;
		
		$envconf ["useuser"] = 0; // 회원관리
		
		$envconf ["show_list"] = 0; // 본문 밑으로 문서목록 보이기
		$envconf ["show_findmenu"] = 0; // 검색메뉴 보이기
		
		$envconf ["access_read"] = 1; // 글 읽기권한
		$envconf ["access_write"] = 1; // 글 쓰기권한
		
		$envconf ["pageline"] = "30"; // 한 페이지당 출력할 목록 갯수
	}
	
	// 목록보이기
	function testboardlist() {
		$this->htmlheader ();
		$this->columnheader ();
		
		$this->showlist ( $this->db, $this->data );
		$this->showpagemenu ( $this->db );
		
		$this->columnbottom ();
		$this->htmlbottom ();
	}
	
	// 본문 출력하기
	function testboardread() {
		$this->htmlheader ();
		$this->columnheader ();
		$this->showdata ( $this->db, $this->id );
		$this->columnbottom ();
		$this->htmlbottom ();
	}
	
	// 본문 입력하기
	function testboardinput() {
		$this->htmlheader ();
		$this->columnheader ();
		
		$this->inputform ();
		
		$this->columnbottom ();
		$this->htmlbottom ();
	}
	
	// 관리자 비밀번호 검사하기
	function checkpassword($userid, $passwd) {
		global $envconf;
		return (is_equal ( $passwd, $envconf ["admin_password"] ));
	}
	
	// 데이타설정 화면정의
	function configform() {
		global $envconf, $btn, $label, $hint, $def_data;
		
		// 데이타 초기값
		$data = $def_data;
		
		// 데이타파일 읽기
		if ($temp = $this->obj->loaddatafile ( $this->db, $this->id ))
			$data = a4b ( $data, $temp );
		
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='1' cellpadding='2'>\n");
		echo ("\n<!-- inputform design -->\n");
		echo ("<form name='inputform' method='post' action='$this->prog'>\n");
		echo ("<input type='hidden' name='db'   value='$this->db'>\n");
		echo ("<input type='hidden' name='cmd'  value='$this->mode'>\n");
		echo ("<input type='hidden' name='page' value='$this->page'>\n");
		echo ("<input type='hidden' name='id'   value='$this->id'>\n");
		echo ("<input type='hidden' name='mode' value='admin_save'>\n");
		
		// 데이타정보
		foreach ( $data as $key => $val ) {
			$size = strlen ( $val ) ? strlen ( $val ) + 1 : "";
			
			// 코드형인경우
			if (is_array ( $hint [$key] ))
				$this->radiobox ( $key, $val );
			else
				$this->textbox ( $key, $val, $size, 0 );
		} // end foreach
		  
		// 비밀번호
		echo ("<input type='hidden' name='passwd' value='$data[passwd]'>\n");
		echo ("<input type='hidden' name='repasswd' value='$data[passwd]'>\n");
		
		// 수평 분리선
		echo ("<tr><td colspan=3><hr size=1 noshade></td></tr>\n");
		
		// 확인,취소버튼
		echo ("<tr><td colspan=3 align='center'>\n");
		echo ("<input type='button' value='$btn[ok]' class='button' onclick='submit()'>\n");
		echo ("<input type='button' value='$btn[cancel]' class='button' onclick='javascript:history.back()'>\n");
		echo ("</td></tr>\n");
		echo ("</form>\n");
		echo ("</table>\n");
	}
	
	// 목록 보이기
	function showlist($db, $index) {
		global $sysconf, $envconf, $btn, $label;
		
		// 홀짝줄에 대한 색상
		$listbkcol = array (
				$envconf ["listbkcolodd"],
				$envconf ["listbkcoleven"] 
		);
		$listtxtcol = array (
				$envconf ["listtxtcolodd"],
				$envconf ["listtxtcoleven"] 
		);
		
		// 글 출력 시작
		echo ("\n<!-- showlist design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='1' cellpadding='2'>\n");
		echo ("<tr align=center bgcolor='$envconf[listheadbkcol]'>\n");
		
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[id]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[pid]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[ppid]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[depth]</font></th>\n");
		
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[subject]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[name]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[mo_date]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[readcount]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$btn[modify]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$btn[delete]</font></th>\n");
		echo ("</tr>\n");
		
		// 글 목록 번호
		$num = $this->total_data - max ( 0, $this->current_page - 1 ) * $this->pageline + 1;
		
		// 목록시작
		for(reset ( $index ), $ii = 0; list ( $key, $data ) = each ( $index );) {
			extract ( $data );
			$num --;
			
			echo ("\n<!-- $key -->\n");
			$bg = ($num & 1); // 다른방법으로 한번 해봄
			
			echo ("<tr align=center bgcolor='$listbkcol[$bg]'>\n");
			
			// 순서
			echo ("<td><font color='$listtxtcol[$bg]'>$id</font></td>\n");
			echo ("<td><font color='$listtxtcol[$bg]'>$pid</font></td>\n");
			echo ("<td><font color='$listtxtcol[$bg]'>$ppid</font></td>\n");
			echo ("<td><font color='$listtxtcol[$bg]'>$depth</font></td>\n");
			
			echo ("<td align=left><font color='$listtxtcol[$bg]'>\n");
			echo ("<a href='$this->prog?db=$this->db&mode=read&id=$key&page=$this->page'>$subject</a>\n");
			echo ("</font></td>\n");
			
			// 이름
			echo ("<td><font color='$listtxtcol[$bg]'>$name</font></td>\n");
			echo ("<td><font color='$listtxtcol[$bg]'>$mo_date</font></td>\n");
			echo ("<td><font color='$listtxtcol[$bg]'>$readcount</font></td>\n");
			
			// 수정,삭제
			echo ("<td><a href='$this->prog?db=$this->db&mode=admin_modify&id=$key&page=$this->page&ff=$this->ff&fw=$this->fw'>$btn[modify]</a></td>\n");
			echo ("<td><a href='$this->prog?db=$this->db&mode=admin_delete&id=$key&page=$this->page&ff=$this->ff&fw=$this->fw'>$btn[delete]</a></td>\n");
			
			echo ("</tr>\n");
		} // end for
		
		echo ("</table>\n");
	}
}

// 기본스킨읽기
$inst = new testdata ();

// 모드설정
$inst->mode ( $_REQUEST );
$inst->mode_swap ( $inst->mode, $inst->cmd );

// 홈페이지 시작
switch ($inst->mode) {
	case '' :
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
			
			// 비밀번호 획득 취약점(예상)
		$inst->testboardsave ( $_POST, $inst->cmd );
		$inst->refresh ();
		break;
	
	case 'admin_save' :
		// 편법을 이용한 글쓰기 방지(2003.03.13)
		if (! is_host ())
			$inst->htmlerror ( $msg ["err_host_method"] );
			
			// 접근허용검사(2001.12.27)
		if (! is_post ())
			$inst->htmlerror ( $msg ["err_post_method"] );
			
			// 글 파일 읽기
		$data = $inst->obj->loaddatafile ( $inst->db, $inst->id );
		
		// 데이타저장하기
		$data = a4b ( $data, $_POST );
		
		// 수정일자 변경오류
		$data ["mo_time"] = time_now;
		
		$inst->obj->savedatafile ( $data, $inst->db, $inst->cmd );
		$inst->refresh ();
		break;
	
	case 'user_login' :
	case 'admin_login' :
		$inst->testboardadminlogin ( '', $inst->cmd );
		break;
	
	case 'user_logout' :
	case 'admin_logout' :
		// 세션파일 파기(2003.04.20)
		$inst->session->destroy ();
		unset ( $inst->session );
		$inst->commit ();
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
		$inst->refresh ();
		break;
	
	default :
		$inst->htmlerror ( $msg ["err_no_param"] );
		break;
} // end switch

$inst->free ();
?>

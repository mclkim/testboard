<?php
/************************************************************************\
 * 프로그램명 : 화면정의(게시판)
 * 특기사항   : 1.비공개문서 기능(2001.04.23)
                2.글 쓰기 화면정의
                3.글 쓰기 로그인 화면정의
                4.비밀번호 검사
                5.주민번호 보이기(2001.10.08)
                6.정회원검사(오류)
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/01
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1save.php');
class form1form extends form1save {
	// 글 글쓰기 화면정의
	function inputform() {
		global $envconf, $btn, $label, $msg, $def_data;
		global $cook_user;
		
		// 데이타 초기값
		$data = $def_data;
		
		// 기본 본문 메시지
		if (intval ( $envconf ["autohidecont"] ))
			$content = $envconf ["defcontent"];
			
			// 임시
		$this->session->setsess ( "attachfile", null );
		$this->session->setsess ( "small_image", null );
		
		switch ($this->mode) {
			// 답변
			case 'reply' :
				// 글 파일 읽기
				if (! $temp = $this->data [$this->id])
					$temp = $this->obj->loaddatafile ( $this->db, $this->id );
					
					// 답변글 제목 적용
				$data ["subject"] = $envconf ["resubject"] . $temp ["subject"];
				
				// 답변글 작성자 적용
				$content = "\n\n" . $envconf ["retext"] . $temp ["name"] . "\n\n";
				
				// 본문내용 파일 읽기
				$content .= $this->obj->loadcontentfile ( $data, $this->db, $this->id );
				
				// 답변글 본문 적용
				$content = ereg_replace ( "\n", "\n" . $envconf ["reline"], $content );
				break;
			
			// 수정
			case 'modify' :
				// 글 파일 읽기
				if (! $data = $this->data [$this->id])
					$data = $this->obj->loaddatafile ( $this->db, $this->id );
					
					// 세션(첨부파일)정보(2004.03.10)
				$this->session->setsess ( "attachfile", $data ["attachfile"] );
				$this->session->setsess ( "small_image", $data ["small_image"] );
				
				// 본문내용 파일 읽기
				$content = $this->obj->loadcontentfile ( $data, $this->db, $this->id );
				break;
		} // end switch
		  
		// 쿠기정보
		$data = a4b ( $data, $cook_user );
		
		// 세션(파일) 회원정보
		$data = a4b ( $data, $this->sess_user );
		
		// 인코딩 타입(첨부파일시...)
		$enctype = empty ( $envconf ["attachfile"] ) && empty ( $envconf ["attachfile"] ) && empty ( $envconf ["attachfile"] ) ? '' : "enctype=multipart/form-data";
		
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing=1 cellpadding=2>\n");
		echo ("\n<!-- inputform design -->\n");
		echo ("<form name='inputform' method='post' action='$this->prog' $enctype>\n");
		echo ("<input type='hidden' name='db'   value='$this->db'>\n");
		echo ("<input type='hidden' name='cmd'  value='$this->mode'>\n");
		echo ("<input type='hidden' name='page' value='$this->page'>\n");
		echo ("<input type='hidden' name='id'   value='$this->id'>\n");
		echo ("<input type='hidden' name='mode' value='save'>\n");
		
		// 이름
		$this->textbox ( "name", $data ["name"], 10, 20 );
		
		// 주민번호 보이기(2001.10.08)
		if (intval ( $envconf ["mustjumin"] ))
			$this->textbox ( "jumin", $data ["jumin"], 13, 13 );
			
			// 이메일
		if (intval ( $envconf ["show_mail"] ) || intval ( $envconf ["mustmail"] ))
			$this->textbox ( "email", $data ["email"], 25, 50 );
			
			// 홈페이지
		if (intval ( $envconf ["show_homepage"] ) || intval ( $envconf ["musthome"] ))
			$this->textbox ( "homeurl", $data ["homeurl"], 25, 50 );
			
			// 기념일
		if (intval ( $envconf ["show_anniversary"] ))
			$this->textbox ( "anniversary", $data ["anniversary"], 10, 10 );
			
			// 카테고리
		if (intval ( $envconf ["show_category"] ))
			$this->selectbox ( "category", $data ["category"] );
			
			// 제목
		$this->textbox ( "subject", $data ["subject"], 40, $envconf ["maxsubj"] );
		
		// 비밀번호
		if (intval ( $envconf ["show_passwd"] ))
			$this->passwordbox ( "passwd", $data ["passwd"], 8, 8 );
			
			// 본문내용 입력
		$this->memobox ( "content", $content, 15, 55, $envconf ["maxcont"] );
		
		// 세션(첨부파일)정보(2004.03.10)
		$attachfile = $this->session->getsess ( "attachfile" );
		if (intval ( $envconf ["attachfile"] ) || $envconf ["mustattachfile"])
			$this->attachbox ( "attachfile", $attachfile, 25, 50, $envconf ["filelimit"] );
			
			// 태그문서 입력
		if (intval ( $envconf ["show_htmltype"] ))
			$this->radiobox ( "htmltype", $data ["htmltype"] );
			
			// 비공개 문서 입력
		if (intval ( $envconf ["show_privatetype"] ))
			$this->radiobox ( "privatetype", $data ["privatetype"] );
			
			// 수평 분리선
		echo ("<tr><td colspan=3><hr size=1 noshade></td></tr>\n");
		
		// 확인,취소버튼
		echo ("<tr><td colspan=3 align='center'>\n");
		echo ("<input type='button' value='$btn[ok]' class='button' onclick='check_submit(inputform)'>\n");
		echo ("<input type='button' value='$btn[cancel]' class='button' onclick='javascript:history.back()'>\n");
		echo ("</td></tr>\n");
		echo ("</form>\n");
		echo ("</table>\n");
		
		// 스크립트
		echo ("\n<!-- javascript design -->\n");
		echo ("<script language='javascript'>\n");
		echo ("<!--\n");
		
		echo ("setTimeout('inputform.name.focus()',10);\n");
		
		echo ("function init_array(size)\n");
		echo ("{\n");
		echo ("  this.length=size;\n");
		echo ("  for(i=1;i<=size;i++)this[i]='';\n");
		echo ("  return this;\n");
		echo ("}\n\n");
		
		echo ("function check_submit(obj)\n");
		echo ("{\n");
		
		echo ("  err=0;\n");
		echo ("  msg='';\n");
		echo ("  msgarray=new init_array(9);\n");
		
		// 이름 필수입력
		echo ("  if (!inputform.name.value){ msgarray[++err]='$label[name]';}\n");
		
		// 주민번호 필수입력(2001.10.08)
		if (intval ( $envconf ["mustjumin"] ))
			echo ("  if (!inputform.jumin.value){ msgarray[++err]='$label[jumin]';}\n");
			
			// 이메일 필수입력
		if (intval ( $envconf ["mustmail"] ))
			echo ("  if (!inputform.email.value){ msgarray[++err]='$label[email]';}\n");
			
			// 홈페이지 필수입력
		if (intval ( $envconf ["musthome"] ))
			echo ("  if (!inputform.homeurl.value){ msgarray[++err]='$label[homeurl]';}\n");
			
			// 제목 필수입력
		echo ("  if (!inputform.subject.value.split(' ').join('')){ msgarray[++err]='$label[subject]';}\n");
		
		// 첨부파일 필수입력
		if (intval ( $envconf ["mustattachfile"] ))
			echo ("  if (!inputform.attachfile.value){ msgarray[++err]='$label[attachfile]';}\n");
			
			// 비밀번호
		if (intval ( $envconf ["show_passwd"] ))
			echo ("  if (!inputform.passwd.value){ msgarray[++err]='$label[passwd]';}\n");
			
			// 본문내용
		echo ("  if (!inputform.content.value.split(' ').join('')){ msgarray[++err]='$label[content]';}\n");
		echo ("  if (err){\n");
		echo ("    for(i=1;i<=err;i++){\n");
		echo ("      msg=msg+msgarray[i];\n");
		echo ("      if (err!=i)msg=msg+',';\n");
		echo ("    }//end for\n");
		echo ("    alert(msg+'을(를) 반드시 입력하십시오');\n");
		echo ("  }//endif\n");
		echo ("  else if(confirm('$msg[info_save]')) {\n");
		echo ("    waiting.style.visibility = 'visible';\n");
		echo ("    obj.submit();\n");
		echo ("  }\n");
		
		echo ("}\n\n");
		
		echo ("//-->\n");
		echo ("</script>\n");
	}
	
	// 관리자 로그인 화면정의
	function adminloginform($userid, $cmd) {
		global $sysconf, $envconf, $btn, $step, $msg;
		
		// 세션(파일)
		$userid = if_empty ( $userid, '' );
		
		echo ("<br><br><br>\n");
		echo ("<center>\n");
		echo ("<table border=0 cellspacing=1 cellpadding=4>\n");
		
		echo ("\n<!-- adminloginform design -->\n");
		echo ("<form name='adminloginform' method='post' action='$this->prog' autocomplete='off'>\n");
		echo ("<input type='hidden' name='db'   value='$this->db'>\n");
		echo ("<input type='hidden' name='mode' value='$this->cmd'>\n");
		echo ("<input type='hidden' name='id'   value='$this->id'>\n");
		echo ("<input type='hidden' name='page' value='$this->page'>\n");
		echo ("<input type='hidden' name='ff'   value='$this->ff'>\n");
		echo ("<input type='hidden' name='fw'   value='$this->fw'>\n");
		echo ("<input type='hidden' name='fn'   value='$this->fn'>\n");
		echo ("<input type='hidden' name='userid' value='$userid'>\n");
		echo ("<input type='hidden' name='db_engine' value='$this->db_engine'>\n");
		echo ("<input type='hidden' name='cmd'  value='logon'>\n");
		
		// 메세지
		echo ("<tr>\n");
		$this->showtitle ( $msg ["admin_only"], "white", '#6061be', '2' );
		echo ("</tr>\n");
		
		// 형태
		echo ("<tr>\n");
		$this->showlabel ( "Access Mode", '#eeeeff', 'right', '' );
		$this->showlabel ( $step ["admin_login"], '#ffffff', 'left', '' );
		echo ("</tr>\n");
		
		switch ($cmd) {
			case 'newpass' :
				// 현재비밀번호 입력
				echo ("<tr>\n");
				$this->showlabel ( "current password", '#eeeeff' );
				echo ("<td>\n");
				$this->showinput ( "password", "passwd", '', 25, 8 );
				echo ("</td></tr>\n");
				
				// 새비밀번호 입력
				echo ("<tr>\n");
				$this->showlabel ( "new password", '#eeeeff' );
				echo ("<td>\n");
				$this->showinput ( "password", "newpass", '', 20, 8 );
				echo ("</td></tr>\n");
				
				// 새비밀번호 확인
				echo ("<tr>\n");
				$this->showlabel ( "confirm password", '#eeeeff' );
				echo ("<td>\n");
				$this->showinput ( "password", "confirm", '', 20, 8 );
				echo ("</td></tr>\n");
				break;
			
			default :
				// 비밀번호
				echo ("<tr>\n");
				$this->showlabel ( "password", '#eeeeff' );
				echo ("<td>\n");
				$this->showinput ( "password", "passwd", '', 20, 8 );
				echo ("</td></tr>\n");
				break;
		} // end switch
		  
		// 수평 분리선
		echo ("<tr><td colspan=2><hr size=1 noshade></td></tr>\n");
		
		// 확인,취소버튼
		echo ("<tr><td colspan=2 align='center'>\n");
		echo ("<input type='button' value='$btn[ok]' class='button' onclick='check_submit(adminloginform)'>\n");
		echo ("<input type='button' value='$btn[cancel]' class='button' onclick='javascript:history.back()'></td>\n");
		echo ("</td></tr>\n");
		echo ("</form>\n");
		echo ("</table>\n");
		echo ("</center>\n");
		
		echo ("<script language='javascript'>\n");
		echo ("<!--\n");
		echo ("function check_submit(obj)\n");
		echo ("{\n");
		echo ("  waiting.style.visibility = 'visible';\n");
		echo ("  obj.submit();\n");
		echo ("}\n");
		echo ("-->\n");
		echo ("</script>");
	}
	
	// 글 로그인 화면정의
	function userloginform($userid, $cmd) {
		global $sysconf, $envconf, $btn, $step, $msg;
		
		// 세션(파일)
		$userid = if_empty ( $userid, $this->sess_user ["uid"] );
		
		echo ("<br><br><br>\n");
		echo ("<center>\n");
		echo ("<table border=0 cellspacing=1 cellpadding=4>\n");
		
		echo ("\n<!-- userloginform design -->\n");
		echo ("<form name='userloginform' method='post' action='$this->prog' autocomplete='off'>\n");
		echo ("<input type='hidden' name='db'   value='$this->db'>\n");
		echo ("<input type='hidden' name='mode' value='$this->cmd'>\n");
		echo ("<input type='hidden' name='id'   value='$this->id'>\n");
		echo ("<input type='hidden' name='page' value='$this->page'>\n");
		echo ("<input type='hidden' name='ff'   value='$this->ff'>\n");
		echo ("<input type='hidden' name='fw'   value='$this->fw'>\n");
		echo ("<input type='hidden' name='fn'   value='$this->fn'>\n");
		echo ("<input type='hidden' name='userid' value='$userid'>\n");
		echo ("<input type='hidden' name='cmd'  value='logon'>\n");
		
		// 메세지
		echo ("<tr>\n");
		$this->showtitle ( "$msg[member_only]", "white", '#6061be', '2' );
		echo ("</tr>\n");
		
		// 형태
		echo ("<tr>\n");
		$this->showlabel ( "Access Mode", '#eeeeff', 'right', '' );
		$this->showlabel ( $step [$cmd], '#ffffff', 'left', '' );
		echo ("</tr>\n");
		
		switch ($cmd) {
			case 'find_pass' :
				// 비밀번호를 찾기위해 이메일을 입력받는다.
				echo ("<tr>\n");
				$this->showlabel ( "email", '#eeeeff' );
				echo ("<td>\n");
				$this->showinput ( "text", "email", '', 20, 50 );
				echo ("</td></tr>\n");
				break;
			
			case 'remote' :
			case 'read' :
			case 'down' :
			case 'modify' :
			case 'write' :
			case 'reply' :
			case 'login' :
			case 'delete' :
			case 'webmail' :
				// 회원관리
				if (intval ( $envconf ["useuser"] )) {
					echo ("<tr>\n");
					$this->showlabel ( 'uid', '#eeeeff' );
					echo ("<td>\n");
					$this->showinput ( "text", "userid", $userid, 20, 8 );
					echo ("</td></tr>\n");
				} // endif
			
			default :
				// 비밀번호
				echo ("<tr>\n");
				$this->showlabel ( "password", '#eeeeff' );
				echo ("<td>\n");
				$this->showinput ( "password", "passwd", '', 20, 8 );
				echo ("</td></tr>\n");
				break;
		} // end switch
		  
		// 수평 분리선
		echo ("<tr><td colspan=2><hr size=1 noshade></td></tr>\n");
		
		// 확인,취소버튼
		echo ("<tr><td colspan=2 align='center'>\n");
		echo ("<input type='button' value='$btn[ok]' class='button' onclick='check_submit(userloginform)'>\n");
		echo ("<input type='button' value='$btn[cancel]' class='button' onclick='javascript:history.back()'></td>\n");
		echo ("</td></tr>\n");
		echo ("</form>\n");
		echo ("</table>\n");
		echo ("</center>\n");
		
		echo ("<script language='javascript'>\n");
		echo ("<!--\n");
		echo ("function check_submit(obj)\n");
		echo ("{\n");
		echo ("  waiting.style.visibility = 'visible';\n");
		echo ("  obj.submit();\n");
		echo ("}\n");
		echo ("-->\n");
		echo ("</script>");
	}
	
	// 회원 검사하기
	function usercheck() {
		// 비밀번호 검사하기
		// 세션파일이 없을경우
		// 사용자파일이 없을경우
		return (! empty ( $this->sess_user ) && is_equal ( $this->session->getsess ( "userid" ), $this->sess_user ["uid"] ) && is_equal ( $this->session->getsess ( "passwd" ), $this->sess_user ["passwd"] ));
	}
	
	// 비밀번호 검사하기
	function checkpassword($userid, $passwd) {
		global $envconf;
		
		// 글 파일 읽기(수정예정)
		$temp = $this->session->getsess ( "data" );
		$data = if_exists ( $temp, $this->id, $this->obj->loaddatafile ( $this->db, $this->id ) );
		
		// 글 비밀번호 확인하기
		switch ($this->mode) {
			// 글 읽기권한
			case 'remote' :
			case 'read' :
			case 'down' :
				// 관리자 권한
				if (intval ( $envconf ["access_read"] ))
					return (is_equal ( $passwd, $envconf ["admin_password"] ));
					// 비공개문서 기능(2001.04.23)
				else if (intval ( $data ["privatetype"] ))
					return (is_equal ( $passwd, $data ["passwd"] ) || is_equal ( $passwd, $envconf ["admin_password"] ));
					// 회원 권한
				else if (intval ( $envconf ["useuser"] ))
					return $this->usercheck ();
				break;
			
			// 답변글 쓰기권한
			case 'reply' :
				// 관리자 권한
				if (intval ( $envconf ["access_reply"] ))
					return (is_equal ( $passwd, $envconf ["admin_password"] ));
					// 회원 권한
				else if (intval ( $envconf ["useuser"] ))
					return $this->usercheck ();
				break;
			
			// 글 쓰기권한
			case 'write' :
				// 관리자 권한
				if (intval ( $envconf ["access_write"] ))
					return (is_equal ( $passwd, $envconf ["admin_password"] ));
					// 회원 권한
				else if (intval ( $envconf ["useuser"] ))
					return $this->usercheck ();
				break;
			
			// 글 수정권한
			case 'modify' :
				// 관리자 권한
				if (intval ( $envconf ["access_write"] ))
					return (is_equal ( $passwd, $envconf ["admin_password"] ));
					// 작성자 권한
				else if (intval ( $envconf ["show_passwd"] ))
					return (is_equal ( $passwd, $data ["passwd"] ) || is_equal ( $passwd, $envconf ["admin_password"] ));
					// 회원 권한
				else if (intval ( $envconf ["useuser"] ))
					return $this->usercheck ();
				break;
			
			// 글 삭제권한
			case 'delete' :
				// 관리자 권한
				if (intval ( $envconf ["access_write"] ))
					return (is_equal ( $passwd, $envconf ["admin_password"] ));
					// 작성자 권한
				else
					return (is_equal ( $passwd, $data ["passwd"] ) || is_equal ( $passwd, $envconf ["admin_password"] ));
				break;
			
			case 'member' : // 정보변경
			case 'webmail' :
				return $this->usercheck ();
				break;
			
			default :
				// 관리자 권한
				return (is_equal ( $passwd, $envconf ["admin_password"] ));
				break;
		} // end switch
		
		return true;
	}
	/**
	 * **********************************************************************\
	 * 박스(형)태그출력함수
	 * \***********************************************************************
	 */
	// 박스함수-텍스트박스(2002.07.13)
	function readbox($field, $data, $size = 0, $max = 0) {
		echo ("<tr>\n");
		$this->showlabel ( $field );
		echo ("<td>\n");
		$this->showoutput ( "text", $field, $data, $size, $max );
		echo ("</td></tr>\n\n");
	}
	function textbox($field, $data, $size = 10, $max = 10) {
		echo ("<tr>\n");
		$this->showlabel ( $field );
		echo ("<td>\n");
		$this->showinput ( "text", $field, $data, $size, $max );
		echo ("</td></tr>\n\n");
	}
	function passwordbox($field, $data, $size = 8, $max = 8) {
		echo ("<tr>\n");
		$this->showlabel ( $field );
		echo ("<td>\n");
		$this->showinput ( "password", $field, $data, $size, $max );
		echo ("</td></tr>\n\n");
	}
	function __memobox($field, $data, $rows = 5, $cols = 50, $max = 0) {
		global $sysconf;
		
		echo ("<tr>\n");
		$this->showlabel ( $field );
		echo ("<td>\n");
		echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
		echo "<tr>\n<td>", "\n\n";
		$this->showselect ( "fontface", "", "", "SetFont(this)" );
		$this->showselect ( "fontsize", "", "", "SetSize(this)" );
		echo "
<a href='javascript:action(\"bold\");' ><img src='$sysconf[img_image]/bold.gif' align=absmiddle></a>
<a href='javascript:action(\"italic\");' ><img src='$sysconf[img_image]/italic.gif' align=absmiddle></a>
<a href='javascript:action(\"under\");' ><img src='$sysconf[img_image]/under.gif' align=absmiddle></a>

<a href='javascript:action(\"left\");' ><img src='$sysconf[img_image]/aleft.gif' align=absmiddle></a>
<a href='javascript:action(\"center\");' ><img src='$sysconf[img_image]/center.gif' align=absmiddle></a>
<a href='javascript:action(\"right\");' ><img src='$sysconf[img_image]/aright.gif' align=absmiddle></a>

<a href='javascript:action(\"wlink\");' ><img src='$sysconf[img_image]/wlink.gif' align=absmiddle></a>
";
		echo '<iframe id="POSTEDITOR" src="about:blank" style="height:370px; width:568px;border:1 solid C4CAD1" scrolling="auto" frameborder="0" onfocus="onEditFocus(\'1\');"></iframe>';
		echo '<iframe id="POSTEDITOR2" src="about:blank" style="height:275px; width:568px;border:1 solid C4CAD1;display:none"  scrolling="auto" frameborder="0"  onfocus="onEditFocus(\'2\');"></iframe>';
		echo '<textarea name="contents" style="width:570px;height:370px;padding:10 10 10 10" class="tbox" style="display:none"></textarea>';
		
		echo "</td>\n</tr>\n</table>", "\n\n";
		
		echo ("</td></tr>\n\n");
	}
	function memobox($field, $data, $rows = 5, $cols = 50, $max = 0) {
		echo ("<tr>\n");
		$this->showlabel ( $field );
		echo ("<td>\n");
		$this->showmemo ( $field, $data, $rows, $cols, $max );
		echo ("</td></tr>\n\n");
	}
	function _memobox($field, $data, $style = 'width:570px;height:379px;padding:10 10 10 10') {
		echo ("<tr>\n");
		echo ("<td colspan=2>\n");
		
		echo "
<td>
<select name='fontface' onchange='SetFont(this)'>
<option value='돋움' selected>돋움</option>
<option value='굴림'>굴림</option>
<option value='바탕'>바탕</option>
<option value='궁서'>궁서</option>
<option value='verdana'>verdana</option>
<option value='times'>times</option>
</select>

<select name='fontsize' onchange='SetFontSize(this)'>
<option selected>크기</option>
<option value='1'>7</option>
<option value='2'>10</option>
<option value='3'>12</option>
<option value='4'>14</option>
<option value='5'>18</option>
<option value='6'>24</option>
<option value='7'>36</option>
</select>


<a href='javascript:action(\"Bold\");' ><img src='http://blogimgs.naver.com/imgs/w_icon_01.gif' align=absmiddle></a>
<a href='javascript:action(\"Underline\")'><img src='http://blogimgs.naver.com/imgs/w_icon_02.gif' align=absmiddle></a>
<a href='javascript:action(\"Italic\");'><img src='http://blogimgs.naver.com/imgs/w_icon_03.gif' align=absmiddle></a>
<a href='javascript:action(\"Strike\");'><img src='http://blogimgs.naver.com/imgs/w_icon_04.gif' align=absmiddle></a>
<img src='http://blogimgs.naver.com/imgs/w_icon_00.gif' align=absmiddle>

<a href='javascript:action(\"fontcolor\");'><img src='http://blogimgs.naver.com/imgs/w_icon_05.gif' align=absmiddle></a>
<a href='javascript:action(\"bgcolor\");'><img src='http://blogimgs.naver.com/imgs/w_icon_06.gif' align=absmiddle></a>
<a href='javascript:action(\"link\");'><img src='http://blogimgs.naver.com/imgs/w_icon_06_url.gif' align=absmiddle></a>
<img src='http://blogimgs.naver.com/imgs/w_icon_00.gif' align=absmiddle>

<a href='javascript:action(\"alignLeft\");'><img src='http://blogimgs.naver.com/imgs/w_icon_07.gif' align=absmiddle></a>
<a href='javascript:action(\"alignCenter\");'><img src='http://blogimgs.naver.com/imgs/w_icon_08.gif' align=absmiddle></a>
<a href='javascript:action(\"alignRight\");'><img src='http://blogimgs.naver.com/imgs/w_icon_09.gif' align=absmiddle></a>
<img src='http://blogimgs.naver.com/imgs/w_icon_00.gif' align=absmiddle>

</td>
";
		echo ("</td></tr>\n\n");
		// echo("<textarea name='$field' style='$style' class=memobox>$data</textarea>\n");
		$this->showmemo ( $field, $data, $style );
	}
	function checkbox($field, $data = false, $help = false) {
		echo ("<tr>\n");
		$this->showlabel ( $field );
		echo ("<td>\n");
		$this->showcheck ( $field, $data, $help );
		echo ("</td></tr>\n\n");
	}
	function radiobox($field, $data, $help = '') {
		echo ("<tr>\n");
		$this->showlabel ( $field );
		echo ("<td>\n");
		$this->showradio ( $field, $data, $help );
		echo ("</td></tr>\n\n");
	}
	function selectbox($field, $data, $help = '', $onchange = '') {
		echo ("<tr>\n");
		$this->showlabel ( $field );
		echo ("<td>\n");
		$this->showselect ( $field, $data, $help, $onchange );
		echo ("</td></tr>\n\n");
	}
	function filebox($field, $data, $size = 0, $max = 0, $limit = 0) {
		global $menu;
		
		$max_file_name = $field . '_limit';
		$max_file_size = ($limit) << 10;
		
		echo ("<input type='hidden' name='$max_file_name' value='$max_file_size'>\n");
		echo ("<tr>\n");
		$this->showlabel ( $field );
		echo ("<td>\n");
		$this->showinput ( "file", $field, $data, $size, $max );
		echo ("<font class=hint>($limit kbyte $menu[below])</font>\n");
		echo ("</td></tr>\n");
	}
	function linebox($width = 100, $colspan = 0, $bgcolor = '') {
		global $envconf;
		
		if (intval ( $width ) > 0 && intval ( $width ) < 101)
			$width .= '%';
		$backcolor = empty ( $bgcolor ) ? "" : "bgcolor='$bgcolor'";
		
		echo ("<tr>\n");
		echo ("<td width='$width' $backcolor colspan=$colspan><p>\n");
		echo ("<hr color='$envconf[defcolor]' size=1 noshade></td>\n");
		echo ("</tr>\n");
	}
	function attachbox($field, $data, $size = 0, $max = 0, $limit = 0) {
		global $btn;
		
		echo ("<tr>\n");
		$this->showlabel ( $field );
		echo ("<td>\n");
		echo ("<select name='$field' size=6>\n");
		echo ("<option selected value='-1'>---------- 첨부할 파일목록 -----------</option>\n");
		foreach ( $data as $key => $val )
			echo ("<option value='$key'>$val[name]($val[size]byte)</option>\n");
		echo ("</select>\n");
		echo ("<input type='button' value='$btn[add]/$btn[del]' class='button' onclick='javascript:attachwindow(\"$field\")'>\n");
		echo ("</td></tr>\n\n");
	}
}
?>

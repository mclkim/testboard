<?php
/************************************************************************\
 * 프로그램명 : 화면정의(회원관리)
 * 특기사항   : 1.회원목록 보기(2001.05.25)
                2.회원공개내용 보기(2001.05.25)
                3.자동메일링 기능(예정)
                4.회원등록후 결과를 메일로 전송하기(예정)
                5.비밀번호 보내기(2001.05.25)
                6.회원정보변경(예정)
                7.그룹단위회원관리(예정)
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/04
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1list.php');
class form1member extends form1list {
	// 환경설정 초기값 재정의
	function resetconfig() {
		global $sysconf, $envconf;
		
		$envconf ["useuser"] = 1; // 회원관리
		
		$envconf ["show_list"] = 0; // 본문 밑으로 문서목록 보이기
		$envconf ["show_findmenu"] = 0; // 검색메뉴 보이기
		$envconf ["show_sortmenu"] = 0; // 정렬버튼 보이기
	}
	
	// 자바스크립트 재정의
	function resetjavascript() {
		global $sysconf;
		
		echo ("\n<!-- javascript design -->\n");
		echo ("<script language='javascript' src='$sysconf[home_inc]/js/default.js'></script>\n");
		echo ("<script language='javascript' src='$sysconf[home_inc]/js/calendar.js'></script>\n");
	}
	
	// 목록(회원정보) 보이기
	function testboardlist() {
		$this->htmlheader ();
		$this->columnheader ();
		
		$this->showlist ( $this->db, $this->index );
		$this->showpagemenu ( $this->db );
		$this->showlistbutton ( $this->db );
		
		$this->columnbottom ();
		$this->htmlbottom ();
	}
	
	// 본문(회원정보) 입력하기
	function testboardinput() {
		$this->htmlheader ();
		$this->columnheader ();
		
		$this->inputform ();
		
		$this->columnbottom ();
		$this->htmlbottom ();
	}
	
	// 회원등록 화면정의
	function inputform() {
		global $envconf, $btn, $msg, $label, $def_user;
		
		// 회원파일 읽기
		$data = a4b ( $def_user, $this->sess_user );
		
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='1' cellpadding='2'>\n");
		echo ("\n<!-- inputform design -->\n");
		echo ("<form name='inputform' method='post' action='$this->prog'>\n");
		echo ("<input type='hidden' name='db'   value='$this->db'>\n");
		echo ("<input type='hidden' name='cmd'  value='$this->mode'>\n");
		echo ("<input type='hidden' name='page' value='$this->page'>\n");
		echo ("<input type='hidden' name='id'   value='$this->id'>\n");
		echo ("<input type='hidden' name='mode' value='admin_save'>\n");
		
		// 아이디
		switch ($this->mode) {
			case 'member' :
				$this->readbox ( "uid", $data ["uid"], 10, 20 );
				break;
			
			default :
				$this->textbox ( "uid", $data ["uid"], 25, 50 );
				break;
		} // end switch
		  
		// 이름
		$this->textbox ( "name", $data ["name"], 10, 20 );
		
		// 이메일
		$this->textbox ( "email", $data ["email"], 25, 50 );
		
		// 메일링
		$this->radiobox ( "mailing", $data ["mailing"] );
		
		// 홈페이지
		$this->textbox ( "homeurl", $data ["homeurl"], 25, 50 );
		
		// 주민번호
		$this->textbox ( "jumin", $data ["jumin"], 13, 13 );
		
		// 성별
		$this->radiobox ( "sex", $data ["sex"] );
		
		// 생년월일
		$this->textbox ( "birth", $data ["birth"], 10, 10 );
		
		// 연락처
		$this->textbox ( "phone", $data ["phone"], 20, 20 );
		
		// 우편번호
		$this->textbox ( "post", $data ["post"], 10, 10 );
		
		// 주소
		$this->textbox ( "address", $data ["address"], 50, 50 );
		
		// 사용환경
		$this->textbox ( "os", $data ["os"], 20, 20 );
		
		// 근무처(학교)
		$this->textbox ( "work", $data ["work"], 20, 20 );
		
		// 부서(학력)
		$this->textbox ( "dept", $data ["dept"], 20, 20 );
		
		// 비밀번호
		$this->passwordbox ( "passwd", $data ["passwd"], 8, 8 );
		$this->passwordbox ( "repasswd", $data ["passwd"], 8, 8 );
		
		// 사용권한
		$this->readbox ( "private", $data ["private"], 10, 10 );
		$this->readbox ( "in_date", $data ["in_date"], 20, 20 );
		
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
		
		echo ("function initarray(size)\n");
		echo ("{\n");
		echo ("  this.length=size;\n");
		echo ("  for(i=1;i<=size;i++)this[i]='';\n");
		echo ("  return this;\n");
		echo ("}\n\n");
		
		echo ("function check_submit(obj)\n");
		echo ("{\n");
		echo ("  err=0;\n");
		echo ("  msg='';\n");
		echo ("  msgarray=new initarray(5);\n");
		echo ("  if (document.inputform.uid.value==''){ err++;msgarray[err]='$label[uid]';}\n");
		echo ("  if (document.inputform.name.value==''){ err++;msgarray[err]='$label[name]';}\n");
		echo ("  if (document.inputform.email.value==''){ err++;msgarray[err]='$label[email]';}\n");
		echo ("  if (document.inputform.passwd.value==''){ err++;msgarray[err]='$label[passwd]';}\n");
		echo ("  if (document.inputform.repasswd.value==''){ err++;msgarray[err]='$label[repasswd]';}\n");
		echo ("  if (err){\n");
		echo ("    for(i=1;i<=err;i++)\n");
		echo ("    {\n");
		echo ("      msg=msg+msgarray[i];\n");
		echo ("      if (err!=i)msg=msg+',';\n");
		echo ("    }//end for\n");
		echo ("    alert(msg+'을(를) 반드시 입력하십시오');\n");
		echo ("  }//endif\n");
		echo ("  else if(confirm('$msg[info_save]')){\n");
		echo ("  waiting.style.visibility = 'visible';\n");
		echo ("  obj.submit();}\n");
		echo ("}\n\n");
		
		echo ("function check_uid(obj)\n");
		echo ("{\n");
		echo ("  if(!obj.value)\n");
		echo ("    {alert('$label[uid]'+'을(를) 반드시 입력하십시오');}\n");
		echo ("  else{\n");
		echo ("    theurl='$this->prog?db=$this->db&mode=check_id&userid='+obj.value;\n");
		echo ("    messagewindow(theurl);\n");
		echo ("  }//end else\n");
		echo ("}\n\n");
		
		echo ("//-->\n");
		echo ("</script>\n");
	}
	
	// 회원변경 화면정의
	function configform() {
		global $envconf, $btn, $label, $hint, $def_user;
		
		// 회원파일 읽기
		$data = a4b ( $def_user, $this->sess_user );
		
		// 폼(inputform)이름을 refer 화면을 위해 고정해야 한다.
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='1' cellpadding='2'>\n");
		echo ("\n<!-- inputform design -->\n");
		echo ("<form name='inputform' method='post' action='$this->prog'>\n");
		echo ("<input type='hidden' name='db'   value='$this->db'>\n");
		echo ("<input type='hidden' name='cmd'  value='$this->mode'>\n");
		echo ("<input type='hidden' name='page' value='$this->page'>\n");
		echo ("<input type='hidden' name='id'   value='$this->id'>\n");
		echo ("<input type='hidden' name='mode' value='admin_save'>\n");
		
		// 회원정보
		foreach ( $data as $key => $val ) {
			$size = strlen ( $val ) ? strlen ( $val ) + 1 : "";
			
			// 키인 경우
			if ($key == uid)
				$this->readbox ( $key, $val, 10, 10 );
				// 코드형인 경우
			else if (is_array ( $hint [$key] ))
				$this->radiobox ( $key, $val );
				// 일반형인 경우
			else
				$this->textbox ( $key, $val, $size, $size );
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
	
	// 회원정보 메일보내기
	function findpassword($email) {
		global $sysconf, $envconf;
		
		if (empty ( $email ))
			return 0; // false
				          
		// 파일 찾기 시작
		$common_query = "$sysconf[findcmd] email=$email " . path_fix ( "$sysconf[path_users]/*.cgi" );
		$rows = runshell ( $common_query );
		
		// 파일이 없는지?
		if (! $userid = basename ( $rows [0] ))
			return 0; // false
				          
		// 회원파일 읽기
		$data = $this->loaduserfile ( $userid );
		if (empty ( $data ))
			return 0; // false
				          
		// 메시지의 본문
				          // 보안노출때문에 임의의 세션을 전달후 비교하기(2002.08.13)
		$sid = $this->session->sid;
		$content = url2text ( "$sysconf[path_home]/$sysconf[testmember]?db=$this->db&mode=print&userid=$userid&sid=$sid" );
		
		// 개체 인스턴스를 작성한다.
		$mail = new mime ();
		
		// 모든 데이터 슬롯들을한다.
		$mail->from = empty ( $envconf ["admin_mail"] ) ? "" : $envconf ["admin_mail"];
		$mail->subject = empty ( $envconf ["boardtitle"] ) ? "" : $envconf ["boardtitle"];
		$mail->to = empty ( $data ["email"] ) ? "$email" : $data ["email"];
		$mail->_attach ( $content, "", "text/html" );
		
		// 메일서버(smtp)가 없을경우
		if (empty ( $envconf ["smtp_server"] ))
			return $mail->send ();
			
			// 개체 인스턴스를 작성한다.
		$smtp = new smtp ( $envconf ["smtp_server"] );
		
		// 전자메일을 보낸다.
		return $smtp->send ( $mail->from, $mail->to, $mail->_mail () );
	}
	
	// 목록(회원정보) 보이기
	function showlist($db, $index) {
		global $sysconf, $envconf, $btn, $label, $hint;
		
		// 홀짝줄에 대한 색상
		$listbkcol = array (
				$envconf ["listbkcolodd"],
				$envconf ["listbkcoleven"] 
		);
		$listtxtcol = array (
				$envconf ["listtxtcolodd"],
				$envconf ["listtxtcoleven"] 
		);
		
		// 목록 인덱스
		$previdx = max ( 0, $this->current_page - 1 ) * $this->pageline + 1;
		$nextidx = min ( $this->total_page, $this->current_page ) * $this->pageline;
		$nextidx = min ( count ( $index ), $nextidx );
		
		// 글 출력 시작
		echo ("\n<!-- showlist design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='1' cellpadding='2'>\n");
		echo ("<tr align=center bgcolor='$envconf[listheadbkcol]'>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[no]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[uid]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[name]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[in_date]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[ip]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>e</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>h</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>p</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>m</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>qy</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>pt</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$btn[modify]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$btn[delete]</font></th>\n");
		echo ("</tr>\n");
		
		// 목록시작
		// 인덱스는 '0'부터 시작한다.
		for($idx = $previdx - 1, $bg = 1; $idx < $nextidx; $idx ++, $bg = ! $bg) {
			// 목록인덱스에서 글파일명을 찾아서...
			$row = $this->index2head ( $index, $idx );
			
			// 회원정보 파일 읽기
			if (! $data = $this->loaduserfile ( $row ))
				continue;
			
			extract ( $data );
			
			echo ("\n<!-- $idx -->\n");
			echo ("<tr align=center bgcolor='$listbkcol[$bg]'>\n");
			
			// 순서
			$ii = $idx + 1;
			echo ("<td><font color='$listtxtcol[$bg]'>$ii</font></td>\n");
			
			// user id
			echo ("<td><font color='$listtxtcol[$bg]'>\n");
			echo ("<a href='$this->prog?db=$this->db&mode=read&page=$this->page&userid=$uid'>$uid</a>\n");
			echo ("</font></td>\n");
			
			// 이름
			echo ("<td><font color='$listtxtcol[$bg]'>$name</font></td>\n");
			echo ("<td><font color='$listtxtcol[$bg]'>$in_date</font></td>\n");
			echo ("<td><font color='$listtxtcol[$bg]'>$ip</font></td>\n");
			// 이메일
			$link = is_email ( $email ) ? print_email ( $email ) : $email;
			echo ("<td><font color='$listtxtcol[$bg]'>$link</font></td>\n");
			
			// homeurl
			$link = is_home ( $homeurl ) ? "<a href='$homeurl'>h</a>" : "";
			echo ("<td><font color='$listtxtcol[$bg]'>$link</font></td>\n");
			
			// private
			$link = $hint ["private"] [$private];
			echo ("<td><font color='$listtxtcol[$bg]'>$link</font></td>\n");
			
			// mailing
			$link = intval ( $mailing ) ? "y" : "";
			echo ("<td><font color='$listtxtcol[$bg]'>$link</font></td>\n");
			
			// 방문횟수
			echo ("<td><font color='$listtxtcol[$bg]'>$querycount</font></td>\n");
			echo ("<td><font color='$listtxtcol[$bg]'>$point</font></td>\n");
			
			// 회원수정,회원삭제
			echo ("<td><a href='$this->prog?db=$this->db&mode=admin_modify&userid=$uid'>$btn[modify]</a></td>\n");
			echo ("<td><a href='$this->prog?db=$this->db&mode=admin_delete&userid=$uid'>$btn[delete]</a></td>\n");
			echo ("</tr>\n");
		} // end for
		
		echo ("</table>\n");
	}
	
	// 본문(회원정보) 보이기
	function showdata($db, $id) {
		global $sysconf, $envconf, $label, $hint, $def_user;
		
		// 회원파일 읽기
		$data = a4b ( $def_user, $this->sess_user );
		
		// 본문 시작
		echo ("\n<!-- showdata design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='1' cellpadding='2'>\n");
		
		// 메시지의 본문
		foreach ( $data as $key => $val ) {
			echo ("<tr>\n");
			echo ("<th width='20%' bgcolor='$envconf[dataheadbkcol]' align='right'>\n");
			echo ("<font color='$envconf[defcolor]'>$label[$key]&nbsp;</font></th>\n");
			echo ("<td align=left>\n");
			echo ("<font color='$envconf[datatxtcol]'>&nbsp;$val&nbsp;</font>\n");
			
			// 코드값
			if (is_array ( $hint [$key] ))
				$temp = "(" . $hint [$key] [$val] . ")";
				// 테그생략
			else
				$temp = strip_tags ( $hint [$key] );
			
			echo ("<font class=hint>&nbsp;&nbsp;$temp&nbsp;&nbsp;</font>\n");
			echo ("</td></tr>\n");
		} // end foreach
		
		echo ("</td></tr>\n");
		echo ("</table>\n");
	}
}
?>

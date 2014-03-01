<?
/************************************************************************\
 * 프로그램명 : 스킨파일(설문조사하기)
 * 특기사항   : 1.제목을 길게 작성하기(예정)
                2.손쉬운 생성 기능.
                3.ip 및 쿠키 사용으로 복수투표 방지 기능.
                4.자동 종료시간을 출력.(예정)
                5.설문 투표 현황 실시간 집계 및 확인.
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/03
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1board.php');

// <주의>파일이름과 클래스이름을 일치해야...
class skin1poll extends form1board {
	// 환경설정 초기값 재정의
	function resetconfig() {
		global $sysconf, $envconf, $btn;
		
		// 스킨에서 사용한 이미지(image)파일의 상대위치
		$sysconf ["skin_image"] = ("$sysconf[home_skins]/" . get_class ( $this ));
		
		$envconf ["access_write"] = 1; // 글 쓰기권한
		$envconf ["attachfile"] = 0; // 첨부파일 가능여부
		$envconf ["show_htmltype"] = 0; // 태그문서 입력
		
		$btn ["admin"] = "<b>admin</b>";
		$btn ["blank"] = "&nbsp;&nbsp;";
		$btn ["reply"] = "";
	}
	
	// 설문조사하기
	function testboardpoll() {
		global $envconf;
		
		switch ($this->cmd) {
			case 'remote' :
				$envconf ["tablewidth"] = '100%'; // 임시
				
				$this->htmlheader ();
				$this->columnheader ();
				$this->showpoll ( $this->db, $this->id );
				$this->columnbottom ();
				$this->htmlbottom ();
				break;
			
			case 'read' :
				$this->htmlheader ();
				$this->userheader ();
				$this->columnheader ();
				if ($envconf ["contbuttonpos"] & 1)
					$this->showdatabutton ( $this->db, $this->id );
				$this->showpoll ( $this->db, $this->id );
				if ($envconf ["contbuttonpos"] & 2)
					$this->showdatabutton ( $this->db, $this->id );
				$this->columnbottom ();
				$this->userbottom ();
				$this->htmlbottom ();
				break;
			
			default :
				$this->htmlerror ( $msg ["err_no_param"] );
				break;
		} // end switch
	}
	
	// 임시
	function index2array($index) {
		if (empty ( $index ))
			return null;
		
		foreach ( $index as $v ) {
			// line mustn't start with a ';' and must contain at least one '=' symbol.
			if ((substr ( trim ( $v ), 0, 1 ) != ';') && (substr_count ( $v, '=' ) >= 1)) {
				$pos = strpos ( $v, '=' );
				$config [trim ( substr ( $v, 0, $pos ) )] = trim ( substr ( $v, $pos + 1 ) );
			} // end if
		} // end foreach
		unset ( $index );
		
		return count ( $config ) > 0 ? $config : null;
	}
	
	// 설문지 보이기
	function showdata($db, $id) {
		global $sysconf, $envconf, $btn;
		
		// 마지막 등록한 인덱스 읽어오기
		if (empty ( $id ) || ($id = to_index ( $id )) < 0)
			$id = $this->index2head ( $this->index, 0 );
			
			// 글 파일 읽기
		if (! $data = $this->data [$id])
			$data = $this->obj->loaddatafile ( $db, $id );
		
		extract ( $data );
		
		// 제목
		$subject = htmlspecialchars ( $data ["subject"] );
		
		// 제목이 긴경우 줄여서 표시
		$subject = kstrcut ( $subject, $envconf ["short_subject"] );
		
		// 본문내용 파일 읽기
		$content = $this->obj->loadcontentfile ( $data, $db, $id );
		
		// 본문 시작
		echo ("\n<!-- showdata design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		
		// 타이틀,관리자모드 연결하기
		echo ("<tr align=center>\n");
		echo ("<td background='$sysconf[skin_image]/poll_bg1.gif' height='24'>\n");
		echo ("<a href=\"javascript:remotewindow('$sysconf[testadmin]?db=$db&mode=config')\">$btn[admin]</a>\n");
		echo ("</td></tr>\n");
		
		// 제목
		echo ("<tr align=center>\n");
		echo ("<td bgcolor='$envconf[databkcol]' height='24'>\n");
		echo ("<font color='$envconf[datatxtcol]'><b>$subject</b></font>\n");
		echo ("</td></tr>\n");
		
		// 내용
		echo ("\n<!-- quickpollform design -->\n");
		echo ("<form name='quickpollform' method='post' action='$this->prog'>\n");
		echo ("<input type='hidden' name='db'   value='$db'>\n");
		echo ("<input type='hidden' name='cmd'  value='$this->mode'>\n");
		echo ("<input type='hidden' name='page' value='$this->page'>\n");
		echo ("<input type='hidden' name='id'   value='$this->id'>\n");
		echo ("<input type='hidden' name='mode' value='poll'>\n");
		
		$buffer = explode ( chr ( 10 ), $content );
		$buffer = index2array ( $buffer );
		
		// 설문내용 출력
		foreach ( array_keys ( $buffer ) as $key => $val ) {
			echo ("<tr align=left>\n");
			echo ("<td><input type='radio' name='poll' value='$key'>&nbsp;&nbsp;$val");
			echo ("</td></tr>\n");
		} // end foreach
		
		echo ("<tr><td>&nbsp;&nbsp;</td></tr>\n");
		echo ("<tr align=left>\n");
		echo ("<td><input type='image' name='submit' src='$sysconf[skin_image]/poll_bg2.gif' style='position:relative;' onload='return set_button()' border=0 align=absmiddle hspace=1 border=0 alt='투표합니다.'>\n");
		echo ("</td></tr>\n");
		echo ("</form>\n");
		echo ("</table>\n");
	}
	
	// 설문결과 보이기
	function showpoll($db, $id) {
		global $sysconf, $envconf, $btn;
		
		// 마지막 등록한 인덱스 읽어오기
		if (empty ( $id ) || ($id = to_index ( $id )) < 0)
			$id = $this->index2head ( $this->index, 0 );
			
			// 글 파일 읽기
		if (! $data = $this->data [$id])
			$data = $this->obj->loaddatafile ( $db, $id );
		
		extract ( $data );
		
		// 제목
		$subject = htmlspecialchars ( $data ["subject"] );
		
		// 제목이 긴경우 줄여서 표시
		$subject = kstrcut ( $subject, $envconf ["short_subject"] );
		
		// 본문내용 파일 읽기
		$content = $this->obj->loadcontentfile ( $data, $db, $id );
		
		// 본문 시작
		echo ("\n<!-- showpoll design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		
		// 타이틀,관리자모드 연결하기
		echo ("<tr align=center>\n");
		echo ("<td background='$sysconf[skin_image]/poll_bg1.gif' height='24'>\n");
		echo ("<a href=\"javascript:remotewindow('$sysconf[testadmin]?db=$db&mode=config')\">$btn[admin]</a>\n");
		echo ("</td></tr>\n");
		
		// 제목
		echo ("<tr align=center>\n");
		echo ("<td bgcolor='$envconf[databkcol]' height='24'>\n");
		echo ("<font color='$envconf[datatxtcol]'><b>$subject</b></font>\n");
		echo ("</td></tr>\n");
		
		$buffer = explode ( chr ( 10 ), $content );
		$buffer = index2array ( $buffer );
		$sum = array_sum ( $buffer );
		
		// 내용
		echo ("<tr align=center>\n");
		echo ("<td bgcolor='$envconf[databkcol]'>\n");
		
		// //////////////////설문내용////////////////////
		echo ("\n<!-- showpoll design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		echo ("<tr><td>&nbsp;</td></tr>\n");
		
		$ii = 1;
		// 설문내용 출력
		foreach ( $buffer as $key => $val ) {
			$rate = round ( $val * 100 / $sum );
			echo ("<tr>\n");
			echo ("<td valign=top>&nbsp;&nbsp;" . ($ii ++) . ".&nbsp;&nbsp;$key</td>\n");
			echo ("<td valign=top><img src='$sysconf[skin_image]/poll_bg3.gif' width='$rate' height='10' border='0'>\n");
			echo ("<font color=#330099><b>$val</b></font>표(<font color=#990099><b>$rate</b></font>%)</td>\n");
			echo ("</tr>\n");
		} // end foreach
		
		$in_date = date ( longdateformat, $in_time );
		$mo_date = date ( longdateformat, $mo_time );
		echo ("<tr><td height=5></td></tr>\n");
		echo ("<tr>\n");
		echo ("<td height=5 ></td>\n");
		echo ("<td height=5 align=right><small>총 $sum 명이 참여하셨습니다. ($in_date~$mo_date) </small></td>\n");
		echo ("</tr>\n");
		
		echo ("<tr><td colspan=2 align=center>\n");
		echo ("<input type='button' value='$btn[back]' class='button' onclick='history.go(-1)'>\n");
		echo ("<input type='button' value='$btn[close]' class='button' onclick='self.close()'>\n");
		echo ("</td></tr>\n");
		echo ("</table>\n");
		// //////////////////설문내용////////////////////
		echo ("</td></tr>\n");
		echo ("</table>\n");
	}
	
	// 설문조사결과 기록하기
	function rollhitcount($poll) {
		// 본문내용 파일 읽기
		$content = $this->obj->loadcontentfile ( $data, $this->db, $this->id );
		
		$buffer = explode ( chr ( 10 ), $content );
		$buffer = index2array ( $buffer );
		
		$ii = 0;
		$content = '';
		foreach ( $buffer as $key => $val ) {
			if (isset ( $poll ) && ($poll == $ii ++)) {
				unset ( $poll );
				$val = intval ( $val ) + 1;
			} // endif
			$content .= "$key=$val\n";
		} // end foreach
		  
		// 본문내용 파일 쓰기
		$this->obj->savecontentfile ( $content, $this->db, $this->id );
	}
}
?>

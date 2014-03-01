<?php
/************************************************************************\
 * 프로그램명 : 게시판(기본스킨)
 * 특기사항   : 1.글 찾기(정렬)메뉴 보이기
                2.다운로드시 회원전용에서 화면전환 버그(예상)
                3.그림 미리보기 기능(2001.07.04)
                4.의견달기 기능(2001.09.06)
                *.의견달기 파일분리(2002.02.28)
                5.답변글보기(2002.03.12)
                6.접속통계 기능(2001.10.17)
                7.선택글 모두보기 기능(2003.04.01)
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/01
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1form.php');
class form1list extends form1form {
	// 현재 페이지 보이기
	function showpage() {
		global $sysconf, $envconf, $menu, $btn;
		
		// 페이지 보이기(0:disable,1:enable)
		if (empty ( $envconf ["show_pagemenu"] ))
			return;
		
		echo ("<base href='" . http_url . "/'>\n");
		echo ("<base target='_self'>\n");
		
		// 전체글수/현재페이지
		echo ("\n<!-- showpage design -->\n");
		echo ("<table width='100%' border='0' cellspacing='0' cellpadding='2'>\n");
		echo ("<tr>\n");
		
		// 관리자 버튼
		echo ("<td align=left class=small><nobr>\n");
		
		// 관리자
		echo ("<a href='$sysconf[testadmin]?db=$this->db&mode=config'>$btn[admin]</a>\n");
		
		// 로그인&회원가입&로그아웃
		if (intval ( $envconf ["useuser"] )) {
			// 빈칸
			echo ("$btn[blank]\n");
			
			// 로그인 세션이 있을경우
			// 회원정보 변경시 회원id 전달해야(수정예정)
			// 예전에는 쿠키세션을 사용하여 쿠키값으로 회원정보를 확인할수 있었슴.
			if (isset ( $this->sess_user )) {
				print_link ( "$this->prog?db=$this->db&mode=logout", $btn ["logout"] );
				echo ("&nbsp\n");
				print_popup_link ( "$sysconf[testmember]?db=$this->db&mode=member&userid=" . $this->sess_user ["uid"], $btn ["member"] );
			} else {
				print_link ( "$this->prog?db=$this->db&mode=login", $btn ["login"] );
				echo ("&nbsp\n");
				print_popup_link ( "$sysconf[testmember]?db=$this->db&mode=join", $btn ["join"] );
			} // end if else
		} // end if
		echo ("</nobr></td>\n");
		
		echo ("<td align=right class=small><nobr>\n");
		echo ("$menu[total]<font color=red><b>$this->total_data</b></font>&nbsp;\n");
		echo ("$menu[page]<font color=red><b>$this->current_page</b></font>\n");
		echo ("/<font color=black><b>$this->total_page</b></font>&nbsp;\n");
		
		// 일별접속통계 기능(2001.10.17)
		// 어제,오늘접속수 출력(예정)
		// 오늘등록글수 출력(예정)
		print_popup_link ( "$this->prog?db=$this->db&mode=stat", $menu ["access"] );
		echo ("<font color=black><b>" . $this->access ["hit"] . "</b></font>&nbsp;\n");
		echo ("</nobr></td></tr>\n");
		echo ("</table>\n");
	}
	
	// 목록 보이기 버튼
	function showlistbutton($db, $mode = "list") {
		global $sysconf, $envconf, $btn;
		
		// 이전페이지 버튼 & 다음페이지 버튼
		$prevpage = max ( 1, $this->current_page - 1 );
		$nextpage = min ( $this->total_page, $this->current_page + 1 );
		
		echo ("<base href='" . http_url . "/'>\n");
		echo ("<base target='_self'>\n");
		
		echo ("\n<!-- showlistbutton design -->\n");
		echo ("<table width='100%' border='0' cellspacing='0' cellpadding='2'>\n");
		echo ("<tr>\n");
		echo ("<td align=left><nobr>\n");
		
		// 글쓰기 버튼
		echo ("<a href='$this->prog?db=$db&mode=write'>$btn[write]</a>\n");
		
		// 빈칸
		echo ("$btn[blank]\n");
		
		// 이전페이지 버튼 & 다음페이지 버튼
		echo ("<a href='$this->prog?db=$db&mode=$mode&page=$prevpage&id=$this->id&ff=$this->ff&fw=$this->fw'>$btn[prev]</a>\n");
		echo ("<a href='$this->prog?db=$db&mode=$mode&page=$nextpage&id=$this->id&ff=$this->ff&fw=$this->fw'>$btn[next]</a>\n");
		
		// 빈칸
		echo ("$btn[blank]\n");
		
		// 목록버튼
		echo ("<a href='$this->prog?db=$db&mode=list'>$btn[list]</a>\n");
		echo ("</nobr></td>\n");
		
		// 글 찾기메뉴 보이기
		$this->showfindmenu ();
		
		echo ("</tr>\n");
		echo ("</table>\n");
	}
	
	// 글 찾기(정렬)메뉴 보이기
	function showfindmenu() {
		global $envconf, $btn, $label;
		
		// 찾기(정렬)메뉴 보이기
		if (empty ( $envconf ["show_findmenu"] ) && empty ( $envconf ["show_sortmenu"] ))
			return;
			
			// 검색 도구
		echo ("\n<!-- showfindmenu design -->\n");
		echo ("<td align=right><nobr>\n");
		echo ("<form name='find' method='post' action='$this->prog'>\n");
		echo ("<input type='hidden' name='db'   value='$this->db'>\n");
		echo ("<input type='hidden' name='cmd'  value='$this->mode'>\n");
		echo ("<input type='hidden' name='page' value='$this->page'>\n");
		echo ("<input type='hidden' name='id'   value='$this->id'>\n");
		echo ("<input type='hidden' name='mode' value='find'>\n");
		echo ("<input type='hidden' name='ff'   value='all'>\n");
		
		echo ("<select name=ff>\n");
		$findlist = explode ( ",", $envconf ["findlist"] );
		
		for(reset ( $findlist ); list ( , $key ) = each ( $findlist );) {
			$selected = ($key == $this->ff) ? "selected" : "";
			echo ("<option value='$key' $selected>$label[$key]</option>\n");
		} // end for
		echo ("</select>\n");
		
		$this->showinput ( 'text', 'fw', $this->fw, 12, 40 );
		
		// 검색버튼
		if (intval ( $envconf ["show_findmenu"] ))
			$this->showbutton ( $btn ["find"], "find_check()" );
			
			// 정렬버튼
		if (intval ( $envconf ["show_sortmenu"] ))
			$this->showbutton ( $btn ["sort"], "sort_check()" );
		
		echo ("</nobr></td>\n");
		echo ("</form>\n");
		
		// 스크립트
		echo ("\n<!-- javascript design -->\n");
		echo ("<script language='javascript'>\n");
		echo ("<!--\n");
		
		echo ("function find_check()\n");
		echo ("{\n");
		echo ("  if (!find.fw.value.split(' ').join(''))\n");
		echo ("  alert('검색단어를 입력하시오');\n");
		echo ("  else{\n");
		echo ("  waiting.style.visibility = 'visible';\n");
		echo ("  document.find.submit();}\n");
		echo ("}\n\n");
		
		echo ("function sort_check()\n");
		echo ("{\n");
		echo ("  waiting.style.visibility = 'visible';\n");
		echo ("  document.find.submit();\n");
		echo ("}\n\n");
		
		echo ("//-->\n");
		echo ("</script>\n");
	}
	
	// 답변글보기(2002.03.12)
	function showreplyfile($db, $id) {
		// 글 파일 읽기
		if (! $data = $this->data [$id])
			$data = $this->obj->loaddatafile ( $db, $id );
			
			// 답변글만 다시 쿼리하기
		$this->index = $this->obj->findreplyfile ( $db, $data ["ppid"] );
	}
	
	// 본문 보이기 버튼
	function showdatabutton($db, $id) {
		global $sysconf, $envconf, $btn;
		
		echo ("<base href='" . http_url . "/'>\n");
		echo ("<base target='_self'>\n");
		
		echo ("\n<!-- showdatabutton design -->\n");
		echo ("<table width='100%' border='0' cellspacing='0' cellpadding='2'>\n");
		echo ("<tr>\n");
		echo ("<td align=left><nobr>\n");
		
		// 목록버튼
		echo ("<a href='$this->prog?db=$db&mode=list'>$btn[list]</a>\n");
		
		// 빈칸
		echo ("$btn[blank]\n");
		
		// 글 쓰기
		echo ("<a href='$this->prog?db=$db&mode=write&id=$id&page=$this->page'>$btn[write]</a>\n");
		
		// 답변글 쓰기
		// 공지사항에는 답변글을 달수가 없슴
		if ($this->head2index ( $this->note, $id ) >= 0)
			$btn ["reply"] = "<testboard>";
		echo ("<a href='$this->prog?db=$db&mode=reply&id=$id&page=$this->page'>$btn[reply]</a>\n");
		
		// 수정버튼
		echo ("<a href='$this->prog?db=$db&mode=modify&id=$id&page=$this->page'>$btn[modify]</a>\n");
		
		// 삭제버튼
		echo ("<a href='$this->prog?db=$db&mode=delete&id=$id&page=$this->page'>$btn[delete]</a>\n");
		echo ("</nobr></td>\n");
		
		echo ("<td align=right><nobr>\n");
		
		// 인쇄하기버튼(2001.10.30)
		// 보안노출때문에 임의의 세션을 전달후 비교하기(2002.08.13)
		$sid = $this->session->sid;
		echo ("<a href=\"javascript:printwindow('$this->prog?db=$db&mode=print&id=$id&sid=$sid')\">$btn[print]</a>\n");
		
		// 메일보내기버튼
		echo ("<a href='$sysconf[testmail]?db=$db&mode=forward&id=$id&page=$this->page'>$btn[forward]</a>\n");
		
		// 빈칸
		echo ("$btn[blank]\n");
		
		// 전화면버튼,창닫기버튼
		echo ("<a href='javascript:history.back()'>$btn[back]</a>\n");
		
		echo ("</nobr></td></tr>\n");
		echo ("</table>\n");
	}
	
	// 페이지 이동메뉴 보이기
	function showpagemenu($db, $mode = "list") {
		global $envconf;
		
		// 페이지 이동메뉴 보이기
		if (empty ( $envconf ["show_pagemenu"] ))
			return;
		$pres = max ( 1, $this->current_page - (($this->current_page - 1) % $this->tabsperpage) );
		$nexs = min ( $pres + $this->tabsperpage - 1, $this->total_page );
		
		echo ("\n<!-- showpagemenu design -->\n");
		echo ("<table width='100%' border='0' cellspacing='0' cellpadding='2'>\n");
		echo ("<tr>\n");
		echo ("<td align=center class=small><nobr>\n");
		
		// 맨 처음 페이지
		if ($this->current_page == 1)
			echo ("<b>[◀]</b>\n");
		else
			echo ("<a href='$this->prog?db=$db&mode=$mode&id=$this->id&page=1&ff=$this->ff&fw=$this->fw'>[◀]</a>\n");
			
			// 이동할 페이지의 가장 처음이 1 페이지보다 클 경우
		if ($pres > 1)
			echo ("<a href='$this->prog?db=$db&mode=$mode&id=$this->id&page=" . ($pres - 1) . "&ff=$this->ff&fw=$this->fw'>...</a>\n");
			
			// 중간 페이지
		for($ii = $pres; $ii <= $nexs; $ii ++) {
			if ($this->current_page == $ii)
				echo ("<b id=o>[$ii]</b>\n");
			else
				echo ("<a href='$this->prog?db=$db&mode=$mode&id=$this->id&page=$ii&ff=$this->ff&fw=$this->fw'>[$ii]</a>\n");
		} // end for
		  
		// 이동할 페이지의 가장 끝이 마지막 페이지보다 작을 경우
		if ($nexs < $this->total_page)
			echo ("<a href='$this->prog?db=$db&mode=$mode&id=$this->id&page=" . ($nexs + 1) . "&ff=$this->ff&fw=$this->fw'>...</a>\n");
			
			// 맨 끝 페이지
		if ($this->current_page == $this->total_page)
			echo ("<b>[▶]</b>\n");
		else
			echo ("<a href='$this->prog?db=$db&mode=$mode&id=$this->id&page=$this->total_page&ff=$this->ff&fw=$this->fw'>[▶]</a>\n");
		
		echo ("</nobr></td></tr>\n");
		echo ("</table>\n");
	}
	
	// 접속통계 기능(2001.10.17)
	function showlogstat($index) {
		global $sysconf, $envconf;
		
		if (empty ( $index ))
			return;
		
		$dan = 2;
		$dan_width = "50%"; // 단의 넓이
		
		$hit_month = array ();
		$max_month = array ();
		
		$mm = $mx = 0;
		$month = 0;
		
		foreach ( $index as $key => $val ) {
			list ( $today, $idx, $cnt, $hit, $to, $min, $max ) = explode ( "|", $val );
			
			// 임시(날짜가없으면)
			if (strtodate ( $today ) < 1)
				continue;
				
				// 월별리스트
			if ($month != substr ( $today, 0, 7 )) {
				if ($month)
					$max_month [$month] = $mx;
				if ($month)
					$hit_month [$month] = $hit - $to - $mm;
				$month = substr ( $today, 0, 7 );
				// 어제까지전체방문수=전체방문수-오늘방문수
				$mm = $hit - $to;
				// 최대값기본값
				// $mx=$to;
				$mx = 0;
			} // endif
			  
			// 최대값구하기
			$mx = max ( $mx, $to );
		} // end foreach
		
		$month = substr ( $today, 0, 7 );
		$max_month [$month] = $mx;
		$hit_month [$month] = $hit - $mm;
		
		$ii = 0;
		$month = 0;
		
		// 방문횟수를일자별로 출력하기
		echo ("\n<!-- showlogstat design -->\n");
		echo ("<table width='100%' border='0' cellspacing='0' cellpadding='2'>\n");
		
		// 단 나누기
		for($i = 0; $i < $dan; $i ++)
			echo ("<col width='$dan_width'></col>\n");
			
			// 단그리기
		foreach ( $index as $key => $val ) {
			list ( $today, $idx, $cnt, $hit, $to, $min, $max ) = explode ( "|", $val );
			
			// 임시(날짜가없으면)
			if (strtodate ( $today ) < 1)
				continue;
				
				// 월별리스트
			if ($month != substr ( $today, 0, 7 )) {
				$month = substr ( $today, 0, 7 );
				$mm = $hit_month [$month];
				$mx = $max_month [$month];
				
				// 단나누기
				if ($ii != 0)
					echo ("</table></td><!-- a -->\n\n");
					
					// 단나누기 시작
				if ($ii % $dan == 0 && $ii != 0)
					echo ("</tr><!-- b -->\n");
				if ($ii % $dan == 0)
					echo ("<tr bgcolor='white'><!-- b -->\n");
				
				echo ("\n<!-- $ii -->\n");
				echo ("<td align='center' valign='top' class='babel'><!-- d -->\n");
				// //////////////////단시작////////////////////
				echo ("<table width=100%><!-- e -->\n");
				$ii ++;
			} // endif
			
			$rate = round ( $to * 100 / $mm );
			$width = round ( $to * intval ( $dan_width ) / $mx ) - 8;
			
			echo ("  <tr><td class=small>$today\n");
			echo ("  <img src='$sysconf[img_image]/poll.gif' width='$width%' height='10' border='0'>\n");
			echo ("  <font color=#330099><b>$to</b></font>&nbsp;(<font color=#990099><b>$rate</b></font>%)\n");
			echo ("  </td></tr><!-- f -->\n");
		} // end foreach
		
		if ($ii != 0)
			echo ("</table></td><!-- a -->\n\n");
			
			// 뒷공백
		for($i = $ii; $i % $dan != 0; $i ++)
			echo ("<td bgcolor='eeeeee' class='babel'>&nbsp;</td>\n");
		
		echo ("</tr></table><!-- b -->\n");
	}
	
	// 글 로그인 화면정의
	function loginform() {
		global $sysconf, $envconf, $btn;
		
		if (intval ( $envconf ["useuser"] ))
			return;
			
			// 로그인 세션이 있을경우
		if ($this->sess_user) {
			echo ("<table width='100%' border='0' cellspacing='0' cellpadding='2'>\n");
			echo ("\n<!-- loginform design -->\n");
			echo ("<form name='loginform' method='post' action='$this->prog' autocomplete='off'>\n");
			echo ("<input type='hidden' name='db'   value='$this->db'>\n");
			echo ("<input type='hidden' name='page' value='$this->page'>\n");
			echo ("<input type='hidden' name='id'   value='$this->id'>\n");
			echo ("<input type='hidden' name='cmd'  value='$this->mode'>\n");
			echo ("<input type='hidden' name='mode' value='logout'>\n");
			
			$help = "logon:&nbsp;" . $this->sess_user ["name"] . "(" . $this->sess_user ["email"] . ")님 환영합니다.";
			echo ("<tr>\n");
			echo ("<td bgcolor='#73a2ea' valign=bottom style='font-size:9pt'>\n");
			echo ("<font class=hint>&nbsp;$help&nbsp;</font>\n");
			
			echo ("<input type='button' value='$btn[logout]' class='button' onclick='submit()'>\n");
			echo ("<input type='button' value='$btn[member]' class='button' onclick=remotewindow('$sysconf[testmember]?db=$this->db&mode=member')>\n");
			
			echo ("</td></tr>\n");
			echo ("</form>\n");
			echo ("</table>\n");
		} else {
			echo ("<table width='100%' border='0' cellspacing='0' cellpadding='2'>\n");
			echo ("\n<!-- loginform design -->\n");
			echo ("<form name='loginform' method='post' action='$this->prog' autocomplete='off'>\n");
			echo ("<input type='hidden' name='db'   value='$this->db'>\n");
			echo ("<input type='hidden' name='page' value='$this->page'>\n");
			echo ("<input type='hidden' name='id'   value='$this->id'>\n");
			echo ("<input type='hidden' name='cmd'  value='$this->mode'>\n");
			echo ("<input type='hidden' name='mode' value='login'>\n");
			
			echo ("<tr>\n");
			echo ("<td bgcolor='#73a2ea' valign=bottom style='font-size:9pt'>\n");
			echo ("<font color=black>&nbsp;id&nbsp;</font>\n");
			$this->showinput ( 'text', 'userid', '', 10, 10 );
			echo ("<font color=black>&nbsp;password&nbsp;</font>\n");
			$this->showinput ( 'password', 'passwd', '', 8, 8 );
			
			echo ("<input type='button' value='$btn[login]' class='button' onclick='submit()'>\n");
			echo ("<input type='button' value='$btn[join]' class='button' onclick=remotewindow('$sysconf[testmember]?db=$this->db&mode=join')>\n");
			echo ("<input type='button' value='$btn[findpass]' class='button' onclick=remotewindow('$sysconf[testmember]?db=$this->db&mode=find_pass')>\n");
			
			echo ("</td></tr>\n");
			echo ("</form>\n");
			echo ("</table>\n");
		} // endif else
	}
}
?>

<?
/************************************************************************\
 * 프로그램명 : 스킨파일(블로그)
 * 특기사항   :
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2004/05
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1board.php');

// <주의>파일이름과 클래스이름을 일치해야...
class skin1blog extends form1board {
	// 환경설정 초기값 재정의
	function resetconfig() {
		global $sysconf, $envconf;
		
		$sysconf ["skin_image"] = "$sysconf[home_skins]/" . get_class ( $this );
		
		$envconf ["tablewidth"] = '800'; // 게시판 너비
		$envconf ["pageline"] = "5"; // 한 페이지당 출력할 목록 갯수
		$envconf ["show_category"] = 1; // 카테고리
	}
	
	// 전체 목록 보이기
	function testboardlist() {
		global $envconf, $btn;
		global $year, $month;
		
		$this->htmlheader ();
		$this->userheader ();
		echo ("\n<!-- testboardlist design -->\n");
		echo ("<table width='$envconf[tablewidth]' border='0' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]' bordercolor='$envconf[bordercolor]'>\n");
		echo ("<tr valign='top'>\n");
		echo ("<td width='175' align='center'>\n");
		
		// 달력
		echo ("<table width='175' border='0' cellpadding='3' cellspacing='0' bgcolor='$envconf[dataheadbkcol]'>\n");
		echo ("<tr><td>\n");
		echo ("  <table width='169' border='0' cellpadding='6' cellspacing='0' bgcolor='#ffffff' style='border:1px solid $envconf[titlebkcol]' >\n");
		echo ("  <tr><td>\n");
		echo ("          <table width='155' border='0' cellpadding='0' cellspacing='0' style='table-layout:fixed'>\n");
		echo ("          <tr><td align='center'>\n");
		$this->category ( $year, $month );
		$this->calendar ( $year, $month );
		// $this->showfindmenu();
		echo ("          </td></tr>\n");
		echo ("          </table>\n");
		echo ("  </td></tr>\n");
		echo ("  </table>\n");
		echo ("</td></tr>\n");
		echo ("</table>\n");
		
		// 방문횟수
		// echo("<table width='100%' border='0' cellpadding='4' cellspacing='0'>\n");
		// echo("<tr>\n");
		// echo("<td><div align='center'><span class='eng'>today 106 / total 4010</span></div></td>\n");
		// echo("</tr>\n");
		// echo("</table>\n");
		echo ("\n<!-----------------------left_menu 부분----------------------------------->\n");
		
		echo ("</td>\n");
		
		// 포스트
		$tablewidth = empty ( $envconf ["tablewidth"] ) ? "" : $envconf ["tablewidth"] - 175;
		echo ("<td width='$tablewidth' valign='top'>\n");
		echo ("\n<!-----------------------main_content 부분----------------------------------->\n");
		$this->showlist ( $this->db, $this->data );
		$this->showpagemenu ( $this->db );
		echo ("</td></tr>\n");
		
		echo ("</table>\n");
		
		$this->userbottom ();
		$this->htmlbottom ();
	}
	function category($year, $month) {
		global $sysconf, $envconf;
		
		$year = if_empty ( $year, yy () );
		$month = if_empty ( $month, mm () );
		
		$first = mktime ( 0, 0, 0, $month, 1, $year );
		$cal_year = date ( 'Y', $first );
		$cal_month = date ( 'm', $first );
		
		$style = 'cursor:hand;word-break:break-all;';
		
		echo ("<div id='category'>\n");
		echo ("<table width='155' border='0' cellspacing='0' cellpadding='0'>\n");
		
		$alink = "$this->prog?db=$this->db&mode=write&page=$this->page&id=$this->id&ff=&fw=&year=$cal_year&month=$cal_month";
		echo ("<tr>\n");
		echo ("<td align='left' onclick='location.href=\"$alink\"' style='$style'>");
		print_image ( 'ico_note.gif', $sysconf ["skin_image"], '', '', "vspace='5' hspace='5' align='absmiddle'" );
		echo ("글남기기</td></tr>\n");
		
		$alink = "$this->prog?db=$this->db&mode=list&page=$this->page&id=$this->id&ff=&fw=&year=$cal_year&month=$cal_month";
		echo ("<tr>\n");
		echo ("<td align='left' onclick='location.href=\"$alink\"' style='$style'>");
		print_image ( 'ico_note.gif', $sysconf ["skin_image"], '', '', "vspace='5' hspace='5' align='absmiddle'" );
		echo ("전체보기</td></tr>\n");
		
		echo ("<tr>\n");
		echo ("<td background='$sysconf[skin_image]/bg_dot02.gif'></td>\n");
		echo ("</tr>\n");
		
		$category = explode ( ",", $envconf ["category"] );
		for(reset ( $category ); list ( , $item ) = each ( $category );) {
			$alink = "$this->prog?db=$this->db&mode=list&page=$this->page&id=$this->id&ff=category&fw=$item&year=$cal_year&month=$cal_month";
			
			echo ("<tr>\n");
			echo ("<td align='left' onclick='location.href=\"$alink\"' style='$style'>");
			print_image ( 'ico_note.gif', $sysconf ["skin_image"], '', '', "vspace='5' hspace='5' align='absmiddle'" );
			echo ("$item</td></tr>\n");
		} // end for
		
		echo ("<tr>\n");
		echo ("<td background='$sysconf[skin_image]/bg_dot02.gif'></td>\n");
		echo ("</tr>\n");
		
		echo ("</table>\n");
		echo ("</div>\n");
	}
	
	// 달력
	function calendar($year, $month) {
		global $sysconf, $envconf;
		
		$day_color = array (
				'red',
				'black',
				'black',
				'black',
				'black',
				'black',
				'blue' 
		);
		$num_dayname = array (
				0,
				1,
				2,
				3,
				4,
				5,
				6 
		);
		$han_dayname = array (
				'일',
				'월',
				'화',
				'수',
				'목',
				'금',
				'토' 
		);
		$eng_dayname = array (
				'S',
				'M',
				'T',
				'W',
				'T',
				'F',
				'S' 
		);
		
		$year = if_empty ( $year, yy () );
		$month = if_empty ( $month, mm () );
		
		$first = mktime ( 0, 0, 0, $month, 1, $year );
		$cal_year = date ( 'Y', $first );
		$cal_month = date ( 'm', $first );
		
		$prev = mktime ( 0, 0, 0, ($cal_month - 1), 1, $cal_year );
		$prev_year = date ( 'Y', $prev );
		$prev_month = date ( 'm', $prev );
		
		$next = mktime ( 0, 0, 0, ($cal_month + 1), 1, $cal_year );
		$next_year = date ( 'Y', $next );
		$next_month = date ( 'm', $next );
		
		$todate = date ( "Y/m/d" );
		$week = date ( "w", $first );
		$days_month = date ( "t", $first );
		
		// 해당월에 내용 읽기
		$table = $this->obj->findfile ( $this->db, "in_date", "$cal_year/$cal_month" );
		
		// 목록 출력하기
		echo ("\n<!-- calendar design -->\n");
		echo ("<div id='calendar'>\n");
		echo ("<table width='150' border='0' cellspacing='0' cellpadding='0'>\n");
		echo ("<tr><td>\n");
		
		echo ("<table width='100%' border='0' cellspacing='0' cellpadding='4' style='0' align='center'>\n");
		
		// 년월
		$style = 'cursor:hand;font-size:11px;background:#ffffff';
		echo ("<tr align='center'><td>&nbsp</td>\n");
		$alink = "$this->prog?db=$this->db&mode=list&page=$this->page&id=$this->id&ff=$this->ff&fw=$this->fw&year=$prev_year&month=$prev_month";
		
		echo ("<td onclick='location.href=\"$alink\"' style=$style>");
		print_image ( 'ico_arrow_l.gif', $sysconf ["skin_image"], '', '', "vspace='2' hspace='2' align='absmiddle'" );
		echo ("</td>");
		
		$alink = "$this->prog?db=$this->db&mode=list&page=$this->page&id=$this->id&ff=in_date&fw=$cal_year/$cal_month&year=$cal_year&month=$cal_month";
		echo ("<td align='center' colspan=3 onclick='location.href=\"$alink\"' style=$style><font size='2'><b><u>$cal_year<font color='#FF6600'>$cal_month</font></u></b></font></td>");
		
		$alink = "$this->prog?db=$this->db&mode=list&page=$this->page&id=$this->id&ff=$this->ff&fw=$this->fw&year=$next_year&month=$next_month";
		echo ("<td onclick='location.href=\"$alink\"' style=$style>");
		print_image ( 'ico_arrow_r.gif', $sysconf ["skin_image"], '', '', "vspace='2' hspace='2' align='absmiddle'" );
		echo ("</td>");
		echo ("<td>&nbsp</td></tr>\n");
		
		// 요일
		echo ("<tr align=center bgcolor='$envconf[listheadbkcol]'>\n");
		for($i = 0; $i < 7; $i ++) {
			echo ("<td height=13 align='center' valign='top'>\n");
			$font_color = $day_color [$i];
			echo ("<font color=$font_color style='font-size:11px'>$han_dayname[$i]</font>");
			echo ("</td>\n");
		} // end for
		echo ("</tr>\n");
		
		// 첫번째 주에서 빈칸을 1일전까지 빈칸을 삽입
		echo "<tr>\n";
		for($i = 0; $i < $week; $i ++)
			echo ("<td>&nbsp;</td>\n");
		
		for($cal_day = 1; $cal_day <= $days_month; $cal_day ++) {
			// 단설정
			echo ("\n<!-- $cal_day -->\n");
			if ($week != 0 && $week % 7 == 0)
				echo "<tr><!--단시작-->\n";
			
			echo ("<td height=13 align='center' valign='top'>\n");
			// //////////////////단시작////////////////////
			$font_color = $day_color [$week % 7];
			$style = 'font-size:11px;';
			$alink = '#';
			
			// 오늘 표시
			$cal_date = sprintf ( "%04d/%02d/%02d", $cal_year, $cal_month, $cal_day );
			if (is_array ( $table ) && array_key_exists ( $cal_date, $table )) {
				$style = 'cursor:hand;font-size:11px;font-weight:bold;text-decoration:underline;';
				$alink = "$this->prog?db=$this->db&mode=list&page=$this->page&id=$this->id&ff=in_date&fw=$cal_date&year=$cal_year&month=$cal_month";
			} 			// end if
			elseif ($todate == $cal_date)
				$style = 'font-size:11px;font-weight:bold;';
			
			echo ("<table width='100%' border='0' cellspacing='0' cellpadding='0'>\n");
			echo ("<tr>\n");
			echo ("<td align='center' onclick='location.href=\"$alink\"' style=$style><font color=$font_color>$cal_day</font></td>");
			echo ("</tr>\n");
			echo ("</table>\n");
			// //////////////////단끝////////////////////
			echo ("</td>\n");
			
			// 단설정
			$week ++;
			if ($week != 0 && $week % 7 == 0)
				echo "</tr><!--단끝-->\n";
		} // end for
		  
		// 선택한 월의 마지막날 이후의 빈테이블 삽입
		for($i = $week; $i % 7 != 0; $i ++)
			echo ("<td>&nbsp;</td>\n");
		
		echo ("</tr>\n");
		echo ("</table>\n");
		
		echo ("</td></tr>\n");
		echo ("</table>\n");
		echo ("</div>\n");
	}
	
	// 목록 보이기
	function showlist($db, $index) {
		global $sysconf, $envconf, $btn, $label;
		
		// 글 목록 번호
		$num = $this->total_data - max ( 0, $this->current_page - 1 ) * $this->pageline + 1;
		
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
		
		// 목록시작
		foreach ( $index as $key => $data ) {
			extract ( $data );
			$num --;
			
			$bg = ($num & 1);
			
			// 본문내용 파일 읽기
			$content = $this->obj->loadcontentfile ( $data, $db, $key );
			
			// htmltype에 따라
			switch (intval ( $htmltype )) {
				case 0 :
					// 특수문자들을 변환
					$content = htmlspecialchars ( trim ( $content ) );
					
					// 본문내에 url이 있으면 자동 인식
					if (intval ( $envconf ["autolink"] ))
						$content = hyperlink ( $content );
						
						// cr/lf 처리 옵션
					if (intval ( $envconf ["entertype"] ))
						$content = nl2br ( $content );
					break;
				
				case 1 :
					break;
				
				case 2 :
					$content = nl2br ( $content );
					break;
			} // end switch
			
			echo ("\n<!-- $key -->\n");
			echo ("<table width='100%' border='0' cellspacing='0' style='border:1px solid #DCDCDC'>\n");
			
			echo ("<tr>\n");
			echo ("<td style='padding:2 8 8 8px' colspan='2'>\n");
			
			echo ("<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n");
			echo ("<tr>\n");
			
			// 목록 제목
			// 제목이 긴경우 줄여서 표시
			$subject = kstrcut ( $subject, $envconf ["short_subject"] );
			echo ("<td style='padding: 11 0 10 9'><span class='subject'>$subject</span>\n");
			echo ("<span class='hint'><b>|</b></span>\n");
			echo ("<a href='$this->prog?db=$this->db&mode=list&page=$this->page&id=$id&ff=category&fw=$category'><span class='hint'>$category</span></a>\n");
			echo ("<a href='$this->prog?db=$this->db&mode=delete&page=$this->page&id=$id&ff=$this->ff&fw=$this->fw'><img src='$sysconf[skin_image]/btn_del04.gif' width=21 height=9 align=absmiddle style=margin-left:2;margin-bottom:2 border=0 alt='포스트 삭제'></a>\n");
			echo ("</td>\n");
			
			// 글 작성날짜
			$in_date = date ( longdateformat, $in_time );
			echo ("<td align='right' class='small' style='padding: 0 10 0 0' nowrap>$in_date</td>\n");
			echo ("</tr>\n");
			
			// 구분
			echo ("<tr><td colspan='2' background='$sysconf[skin_image]/bg_dot.gif' height='1'></td></tr>\n");
			
			// (pid)링크
			if ($pid > 0) {
				echo ("<tr>\n");
				echo ("<td style='padding: 0 10 0 0' align='right' colspan='2' height='20'><a href='$this->prog?db=$this->db&mode=list&page=$this->page&id=$id&ff=ppid&fw=$ppid' target='_parent'><span class='hint'>" . http_url . "/$db/$pid</span></a></td>\n");
				echo ("</tr>\n");
			} // end if
			  
			// (작성자)출처
			$name = is_email ( $email ) ? $name . "&nbsp(" . print_email ( $email ) . ")" : $name;
			echo ("<tr>\n");
			echo ("<td style='padding: 0 10 0 0' align='right' colspan='2' height='20'><span class='hint'>$label[name]:$name</span></td>\n");
			echo ("</tr>\n");
			
			echo ("<tr>\n");
			echo ("<td align='right' colspan='2' style='padding: 0 10 0 0'>\n");
			echo ("</td>\n");
			echo ("</tr>\n");
			
			// 내용보이기
			echo ("<tr>\n");
			echo ("<td width='100%' style='padding: 15 10 35 10' colspan='2' class='normal'>\n");
			
			// 본문내용
			echo ("<table width='100%' bgcolor='$envconf[databkcol]'><tr><td><font color='$envconf[datatxtcol]'>$content</font></td></tr></table>\n");
			
			// 첨부이미지파일(2004.03.10)
			if (intval ( $envconf ["attachfile"] ) && is_array ( $attachfile )) {
				foreach ( $attachfile as $key => $value ) {
					// 파일이름이 한글인 경우 오류발생(2001.12.21)
					$image = urlencode ( $value ["name"] );
					if (is_image ( $image ))
						print_image ( $image, $sysconf ["db_image"], $value ["name"], '', '', '', 550 );
					echo ("<p>\n");
				} // end foreach
			} // endif
			
			echo ("</td></tr>\n");
			
			// 글수정/글삭제
			echo ("<tr><td colspan='2' style='padding: 0 10 0 10' align='right'>\n");
			echo ("<span style=''>\n");
			echo ("</span>\n");
			echo ("<span algin='right'>\n");
			echo ("<a href='$this->prog?db=$db&mode=modify&id=$key&page=$this->page&ff=$this->ff&fw=$this->fw'>$btn[modify]</a> |\n");
			echo ("<a href='$this->prog?db=$db&mode=delete&id=$key&page=$this->page&ff=$this->ff&fw=$this->fw' style='color:#E20000'>$btn[delete]</a>\n");
			echo ("</span>\n");
			echo ("</td></tr>\n");
			
			echo ("<tr><td colspan='2' height='10'></td></tr>\n");
			
			echo ("</table>\n");
			echo ("</td>\n");
			echo ("</tr>\n");
			echo ("</table>\n");
			
			echo ("<table width='100%'  border='0' cellpadding='0' cellspacing='0'><tr><td height='9'></td></tr></table>\n");
		} // end for
	}
}
?>

<?
/************************************************************************\
 * 프로그램명 : 스킨파일(스케줄)
 * 특기사항   :
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2003/04
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1board.php');

// <주의>파일이름과 클래스이름을 일치해야...
class skin1calendar extends form1board {
	var $week_name = array (
			"<font color=red>일</font>",
			"월",
			"화",
			"수",
			"목",
			"금",
			"<font color=blue>토</font>",
			"<font color=red>일</font>",
			"월",
			"화",
			"수",
			"목",
			"금",
			"<font color=blue>토</font>" 
	);
	var $cellh = 90;
	var $cellw = 120;
	var $dan = 7;
	var $dan_width = "14%"; // 단의 넓이
	                        
	// 환경설정 초기값 재정의
	function resetconfig() {
		global $sysconf, $envconf, $btn;
		global $year, $month;
		
		$sysconf ["skin_image"] = ("$sysconf[home_skins]/skin1calendar");
		
		$envconf ["cellspacing"] = 0; // 셀 간격
		$envconf ["cellpadding"] = 2;
		$envconf ["show_anniversary"] = 1; // 기념일
		$envconf ["show_findmenu"] = 0;
		$envconf ["show_sortmenu"] = 0;
		$envconf ["pageline"] = 0; // 한 페이지당 출력할 목록 갯수
		
		$btn ["next"] = "<testboard>";
		$btn ["prev"] = "<testboard>";
		
		$this->dan = max ( $this->dan, $envconf ["dan_size"] ); // 단의 갯수
		$this->dan_width = round ( 100 / $this->dan ) . '%'; // 단의 넓이
		                                                     
		// 오늘 날짜를 년,월로 구하기
		$year = if_empty ( $year, yy () );
		$month = if_empty ( $month, mm () );
		
		$this->ff = "anniversary";
		$this->fw = sprintf ( "%04d/%02d", $year, $month );
	}
	function showpagemenu($db, $mode = "list") {
		global $envconf;
		
		list ( $year, $month ) = explode ( "/", $this->fw );
		
		// 오늘 날짜를 년,월로 구하기
		$year = if_empty ( $year, yy () );
		$month = if_empty ( $month, mm () );
		
		$prev = mktime ( 0, 0, 0, ($month - 1), 1, $year );
		$next = mktime ( 0, 0, 0, ($month + 1), 1, $year );
		
		echo ("\n<!-- showpagemenu design -->\n");
		echo ("<table width='100%' border='0' cellspacing='0' cellpadding='2'>\n");
		echo ("<tr>\n");
		echo ("<th align=center class=small><nobr>\n");
		
		echo ("<a href='$this->prog?db=$db&mode=$mode&year=" . date ( "Y", $prev ) . "&month=" . date ( "n", $prev ) . "'>◀이전달</a>\n");
		echo "<b>$year 년 $month 월</b>\n";
		echo ("<a href='$this->prog?db=$db&mode=$mode&year=" . date ( "Y", $next ) . "&month=" . date ( "n", $next ) . "'>다음달▶</a>\n");
		
		echo ("</nobr></th></tr>\n");
		echo ("</table>\n");
	}
	
	// 목록 보이기
	function showlist($db, $index) {
		global $sysconf, $envconf;
		
		list ( $year, $month ) = explode ( "/", $this->fw );
		
		// 오늘 날짜를 년,월로 구하기
		$year = if_empty ( $year, yy () );
		$month = if_empty ( $month, mm () );
		
		// 해당월에 행사내용 읽기
		$table = array ();
		foreach ( $index as $item )
			$table [$item [$this->ff]] [] = $item;
		
		$first = mktime ( 0, 0, 0, $month, 1, $year );
		
		$count = date ( "w", $first ); // $count는 <tr>태그를 넘기기 위한 변수.
		$total_days = date ( "t", $first );
		
		// 목록 출력하기
		echo ("\n<!-- showlist design -->\n");
		echo ("<table width='100%' border='0' cellspacing='0' cellpadding='2'>\n");
		
		// 단 나누기
		for($i = 0; $i < $this->dan; $i ++)
			echo ("<col width='$this->dan_width'></col>\n");
			
			// 요일
		echo ("<tr align=center bgcolor='$envconf[listheadbkcol]'>\n");
		for($i = 0; $i < $this->dan; $i ++)
			echo ("<th align='center' valign='top' class='babel'>" . $this->week_name [$i] . "</th>\n");
		echo ("</tr>\n");
		
		// 첫번째 주에서 빈칸을 1일전까지 빈칸을 삽입
		echo "<tr>\n";
		for($i = 0; $i < $count; $i ++)
			echo ("<td bgcolor='eeeeee' class='babel'>&nbsp;</td>\n");
			
			// 달력그리기
		for($day = 1; $day <= $total_days; $day ++) {
			// 단설정
			echo ("\n<!-- $day -->\n");
			if ($count != 0 && $count % $this->dan == 0)
				echo "<tr>\n";
			
			echo ("<td align='center' valign='top' class='babel'>\n");
			// //////////////////단시작////////////////////
			echo ("<table width=100% border=0 cellspacing=0 cellpadding=0>\n");
			echo ("<tr valign='top'>\n");
			echo ("<td height=$this->cellh>\n");
			
			// 요일별 색지정
			switch ($count % 7) {
				case 0 : // 일요일
					echo ("<font color=red><b>$day</b></font>");
					break;
				case 6 : // 토요일
					echo ("<font color=blue><b>$day</b></font>");
					break;
				default : // 평일
					echo ("<font color=black><b>$day</b></font>");
					break;
			} // end switch
			  
			// 오늘 표시(예정)
			$todate = sprintf ( "%04d/%02d/%02d", $year, $month, $day );
			if (is_array ( $table ) && array_key_exists ( $todate, $table ))
				$this->showlocallist ( $db, $table [$todate] );
			
			echo ("</td></tr>\n");
			echo ("</table>\n");
			// //////////////////단끝////////////////////
			echo ("</td>\n");
			
			// 단설정
			$count ++;
			if ($count != 0 && $count % $this->dan == 0)
				echo "</tr>\n";
		} // end for
		  
		// 선택한 월의 마지막날 이후의 빈테이블 삽입
		for($i = $count; $i % $this->dan != 0; $i ++)
			echo ("<td bgcolor='eeeeee' class='babel'>&nbsp;</td>\n");
		
		echo ("</tr>\n");
		echo ("</table>\n");
	}
	
	// 서브 목록 보이기
	function showlocallist($db, $index) {
		global $sysconf, $envconf, $btn;
		
		// 글 출력 시작
		echo ("\n<!-- showlocallist design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		
		// 목록시작
		foreach ( $index as $key => $data ) {
			extract ( $data );
			
			echo ("\n<!--* $key *-->\n");
			echo ("<tr onmouseover=this.style.background='lavender' onmouseout=this.style.background=''>\n");
			
			// 목록 제목
			echo ("<td align=left>\n");
			
			// 폴더 아이콘을 출력한다.
			if (intval ( $envconf ["foldericon"] )) {
				$foldericon = getfoldericon ( $data );
				print_image ( $foldericon, $sysconf ["icon_image"] );
			} // endif
			  
			// 제목이 긴경우 줄여서 표시
			$subject = kstrcut ( $subject, $envconf ["short_subject"] );
			
			echo ("<font color='$envconf[datatxtcol]'>\n");
			print_link ( "$this->prog?db=$db&mode=read&id=$id&page=$this->page&ff=$this->ff&fw=$this->fw", $subject, '', '' );
			print_image ( getstaticon ( $data ), $sysconf ["icon_image"] );
			echo ("</font>\n");
			
			echo ("</td></tr>\n");
		} // end for
		
		echo ("</table>\n");
	}
}
?>
